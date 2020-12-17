<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RFP;

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

    public function getClientName($clientID) {
        $clientNames = DB::select("SELECT Business_Number as 'clientID', ifnull(business_fullname, '') AS 'clientName' FROM general.`business_list` WHERE Business_Number IN (SELECT `ClientID` FROM general.`setup_project` WHERE `project_id` = '" . $clientID . "')");
        if(count($clientNames) > 0) {
            return $clientNames;
        } else {
            return '';
        }
    }

    public function createRFP(Request $request) {
        DB::table('accounting.request_for_payment')->insert([
            'DATE' => $request->dateRequested,
            'REQREF' => $request->referenceNumber,
            'Deadline' => date($request->dateNeeded, 'Y-m-d'),
            'AMOUNT' => number_format($request->amount, 2, '.', ''),
            'STATUS' => 'In Progress',
            'UID' => Auth::user()->id,
            'FNAME' => Auth::user()->fname,
            'LNAME' => Auth::user()->lname,
            'DEPARTMENT' => Auth::user()->department,
            'REPORTING_MANAGER' => '',
            'POSITION' => Auth::user()->positionName,
            'GUID' => trim(com_create_guid(), '{}')
        ]);

    }
}
