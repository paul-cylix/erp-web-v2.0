<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class TheApiController extends Controller
{
    public function index(){
       $employees = DB::select("SELECT SysPK_Empl, Name_Empl FROM humanresource.`employees` WHERE CompanyID = 1 AND Status_Empl LIKE 'Active%' ORDER BY Name_Empl;");
       $managers = DB::select("SELECT * FROM general.`managers`");
        return view('TheApi.register',compact('employees','managers'));
    }

    public function saveUserAttendance(Request $request){


        $validator = Validator::make($request->all(), [
            'username'=>'required',
            'fullname'=>'required',
            'employeeId'=>'required',
            'isManager'=>'required',
            'password'=>'required|confirmed|min:8',
            'rank'=>'required',
            'managerId'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['failed'=>$validator->errors()]);
        }


        $usernameEmail = $request->username;
        $userFullname = $request->fullname;
        $employeeId = intval($request->employeeId);

       $genUserId = DB::table('general.users')->insertGetId([
            'UserName_User' => $usernameEmail,
            'UserFull_name' => $userFullname,
            'Password_User' => 'dg4uCwwDtek=',
            'Employee_id' => $employeeId,
            'email_address' => $usernameEmail,
            'IsManager' => intval($request->IsManager),
        ]);

        DB::table('erpweb.users_attendance')->insert([
            'id' => $genUserId,
            'display_name' => $userFullname,
            'username' => $usernameEmail,
            'password' => $request->password,
            'employee_id' => $employeeId,
            'rank' => $request->rank,
            'manager_id' => intval($request->managerId),
        ]);




    return response()->json(['success'=>'Registered Successfully!']);
















    }
}
