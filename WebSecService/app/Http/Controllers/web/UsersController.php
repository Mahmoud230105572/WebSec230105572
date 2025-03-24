<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Artisan;




class UsersController extends Controller {
    use ValidatesRequests;

    
    
    public function index(Request $request) {
        if (!auth()->check()) {
            abort(401, 'User not authenticated'); // Ensure user is logged in
        }
    
        $user = auth()->user();
    
        // Fetch only customers if the user is an employee
        if ($user->hasRole('employee')) {
            $query = User::whereHas('roles', function ($q) {
                $q->where('name', 'customer');
            });
        } else {
            // Admins can see all users
            $query = User::query();
        }
    
        // Apply search filter if input exists
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('email', 'LIKE', "%{$request->search}%")
                ->orWhereHas('roles', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->search}%");
                });
            });
        }
    
        $users = $query->paginate(10);
    
        return view('users.index', compact('users'));
    }
    
    
    
    



    public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {

        $this->validate($request, [
            'name' => ['required', 'string', 'min:4'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed',
                Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        ]);


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); //Secure

        // Assign "customer" role
        $user->assignRole('customer');


        $user->save();
        return redirect("/");
    }
    
    
    public function login(Request $request) {
        return view('users.login');
        }

        
    public function doLogin(Request $request) {

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);
        return redirect("/");
        }

    public function doLogout(Request $request) {

        Auth::logout();
        return redirect("/");
        }


        public function profile(Request $request, User $user = null) {
            $user = $user??auth()->user();
            if(auth()->id()!=$user?->id) {
                if(!auth()->user()->hasPermissionTo('show_users')) abort(401);} 


                $permissions = [];
                foreach($user->permissions as $permission) {
                    $permissions[] = $permission;
                }
                foreach($user->roles as $role) {
                    foreach($role->permissions as $permission) {
                        $permissions[] = $permission;
                    }
                }
                
                return view('users.profile', compact('user', 'permissions'));
        }

        public function edit(Request $request, User $user = null) {

            $user = $user??auth()->user();
            if(auth()->id()!=$user?->id) {
                if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
            }
            
            
            $roles = [];
            foreach(Role::all() as $role) {
                $role->taken = ($user->hasRole($role->name));
                $roles[] = $role;
            }
            
            
            $permissions = [];
            $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
            foreach(Permission::all() as $permission) {
                $permission->taken = in_array($permission->id, $directPermissionsIds);
                $permissions[] = $permission;
            }
            return view('users.edit', compact('user', 'roles', 'permissions'));
        
        }

        public function save(Request $request, User $user) {
            // Authorization: Allow user to update their own profile or require permission
            if (auth()->id() != $user->id && !auth()->user()->hasPermissionTo('edit_users')) {
                abort(403, 'Unauthorized action.');
            }
        
            $this->validate($request, [
                'name' => ['required', 'string', 'min:4'],

            ]);

        

            $user->name = $request->name;
        
            // Password update with old password verification and validation
            if ($request->filled('password')) {
                $this->validate($request, [
                    'old_password' => ['required'],
                    'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                ]);

                if (!Hash::check($request->old_password, $user->password)) {
                    return back()->withErrors(['old_password' => 'Old password is incorrect']);
                }

                $user->password = bcrypt($request->password);
            }

            if (auth()->user()->hasPermissionTo('add_credit')) {
                $this->validate($request, [
                    'account_credit' => ['required', 'numeric', 'min:0'],
                ]);
                $user->account_credit += $request->account_credit;
            }
            
            
            if(auth()->user()->hasPermissionTo('edit_users')) {
                // Only sync roles if they exist in the request
                if ($request->has('roles')) {
                    $user->syncRoles($request->roles);
                }
            
                // Only sync permissions if they exist in the request
                if ($request->has('permissions')) {
                    $user->syncPermissions($request->permissions);
                }
            
                Artisan::call('cache:clear');
            }
            

            

            $user->save();


            
            return redirect(route('profile', ['user' => $user->id]));
        }
        
}
    

