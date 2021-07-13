<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HumanResourceRequestController extends Controller
{
    // HumanResource
    public function createOt() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        $employee = DB::select("SELECT SysPK_Empl, Name_Empl FROM humanresource.`employees` WHERE Status_Empl LIKE 'Active%' AND CompanyID = ".session('LoggedUser_CompanyID')." ORDER BY Name_Empl");
        $project = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE title_id = ".session('LoggedUser_CompanyID')." AND `status` = 'Active' AND project_type IN ('Project Site', 'Non-Project') ORDER BY project_name;");
        $managers = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");


     
        return view('HumanResourceRequest.create-ot', compact('posts','employee','project','managers'));
    }

    public function getClient($project_id){
        return json_encode(DB::select("SELECT Business_Number 'clientID', Business_fullname 'clientName' FROM general.`business_list` WHERE Business_Number = (SELECT ClientID FROM general.`setup_project` WHERE project_id = ".$project_id.");"));
    }

    public function saveOT(Request $request){

        $request->validate([
            'rmID'=>'required',
            'purpose'=>'required',
            'jsonOTdata'=>'required',           
        ]);

        $success = true;
        DB::beginTransaction();
        try{    

            $dataREQREF = DB::select("SELECT IFNULL((SELECT MAX(SUBSTRING(reference ,10)) FROM humanresource.`overtime_request` WHERE YEAR(request_date)=YEAR(NOW()) AND TITLEID = ".session('LoggedUser_CompanyID')."),0) + 1 'OTR'");
            $getref = $dataREQREF[0]->OTR;
            $ref = str_pad($getref, 4, "0", STR_PAD_LEFT); 
            $ref = "OTR-" . date('Y') . "-" . $ref;
    
            $mainID = DB::select("SELECT IFNULL(MAX(main_id),0) + 1 AS main FROM humanresource.`overtime_request`");


            $otData = $request->jsonOTdata;
            $otData =json_decode($otData,true);

            $dateRequested = date_create($request->dateRequested);

            mt_srand((double)microtime()*10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $GUID = '';
            $GUID = chr(123)
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);
            $GUID = trim($GUID, '{');
            $GUID = trim($GUID, '}');


            if(!empty($otData)){
                // insert to hr.ot main table
                for($i = 0; $i <count($otData); $i++) {

                    $ot_date = date_create($otData[$i][2]);
                    $ot_in = date_create($otData[$i][3]);
                    $ot_out = date_create($otData[$i][4]);                    

                    $setOTData[] = [
                        'reference' => $ref,
                        'request_date' => date_format($dateRequested, 'Y-m-d'),
                        'overtime_date' => date_format($ot_date, 'Y-m-d'),
                        'ot_in' => date_format($ot_in, 'Y-m-d H:i:s'),
                        'ot_out' => date_format($ot_out, 'Y-m-d H:i:s'),
                        'ot_totalhrs' => $otData[$i][5],
                        'employee_id' => $otData[$i][9],
                        'employee_name' => $otData[$i][0],
                        'purpose' => $otData[$i][6],
                        'status' => 'In Progress',
                        'UID' => session('LoggedUser'),
                        'fname' => session('LoggedUser_FirstName'), 
                        'lname' => session('LoggedUser_LastName'),
                        'department' => session('LoggedUser_DepartmentName'), 
                        'reporting_manager' => $request->rmName, 
                        'position' => session('LoggedUser_PositionName'),
                        'ts' => now(),
                        'GUID' => $GUID,
                        // 'comments' => , 
                        // 'ot_in_actual' => , 
                        // 'ot_out_actual' => ,
                        // 'ot_totalhrs_actual' => , 
                        'main_id' => $mainID[0]->main,
                        'remarks' => $request->purpose,
                        'cust_id' => $otData[$i][9],
                        'cust_name' => $otData[$i][10],
                        'TITLEID' => session('LoggedUser_CompanyID'),
                        'PRJID' => $otData[$i][8]
                    ];
                }
                DB::table('humanresource.overtime_request')->insert($setOTData);


            }
                //Insert general.actual_sign
                for ($x = 0; $x < 4; $x++) {
                    $array[] = array(
                        'PROCESSID'=>$mainID[0]->main,
                        'USER_GRP_IND'=>'0',
                        'FRM_NAME'=>$request->frmName,
                        // 'TaskTitle'=>'',
                        // 'NS'=>'',
                        'FRM_CLASS'=>'frmOvertimeRequest', //Hold
                        'REMARKS'=>$request->purpose,
                        'STATUS'=>'Not Started',
                        // 'UID_SIGN'=>'0',
                        // 'TS'=>'',
                        'DUEDATE'=>date_format($dateRequested, 'Y-m-d'),
                        // 'SIGNDATETIME'=>'',
                        'ORDERS'=>$x,
                        'REFERENCE'=>$ref,
                        'PODATE'=>date_format($dateRequested, 'Y-m-d'),
                        // 'PONUM'=>'',
                        'DATE'=>date_format($dateRequested, 'Y-m-d'),
                        'INITID'=>session('LoggedUser'),
                        'FNAME'=>session('LoggedUser_FirstName'),
                        'LNAME'=>session('LoggedUser_LastName'),
                        // 'MI'=>'',
                        'DEPARTMENT'=>session('LoggedUser_DepartmentName'),
                        'RM_ID'=> $request->rmID,
                        'REPORTING_MANAGER'=>$request->rmName,
                        'PROJECTID'=>'0',
                        'PROJECT'=>session('LoggedUser_DepartmentName'),
                        'COMPID'=>session('LoggedUser_CompanyID'),
                        'COMPANY'=>session('LoggedUser_CompanyName'),
                        'TYPE'=>$request->frmName,
                        'CLIENTID'=>'0',
                        'CLIENTNAME'=>session('LoggedUser_CompanyName'),
                        // 'VENDORID'=>'0',
                        // 'VENDORNAME'=>'',
                        'Max_approverCount'=>'4',
                        // 'GUID_GROUPS'=>'',
                        'DoneApproving'=>'0',
                        // 'WebpageLink'=>'pc_approve.php',
                        // 'ApprovedRemarks'=>'',
                        'Payee'=> 'N/A',
                        // 'CurrentSender'=>'0',
                        // 'CurrentReceiver'=>'0',
                        // 'NOTIFICATIONID'=>'0',
                        // 'SENDTOID'=>'0',
                        // 'NRN'=>'imported',
                        // 'imported_from_excel'=>'0',
                        // 'Amount'=>$request->amount,
    
                        // to follow
                        // 'user_grp_info' => '1', // 0 = Reporting Manager, 1 = For Approval of Management, 2 = Releasing of Cash, 3 = Initiator, 4 = Acknowledgement of Accountung
                        // 'orders'=>$x, //01234
                        // 'status' => 'Not Started' //in-progress & not started
                    );
                    }
        
                        if ($array[0]['ORDERS'] == 0){
                            $array[0]['USER_GRP_IND'] = 'Acknowledgement of Reporting Manager';
                            $array[0]['STATUS'] = 'In Progress';
                        }
                
                        if ($array[1]['ORDERS'] == 1){
                            $array[1]['USER_GRP_IND'] = 'Input of Actual Overtime (Initiator)';
                        }
                
                        if ($array[2]['ORDERS'] == 2){
                            $array[2]['USER_GRP_IND'] = 'Approval of Reporting Manager';
                        }
                
                        if ($array[3]['ORDERS'] == 3){
                            $array[3]['USER_GRP_IND'] = 'Acknowledgement of Accounting';
                        }
                
                        DB::table('general.actual_sign')->insert($array);
    
                        DB::commit();

        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
    
        if($success){
            return back()->with('form_submitted', 'Your Overtime Request was successfully submitted.');
        }
        else{
            return back()->with('form_error', 'Please complete required fields!');
        }

    }


       // approved HR
       public function approvedHR(Request $request){

       

        $otData = $request->jsonOTdata;
        $otData =json_decode($otData,true);



        if(!empty($otData)){
            // insert to hr.ot main table
            for($i = 0; $i <count($otData); $i++) {

    
                $ot_in_actual = date_create($otData[$i][6]);
                $ot_out_actual = date_create($otData[$i][7]);   


                DB::update("UPDATE humanresource.`overtime_request` SET `ot_in_actual` = '".date_format($ot_in_actual, 'Y-m-d H:i:s')."', ot_out_actual = '".date_format($ot_out_actual, 'Y-m-d H:i:s')."', ot_totalhrs_actual = '".$otData[$i][8]."' WHERE `id` = '".$otData[$i][10]."' ;");
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
                DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");


            }
        };
        // $success = true;
        // DB::beginTransaction();
        // try{    
        //     DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
        //     DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
            
        //     DB::commit();
        // }catch(\Exception $e){
        //     DB::rollback();
        //     $success = false;
        // }
        // if($success){
        //     return back()->with('form_submitted', 'The request has been Approved.');
        // }
        // else{
        //     return back()->with('form_error', 'Error in Transaction');
        // }

            return back()->with('form_submitted', 'The request has been Approved.');


    }


    // Withdraw HR
    public function withdrawHR(Request $request){
        $success = true;
        DB::beginTransaction();
        try{    

            DB::update("UPDATE humanresource.`overtime_request` a SET a.`status` = 'Withdrawn'  WHERE a.`main_id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
            DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
            WHERE a.`FRM_NAME` = '".$request->frmName."' AND a.`PROCESSID` = '".$request->main_id."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress'");


            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
    
        if($success){
            return back()->with('form_submitted', 'Your Overtime Request was successfully withdrawn.');
        }
        else{
            return back()->with('form_error', 'Please complete required fields!');
        }
    }


       // rejected HR
       public function rejectedHR(Request $request){

        $success = true;
        DB::beginTransaction();
        try{    
            DB::update("UPDATE humanresource.`overtime_request` a SET a.`status` = 'Rejected'  WHERE a.`main_id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
            DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Rejected', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
            WHERE a.`FRM_NAME` = '".$request->frmName."' AND a.`PROCESSID` = '".$request->main_id."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress'");

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'The request has been Rejected.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }
    }

    























    public function createLeave() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('HumanResourceRequest.create-leave', compact('posts'));
    
    }

    public function createItinerary() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('HumanResourceRequest.create-itinerary', compact('posts'));

    }

    public function createIncedentReport() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('HumanResourceRequest.create-incidentreport', compact('posts'));

    }

    // function createOt(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('HumanResourceRequest.create-ot', $data);
    // }

    // function createLeave(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('HumanResourceRequest.create-leave', $data);
    // }

    // function createItinerary(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('HumanResourceRequest.create-itinerary', $data);
    // }

    // function createIncedentReport(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('HumanResourceRequest.create-incidentreport', $data);
    // }
}
