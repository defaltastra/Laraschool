@extends('layouts.master')

@section('content')
    {{-- Success/Failure Message --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Test List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Tests</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                       {{-- Show the "Create New Test" button only if the user is not a student --}}
                       @if(auth()->user()->role_name !== 'Student')
                       <a href="{{ route('tests.create.page') }}" class="btn btn-primary">
                           <i class="fa fa-plus"></i> Create New Test
                       </a>
                   @endif
                    </div>
                </div>
            </div>

            {{-- Tests Table --}}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Test Name</th>
                                            <th>Created By</th>
                                            <th>Date</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tests as $test)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $test->name }}</td>
                                                <td>{{ $test->teacher->name }}</td>
                                                <td>{{ $test->created_at->format('d M Y') }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('tests.show', $test->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>

                                                    {{-- Check if the authenticated user is not a student --}}
                                                    @if(auth()->user()->role_name !== 'Student')
                                                        <a href="{{ route('tests.edit', $test->id) }}" class="btn btn-warning btn-sm">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('tests.destroy', $test->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No tests available.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection
