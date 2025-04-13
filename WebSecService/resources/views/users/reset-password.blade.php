@extends('layouts.master')
@section('title', 'reset password')
@section('content')
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            @error('email')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror

            @error('password')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
@endsection
