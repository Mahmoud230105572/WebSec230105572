@extends('layouts.master')
@section('title', 'Prime')
@section('content')
    @auth
    <form action="{{ route('todos.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Task Name" required>
        <button type="submit">Add</button>
    </form>
    @endauth

    <ul>
        @foreach ($todos as $todo)
            <li>
                {{ $todo->name }} 
                @auth
                <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
                @endauth
            </li>
        @endforeach
    </ul>
@endsection
