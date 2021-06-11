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

        public function getParticipantsByID($class,$id,$frmname){

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

                $post = DB::table('accounting.reimbursement_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`reimbursement_expense_details` a WHERE a.`REID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`reimbursement_request_details` a WHERE a.`REID` = $id");
                // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`reimbursement_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Reimbursement Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.participants-byid.part-re', compact('post','initName','expenseDetails','transpoDetails','attachmentsDetails'));
            }

            if($class === 'PETTYCASHREQUEST'){
                $post = DB::table('accounting.petty_cash_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`petty_cash_expense_details` a WHERE a.`PCID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`petty_cash_request_details` a WHERE a.`PCID` = $id");
                // // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`petty_cash_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Petty Cash Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.participants-byid.part-pc', compact('post','initName','attachmentsDetails','expenseDetails','transpoDetails'));
            }


            if($class === 'SALES_ORDER_FRM'){
                
                if ($frmname === 'Sales Order - Project') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Project' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.participants-byid.part-sof-prj', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));

                } 

                if ($frmname === 'Sales Order - Delivery') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Delivery' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.participants-byid.part-sof-dlv', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - Demo') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Demo' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.participants-byid.part-sof-dmo', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - POC') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - POC ' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.participants-byid.part-sof-poc', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 


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
        public function getInputsByID($class,$id,$frmname){
    

            if($class === 'REQUESTFORPAYMENT'){
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

            if($class === 'REIMBURSEMENT_REQUEST'){
                $post = DB::table('accounting.reimbursement_request')->where('ID',$id)->first();
                $expenseDetails = DB::select("SELECT * FROM accounting.`reimbursement_expense_details` a WHERE a.`REID` = $id");
                $transpoDetails = DB::select("SELECT * FROM accounting.`reimbursement_request_details` a WHERE a.`REID` = $id");
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`reimbursement_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Reimbursement Request' AND a.`REQID` =$id");
                
                $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                FROM (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Reimbursement Request' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                UNION ALL SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Reimbursement Request' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."') a GROUP BY uid;");

                $qeInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");


                return view('MyWorkflow.inputs-byid.npu-re', compact('post','initName','expenseDetails','transpoDetails','attachmentsDetails','getRecipientName','qeInProgressID'));         
            }

            if($class === 'SALES_ORDER_FRM'){

 
            
            
            
            
            
                if ($frmname === 'Sales Order - Project') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $approvalOfPrjHeadChecker = DB::select(" SELECT IFNULL((SELECT a.`status` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`ORDERS` = 3 AND a.`STATUS` = 'In Progress'), FALSE) AS checker ");
                    $projectCoordinator = DB::table('general.users')->where('id','!=','1')->where('status','ACTIVE')->get();
                    $siConfirmationChecker =DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`ORDERS` = 7 AND a.`STATUS` = 'In Progress'), FALSE) AS checker");
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Project' AND a.`REQID` =$id");
    
                    $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                    FROM (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Project' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                    UNION ALL SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Project' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."') a GROUP BY uid;");
    
                    $qeInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");
    
                    
                    return view('MyWorkflow.inputs-byid.npu-sof-prj', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','approvalOfPrjHeadChecker','projectCoordinator','siConfirmationChecker','getRecipientName','qeInProgressID'));
               


                    
                } 
    
                if ($frmname === 'Sales Order - Delivery') {

            
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $approvalOfPrjHeadChecker = DB::select(" SELECT IFNULL((SELECT a.`status` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`ORDERS` = 3 AND a.`STATUS` = 'In Progress'), FALSE) AS checker ");
                    $projectCoordinator = DB::table('general.users')->where('id','!=','1')->where('status','ACTIVE')->get();
                    $siConfirmationChecker =DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`USER_GRP_IND` = 'SI Confirmation' AND a.`STATUS` = 'In Progress'), FALSE) AS checker");
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Delivery' AND a.`REQID` =$id");
    
                    $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                    FROM (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Project' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                    UNION ALL SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Project' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."') a GROUP BY uid;");
    
                    $qeInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");
    
                    
                    return view('MyWorkflow.inputs-byid.npu-sof-dlv', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','approvalOfPrjHeadChecker','projectCoordinator','siConfirmationChecker','getRecipientName','qeInProgressID'));
               

                } 
    
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            }

            





















        }

            public function approvedRENpu(Request $request){
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->reID."' AND `FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->reID."' AND `FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");

                DB::update("UPDATE accounting.`reimbursement_request` a SET a.`ISRELEASED` = '1', a.`RELEASEDCASH` = '1'  WHERE a.`ID` = '".$request->reID."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");
                DB::update("UPDATE accounting.`reimbursement_expense_details` a SET a.`RELEASEDCASH` = '1'  WHERE a.`REID` = '".$request->reID."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");
                DB::update("UPDATE accounting.`reimbursement_request_details` a SET a.`RELEASEDCASH` = '1'  WHERE a.`REID` = '".$request->reID."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");

                return back()->with('form_submitted', 'The request has been approved.');
            }

            public function rejectedRENpu(Request $request){
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Rejected', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->rejectedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->reID."' AND `FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE accounting.`reimbursement_request` a SET a.`STATUS` = 'Rejected'  WHERE a.`ID` = '".$request->reID."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");
                return back()->with('form_submitted', 'The request has been rejected.');
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
    public function viewAppStatus($class,$id){
        $posts = DB::select("SELECT *, (SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID_SIGN`) AS 'Approved_By' FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = '".$class."' AND a.`COMPID` ='".session('LoggedUser_CompanyID')."'");
        return response()->json($posts);
    }

    // View Message
    public function viewClaComments($class,$id){

        if($class == 'REQUESTFORPAYMENT'){
            $class = 'Request for Payment';
        }

        if($class == 'REIMBURSEMENT_REQUEST'){
            $class = 'Reimbursement Request';
        }
        
        if($class == 'PETTYCASHREQUEST'){
            $class = 'Petty Cash Request';
        }

        if($class == 'SALES_ORDER_FRM'){
            $class = 'Sales Order - Project';
        }

        $comments = DB::select("SELECT *, (SELECT UserFull_name FROM general.`users` b WHERE b.id = a.`RECEIVERID`) AS 'SENDERNAME',(SELECT c.`USER_GRP_IND` FROM general.`actual_sign` c WHERE c.`ID` = a.`ACTUALID`) AS USERLEVEL FROM general.`notifications` a WHERE a.`PROCESSID` = $id AND a.`FRM_NAME` = '".$class."'");
        return response()->json($comments);
    }



    // View Approvals by single post
        public function getApprovalByID($class,$id,$frmname){

            if($class === 'REQUESTFORPAYMENT'){
                $post = DB::table('accounting.request_for_payment')->where('ID',$id)->first();
                $queryPostDetails = DB::select("SELECT * FROM accounting.`rfp_details` AS a WHERE a.`RFPID` = $id ");
                $postDetails = $queryPostDetails[0];
                $queryPayeeName = DB::select("SELECT Payee,FRM_NAME FROM general.`actual_sign` AS a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment'ORDER BY a.`Payee` LIMIT 1");

                $payeeDetails = $queryPayeeName[0];
                $expenseType = DB::select("SELECT type FROM accounting.`expense_type_setup`");
                $currencyType = DB::select("SELECT CurrencyName FROM accounting.`currencysetup`");
                $liqTableCondition = DB::select("SELECT COUNT(*) AS myNumLiq FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`TYPE` = 'Request for Payment' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`USER_GRP_IND` = 'Initiator' AND a.`STATUS` = 'In Progress'");
                $liqTableCondition = $liqTableCondition[0]->myNumLiq;


                $qeLiquidationTable = DB::select("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");
                $qeSubTotal = DB::select("SELECT SUM(Amount) subTotalAmount FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");

                $qeSubTotal = $qeSubTotal[0]->subTotalAmount;

                // NEW
                //recipient name
                $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                FROM
                (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Request for Payment' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                UNION ALL
                SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Request for Payment' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."')
                a GROUP BY uid;");


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


            if($class === 'REIMBURSEMENT_REQUEST'){
                $post = DB::table('accounting.reimbursement_request')->where('ID',$id)->first();
                $expenseDetails = DB::select("SELECT * FROM accounting.`reimbursement_expense_details` a WHERE a.`REID` = $id");
                $transpoDetails = DB::select("SELECT * FROM accounting.`reimbursement_request_details` a WHERE a.`REID` = $id");
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`reimbursement_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Reimbursement Request' AND a.`REQID` =$id");
                $initCheck = DB::select("SELECT IFNULL ((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`ORDERS` = 5 AND a.`STATUS` = 'In Progress'), FALSE) AS initQue;");


                $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                FROM (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Reimbursement Request' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                UNION ALL SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Reimbursement Request' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."') a GROUP BY uid;");

                $qeInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");

                return view('MyWorkflow.approval-byid.app-re', compact('post','initName','expenseDetails','transpoDetails','attachmentsDetails','initCheck','getRecipientName','qeInProgressID'));
            }


            if($class === 'PETTYCASHREQUEST'){
                $post = DB::table('accounting.petty_cash_request')->where('ID',$id)->first();
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`petty_cash_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Petty Cash Request' AND a.`REQID` =$id");

                // Initiator Check
                $initCheck = DB::select("SELECT IFNULL ((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`ORDERS` = 2 AND a.`STATUS` = 'In Progress'), FALSE) AS initCheck;");

                // for Approval of accounting Check
                $acctngCheck = DB::select("SELECT IFNULL ((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`ORDERS` = 1 AND a.`STATUS` = 'In Progress'), FALSE) AS acctngCheck;");
                $acknowledgementCheck = DB::select("SELECT IFNULL ((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress' AND a.`ORDERS` = '3'), FALSE) AS acknowledgementCheck;");
                $expenseType = DB::select("SELECT type FROM accounting.`expense_type_setup`");
                $transpoSetup = DB::select("SELECT MODE FROM accounting.`transpo_setup`");

                $expenseDetails = DB::select("SELECT * FROM accounting.`petty_cash_expense_details` a WHERE a.`PCID` = $id");
                $transpoDetails = DB::select("SELECT * FROM accounting.`petty_cash_request_details` a WHERE a.`PCID` = $id");

                $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                FROM (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Petty Cash Request' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                UNION ALL SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Petty Cash Request' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."') a GROUP BY uid;");

                $getInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");
                
                return view('MyWorkflow.approval-byid.app-pc', compact('post','initName','attachmentsDetails','initCheck','acctngCheck','expenseType','transpoSetup','acknowledgementCheck','expenseDetails','transpoDetails','getRecipientName','getInProgressID'));
            }

            if($class === 'SALES_ORDER_FRM'){

                if ($frmname === 'Sales Order - Project') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $approvalOfPrjHeadChecker = DB::select(" SELECT IFNULL((SELECT a.`status` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`ORDERS` = 3 AND a.`STATUS` = 'In Progress'), FALSE) AS checker ");
                    $projectCoordinator = DB::table('general.users')->where('id','!=','1')->where('status','ACTIVE')->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Project' AND a.`REQID` =$id");
                    $siConfirmationChecker =DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`ORDERS` = 7 AND a.`STATUS` = 'In Progress'), FALSE) AS checker");
                    
                    $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                    FROM (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Project' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                    UNION ALL SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Project' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."') a GROUP BY uid;");
    
                    $qeInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");
    
                    return view('MyWorkflow.approval-byid.app-sof-prj', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','approvalOfPrjHeadChecker','projectCoordinator','siConfirmationChecker','getRecipientName','qeInProgressID'));
                
    
                } 
    
                if ($frmname === 'Sales Order - Delivery') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $approvalOfPrjHeadChecker = DB::select(" SELECT IFNULL((SELECT a.`status` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`ORDERS` = 3 AND a.`STATUS` = 'In Progress' AND a.`USER_GRP_IND` = 'Approval of Project Head' ), FALSE) AS checker ");
                    $projectCoordinator = DB::table('general.users')->where('id','!=','1')->where('status','ACTIVE')->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Delivery' AND a.`REQID` =$id");
                    $siConfirmationChecker =DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`ORDERS` = 7 AND a.`STATUS` = 'In Progress'), FALSE) AS checker");
                    
                    $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                    FROM (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Delivery' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                    UNION ALL SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Delivery' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."') a GROUP BY uid;");
    
                    $qeInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");
    
                    return view('MyWorkflow.approval-byid.app-sof-dlv', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','approvalOfPrjHeadChecker','projectCoordinator','siConfirmationChecker','getRecipientName','qeInProgressID'));
                
                } 

                if ($frmname === 'Sales Order - Demo') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $approvalOfPrjHeadChecker = DB::select(" SELECT IFNULL((SELECT a.`status` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`ORDERS` = 3 AND a.`STATUS` = 'In Progress' AND a.`USER_GRP_IND` = 'Approval of Project Head' ), FALSE) AS checker ");
                    $projectCoordinator = DB::table('general.users')->where('id','!=','1')->where('status','ACTIVE')->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Demo' AND a.`REQID` =$id");
                    $siConfirmationChecker =DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`ORDERS` = 7 AND a.`STATUS` = 'In Progress'), FALSE) AS checker");
                    
                    $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                    FROM (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Demo' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                    UNION ALL SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - Demo' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."') a GROUP BY uid;");
    
                    $qeInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");
    
                    $dmoInitCheck = DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`USER_GRP_IND` = 'Initiator' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`STATUS` = 'In Progress' AND a.`ORDERS` = 4 AND a.`Max_approverCount` = 5), FALSE) AS checker");


                    return view('MyWorkflow.approval-byid.app-sof-dmo', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','approvalOfPrjHeadChecker','projectCoordinator','siConfirmationChecker','getRecipientName','qeInProgressID','dmoInitCheck','dmoInitCheck'));
                
                } 

                if ($frmname === 'Sales Order - POC') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();

                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();


                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $approvalOfPrjHeadChecker = DB::select(" SELECT IFNULL((SELECT a.`status` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`ORDERS` = 3 AND a.`STATUS` = 'In Progress' AND a.`USER_GRP_IND` = 'Approval of Project Head' ), FALSE) AS checker ");
                    $projectCoordinator = DB::table('general.users')->where('id','!=','1')->where('status','ACTIVE')->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - POC' AND a.`REQID` =$id");
                    $siConfirmationChecker =DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`ORDERS` = 7 AND a.`STATUS` = 'In Progress'), FALSE) AS checker");
                    
                    $getRecipientName = DB::select("SELECT a.uid,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.uid) AS 'Name'
                    FROM (SELECT initid AS 'uid' FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - POC' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND initid <> '".session('LoggedUser')."'
                    UNION ALL SELECT UID_SIGN AS 'uid'  FROM general.`actual_sign` WHERE processid = $id AND `FRM_NAME` = 'Sales Order - POC' AND `COMPID` = '".session('LoggedUser_CompanyID')."' AND `status` = 'Completed' AND uid_sign <> '".session('LoggedUser')."') a GROUP BY uid;");
    
                    $qeInProgressID = DB::select("SELECT IFNULL((SELECT ID AS inpId FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In progress'), FALSE) AS inpId;");
    
                    $dmoInitCheck = DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`USER_GRP_IND` = 'Initiator' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`STATUS` = 'In Progress' AND a.`ORDERS` = 4 AND a.`Max_approverCount` = 5), FALSE) AS checker");


                    return view('MyWorkflow.approval-byid.app-sof-poc', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','approvalOfPrjHeadChecker','projectCoordinator','siConfirmationChecker','getRecipientName','qeInProgressID','dmoInitCheck','dmoInitCheck'));
                
                } 



    

            }
       


}

            // SOF - Project


            public function replySOF(Request $request){

                if (!empty($request->checkInit) == True && !empty($request->checkOrder) == True) {

                    if($request->frmname === 'Sales Order - Demo' || $request->frmname === 'Sales Order - POC'){
  
                        $request->validate([
                            'poNumber'=>'required',
                            'poDate'=>'required',
                            'scopeOfWork'=>'required',
                            'accountingRemarks'=>'required',
                            'clientID' => 'required|not_in:0',
                            'projectCode'=>'required',
                            'projectShortText'=>'required',
                            'projectName'=>'required',
                            'contactPerson'=>'required', 
                            'contactNumber'=>'required',
                            'deliveryAddress'=>'required',
                            'billingAddress'=>'required',
                
               
                
                            'systemname'=>'required',
                            'documentname'=>'required',
                
                            // 'accountmanager'=>'required',
                
                
                            // 'file'=>'required'
                        ]);  
                         
                        $projectStart = NULL;
                        $projectEnd = NULL;
                        $projectDuation = NULL;

                    }else{
                        $request->validate([
                            'poNumber'=>'required',
                            'poDate'=>'required',
                            'scopeOfWork'=>'required',
                            'accountingRemarks'=>'required',
                            'clientID' => 'required|not_in:0',
                            'projectCode'=>'required',
                            'projectShortText'=>'required',
                            'projectName'=>'required',
                            'contactPerson'=>'required', 
                            'contactNumber'=>'required',
                            'deliveryAddress'=>'required',
                            'billingAddress'=>'required',
                
                            'paymentTerms'=>'required',
                            'projectStart'=>'required',
                            'projectEnd'=>'required',
                            'warranty'=>'required',   
                            'currency'=>'required',
                            'projectCost'=>'required',
                            
                
                            'systemname'=>'required',
                            'documentname'=>'required',
                
                            // 'accountmanager'=>'required',
                
                            'downpaymentrequired' => 'required|bool',
                            'downPaymentPercentage' => 'required_if:downpaymentrequired,1|numeric|between:1,100',
                
                            'invoicerequired' => 'required|bool',
                            'invoiceDateNeeded' => 'required_if:invoicerequired,1',
                
                            // 'file'=>'required'
                        ]);       
                        $projectStart = date_create($request->projectStart);
                        $projectEnd = date_create($request->projectEnd);   

                        $projectStart = date_format($projectStart, 'Y-m-d');
                        $projectEnd = date_format($projectEnd, 'Y-m-d');

                        $projectStartConverted = strtotime($projectStart);
                        $projectEndConverted = strtotime($projectEnd);
                
        
                        $projectDuation = ($projectEndConverted - $projectStartConverted)/60/60/24;
                    }



    
                    $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->soID."' AND a.`FRM_NAME` = '".$request->frmname."' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
                    $notifCount = count($notif);
    

                    $dateCreated = date_create($request->dateCreated);
                    $poDate = date_create($request->poDate);
    
      
                    $dateCreated = date_format($dateCreated, 'Y-m-d');
                    $poDate = date_format($poDate, 'Y-m-d');
    

    
                    if(!empty($request->downPaymentPercentage)){
                        $downPaymentPercentage = $request->downPaymentPercentage;
                    } else {
                        $downPaymentPercentage = 0;
                    }
    
    
                    if($notif == True){
    
                        $nParentId= $notif[0]->ID;
                        $nReceiverId= $notif[0]->SENDERID;
                        $nActualId= $notif[0]->ACTUALID;
    
                        if(!empty($request->invoiceDateNeeded)){
                        $invoiceDateNeeded = date_create($request->invoiceDateNeeded);
                        $invoiceDateNeeded = date_format($invoiceDateNeeded, 'Y-m-d');
                        } else {
                        $invoiceDateNeeded = 'NULL';
                        }
    
    
                       $insert_doc = DB::table('general.notifications')->insert([
    
                        'ParentID' =>$nParentId,
                        'levels'=>'0',
                        'FRM_NAME' =>$request->frmname,
                        'PROCESSID' =>$request->soID,
                        'SENDERID' =>session('LoggedUser'),
                        'RECEIVERID' =>$nReceiverId,
                        'MESSAGE' =>$request->replyRemarks,
                        'TS' =>NOW(),
                        'SETTLED' => 'YES',
                        'ACTUALID' => $nActualId,
                        'SENDTOACTUALID' =>'0',
                        'UserFullName' =>session('LoggedUser_FullName'),
    
                       ]);
                       
                        // Setup Project Update
                        DB::update("UPDATE general.`setup_project` a SET 
                        a.`project_name` = '".$request->projectName."',
                        a.`project_shorttext` = '".$request->projectShortText."',
                        a.`project_location` = '".$request->deliveryAddress."', 
                        a.`project_remarks` = '".$request->scopeOfWork."', 
                        a.`project_no` = '".$request->projectCode."',
                        a.`project_amount` = '".$request->projectCost."', 
                        a.`project_duration` = '".$projectDuation."',                    
                        a.`project_effectivity` = '".$projectStart."', 
                        a.`project_expiry` = '".$projectEnd."', 
                        a.`ClientID` = '".$request->clientID."', 
                        a.`ProjectStatus` = 'On-Going', 
                        a.`last_edit_datetime` = NOW()
                        WHERE a.`SOID` = '".$request->soID."' ");
    
                        // Sales Order - Main
                        DB::update("UPDATE sales_order.`sales_orders` a SET 
                        a.`pcode` = '".$request->projectCode."',
                        a.`project` = '".$request->projectName."',
                        a.`clientID` = '".$request->clientID."', 
                        a.`client` = '".$request->client."', 
                        a.`Contactid` = '".$request->contactPerson."',
                        a.`Contact` = '".$request->contactPersonName."', 
                        a.`ContactNum` = '".$request->contactNumber."',  
                        a.`sodate` = '".$dateCreated."', 
                        a.`podate` = '".$poDate."',                     
                        a.`poNum` = '".$request->poNumber."', 
                        a.`DeliveryAddress` = '".$request->deliveryAddress."', 
                        a.`BillTo` = '".$request->billingAddress."',  
                        a.`currency` = '".$request->currency."',  
                        a.`amount` = '".$request->projectCost."',  
                        a.`remarks` = '".$request->scopeOfWork."',  
                        a.`Remarks2` = '".$request->accountingRemarks."',  
                        a.`DateAndTimeNeeded` = '".$projectEnd."', 
                        a.`Terms` = '".$request->paymentTerms."',
                        a.`Status` =  'In Progress',  
                        a.`DeadLineDate` = '".$projectEnd."', 
                        a.`IsInvoiceRequired` = '".$request->invoicerequired."', 
                        a.`invDate` = '".$invoiceDateNeeded."', 
                        a.`dp_required` = '".$request->downpaymentrequired."',  
                        a.`dp_percentage` = '".$downPaymentPercentage."',  
                        a.`project_shorttext` = '".$request->projectShortText."',  
                        a.`warranty` = '".$request->warranty."'
                        WHERE a.`id` = '".$request->soID."' ");
    
    
                        // System Name
                        DB::table('sales_order.sales_order_system')->where('soid', $request->soID)->delete();
    
                        for($i = 0; $i <count($request->systemname); $i++) {
                            $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a WHERE a.`id` = '".$request->systemname[$i]."'" );
                            $systemName = $systemName[0]->type_name;
                
                            $systemNameArray[] = [
                                'soid' => $request->soID,
                                'systemType'=> $systemName,
                                'sysID' => $request->systemname[$i],
                                'imported_from_excel' => '0'
                            ];
                
                        }
                        DB::table('sales_order.sales_order_system')->insert($systemNameArray);
    
    
    
                        // System Docs
                        DB::table('sales_order.sales_order_docs')->where('SOID', $request->soID)->delete();
    
                        for($i = 0; $i <count($request->documentname); $i++) {
    
                            $documentname = DB::select("SELECT * FROM sales_order.`documentlist` a WHERE a.`id` = '".$request->documentname[$i]."'" );
                            $documentname = $documentname[0]->DocumentName;
                
                            $documentnameArray[] = [
                                'SOID' => $request->soID,
                                'DocID'=> $request->documentname[$i],
                                'DocName' => $documentname,
                                'imported_from_excel' => '0'
                            ];
                
                        }
                
                        DB::table('sales_order.sales_order_docs')->insert($documentnameArray);
    
                        // Actual Sign
                        DB::update("UPDATE general.`actual_sign` a SET 
                        a.`REMARKS` = '".$request->scopeOfWork."', 
                        a.`TS` = NOW(), 
                        a.`DUEDATE` = '".$projectEnd."', 
                        a.`PODATE` = '".$poDate."',
                        a.`PONUM` = '".$request->poNumber."',                  
                        a.`DATE` = '".$projectEnd."', 
                        a.`CLIENTID` = '".$request->clientID."', 
                        a.`CLIENTNAME` = '".$request->client."',
                        a.`Amount` = '".$request->projectCost."'
                        WHERE a.`PROCESSID` = '".$request->soID."' AND a.`FRM_NAME` = '".$request->frmname."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ");
    
                        // Actual Sign 
                        // 1 line
                        DB::update("UPDATE general.`actual_sign` a SET 
                        a.`STATUS` = 'In Progress', 
                        a.`ApprovedRemarks` = '".$request->replyRemarks."', 
                        a.`CurrentSender` = '', 
                        a.`CurrentReceiver` = '',
                        a.`NOTIFICATIONID` = ''             
                        WHERE a.`PROCESSID` = '".$request->soID."' AND a.`FRM_NAME` = '".$request->frmname."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification' ");
    
    
                        $toDeleteFile = $request->deleteAttached;
                        $toDeleteFile =json_decode($toDeleteFile,true);
                        
                        // newly added
    
                        if(!empty($toDeleteFile)){
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
                                // $ref = $request->referenceNumber;
                                $soNumber = str_replace('-', '_', $request->sofNumber);
                                // For moving the file
                                $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                                // For preview
                                $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                                $symPath ="public/Attachments/SOF";
                                $file->storeAs($destinationPath, $completeFileName);
                                $fileDestination = $storagePath.'/'.$completeFileName;

                                $image = base64_encode(file_get_contents($file));

                
                                DB::table('repository.so_attachment')->insert([
                                    'REFID' => $request->soID,
                                    'FileName' => $completeFileName,
                                    'IMG' => $image,
                                    'UID' => session('LoggedUser'),
                                    'Ext' => $extension
                                ]);
                                

                                $insert_doc = DB::table('general.attachments')->insert([
                                    'INITID' => session('LoggedUser'),
                                    'REQID' => $request->soID, 
                                    'filename' => $completeFileName,
                                    'filepath' => $storagePath, 
                                    'fileExtension' => $extension,
                                    'newFilename' => $newFileName,
                                    'fileDestination'=>$destinationPath,
                                    'formName' => $request->frmname,
                                    'created_at' => date('Y-m-d H:i:s')
                           
                                ]);
                            }
                        } 
    
                    
                    return back()->with('form_submitted', 'Your request is now In Progress.');
                    } else {
                    return back()->with('form_submitted', 'Reply Error');
                    }
                
                } else {
                         
                        $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->soID."' AND a.`FRM_NAME` = '".$request->frmname."' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
                        $notifCount = count($notif);
        
                        if($notif == True){
        
                            $nParentId= $notif[0]->ID;
                            $nReceiverId= $notif[0]->SENDERID;
                            $nActualId= $notif[0]->ACTUALID;
        
        
                           $insert_doc = DB::table('general.notifications')->insert([
        
                            'ParentID' =>$nParentId,
                            'levels'=>'0',
                            'FRM_NAME' =>$request->frmname,
                            'PROCESSID' =>$request->soID,
                            'SENDERID' =>session('LoggedUser'),
                            'RECEIVERID' =>$nReceiverId,
                            'MESSAGE' =>$request->replyRemarks,
                            'TS' =>NOW(),
                            'SETTLED' => 'YES',
                            'ACTUALID' => $nActualId,
                            'SENDTOACTUALID' =>'0',
                            'UserFullName' =>session('LoggedUser_FullName'),
        
                           ]);
                           
                            // Sales Order - Main
                            DB::update("UPDATE sales_order.`sales_orders` a SET 
                            a.`Status` =  'In Progress'
                            WHERE a.`id` = '".$request->soID."' ");
        
                            // Actual Sign 
                            // 1 line
                            DB::update("UPDATE general.`actual_sign` a SET 
                            a.`STATUS` = 'In Progress', 
                            a.`ApprovedRemarks` = '".$request->replyRemarks."', 
                            a.`CurrentSender` = '', 
                            a.`CurrentReceiver` = '',
                            a.`TS` = NOW(),
                            a.`SIGNDATETIME` = NOW(),
                            a.`NOTIFICATIONID` = ''             
                            WHERE a.`PROCESSID` = '".$request->soID."' AND a.`FRM_NAME` = '".$request->frmname."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification' ");
              
                        
                        return back()->with('form_submitted', 'The request is now In Progress.');
                        } else {
                        return back()->with('form_submitteds', 'Reply Error');
                        }
                }
                
            }





            // Clarify / Approver
            public function clarifySOF(Request $request){
                // dd($request->soID, $request->inpID);

                $notificationIdClarity = DB::table('general.notifications')->insertGetId([
                    'ParentID' =>'0',
                    'levels'=>'0',
                    'FRM_NAME' =>$request->frmname,
                    'PROCESSID' =>$request->soID,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$request->clarityRecipient,
                    'MESSAGE' =>$request->clarificationRemarks,
                    'TS' =>NOW(),
                    'SETTLED' =>'NO',
                    'ACTUALID' =>$request->inpID,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName')
                ]);

                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'For Clarification', a.`CurrentSender` = '".session('LoggedUser')."', a.`CurrentReceiver` = '".$request->clarityRecipient."' ,
                a.`NOTIFICATIONID` = '".$notificationIdClarity."', a.`UID_SIGN` = '".session('LoggedUser')."',a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '".$request->clarificationRemarks."' WHERE
                a.`PROCESSID` = '".$request->soID."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`STATUS` = 'In Progress'
                ");
                
                DB::update("UPDATE sales_order.`sales_orders` a SET a.`Status` =  'For Clarification' WHERE a.`id` = '".$request->soID."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");

                return back()->with('form_submitted', 'The request is now For Clarification.');
            }


            // Reject / Approver
            public function rejectedSOF(Request $request){

                DB::update("UPDATE sales_order.`sales_orders` a SET a.`Status` =  'Rejected' WHERE a.`id` = '".$request->soID."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Rejected', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->rejectedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->soID."' AND `FRM_CLASS` = 'SALES_ORDER_FRM' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
                return back()->with('form_submitted', 'The request has been Rejected.');

            }


            // Approve / Approver
            public function approvedSOF(Request $request){

                if(!empty($request->approvalOfPrjHeadChecker)){
                                    
                    $request->validate([
                        'coordinatorID' => 'required'
                    ]);

                    DB::table('sales_order.projectcoordinator')->insert([
                        'CoordID' => $request->coordinatorID,
                        'CoordinatorName' =>$request->coordinatorName,
                        'SOID' => $request->soID,
                        'SOTYPE' => 'Sales Order - Project'
                    ]);
              
                    DB::update("UPDATE general.`setup_project` a SET a.`Coordinator` = '".$request->coordinatorID."' WHERE a.`SOID` = '".$request->soID."' AND a.`title_id` = '".session('LoggedUser_CompanyID')."' ");
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->soID."' AND `FRM_CLASS` = 'SALES_ORDER_FRM' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->soID."' AND `FRM_CLASS` = 'SALES_ORDER_FRM' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
                    return back()->with('form_submitted', 'The request has been approved.');

                } elseif(!empty($request->siConfirmationChecker)) {
                   
                    $request->validate([
                        'salesInvoiceReleased' => 'required|numeric|gt:0',
                        'dateOfInvoice' => 'required_if:salesInvoiceReleased,1'
                    ],
                    // Custom Messages
                    [
                        'required'  => 'The :attribute field is required.',
                        'unique'    => 'attribute is already used',
                        'gt'        => 'The :attribute field is required',
                        'required_if' => 'The :attribute field is required when Sales Invoice Released is Yes.'
                    ]);


                    // dd($request->salesInvoiceReleased);
                                

                    $dateOfInvoice = date_create($request->dateOfInvoice);
                    $dateOfInvoice = date_format($dateOfInvoice, 'Y-m-d');
                    
                    DB::update("UPDATE general.`setup_project` a SET a.`ProjectStatus` = 'Closed' WHERE a.`title_id` = '".session('LoggedUser_CompanyID')."' AND a.`status` LIKE 'Active%' AND a.`SOID` = '".$request->soID."'");
                    DB::update("UPDATE sales_order.`sales_orders` a SET 
                    a.`Status` = 'Completed',
                    a.`InvoiceNumber` = '".$request->invoiceNumber."', 
                    a.`InvoiceDate` = '".$dateOfInvoice."', 
                    -- a.`IsInvoiceReleased` = '".$request->salesInvoiceReleased."', 
                    a.`IsInvoiceReleased` = '1' 
                    WHERE a.`titleid` = '".session('LoggedUser_CompanyID')."' AND a.`id` = '".$request->soID."'");
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "', `DoneApproving` = '1' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->soID."' AND `FRM_CLASS` = 'SALES_ORDER_FRM' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
                    return back()->with('form_submitted', 'The request has been approved.');



                } elseif(!empty($request->dmoInitCheck)) {
                
                    DB::update("UPDATE general.`setup_project` a SET a.`ProjectStatus` = 'Closed' WHERE a.`title_id` = '".session('LoggedUser_CompanyID')."' AND a.`status` LIKE 'Active%' AND a.`SOID` = '".$request->soID."'");
                    DB::update("UPDATE sales_order.`sales_orders` a SET 
                    a.`Status` = 'Completed',
                    a.`IsInvoiceReleased` = '1' 
                    WHERE a.`titleid` = '".session('LoggedUser_CompanyID')."' AND a.`id` = '".$request->soID."'");
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "', `DoneApproving` = '1' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->soID."' AND `FRM_CLASS` = 'SALES_ORDER_FRM' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
                    return back()->with('form_submitted', 'The request has been approved.');
         
                } else {


                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->soID."' AND `FRM_CLASS` = 'SALES_ORDER_FRM' AND `COMPID` = '".session('LoggedUser_CompanyID')."'  ;");
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->soID."' AND `FRM_CLASS` = 'SALES_ORDER_FRM' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
                    return back()->with('form_submitted', 'The request has been approved.');
                }
                
            }




            // PC
            // Approver
            public function approvedPCApp(Request $request){

                if (!empty($request->acctngCheck)){
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' 
                    WHERE `status` = 'In Progress' AND PROCESSID = '".$request->pcID."' AND `FRM_CLASS` = 'PETTYCASHREQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->pcID."' AND `FRM_CLASS` = 'PETTYCASHREQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
                    DB::update("UPDATE accounting.`petty_cash_request` a SET a.`ISRELEASED` = '1', a.`RELEASEDCASH` = '1' WHERE a.`id` = '".$request->pcID."' ");
                    return back()->with('form_submitted', 'The request has been approved.');
                                 
                }elseif(!empty($request->acknowledgementCheck)){
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' 
                    WHERE `status` = 'In Progress' AND PROCESSID = '".$request->pcID."' AND `FRM_CLASS` = 'PETTYCASHREQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                    DB::update("UPDATE accounting.`petty_cash_request` a SET a.`STATUS` = 'Completed' WHERE a.`id` = '".$request->pcID."' ");
                    return back()->with('form_submitted', 'The request has been approved.');
                } else {

                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' 
                    WHERE `status` = 'In Progress' AND PROCESSID = '".$request->pcID."' AND `FRM_CLASS` = 'PETTYCASHREQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->pcID."' AND `FRM_CLASS` = 'PETTYCASHREQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
                    return back()->with('form_submitted', 'The request has been approved.');

                }

            }

            // Rejected
            public function rejectedPCApp(Request $request){
                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'Rejected', a.`UID_SIGN` = '".session('LoggedUser')."', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->rejectedRemarks. "' WHERE a.`STATUS` = 'In Progress' AND a.`PROCESSID` = '".$request->pcID."' AND  a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE accounting.`petty_cash_request` a SET a.`STATUS` = 'Rejected'  WHERE a.`ID` = '".$request->pcID."'  ");
                return back()->with('form_submitted', 'The request has been rejected.');

            }

            // Clarify 
            public function clarifyPCApp(Request $request){
    
                $notificationIdClarity = DB::table('general.notifications')->insertGetId([
                    'ParentID' =>'0',
                    'levels'=>'0',
                    'FRM_NAME' =>'Petty Cash Request',
                    'PROCESSID' =>$request->pcID,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$request->clarityRecipient,
                    'MESSAGE' =>$request->clarificationRemarks,
                    'TS' =>NOW(),
                    'SETTLED' =>'NO',
                    'ACTUALID' =>$request->inpID,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName')
                ]);

                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'For Clarification', a.`CurrentSender` = '".session('LoggedUser')."', a.`CurrentReceiver` = '".$request->clarityRecipient."' ,
                a.`NOTIFICATIONID` = '".$notificationIdClarity."', a.`UID_SIGN` = '".session('LoggedUser')."',a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '".$request->clarificationRemarks."' WHERE
                a.`PROCESSID` = '".$request->pcID."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`STATUS` = 'In Progress'
                ");
                
                DB::update("UPDATE accounting.`petty_cash_request` a SET a.`STATUS` = 'For Clarification'  WHERE a.`ID` = '".$request->pcID."' ;");


                return back()->with('form_submitted', 'The request is now For Clarification.');
            }



            public function approvedPCAppInit(Request $request){

                $mainID =DB::select("SELECT IFNULL(( SELECT a.`Main_office_id` FROM general.`setup_project` a WHERE a.`project_id` = '".$request->projectID."' LIMIT 1 ), FALSE) AS mainID;");
                

                if (!empty($request->xdData) == true || !empty($request->tdData) == true) {

                    if(!empty($request->xdData) == true){
                      
                        $expenseDetails = $request->xdData;
                        $expenseDetails =json_decode($expenseDetails,true);

                        $xdArray = $expenseDetails;

                        for($i = 0; $i <count($xdArray); $i++) {
                            $setXDArray[] = [
                                'PCID' => $request->pcID,
                                'payee_id'=>'0',
                                'PAYEE' => $request->payeeName,
                                'CLIENT_NAME' => $request->clientName, 
                                'TITLEID'=>session('LoggedUser_CompanyID'),
                                'PRJID' =>'0',
                                'PROJECT' =>'',
                                'DESCRIPTION' => $xdArray[$i][2],
                                'AMOUNT' => $xdArray[$i][3],
                                'GUID'=>$request->guid,  
                                'TS' => now(),
                                'MAINID' => $mainID[0]->mainID,
                                'STATUS' =>'ACTIVE',
                                'CLIENT_ID' =>$request->clientID, 
                                'EXPENSE_TYPE'=> $xdArray[$i][1],
                                'DEPT'=> '',
                                'RELEASEDCASH'=> '0',
                                'date_'=> $xdArray[$i][0],
                                'ISLIQUIDATED' => '0'
                            ];
                        }
        
                        DB::table('accounting.petty_cash_expense_details')->insert($setXDArray);


                }

                if(!empty($request->tdData) == true){

                        DB::table('accounting.reimbursement_request_details')->where('REID', $request->reID)->delete();

                        $transpoDetails = $request->tdData;
                        $transpoDetails =json_decode($transpoDetails,true);
                        $tdArray = $transpoDetails;

                        if(!empty($tdArray) == true){
                            // return "tdarray true";
                            // $tdArray = [];
                            for($i = 0; $i <count($tdArray); $i++) {
                                $setTDArray[] = [
            
                                    'PCID' => $request->pcID,
                                    'PRJID'=> '0',
                                    'payee_id' => '0',
                                    'PAYEE' => $request->payeeName, 
                                    'CLIENT_NAME'=> $request->clientName,
                                    'DESTINATION_FRM' => $tdArray[$i][1],
                                    'DESTINATION_TO' => $tdArray[$i][2],
                                    'DESCRIPTION' => $tdArray[$i][4],
                                    'AMT_SPENT' => $tdArray[$i][5],
                                    'TITLEID'=> '1',
                                    'MOT' => $tdArray[$i][3],
                                    'PROJECT' => '',
                                    'GUID' =>$request->guid, 
                                    'TS' =>now(),
                                    'MAINID'=> $mainID[0]->mainID,
                                    'STATUS'=> 'ACTIVE',
                                    'CLIENT_ID'=> $request->clientID,
                                    'DEPT'=> session('LoggedUser_DepartmentName'),
                                    'RELEASEDCASH'=> '0',
                                    'date_'=> $tdArray[$i][0],
                                    'ISLIQUIDATED' => '0'
                                ];
                            }
                            DB::table('accounting.petty_cash_request_details')->insert($setTDArray);
                        }
                }

               
                // Delete attachments
                $toDeleteFile = $request->deleteAttached;
                $toDeleteFile =json_decode($toDeleteFile,true);
                
        
                if(isset($toDeleteFile)) {
                for($i = 0; $i <count($toDeleteFile); $i++) {
                $idAttachment = $toDeleteFile[$i]['0'];
                $pathAttachment = $toDeleteFile[$i]['1'];
                $fileNameAttachment = $toDeleteFile[$i]['2'];

                $public_path = public_path($pathAttachment.'/'.$fileNameAttachment);
                unlink($public_path);

                DB::table('general.attachments')->where('id', $idAttachment)->delete();
                }
                }

                if($request->hasFile('file')){
                    foreach($request->file as $file) {
                        $completeFileName = $file->getClientOriginalName();
                        $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $randomized = rand();
                        $newFileName = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                        // $path = '/uploads/attachments/'.$GUID;
                        $ref = $request->referenceNumber;
                        $ref = str_replace('-', '_', $ref);
                        // For moving the file
                        $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/PC/".$ref;
                        // For preview
                        $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/PC/".$ref;
                        $symPath ="public/Attachments/PC";
                        $file->storeAs($destinationPath, $completeFileName);
                        $fileDestination = $storagePath.'/'.$completeFileName;
                        
                        $image = base64_encode(file_get_contents($file));
                
                        DB::table('repository.petty_cash')->insert([
                            'REFID' => $request->pcID,
                            'FileName' => $completeFileName,
                            'IMG' => $image,
                            'UID' => session('LoggedUser'),
                            'Ext' => $extension
                        ]);
                        

                        $insert_doc = DB::table('general.attachments')->insert([
                            'INITID' => session('LoggedUser'),
                            'REQID' => $request->pcID, 
                            'filename' => $completeFileName,
                            'filepath' => $storagePath, 
                            'fileExtension' => $extension,
                            'newFilename' => $newFileName,
                            'fileDestination'=>$destinationPath,
                            'formName' => 'Petty Cash Request',
                            'created_at' => date('Y-m-d H:i:s')
                   
                        ]);
                    }
                } 
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedModalInit. "' 
                    WHERE `status` = 'In Progress' AND PROCESSID = '".$request->pcID."' AND `FRM_CLASS` = 'PETTYCASHREQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->pcID."' AND `FRM_CLASS` = 'PETTYCASHREQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");

                    return back()->with('form_submitted', 'Your request is now In Progress.'); 
                } else {
                    return back()->with('form_error', 'Request Failed, Please provide records!');
                }



            }









            //Approve Reimbursement in Approvals by Reporting Manager 
            public function approvedREApp(Request $request){

                // For Initiator Only // Approve and Completed Main RE
                if (!empty(intval($request->apprCheckinit))){
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->reID."' AND `FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                    DB::update("UPDATE accounting.`reimbursement_request` a SET a.`STATUS` = 'Completed'  WHERE a.`ID` = '".$request->reID."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");      
                    return back()->with('form_submitted', 'The request has been approved.');
                    
                }else{
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'Completed', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->reID."' AND `FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                    DB::update("UPDATE general.`actual_sign` SET `status` = 'In Progress' WHERE `status` = 'Not Started' AND PROCESSID = '".$request->reID."' AND `FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' LIMIT 1;");
                    return back()->with('form_submitted', 'The request has been approved.');
                }
            }

            //Reject Reimbursement in Approvals by approver
            public function rejectedREApp(Request $request){
                DB::update("UPDATE general.`actual_sign` SET `status` = 'Rejected', UID_SIGN = '".session('LoggedUser')."', SIGNDATETIME = NOW(), ApprovedRemarks = '" .$request->approvedRemarks. "' WHERE `status` = 'In Progress' AND PROCESSID = '".$request->reID."' AND `FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND `COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE accounting.`reimbursement_request` a SET a.`STATUS` = 'Rejected'  WHERE a.`ID` = '".$request->reID."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");
                return back()->with('form_submitted', 'The request has been rejected.');
            }

            // Clarify Reimbursement in Approvals by approver
            public function clarifyREApp(Request $request){
                
                $notificationIdClarity = DB::table('general.notifications')->insertGetId([
                    'ParentID' =>'0',
                    'levels'=>'0',
                    'FRM_NAME' =>'Reimbursement Request',
                    'PROCESSID' =>$request->reID,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$request->clarityRecipient,
                    'MESSAGE' =>$request->clarificationRemarks,
                    'TS' =>NOW(),
                    'SETTLED' =>'NO',
                    'ACTUALID' =>$request->inpID,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName')
                ]);

                DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'For Clarification', a.`CurrentSender` = '".session('LoggedUser')."', a.`CurrentReceiver` = '".$request->clarityRecipient."' ,
                a.`NOTIFICATIONID` = '".$notificationIdClarity."', a.`UID_SIGN` = '".session('LoggedUser')."',a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '".$request->clarificationRemarks."' WHERE
                a.`PROCESSID` = '".$request->reID."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND a.`STATUS` = 'In Progress'
                ");
                
                DB::update("UPDATE accounting.`reimbursement_request` a SET a.`STATUS` = 'For Clarification'  WHERE a.`ID` = '".$request->reID."' ;");


                return back()->with('form_submitted', 'The request is now For Clarification.');
            }


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


                                $image = base64_encode(file_get_contents($file));

                
                                DB::table('repository.rfp')->insert([
                                    'REFID' => $request->idName, 
                                    'FileName' => $completeFileName,
                                    'IMG' => $image,
                                    'UID' => session('LoggedUser'),
                                    'Ext' => $extension
                                ]);

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



                $image = base64_encode(file_get_contents($file));

                
                DB::table('repository.rfp')->insert([
                    'REFID' => $rfpIDAttachment[0]->ID, 
                    'FileName' => $completeFileName,
                    'IMG' => $image,
                    'UID' => session('LoggedUser'),
                    'Ext' => $extension
                ]);

    
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

        Paginator::useBootstrap();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($posts);
        $perPage = 10;
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
        $paginatedItems->setPath($request->url());


        return view('MyWorkflow.in-progress', ['posts' => $paginatedItems]);

    }




        // View in-progress
        public function getInProgressByID($class,$id,$frmname){

            if($class === 'REQUESTFORPAYMENT'){
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

            if($class === 'REIMBURSEMENT_REQUEST'){
                $post = DB::table('accounting.reimbursement_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`reimbursement_expense_details` a WHERE a.`REID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`reimbursement_request_details` a WHERE a.`REID` = $id");
                // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`reimbursement_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Reimbursement Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.in-progress-byid.inp-re', compact('post','initName','expenseDetails','transpoDetails','attachmentsDetails'));
                
            }

            if($class === 'PETTYCASHREQUEST'){
                $post = DB::table('accounting.petty_cash_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`petty_cash_expense_details` a WHERE a.`PCID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`petty_cash_request_details` a WHERE a.`PCID` = $id");
                // // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`petty_cash_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Petty Cash Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.in-progress-byid.inp-pc', compact('post','initName','attachmentsDetails','expenseDetails','transpoDetails'));
            }



            if($class === 'SALES_ORDER_FRM'){
                
                if ($frmname === 'Sales Order - Project') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Project' AND a.`REQID` =$id");

                    return view('MyWorkflow.in-progress-byid.inp-sof-prj', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));

                } 

                if ($frmname === 'Sales Order - Delivery') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Delivery' AND a.`REQID` =$id");

                    return view('MyWorkflow.in-progress-byid.inp-sof-dlv', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - Demo') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Demo' AND a.`REQID` =$id");

                    return view('MyWorkflow.in-progress-byid.inp-sof-dmo', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - POC') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - POC' AND a.`REQID` =$id");

                    return view('MyWorkflow.in-progress-byid.inp-sof-poc', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 


            }

            



     

        }

            // Sales Order 
            // Withdraw
            public function withdrawSOF(Request $request){
                DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
                WHERE a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`PROCESSID` = '".$request->soID."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress'");
                DB::update("UPDATE sales_order.`sales_orders` a SET a.`Status` = 'Withdrawn'  WHERE a.`id` = '".$request->soID."' AND a.`titleid` = '".session('LoggedUser_CompanyID')."' ");
                return back()->with('form_submitted', 'Your request is now Withdrawn.');
            }

 











            // RFP withdraw Button in In-progress Workflow
            public function withdrawnByIDRemarks(Request $request){
                DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
                WHERE a.`PROCESSID` = '".$request->idName."' AND a.`FRM_CLASS` = 'REQUESTFORPAYMENT' AND a.`STATUS` = 'In Progress' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE accounting.`request_for_payment` a SET a.`STATUS` = 'Withdrawn'  WHERE a.`ID` = '".$request->idName."' AND a.`REQREF` = '".$request->refNumberApp."';");

                return back()->with('form_submitted', 'Your request is now Withdrawn.');
            }


            // RE withdraw Button in In-progress Workflow
            public function inpREWithdraw(Request $request){
                DB::update("UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn', a.`SIGNDATETIME` = NOW(), a.`ApprovedRemarks` = '" .$request->withdrawRemarks. "' 
                WHERE a.`FRM_CLASS` = 'REIMBURSEMENT_REQUEST' AND a.`PROCESSID` = '".$request->reID."' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'In Progress'");
                DB::update("UPDATE accounting.`reimbursement_request` a SET a.`STATUS` = 'Withdrawn'  WHERE a.`ID` = '".$request->reID."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");

                return back()->with('form_submitted', 'Your request is now Withdrawn.');
            }


            // PC
            public function inpPCWithdraw(Request $request){
                DB::update(" UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn',
                a.`SIGNDATETIME` = NOW(),
                a.`ApprovedRemarks` = '" .$request->withdrawRemarks. 
                "' 
                WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`STATUS` = 'In Progress' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ;");
                DB::update("UPDATE accounting.`petty_cash_request` a SET a.`STATUS` = 'Withdrawn'  WHERE a.`ID` = '".$request->pcID."' ");
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
        public function getClarificationByID($class,$id,$frmname){


            if($class === 'REQUESTFORPAYMENT'){
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
            $currencyType = DB::select("SELECT CurrencyName FROM accounting.`currencysetup`");
            $expenseType = DB::select("SELECT type FROM accounting.`expense_type_setup`");
            // Query recipient in clarification
            $queRecipient = DB::select("SELECT IFNULL((SELECT a.`CurrentReceiver` AS 'recipient' FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'requestforpayment' AND a.`STATUS` = 'for clarification'),FALSE) AS recipient");
            $recipient = $queRecipient[0]->recipient;
            // Query Liquidation Table if edit
            $queLiquidatedT = DB::select("SELECT * FROM accounting.`rfp_liquidation` a WHERE a.`RFPID` = $id");
            $filesAttached = DB::select("SELECT * FROM general.`attachments` a WHERE a.`REQID` = $id");
            return view('MyWorkflow.clarification-byid.cla-post', compact('post','postDetails','payeeDetails','initCheck','initName','editableChecker','mgrs','mgrsId','projects','currencyType','recipient','queLiquidatedT','expenseType','filesAttached'));
         
            }


            if($class === 'REIMBURSEMENT_REQUEST'){
                $post = DB::table('accounting.reimbursement_request')->where('ID',$id)->first();
                $expenseDetails = DB::select("SELECT * FROM accounting.`reimbursement_expense_details` a WHERE a.`REID` = $id");
                $transpoDetails = DB::select("SELECT * FROM accounting.`reimbursement_request_details` a WHERE a.`REID` = $id");
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`reimbursement_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Reimbursement Request' AND a.`REQID` =$id");

                $subqMgrsID = DB::select("SELECT (SELECT RMID FROM general.`systemreportingmanager` b WHERE b.RMName = a.`REPORTING_MANAGER` LIMIT 1) AS 'subRmid' FROM accounting.`reimbursement_request` a WHERE a.`ID` = $id ");
                $mgrsId = $subqMgrsID[0]->subRmid;

                $mgrs = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");

                $projects = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE project_type <> 'MAIN OFFICE' AND `status` = 'Active' AND title_id = 1 ORDER BY project_name");
                $expenseType = DB::select("SELECT type FROM accounting.`expense_type_setup`");
                $transpoSetup = DB::select("SELECT MODE FROM accounting.`transpo_setup`");

                $replyEditChecker = DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`FRM_CLASS` = 'reimbursement_request' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`PROCESSID` = $id AND a.`ORDERS` < 3 AND a.`STATUS` = 'For Clarification'), FALSE) AS replyChecker;");
                $replyEditChecker = $replyEditChecker[0]->replyChecker;

                $initChecker = DB::select("SELECT IFNULL((SELECT a.`INITID` FROM general.`actual_sign` a WHERE a.`FRM_CLASS` = 'reimbursement_request' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`PROCESSID` = $id AND a.`ORDERS` < 3 AND a.`INITID` = '".session('LoggedUser')."' AND a.`STATUS` = 'For Clarification'), FALSE) AS initCheck;");
                $initChecker = $initChecker[0]->initCheck;

                $recipientCheck = DB::select("SELECT IFNULL((SELECT a.`CurrentReceiver` FROM general.`actual_sign` a WHERE a.`FRM_CLASS` = 'reimbursement_request' AND a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification' AND a.`CurrentReceiver` = '".session('LoggedUser')."' ), FALSE) AS recipientCheck;");
                $recipientCheck = $recipientCheck[0]->recipientCheck;

                return view('MyWorkflow.clarification-byid.cla-re', compact('post','initName','expenseDetails','transpoDetails','attachmentsDetails','mgrs','mgrsId','projects','expenseType','transpoSetup','replyEditChecker','initChecker','recipientCheck'));
            }

        
            if($class === 'PETTYCASHREQUEST'){
                $post = DB::table('accounting.petty_cash_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`petty_cash_expense_details` a WHERE a.`PCID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`petty_cash_request_details` a WHERE a.`PCID` = $id");
                // // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`petty_cash_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     
               
                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Petty Cash Request' AND a.`REQID` =$id");
                
                $projects = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE project_type <> 'MAIN OFFICE' AND `status` = 'Active' AND title_id = 1 ORDER BY project_name");

                $mgrs = DB::select("SELECT RMID, RMName FROM general.`systemreportingmanager` WHERE UID = '" . session('LoggedUser') . "' ORDER BY RMName");

                $subqMgrsID = DB::select("SELECT (SELECT RMID FROM general.`systemreportingmanager` b WHERE b.RMName = a.`REPORTING_MANAGER` LIMIT 1) AS 'subRmid' FROM accounting.`petty_cash_request` a WHERE a.`ID` = $id ");
                $mgrsId = $subqMgrsID[0]->subRmid;

                $initRecipientCheck = DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For CLarification' AND a.`CurrentReceiver` = '".session('LoggedUser')."' AND a.`INITID` = '".session('LoggedUser')."'  ), FALSE) AS initRecipientCheck;");
                $initRecipientCheck = $initRecipientCheck[0]->initRecipientCheck;

                $recipientCheck = DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For CLarification' AND a.`CurrentReceiver` = '".session('LoggedUser')."' ), FALSE) AS recipientCheck;");
                $recipientCheck = $recipientCheck[0]->recipientCheck;

                $senderCheck = DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For CLarification' AND a.`CurrentSender` = '".session('LoggedUser')."' ), FALSE) AS senderCheck;");
                $senderCheck = $senderCheck[0]->senderCheck;

                $tableCheck = DB::select("SELECT IFNULL(( SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`ORDERS` < 2 AND a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification' ), FALSE) AS tableCheck;");

                $expenseType = DB::select("SELECT type FROM accounting.`expense_type_setup`");

                $transpoSetup = DB::select("SELECT MODE FROM accounting.`transpo_setup`");

                return view('MyWorkflow.clarification-byid.cla-pc', compact('post','initName','attachmentsDetails','expenseDetails','transpoDetails','tableCheck','mgrsId','mgrs','projects','initRecipientCheck','recipientCheck','senderCheck','expenseType','transpoSetup'));
            }


            if($class === 'SALES_ORDER_FRM'){


                if ($frmname === 'Sales Order - Project') {

                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Project' AND a.`REQID` =$id");
                    $businesslist = DB::select("SELECT * FROM general.`business_list` a WHERE a.`status` LIKE 'Active%' AND a.`title_id` = 1 AND a.`Type` = 'CLIENT' ORDER BY a.`business_fullname` ASC");
                    $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a ORDER BY a.`id` DESC");
                    $documentlist = DB::select("SELECT * FROM sales_order.`documentlist` a ORDER BY a.`ID` DESC" );
                    $checkInit = DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`INITID` = '".session('LoggedUser')."' AND a.`STATUS` = 'For Clarification'), FALSE) AS checker");
                    $checkOrder = DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`STATUS` = 'For Clarification' AND a.`ORDERS` = 0 AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ), FALSE) AS checker");
                    $systemNameChecked = DB::select("SELECT IFNULL((SELECT 'True' FROM sales_order.`sales_order_system` b WHERE b.sysID = a.id AND b.soid = $id), 'False') AS 'ID', type_name, a.`id` AS 'sysID' FROM sales_order.`systems_type` a");
                    $documentNameChecked = DB::select("SELECT IFNULL((SELECT 'True' FROM sales_order.`sales_order_docs` b WHERE b.DocID = a.ID AND b.soid = 1590), 'False') AS 'ID', DocumentName, a.`ID` AS 'DocID' FROM sales_order.`documentlist` a");
                    return view('MyWorkflow.clarification-byid.cla-sof-prj', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','businesslist','systemName','documentlist','checkInit','checkOrder','systemNameChecked','documentNameChecked'));

                } 

                if ($frmname === 'Sales Order - Delivery') {

                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Delivery' AND a.`REQID` =$id");
                    $businesslist = DB::select("SELECT * FROM general.`business_list` a WHERE a.`status` LIKE 'Active%' AND a.`title_id` = 1 AND a.`Type` = 'CLIENT' ORDER BY a.`business_fullname` ASC");
                    $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a ORDER BY a.`id` DESC");
                    $documentlist = DB::select("SELECT * FROM sales_order.`documentlist` a ORDER BY a.`ID` DESC" );
                    $checkInit = DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`INITID` = '".session('LoggedUser')."' AND a.`STATUS` = 'For Clarification'), FALSE) AS checker");
                    $checkOrder = DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`STATUS` = 'For Clarification' AND a.`ORDERS` = 0 AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ), FALSE) AS checker");
                    $systemNameChecked = DB::select("SELECT IFNULL((SELECT 'True' FROM sales_order.`sales_order_system` b WHERE b.sysID = a.id AND b.soid = $id), 'False') AS 'ID', type_name, a.`id` AS 'sysID' FROM sales_order.`systems_type` a");
                    $documentNameChecked = DB::select("SELECT IFNULL((SELECT 'True' FROM sales_order.`sales_order_docs` b WHERE b.DocID = a.ID AND b.soid = 1590), 'False') AS 'ID', DocumentName, a.`ID` AS 'DocID' FROM sales_order.`documentlist` a");
 
                    return view('MyWorkflow.clarification-byid.cla-sof-dlv', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','businesslist','systemName','documentlist','checkInit','checkOrder','systemNameChecked','documentNameChecked'));

                } 

                if ($frmname === 'Sales Order - Demo') {

                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Demo' AND a.`REQID` =$id");
                    $businesslist = DB::select("SELECT * FROM general.`business_list` a WHERE a.`status` LIKE 'Active%' AND a.`title_id` = 1 AND a.`Type` = 'CLIENT' ORDER BY a.`business_fullname` ASC");
                    $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a ORDER BY a.`id` DESC");
                    $documentlist = DB::select("SELECT * FROM sales_order.`documentlist` a ORDER BY a.`ID` DESC" );
                    $checkInit = DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`INITID` = '".session('LoggedUser')."' AND a.`STATUS` = 'For Clarification'), FALSE) AS checker");
                    $checkOrder = DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`STATUS` = 'For Clarification' AND a.`ORDERS` = 0 AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ), FALSE) AS checker");
                    $systemNameChecked = DB::select("SELECT IFNULL((SELECT 'True' FROM sales_order.`sales_order_system` b WHERE b.sysID = a.id AND b.soid = $id), 'False') AS 'ID', type_name, a.`id` AS 'sysID' FROM sales_order.`systems_type` a");
                    $documentNameChecked = DB::select("SELECT IFNULL((SELECT 'True' FROM sales_order.`sales_order_docs` b WHERE b.DocID = a.ID AND b.soid = 1590), 'False') AS 'ID', DocumentName, a.`ID` AS 'DocID' FROM sales_order.`documentlist` a");
 
                    return view('MyWorkflow.clarification-byid.cla-sof-dmo', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','businesslist','systemName','documentlist','checkInit','checkOrder','systemNameChecked','documentNameChecked'));

                } 

                if ($frmname === 'Sales Order - POC') {

                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - POC' AND a.`REQID` =$id");
                    $businesslist = DB::select("SELECT * FROM general.`business_list` a WHERE a.`status` LIKE 'Active%' AND a.`title_id` = 1 AND a.`Type` = 'CLIENT' ORDER BY a.`business_fullname` ASC");
                    $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a ORDER BY a.`id` DESC");
                    $documentlist = DB::select("SELECT * FROM sales_order.`documentlist` a ORDER BY a.`ID` DESC" );
                    $checkInit = DB::select("SELECT IFNULL((SELECT a.`STATUS` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`INITID` = '".session('LoggedUser')."' AND a.`STATUS` = 'For Clarification'), FALSE) AS checker");
                    $checkOrder = DB::select("SELECT IFNULL((SELECT a.`ID` FROM general.`actual_sign` a WHERE a.`PROCESSID` = $id AND a.`STATUS` = 'For Clarification' AND a.`ORDERS` = 0 AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ), FALSE) AS checker");
                    $systemNameChecked = DB::select("SELECT IFNULL((SELECT 'True' FROM sales_order.`sales_order_system` b WHERE b.sysID = a.id AND b.soid = $id), 'False') AS 'ID', type_name, a.`id` AS 'sysID' FROM sales_order.`systems_type` a");
                    $documentNameChecked = DB::select("SELECT IFNULL((SELECT 'True' FROM sales_order.`sales_order_docs` b WHERE b.DocID = a.ID AND b.soid = 1590), 'False') AS 'ID', DocumentName, a.`ID` AS 'DocID' FROM sales_order.`documentlist` a");

                    return view('MyWorkflow.clarification-byid.cla-sof-poc', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject','businesslist','systemName','documentlist','checkInit','checkOrder','systemNameChecked','documentNameChecked'));

                } 
           
            }

     





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


                        $image = base64_encode(file_get_contents($file));

                
                        DB::table('repository.rfp')->insert([
                            'REFID' => $request->idName, 
                            'FileName' => $completeFileName,
                            'IMG' => $image,
                            'UID' => session('LoggedUser'),
                            'Ext' => $extension
                        ]);




                        
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


                    $image = base64_encode(file_get_contents($file));

                
                    DB::table('repository.rfp')->insert([
                        'REFID' => $request->idName, 
                        'FileName' => $completeFileName,
                        'IMG' => $image,
                        'UID' => session('LoggedUser'),
                        'Ext' => $extension
                    ]);







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

            public function claPCReplyInit(Request $request){
// doge

                $mainID = DB::select("SELECT IFNULL(( SELECT a.`Main_office_id` FROM general.`setup_project` a WHERE a.`project_id` = '".$request->projectID."' LIMIT 1 ), FALSE) AS mainID;");
                
                if (!empty($request->xdData) == true || !empty($request->tdData) == true) {


                    $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`FRM_NAME` = 'Petty Cash Request' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC");
               
                    $nParentId= $notif[0]->ID;
                    $nReceiverId= $notif[0]->SENDERID;
                    $nActualId= $notif[0]->ACTUALID;
    
                    DB::table('general.notifications')->insert([
                        'ParentID' =>$nParentId,
                        'levels'=>'0',
                        'FRM_NAME' =>'Petty Cash Request',
                        'PROCESSID' =>$request->pcID,
                        'SENDERID' =>session('LoggedUser'),
                        'RECEIVERID' =>$nReceiverId,
                        'MESSAGE' =>$request->replyRemarks,
                        'TS' =>NOW(),
                        'SETTLED' => 'YES',
                        'ACTUALID' => $nActualId,
                        'SENDTOACTUALID' =>'0',
                        'UserFullName' =>session('LoggedUser_FullName'),
    
                       ]);
    
                       DB::update("UPDATE accounting.`petty_cash_request` a SET a.`STATUS` = 'In Progress', a.`TS` = NOW() WHERE a.`ID` = '".$request->pcID."' ");
           
                       // For clarification to in progress
                       DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
                       WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`FRM_NAME` = 'Petty Cash Request' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");

              
                    if(!empty($request->xdData) == true){
                      
                            DB::table('accounting.petty_cash_expense_details')->where('PCID', $request->pcID)->delete();

                            $expenseDetails = $request->xdData;
                            $expenseDetails =json_decode($expenseDetails,true);

                            $xdArray = $expenseDetails;

                            for($i = 0; $i <count($xdArray); $i++) {
                                $setXDArray[] = [
                                    'PCID' => $request->pcID,
                                    'payee_id'=>'0',
                                    'PAYEE' => $request->payeeName,
                                    'CLIENT_NAME' => $request->clientName, 
                                    'TITLEID'=>session('LoggedUser_CompanyID'),
                                    'PRJID' =>'0',
                                    // 'PROJECT' =>,
                                    'DESCRIPTION' => $xdArray[$i][2],
                                    'AMOUNT' => $xdArray[$i][3],
                                    'GUID'=>$request->guid,
                                    'TS' => now(),
                                    'MAINID' => $mainID[0]->mainID,
                                    'STATUS' =>'ACTIVE',
                                    'CLIENT_ID' =>$request->clientID, 
                                    'EXPENSE_TYPE'=> $xdArray[$i][1],
                                    'DEPT'=> session('LoggedUser_DepartmentName'),
                                    'RELEASEDCASH'=> '0',
                                    'date_'=> $xdArray[$i][0],
                                    'ISLIQUIDATED' => '0'
                                ];
                            }
            
                            DB::table('accounting.petty_cash_expense_details')->insert($setXDArray);


                    }

                    if(!empty($request->tdData) == true){

                            DB::table('accounting.petty_cash_request_details')->where('PCID', $request->pcID)->delete();

                            $transpoDetails = $request->tdData;
                            $transpoDetails =json_decode($transpoDetails,true);
                            $tdArray = $transpoDetails;

                            if(!empty($tdArray) == true){
                                // return "tdarray true";
                                // $tdArray = [];
                                for($i = 0; $i <count($tdArray); $i++) {
                                    $setTDArray[] = [
                
                                        'PCID' => $request->pcID,
                                        'PRJID'=> '0',
                                        'payee_id' => '0',
                                        'PAYEE' => $request->payeeName, 
                                        'CLIENT_NAME'=> $request->clientName,
                                        'DESTINATION_FRM' => $tdArray[$i][1],
                                        'DESTINATION_TO' => $tdArray[$i][2],
                                        'DESCRIPTION' => $tdArray[$i][4],
                                        'AMT_SPENT' => $tdArray[$i][5],
                                        'TITLEID'=> session('LoggedUser_CompanyID'),
                                        'MOT' => $tdArray[$i][3],
                                        'PROJECT' => '',
                                        'GUID' =>$request->guid,
                                        'TS' =>now(),
                                        'MAINID'=> $mainID[0]->mainID,
                                        'STATUS'=> 'ACTIVE',
                                        'CLIENT_ID'=> $request->clientID,
                                        'DEPT'=> session('LoggedUser_DepartmentName'),
                                        'RELEASEDCASH'=> '0',
                                        'date_'=> $tdArray[$i][0],
                                        'ISLIQUIDATED' => '0'
                                    ];
                                }
                                DB::table('accounting.petty_cash_request_details')->insert($setTDArray);
                            }
                    }

                   
                    // Delete attachments
                    $toDeleteFile = $request->deleteAttached;
                    $toDeleteFile =json_decode($toDeleteFile,true);
                    
            
                    if(isset($toDeleteFile)) {
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
                            // $ref = $request->referenceNumber;
                            $ref = str_replace('-', '_', $request->referenceNumber);
                            // For moving the file
                            $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/PC/".$ref;
                            // For preview
                            $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/PC/".$ref;
                            $symPath ="public/Attachments/PC";
                            $file->storeAs($destinationPath, $completeFileName);
                            $fileDestination = $storagePath.'/'.$completeFileName;
                            
                            $image = base64_encode(file_get_contents($file));
                
                            DB::table('repository.petty_cash')->insert([
                                'REFID' => $request->pcID,
                                'FileName' => $completeFileName,
                                'IMG' => $image,
                                'UID' => session('LoggedUser'),
                                'Ext' => $extension
                            ]);


                            $insert_doc = DB::table('general.attachments')->insert([
                                'INITID' => session('LoggedUser'),
                                'REQID' => $request->pcID, 
                                'filename' => $completeFileName,
                                'filepath' => $storagePath, 
                                'fileExtension' => $extension,
                                'newFilename' => $newFileName,
                                'fileDestination'=>$destinationPath,
                                'formName' => 'Petty Cash Request',
                                'created_at' => date('Y-m-d H:i:s')
                        
                            ]);
                        }
                    } 

                return back()->with('form_submitted', 'Your request is now In Progress.');               
                } else {
                return back()->with('form_error', 'Request Failed, Please provide records!');
                }



            }


            public function claPCReplyApprvr (Request $request){
                $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`FRM_NAME` = 'Petty Cash Request' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
              
                if($notif == True){

                    $nParentId= $notif[0]->ID;
                    $nReceiverId= $notif[0]->SENDERID;
                    $nActualId= $notif[0]->ACTUALID;

                   DB::table('general.notifications')->insert([

                    'ParentID' =>$nParentId,
                    'levels'=>'0',
                    'FRM_NAME' =>'Petty Cash Request',
                    'PROCESSID' =>$request->pcID,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$nReceiverId,
                    'MESSAGE' =>$request->replyRemarks,
                    'TS' =>NOW(),
                    'SETTLED' => 'YES',
                    'ACTUALID' => $nActualId,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName'),

                   ]);
                   

                    // Clarity Edit
                    DB::update("UPDATE accounting.`petty_cash_request` a SET a.`STATUS` = 'In Progress', a.`TS` = NOW() WHERE a.`ID` = '".$request->pcID."' ");

                    // For clarification to in progress
                    DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
                    WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`FRM_NAME` = 'Petty Cash Request' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");
    


                return back()->with('form_submitted', 'Your request is now In Progress.');
                } else {
                return back()->with('form_submitteds', 'Reply Error');
                }
            }




            public function claPCReply(Request $request){
                // shiba



                // dd(
                //     $request->reportingManager . " => RMID",
                //     $request->projectName. " => PRHID" ,
                //     $request->guid . " => GUID",
                //     $request->pcID. " => pcID",
                //     $request->mainID. " => mainID",
                //     $request->clientID. " => clientID",
                //     $request->clientName. " => clientName",
                //     $request->payeeName. " => payeeName",
                //     $request->dateNeeded. " => dateNeeded",
                //     $request->amount. " => amount",
                //     $request->purpose. " => purpose",
                //     $request->file. " => file",
                //     $request->deleteAttached. " => deleteAttached"
                // );



                $request->validate([
                    'reportingManager'=>'required',
                    'projectName'=>'required',
                    'dateNeeded'=>'required',
                    'payeeName'=>'required',
                    'amount' => 'required|numeric|between:1,1000',
                    'purpose'=>'required'
                ]);
              

                $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`FRM_NAME` = 'Petty Cash Request' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC ");
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
                    'FRM_NAME' =>'Petty Cash Request',
                    'PROCESSID' =>$request->pcID,
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
                    AS 'RMName' FROM general.`actual_sign` a WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`COMPID` = '1' AND a.`FRM_CLASS` = 'PETTYCASHREQUEST' LIMIT 1");
                    $rMName = $queRMName[0]->RMName;


                    // Clarity Edit
                    DB::update("UPDATE accounting.`petty_cash_request` a SET a.`STATUS` = 'In Progress', a.`REPORTING_MANAGER` = '".$rMName."', a.`DEADLINE` = '".$request->dateNeeded."', 
                    a.`REQUESTED_AMT` = '".$request->amount."', a.`TS` = NOW()   
                    WHERE a.`ID` = '".$request->pcID."' ");

                    // For clarification to in progress
                    DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
                    WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`FRM_NAME` = 'Petty Cash Request' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");
    
                    // Update form in actual sign
                    DB::update("UPDATE general.`actual_sign` a SET a.`REMARKS` = '".$request->purpose."', a.`TS` = NOW(), a.`DUEDATE` = '".$request->dateNeeded."', a.`PODATE` = '".$request->dateNeeded."',
                    a.`DATE` = '".$request->dateNeeded."', a.`RM_ID` = '".$request->reportingManager."', a.`REPORTING_MANAGER` = '".$rMName."', a.`PROJECTID` = '".$request->projectName."', a.`PROJECT` = '".$project_name."', a.`CLIENTID` = '".$request->clientID."', a.`CLIENTNAME` = '".$request->clientName."',
                    a.`Payee` = '".$request->payeeName."', a.`Amount` = '".$request->amount."'
                    WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`FRM_NAME` = 'Petty Cash Request' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ");

                    
                    $toDeleteFile = $request->deleteAttached;
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
                            // $ref = $request->referenceNumber;
                            $ref = str_replace('-', '_', $request->referenceNumber);
                            // For moving the file
                            $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/PC/".$ref;
                            // For preview
                            $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/PC/".$ref;
                            $symPath ="public/Attachments/PC";
                            $file->storeAs($destinationPath, $completeFileName);
                            $fileDestination = $storagePath.'/'.$completeFileName; 
                            
                            $image = base64_encode(file_get_contents($file));
                
                            DB::table('repository.petty_cash')->insert([
                                'REFID' => $request->pcID,
                                'FileName' => $completeFileName,
                                'IMG' => $image,
                                'UID' => session('LoggedUser'),
                                'Ext' => $extension
                            ]);


                            $insert_doc = DB::table('general.attachments')->insert([
                                'INITID' => session('LoggedUser'),
                                'REQID' => $request->pcID, 
                                'filename' => $completeFileName,
                                'filepath' => $storagePath, 
                                'fileExtension' => $extension,
                                'newFilename' => $newFileName,
                                'fileDestination'=>$destinationPath,
                                'formName' => 'Petty Cash Request',
                                'created_at' => date('Y-m-d H:i:s')
                       
                            ]);
                        }
                    } 

                



                return back()->with('form_submitted', 'Your request is now In Progress.');
                } else {
                return back()->with('form_submitteds', 'Reply Error');
                }





                
            }




            // Withdraw PC
            public function claPCWithdraw(Request $request){
            DB::update(" UPDATE general.`actual_sign` AS a SET a.`STATUS` = 'Withdrawn',
            a.`SIGNDATETIME` = NOW(),
            a.`ApprovedRemarks` = '" .$request->withdrawRemarks. 
            "' 
            WHERE a.`PROCESSID` = '".$request->pcID."' AND a.`FRM_CLASS` = 'PETTYCASHREQUEST' AND a.`STATUS` = 'For Clarification' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' ;");
            DB::update("UPDATE accounting.`petty_cash_request` a SET a.`STATUS` = 'Withdrawn'  WHERE a.`ID` = '".$request->pcID."' ");
            return back()->with('form_submitted', 'Your request is now Withdrawn.');
        }



         
            public function claREReply(Request $request){


                $request->validate([
                    'reportingManager'=>'required',
                    'projectName'=>'required',
                    'dateNeeded'=>'required',
                    'payeeName'=>'required',
                    'amount'=>'required',
                    'purpose'=>'required'
                ]);


                if (!empty($request->xdData) == true || !empty($request->tdData) == true) {


                    $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->reID."' AND a.`FRM_NAME` = 'Reimbursement Request' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC");
               
                    $nParentId= $notif[0]->ID;
                    $nReceiverId= $notif[0]->SENDERID;
                    $nActualId= $notif[0]->ACTUALID;
    
                    DB::table('general.notifications')->insert([
                        'ParentID' =>$nParentId,
                        'levels'=>'0',
                        'FRM_NAME' =>'Reimbursement Request',
                        'PROCESSID' =>$request->reID,
                        'SENDERID' =>session('LoggedUser'),
                        'RECEIVERID' =>$nReceiverId,
                        'MESSAGE' =>$request->replyRemarks,
                        'TS' =>NOW(),
                        'SETTLED' => 'YES',
                        'ACTUALID' => $nActualId,
                        'SENDTOACTUALID' =>'0',
                        'UserFullName' =>session('LoggedUser_FullName'),
    
                       ]);
    
                       DB::update("UPDATE accounting.`reimbursement_request` a SET a.`STATUS` = 'In Progress', a.`TS` = NOW()   
                       WHERE a.`ID` = '".$request->reID."' ");
           
                       // For clarification to in progress
                       DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
                       WHERE a.`PROCESSID` = '".$request->reID."' AND a.`FRM_NAME` = 'Reimbursement Request' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");

                  
                    // Change Date Format
                    $project_name = DB::select("SELECT project_name FROM general.`setup_project` WHERE `project_id` = '" . $request->projectName . "'");
                    $project_name =  $project_name[0]->project_name;            
                    $dateNeeded = date_create($request->dateNeeded);


                    // Insert in re main
                    DB::update("UPDATE accounting.`reimbursement_request` a 
                        SET 
                        a.`REPORTING_MANAGER` = '".$request->RMName."',
                        a.`PAYEE` = '".$request->payeeName."',
                        a.`TRANS_DATE` = '".date_format($dateNeeded, 'Y-m-d')."',
                        a.`DEADLINE` = '".date_format($dateNeeded, 'Y-m-d')."',
                        a.`AMT_DUE_FRM_EMP` = '".$request->amount."',
                        a.`TOTAL_AMT_SPENT` = '".$request->amount."',
                        a.`DESCRIPTION` = '".$request->purpose."',
                        a.`PROJECT` = '".$project_name."',
                        a.`PRJID` = '".$request->projectName."',
                        a.`TS` = NOW(),
                        a.`CLIENT_NAME` = '".$request->clientName."',
                        a.`CLIENTID` = '".$request->clientID."'
                    WHERE a.`id` = '".$request->reID."' AND a.`TITLEID` = '".session('LoggedUser_CompanyID')."' ");
                
              
                    if(!empty($request->xdData) == true){
                      
                            DB::table('accounting.reimbursement_expense_details')->where('REID', $request->reID)->delete();

                            $expenseDetails = $request->xdData;
                            $expenseDetails =json_decode($expenseDetails,true);

                            $xdArray = $expenseDetails;

                            for($i = 0; $i <count($xdArray); $i++) {
                                $setXDArray[] = [

                                    'REID' => $request->reID,
                                    'payee_id'=>'0',
                                    'PAYEE' => $request->payeeName,
                                    'CLIENT_NAME' => $request->clientName, 
                                    'TITLEID'=>session('LoggedUser_CompanyID'),
                                    'PRJID' =>$request->projectName,
                                    'PROJECT' =>$project_name,
                                    'DESCRIPTION' => $xdArray[$i][2],
                                    'AMOUNT' => $xdArray[$i][3],
                                    'GUID'=>$request->guid,
                                    'TS' => now(),
                                    'MAINID' => $request->mainID,
                                    'STATUS' =>'ACTIVE',
                                    'CLIENT_ID' =>$request->clientID, 
                                    'EXPENSE_TYPE'=> $xdArray[$i][1],
                                    'DEPT'=> session('LoggedUser_DepartmentName'),
                                    'RELEASEDCASH'=> '0',
                                    'date_'=> $xdArray[$i][0]

                                ];
                            }
            
                            DB::table('accounting.reimbursement_expense_details')->insert($setXDArray);


                    }

                    if(!empty($request->tdData) == true){

                            DB::table('accounting.reimbursement_request_details')->where('REID', $request->reID)->delete();

                            $transpoDetails = $request->tdData;
                            $transpoDetails =json_decode($transpoDetails,true);
                            $tdArray = $transpoDetails;

                            if(!empty($tdArray) == true){

                                for($i = 0; $i <count($tdArray); $i++) {
                                    $setTDArray[] = [
                
                                        'REID' => $request->reID,
                                        'PRJID'=> $request->projectName,
                                        'payee_id' => '0',
                                        'PAYEE' => $request->payeeName, 
                                        'CLIENT_NAME'=> $request->clientName,
                                        'DESTINATION_FRM' => $tdArray[$i][1],
                                        'DESTINATION_TO' => $tdArray[$i][2],
                                        'DESCRIPTION' => $tdArray[$i][4],
                                        'AMT_SPENT' => $tdArray[$i][5],
                                        'TITLEID'=> session('LoggedUser_CompanyID'),
                                        'MOT' => $tdArray[$i][3],
                                        'PROJECT' => $project_name,
                                        'GUID' =>$request->guid,
                                        'TS' =>now(),
                                        'MAINID'=> $request->mainID,
                                        'STATUS'=> 'ACTIVE',
                                        'CLIENT_ID'=> $request->clientID,
                                        'DEPT'=> session('LoggedUser_DepartmentName'),
                                        'RELEASEDCASH'=> '0',
                                        'date_'=> $tdArray[$i][0]

                                    ];
                                }
                                DB::table('accounting.reimbursement_request_details')->insert($setTDArray);
                            }
                    }


                   
                    // Delete attachments
                    $toDeleteFile = $request->deleteAttached;
                    $toDeleteFile =json_decode($toDeleteFile,true);
                    
            
                    if(isset($toDeleteFile)) {
                    for($i = 0; $i <count($toDeleteFile); $i++) {
                    $idAttachment = $toDeleteFile[$i]['0'];
                    $pathAttachment = $toDeleteFile[$i]['1'];
                    $fileNameAttachment = $toDeleteFile[$i]['2'];

                    $public_path = public_path($pathAttachment.'/'.$fileNameAttachment);
                    unlink($public_path);

                    DB::table('general.attachments')->where('id', $idAttachment)->delete();
                    }
                    }

                    if($request->hasFile('file')){
                        foreach($request->file as $file) {
                            $completeFileName = $file->getClientOriginalName();
                            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                            $extension = $file->getClientOriginalExtension();
                            $randomized = rand();
                            $newFileName = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                            // $path = '/uploads/attachments/'.$GUID;
                            // $ref = $request->referenceNumber;
                            $ref = str_replace('-', '_', $request->reID);
                            // For moving the file
                            $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/RE/".$ref;
                            // For preview
                            $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/RE/".$ref;
                            $symPath ="public/Attachments/RFP";
                            $file->storeAs($destinationPath, $completeFileName);
                            $fileDestination = $storagePath.'/'.$completeFileName;
                            
                            $image = base64_encode(file_get_contents($file));

                
                            DB::table('repository.reimbursement')->insert([
                                'REFID' => $request->reID,
                                'FileName' => $completeFileName,
                                'IMG' => $image,
                                'UID' => session('LoggedUser'),
                                'Ext' => $extension
                            ]);


                            $insert_doc = DB::table('general.attachments')->insert([
                                'INITID' => session('LoggedUser'),
                                'REQID' => $request->reID, 
                                'filename' => $completeFileName,
                                'filepath' => $storagePath, 
                                'fileExtension' => $extension,
                                'newFilename' => $newFileName,
                                'fileDestination'=>$destinationPath,
                                'formName' => 'Reimbursement Request',
                                'created_at' => date('Y-m-d H:i:s')
                       
                            ]);
                        }
                    } 

                return back()->with('form_submitted', 'Your request is now In Progress.');               
                } else {
                return back()->with('form_error', 'Request Failed, Please provide records!');
                }
    
            }

            public function claREapproved(Request $request){

                $notif = DB::select("SELECT * FROM general.`notifications` a WHERE a.`PROCESSID` = '".$request->reID."' AND a.`FRM_NAME` = 'Reimbursement Request' AND a.`SETTLED` = 'NO' ORDER BY a.`ID` DESC");
               
                $nParentId= $notif[0]->ID;
                $nReceiverId= $notif[0]->SENDERID;
                $nActualId= $notif[0]->ACTUALID;

                DB::table('general.notifications')->insert([
                    'ParentID' =>$nParentId,
                    'levels'=>'0',
                    'FRM_NAME' =>'Reimbursement Request',
                    'PROCESSID' =>$request->reID,
                    'SENDERID' =>session('LoggedUser'),
                    'RECEIVERID' =>$nReceiverId,
                    'MESSAGE' =>$request->approvedRemarks,
                    'TS' =>NOW(),
                    'SETTLED' => 'YES',
                    'ACTUALID' => $nActualId,
                    'SENDTOACTUALID' =>'0',
                    'UserFullName' =>session('LoggedUser_FullName'),

                   ]);

                   DB::update("UPDATE accounting.`reimbursement_request` a SET a.`STATUS` = 'In Progress', a.`TS` = NOW()   
                   WHERE a.`ID` = '".$request->reID."' ");
       
                   // For clarification to in progress
                   DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress', a.`CurrentSender` = '0', a.`CurrentReceiver` = '0', a.`NOTIFICATIONID` = '0' 
                   WHERE a.`PROCESSID` = '".$request->reID."' AND a.`FRM_NAME` = 'Reimbursement Request' AND a.`COMPID` = '".session('LoggedUser_CompanyID')."' AND a.`STATUS` = 'For Clarification'");

                return back()->with('form_submitted', 'Your request is now In Progress.');               

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
        public function getApprovedByID($class,$id,$frmname){


            if($class === 'REQUESTFORPAYMENT'){
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



            if($class === 'REIMBURSEMENT_REQUEST'){

                $post = DB::table('accounting.reimbursement_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`reimbursement_expense_details` a WHERE a.`REID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`reimbursement_request_details` a WHERE a.`REID` = $id");
                // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`reimbursement_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Reimbursement Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.approved-byid.appd-re', compact('post','initName','expenseDetails','transpoDetails','attachmentsDetails'));
            }

            if($class === 'PETTYCASHREQUEST'){
                $post = DB::table('accounting.petty_cash_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`petty_cash_expense_details` a WHERE a.`PCID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`petty_cash_request_details` a WHERE a.`PCID` = $id");
                // // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`petty_cash_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Petty Cash Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.approved-byid.appd-pc', compact('post','initName','attachmentsDetails','expenseDetails','transpoDetails'));
            }

       

            if($class === 'SALES_ORDER_FRM'){
                
                if ($frmname === 'Sales Order - Project') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Project' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.approved-byid.appd-sof-prj', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));

                } 

                if ($frmname === 'Sales Order - Delivery') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Delivery' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.approved-byid.appd-sof-dlv', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - Demo') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Demo' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.approved-byid.appd-sof-dmo', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - POC') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - POC ' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.approved-byid.appd-sof-poc', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 


            }

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
        public function getWithdrawByID($class,$id,$frmname){


            if($class === 'REQUESTFORPAYMENT'){
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

            if($class === 'REIMBURSEMENT_REQUEST'){
                $post = DB::table('accounting.reimbursement_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`reimbursement_expense_details` a WHERE a.`REID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`reimbursement_request_details` a WHERE a.`REID` = $id");
                // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`reimbursement_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Reimbursement Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.withdrawn-byid.wit-re', compact('post','initName','expenseDetails','transpoDetails','attachmentsDetails'));
            }


            if($class === 'PETTYCASHREQUEST'){
                $post = DB::table('accounting.petty_cash_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`petty_cash_expense_details` a WHERE a.`PCID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`petty_cash_request_details` a WHERE a.`PCID` = $id");
                // // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`petty_cash_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Petty Cash Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.withdrawn-byid.wit-pc', compact('post','initName','attachmentsDetails','expenseDetails','transpoDetails'));
            }


            if($class === 'SALES_ORDER_FRM'){
                
                if ($frmname === 'Sales Order - Project') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Project' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.withdrawn-byid.wit-sof-prj', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));

                } 

                if ($frmname === 'Sales Order - Delivery') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Delivery' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.withdrawn-byid.wit-sof-dlv', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - Demo') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Demo' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.withdrawn-byid.wit-sof-dmo', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - POC') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - POC' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.withdrawn-byid.wit-sof-poc', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

            }




















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
        public function getrejectedByID($class,$id,$frmname){

            if($class === 'REQUESTFORPAYMENT'){
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
            if($class === 'REIMBURSEMENT_REQUEST'){
                $post = DB::table('accounting.reimbursement_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`reimbursement_expense_details` a WHERE a.`REID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`reimbursement_request_details` a WHERE a.`REID` = $id");
                // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`reimbursement_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Reimbursement Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.rejected-byid.rej-re', compact('post','initName','expenseDetails','transpoDetails','attachmentsDetails'));
            }

            if($class === 'PETTYCASHREQUEST'){
                $post = DB::table('accounting.petty_cash_request')->where('ID',$id)->first();
                // Expense Details
                $expenseDetails = DB::select("SELECT * FROM accounting.`petty_cash_expense_details` a WHERE a.`PCID` = $id");
                // Transportation Details
                $transpoDetails = DB::select("SELECT * FROM accounting.`petty_cash_request_details` a WHERE a.`PCID` = $id");
                // // Initiator Name
                $queinitName = DB::select("SELECT a.`UID`,(SELECT UserFull_name FROM general.`users` usr WHERE usr.id = a.`UID`) AS 'NAME' FROM accounting.`petty_cash_request` a WHERE a.`ID` = $id");
                $initName  = $queinitName[0]->NAME;     

                // Attachments
                $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Petty Cash Request' AND a.`REQID` =$id");
                
                return view('MyWorkflow.rejected-byid.rej-pc', compact('post','initName','attachmentsDetails','expenseDetails','transpoDetails'));
            }


            // if($class === 'SALES_ORDER_FRM'){
                

            //     $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
            //     $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
            //     $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
            //     $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
            //     $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Project' AND a.`REQID` =$id");

            //     return view('MyWorkflow.rejected-byid.rej-sof-prj', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));

            // }




            if($class === 'SALES_ORDER_FRM'){
                
                if ($frmname === 'Sales Order - Project') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Project' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.rejected-byid.rej-sof-prj', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));

                } 

                if ($frmname === 'Sales Order - Delivery') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Delivery' AND a.`REQID` =$id");
    
                    return view('MyWorkflow.rejected-byid.rej-sof-dlv', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - Demo') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - Delivery' AND a.`REQID` =$id");

                    return view('MyWorkflow.rejected-byid.rej-sof-dmo', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

                if ($frmname === 'Sales Order - POC') {
                    $salesOrder = DB::table('sales_order.sales_orders')->where('id',$id)->first();
                    $setupProject = DB::table('general.setup_project')->where('SOID',$id)->first();
                    $salesOrderSystem = DB::table('sales_order.sales_order_system')->where('soid',$id)->get();
                    $salesOrderDocs = DB::table('sales_order.sales_order_docs')->where('SOID',$id)->get();
                    $attachmentsDetails = DB::select("SELECT * FROM general.`attachments` a WHERE a.`formName` = 'Sales Order - POC' AND a.`REQID` =$id");

                    return view('MyWorkflow.rejected-byid.rej-sof-poc', compact('salesOrder','salesOrderSystem','salesOrderDocs','attachmentsDetails','setupProject'));
                } 

            }




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
 
