@extends('layouts.master')

@section('title', 'Users List')

@section('content')


<form action="{{route('do_login')}}" method="post">
    {{ csrf_field() }}
    <div class="form-group">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            <strong>Error!</strong> {{$error}}
        </div>
        @break
        @endforeach
        </div>
        <div class="form-group mb-2">
        <label for="model" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="email" name="email" required>
        </div>
        <div class="form-group mb-2">
        <label for="model" class="form-label">Password:</label>
        <input type="password" class="form-control" placeholder="password" name="password" required>
        </div>
        <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="{{route('login_with_google')}}" class="btn btn-success">Login with Google</a>
    </div>
</form>

@endsection