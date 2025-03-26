@extends('layouts.master')

@section('content')
    {{-- Success/Failure Message --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Edit Test: {{ $test->name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit Test</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('tests.update', $test->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Test Information</span></h5>
                                    </div>

                                    {{-- Test Name --}}
                                    <div class="col-12 col-sm-8">
                                        <div class="form-group local-forms">
                                            <label for="name">Test Name <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{ $test->name }}" required>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-12">
                                        <div class="form-group local-forms">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3">{{ $test->description }}</textarea>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="col-12">
                                        <h5 class="form-title"><span>Questions & Answers</span></h5>
                                    </div>

                                    {{-- Existing Questions --}}
                                    <div id="questions-container">
                                        @foreach($test->questions as $qIndex => $question)
                                            <div class="question-block">
                                                <div class="form-group">
                                                    <label>Question <span class="login-danger">*</span></label>
                                                    <input type="text" name="questions[{{ $qIndex }}][text]" class="form-control" value="{{ $question->text }}" required>
                                                </div>

                                                {{-- Answer Options --}}
                                                <div class="answers-group">
                                                    @foreach($question->answers as $aIndex => $answer)
                                                        <div class="form-check">
                                                            <input type="radio" name="questions[{{ $qIndex }}][correct]" value="{{ $aIndex }}" {{ $answer->is_correct ? 'checked' : '' }} required>
                                                            <input type="text" name="questions[{{ $qIndex }}][answers][]" class="form-control d-inline-block w-75" value="{{ $answer->text }}" required>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                {{-- Points --}}
                                                <div class="form-group mt-2">
                                                    <label>Points (must sum to 20)</label>
                                                    <input type="number" name="questions[{{ $qIndex }}][points]" class="form-control" min="1" max="20" value="{{ $question->points }}" required>
                                                </div>

                                                <button type="button" class="btn btn-danger btn-sm remove-question">Remove Question</button>
                                                <hr>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Add Question Button --}}
                                    <button type="button" id="add-question" class="btn btn-secondary">Add Question</button>

                                    <div class="col-12 mt-3">
                                        <div class="student-submit">
                                            <button type="submit" class="btn btn-primary">Update Test</button>
                                            <a href="{{ route('tests.list') }}" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for Dynamic Question Addition & Removal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let questionIndex = {{ count($test->questions) }};
            
            document.getElementById('add-question').addEventListener('click', function () {
                let container = document.getElementById('questions-container');
                let newQuestion = document.createElement('div');
                newQuestion.classList.add('question-block');
                newQuestion.innerHTML = `
                    <div class="form-group">
                        <label>Question <span class="login-danger">*</span></label>
                        <input type="text" name="questions[\${questionIndex}][text]" class="form-control" required>
                    </div>
                    <div class="answers-group">
                        ${[0, 1, 2, 3].map(i => `
                            <div class="form-check">
                                <input type="radio" name="questions[\${questionIndex}][correct]" value="\${i}" required>
                                <input type="text" name="questions[\${questionIndex}][answers][]" class="form-control d-inline-block w-75" placeholder="Answer Option" required>
                            </div>
                        `).join('')}
                    </div>
                    <div class="form-group mt-2">
                        <label>Points (must sum to 20)</label>
                        <input type="number" name="questions[\${questionIndex}][points]" class="form-control" min="1" max="20" required>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-question">Remove Question</button>
                    <hr>
                `;
                container.appendChild(newQuestion);
                questionIndex++;
            });

            document.getElementById('questions-container').addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-question')) {
                    event.target.closest('.question-block').remove();
                }
            });
        });
    </script>
@endsection
