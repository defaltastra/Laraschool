@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Welcome {{ Session::get('name') ?? 'Student' }}!</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Student</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>All Courses</h6>
                                    <h3>{{ $enrolled_courses ?? 0 }}/{{ $total_courses ?? 0 }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/teacher-icon-01.svg') }}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>All Projects</h6>
                                    <h3>{{ $completed_projects ?? 0 }}/{{ $total_projects ?? 0 }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/teacher-icon-02.svg') }}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Tests Attended</h6>
                                    <h3>{{ $attended_tests ?? 0 }}/{{ $total_tests ?? 0 }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/student-icon-01.svg') }}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Tests Passed</h6>
                                    <h3>{{ $passed_tests ?? 0 }}/{{ $total_passed_tests ?? 0 }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/student-icon-02.svg') }}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-12 col-xl-8">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="card-title">Today's Lesson</h5>
                                </div>
                                <div class="col-6">
                                    <ul class="chart-list-out">
                                      
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="dash-circle">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 dash-widget1">
                                    <div class="circle-bar circle-bar2">
                                        <div class="circle-graph2" data-percent="{{ $today_lesson_progress ?? 0 }}">
                                            <b>{{ $today_lesson_progress ?? 0 }}%</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="dash-details">
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{ URL::to('assets/img/icons/lesson-icon-01.svg') }}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Class</h5>
                                                <h4>{{ $today_lesson->class ?? 'N/A' }}</h4>
                                            </div>
                                        </div>
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{ URL::to('assets/img/icons/lesson-icon-02.svg') }}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Lessons</h5>
                                                <h4>{{ $today_lesson->total_lessons ?? 0 }} Lessons</h4>
                                            </div>
                                        </div>
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{ URL::to('assets/img/icons/lesson-icon-03.svg') }}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Time</h5>
                                                <h4>{{ $today_lesson->duration ?? 'N/A' }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="dash-details">
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{ URL::to('assets/img/icons/lesson-icon-04.svg') }}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Assignment</h5>
                                                <h4>{{ $today_lesson->assignments ?? 0 }} Assignment</h4>
                                            </div>
                                        </div>
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{ URL::to('assets/img/icons/lesson-icon-05.svg') }}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Instructor</h5>
                                                <h4>{{ $today_lesson->instructor ?? 'N/A' }}</h4>
                                            </div>
                                        </div>
                                        <div class="lesson-activity">
                                            <div class="lesson-imgs">
                                                <img src="{{ URL::to('assets/img/icons/lesson-icon-06.svg') }}" alt="">
                                            </div>
                                            <div class="views-lesson">
                                                <h5>Lesson Learned</h5>
                                                <h4>{{ $completed_lessons ?? 0 }}/{{ $total_lessons ?? 0 }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 d-flex align-items-center justify-content-center">
                                    <div class="skip-group">
                                        @if(isset($today_lesson) && $today_lesson)
                                            <a href="{{ url('/student/lessons/'.$today_lesson->id.'/skip') }}" class="btn btn-info skip-btn">Skip</a>
                                            <a href="{{ url('/student/lessons/'.$today_lesson->id.'/continue') }}" class="btn btn-info continue-btn">Continue</a>
                                        @else
                                            <button class="btn btn-info skip-btn" disabled>Skip</button>
                                            <button class="btn btn-info continue-btn" disabled>Continue</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-12 col-xl-12 d-flex">
                            <div class="card flex-fill comman-shadow">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <h5 class="card-title">Learning Activity</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="apexcharts-area"></div>
                                </div>
                            </div>
                        </div>
                  
                    </div>
                </div>
          
            </div>
        </div>
    </div>
@endsection