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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;





class UsersController extends Controller {
    use ValidatesRequests;

    
    
    public function index() {
        if (!auth()->check()) {
            abort(401, 'User not authenticated'); // Ensure user is logged in
        }
    
        if (!auth()->user()->hasPermissionTo('show_users')) {
            abort(403, 'User does not have permission'); // 403 is better for permission denial
        }
    
        $users = User::paginate(10);
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
        $user->save();


        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("verify", ['token' => $token]);
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

        return redirect("/");
    }
    
    
    public function login(Request $request) {
        return view('users.login');
        }

        
    public function doLogin(Request $request) {


        $user = User::where('email', $request->email)->first();

        // if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        // return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
        // we have made this change because Auth::attempt will log the user directly 

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withInput($request->input())
                ->withErrors('Invalid login information.');
        }


        if(!$user->email_verified_at){
            return redirect()->back()->withInput($request->input())
            ->withErrors('Your email is not verified.');
        }

        Auth::login($user);
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

            $user->save();

            if(auth()->user()->hasPermissionTo('edit_users')) {
                $user->syncRoles($request->roles);
                $user->syncPermissions($request->permissions);
                Artisan::call('cache:clear');
            }
            
            return redirect(route('profile', ['user' => $user->id]));
        }


        public function verify(Request $request) {

            $decryptedData = json_decode(Crypt::decryptString($request->token), true);
            $user = User::find($decryptedData['id']);
            if(!$user) abort(401);
            $user->email_verified_at = Carbon::now();
            $user->save();
            return view('users.verified', compact('user'));
        }


        public function redirectToGoogle()
        {
            return Socialite::driver('google')->redirect();
        }

        public function handleGoogleCallback() {
            try {
                $googleUser = Socialite::driver('google')->user();
                // Log the user details to verify the callback
                \Log::info('Google User:', (array) $googleUser);
        
                $user = User::updateOrCreate([
                    'google_id' => $googleUser->id,
                ], [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                ]);
        
                Auth::login($user);
                return redirect('/');
            } catch (\Exception $e) {
                \Log::error('Google login error:', ['error' => $e->getMessage()]);
                return redirect('/login')->with('error', 'Google login failed.');
            }
        }
        



            // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('users.forgot-password');
    }

    // Handle Forgot Password Form Submission
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
    
        // Find the user by email
        $user = User::where('email', $request->email)->first();
    
        // Generate a secure token (you can use any logic, here I'm using Crypt::encryptString)
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
    
        // Generate the reset password link
        $link = route('password.reset', ['token' => $token]);
    
        // Send the reset password email
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));
    
        return back()->with('status', 'A password reset link has been sent to your email.');
    }

    // Show the Reset Password Form
    public function showResetPasswordForm($token)
    {
        return view('users.reset-password', ['token' => $token]);
    }



    public function resetPassword(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8', // Add your validation rules here
            'token' => 'required',
        ]);
    
        // Find the user by the provided email
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }
    
        // Check if the token is valid (you can implement token validation yourself)
        // For example, if you're storing the token in a database or a password_resets table
        // You can manually verify the token here.
    
        // Assuming token is validated and is valid, update the user's password
        $user->password = Hash::make($request->password); // Encrypt the new password
        $user->save(); // Save the updated password
    
        // Log the user in after resetting the password
        Auth::loginUsingId($user->id);
    
        // Redirect the user to a home page or dashboard
        return redirect("/"); // Adjust this to your main route
    }


}


