@extends('layouts.master')

@section('content')
    {{-- Success/Failure Message --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Create New QCM Test</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Create Test</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('tests.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Test Information</span></h5>
                                    </div>

                                    {{-- Test Name --}}
                                    <div class="col-12 col-sm-8">
                                        <div class="form-group local-forms">
                                            <label for="name">Test Name <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter test name" required>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-12">
                                        <div class="form-group local-forms">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter test description (optional)"></textarea>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="col-12">
                                        <h5 class="form-title"><span>Questions & Answers</span></h5>
                                    </div>

                                    {{-- Dynamic Questions --}}
                                    <div id="questions-container">
                                        <div class="question-block">
                                            <div class="form-group">
                                                <label>Question <span class="login-danger">*</span></label>
                                                <input type="text" name="questions[0][text]" class="form-control" required>
                                            </div>

                                            {{-- Answer Options --}}
                                            <div class="answers-group">
                                                @for($i = 0; $i < 4; $i++)
                                                    <div class="form-check">
                                                        <input type="radio" name="questions[0][correct]" value="{{ $i }}" required>
                                                        <input type="text" name="questions[0][answers][]" class="form-control d-inline-block w-75" placeholder="Answer Option" required>
                                                    </div>
                                                @endfor
                                            </div>

                                            {{-- Points --}}
                                            <div class="form-group mt-2">
                                                <label>Points (must sum to 20)</label>
                                                <input type="number" name="questions[0][points]" class="form-control" min="1" max="20" required>
                                            </div>

                                            <hr>
                                        </div>
                                    </div>

                                    {{-- Add Question Button --}}
                                    <button type="button" id="add-question" class="btn btn-secondary">Add Question</button>

                                    <div class="col-12 mt-3">
                                        <div class="student-submit">
                                            <button type="submit" class="btn btn-primary">Save Test</button>
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

    {{-- JavaScript for Dynamic Question Addition --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let questionIndex = 1;
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
                    <hr>
                `;
                container.appendChild(newQuestion);
                questionIndex++;
            });
        });
    </script>
@endsection
