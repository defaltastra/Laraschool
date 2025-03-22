@extends('layouts.master')

@section('content')
    {{-- Message --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Uploaded Media (Subjects)</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Uploaded Media</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Media List Table -->
            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="page-title">Uploaded Media</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($media as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->filename }}</td>
                                    <td>{{ ucfirst($item->type) }}</td>
                                    <td>
                                        {{-- If it's a PDF --}}
                                        @if($item->type === 'pdf')
                                            <a href="{{ asset('storage/media/' . $item->filename) }}" class="btn btn-sm btn-success" target="_blank">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        {{-- If it's a Video --}}
                                        @elseif($item->type === 'video')
                                            <a href="{{ asset('storage/media/' . $item->filename) }}" class="btn btn-sm btn-info" target="_blank">
                                                <i class="fas fa-play"></i> Watch
                                            </a>
                                        @endif

                                        {{-- Delete Button --}}
                                       <!-- Check if the user is not a student -->
@if(auth()->user()->role_name != 'Student')
<form action="{{ route('media.delete', $item->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">
        <i class="fas fa-trash"></i> Delete
    </button>
</form>
@endif

                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
