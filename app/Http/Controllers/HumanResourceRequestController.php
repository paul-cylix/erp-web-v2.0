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
        // $project = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE title_id = ".session('LoggedUser_CompanyID')." AND `status` = 'Active' AND project_type IN ('Project Site', 'Non-Project') ORDER BY project_name;");
        $project = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE `status` = 'Active' AND project_type IN ('Project Site', 'Non-Project') ORDER BY project_name;");
        $managers = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");


     
        return view('HumanResourceRequest.create-ot', compact('posts','employee','project','managers'));
    }

    public function getClient($project_id){
        return json_encode(DB::select("SELECT Business_Number 'clientID', Business_fullname 'clientName' FROM general.`business_list` WHERE Business_Number = (SELECT ClientID FROM general.`setup_project` WHERE project_id = ".$project_id.");"));
    }

    public function getotdatetime($employeeID,$overtimeDate,$authTimeStart,$authTimeEnd){

        // $employeeID = '710';

        // $overtimeDate = '07-13-2021';
        // $authTimeStart = '07-13-2021%11:35%AM';
        // $authTimeEnd = '07-13-2021%1:40%PM';

        $overtimeDate = str_replace("-","/",$overtimeDate);
        $authTimeStart = str_replace("-","/",$authTimeStart);
        $authTimeEnd = str_replace("-","/",$authTimeEnd);

        $authTimeStart = str_replace("%"," ",$authTimeStart);
        $authTimeEnd = str_replace("%"," ",$authTimeEnd);

        $ot_date = date_create($overtimeDate);
        $ot_in = date_create($authTimeStart);
        $ot_out = date_create($authTimeEnd);   
        
        $overtimeDate= date_format($ot_date, 'Y-m-d');
        $authTimeStart= date_format($ot_in, 'Y-m-d H:i:s');
        $authTimeEnd= date_format($ot_out, 'Y-m-d H:i:s');

        return json_encode(DB::select("SELECT IFNULL((SELECT TRUE FROM humanresource.`overtime_request` a WHERE a.`employee_id` = '".$employeeID."' AND a.`status` IN ('In Progress', 'Completed') AND a.`overtime_date` = '".$overtimeDate."' AND (a.`ot_in` ='".$authTimeStart."' OR a.`ot_out` ='".$authTimeEnd."')), FALSE) AS checker;"));
 
    

    }



    public function saveOT(Request $request){

        $request->validate([
            'rmID'=>'required',
            // 'purpose'=>'required',
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
                        'employee_id' => $otData[$i][7],
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


// Reply HR
        public function replyHR(Request $request){
            
            $request->validate([
                'rmID'=>'required',
                // 'purpose'=>'required',
                'jsonOTdata'=>'required',           
            ]);

            $success = true;
            DB::beginTransaction();
            try{    

            
            $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
          
            $nParentId= $notif[0]->ID;
            $nReceiverId= $notif[0]->SENDERID;
            $nActualId= $notif[0]->ACTUALID;


           DB::table('general.notifications')->insert([

            'ParentID' =>$nParentId,
            'levels'=>'0',
            'FRM_NAME' =>$request->frmName,
            'PROCESSID' =>$request->main_id,
            'SENDERID' =>session('LoggedUser'),
            'RECEIVERID' =>$nReceiverId,
            'MESSAGE' =>$request->replyRemarks,
            'TS' =>NOW(),
            'SETTLED' => 'YES',
            'ACTUALID' => $nActualId,
            'SENDTOACTUALID' =>'0',
            'UserFullName' =>session('LoggedUser_FullName'),

           ]);


           $mainData = DB::table('humanresource.overtime_request')->where('main_id', $request->main_id)->first();
           DB::table('humanresource.overtime_request')->where('main_id', $request->main_id)->delete();

           $otData = $request->jsonOTdata;
           $otData =json_decode($otData,true);

           $dateRequested = date_create($mainData->request_date);
    


           for($i = 0; $i <count($otData); $i++) {

            $ot_date = date_create($otData[$i][2]);
            $ot_in = date_create($otData[$i][3]);
            $ot_out = date_create($otData[$i][4]);
            
            if (!empty($otData[$i][6]) || !empty($otData[$i][7])) {
                $ot_in_actual = date_create($otData[$i][6]);   
                $ot_out_actual = date_create($otData[$i][7]);   
                $ot_in_actual = date_format($ot_in_actual, 'Y-m-d H:i:s');
                $ot_out_actual = date_format($ot_out_actual, 'Y-m-d H:i:s');


                $setOTData[] = [
                    'reference' => $mainData->reference,
                    'request_date' => date_format($dateRequested, 'Y-m-d'),
                    'overtime_date' => date_format($ot_date, 'Y-m-d'),
                    'ot_in' => date_format($ot_in, 'Y-m-d H:i:s'),
                    'ot_out' => date_format($ot_out, 'Y-m-d H:i:s'),
                    'ot_totalhrs' => $otData[$i][5],
                    'employee_id' => $otData[$i][10],
                    'employee_name' => $otData[$i][0],
                    'purpose' => $otData[$i][9],
                    'status' => 'In Progress',
                    'UID' => session('LoggedUser'),
                    'fname' => session('LoggedUser_FirstName'), 
                    'lname' => session('LoggedUser_LastName'),
                    'department' => session('LoggedUser_DepartmentName'), 
                    'reporting_manager' => $request->rmName, 
                    'position' => session('LoggedUser_PositionName'),
                    'ts' => now(),
                    'GUID' => $mainData->GUID,
                    // 'comments' => , 
                    'ot_in_actual' => $ot_in_actual, 
                    'ot_out_actual' => $ot_out_actual,
                    'ot_totalhrs_actual' => $otData[$i][8], 
                    'main_id' => $mainData->main_id,
                    'remarks' => $request->purpose,
                    'cust_id' => $otData[$i][12],
                    'cust_name' => $otData[$i][13],
                    'TITLEID' => session('LoggedUser_CompanyID'),
                    'PRJID' => $otData[$i][11]
                ];

               
            } else {
                $setOTData[] = [
                    'reference' => $mainData->reference,
                    'request_date' => date_format($dateRequested, 'Y-m-d'),
                    'overtime_date' => date_format($ot_date, 'Y-m-d'),
                    'ot_in' => date_format($ot_in, 'Y-m-d H:i:s'),
                    'ot_out' => date_format($ot_out, 'Y-m-d H:i:s'),
                    'ot_totalhrs' => $otData[$i][5],
                    'employee_id' => $otData[$i][10],
                    'employee_name' => $otData[$i][0],
                    'purpose' => $otData[$i][9],
                    'status' => 'In Progress',
                    'UID' => session('LoggedUser'),
                    'fname' => session('LoggedUser_FirstName'), 
                    'lname' => session('LoggedUser_LastName'),
                    'department' => session('LoggedUser_DepartmentName'), 
                    'reporting_manager' => $request->rmName, 
                    'position' => session('LoggedUser_PositionName'),
                    'ts' => now(),
                    'GUID' => $mainData->GUID,
                    // 'comments' => , 
                    // 'ot_in_actual' => $ot_in_actual, 
                    // 'ot_out_actual' => $ot_out_actual,
                    // 'ot_totalhrs_actual' => $otData[$i][8], 
                    'main_id' => $mainData->main_id,
                    'remarks' => $request->purpose,
                    'cust_id' => $otData[$i][12],
                    'cust_name' => $otData[$i][13],
                    'TITLEID' => session('LoggedUser_CompanyID'),
                    'PRJID' => $otData[$i][11]
                ];
            }
            


        }
        DB::table('humanresource.overtime_request')->insert($setOTData);

        // For clarification to in progress
        DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
        WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");

        // Update form in actual sign
        DB::update("UPDATE general.`actual_sign` a 
        SET 
        a.`REMARKS` = '".$request->purpose."', 
        a.`TS` = NOW(), 
        a.`RM_ID` = '".$request->rmID."', 
        a.`REPORTING_MANAGER` = '".$request->rmName."' 
        WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."'  AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ");





        DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'The request is now For Clarification.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }


        }

// Approved Approver HR
        public function approvedApprvrHR(Request $request){
            $success = true;
            DB::beginTransaction();
            try{    

            
            $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
          
            $nParentId= $notif[0]->ID;
            $nReceiverId= $notif[0]->SENDERID;
            $nActualId= $notif[0]->ACTUALID;


            DB::table('general.notifications')->insert([

                'ParentID' =>$nParentId,
                'levels'=>'0',
                'FRM_NAME' =>$request->frmName,
                'PROCESSID' =>$request->main_id,
                'SENDERID' =>session('LoggedUser'),
                'RECEIVERID' =>$nReceiverId,
                'MESSAGE' =>$request->approveRemarks,
                'TS' =>NOW(),
                'SETTLED' => 'YES',
                'ACTUALID' => $nActualId,
                'SENDTOACTUALID' =>'0',
                'UserFullName' =>session('LoggedUser_FullName'),
    
            ]);



            DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
            WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");
    
            DB::update("UPDATE humanresource.`overtime_request` a SET a.`status` = 'In Progress' WHERE a.`main_id` = '".$request->main_id."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");

            
            DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                $success = false;
            }
            if($success){
                return back()->with('form_submitted', 'The request is now In Progress.');
            }
            else{
                return back()->with('form_error', 'Error in Transaction');
            }

        }


// Rejected by approver in Clarification
        
        public function rejectedApprvrHR(Request $request){
            $success = true;
            DB::beginTransaction();
            try{    

                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'Rejected', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' ,a.`ApprovedRemarks` = '".$request->rejectedRemarks."'
                WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ");
        
                DB::update("UPDATE humanresource.`overtime_request` a SET a.`status` = 'Rejected' WHERE a.`main_id` = '".$request->main_id."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");

                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                $success = false;
            }
            if($success){
                return back()->with('form_submitted', 'The request is now Rejected.');
            }
            else{
                return back()->with('form_error', 'Error in Transaction');
            }
        }

        
// Clarify HR

        public function clarifyHR(Request $request){

            $success = true;
            DB::beginTransaction();
            try{    
       
                $actualID = DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress'), FALSE) AS inpid;");
         
                $notificationIdClarity = DB::table('general.notifications')->insertGetId([
                    'ParentID' =>'0',
                    'levels'=>'0',
                    'FRM_NAME' =>$request->frmName,
                    'PROCESSID' =>$request->main_id,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$request->clarityRecipient,
                    'MESSAGE' =>$request->clarificationRemarks,
                    'TS' =>NOW(),
                    'SETTLED' =>'NO',
                    'ACTUALID' => $actualID[0]->inpid,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName')
                ]);
    
                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'For Clarification', a.`CurrentSender` = '".session('LoggedUser')."', a.`CurrentReceiver` = '".$request->clarityRecipient."' ,
                a.`NOTIFICATIONID` = '".$notificationIdClarity."', a.`UID_SIGN` = '".session('LoggedUser')."',a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '".$request->clarificationRemarks."' WHERE
                a.`PROCESSID` = '".$request->main_id."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`STATUS` = 'In Progress'
                ");
                
                DB::update("UPDATE humanresource.`overtime_request` a SET a.`status` =  'For Clarification' WHERE a.`main_id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
    
                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                $success = false;
            }
            if($success){
                return back()->with('form_submitted', 'The request is now For Clarification.');
            }
            else{
                return back()->with('form_error', 'Error in Transaction');
            }

        }


// approved HR
       public function approvedHR(Request $request){

        // Check if approver is the final approver for it to be completed
        $isCompleted =DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`ORDERS` = 3 AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress' AND a.`FRM_NAME` = '".$request->frmName."'), FALSE) AS tableCheck;");
        
        
        if (!empty($isCompleted[0]->tableCheck)) {
            $success = true;
            DB::beginTransaction();
            try{   

                DB::update("UPDATE general.`actual_sign` SET `DoneApproving` = '1', `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE humanresource.`overtime_request` set `status` = 'Completed' WHERE `main_id` = '".$request->main_id."';  ");

                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                $success = false;
            }
            if($success){
                return back()->with('form_submitted', 'The request has been Approved.');
            }
            else{
                return back()->with('form_error', 'Error in Transaction');
            }

        } else {

            $success = true;
            DB::beginTransaction();
            try{    
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
                DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
                
                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                $success = false;
            }
            if($success){
                return back()->with('form_submitted', 'The request has been Approved.');
            }
            else{
                return back()->with('form_error', 'Error in Transaction');
            }
        }
        

}





    public function approvedInit(Request $request){
        $otData = $request->jsonOTdata;
        $otData =json_decode($otData,true);

        if(!empty($otData)){
            // insert to hr.ot main table
            for($i = 0; $i <count($otData); $i++) {
    
                $ot_in_actual = date_create($otData[$i][6]);
                $ot_out_actual = date_create($otData[$i][7]);   

                DB::update("UPDATE humanresource.`overtime_request` SET `ot_in_actual` = '".date_format($ot_in_actual, 'Y-m-d H:i:s')."', ot_out_actual = '".date_format($ot_out_actual, 'Y-m-d H:i:s')."', ot_totalhrs_actual = '".$otData[$i][8]."' WHERE `id` = '".$otData[$i][10]."' ;");
            
            }
        };

        DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
        DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
        

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
            DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Rejected', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->rejectedRemarks. "' 
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
        $mediumofreport = DB::select("SELECT id, item FROM general.`setup_dropdown_items` WHERE `type` = 'Medium of Report' AND `status` = 'Active' ORDER BY OrderingPref ASC;");
        $employee = DB::select("SELECT SysPK_Empl, Name_Empl FROM humanresource.`employees` WHERE Status_Empl LIKE 'Active%' AND CompanyID = ".session('LoggedUser_CompanyID')." ORDER BY Name_Empl");
        $managers = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");
        $leavetype = DB::select("SELECT id, item FROM general.`setup_dropdown_items` WHERE `type` = 'Leave Type' AND `status` = 'Active' ORDER BY OrderingPref ASC;");
       
        return view('HumanResourceRequest.create-leave', compact('posts','mediumofreport','employee','managers','leavetype'));
    
    }

    public function saveLeave(Request $request){

        $request->validate([
            'rmID'=>'required',
            'employeeID'=>'required',
            'mediumofreportid'=>'required',    
            'reportTime'=>'required',
            'purpose'=>'required',  
            'jsonLeaveData'=>'required',           

        ]);

        $success = true;
        DB::beginTransaction();
        try{    
            

            $dataREQREF = DB::select("SELECT IFNULL((SELECT MAX(SUBSTRING(reference ,10)) FROM humanresource.`leave_request` WHERE YEAR(request_date)=YEAR(NOW()) AND TITLEID = ".session('LoggedUser_CompanyID')."),0) + 1 'LAF'");
            $getref = $dataREQREF[0]->LAF;
            $ref = str_pad($getref, 4, "0", STR_PAD_LEFT); 
            $ref = "LAF-" . date('Y') . "-" . $ref;

            // dd($ref);
            $mainID = DB::select("SELECT IFNULL(MAX(main_id),0) + 1 AS main FROM humanresource.`leave_request`");

            $leaveData = $request->jsonLeaveData;
            $leaveData =json_decode($leaveData,true);

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



            if(!empty($leaveData)){
            
                for($i = 0; $i <count($leaveData); $i++) {

                    $leave_date = date_create($leaveData[$i][0]);
                    $reportTime = date_create($request->reportTime);
                  
                    $setLeaveData[] = [
                        'main_id' => $mainID[0]->main,
                        // 'draft_iden' => date_format($dateRequested, 'Y-m-d'),
                        // 'draft_reference' => date_format($ot_date, 'Y-m-d'),
                        'reference' => $ref,
                        'request_date' => date_format($dateRequested, 'Y-m-d'),
                        'date_needed' => date_format($dateRequested, 'Y-m-d'),
                        'employee_id' => $request->employeeID,
                        'employee_name' => $request->employeeName,
                        'medium_of_report' => $request->mediumofreportName,
                        'report_time' => date_format($reportTime, 'Y-m-d H:i:s'),
                        'leave_type' => $leaveData[$i][1],
                        'leave_date' => date_format($leave_date, 'Y-m-d'), 
                        'leave_paytype' => $leaveData[$i][4],
                        'leave_halfday' => $leaveData[$i][2], 
                        'num_days' => $leaveData[$i][3], 
                        'reason' => $request->purpose,
                        'status' => 'In Progress',
                        'UID' => session('LoggedUser'),
                        'fname' => session('LoggedUser_FirstName'), 
                        'lname' => session('LoggedUser_LastName'), 
                        'position' => session('LoggedUser_PositionName'),
                        'reporting_manager' => $request->rmName, 
                        'department' =>session('LoggedUser_DepartmentName'),
                        'ts' => now(),
                        'GUID' => $GUID,
                        // 'comments' => $leaveData[$i][10],
                        'TITLEID' => session('LoggedUser_CompanyID')
          
                    ];
                }
                DB::table('humanresource.leave_request')->insert($setLeaveData);


            }

            //Insert general.actual_sign
            for ($x = 0; $x < 2; $x++) {
                $array[] = array(
                    'PROCESSID'=>$mainID[0]->main,
                    'USER_GRP_IND'=>'0',
                    'FRM_NAME'=>$request->frmName,
                    // 'TaskTitle'=>'',
                    // 'NS'=>'',
                    'FRM_CLASS'=>'frmLeaveApplication', //Hold
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
                    'Max_approverCount'=>'2',
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
                        $array[0]['USER_GRP_IND'] = 'Reporting Manager';
                        $array[0]['STATUS'] = 'In Progress';
                    }
            
                    if ($array[1]['ORDERS'] == 1){
                        $array[1]['USER_GRP_IND'] = 'For HR Management Approval';
                    }
            
  
            
                    DB::table('general.actual_sign')->insert($array);


                    DB::commit();

        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
    
        if($success){
            return back()->with('form_submitted', 'Your Leave Request was successfully submitted.');
        }
        else{
            return back()->with('form_error', 'Please complete required fields!');
        }
       
    }


    // Withdraw Leave
    public function withdrawLeave(Request $request){
        $success = true;
        DB::beginTransaction();
        try{    

            DB::update("UPDATE humanresource.`leave_request` a SET a.`status` = 'Withdrawn'  WHERE a.`main_id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
            DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
            WHERE a.`FRM_NAME` = '".$request->frmName."' AND a.`PROCESSID` = '".$request->main_id."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress'");


            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
    
        if($success){
            return back()->with('form_submitted', 'Your Leave Request was successfully withdrawn.');
        }
        else{
            return back()->with('form_error', 'Please complete required fields!');
        }
    }

    

    // rejected leave
    public function rejectedLeave(Request $request){

    $success = true;
    DB::beginTransaction();
    try{    
        DB::update("UPDATE humanresource.`leave_request` a SET a.`status` = 'Rejected'  WHERE a.`main_id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
        DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Rejected', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->rejectedRemarks. "' 
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


// approved HR
public function approvedLeave(Request $request){

    // Check if approver is the final approver for it to be completed
    $isInprogress =DB::select("SELECT IFNULL((SELECT TRUE FROM general.`actual_sign` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`ORDERS` = 0 AND a.`STATUS` = 'In Progress' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ), FALSE) AS tableCheck;");
    // dd($isInprogress[0]->tableCheck);
    
    if (!empty($isInprogress[0]->tableCheck)) {

        $success = true;
        DB::beginTransaction();
        try{    
            DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
            DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
            
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'The request has been Approved.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }

    } else {

        $success = true;
        DB::beginTransaction();
        try{   

            DB::update("UPDATE general.`actual_sign` SET `DoneApproving` = '1', `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
            DB::update("UPDATE humanresource.`leave_request` set `status` = 'Completed' WHERE `main_id` = '".$request->main_id."';  ");

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'The request has been Approved.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }

    }
    
}


public function clarifyLeave(Request $request){

    $success = true;
    DB::beginTransaction();
    try{    

        $actualID = DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress'), FALSE) AS inpid;");
 
        $notificationIdClarity = DB::table('general.notifications')->insertGetId([
            'ParentID' =>'0',
            'levels'=>'0',
            'FRM_NAME' =>$request->frmName,
            'PROCESSID' =>$request->main_id,
            'SENDERID' =>session('LoggedUser'),
            'RECEIVERID' =>$request->clarityRecipient,
            'MESSAGE' =>$request->clarificationRemarks,
            'TS' =>NOW(),
            'SETTLED' =>'NO',
            'ACTUALID' => $actualID[0]->inpid,
            'SENDTOACTUALID' =>'0',
            'UserFullName' =>session('LoggedUser_FullName')
        ]);

        DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'For Clarification', a.`CurrentSender` = '".session('LoggedUser')."', a.`CurrentReceiver` = '".$request->clarityRecipient."' ,
        a.`NOTIFICATIONID` = '".$notificationIdClarity."', a.`UID_SIGN` = '".session('LoggedUser')."',a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '".$request->clarificationRemarks."' WHERE
        a.`PROCESSID` = '".$request->main_id."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`STATUS` = 'In Progress'
        ");
        
        DB::update("UPDATE humanresource.`leave_request` a SET a.`status` =  'For Clarification' WHERE a.`main_id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");

        DB::commit();
    }catch(\Exception $e){
        DB::rollback();
        $success = false;
    }
    if($success){
        return back()->with('form_submitted', 'The request is now For Clarification.');
    }
    else{
        return back()->with('form_error', 'Error in Transaction');
    }

}



    // Withdraw Leave
    public function withdrawInitLeave(Request $request){
        $success = true;
        DB::beginTransaction();
        try{    

            DB::update("UPDATE humanresource.`leave_request` a SET a.`status` = 'Withdrawn'  WHERE a.`main_id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
            DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
            WHERE a.`FRM_NAME` = '".$request->frmName."' AND a.`PROCESSID` = '".$request->main_id."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");


            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
    
        if($success){
            return back()->with('form_submitted', 'Your Leave Request was successfully withdrawn.');
        }
        else{
            return back()->with('form_error', 'Please complete required fields!');
        }
    }


    public function replyInitLeave(Request $request){
            
        $request->validate([
            'rmID'=>'required',
            'employeeID'=>'required',
            'mediumofreportid'=>'required',    
            'reportTime'=>'required',
            'purpose'=>'required',  
            'jsonLeaveData'=>'required',   
        ]);

        // dd(
        //     $request->rmID,
        //     $request->rmName,
        //     $request->employeeID,
        //     $request->employeeName,
        //     $request->mediumofreportid,
        //     $request->mediumofreportName,
        //     $request->reportTime,
        //     $request->purpose,
        //     $request->jsonLeaveData,
        //     $request->replyRemarks
        // );

        $success = true;
        DB::beginTransaction();
        try{    

        
        $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
      
        $nParentId= $notif[0]->ID;
        $nReceiverId= $notif[0]->SENDERID;
        $nActualId= $notif[0]->ACTUALID;


       DB::table('general.notifications')->insert([

        'ParentID' =>$nParentId,
        'levels'=>'0',
        'FRM_NAME' =>$request->frmName,
        'PROCESSID' =>$request->main_id,
        'SENDERID' =>session('LoggedUser'),
        'RECEIVERID' =>$nReceiverId,
        'MESSAGE' =>$request->replyRemarks,
        'TS' =>NOW(),
        'SETTLED' => 'YES',
        'ACTUALID' => $nActualId,
        'SENDTOACTUALID' =>'0',
        'UserFullName' =>session('LoggedUser_FullName'),

       ]);


            $mainData = DB::table('humanresource.leave_request')->where('main_id', $request->main_id)->first();
            DB::table('humanresource.leave_request')->where('main_id', $request->main_id)->delete();

            $leaveData = $request->jsonLeaveData;
            $leaveData =json_decode($leaveData,true);
            $dateRequested = date_create($request->dateRequested);

            $leaveData = $request->jsonLeaveData;
            $leaveData =json_decode($leaveData,true);
            for($i = 0; $i <count($leaveData); $i++) {

                    $leave_date = date_create($leaveData[$i][0]);
                    $reportTime = date_create($request->reportTime);
                
                    $setLeaveData[] = [
                        'main_id' => $mainData->main_id,
                        // 'draft_iden' => date_format($dateRequested, 'Y-m-d'),
                        // 'draft_reference' => date_format($ot_date, 'Y-m-d'),
                        'reference' => $mainData->reference,
                        'request_date' => date_format($dateRequested, 'Y-m-d'),
                        'date_needed' => date_format($dateRequested, 'Y-m-d'),
                        'employee_id' => $request->employeeID,
                        'employee_name' => $request->employeeName,
                        'medium_of_report' => $request->mediumofreportName,
                        'report_time' => date_format($reportTime, 'Y-m-d H:i:s'),
                        'leave_type' => $leaveData[$i][1],
                        'leave_date' => date_format($leave_date, 'Y-m-d'), 
                        'leave_paytype' => $leaveData[$i][4],
                        'leave_halfday' => $leaveData[$i][2], 
                        'num_days' => $leaveData[$i][3], 
                        'reason' => $request->purpose,
                        'status' => 'In Progress',
                        'UID' => session('LoggedUser'),
                        'fname' => session('LoggedUser_FirstName'), 
                        'lname' => session('LoggedUser_LastName'), 
                        'position' => session('LoggedUser_PositionName'),
                        'reporting_manager' => $request->rmName, 
                        'department' =>session('LoggedUser_DepartmentName'),
                        'ts' => now(),
                        'GUID' => $mainData->GUID,
                        // 'comments' => $leaveData[$i][10],
                        'TITLEID' => session('LoggedUser_CompanyID')

                    ];
                }
                DB::table('humanresource.leave_request')->insert($setLeaveData);




    // For clarification to in progress
    DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
    WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");

    // Update form in actual sign
    DB::update("UPDATE general.`actual_sign` a 
    SET 
    a.`REMARKS` = '".$request->purpose."', 
    a.`TS` = NOW(), 
    a.`RM_ID` = '".$request->rmID."', 
    a.`REPORTING_MANAGER` = '".$request->rmName."' 
    WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."'  AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ");





    DB::commit();
    }catch(\Exception $e){
        DB::rollback();
        $success = false;
    }
    if($success){
        return back()->with('form_submitted', 'The request is now For Clarification.');
    }
    else{
        return back()->with('form_error', 'Error in Transaction');
    }


    }








    
    

    
// Approved Approver Leave
public function approvedApprvrLeave(Request $request){
    $success = true;
    DB::beginTransaction();
    try{    

    
    $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
  
    $nParentId= $notif[0]->ID;
    $nReceiverId= $notif[0]->SENDERID;
    $nActualId= $notif[0]->ACTUALID;


    DB::table('general.notifications')->insert([

        'ParentID' =>$nParentId,
        'levels'=>'0',
        'FRM_NAME' =>$request->frmName,
        'PROCESSID' =>$request->main_id,
        'SENDERID' =>session('LoggedUser'),
        'RECEIVERID' =>$nReceiverId,
        'MESSAGE' =>$request->approveRemarks,
        'TS' =>NOW(),
        'SETTLED' => 'YES',
        'ACTUALID' => $nActualId,
        'SENDTOACTUALID' =>'0',
        'UserFullName' =>session('LoggedUser_FullName'),

    ]);



    DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
    WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");

    DB::update("UPDATE humanresource.`leave_request` a SET a.`status` = 'In Progress' WHERE a.`main_id` = '".$request->main_id."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");

    
    DB::commit();
    }catch(\Exception $e){
        DB::rollback();
        $success = false;
    }
    if($success){
        return back()->with('form_submitted', 'The request is now In Progress.');
    }
    else{
        return back()->with('form_error', 'Error in Transaction');
    }

}


// Rejected by approver in Clarification

public function rejectedApprvrLeave(Request $request){
    $success = true;
    DB::beginTransaction();
    try{    

        DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'Rejected', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' ,a.`ApprovedRemarks` = '".$request->rejectedRemarks."'
        WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ");

        DB::update("UPDATE humanresource.`leave_request` a SET a.`status` = 'Rejected' WHERE a.`main_id` = '".$request->main_id."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");

        DB::commit();
    }catch(\Exception $e){
        DB::rollback();
        $success = false;
    }
    if($success){
        return back()->with('form_submitted', 'The request is now Rejected.');
    }
    else{
        return back()->with('form_error', 'Error in Transaction');
    }
}



    public function createItinerary() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        $employee = DB::select("SELECT SysPK_Empl, Name_Empl FROM humanresource.`employees` WHERE Status_Empl LIKE 'Active%' AND CompanyID = ".session('LoggedUser_CompanyID')." ORDER BY Name_Empl");
        $project = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE title_id = ".session('LoggedUser_CompanyID')." AND `status` = 'Active' AND project_type IN ('Project Site', 'Non-Project') ORDER BY project_name;");
        $managers = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` a WHERE a.`UID` = '" . session('LoggedUser') . "' ORDER BY RMName");
        $businesslist = DB::select("SELECT * FROM general.`business_list` a WHERE a.`status` LIKE 'Active%' AND a.`title_id` = '".session('LoggedUser_CompanyID')."' AND a.`Type` = 'CLIENT' ORDER BY a.`business_fullname` ASC");
        
        return view('HumanResourceRequest.create-itinerary', compact('posts','managers','businesslist'));

    }


    public function saveItinerary(Request $request){
        $request->validate([
            'rmID'=>'required',
            'jsonitineraryData'=>'required',           
        ]);

        $success = true;
        DB::beginTransaction();
        try{    

            $dataREQREF = DB::select("SELECT IFNULL((SELECT MAX(SUBSTRING(reference ,10)) FROM humanresource.`itinerary_main` WHERE YEAR(request_date)=YEAR(NOW()) AND TITLEID = '".session('LoggedUser_CompanyID')."'),0) + 1 'ITF'");
            $getref = $dataREQREF[0]->ITF;
            $ref = str_pad($getref, 4, "0", STR_PAD_LEFT); 
            $ref = "ITF-" . date('Y') . "-" . $ref;

            // dd($ref);

            $itineraryData = $request->jsonitineraryData;
            $itineraryData =json_decode($itineraryData,true);
       

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
            
           


            $itfID = DB::table('humanresource.itinerary_main')->insertGetId([
                // 'draft_iden' => date_format($dateRequested, 'Y-m-d'),
                // 'draft_reference' => $ref,
                'reference' => $ref,
                'request_date' => date_format($dateRequested, 'Y-m-d H:i:s'),
                'date_needed' => date_format($dateRequested, 'Y-m-d H:i:s'),
                'status' => 'In Progress',
                'UID' => session('LoggedUser'),
                'fname' => session('LoggedUser_FirstName'),
                'lname' => session('LoggedUser_LastName'),
                'department' => session('LoggedUser_DepartmentName'),
                'reporting_manager' => $request->rmName,
                'position' => session('LoggedUser_PositionName'),
                'ts' => now(),
                'GUID' => $GUID,
                // 'comments' => '1',
                'TITLEID' => session('LoggedUser_CompanyID')

            ]);

         
               for($i = 0; $i <count($itineraryData); $i++) {

                    $ot_in = date_create($itineraryData[$i][2]);
                    $ot_out = date_create($itineraryData[$i][3]);                    

                    $setItineraryData[] = [
                        'main_id' => $itfID,
                        'client_id' => $itineraryData[$i][0],
                        'client_name' => $itineraryData[$i][1],
                        'time_start' => date_format($ot_in, 'Y-m-d H:i:s'),
                        'time_end' => date_format($ot_out, 'Y-m-d H:i:s'),
                        // 'actual_start' => $itineraryData[$i][0],
                        // 'actual_end' => $itineraryData[$i][6],
                        'purpose' => $itineraryData[$i][4],
                        'ts' => now()
                        // 'updated_by' => session('LoggedUser_FirstName'), 
                        // 'updated_ts' => session('LoggedUser_LastName'),
                    ];
                }
                DB::table('humanresource.itinerary_details')->insert($setItineraryData);



                //Insert general.actual_sign
                for ($x = 0; $x < 5; $x++) {
                    $array[] = array(
                        'PROCESSID'=>$itfID,
                        'USER_GRP_IND'=>'0',
                        'FRM_NAME'=>$request->frmName,
                        // 'TaskTitle'=>'',
                        // 'NS'=>'',
                        'FRM_CLASS'=>'frmItinerary', //Hold
                        // 'REMARKS'=>$request->purpose,
                        'STATUS'=>'Not Started',
                        // 'UID_SIGN'=>'0',
                        'TS'=>now(),
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
                        'PROJECTID'=>'1',
                        'PROJECT'=>session('LoggedUser_DepartmentName'),
                        'COMPID'=>session('LoggedUser_CompanyID'),
                        'COMPANY'=>session('LoggedUser_CompanyName'),
                        'TYPE'=>$request->frmName,
                        'CLIENTID'=>'1',
                        'CLIENTNAME'=>session('LoggedUser_CompanyName'),
                        // 'VENDORID'=>'0',
                        // 'VENDORNAME'=>'',
                        'Max_approverCount'=>'5',
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
                            $array[0]['USER_GRP_IND'] = 'Approval of Reporting Manager';
                            $array[0]['STATUS'] = 'In Progress';
                        }
                
                        if ($array[1]['ORDERS'] == 1){
                            $array[1]['USER_GRP_IND'] = 'Input of Actual Time (Initiator)';
                        }
                
                        if ($array[2]['ORDERS'] == 2){
                            $array[2]['USER_GRP_IND'] = 'Approval of Reporting Manager';
                        }
                
                        if ($array[3]['ORDERS'] == 3){
                            $array[3]['USER_GRP_IND'] = 'Acknowledgement of Human Resource';
                        }

                        if ($array[4]['ORDERS'] == 4){
                            $array[4]['USER_GRP_IND'] = 'Acknowledgement of Accounting';
                        }
                
                        DB::table('general.actual_sign')->insert($array);

 
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'Your Itinerary Request was successfully submitted.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }


    }

    
    // Withdraw HR
    public function withdrawItinerary(Request $request){
        $success = true;
        DB::beginTransaction();
        try{    

            DB::update("UPDATE humanresource.`itinerary_main` a SET a.`status` = 'Withdrawn'  WHERE a.`id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
            DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
            WHERE a.`FRM_NAME` = '".$request->frmName."' AND a.`PROCESSID` = '".$request->main_id."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress'");


            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
    
        if($success){
            return back()->with('form_submitted', 'Your Itinerary Request was successfully withdrawn.');
        }
        else{
            return back()->with('form_error', 'Please complete required fields!');
        }
    }





    // rejected Itinerary
    public function rejectedItinerary(Request $request){

        $success = true;
        DB::beginTransaction();
        try{    
            DB::update("UPDATE humanresource.`itinerary_main` a SET a.`status` = 'Rejected'  WHERE a.`id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
            DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Rejected', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->rejectedRemarks. "' 
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




    // approved Itinerary
    public function approvedItinerary(Request $request){

        // Check if approver is the final approver for it to be completed
        $isInprogress =DB::select("SELECT IFNULL((SELECT TRUE FROM general.`actual_sign` a WHERE a.`PROCESSID` = '".$request->main_id."'  AND a.`FRM_NAME` = '".$request->frmName."' AND a.`STATUS` = 'In Progress' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`ORDERS` = 4), FALSE) AS tableCheck;");
        // dd($isInprogress[0]->tableCheck);
        
        if (empty($isInprogress[0]->tableCheck)) {

            $success = true;
            DB::beginTransaction();
            try{    
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
                DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
                
                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                $success = false;
            }
            if($success){
                return back()->with('form_submitted', 'The request has been Approved.');
            }
            else{
                return back()->with('form_error', 'Error in Transaction');
            }

        } else {

            $success = true;
            DB::beginTransaction();
            try{   

                DB::update("UPDATE general.`actual_sign` SET `DoneApproving` = '1', `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE humanresource.`itinerary_main` set `status` = 'Completed' WHERE `id` = '".$request->main_id."';  ");

                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                $success = false;
            }
            if($success){
                return back()->with('form_submitted', 'The request has been Approved.');
            }
            else{
                return back()->with('form_error', 'Error in Transaction');
            }

        }
        
    }



    public function approvedItineraryInit(Request $request){
        $itineraryData = $request->jsonItineraryData;
        $itineraryData =json_decode($itineraryData,true);
        $success = true;
        DB::beginTransaction();
        try{   

        if(!empty($itineraryData)){
            // insert to hr.ot main table
            for($i = 0; $i <count($itineraryData); $i++) {
    
                $ot_in_actual = date_create($itineraryData[$i][4]);
                $ot_out_actual = date_create($itineraryData[$i][5]);   

                DB::update("UPDATE humanresource.`itinerary_details` SET `actual_start` = '".date_format($ot_in_actual, 'Y-m-d H:i:s')."', actual_end = '".date_format($ot_out_actual, 'Y-m-d H:i:s')."' WHERE `id` = '".$itineraryData[$i][7]."' ;");
            }
        };

        DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approveRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
        DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->main_id."' AND `FRM_NAME` = '".$request->frmName."' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
        
        DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'The request has been Approved.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }

    }


    
    public function clarifyItinerary(Request $request){

        $success = true;
        DB::beginTransaction();
        try{    
   
            $actualID = DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress'), FALSE) AS inpid;");
     
            $notificationIdClarity = DB::table('general.notifications')->insertGetId([
                'ParentID' =>'0',
                'levels'=>'0',
                'FRM_NAME' =>$request->frmName,
                'PROCESSID' =>$request->main_id,
                'SENDERID' =>session('LoggedUser'),
                'RECEIVERID' =>$request->clarityRecipient,
                'MESSAGE' =>$request->clarificationRemarks,
                'TS' =>NOW(),
                'SETTLED' =>'NO',
                'ACTUALID' => $actualID[0]->inpid,
                'SENDTOACTUALID' =>'0',
                'UserFullName' =>session('LoggedUser_FullName')
            ]);

            DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'For Clarification', a.`CurrentSender` = '".session('LoggedUser')."', a.`CurrentReceiver` = '".$request->clarityRecipient."' ,
            a.`NOTIFICATIONID` = '".$notificationIdClarity."', a.`UID_SIGN` = '".session('LoggedUser')."',a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '".$request->clarificationRemarks."' WHERE
            a.`PROCESSID` = '".$request->main_id."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`STATUS` = 'In Progress'
            ");
            
            DB::update("UPDATE humanresource.`itinerary_main` a SET a.`status` =  'For Clarification' WHERE a.`id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'The request is now For Clarification.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }

    }



    public function replyItinerary(Request $request){
        $request->validate([
            'rmID'=>'required',
            'jsonitineraryData'=>'required',           
        ]);

        
        $success = true;
        DB::beginTransaction();
        try{    

            $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
          
            $nParentId= $notif[0]->ID;
            $nReceiverId= $notif[0]->SENDERID;
            $nActualId= $notif[0]->ACTUALID;


           DB::table('general.notifications')->insert([

            'ParentID' =>$nParentId,
            'levels'=>'0',
            'FRM_NAME' =>$request->frmName,
            'PROCESSID' =>$request->main_id,
            'SENDERID' =>session('LoggedUser'),
            'RECEIVERID' =>$nReceiverId,
            'MESSAGE' =>$request->replyRemarks,
            'TS' =>NOW(),
            'SETTLED' => 'YES',
            'ACTUALID' => $nActualId,
            'SENDTOACTUALID' =>'0',
            'UserFullName' =>session('LoggedUser_FullName'),

           ]);


           $mainData = DB::table('humanresource.overtime_request')->where('main_id', $request->main_id)->first();
           DB::table('humanresource.itinerary_details')->where('main_id', $request->main_id)->delete();

           $otData = $request->jsonOTdata;
           $otData =json_decode($otData,true);
    

           $itineraryData = $request->jsonitineraryData;
           $itineraryData =json_decode($itineraryData,true);
      



        
              for($i = 0; $i <count($itineraryData); $i++) {

                   $time_start = date_create($itineraryData[$i][2]);
                   $time_end = date_create($itineraryData[$i][3]);                    

                if (!empty($itineraryData[$i][4]) || !empty($itineraryData[$i][5])) {

                    $actual_start = date_create($itineraryData[$i][4]);
                    $actual_end = date_create($itineraryData[$i][5]);   

                    $setItineraryData[] = [
                        'main_id' => $request->main_id,
                        'client_id' => $itineraryData[$i][0],
                        'client_name' => $itineraryData[$i][1],
                        'time_start' => date_format($time_start, 'Y-m-d H:i:s'),
                        'time_end' => date_format($time_end, 'Y-m-d H:i:s'),
                        'actual_start' => date_format($actual_start, 'Y-m-d H:i:s'),
                        'actual_end' => date_format($actual_end, 'Y-m-d H:i:s'),
                        'purpose' => $itineraryData[$i][6],
                        'ts' => now()
                        // 'updated_by' => session('LoggedUser_FirstName'), 
                        // 'updated_ts' => session('LoggedUser_LastName'),
                    ];
                } else {
                    $setItineraryData[] = [
                        'main_id' => $request->main_id,
                        'client_id' => $itineraryData[$i][0],
                        'client_name' => $itineraryData[$i][1],
                        'time_start' => date_format($time_start, 'Y-m-d H:i:s'),
                        'time_end' => date_format($time_end, 'Y-m-d H:i:s'),
                        // 'actual_start' => $itineraryData[$i][0],
                        // 'actual_end' => $itineraryData[$i][6],
                        'purpose' => $itineraryData[$i][6],
                        'ts' => now()
                        // 'updated_by' => session('LoggedUser_FirstName'), 
                        // 'updated_ts' => session('LoggedUser_LastName'),
                    ];
                }
                    

               }
               DB::table('humanresource.itinerary_details')->insert($setItineraryData);




        // For clarification to in progress
        DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
        WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");

        // Update form in actual sign
        DB::update("UPDATE general.`actual_sign` a 
        SET 
        a.`TS` = NOW(), 
        a.`RM_ID` = '".$request->rmID."', 
        a.`REPORTING_MANAGER` = '".$request->rmName."' 
        WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."'  AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ");

        DB::update("UPDATE humanresource.`itinerary_main` a SET a.`status` = 'In Progress' , a.`reporting_manager` = '".$request->rmName."' WHERE a.`id` = '".$request->main_id."';");


            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'The request is now For Clarification.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }
    }


    // Withdraw HR
    public function withdrawItineraryInit(Request $request){
        $success = true;
        DB::beginTransaction();
        try{    

            DB::update("UPDATE humanresource.`itinerary_main` a SET a.`status` = 'Withdrawn'  WHERE a.`id` = '".$request->main_id."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
            DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
            WHERE a.`FRM_NAME` = '".$request->frmName."' AND a.`PROCESSID` = '".$request->main_id."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");


            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'Your Itinerary Request was successfully withdrawn.');
        }
        else{
            return back()->with('form_error', 'Please complete required fields!');
        }
    }


    // Approved Approver Leave
    public function approvedApprvrItinerary(Request $request){
        $success = true;
        DB::beginTransaction();
        try{    
        
        $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
    
        $nParentId= $notif[0]->ID;
        $nReceiverId= $notif[0]->SENDERID;
        $nActualId= $notif[0]->ACTUALID;

        DB::table('general.notifications')->insert([

            'ParentID' =>$nParentId,
            'levels'=>'0',
            'FRM_NAME' =>$request->frmName,
            'PROCESSID' =>$request->main_id,
            'SENDERID' =>session('LoggedUser'),
            'RECEIVERID' =>$nReceiverId,
            'MESSAGE' =>$request->approveRemarks,
            'TS' =>NOW(),
            'SETTLED' => 'YES',
            'ACTUALID' => $nActualId,
            'SENDTOACTUALID' =>'0',
            'UserFullName' =>session('LoggedUser_FullName'),

        ]);

        DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
        WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");

        DB::update("UPDATE humanresource.`itinerary_main` a SET a.`status` = 'In Progress' WHERE a.`id` = '".$request->main_id."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");

        
        DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'The request is now In Progress.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }

    }


    // Rejected by approver in Clarification

    public function rejectedApprvrItinerary(Request $request){
        $success = true;
        DB::beginTransaction();
        try{    

            DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'Rejected', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' ,a.`ApprovedRemarks` = '".$request->rejectedRemarks."'
            WHERE a.`PROCESSID` = '".$request->main_id."' AND a.`FRM_NAME` = '".$request->frmName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification' ");

            DB::update("UPDATE humanresource.`itinerary_main` a SET a.`status` = 'Rejected' WHERE a.`id` = '".$request->main_id."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $success = false;
        }
        if($success){
            return back()->with('form_submitted', 'The request is now Rejected.');
        }
        else{
            return back()->with('form_error', 'Error in Transaction');
        }
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
