@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="d-flex justify-content-center">
    <div class="row m-4 col-sm-8">
        <div class="card shadow-lg p-4">
            <h3 class="text-center mb-4">Edit Profile</h3>
            <form action="{{ route('users_save', $user->id) }}" method="post">
                {{ csrf_field() }}

                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                        <strong>Error!</strong> {{ $error }}
                    </div>
                @endforeach

                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" placeholder="Name" name="name" required value="{{ $user->name }}">
                </div>


                <hr>

                <h5 class="text-muted">Change Password</h5>
                
                <div class="mb-3">
                    <label for="old_password" class="form-label">Old Password:</label>
                    <input type="password" class="form-control" name="old_password" placeholder="Enter old password">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password:</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter new password">
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password:</label>
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm new password">
                </div>

                @can('add_credit')
                <div class="mb-3">
                    <label for="account_credit" class="form-label">Account Credit:</label>
                    <input type="number" class="form-control" name="account_credit" step="0.01" value="{{ $user->account_credit }}">
                </div>
                @endcan



                @can('edit_permissions')
                <div class="col-12 mb-2">
                    <label for="model" class="form-label">Roles:</label>
                    <select multiple class="form-select" name="roles[]">
                    @foreach($roles as $role)
                        <option value='{{$role->name}}' {{$role->taken?'selected':''}}>
                        {{$role->name}}
                    </option>
                    @endforeach
                    </select>
                </div>
                @endcan


                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-50">Save Changes</button>
                </div>
            </form>
        </div>
    </div>



</div>


@endsection