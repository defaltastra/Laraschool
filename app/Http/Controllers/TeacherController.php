<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Teacher;
use Brian2694\Toastr\Facades\Toastr;

class TeacherController extends Controller
{
    /** add teacher page */
    public function teacherAdd()
    {
        $users = User::where('role_name','Teachers')->get();
        return view('teacher.add-teacher',compact('users'));
    }

    /** teacher list */
    public function teacherList()
    {
        $listTeacher = Teacher::join('users', 'teachers.user_id','users.user_id')
                    ->select('users.date_of_birth','users.join_date','users.phone_number','teachers.*')->get();
        return view('teacher.list-teachers',compact('listTeacher'));
    }

    /** teacher Grid */
    public function teacherGrid()
    {
        $teacherGrid = Teacher::all();
        return view('teacher.teachers-grid',compact('teacherGrid'));
    }

    /** save record */
    /** save record */
public function saveRecord(Request $request)
{
    $request->validate([
        'full_name'     => 'required|string',
        'gender'        => 'required|string',
        'city'          => 'required|string',
    ]);

    try {
        // Create a new Teacher record
        $saveRecord = new Teacher;
        $saveRecord->full_name     = $request->full_name;
        $saveRecord->user_id       = $request->teacher_id;  // This is passed from the form
        $saveRecord->gender        = $request->gender;
        $saveRecord->city          = $request->city;
        $saveRecord->save(); // Save the teacher

        Toastr::success('Teacher has been added successfully :)','Success');
        return redirect()->route('teacher.list');  // Redirect to teacher list
    } catch(\Exception $e) {
        DB::rollback();

        return redirect()->back();
    }
}

    /** edit record */
    public function editRecord($user_id)
    {
        $teacher = Teacher::join('users', 'teachers.user_id','users.user_id')
                    ->select('users.date_of_birth','users.join_date','users.phone_number','teachers.*')
                    ->where('users.user_id', $user_id)->first();
        return view('teacher.edit-teacher',compact('teacher'));
    }

    /** update record teacher */
    public function updateRecordTeacher(Request $request)
    {
        DB::beginTransaction();
        try {

            $updateRecord = [
                'full_name'     => $request->full_name,
                'gender'        => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'city'          => $request->city,
                'country'      => $request->country,
            ];
            Teacher::where('id',$request->id)->update($updateRecord);
            
            Toastr::success('Has been update successfully :)','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            \Log::info($e);
            Toastr::error('fail, update record  :)','Error');
            return redirect()->back();
        }
    }

    /** delete record */
    public function teacherDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $teacherId = $request->id;
    
            // Make sure the ID is passed and it's a valid ID
            if (!empty($teacherId)) {
                $teacher = Teacher::find($teacherId);
    
                // Ensure the teacher exists before attempting to delete
                if ($teacher) {
                    // You can add more checks here if you have other related data to delete (e.g. user photos, etc.)
    
                    $teacher->delete();  // Delete the teacher
    
                    DB::commit();
                    Toastr::success('Teacher deleted successfully :)','Success');
                } else {
                    // If the teacher does not exist
                    DB::rollback();
                    Toastr::error('Teacher not found, could not delete.','Error');
                }
            } else {
                // If no ID is passed
                DB::rollback();
                Toastr::error('Teacher ID is missing, could not delete.','Error');
            }
    
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Teacher deletion failed: ' . $e->getMessage());
            Toastr::error('Teacher deletion failed.','Error');
            return redirect()->back();
        }
    }
    
    
}
