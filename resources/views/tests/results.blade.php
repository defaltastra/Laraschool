@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Test Results</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Test Name</th>
                <th>Student</th>
                <th>Score</th>
                <th>Date Taken</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $result->test->name }}</td>
                <td>{{ $result->student->name }}</td>
                <td>{{ $result->score }}%</td>
                <td>{{ $result->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
