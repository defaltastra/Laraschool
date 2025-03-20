<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getStats()
    {
        // Get teacher data with join
        $teacherData = Teacher::join('users', 'teachers.user_id', 'users.user_id')
            ->select('users.date_of_birth', 'users.join_date', 'users.phone_number', 'teachers.*')
            ->get();
            
        // Get student data
        $studentData = Student::all();
        
        return response()->json([
            'teacherData' => $teacherData,
            'studentData' => $studentData
        ]);
    }
}