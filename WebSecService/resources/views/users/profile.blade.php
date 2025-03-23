@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
    <div class="d-flex justify-content-center">
    <div class="m-4 col-sm-8">
        <table class="table table-striped">
            <tr>
                <th>Name</th><td>{{$user->name}}</td>
            </tr>
            <tr>
                <th>Email</th><td>{{$user->email}}</td>
            </tr>
            <tr>
                <th>Roles</th>
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{$role->name}}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Permissions</th>
                <td>
                    @foreach($permissions as $permission)
                    <span class="badge bg-success">{{$permission->name}}</span>
                    @endforeach
                </td>
            </tr>

            
        </table>


    </div>

    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-auto">
            @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                <a href="{{ route('users_edit') }}" class="btn btn-success">Edit</a>
            @endif
        </div>
    </div>
@endsection