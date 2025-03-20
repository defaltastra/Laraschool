<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Log;
use Carbon\Carbon;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;

class UserManagementController extends Controller
{
    public function index()
    {
        return view('usermanagement.list_users');
    }

    public function userView($id)
    {
        $users = User::where('user_id', $id)->first();
        $role = DB::table('role_type_users')->get();
        return view('usermanagement.user_update', compact('users', 'role'));
    }

    public function userUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$this->isAuthorized(['Admin', 'Super Admin'])) {
                Toastr::error('User update fail :)', 'Error');
                return redirect()->back();
            }

            $userData = $this->extractUserData($request);
            $userData['avatar'] = $this->handleAvatar($request);
            
            User::where('user_id', $request->user_id)->update($userData);
            
            DB::commit();
            Toastr::success('User updated successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('User update fail :)', 'Error');
            return redirect()->back();
        }
    }

    public function userDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$this->isAuthorized(['Super Admin', 'Admin'])) {
                Toastr::error('User deleted fail :)', 'Error');
                return redirect()->back();
            }

            if ($request->avatar != 'photo_defaults.jpg') {
                unlink('images/' . $request->avatar);
            }
            
            User::destroy($request->user_id);
            
            DB::commit();
            Toastr::success('User deleted successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollback();
            Toastr::error('User deleted fail :)', 'Error');
            return redirect()->back();
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);
        DB::commit();
        Toastr::success('User change successfully :)', 'Success');
        return redirect()->intended('home');
    }

    public function getUsersData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowPerPage = $request->get("length");
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'] == 'name' ? 'name' : $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        $users = DB::table('users');
        $totalRecords = $users->count();

        $searchCondition = function ($query) use ($searchValue) {
            $query->where('name', 'like', '%' . $searchValue . '%')
                ->orWhere('email', 'like', '%' . $searchValue . '%')
                ->orWhere('position', 'like', '%' . $searchValue . '%')
                ->orWhere('phone_number', 'like', '%' . $searchValue . '%')
                ->orWhere('status', 'like', '%' . $searchValue . '%');
        };

        $totalRecordsWithFilter = $users->where($searchCondition)->count();

        $records = $users->orderBy($columnName, $columnSortOrder)
            ->where($searchCondition)
            ->skip($start)
            ->take($rowPerPage)
            ->get();
            
        $data_arr = [];
        
        foreach ($records as $record) {
            $status = $this->getStatusBadge($record->status);
            $data_arr[] = [
                "user_id" => $record->user_id,
                "avatar" => $this->generateAvatarHtml($record),
                "name" => $record->name,
                "email" => $record->email,
                "position" => $record->position,
                "phone_number" => $record->phone_number,
                "join_date" => $record->join_date,
                "status" => $status,
                "modify" => $this->generateActionButtons($record),
            ];
        }

        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData" => $data_arr
        ]);
    }

    private function isAuthorized($roles)
    {
        return in_array(Session::get('role_name'), $roles);
    }

    private function extractUserData(Request $request)
    {
        return [
            'user_id' => $request->user_id,
            'name' => $request->name,
            'role_name' => $request->role_name,
            'email' => $request->email,
            'position' => $request->position,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'department' => $request->department,
            'status' => $request->status,
        ];
    }

    private function handleAvatar(Request $request)
    {
        $image_name = $request->hidden_avatar;
        $image = $request->file('avatar');
        
        if (!$image) {
            return $image_name;
        }
        
        if ($image_name != 'photo_defaults.jpg') {
            unlink('images/' . $image_name);
        }
        
        $image_name = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('/images/'), $image_name);
        
        return $image_name;
    }

    private function getStatusBadge($status)
    {
        $badges = [
            'Active' => '<td><span class="badge bg-success-dark">' . $status . '</span></td>',
            'Disable' => '<td><span class="badge bg-danger-dark">' . $status . '</span></td>',
            'Inactive' => '<td><span class="badge badge-warning">' . $status . '</span></td>',
        ];
        
        return $badges[$status] ?? '<td><span class="badge badge-secondary">' . $status . '</span></td>';
    }

    private function generateAvatarHtml($record)
    {
        return '
            <td>
                <h2 class="table-avatar">
                    <a class="avatar-sm me-2">
                        <img class="avatar-img rounded-circle avatar" data-avatar=' . $record->avatar . ' src="/images/' . $record->avatar . '"alt="' . $record->name . '">
                    </a>
                </h2>
            </td>
        ';
    }

    private function generateActionButtons($record)
    {
        return '
            <td class="text-end"> 
                <div class="actions">
                    <a href="' . url('view/user/edit/' . $record->user_id) . '" class="btn btn-sm bg-danger-light">
                        <i class="far fa-edit me-2"></i>
                    </a>
                    <a class="btn btn-sm bg-danger-light delete user_id" data-bs-toggle="modal" data-user_id="' . $record->user_id . '" data-bs-target="#delete">
                    <i class="fe fe-trash-2"></i>
                    </a>
                </div>
            </td>
        ';
    }
}