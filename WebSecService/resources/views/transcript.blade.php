@extends('layouts.master')

@section('title', 'Student Transcript')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Student Transcript</h2>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Course Name</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
                <tr>
                    <td>{{ $course['name'] }}</td>
                    <td>{{ $course['grade'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3 text-center">
        <h4>Total GPA: <span class="badge bg-success">{{ $gpa }}</span></h4>
    </div>
</div>
@endsection
