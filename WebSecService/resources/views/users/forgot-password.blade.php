@extends('layouts.master')
@section('title', 'reset password')
@section('content')
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            @if(session('status'))
                <div class="alert alert-success mt-2">
                    {{ session('status') }}
                </div>
            @endif

            @error('email')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>
    </div>
@endsection
