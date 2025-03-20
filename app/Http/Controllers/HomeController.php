<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    /** home dashboard */
    public function index()
    {
        // Fetch dynamic data for the dashboard
        $dashboardData = [
            'total_students' => DB::table('students')->count(),
            'total_departments' => DB::table('departments')->count(),
        ];

        // Pass the data to the view
        return view('dashboard.home', compact('dashboardData'));
    }

    /** profile user */
    public function userProfile()
    {
        // Fetch the authenticated user's data
        $user = Auth::user();

        // Pass the user data to the profile view
        return view('dashboard.profile', compact('user'));
    }

    /** teacher dashboard */
    public function teacherDashboardIndex()
    {
        // Fetch data specific to the teacher dashboard
      

        // Pass the data to the teacher dashboard view
        return view('dashboard.teacher_dashboard');
    }

    /** student dashboard */
    public function studentDashboardIndex()
    {
        // Fetch data specific to the student dashboard
        $studentData = [

            'upcoming_assignments' => DB::table('assignments')

        ];

        // Pass the data to the student dashboard view
        return view('dashboard.student_dashboard', compact('studentData'));
    }
}