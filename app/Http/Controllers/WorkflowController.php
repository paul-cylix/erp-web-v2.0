<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use phpDocumentor\Reflection\PseudoTypes\True_;

class WorkflowController extends Controller
{

    public function getParticipants(Request $request) { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        Paginator::useBootstrap();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($posts);
        $perPage = 10;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->url());

        return view('MyWorkflow.participant', ['posts' => $paginatedItems]);
    }

        public function getParticipantsByID($class,$id){


            if($class === 'REQUESTFORPAYMENT'){

                $post = DB::table('accounting.request_for_payment')->where('ID',$id)->first();
                $queryPostDetails = DB::select("SELECT * FROM accounting.`rfp_details` AS a WHERE a.`RFPID` = $id ");
                $postDetails = $queryPostDetails[0];
    
                // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;
    
                $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment'ORDER BY a.`Payee` LIMIT 1");
                // $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id ORDER BY a.`Payee` DESC");
                $payeeDetails = $queryPayeeName[0];
    
                $qeLiquidationTable = DB::select("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");
    
                $filesAttached = DB::select("SELECT * FROM general.`attachments` a WHERE a.`REQID` = $id");
    
                return view('MyWorkflow.participants-byid.part-post', compact('post','postDetails','payeeDetails','initName','qeLiquidationTable','filesAttached'));
            }


            if($class === 'REIMBURSEMENT_REQUEST'){
                echo "stest";

                
            }


        }





    public function getInputs(Request $request) { 
        $posts = DB::select("call general.Display_Input_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        Paginator::useBootstrap();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($posts);
        $perPage = 10;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->url());

        

        
        // return view('MyWorkflow.input', compact('posts'));
        return view('MyWorkflow.input', ['posts' => $paginatedItems]);


        // error pagination
        
        // $posts = DB::table("call general.Display_Input_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')")->paginate(10);
        // return view('MyWorkflow.input', ['posts'=>$posts]);
    }
        // View inputs By Id
        public function getInputsByID($id){
            $post = DB::table('accounting.request_for_payment')->where('ID',$id)->first();
            $queryPostDetails = DB::select("SELECT * FROM accounting.`rfp_details` AS a WHERE a.`RFPID` = $id ");
            $postDetails = $queryPostDetails[0];
            $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ORDER BY a.`Payee` DESC");
            $payeeDetails = $queryPayeeName[0];
            $expenseType = DB::select("SELECT type FROM accounting.`expense_type_setup`");
            $currencyType = DB::select("SELECT CurrencyName FROM accounting.`currencysetup`");
            $liqTableCondition = DB::select("SELECT COUNT(*) AS myNumLiq FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`TYPE` = 'Request for Payment' AND a.`USER_GRP_IND` = 'Initiator' AND a.`STATUS` = 'In Progress' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."'");
            $liqTableCondition = $liqTableCondition[0]->myNumLiq;


            // Inputs checker for button
            $inputsInitCheck = DB::select("SELECT IFNULL ((SELECT COUNT(*) FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_NAME` = 'request for payment' 
            AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`INITID` = '".session('LoggedUser_CompanyID')."'), FALSE) AS inputsInitCheck");

            $inputsInitChecker = $inputsInitCheck[0]->inputsInitCheck;


            // Initiator Name
            $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id");
            $initName  = $queinitName[0]->NAME;          

            // Recipient - Clarity in Inputs
            $getRecipientNameInputs = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
            FROM
            (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Request for Payment' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
            UNION ALL
            SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Request for Payment' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."')
            a GROUP BY uid;");


            // Query In Progress ID
            $queInpId = DB::select("SELECT IFNULL((SELECT ID FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'requestforpayment' AND a.`STATUS` = 'In Progress' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."'), FALSE) AS inpId");
            $inputsInpId = $queInpId[0]->inpId;
            // attached files
            $filesAttached = DB::select("SELECT * FROM general.`attachments` a WHERE a.`REQID` = $id");


           

            return view('MyWorkflow.inputs-byid.npu-post',compact('post','postDetails','payeeDetails','expenseType','currencyType','inputsInitChecker','getRecipientNameInputs','inputsInpId','initName','filesAttached'),['liqTableCondition' => $liqTableCondition]); 
        }
            // For inputs Approved - Approver and Initiator
            public function approvedByIDRemarksInputs(Request $request){
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
                DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");

                return back()->with('form_submitted', 'The request has been approved.');
            }

            // Clarity Button in Inputs - Approver
            public function clarifyBtnInputs(Request $request){

                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'Not Started' WHERE a.`PROCESSID` = '".$request->idName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."'
                AND a.`FRM_CLASS` = 'requestforpayment' AND a.`STATUS` = 'In Progress'  AND a.`ORDERS` = '2' ");

                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress' WHERE a.`PROCESSID` = '".$request->idName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."'
                AND a.`FRM_CLASS` = 'requestforpayment' AND a.`ORDERS` = '1'");




                $notifIdClarityInp = DB::table('general.notifications')->insertGetId([
                    'ParentID' =>'0',
                    'levels'=>'0',
                    'FRM_NAME' =>'Request for Payment',
                    'PROCESSID' =>$request->idName,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$request->clarityRecipient,
                    'MESSAGE' =>$request->clarityMessage,
                    'TS' =>NOW(),
                    'SETTLED' =>'NO',
                    'ACTUALID' =>$request->inputsInpId,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName'),

                ]);

                DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'For Clarification'  WHERE a.`ID` = '".$request->idName."' AND a.`REQREF` = '".$request->refNumberNpu."';");
                
                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'For Clarification', a.`CurrentSender` = '".session('LoggedUser')."', a.`CurrentReceiver` = '".$request->clarityRecipient."' , 
                a.`NOTIFICATIONID` = '".$notifIdClarityInp."' , a.`UID_SIGN` = '".session('LoggedUser')."',a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '".$request->clarityMessage."' WHERE
                a.`PROCESSID` = '".$request->idName."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment' AND a.`STATUS` = 'In Progress'
                ");

                return back()->with('form_submitted', 'The request is now For Clarification.');
                   
            }

            // Reject Button in Inputs - Approver
            public function rejectBtnInputs(Request $request){
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Rejected', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->rejectedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'Rejected'  WHERE a.`ID` = '".$request->idName."' ");
                return back()->with('form_submitted', 'The request has been Rejected.');
            }

            // public function withdrawBtnInputs(Request $request){

            // }

            










    //Approval List
    public function getApprovals(Request $request) { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");

        // DRAFTS
            // $posts = "call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')";
            // $page = 1;
            // $size = 10;
            // $data = DB::select($posts);
            // $collect = collect($data);
            
            // $paginationData = new LengthAwarePaginator(
            //                          $collect->forPage($page, $size),
            //                          $collect->count(), 
            //                          $size, 
            //                          $page
                                    
            //                        );
            // $myCollectionObj = collect($posts);
            // $posts = $this->paginate($myCollectionObj);
        // DRAFTS

        // Change the pagination links to bootstrap Default UI
        Paginator::useBootstrap();

        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
 
        // Create a new Laravel collection from the array data
        $itemCollection = collect($posts);
 
        // Define how many items we want to be visible in each page
        $perPage = 10;
 
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
 
        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
 
        // set url path for generted links
        $paginatedItems->setPath($request->url());

 

        
        // $appStatus = DB::select("SELECT * FROM general.`actual_sign` a WHERE a.`PROCESSID` = '1945' AND a.`FRM_CLASS` = 'requestforpayment'");
        // return Response::json($appStatus);
        // return response()->json($appStatus[0]);


        return view('MyWorkflow.approval', ['posts' => $paginatedItems]);


        // return view('MyWorkflow.approval', compact('posts'));


    }

    // View Status
    public function viewAppStatus($id){
        $posts = DB::select("SELECT *, (SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID_SIGN`) AS 'Approved_By' FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'requestforpayment' AND a.`COMPID` ='".session('LoggedUser_CompanyID')."'");
        // $posts = DB::select("SELECT * FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'requestforpayment' AND a.`COMPID` = '1'");

        // return Response::json($rfpXmlReq);
        return response()->json($posts);
    }


    // View Message
    public function viewClaComments($id){
        $comments = DB::select("SELECT *, (SELECT UserFull_name FROM general.`users` b WHERE b.id = a.`RECEIVERID`) AS 'SENDERNAME',(SELECT c.`USER_GRP_IND` FROM general.`actual_sign` c WHERE c.`ID` = a.`ACTUALID`) AS USERLEVEL FROM general.`notifications` a WHERE a.`PROCESSID` = $id AND a.`FRM_NAME` = 'request for payment'");

        return response()->json($comments);
    }

    // View Liquidation Table Editable
    // public function viewLiqTable($id){
    //     $liquidated = DB::statement("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");
    //     return response()->json($liquidated);


    // }





    // View Approvals by single post
        public function getApprovalByID($id){
            // sir Mark
            // $request = new Request(['id' => $id]);
            // $this->approvedByIDRemarks($request);

            $post = DB::table('accounting.request_for_payment')->where('ID',$id)->first();
            $queryPostDetails = DB::select("SELECT * FROM accounting.`rfp_details` AS a WHERE a.`RFPID` = $id ");
            $postDetails = $queryPostDetails[0];
            $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment'ORDER BY a.`Payee` LIMIT 1");
            // old query payeedetails
            // $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id ORDER BY a.`Payee` DESC");
            $payeeDetails = $queryPayeeName[0];
            $expenseType = DB::select("SELECT type FROM accounting.`expense_type_setup`");
            $currencyType = DB::select("SELECT CurrencyName FROM accounting.`currencysetup`");
            $liqTableCondition = DB::select("SELECT COUNT(*) AS myNumLiq FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`TYPE` = 'Request for Payment' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`USER_GRP_IND` = 'Initiator' AND a.`STATUS` = 'In Progress'");
            $liqTableCondition = $liqTableCondition[0]->myNumLiq;

            //Query Existing Liquidation Table >>
            $qeLiquidationTable = DB::select("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");
            $qeSubTotal = DB::select("SELECT SUM(Amount) subTotalAmount FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");
            // $qeSubTotal = $qeSubTotal[0]->subTotalAmount;
            $qeSubTotal = $qeSubTotal[0]->subTotalAmount;
            // $qeSubTotal = json_encode($qeSubTotal);

            

            // NEW
            //recipient name
            $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
            FROM
            (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Request for Payment' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
            UNION ALL
            SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Request for Payment' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."')
            a GROUP BY uid;");

            // NEW
            // GET ACTIVE INPROGRESS ID

            // $qeInProgressID = DB::select("SELECT IF(ID > NULL,FALSE,ID) AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment' AND a.`STATUS` = 'In Progress'");
           
            // $qeInProgressID = $qeInProgressID[0]->inpId;

            // $qeInProgressID = strval( $qeInProgressID );

            // Condition for liquidation table to show
            $initiatorTableCon = DB::select("SELECT IFNULL((SELECT COUNT(*) FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment' AND a.`USER_GRP_IND` = 'Initiator' AND a.`STATUS` = 'In Progress'), FALSE) AS initiatorCheck");
            $initiatorCheck = $initiatorTableCon[0]->initiatorCheck;

            $acknowledgeTableCon = DB::select("SELECT IFNULL((SELECT COUNT(*) FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment' AND a.`USER_GRP_IND` = 'Acknowledgement of Accounting' AND a.`STATUS` = 'In Progress'), FALSE) AS acknowledgeCheck");
            $acknowledgeCheck = $acknowledgeTableCon[0]->acknowledgeCheck;

            $qeInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'requestforpayment' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");
            $qeInProgressID = $qeInProgressID[0]->inpId;

            // INIT CHECKER
            $initCheckAppr = DB::SELECT("SELECT IFNULL((SELECT COUNT(*) FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'requestforpayment' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress' AND a.`INITID` = '".session('LoggedUser')."'),FALSE) AS approvalChecker");
            $initCheckAppr = $initCheckAppr[0]->approvalChecker;


            // Initiator Name
            $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id");
            $initName  = $queinitName[0]->NAME;               
  
            // get Projects - Select 
            $projects = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE project_type <> 'MAIN OFFICE' AND `status` = 'Active' AND title_id = 1 ORDER BY project_name");

            // Reporting Manager
            $mgrs = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");

            // Reporting Manager ID -> Subquery

            $subqMgrsID = DB::select("SELECT (SELECT RMID FROM general.`systemreportingmanager` b WHERE b.RMName = a.`REPORTING_MANAGER` LIMIT 1) AS 'subRmid' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id ");
            $mgrsId = $subqMgrsID[0]->subRmid;

            $filesAttached = DB::select("SELECT * FROM general.`attachments` a WHERE a.`REQID` = $id");

            
            
            
            // dd($qeInProgressID);
            return view('MyWorkflow.approval-byid.app-post',compact('post','postDetails','payeeDetails','expenseType','currencyType',
            'qeLiquidationTable','qeSubTotal','getRecipientName','id','qeInProgressID','initCheckAppr','initName','projects','mgrs','mgrsId','initiatorCheck','acknowledgeCheck','filesAttached'),['liqTableCondition' => $liqTableCondition]);
          
        }

        public function downloadFile($file){
            // return response()->download($file);

            // $file_path = storage_path('Attachments/RFP/'.$file);
            // return response()->download(storage_path($file));
            // return response()->download(storage_path('app/public/' . $file));
            // $files = storage_path().'/Attachments/RFP/'.$file;
            $headers = 'Content-Type: image/jpeg';
            // return response()->download($files,$file, $headers);
            
            return response()->download('/storage/Attachments/RFP/'.$file,$headers);


        }

        // VIew the Approval Sequence by ID
        // public function viewApprovalSequence($id){
        //     $posts = DB::select("SELECT * FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = '".$id."' AND a.`FRM_CLASS` = 'REQUESTFORPAYMENT'");
        //     return response()->json($posts);


        // }

            // Approved button with remarks
            public function approvedByIDRemarks(Request $request){

                    $testerCount = DB::select("SELECT COUNT(ID) counterQ FROM general.`actual_sign` a WHERE a.`STATUS` = 'In Progress' AND a.`ORDERS` = '4' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`REFERENCE` = '".$request->refNumberApp."';");
                    $testerCount = $testerCount[0]->counterQ;

                    // Acknowledgement of Accounting - Approval
                    if($testerCount == True){
                        $acknowledgementAcc = DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'Completed', a.`UID_SIGN` = '".session('LoggedUser')."', a.`TS` = NOW(), a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '".$request->approvedRemarks."' WHERE a.`REFERENCE` = '".$request->refNumberApp."' AND a.`STATUS` = 'In Progress' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND  a.`ORDERS` = '4' AND a.`PROCESSID` = '".$request->idName."';");
                        $isReleasedRfp = DB::update("UPDATE accounting.`request_for_payment` a SET a.`ISRELEASED` = '1' AND a.`STATUS` = 'Completed'  WHERE a.`ID` = '".$request->idName."' AND a.`REQREF` = '".$request->refNumberApp."';");
                        return back()->with('form_submitted', 'The request has been approved.');
                    
                    }else{
                        DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                        DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
                        return back()->with('form_submitted', 'The request has been approved.');
                    }
                
            }

            // 429
            public function saveFilesAndTable(Request $request){

                $request->validate([

                    'liquidationTable'=>'required',
                    'file'=>'required',

                ]);


                $queryIdOfRfp = DB::select("SELECT IFNULL((SELECT GUID FROM accounting.`request_for_payment` a WHERE a.`ID` = '".$request->idName."'), FALSE) AS queGUID");
                $queryGUIDdOfRfp = $queryIdOfRfp[0]->queGUID;
                
                $queryClientID = DB::select("SELECT ClientID as qclientId FROM accounting.`rfp_details` a WHERE a.`RFPID` = '".$request->idName."' AND a.`CLIENTNAME` = '".$request->refClientName."'");
                $queryClientID = $queryClientID[0]->qclientId;


                $liquidationDataTable = $request->liquidationTable;
                $liquidationDataTable =json_decode($liquidationDataTable,true);
                $liquidationDataCount = count($liquidationDataTable);
                    
          

                        $liqdata = [];
                        for($i = 0; $i <count($liquidationDataTable); $i++) {
                            $liqdata[] = [

                                'RFPID' => $request->idName,
                                'trans_date'=>$liquidationDataTable[$i][0],
                                'client_id' => $queryClientID,
                                'client_name' =>$request->refClientName, 
                                'description'=>$liquidationDataTable[$i][2],
                                'amt_due_to_comp' =>'0',
                                'amt_due_to_emp' =>'0',
                                'date_' =>$liquidationDataTable[$i][0],
                                'Amount' =>$liquidationDataTable[$i][4],
                                'STATUS'=>'ACTIVE',
                                'ts' => now(),
                                'ISLIQUIDATED' => '0',
                                'currency_id' =>'0',
                                'currency' =>$liquidationDataTable[$i][3],
                                'expense_type'=> $liquidationDataTable[$i][1],
                            ];
                        }
           
                        DB::table('accounting.rfp_liquidation')->insert($liqdata);

                        DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                        DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
                   
                        if($request->hasFile('file')){

                            foreach($request->file as $file) {
            
                                $completeFileName = $file->getClientOriginalName();
                                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                                $extension = $file->getClientOriginalExtension();
                                $randomized = rand();
                                $newFileName = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                                // $path = '/uploads/attachments/'.$queryGUIDdOfRfp;  //currently not used
                                $rfpCode = $request->refNumberApp;
                                $rfpCode = str_replace('-', '_', $rfpCode);
                                // $rfpCode = str_replace('_', '-', $rfpCode);

                                // For moving the file
                                $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpCode;
                                // For preview
                                $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpCode;

                                $symPath ="public/Attachments/RFP";


                                $file->storeAs($destinationPath,$completeFileName);
                                $fileDestination = $storagePath.'/'.$completeFileName;
                                
                                // $file->move($myPath,$newFileName);
                                // $file->storeAs('public/upload',$newFileName);

                                $insert_doc = DB::table('general.attachments')->insert([
                                    'INITID' => session('LoggedUser'),
                                    'REQID' => $request->idName, 
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

                        $toDeleteFile = $request->toDelete;
                        $toDeleteFile =json_decode($toDeleteFile,true);
                        
                        if (!empty($toDeleteFile)) {
                        for($i = 0; $i <count($toDeleteFile); $i++) {
                           $idAttachment = $toDeleteFile[$i]['0'];
                           $pathAttachment = $toDeleteFile[$i]['1'];
                           $fileNameAttachment = $toDeleteFile[$i]['2'];
            
                           $public_path = public_path($pathAttachment.'/'.$fileNameAttachment);
                           unlink($public_path);
            
                           DB::table('general.attachments')->where('id', $idAttachment)->delete();
                        }
                        }

                        return back()->with('form_submitted', 'Your request was successfully submitted.');
 
            }


            // Rejected button with remarks
            public function rejectedByIDRemarks(Request $request){
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Rejected', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->rejectedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                $statusRejectedRfp = DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'Rejected'  WHERE a.`ID` = '".$request->idName."' AND a.`REQREF` = '".$request->refNumberApp."';");
                return back()->with('form_submitted', 'The request has been Rejected.');
            }

            // For Clarification button with remarks APPROVAL
            public function clarificationByIDRemarks(Request $request){


                $notificationIdClarity = DB::table('general.notifications')->insertGetId([
                    'ParentID' =>'0',
                    'levels'=>'0',
                    'FRM_NAME' =>$request->frmName,
                    'PROCESSID' =>$request->proccessID,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$request->clarityRecipient,
                    'MESSAGE' =>$request->clarificationRemarks,
                    'TS' =>NOW(),
                    'SETTLED' =>'NO',
                    'ACTUALID' =>$request->inProgressID,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName'),

                ]);

                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'For Clarification', a.`CurrentSender` = '".session('LoggedUser')."', a.`CurrentReceiver` = '".$request->clarityRecipient."' ,
                a.`NOTIFICATIONID` = '".$notificationIdClarity."', a.`UID_SIGN` = '".session('LoggedUser')."',a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '".$request->clarificationRemarks."' WHERE
                a.`PROCESSID` = '".$request->proccessID."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment' AND a.`STATUS` = 'In Progress'
                ");
                
                DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'For Clarification'  WHERE a.`ID` = '".$request->idName."' AND a.`REQREF` = '".$request->refNumberApp."';");


                return back()->with('form_submitted', 'The request is now For Clarification.');
            }


    // Upload files
    public function appUploadFiles(Request $request){
        
        $liquidationDataTableUpload = $request->liquidationTableUpload;
        $liquidationDataTableUpload =json_decode($liquidationDataTableUpload,true);
        $liquidationDataCountUpload = count($liquidationDataTableUpload);

        // if($liquidationDataCountUpload == 0){

        if($liquidationDataCountUpload <= 0){
            return back()->with('form_submitteds', 'Liquidation Table is Required!');
        }else{

            if($request->hasFile('file')){

                $rfpIDAttachment = DB::select("SELECT ID, GUID, REQREF FROM accounting.`request_for_payment` AS a WHERE a.`UID` = '" . session('LoggedUser') . "' AND a.`REQREF` = '$request->refNumber' ORDER BY ID DESC LIMIT 1");
                $file = $request->file('file');
                $completeFileName = $file->getClientOriginalName();
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $randomized = rand();
                $documents = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                $path = '/uploads/attachments/'.$rfpIDAttachment[0]->GUID;
    
                $rfpAttachment = $rfpIDAttachment[0]->REQREF;
                $rfpAttachment = str_replace('-', '_', $rfpAttachment);
                $myPath = "C:/Users/Iverson/Desktop/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpAttachment;
    
    
                $file->move($myPath,$documents);
    
                $insert_doc = DB::table('general.attachments')->insert([
                    'INITID' => session('LoggedUser'),
                    'REQID' => $rfpIDAttachment[0]->ID,
                    'filename' => $completeFileName,
                    'filepath' => $path,
                    'fileExtension' => $extension,
                    'newFilename' => $documents,
                    'formName' => 'Request for Payment',
                    'created_at' => date('Y-m-d H:i:s')
           
                ]);
            } 
            return back()->with('form_submitted', 'Your request was successfully submitted.');

        }
 
    }


    // In Progress List
    public function getInProgress(Request $request) { 
        $posts = DB::select("call general.Display_Inprogress_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        // $posts = count($posts);
        // dd($posts);

        Paginator::useBootstrap();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($posts);
        $perPage = 10;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->url());

        // return view('MyWorkflow.input', compact('posts'));
        return view('MyWorkflow.in-progress', ['posts' => $paginatedItems]);
        // return view('MyWorkflow.in-progress', compact('posts'));
    }




        // View in-progress
        public function getInProgressByID($id){
            $post = DB::table('accounting.request_for_payment')->where('ID',$id)->first();
            $queryPostDetails = DB::select("SELECT * FROM accounting.`rfp_details` AS a WHERE a.`RFPID` = $id ");
            $postDetails = $queryPostDetails[0];

            $qeLiquidationTable = DB::select("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");
            $qeSubTotal = DB::select("SELECT SUM(Amount) subTotalAmount FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");

            // Initiator Name
            $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id");
            $initName  = $queinitName[0]->NAME;   

        


            $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment'ORDER BY a.`Payee` LIMIT 1");


            // $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id ORDER BY a.`Payee` DESC");
            $payeeDetails = $queryPayeeName[0];

            $filesAttached = DB::select("SELECT * FROM general.`attachments` a WHERE a.`REQID` = $id");

            return view('MyWorkflow.in-progress-byid.inp-post', compact('post','postDetails','payeeDetails','initName','qeLiquidationTable','filesAttached'));
        }

            // Withdrawn button with remarks
            public function withdrawnByIDRemarks(Request $request){
                DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
                WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_CLASS` = 'REQUESTFORPAYMENT' AND a.`STATUS` = 'In Progress' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                $statusWithdrawnRfp = DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'Withdrawn'  WHERE a.`ID` = '".$request->idName."' AND a.`REQREF` = '".$request->refNumberApp."';");

                return back()->with('form_submitted', 'Your request is now Withdrawn.');
            }


















    // Clarification List
    public function getClarification(Request $request) { 
        $posts = DB::select("call general.Display_Clarification_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        // $postsCount = count($posts);
        // dd($postsCount);
        
        Paginator::useBootstrap();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($posts);
        $perPage = 10;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->url());




        // WIP

        // $search = '19';
        // $search1 = '3';

        // $collection  = collect($posts);

        // $filtered1 = $collection->where('ID', $search);
        // $filtered2 = $collection->where('Remarks', $search);

        // $filtered = $filtered1->all();
        // $filtered1 = $filtered2->all();

        // $e = array_merge($filtered,$filtered1);


        // $name='19';
        // $collection->filter(function ($item) use($name){


        // dd( preg_match("/$name/",$item->ID));

         
        // });



        // dd($e);

        // $filtered = $collection->filter(function($value, $key = '1939'){
        //     $result = Str::startsWith($value->ID, $key);

        //     // $filtered = $result->all();
        //     dd($value->ID);
        // });
        
  
 

        return view('MyWorkflow.clarification', ['posts' => $paginatedItems]);

        // return view('MyWorkflow.clarification', compact('posts'));
    }



        // View Clarification by id
        public function getClarificationByID($id){
            $post = DB::table('accounting.request_for_payment')->where('ID',$id)->first();
            $queryPostDetails = DB::select("SELECT * FROM accounting.`rfp_details` AS a WHERE a.`RFPID` = $id ");
            $postDetails = $queryPostDetails[0];

            $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment'ORDER BY a.`Payee` LIMIT 1");
            // $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id ORDER BY a.`Payee` DESC");
            $payeeDetails = $queryPayeeName[0];

            // check clarification of initiator / Approver
            $initCheck = DB::select("SELECT IFNULL ((SELECT COUNT(*) FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id 
            AND a.`frm_class` = 'requestforpayment' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification' AND a.`INITID` = '" .session('LoggedUser'). "'), FALSE) AS clarifyInitCheck;");
            

            $initCheck = $initCheck[0]->clarifyInitCheck;

            // Initiator Name
            $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id");
            $initName  = $queinitName[0]->NAME;   


            // First two editable for Clarification
            $queCheckifEditable = DB::select("SELECT IFNULL((SELECT COUNT(ID) FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."'  AND a.`FRM_CLASS` = 'requestforpayment' AND a.`ORDERS` <2 AND a.`STATUS` = 'For Clarification'),FALSE) AS editAbleCheck");
            $editableChecker = $queCheckifEditable[0]->editAbleCheck;

            // Reporting Manager
            $mgrs = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");

            // Reporting Manager ID -> Subquery
            $subqMgrsID = DB::select("SELECT (SELECT RMID FROM general.`systemreportingmanager` b WHERE b.RMName = a.`REPORTING_MANAGER` LIMIT 1) AS 'subRmid' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id ");
            $mgrsId = $subqMgrsID[0]->subRmid;

            // get Projects - Select 
            $projects = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE project_type <> 'MAIN OFFICE' AND `status` = 'Active' AND title_id = 1 ORDER BY project_name");
          
            // Currency
            $currencyType = DB::select("SELECT CurrencyName FROM accounting.`currencysetup`");

            // Expense Type
            $expenseType = DB::select("SELECT type FROM accounting.`expense_type_setup`");


            // Query recipient in clarification
            $queRecipient = DB::select("SELECT IFNULL((SELECT a.`CurrentReceiver` AS 'recipient' FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment' AND a.`STATUS` = 'for clarification'),FALSE) AS recipient");
            $recipient = $queRecipient[0]->recipient;

            // Query Liquidation Table if edit
            $queLiquidatedT = DB::select("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");

            // attachments

            $filesAttached = DB::select("SELECT * FROM general.`attachments` a WHERE a.`REQID` = $id");



            return view('MyWorkflow.clarification-byid.cla-post', compact('post','postDetails','payeeDetails','initCheck','initName','editableChecker','mgrs','mgrsId','projects','currencyType','recipient','queLiquidatedT','expenseType','filesAttached'));
        }



            // Save function for editable
            // public function saveEditable(Request $request){
            //     $saveData = DB::update("UPDATE accounting.`request_for_payment` a SET a.`AMOUNT` = '".$request->amount."'  WHERE a.`ID` = '".$request->idName."';");
            //     return back()->with('form_submitted', 'Your request is now Withdrawn.');
            // }




            // Withdraw button in clarification - Initiator
            public function clarifyWithdrawBtnRemarks(Request $request){
                DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
                WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_CLASS` = 'REQUESTFORPAYMENT' AND a.`STATUS` = 'For Clarification' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'Withdrawn'  WHERE a.`ID` = '".$request->idName."' AND a.`REQREF` = '".$request->refNumberApp."';");
                return back()->with('form_submitted', 'Your request is now Withdrawn.');
            }

            // Reply Button in Clarification - Initiator - Not Editable
            public function clarifyReplyBtnNoEdit(Request $request){

                $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_NAME` = 'Request for Payment' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC");
                $notifCount = count($notif);

                if($notif == True){

                $nParentId= $notif[0]->ID;
                $nReceiverId= $notif[0]->SENDERID;
                $nActualId= $notif[0]->ACTUALID;

                $insert_doc = DB::table('general.notifications')->insert([

                    'ParentID' =>$nParentId,
                    'levels'=>'0',
                    'FRM_NAME' =>'Request for Payment',
                    'PROCESSID' =>$request->idName,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$nReceiverId,
                    'MESSAGE' =>$request->replyRemarks,
                    'TS' =>NOW(),
                    'SETTLED' => 'YES',
                    'ACTUALID' => $nActualId,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName'),

                   ]);

                   DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'In Progress', a.`TS` = NOW()   
                   WHERE a.`ID` = '".$request->idName."' AND a.`REQREF` = '".$request->refNumberReply."';");
       
                   // For clarification to in progress
                   DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
                   WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_NAME` = 'request for payment' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");


            // Liquidation Table
            $liquidationDataTable = $request->jsonData;
            $liquidationDataTable =json_decode($liquidationDataTable,true);


            if(count($liquidationDataTable) == True){
                $queID = DB::select("SELECT IFNULL((SELECT (SELECT Business_Number FROM general.`business_list` b WHERE b.`business_fullname` = a.`client_name` LIMIT 1) AS 'clientID'
                FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = '".$request->idName."' LIMIT 1), FALSE) AS clientID;");
                $clientID = $queID[0]->clientID;
                

                
                DB::table('accounting.rfp_liquidation')->where('RFPID', $request->idName)->delete();

                $liqdata = [];
                for($i = 0; $i <count($liquidationDataTable); $i++) {
                    $liqdata[] = [
                        'RFPID' => $request->idName,
                        'trans_date'=>$liquidationDataTable[$i][0],
                        'client_id' => $clientID,
                        'client_name' =>$request->clientName,
                        'description'=>$liquidationDataTable[$i][2],
                        'amt_due_to_comp' =>'0',
                        'amt_due_to_emp' =>'0',
                        'date_' =>$liquidationDataTable[$i][0],
                        'Amount' =>$liquidationDataTable[$i][4],
                        'STATUS'=>'ACTIVE',
                        'ts' => now(),
                        'ISLIQUIDATED' => '0',
                        'currency_id' =>'0',
                        'currency' =>$liquidationDataTable[$i][3],
                        'expense_type'=> $liquidationDataTable[$i][1],
                    ];
                }
                DB::table('accounting.rfp_liquidation')->insert($liqdata);
            }


                // Delete attachments
                $toDeleteFile = $request->deleteAttached;
                $toDeleteFile =json_decode($toDeleteFile,true);
                
        
                if(count($toDeleteFile) != null) {
                for($i = 0; $i <count($toDeleteFile); $i++) {
                $idAttachment = $toDeleteFile[$i]['0'];
                $pathAttachment = $toDeleteFile[$i]['1'];
                $fileNameAttachment = $toDeleteFile[$i]['2'];

                $public_path = public_path($pathAttachment.'/'.$fileNameAttachment);
                unlink($public_path);

                DB::table('general.attachments')->where('id', $idAttachment)->delete();
                }
                }

                // Upload
                if($request->hasFile('file')){

                    foreach($request->file as $file) {

                        $completeFileName = $file->getClientOriginalName();
                        $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $randomized = rand();
                        $newFileName = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                        $rfpCode = $request->referenceNumber;
                        $rfpCode = str_replace('-', '_', $rfpCode);
                        $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpCode;
                        $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/RFP/".$rfpCode;
                        $symPath ="public/Attachments/RFP";

                        $file->storeAs($destinationPath,$completeFileName);
                        $fileDestination = $storagePath.'/'.$completeFileName;
                        
                        $insert_doc = DB::table('general.attachments')->insert([
                            'INITID' => session('LoggedUser'),
                            'REQID' => $request->idName, 
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
            
            







                return back()->with('form_submitted', 'Your request is now In Progress.');
                } else {
                return back()->with('form_submitteds', 'Reply Error');
                }

            }




            // Reply Button in Clarification - Initiator - Editable
            public function clarifyReplyBtnRemarks(Request $request){


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
              

                $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_NAME` = 'Request for Payment' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
                $notifCount = count($notif);


                
                if($notif == True){
                    $project_name = DB::select("SELECT project_name FROM general.`setup_project` WHERE `project_id` = '" . $request->projectName . "'");             
                    $project_name = $project_name[0]->project_name;

                    $dateNeeded = date_create($request->dateNeeded);
                    $dateNeeded = date_format($dateNeeded, 'Y-m-d');


                   $nParentId= $notif[0]->ID;
                   $nReceiverId= $notif[0]->SENDERID;
                   $nActualId= $notif[0]->ACTUALID;


                   $insert_doc = DB::table('general.notifications')->insert([

                    'ParentID' =>$nParentId,
                    'levels'=>'0',
                    'FRM_NAME' =>'Request for Payment',
                    'PROCESSID' =>$request->idName,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$nReceiverId,
                    'MESSAGE' =>$request->replyRemarks,
                    'TS' =>NOW(),
                    'SETTLED' => 'YES',
                    'ACTUALID' => $nActualId,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName'),

                   ]);
                   

                $queRMName = DB::select("SELECT (SELECT RMName FROM general.`systemreportingmanager` b WHERE b.RMID = '".$request->reportingManager."' LIMIT 1) 
                AS 'RMName' FROM general.`actual_sign` a WHERE a.`PROCESSID` = $request->idName AND a.`COMPID` = '1' AND a.`FRM_CLASS` = 'requestforpayment' LIMIT 1");
                $rMName = $queRMName[0]->RMName;


                //    $request->reportingManager;


                // Clarity Edit
                DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'In Progress', a.`REPORTING_MANAGER` = '".$rMName."', a.`DATE` = '".$request->dateNeeded."', 
                a.`Deadline` = '".$request->dateNeeded."', a.`AMOUNT` = '".$request->amount."', a.`TS` = NOW()   
                WHERE a.`ID` = '".$request->idName."' AND a.`REQREF` = '".$request->refNumberReply."';");

                DB::update("UPDATE accounting.`rfp_details` a SET a.`PROJECTID` = '".$request->projectName."', a.`ClientID` ='".$request->clientID."', a.`CLIENTNAME` = '".$request->clientName."', 
                a.`PROJECT` = '".$project_name."',a.`DATENEEDED` = '".$dateNeeded."', a.`MOP` = '".$request->modeOfPayment."' , a.`PURPOSED` = '".$request->purpose."', 
                a.`DESCRIPTION` = '".$request->purpose."', a.`CURRENCY` = '".$request->currency."', a.`AMOUNT` = '".$request->amount."', a.`TS` = NOW()
                WHERE a.`RFPID` = '".$request->idName."'");

                // For clarification to in progress
                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
                WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_NAME` = 'request for payment' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");
 
                // Update form in actual sign
                DB::update("UPDATE general.`actual_sign` a SET a.`REMARKS` = '".$request->purpose."', a.`TS` = NOW(), a.`DUEDATE` = '".$request->dateNeeded."', a.`PODATE` = '".$request->dateNeeded."',
                a.`DATE` = '".$request->dateNeeded."', a.`RM_ID` = '".$request->reportingManager."', a.`REPORTING_MANAGER` = '".$rMName."', a.`PROJECTID` = '".$request->projectName."', a.`PROJECT` = '".$project_name."', a.`CLIENTID` = '".$request->clientID."', a.`CLIENTNAME` = '".$request->clientName."',
                a.`Payee` = '".$request->payeeName."', a.`Amount` = '".$request->amount."'
                WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_NAME` = 'request for payment' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ");


                
            $toDeleteFile = $request->toDelete;
            $toDeleteFile =json_decode($toDeleteFile,true);
            
            // newly added
            if(count($toDeleteFile) > 0) {
            for($i = 0; $i <count($toDeleteFile); $i++) {
               $idAttachment = $toDeleteFile[$i]['0'];
               $pathAttachment = $toDeleteFile[$i]['1'];
               $fileNameAttachment = $toDeleteFile[$i]['2'];

               $public_path = public_path($pathAttachment.'/'.$fileNameAttachment);
               unlink($public_path);

               DB::table('general.attachments')->where('id', $idAttachment)->delete();
            }
            }

            // File Upload
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


                    $file->storeAs($destinationPath,$completeFileName);
                    $fileDestination = $storagePath.'/'.$completeFileName;
                    
                    // $file->move($myPath,$newFileName);
                    // $file->storeAs('public/upload',$newFileName);

                    $insert_doc = DB::table('general.attachments')->insert([
                        'INITID' => session('LoggedUser'),
                        'REQID' => $request->idName, 
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

                



                return back()->with('form_submitted', 'Your request is now In Progress.');
                } else {
                return back()->with('form_submitteds', 'Reply Error');
                }
                
            }

            // Approve button in clarification - Approver
            public function clarifyApproveBtnRemarks(Request $request){
                $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_NAME` = 'Request for Payment' AND a.`SETTLED` = 'NO'");
                $notifCount = count($notif);


                if($notifCount == True){
                    $nParentId= $notif[0]->ID;
                    $nActualId= $notif[0]->ACTUALID;

                    DB::table('general.notifications')->insert([
                        'ParentID' => $nParentId,
                        'levels' => '0',
                        'FRM_NAME' => 'Request for Payment',
                        'PROCESSID' => $request->idName,
                        'SENDERID' => session('LoggedUser'),
                        'RECEIVERID' => session('LoggedUser'),
                        'MESSAGE' => $request->approveRemarks,
                        'TS'=> now(),
                        'SETTLED' => 'YES',
                        'ACTUALID' => $nActualId,
                        'SENDTOACTUALID' =>'0',
                        'UserFullName' =>session('LoggedUser_FullName'),
                    ]);

                    $queryOrderChecker = DB::select("SELECT COUNT(*) AS orderChecker FROM general.`actual_sign` a WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_CLASS` = 'requestforpayment'  AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`ORDERS` = '4' AND a.`STATUS` = 'For Clarification'");
                    $checkOrder = $queryOrderChecker[0]->orderChecker;

                    if($checkOrder == True){

                    DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Completed', a.`UID_SIGN` = '".session('LoggedUser')."',
                        a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->approveRemarks. "', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0' 
                       WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_CLASS` = 'REQUESTFORPAYMENT' AND a.`STATUS` = 'For Clarification' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                    DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'Completed', a.`ISRELEASED` = '1' WHERE a.`ID` = '".$request->idName."' ");
                       return back()->with('form_submitted', 'Your request is now Approved.');
       
                    } else {
                    
                    // Actual Sign of for Clarification to Completed
                    DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Completed', a.`UID_SIGN` = '".session('LoggedUser')."',
                        a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->approveRemarks. "', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0' 
                       WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_CLASS` = 'REQUESTFORPAYMENT' AND a.`STATUS` = 'For Clarification' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ;");

                    // Actual sign of Not Started to In Progress
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");

                    // RFP back to in Progress
                    DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'In Progress', a.`ISRELEASED` = '0' WHERE a.`ID` = '".$request->idName."' ");
                       return back()->with('form_submitted', 'Your request is now Approved.');

                    }



                } else {
                return back()->with('form_submitted', 'Error Request.');      
                }
            }

            // Reject button in clarification - Approver
            public function clarifyRejectBtnRemarks(Request $request){
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Rejected', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->rejectedRemarks. "' 
                WHERE `status` = 'For Clarification' AND PROCESSID = '".$request->idName."' AND `FRM_CLASS` = 'REQUESTFORPAYMENT' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'Rejected'  WHERE a.`ID` = '".$request->idName."' ");
                return back()->with('form_submitted', 'The request has been Rejected.');
            }





    // Approved Workflow list 
    public function getApproved(Request $request) { 
        $posts = DB::select("call general.Display_Completed_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        // $postsCount = count($posts);
        Paginator::useBootstrap();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($posts);
        $perPage = 10;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->url());
        // <div>{{ $posts->links() }}</div>
        return view('MyWorkflow.approved', ['posts' => $paginatedItems]);

        // return view('MyWorkflow.approved', compact('posts'));
        //return $posts;
    }

        // View Approved by id
        public function getApprovedByID($id){
            $post = DB::table('accounting.request_for_payment')->where('ID',$id)->first();
            $queryPostDetails = DB::select("SELECT * FROM accounting.`rfp_details` AS a WHERE a.`RFPID` = $id ");
            $postDetails = $queryPostDetails[0];
            $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment'ORDER BY a.`Payee` LIMIT 1");
            // $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id ORDER BY a.`Payee` DESC");
            $payeeDetails = $queryPayeeName[0];

            // Initiator Name
            $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id");
            $initName  = $queinitName[0]->NAME;

            $qeLiquidationTable = DB::select("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");

            $filesAttached = DB::select("SELECT * FROM general.`attachments` a WHERE a.`REQID` = $id");


            return view('MyWorkflow.approved-byid.appd-post', compact('post','postDetails','payeeDetails','initName','qeLiquidationTable','filesAttached'));
        }




    // Withdrawn List
    public function getWithdrawn(Request $request) { 
        $posts = DB::select("call general.Display_withdrawn_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        Paginator::useBootstrap();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($posts);
        $perPage = 10;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->url());
        // <div>{{ $posts->links() }}</div>
        return view('MyWorkflow.withdrawn', ['posts' => $paginatedItems]);
        // return view('MyWorkflow.withdrawn', compact('posts'));
    }

        // View withdrawn by id
        public function getWithdrawByID($id){
            $post = DB::table('accounting.request_for_payment')->where('ID',$id)->first();
            $queryPostDetails = DB::select("SELECT * FROM accounting.`rfp_details` AS a WHERE a.`RFPID` = $id ");
            $postDetails = $queryPostDetails[0];
            $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment'ORDER BY a.`Payee` LIMIT 1");
            // $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id ORDER BY a.`Payee` DESC");
            $payeeDetails = $queryPayeeName[0];

            // Initiator Name
            $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id");
            $initName  = $queinitName[0]->NAME;

            $qeLiquidationTable = DB::select("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");

            $filesAttached = DB::select("SELECT * FROM general.`attachments` a WHERE a.`REQID` = $id");

            return view('MyWorkflow.withdrawn-byid.wit-post', compact('post','postDetails','payeeDetails','initName','filesAttached','qeLiquidationTable'));
        }

            // Withdrawn button with remarks
            // public function withdrawBtnByIDRemarks(Request $request){
            //     DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_CLASS` = 'REQUESTFORPAYMENT' AND a.`STATUS` = 'In Progress' ;");
            //     return back()->with('form_submitted', 'Your request is now Withdrawn.');
            // }






    // Rejected
    public function getRejected(Request $request) { 
        $posts = DB::select("call general.Display_Rejected_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        Paginator::useBootstrap();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($posts);
        $perPage = 10;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->url());
        // <div>{{ $posts->links() }}</div>
        return view('MyWorkflow.rejected', ['posts' => $paginatedItems]);

        // return view('MyWorkflow.rejected', compact('posts'));
        //return $posts;
    }
        // View Rejcted by id
        public function getrejectedByID($id){
            $post = DB::table('accounting.request_for_payment')->where('ID',$id)->first();
            $queryPostDetails = DB::select("SELECT * FROM accounting.`rfp_details` AS a WHERE a.`RFPID` = $id ");
            $postDetails = $queryPostDetails[0];
            $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment'ORDER BY a.`Payee` LIMIT 1");
            // $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id ORDER BY a.`Payee` DESC");
            $payeeDetails = $queryPayeeName[0];

            // Initiator Name
            $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`request_for_payment` a WHERE a.`ID` = $id");
            $initName  = $queinitName[0]->NAME;

            $qeLiquidationTable = DB::select("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");

            $filesAttached = DB::select("SELECT * FROM general.`attachments` a WHERE a.`REQID` = $id");

            return view('MyWorkflow.rejected-byid.rej-post', compact('post','postDetails','payeeDetails','initName','qeLiquidationTable','filesAttached'));
        }


















    public function getRFP_InitData() {
        $userid = Auth::user()->id;
        $mngrs = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");
        $projects = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE project_type <> 'MAIN OFFICE' AND `status` = 'Active' AND title_id = 1 ORDER BY project_name");
        //$user_info = DB::select("SELECT FirstName_Empl AS 'FNAME', LastName_Empl AS 'LNAME', DepartmentName AS 'Department', a.PositionName AS 'PositionName' FROM humanresource.`employees` a INNER JOIN erpweb.`users` b ON (a.`SysPK_Empl` = b.`employee_id`) WHERE b.`id` = '" . $userid . "'");
        return view('AccountingRequest.create-rfp', compact('mngrs', 'projects', 'user_info'));
    } 

    // public function getClientName($prjid) {
    //     $clientNames = DB::select("SELECT Business_Number as 'clientID', ifnull(business_fullname, '') AS 'clientName' FROM general.`business_list` WHERE Business_Number IN (SELECT `ClientID` FROM general.`setup_project` WHERE `project_id` = '" . $prjid . "')");
    //     if(count($clientNames) > 0) {
    //         return $clientNames;
    //     } else {
    //         return '';
    //     }
    // }

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
}
    // public function ref(Request $request)
    // {
    //     try {
    //         $date = Carbon::now();
    //         $results = DB::select(DB::raw("SELECT CONCAT('BOK',DATE_FORMAT('$date','%y'),'-',LPAD(COUNT(*)+1,5,0)) as 'Ref' FROM `booking_main` WHERE  YEAR(`trans_date`)=YEAR('$date')"));
    //         return response()->json(new JsonResponse($results), Response::HTTP_OK);
    //     } catch (\Throwable $th) {
    //         return response()->json(new JsonResponse([], $th), Response::HTTP_BAD_REQUEST);
    //     }
    // }

    // public function createRFP(Request $request) {
    //     $dateRequested = date_create($request->dateRequested);
    //     $dateNeeded = date_create($request->dateNeeded);

    //     mt_srand((double)microtime()*10000);
    //     $charid = strtoupper(md5(uniqid(rand(), true)));
    //     $hyphen = chr(45);
    //     $GUID = chr(123)
    //         .substr($charid, 0, 8).$hyphen
    //         .substr($charid, 8, 4).$hyphen
    //         .substr($charid,12, 4).$hyphen
    //         .substr($charid,16, 4).$hyphen
    //         .substr($charid,20,12)
    //         .chr(125);
    //     $GUID = trim($GUID, '{');
    //     $GUID = trim($GUID, '}');
 

    //     DB::table('accounting.request_for_payment')->insert([
    //         'DATE' => date_format($dateRequested, 'Y-m-d'),
    //         'REQREF' => $request->referenceNumber,
    //         'Deadline' => date_format($dateNeeded, 'Y-m-d'),
    //         'AMOUNT' => number_format($request->amount, 2, '.', ''),
    //         'STATUS' => 'In Progress',
    //         'UID' => Auth::user()->id,
    //         'FNAME' => Auth::user()->fname,
    //         'LNAME' => Auth::user()->lname,
    //         'DEPARTMENT' => Auth::user()->department,
    //         'REPORTING_MANAGER' => $request->RMName,
    //         'POSITION' => Auth::user()->positionName,
    //         'GUID' => $GUID,
    //         'ISRELEASED' => '0',
    //         'TITLEID' => '1'

    //     ]);

        

    //     return back()->with('form_submitted', 'Request has been submitted!');
    // }




    // My Workflow
    // function participants(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('MyWorkflow.participant', $data);
    // }

    // function inputs(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('MyWorkflow.input', $data);
    // }

    // function approvals(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('MyWorkflow.approval', $data);
    // }
    
    // function inProgress(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('MyWorkflow.in-progress', $data);
    // }

    // function clarification(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('MyWorkflow.clarification', $data);
    // }

    // function approved(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('MyWorkflow.approved', $data);
    // }

    // function withdrawn(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('MyWorkflow.withdrawn', $data);
    // }

    // function rejected(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('MyWorkflow.rejected', $data);
    // }
 
