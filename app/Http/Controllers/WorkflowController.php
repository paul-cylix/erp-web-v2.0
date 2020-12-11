<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    public function getPost() {
        $userid = Auth::user()->id;
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . $userid . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('MyWorkflow.approval', compact('posts'));
        //return $posts;
    }

    public function getRFP_InitData() {
        $userid = Auth::user()->id;
        $mngrs = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . $userid . "' ORDER BY RMName");
        $projects = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE project_type <> 'MAIN OFFICE' AND `status` = 'Active' AND title_id = 1 ORDER BY project_name");
        $user_info = DB::select("SELECT FirstName_Empl AS 'FNAME', LastName_Empl AS 'LNAME', DepartmentName AS 'Department', PositionName AS 'PositionName' FROM humanresource.`employees` a INNER JOIN erpweb.`users` b ON (a.`SysPK_Empl` = b.`employee_id`) WHERE b.`id` = '" . $userid . "'");

        return view('AccountingRequest.create-rfp', compact('mngrs', 'projects', 'user_info'));
    } 
}