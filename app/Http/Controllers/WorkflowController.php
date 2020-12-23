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

    public function getReference($reqForm) {
        if ($reqForm == 'RFP') {
            $queryREF = DB::select("SELECT LPAD(IFNULL((SELECT MAX(SUBSTRING(REQREF, 10)) FROM accounting.`request_for_payment` WHERE YEAR(TS)=YEAR(CURDATE()) AND TITLEID = " . Auth::user()->CID . " AND  (REQREF  NOT  LIKE '%AM%' OR REQREF  NOT  LIKE '%PM%')), 0) + 1, 4, 0) 'REF'");
            $REF = 'RFP-' . date('Y') . '-' . $queryREF[0]->REF;
            return $REF;
        }
    }

    function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }
        else {
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            $uuid = trim($uuid, '{');
            $uuid = trim($uuid, '}');
            return $uuid;
        }
    }

    public function createRFP(Request $request) {
        $dateRequested = date_create($request->dateRequested);
        $dateNeeded = date_create($request->dateNeeded);

        mt_srand((double)microtime()*10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $GUID = chr(123)
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);
        $GUID = trim($GUID, '{');
        $GUID = trim($GUID, '}');
 

        DB::table('accounting.request_for_payment')->insert([
            'DATE' => date_format($dateRequested, 'Y-m-d'),
            'REQREF' => $request->referenceNumber,
            'Deadline' => date_format($dateNeeded, 'Y-m-d'),
            'AMOUNT' => number_format($request->amount, 2, '.', ''),
            'STATUS' => 'In Progress',
            'UID' => Auth::user()->id,
            'FNAME' => Auth::user()->fname,
            'LNAME' => Auth::user()->lname,
            'DEPARTMENT' => Auth::user()->department,
            'REPORTING_MANAGER' => $request->RMName,
            'POSITION' => Auth::user()->positionName,
            'GUID' => $GUID,
            'ISRELEASED' => '0',
            'TITLEID' => '1'
        ]);
        return back()->with('form_submitted', 'Request has been submitted!');
    }
}
