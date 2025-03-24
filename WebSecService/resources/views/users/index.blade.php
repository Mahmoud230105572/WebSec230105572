@extends('layouts.master')

@section('title', 'Users List')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Users List</h2>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('users_index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th> <!-- New Role Column -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->roles->pluck('name')->join(', ') }}</td> <!-- Display User Role(s) -->
                <td>
                    <a href="{{ route('users_edit', $user->id) }}" class="btn btn-warning">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
@endsection
