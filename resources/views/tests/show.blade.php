@extends('layouts.master')

@section('content')
    {{-- Success/Failure Message --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $test->name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tests.list') }}">Tests</a></li>
                            <li class="breadcrumb-item active">View Test</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- Test Info --}}
                            <h5 class="form-title"><span>Test Details</span></h5>
                            <p><strong>Description:</strong> {{ $test->description ?? 'No description available' }}</p>
                            <p><strong>Created by:</strong> {{ $test->teacher->name }}</p>
                            <p><strong>Created on:</strong> {{ $test->created_at->format('d M Y') }}</p>
                            
                            <hr>

                            {{-- Test Questions --}}
                            <h5 class="form-title"><span>Questions</span></h5>

                            <form action="{{ route('tests.submit', $test->id) }}" method="POST">
                                @csrf
                                @foreach($test->questions as $index => $question)
                                <div class="mb-4">
                                    <h6>Q{{ $index + 1 }}. {{ $question->text }} <span class="text-muted">({{ $question->points }} pts)</span></h6>
                                    
                                    {{-- Answer Options --}}
                                    @foreach($question->answers as $key => $answer)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" id="q{{ $question->id }}a{{ $answer->id }}" required>
                                            <label class="form-check-label" for="q{{ $question->id }}a{{ $answer->id }}">
                                                {{ $answer->text }}  {{-- Display the text of each answer --}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                                <hr>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success">Submit Test</button>
                                    <a href="{{ route('tests.list') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection
