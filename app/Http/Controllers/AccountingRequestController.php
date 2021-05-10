<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestForPayment;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\PseudoTypes\True_;

class AccountingRequestController extends Controller
{

    function addRFPData(Request $request) {
        $rfp = new RequestForPayment;
        $rfp->DATE = $request->dateRequested;
        $rfp->REQREF = $request->referenceNumber;
        $rfp->Deadline = $request->dateNeeded;
        $rfp->AMOUNT = floatval($request->amount);
        $rfp->STATUS = 'In Progress';
        $rfp->UID = Auth::user()->id;
        $rfp->UID = Auth::user()->name;
    }

    // Accounting and finance
    // public function gotoRFP() { 
    //     $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
    //     return view('AccountingRequest.create-rfp', compact('posts'));
    // }

        // Get data Payments
        public function getReportingMgr(){
            $mgrs = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");
            $projects = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE project_type <> 'MAIN OFFICE' AND `status` = 'Active' AND title_id = 1 ORDER BY project_name");
            // $dataREQREF = DB::select("SELECT SUBSTRING(REQREF ,10) AS 'myReqRef' FROM accounting.`request_for_payment` ORDER BY `REQREF` DESC LIMIT 1");
            // $myReqRef = json_encode($dataREQREF);
            $dataREQREF = DB::select("SELECT IFNULL((SELECT MAX(SUBSTRING(REQREF ,10)) 
             FROM accounting.`request_for_payment` WHERE YEAR(TS)=YEAR(NOW()) AND TITLEID = '1'),0) + 1 'REF'");
            $result = $dataREQREF;
            $ref = $result[0]->REF;
            $ref1 = str_pad($ref, 4, "0", STR_PAD_LEFT); 
            $ref1 = "RFP-" . date('Y') . "-" . $ref1;
            $expenseType = DB::select("SELECT type FROM accounting.`expense_type_setup`");
            $currencyType = DB::select("SELECT CurrencyName FROM accounting.`currencysetup`");
            return view('AccountingRequest.create-rfp', compact('mgrs','projects','expenseType','currencyType'),['ref1' => $ref1]);
        }

        public function getClientName($prjid) {
            $clientNames = DB::select("SELECT Business_Number as 'clientID', ifnull(business_fullname, '') AS 'clientName', (SELECT Main_office_id FROM general.`setup_project` WHERE `project_id` = '" . $prjid . "' LIMIT 1) as 'mainID' FROM general.`business_list` WHERE Business_Number IN (SELECT `ClientID` FROM general.`setup_project` WHERE `project_id` = '" . $prjid . "')");
            if(count($clientNames) > 0) {
                return $clientNames;
            } else {
                return '';
            }
        }
    
        // public function getReference($reqForm) {
        //     if ($reqForm == 'RFP') {
        //         $queryREF = DB::select("SELECT LPAD(IFNULL((SELECT MAX(SUBSTRING(REQREF, 10)) FROM accounting.`request_for_payment` WHERE YEAR(TS)=YEAR(CURDATE()) AND TITLEID = " .session('LoggedUser'). " AND  (REQREF  NOT  LIKE '%AM%' OR REQREF  NOT  LIKE '%PM%')), 0) + 1, 4, 0) 'REF'");
        //         $REF = 'RFP-' . date('Y') . '-' . $queryREF[0]->REF;
        //         return $REF;
        //     }
        // }

        // public function getREQREF(){
        //     $dataREQREF = DB::select("SELECT SUBSTRING(REQREF ,10) AS 'myReqRef' FROM accounting.`request_for_payment` ORDER BY `REQREF` DESC LIMIT 1");
        //     $myReqRef = json_encode($dataREQREF);
        //     return $myReqRef;
        //     // $result = (int)$dataREQREF;
        //     // $reqRefValue = 'RFP-'. date('Y') . '-' .$result++;
        //     // return $reqRefValue;
        // }

        // public function setREQREF($value){
        //     $dataREQREF = DB::select("SELECT SUBSTRING(REQREF ,10) FROM accounting.`request_for_payment` ORDER BY `REQREF` DESC LIMIT 1");
        //     $this->attribute['REQREF'] = $value++;
        //     return $dataREQREF;
        // }

    
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


        // INPUT DATA PAYMENTS
        public function saveRFP(Request $request){

            $request->validate([
                'reportingManager'=>'required',
                'projectName'=>'required',
                'dateNeeded'=>'required',
                'payeeName'=>'required',
                'currency'=>'required',
                'modeOfPayment'=>'required',
                'amount'=>'required',
                'purpose'=>'required'
            ]);

            $dateRequested = date_create($request->dateRequested);
            $dateNeeded = date_create($request->dateNeeded);
    
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
            
            // session()->put('guidSession', '');
            // session()->put('guidSession',$GUID);

            // Insert -> Request for Payment
            // $rfpID = '';
            $rfpID = DB::table('accounting.request_for_payment')->insertGetId([
                'DATE' => date_format($dateRequested, 'Y-m-d'),
                'REQREF' => $request->referenceNumber,
                'Deadline' => date_format($dateNeeded, 'Y-m-d'),
                'AMOUNT' => number_format($request->amount, 2, '.', ''),
                'STATUS' => 'In Progress',
                'UID' => session('LoggedUser'),
                'FNAME' => session('LoggedUser_FirstName'),
                'LNAME' => session('LoggedUser_LastName'),
                'DEPARTMENT' => session('LoggedUser_DepartmentName'),
                'REPORTING_MANAGER' => $request->RMName,
                'POSITION' => session('LoggedUser_PositionName'),
                'GUID' => $GUID,
                'ISRELEASED' => '0',
                'TITLEID' => '1'
            ]);
            
            $project_name = DB::select("SELECT project_name FROM general.`setup_project` WHERE `project_id` = '" . $request->projectName . "'");             
            
            // Insert -> RFP details
            DB::table('accounting.rfp_details')->insert([
                'RFPID' => $rfpID,
                'PROJECTID' =>$request->projectName, 
                'ClientID' => $request->clientID, 
                'CLIENTNAME' =>$request->clientName,
                'TITLEID' => session('LoggedUser_CompanyID'),
                'PAYEEID' =>'0',
                'MAINID' =>$request->mainID,
                'PROJECT' =>$project_name[0]->project_name, 
                'DATENEEDED' =>date_format($dateNeeded, 'Y-m-d'),
                'PAYEE' =>session('LoggedUser_FullName'),
                'MOP' => $request->modeOfPayment,
                'PURPOSED' => $request->purpose,
                'DESCRIPTION' =>$request->purpose,
                'CURRENCY' =>$request->currency,
                'currency_id' =>'0',
                'AMOUNT' =>$request->amount,
                'STATUS' => 'ACTIVE',
                'GUID' => $GUID,
                'RELEASEDCASH' =>'0',
                
            ]);
            
            // session()->put('rfpidSession', '');

            
            // $reporting_manager_name = DB::select("SELECT RMID,RMName FROM general.`systemreportingmanager` WHERE RMID = '".."'     ");
            

            //Insert general.actual_sign
            $array = array();

            for ($x = 0; $x < 5; $x++) {
                $array[] = array(
                    'PROCESSID'=>$rfpID,
                    'USER_GRP_IND'=>'0',
                    'FRM_NAME'=>'Request for Payment',
                    'TaskTitle'=>'',
                    'NS'=>'',
                    'FRM_CLASS'=>'REQUESTFORPAYMENT',
                    'REMARKS'=>$request->purpose,
                    'STATUS'=>'Not Started',
                    // 'UID_SIGN'=>'0',
                    // 'TS'=>'',
                    'DUEDATE'=>date_format($dateNeeded, 'Y-m-d'),
                    // 'SIGNDATETIME'=>'',
                    'ORDERS'=>$x,
                    'REFERENCE'=>$request->referenceNumber,
                    'PODATE'=>date_format($dateNeeded, 'Y-m-d'),
                    // 'PONUM'=>'',
                    'DATE'=>date_format($dateNeeded, 'Y-m-d'),
                    'INITID'=>session('LoggedUser'),
                    'FNAME'=>session('LoggedUser_FirstName'),
                    'LNAME'=>session('LoggedUser_LastName'),
                    // 'MI'=>'',
                    'DEPARTMENT'=>session('LoggedUser_DepartmentName'),
                    'RM_ID'=> $request->reportingManager,
                    'REPORTING_MANAGER'=>$request->RMName,
                    'PROJECTID'=>$request->projectName,
                    'PROJECT'=>$project_name[0]->project_name,
                    'COMPID'=>session('LoggedUser_CompanyID'),
                    'COMPANY'=>session('LoggedUser_CompanyName'),
                    'TYPE'=>'Request for Payment',
                    'CLIENTID'=>$request->clientID,
                    'CLIENTNAME'=>$request->clientName,
                    // 'VENDORID'=>'0',
                    // 'VENDORNAME'=>'',
                    'Max_approverCount'=>'5',
                    // 'GUID_GROUPS'=>'',
                    'DoneApproving'=>'0',
                    'WebpageLink'=>'rfp_approve.php',
                    // 'ApprovedRemarks'=>'',
                    'Payee'=>$request->payeeName,
                    // 'CurrentSender'=>'0',
                    // 'CurrentReceiver'=>'0',
                    // 'NOTIFICATIONID'=>'0',
                    // 'SENDTOID'=>'0',
                    // 'NRN'=>'imported',
                    // 'imported_from_excel'=>'0',
                    'Amount'=>$request->amount,

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
                $array[1]['USER_GRP_IND'] = 'For Approval of Management';
            }
    
            if ($array[2]['ORDERS'] == 2){
                $array[2]['USER_GRP_IND'] = 'Releasing of Cash';
            }
    
            if ($array[3]['ORDERS'] == 3){
                $array[3]['USER_GRP_IND'] = 'Initiator';
            }
    
            if ($array[4]['ORDERS'] == 4){
                $array[4]['USER_GRP_IND'] = 'Acknowledgement of Accounting';
            }
    
            DB::table('general.actual_sign')->insert($array);



            // Liquidation Table
            // $liquidationDataTable = $request->liquidationTable;
            // $liquidationDataTable =json_decode($liquidationDataTable,true);

            // $liqdata = [];
            // for($i = 0; $i <count($liquidationDataTable); $i++) {
            //     $liqdata[] = [
            //         'RFPID' => $rfpID,
            //         'trans_date'=>$liquidationDataTable[$i][0],
            //         'client_id' => $request->clientID,
            //         'client_name' =>$request->clientName,
            //         'description'=>$liquidationDataTable[$i][2],
            //         'amt_due_to_comp' =>'0',
            //         'amt_due_to_emp' =>'0',
            //         'date_' =>$liquidationDataTable[$i][0],
            //         'Amount' =>$liquidationDataTable[$i][4],
            //         'STATUS'=>'ACTIVE',
            //         'ts' => now(),
            //         'ISLIQUIDATED' => '0',
            //         'currency_id' =>'0',
            //         'currency' =>$liquidationDataTable[$i][3],
            //         'expense_type'=> $liquidationDataTable[$i][1],
            //         // // 'date' =>$liquidationDataTable[$i][0],
            //         // 'expense_type'=>$liquidationDataTable[$i][1],
            //         // // 'description'=>$liquidationDataTable[$i][2],
            //         // 'currency'=>$liquidationDataTable[$i][3],
            //         // 'amount'=>$liquidationDataTable[$i][4],
            //     ];
            // }

            // DB::table('accounting.rfp_liquidation')->insert($liqdata);
          
            // UPLOAD FILE UPDATE
            // Updating the tables of image to insert the RFP ID
            // DB::table('db.uploads')
            //     ->where('rfpID', 0)
            //     ->where('requestorUserID', session('LoggedUser'))
            //     ->update([
            //         'rfpID' => $id,
            //         'updated_at' => date('Y-m-d H:i:s')
            //     ]);
        
  
            // $this->x ='22';
          

            // $this->rfpIdGlobal ='$rfpID';
            // $this->guidGlobal ='$GUID';

            if($request->hasFile('file')){

                foreach($request->file as $file) {

                    $completeFileName = $file->getClientOriginalName();
                    $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $randomized = rand();
                    $newFileName = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                    // $path = '/uploads/attachments/'.$GUID;
                    $rfpCode = $request->referenceNumber;
                    $rfpCode = str_replace('-', '_', $rfpCode);
                    // $myPath = "C:/Users/Iverson/Desktop/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpCode;

                    // For moving the file
                    $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpCode;
                    // For preview
                    $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpCode;

                    $symPath ="public/Attachments/RFP";

                    // C:\Users\Iverson\Documents\PSD\Final ID Design\revised may\5421 final\ID-FRONT-V1-Revised-hide-logo-01.png
                    $file->storeAs($destinationPath, $completeFileName);
                    $fileDestination = $storagePath.'/'.$completeFileName;
                    
                    // $file->move($myPath,$newFileName);
                    // $file->storeAs('public/upload',$newFileName);

                    $insert_doc = DB::table('general.attachments')->insert([
                        'INITID' => session('LoggedUser'),
                        'REQID' => $rfpID, 
                        'filename' => $completeFileName,
                        'filepath' => $storagePath, 
                        'fileExtension' => $extension,
                        'newFilename' => $newFileName,
                        'fileDestination'=>$destinationPath,
                        'formName' => 'Request for Payment',
                        'created_at' => date('Y-m-d H:i:s')
               
                    ]);

                }

            } 
  
            return back()->with('form_submitted', 'Your request was successfully submitted.');
        }


    // Upload files
    // public function rfpUploadFiles(Request $request){



    //     $dzCheckC = $request->validationUpload;
    //     $intdzCheckC  =(int)$dzCheckC;
        
    //     if( $intdzCheckC == True){
  
    //     if($request->hasFile('file')){
            
    //         $request->reqRef;
            
        
    //         // echo $this->x;

    //         $rfpIDAttachment = DB::select("SELECT ID, GUID, REQREF FROM accounting.`request_for_payment` AS a WHERE a.`UID` = '" . session('LoggedUser') . "' ORDER BY ID DESC LIMIT 1");
    //         $reqGUID = $rfpIDAttachment[0]->GUID;

    //         $last = DB::table('accounting.request_for_payment')->latest('ID')->first();
    //         $queID = $last->ID;


    //         $file = $request->file('file');
    //         $completeFileName = $file->getClientOriginalName();
    //         $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
    //         $extension = $file->getClientOriginalExtension();
    //         $randomized = rand();
    //         $documents = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;

    //         $path = '/uploads/attachments/'.$rfpIDAttachment[0]->GUID;
    //         // $path = '/uploads/attachments/'.session('guidSession');
    //         // $path = '/uploads/attachments/'.$GUID;


    //         // $file->move(public_path('uploads/attachments/'.$rfpIDAttachment[0]->GUID), $documents);

    //         $rfpAttachment = $request->reqRef;
    //         // $rfpAttachment = $rfpIDAttachment[0]->REQREF;

    //         $rfpAttachment = str_replace('-', '_', $rfpAttachment);
    //         $myPath = "C:/Users/Iverson/Desktop/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpAttachment;
    //         // $myPath = "C:/Users/Iverson/Desktop/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpIDAttachment[0]->REQREF;
    //         // Attachment/1/RFP/RFP_2021_001
            
    //         $file->move($myPath,$documents);
            

    //         // copy(public_path('images'),$documents ,"C:\Users\Iverson\Desktop\attachments",$documents);

    //         #move to final path

    //         // if DEV_MODE = true then
    //         //     $path = "C:\Users\Paul\Desktop\Attachment\";
    //         // else
    //         //      $path = "\\10.0.8.46\Repository\Attachments\RFP\RFP_2021_0001";



    //         $insert_doc = DB::table('general.attachments')->insert([
    //             'INITID' => session('LoggedUser'),
    //             // 'REQID' => session('rfpidSession'),
    //             // 'REQID' => $rfpIDAttachment[0]->ID, // bug
    //             'REQID' => $queID, 

    //             'filename' => $completeFileName,
    //             'filepath' => $path,  //bug
    //             'fileExtension' => $extension,
    //             'newFilename' => $documents,
    //             'formName' => 'Request for Payment',
    //             'created_at' => date('Y-m-d H:i:s')
       
    //         ]);


    //     } 

    // } else {
    //     return back()->with('error_submit', 'Please fill up all form fields.');

    // }

    // }
    
    public function createReimbursement() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        $mgrs = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");
        $projects = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE project_type <> 'MAIN OFFICE' AND `status` = 'Active' AND title_id = 1 ORDER BY project_name");

        $ref = DB::select("SELECT IFNULL((SELECT MAX(SUBSTR(a.`REQREF`,9)) FROM accounting.`reimbursement_request` a WHERE YEAR(TS) = 2021 AND a.`TITLEID` = '1'), FALSE) +1 AS 'ref'");
        $ref = $ref[0]->ref;
        $ref1 = str_pad($ref, 4, "0", STR_PAD_LEFT); 
        $ref1 = "RE-" . date('Y') . "-" . $ref1;


        return view('AccountingRequest.create-reimbursement', compact('posts','ref1','mgrs','projects'));
        //return $posts;
    }



    public function createPettyCash() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('AccountingRequest.create-pettycash', compact('posts'));
        //return $posts;

        
    }

    public function createCashAdvance() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('AccountingRequest.create-cashadvance', compact('posts'));
        //return $posts;
    }

}
