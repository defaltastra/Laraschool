<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /** index page student list */
    public function student()
    {
        $studentList = Student::all();
        return view('student.student',compact('studentList'));
    }

    /** index page student grid */
    public function studentGrid()
    {
        $studentList = Student::all();
        return view('student.student-grid',compact('studentList'));
    }

    /** student add page */
    public function studentAdd()
    {
        return view('student.add-student');
    }
    
    /** student save record */
    public function studentSave(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'gender'        => 'required|not_in:0',
            'date_of_birth' => 'required|string',  
            'email'         => 'required|email',
            'class'         => 'required|string',
        ]);
    
        // Start database transaction
        DB::beginTransaction();
        try {
            // Create a new student record
            $student = new Student;
            $student->first_name   = $request->first_name;
            $student->last_name    = $request->last_name;
            $student->gender       = $request->gender;
            $student->date_of_birth= $request->date_of_birth;
            $student->email        = $request->email;
            $student->class        = $request->class;
            
            // Save the student data
            $student->save();
    
            // Commit transaction
            DB::commit();
    
            // Display success message
            Toastr::success('Student has been added successfully :)','Success');
            
            // Redirect back
            return redirect()->back();
            
        } catch (\Exception $e) {
            // Rollback in case of failure
            DB::rollback();
            
            // Log error
            \Log::info($e);
            
            // Display error message
            Toastr::error('Failed to add new student :)','Error');
            
            // Redirect back
            return redirect()->back();
        }
    }
    

    /** view for edit student */
    public function studentEdit($id)
    {
        $studentEdit = Student::where('id',$id)->first();
        return view('student.edit-student',compact('studentEdit'));
    }

    /** update record */
    public function studentUpdate(Request $request)
    {
        DB::beginTransaction();
        try {

      
            Student::where('id',$request->id)->update($updateRecord);
            
            Toastr::success('Has been update successfully :)','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('fail, update student  :)','Error');
            return redirect()->back();
        }
    }

    /** student delete */
    public function studentDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            // Check if ID is provided
            if (!empty($request->id)) {
                // Delete the student by ID
                Student::destroy($request->id);
                
                // Commit the transaction
                DB::commit();
                
                // Success message
                Toastr::success('Student deleted successfully :)','Success');
                
                // Redirect back
                return redirect()->back();
            }
            
        } catch (\Exception $e) {
            // Rollback the transaction in case of failure
            DB::rollback();
            
            // Log the error
            \Log::info($e);
            
            // Error message
            Toastr::error('Student deletion failed :)','Error');
            
            // Redirect back
            return redirect()->back();
        }
    }
    
    /** student profile page */
    public function studentDashboard()
    {
        $studentId = Auth::id(); // Ensure the student is authenticated

        // Fetch student-related data
        $enrolled_courses = DB::table('course_enrollments')->where('student_id', $studentId)->count();
        $total_courses = DB::table('courses')->count();

        $completed_projects = DB::table('projects')->where('student_id', $studentId)->where('status', 'Completed')->count();
        $total_projects = DB::table('projects')->where('student_id', $studentId)->count();

        $attended_tests = DB::table('tests')->where('student_id', $studentId)->where('attended', true)->count();
        $total_tests = DB::table('tests')->where('student_id', $studentId)->count();

        $passed_tests = DB::table('tests')->where('student_id', $studentId)->where('passed', true)->count();
        $total_passed_tests = DB::table('tests')->where('student_id', $studentId)->count();

        // Today's lesson
        $today_lesson = DB::table('lessons')
            ->where('student_id', $studentId)
            ->whereDate('date', today())
            ->first();

        $today_lesson_progress = $today_lesson ? $today_lesson->progress : 0;
        $completed_lessons = DB::table('lessons')->where('student_id', $studentId)->where('completed', true)->count();
        $total_lessons = DB::table('lessons')->where('student_id', $studentId)->count();

     

      

        return view('dashboard.student_dashboard', compact(
            'enrolled_courses',
            'total_courses',
            'completed_projects',
            'total_projects',
            'attended_tests',
            'total_tests',
            'passed_tests',
            'total_passed_tests',
            'today_lesson',
            'today_lesson_progress',
            'completed_lessons',
            'total_lessons',
            'learning_history',
            'history_dates',
        ));
    }
    
    public function dashboard()
{
    $today_lesson = Lesson::whereDate('date', now()->toDateString())->first(); // Fetch today's lesson

    return view('dashboard.student_dashboard', compact('today_lesson'));
}

}
