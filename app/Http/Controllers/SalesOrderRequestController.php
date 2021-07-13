<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesOrderRequestController extends Controller
{
    //

    // AJAX
    public function getAddress($id){
        return json_encode(DB::select("call general.GetAddress_Client_New($id)"));
    }

    public function getContacts($id){
        return json_encode(DB::select("SELECT * FROM general.`businesscontacts` a WHERE a.`BusinessNumber` = '".$id."' "));
    }

    public function getBusinessList($id){
        return json_encode(DB::select("SELECT * FROM general.`business_list` a WHERE a.`Business_Number` = '".$id."' "));
    }

    public function getSetupProject($id){
        return json_encode(DB::select("SELECT * FROM general.`setup_project` a WHERE a.`ClientID` = '".$id."' "));
    }

    public function getdelegates($id){
        return json_encode(DB::select("SELECT * FROM general.`delegates` a WHERE a.`ClientID` = '".$id."' "));
    }

    public function  getcoordinator($soID,$coordID){
        return json_encode(DB::select("SELECT * FROM sales_order.`projectcoordinator` a WHERE a.`SOID` = '".$soID."' AND a.`CoordID` = '".$coordID."' "));
        
    }


    
    public function postSystemName(Request $request){

        $systemname =DB::table('sales_order.systems_type')->insertGetId([
            'type_name' => $request->systemname, 
            'ts' => now()
        ]);
        return response()->json($systemname);
    }

    public function postDocumentName(Request $request){

        $documentname =DB::table('sales_order.documentlist')->insertGetId([
            'DocumentName' => $request->documentname, 
            'UID' => session('LoggedUser'),
            'ts' => now(),
            'STATUS' => 'Active'
        ]);
        return response()->json($documentname);
        

    }



    public function createSofDelivery() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");


        $businesslist = DB::select("SELECT * FROM general.`business_list` a WHERE a.`status` LIKE 'Active%' AND a.`title_id` = '".session('LoggedUser_CompanyID')."' AND a.`Type` = 'CLIENT' ORDER BY a.`business_fullname` ASC");
        // $address = DB::select("call general.GetAddress_Client_New('487')");

        $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a ORDER BY a.`id` DESC");

        $documentlist = DB::select("SELECT * FROM sales_order.`documentlist` a ORDER BY a.`ID` DESC" );


        return view('SalesOrderRequest.create-sof-delivery', compact('posts','businesslist','systemName','documentlist'));
    //return $posts;
    }




    public function saveDLV(Request $request){

        $request->validate([
            'poNumber'=>'required',
            'poDate'=>'required',
            'scopeOfWork'=>'required',
            // 'accountingRemarks'=>'required',
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
            // 'warranty'=>'required',   
            'currency'=>'required',
            'projectCost'=>'required|not_in:0',
            

            'systemname'=>'required',
            'documentname'=>'required',

            // 'accountmanager'=>'required',

            'downpaymentrequired' => 'required|bool',
            'downPaymentPercentage' => 'required_if:downpaymentrequired,1|numeric|between:1,100',

            'invoicerequired' => 'required|bool',
            'invoiceDateNeeded' => 'required_if:invoicerequired,1',

            'file'=>'required'
        ]);


        if(!empty($request->downPaymentPercentage)){
            $downPaymentPercentage = $request->downPaymentPercentage;
        } else {
            $downPaymentPercentage = 0;
        }



        $projectStart = date_create($request->projectStart);
        $projectEnd = date_create($request->projectEnd);
        $poDate = date_create($request->poDate);
        $dateCreated = date_create($request->dateCreated);

        if(!empty($request->invoiceDateNeeded)){
        $invoiceDateNeeded = date_create($request->invoiceDateNeeded);
        $invoiceDateNeeded = date_format($invoiceDateNeeded, 'Y-m-d');
        } else {
        $invoiceDateNeeded = null;
        }

     
        $projectStart = date_format($projectStart, 'Y-m-d');
        $projectEnd = date_format($projectEnd, 'Y-m-d');
        $poDate = date_format($poDate, 'Y-m-d');
        $dateCreated = date_format($dateCreated, 'Y-m-d');


        
        $projectStartConverted = strtotime($projectStart);
        $projectEndConverted = strtotime($projectEnd);

        $projectDuation = ($projectEndConverted - $projectStartConverted)/60/60/24;
      
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

        $ref = DB::select("SELECT IFNULL ((SELECT MAX(SUBSTR(a.`soNum`,10)) FROM sales_order.`sales_orders` a WHERE YEAR(TS) = YEAR(NOW()) AND a.`titleid` = '1'), FALSE) +1 AS 'ref'");
        $ref = $ref[0]->ref;
        $ref = str_pad($ref, 4, "0", STR_PAD_LEFT); 
        $soNumber = "SOF-" . date('Y') . "-" . $ref;
        

        $soIDPrj = DB::table('general.setup_project')->insertGetId([

            'project' => 'Project Site',
            'project_name' => $request->projectName,
            'project_shorttext' => $request->projectShortText,
            'project_type' => 'Project Site',
            'project_location' => $request->deliveryAddress, 
            'project_remarks' => $request->scopeOfWork ,
            'date_saved' => now(),
            'title_id' => session('LoggedUser_CompanyID'),
            'project_no' => $request->projectCode,
            'project_amount' => $request->projectCost,
            'project_duration' => $projectDuation,
            'project_effectivity' => $projectStart,
            'project_expiry' => $projectEnd,
            'status' => 'ACTIVE', 
            'Main_office_id' => session('LoggedUser_CompanyID'),
            'OfficeAlias_Code' => '',
            'OfficeAlias' => '',
            // 'telno' => '0',
            'fax' => '225',
            'PROJECT_TYPES' => '',
            // 'logoname' =>   $request->clientID,
            'DeptHead' => '',
            'Coordinator' => '0',
            'ClientID' => $request->clientID,
            'total_cost' => '0',
            'GID' => '0',
            'ProjectStatus' => 'On-Going', 
            'imported_from_excel' => '0',
            'SOID' => '', //wala pa
            // 'short_text' => '0',
            'last_edit_by' => '0',
            // 'last_edit_datetime' => $request->projectName,
            'branch_name' => '',
            'IncludeEmail' => '0'

        ]);

        $salesOrderID = DB::table('sales_order.sales_orders')->insertGetId([

            'titleid' => session('LoggedUser_CompanyID'),
            'DraftNum' => '',
            'MAINID' => '1', // wala pa
            'projID' => $soIDPrj,
            'pcode' => $request->projectCode,
            'project' => $request->projectName,
            'clientID' =>  $request->clientID,
            'client' => $request->client,
            'DraftStat' => '0',
            'Contactid' => $request->contactPerson,
            'Contact' => $request->contactPersonName,
            'ContactNum' => $request->contactNumber,
            'SubConID' => '', //wala pa
            'SubConName' => '',  // wala pa
            'sodate' => $dateCreated, // wala pa
            'soNum' => $soNumber, // wala pa
            'podate' => $poDate,
            'poNum' => $request->poNumber,
            'ParentOrderId' => '',  // wala pa
            'ParentSONum' => '',  // wala pa
            'DeliveryAddress' =>  $request->deliveryAddress,
            'BillTo' => $request->billingAddress,
            'currency' => $request->currency,
            'amount' => $request->projectCost,
            'UID' => session('LoggedUser'),
            'fname' => session('LoggedUser_FirstName'),
            'lname' => session('LoggedUser_LastName'),
            'department' => session('LoggedUser_DepartmentName'),
            'reportmanager' => 'Chua, Konrad A.', 
            'position' => session('LoggedUser_PositionName'),
            'remarks' => $request->scopeOfWork,
            'Remarks2' => $request->accountingRemarks,
            'purpose' => 'Sales Order - Delivery', 
            'DateAndTimeNeeded' => $projectEnd,
            'Terms' => $request->paymentTerms,
            'GUID' => $GUID, 
            'DeadLineDate' => $projectEnd,
            'Status' => 'In Progress',
            'TS' => now(),
            'InvolvedCost' =>'', //wala pa
            'DeliveryStatus' => 'On-Going', //wala pa
            'Coordinator' => '0', //wala pa
            'IsInvoiceRequired' => $request->invoicerequired,
            'invDate' => $invoiceDateNeeded,
            'IsInvoiceReleased' => '0', //wala pa
            'IsBeginning' => 'No', //wala pa
            'deliveryOption' => '', //wala pa
            // 'InvoiceDate' => '', //wala pa
            'InvoiceNumber' => '', // wala pa
            'imported_from_excel' => '0', //wala pa
            'dp_required' => $request->downpaymentrequired,
            'dp_percentage' => $downPaymentPercentage,
            'project_shorttext' => $request->projectShortText,
            'ForwardProcess' => '', // wala apa
            'warranty' => $request->warranty,
            'erpweb' => '1'
            
        ]);
            
        DB::update("UPDATE general.`setup_project` a SET a.`SOID` = '".$salesOrderID."' WHERE a.`project_id` = '".$soIDPrj."' ;");

        for($i = 0; $i <count($request->systemname); $i++) {

            $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a WHERE a.`id` = '".$request->systemname[$i]."'" );
            $systemName = $systemName[0]->type_name;

            $systemNameArray[] = [
                'soid' => $salesOrderID,
                'systemType'=> $systemName,
                'sysID' => $request->systemname[$i],
                'imported_from_excel' => '0'
            ];

        }


        DB::table('sales_order.sales_order_system')->insert($systemNameArray);

        for($i = 0; $i <count($request->documentname); $i++) {

            $documentname = DB::select("SELECT * FROM sales_order.`documentlist` a WHERE a.`id` = '".$request->documentname[$i]."'" );
            $documentname = $documentname[0]->DocumentName;

            $documentnameArray[] = [
                'SOID' => $salesOrderID,
                'DocID'=> $request->documentname[$i],
                'DocName' => $documentname,
                'imported_from_excel' => '0'
            ];

        }

        // dd($documentnameArray);
        DB::table('sales_order.sales_order_docs')->insert($documentnameArray);

        // Actual Sign
        for ($x = 0; $x < 6; $x++) {
            $array[] = array(
                'PROCESSID'=>$salesOrderID,
                'USER_GRP_IND'=>'0',
                'FRM_NAME'=>'Sales Order - Delivery',
                'TaskTitle'=>'',
                'NS'=>'',
                'FRM_CLASS'=>'SALES_ORDER_FRM',
                'REMARKS'=>$request->scopeOfWork,
                'STATUS'=>'Not Started',
                // 'UID_SIGN'=>'0',
                'TS'=>now(),
                'DUEDATE'=>$projectEnd,
                // 'SIGNDATETIME'=>'',
                'ORDERS'=>$x,
                'REFERENCE'=>$soNumber,
                'PODATE'=>$poDate,
                'PONUM'=>$request->poNumber,
                'DATE'=>$projectEnd,
                'INITID'=>session('LoggedUser'),
                'FNAME'=>session('LoggedUser_FirstName'),
                'LNAME'=>session('LoggedUser_LastName'),
                // 'MI'=>'',
                'DEPARTMENT'=>session('LoggedUser_DepartmentName'),
                'RM_ID'=> '0',
                'REPORTING_MANAGER'=> 'Chua, Konrad A.',

                'PROJECTID'=>$soIDPrj,
                'PROJECT'=>$request->projectName,

                'COMPID'=>session('LoggedUser_CompanyID'),
                'COMPANY'=>session('LoggedUser_CompanyName'),
                'TYPE'=>'Sales Order - Project',
                'CLIENTID'=>$request->clientID,
                'CLIENTNAME'=>$request->client,
                // 'VENDORID'=>'0',
                // 'VENDORNAME'=>'',
                'Max_approverCount'=>'6',
                // 'GUID_GROUPS'=>'',
                'DoneApproving'=>'0',
                'WebpageLink'=>'so_approve.php',
                // 'ApprovedRemarks'=>'',
                'Payee'=>'',
                // 'CurrentSender'=>'0',
                // 'CurrentReceiver'=>'0',
                // 'NOTIFICATIONID'=>'0',
                // 'SENDTOID'=>'0',
                // 'NRN'=>'imported',
                // 'imported_from_excel'=>'0',
                'Amount'=>$request->projectCost,

            );
          }

        if ($array[0]['ORDERS'] == 0){
            $array[0]['USER_GRP_IND'] = 'Sales Reviewer';
            $array[0]['STATUS'] = 'In Progress';
        }

        if ($array[1]['ORDERS'] == 1){
            $array[1]['USER_GRP_IND'] = 'Approval of Account Manager';
        }

        if ($array[2]['ORDERS'] == 2){
            $array[2]['USER_GRP_IND'] = 'Accounting Acknowledgement';
        }

        if ($array[3]['ORDERS'] == 3){
            $array[3]['USER_GRP_IND'] = 'MM Acknowledgement';
        }

        if ($array[4]['ORDERS'] == 4){
            $array[4]['USER_GRP_IND'] = 'Initiator';
        }

        if ($array[5]['ORDERS'] == 5){
            $array[5]['USER_GRP_IND'] = 'SI Confirmation';
        }

        DB::table('general.actual_sign')->insert($array);

        if($request->hasFile('file')){
            foreach($request->file as $file) {
                $completeFileName = $file->getClientOriginalName();
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $randomized = rand();
                $newFileName = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                // $path = '/uploads/attachments/'.$GUID;
                // $ref = $request->referenceNumber;
                $soNumber = str_replace('-', '_', $soNumber);
                // For moving the file
                $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                // For preview
                $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                $symPath ="public/Attachments/SOF";
                $file->storeAs($destinationPath, $completeFileName);
                $fileDestination = $storagePath.'/'.$completeFileName;
                
                

                $image = base64_encode(file_get_contents($file));

                
                DB::table('repository.so_attachment')->insert([
                    'REFID' => $salesOrderID,
                    'FileName' => $completeFileName,
                    'IMG' => $image,
                    'UID' => session('LoggedUser'),
                    'Ext' => $extension
                ]);





                $insert_doc = DB::table('general.attachments')->insert([
                    'INITID' => session('LoggedUser'),
                    'REQID' => $salesOrderID, 
                    'filename' => $completeFileName,
                    'filepath' => $storagePath, 
                    'fileExtension' => $extension,
                    'newFilename' => $newFileName,
                    'fileDestination'=>$destinationPath,
                    'formName' => 'Sales Order - Delivery',
                    'created_at' => date('Y-m-d H:i:s')
           
                ]);
            }
        } 


        return back()->with('form_submitted', 'Your SOF Delivery request was successfully submitted.');



    }


























    public function createSofProject() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        // $businesslist = DB::table('general.business_list')
        //                                                 ->where('Type','=','CLIENT')
        //                                                 ->where('title_id','=',session('LoggedUser_CompanyID'))
        //                                                 ->where('status', 'like', 'Active%')
        //                                                 ->orderBy('business_fullname', 'asc')
        //                                                 ->get();


        $businesslist = DB::select("SELECT * FROM general.`business_list` a WHERE a.`status` LIKE 'Active%' AND a.`title_id` = '".session('LoggedUser_CompanyID')."' AND a.`Type` = 'CLIENT' ORDER BY a.`business_fullname` ASC");
        // $address = DB::select("call general.GetAddress_Client_New('487')");

        $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a ORDER BY a.`id` DESC");

        $documentlist = DB::select("SELECT * FROM sales_order.`documentlist` a ORDER BY a.`ID` DESC" );

        return view('SalesOrderRequest.create-sof-project', compact('posts','businesslist','systemName','documentlist'));
    }






    public function savePRJ(Request $request){


        $request->validate([
            'poNumber'=>'required',
            'poDate'=>'required',
            'scopeOfWork'=>'required',
            // 'accountingRemarks'=>'required',
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
            // 'warranty'=>'required',   
            'currency'=>'required',
            'projectCost'=>'required',
            

            'systemname'=>'required',
            'documentname'=>'required',

            // 'accountmanager'=>'required',

            'downpaymentrequired' => 'required|bool',
            'downPaymentPercentage' => 'required_if:downpaymentrequired,1|numeric|between:1,100',

            'invoicerequired' => 'required|bool',
            'invoiceDateNeeded' => 'required_if:invoicerequired,1',

            'file'=>'required'
        ]);


        if(!empty($request->downPaymentPercentage)){
            $downPaymentPercentage = $request->downPaymentPercentage;
        } else {
            $downPaymentPercentage = 0;
        }



        $projectStart = date_create($request->projectStart);
        $projectEnd = date_create($request->projectEnd);
        $poDate = date_create($request->poDate);
        $dateCreated = date_create($request->dateCreated);

        if(!empty($request->invoiceDateNeeded)){
        $invoiceDateNeeded = date_create($request->invoiceDateNeeded);
        $invoiceDateNeeded = date_format($invoiceDateNeeded, 'Y-m-d');
        } else {
        $invoiceDateNeeded = null;
        }

     
        $projectStart = date_format($projectStart, 'Y-m-d');
        $projectEnd = date_format($projectEnd, 'Y-m-d');
        $poDate = date_format($poDate, 'Y-m-d');
        $dateCreated = date_format($dateCreated, 'Y-m-d');


        
        $projectStartConverted = strtotime($projectStart);
        $projectEndConverted = strtotime($projectEnd);

        $projectDuation = ($projectEndConverted - $projectStartConverted)/60/60/24;
      
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

        $ref = DB::select("SELECT IFNULL ((SELECT MAX(SUBSTR(a.`soNum`,10)) FROM sales_order.`sales_orders` a WHERE YEAR(TS) = YEAR(NOW()) AND a.`titleid` = '1'), FALSE) +1 AS 'ref'");
        $ref = $ref[0]->ref;
        $ref = str_pad($ref, 4, "0", STR_PAD_LEFT); 
        $soNumber = "SOF-" . date('Y') . "-" . $ref;
        

        $soIDPrj = DB::table('general.setup_project')->insertGetId([

            'project' => 'Project Site',
            'project_name' => $request->projectName,
            'project_shorttext' => $request->projectShortText,
            'project_type' => 'Project Site',
            'project_location' => $request->deliveryAddress, 
            'project_remarks' => $request->scopeOfWork ,
            'date_saved' => now(),
            'title_id' => session('LoggedUser_CompanyID'),
            'project_no' => $request->projectCode,
            'project_amount' => $request->projectCost,
            'project_duration' => $projectDuation,
            'project_effectivity' => $projectStart,
            'project_expiry' => $projectEnd,
            'status' => 'ACTIVE', 
            'Main_office_id' => session('LoggedUser_CompanyID'),
            'OfficeAlias_Code' => '',
            'OfficeAlias' => '',
            // 'telno' => '0',
            'fax' => '225',
            'PROJECT_TYPES' => '',
            // 'logoname' =>   $request->clientID,
            'DeptHead' => '',
            'Coordinator' => '0',
            'ClientID' => $request->clientID,
            'total_cost' => '0',
            'GID' => '0',
            'ProjectStatus' => 'On-Going', 
            'imported_from_excel' => '0',
            'SOID' => '', //wala pa
            // 'short_text' => '0',
            'last_edit_by' => '0',
            // 'last_edit_datetime' => $request->projectName,
            'branch_name' => '',
            'IncludeEmail' => '0'

        ]);

        $salesOrderID = DB::table('sales_order.sales_orders')->insertGetId([

            'titleid' => session('LoggedUser_CompanyID'),
            'DraftNum' => '',
            'MAINID' => '1', // wala pa
            'projID' => $soIDPrj,
            'pcode' => $request->projectCode,
            'project' => $request->projectName,
            'clientID' =>  $request->clientID,
            'client' => $request->client,
            'DraftStat' => '0',
            'Contactid' => $request->contactPerson,
            'Contact' => $request->contactPersonName,
            'ContactNum' => $request->contactNumber,
            'SubConID' => '', //wala pa
            'SubConName' => '',  // wala pa
            'sodate' => $dateCreated, // wala pa
            'soNum' => $soNumber, // wala pa
            'podate' => $poDate,
            'poNum' => $request->poNumber,
            'ParentOrderId' => '',  // wala pa
            'ParentSONum' => '',  // wala pa
            'DeliveryAddress' =>  $request->deliveryAddress,
            'BillTo' => $request->billingAddress,
            'currency' => $request->currency,
            'amount' => $request->projectCost,
            'UID' => session('LoggedUser'),
            'fname' => session('LoggedUser_FirstName'),
            'lname' => session('LoggedUser_LastName'),
            'department' => session('LoggedUser_DepartmentName'),
            'reportmanager' => 'Chua, Konrad A.', 
            'position' => session('LoggedUser_PositionName'),
            'remarks' => $request->scopeOfWork,
            'Remarks2' => $request->accountingRemarks,
            'purpose' => 'Sales Order - Project', 
            'DateAndTimeNeeded' => $projectEnd,
            'Terms' => $request->paymentTerms,
            'GUID' => $GUID, 
            'DeadLineDate' => $projectEnd,
            'Status' => 'In Progress',
            'TS' => now(),
            'InvolvedCost' =>'', //wala pa
            'DeliveryStatus' => 'On-Going', //wala pa
            'Coordinator' => '0', //wala pa
            'IsInvoiceRequired' => $request->invoicerequired,
            'invDate' => $invoiceDateNeeded,
            'IsInvoiceReleased' => '0', //wala pa
            'IsBeginning' => 'No', //wala pa
            'deliveryOption' => '', //wala pa
            // 'InvoiceDate' => '', //wala pa
            'InvoiceNumber' => '', // wala pa
            'imported_from_excel' => '0', //wala pa
            'dp_required' => $request->downpaymentrequired,
            'dp_percentage' => $downPaymentPercentage,
            'project_shorttext' => $request->projectShortText,
            'ForwardProcess' => '', // wala apa
            'warranty' => $request->warranty,
            'erpweb' => '1'
            
        ]);
            
        DB::update("UPDATE general.`setup_project` a SET a.`SOID` = '".$salesOrderID."' WHERE a.`project_id` = '".$soIDPrj."' ;");

        for($i = 0; $i <count($request->systemname); $i++) {

            $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a WHERE a.`id` = '".$request->systemname[$i]."'" );
            $systemName = $systemName[0]->type_name;

            $systemNameArray[] = [
                'soid' => $salesOrderID,
                'systemType'=> $systemName,
                'sysID' => $request->systemname[$i],
                'imported_from_excel' => '0'
            ];

        }


        DB::table('sales_order.sales_order_system')->insert($systemNameArray);

        for($i = 0; $i <count($request->documentname); $i++) {

            $documentname = DB::select("SELECT * FROM sales_order.`documentlist` a WHERE a.`id` = '".$request->documentname[$i]."'" );
            $documentname = $documentname[0]->DocumentName;

            $documentnameArray[] = [
                'SOID' => $salesOrderID,
                'DocID'=> $request->documentname[$i],
                'DocName' => $documentname,
                'imported_from_excel' => '0'
            ];

        }

        // dd($documentnameArray);
        DB::table('sales_order.sales_order_docs')->insert($documentnameArray);

        // Actual Sign
        for ($x = 0; $x < 8; $x++) {
            $array[] = array(
                'PROCESSID'=>$salesOrderID,
                'USER_GRP_IND'=>'0',
                'FRM_NAME'=>'Sales Order - Project',
                'TaskTitle'=>'',
                'NS'=>'',
                'FRM_CLASS'=>'SALES_ORDER_FRM',
                'REMARKS'=>$request->scopeOfWork,
                'STATUS'=>'Not Started',
                // 'UID_SIGN'=>'0',
                'TS'=>now(),
                'DUEDATE'=>$projectEnd,
                // 'SIGNDATETIME'=>'',
                'ORDERS'=>$x,
                'REFERENCE'=>$soNumber,
                'PODATE'=>$poDate,
                'PONUM'=>$request->poNumber,
                'DATE'=>$projectEnd,
                'INITID'=>session('LoggedUser'),
                'FNAME'=>session('LoggedUser_FirstName'),
                'LNAME'=>session('LoggedUser_LastName'),
                // 'MI'=>'',
                'DEPARTMENT'=>session('LoggedUser_DepartmentName'),
                'RM_ID'=> '0',
                'REPORTING_MANAGER'=> 'Chua, Konrad A.',

                'PROJECTID'=>$soIDPrj,
                'PROJECT'=>$request->projectName,

                'COMPID'=>session('LoggedUser_CompanyID'),
                'COMPANY'=>session('LoggedUser_CompanyName'),
                'TYPE'=>'Sales Order - Project',
                'CLIENTID'=>$request->clientID,
                'CLIENTNAME'=>$request->client,
                // 'VENDORID'=>'0',
                // 'VENDORNAME'=>'',
                'Max_approverCount'=>'8',
                // 'GUID_GROUPS'=>'',
                'DoneApproving'=>'0',
                'WebpageLink'=>'so_approve.php',
                // 'ApprovedRemarks'=>'',
                'Payee'=>'',
                // 'CurrentSender'=>'0',
                // 'CurrentReceiver'=>'0',
                // 'NOTIFICATIONID'=>'0',
                // 'SENDTOID'=>'0',
                // 'NRN'=>'imported',
                // 'imported_from_excel'=>'0',
                'Amount'=>$request->projectCost,

            );
          }

        if ($array[0]['ORDERS'] == 0){
            $array[0]['USER_GRP_IND'] = 'Sales Reviewer';
            $array[0]['STATUS'] = 'In Progress';
        }

        if ($array[1]['ORDERS'] == 1){
            $array[1]['USER_GRP_IND'] = 'Approval of Account Manager';
        }

        if ($array[2]['ORDERS'] == 2){
            $array[2]['USER_GRP_IND'] = 'Accounting Acknowledgement';
        }

        if ($array[3]['ORDERS'] == 3){
            $array[3]['USER_GRP_IND'] = 'Approval of Project Head';
        }

        if ($array[4]['ORDERS'] == 4){
            $array[4]['USER_GRP_IND'] = 'PC Acknowledgement';
        }

        if ($array[5]['ORDERS'] == 5){
            $array[5]['USER_GRP_IND'] = 'MM Acknowledgement';
        }

        if ($array[6]['ORDERS'] == 6){
            $array[6]['USER_GRP_IND'] = 'Initiator';
        }

        if ($array[7]['ORDERS'] == 7){
            $array[7]['USER_GRP_IND'] = 'SI Confirmation';
        }

        DB::table('general.actual_sign')->insert($array);

        if($request->hasFile('file')){
            foreach($request->file as $file) {
                $completeFileName = $file->getClientOriginalName();
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $randomized = rand();
                $newFileName = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                // $path = '/uploads/attachments/'.$GUID;
                // $ref = $request->referenceNumber;
                $soNumber = str_replace('-', '_', $soNumber);
                // For moving the file
                $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                // For preview
                $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                $symPath ="public/Attachments/SOF";
                $file->storeAs($destinationPath, $completeFileName);
                $fileDestination = $storagePath.'/'.$completeFileName; 
                
                $image = base64_encode(file_get_contents($file));

                
                DB::table('repository.so_attachment')->insert([
                    'REFID' => $salesOrderID,
                    'FileName' => $completeFileName,
                    'IMG' => $image,
                    'UID' => session('LoggedUser'),
                    'Ext' => $extension
                ]);

                $insert_doc = DB::table('general.attachments')->insert([
                    'INITID' => session('LoggedUser'),
                    'REQID' => $salesOrderID, 
                    'filename' => $completeFileName,
                    'filepath' => $storagePath, 
                    'fileExtension' => $extension,
                    'newFilename' => $newFileName,
                    'fileDestination'=>$destinationPath,
                    'formName' => 'Sales Order - Project',
                    'created_at' => date('Y-m-d H:i:s')
           
                ]);
            }
        } 



        return back()->with('form_submitted', 'Your SOF Project request was successfully submitted.');

    }






        
    public function createSofDemo() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        $businesslist = DB::select("SELECT * FROM general.`business_list` a WHERE a.`status` LIKE 'Active%' AND a.`title_id` = '".session('LoggedUser_CompanyID')."' AND a.`Type` = 'CLIENT' ORDER BY a.`business_fullname` ASC");
        $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a ORDER BY a.`id` DESC");
        $documentlist = DB::select("SELECT * FROM sales_order.`documentlist` a ORDER BY a.`ID` DESC" );
        return view('SalesOrderRequest.create-sof-demo', compact('posts','businesslist','systemName','documentlist'));
    }




    public function saveDMO(Request $request){

        $request->validate([
            'poNumber'=>'required',
            'poDate'=>'required',
            'scopeOfWork'=>'required',
            // 'accountingRemarks'=>'required',
            'clientID' => 'required|not_in:0',
            'projectCode'=>'required',
            'projectShortText'=>'required',
            'projectName'=>'required',
            'contactPerson'=>'required', 
            'contactNumber'=>'required',
            'deliveryAddress'=>'required',
            'billingAddress'=>'required',

            // 'paymentTerms'=>'required',
            // 'projectStart'=>'required',
            // 'projectEnd'=>'required',
            // 'warranty'=>'required',   
            // 'currency'=>'required',
            // 'projectCost'=>'required',

            'systemname'=>'required',
            'documentname'=>'required',

            // 'accountmanager'=>'required',
            // 'downpaymentrequired' => 'required|bool',
            // 'downPaymentPercentage' => 'required_if:downpaymentrequired,1|numeric|between:1,100',
            // 'invoicerequired' => 'required|bool',
            // 'invoiceDateNeeded' => 'required_if:invoicerequired,1',

            'file'=>'required'
        ]);

        if(!empty($request->downPaymentPercentage)){
            $downPaymentPercentage = $request->downPaymentPercentage;
        } else {
            $downPaymentPercentage = 0;
        }

        $projectStart = date_create($request->projectStart);
        $projectEnd = date_create($request->projectEnd);
        $poDate = date_create($request->poDate);
        $dateCreated = date_create($request->dateCreated);

        if(!empty($request->invoiceDateNeeded)){
        $invoiceDateNeeded = date_create($request->invoiceDateNeeded);
        $invoiceDateNeeded = date_format($invoiceDateNeeded, 'Y-m-d');
        } else {
        $invoiceDateNeeded = null;
        }

     
        $projectStart = date_format($projectStart, 'Y-m-d');
        $projectEnd = date_format($projectEnd, 'Y-m-d');
        $poDate = date_format($poDate, 'Y-m-d');
        $dateCreated = date_format($dateCreated, 'Y-m-d');


        
        $projectStartConverted = strtotime($projectStart);
        $projectEndConverted = strtotime($projectEnd);

        $projectDuation = ($projectEndConverted - $projectStartConverted)/60/60/24;
      
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

        $ref = DB::select("SELECT IFNULL ((SELECT MAX(SUBSTR(a.`soNum`,10)) FROM sales_order.`sales_orders` a WHERE YEAR(TS) = YEAR(NOW()) AND a.`titleid` = '1'), FALSE) +1 AS 'ref'");
        $ref = $ref[0]->ref;
        $ref = str_pad($ref, 4, "0", STR_PAD_LEFT); 
        $soNumber = "SOF-" . date('Y') . "-" . $ref;
        

        $soIDPrj = DB::table('general.setup_project')->insertGetId([

            'project' => 'Project Site',
            'project_name' => $request->projectName,
            'project_shorttext' => $request->projectShortText,
            'project_type' => 'Project Site',
            'project_location' => $request->deliveryAddress, 
            'project_remarks' => $request->scopeOfWork ,
            'date_saved' => now(),
            'title_id' => session('LoggedUser_CompanyID'),
            'project_no' => $request->projectCode,
            'project_amount' => $request->projectCost,
            'project_duration' => $projectDuation,
            'project_effectivity' => NULL,
            'project_expiry' => NULL,
            'status' => 'ACTIVE', 
            'Main_office_id' => session('LoggedUser_CompanyID'),
            'OfficeAlias_Code' => '',
            'OfficeAlias' => '',
            // 'telno' => '0',
            'fax' => '225',
            'PROJECT_TYPES' => '',
            // 'logoname' =>   $request->clientID,
            'DeptHead' => '',
            'Coordinator' => '0',
            'ClientID' => $request->clientID,
            'total_cost' => '0',
            'GID' => '0',
            'ProjectStatus' => 'On-Going', 
            'imported_from_excel' => '0',
            'SOID' => '', //wala pa
            // 'short_text' => '0',
            'last_edit_by' => '0',
            // 'last_edit_datetime' => $request->projectName,
            'branch_name' => '',
            'IncludeEmail' => '0'

        ]);

        $salesOrderID = DB::table('sales_order.sales_orders')->insertGetId([

            'titleid' => session('LoggedUser_CompanyID'),
            'DraftNum' => '',
            'MAINID' => '1', // wala pa
            'projID' => $soIDPrj,
            'pcode' => $request->projectCode,
            'project' => $request->projectName,
            'clientID' =>  $request->clientID,
            'client' => $request->client,
            'DraftStat' => '0',
            'Contactid' => $request->contactPerson,
            'Contact' => $request->contactPersonName,
            'ContactNum' => $request->contactNumber,
            'SubConID' => '', //wala pa
            'SubConName' => '',  // wala pa
            'sodate' => $dateCreated, // wala pa
            'soNum' => $soNumber, // wala pa
            'podate' => $poDate,
            'poNum' => $request->poNumber,
            'ParentOrderId' => '',  // wala pa
            'ParentSONum' => '',  // wala pa
            'DeliveryAddress' =>  $request->deliveryAddress,
            'BillTo' => $request->billingAddress,
            'currency' => $request->currency,
            'amount' => $request->projectCost,
            'UID' => session('LoggedUser'),
            'fname' => session('LoggedUser_FirstName'),
            'lname' => session('LoggedUser_LastName'),
            'department' => session('LoggedUser_DepartmentName'),
            'reportmanager' => 'Chua, Konrad A.', 
            'position' => session('LoggedUser_PositionName'),
            'remarks' => $request->scopeOfWork,
            'Remarks2' => $request->accountingRemarks,
            'purpose' => 'Sales Order - Delivery', 
            'DateAndTimeNeeded' => NULL,
            'Terms' => $request->paymentTerms,
            'GUID' => $GUID, 
            'DeadLineDate' => NULL,
            'Status' => 'In Progress',
            'TS' => now(),
            'InvolvedCost' =>'', //wala pa
            'DeliveryStatus' => 'On-Going', //wala pa
            'Coordinator' => '0', //wala pa
            'IsInvoiceRequired' => $request->invoicerequired,
            'invDate' => $invoiceDateNeeded,
            'IsInvoiceReleased' => '0', //wala pa
            'IsBeginning' => 'No', //wala pa
            'deliveryOption' => '', //wala pa
            // 'InvoiceDate' => '', //wala pa
            'InvoiceNumber' => '', // wala pa
            'imported_from_excel' => '0', //wala pa
            'dp_required' => $request->downpaymentrequired,
            'dp_percentage' => $downPaymentPercentage,
            'project_shorttext' => $request->projectShortText,
            'ForwardProcess' => '', // wala apa
            'warranty' => $request->warranty,
            'erpweb' => '1'
            
        ]);
            
        DB::update("UPDATE general.`setup_project` a SET a.`SOID` = '".$salesOrderID."' WHERE a.`project_id` = '".$soIDPrj."' ;");

        for($i = 0; $i <count($request->systemname); $i++) {

            $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a WHERE a.`id` = '".$request->systemname[$i]."'" );
            $systemName = $systemName[0]->type_name;

            $systemNameArray[] = [
                'soid' => $salesOrderID,
                'systemType'=> $systemName,
                'sysID' => $request->systemname[$i],
                'imported_from_excel' => '0'
            ];

        }


        DB::table('sales_order.sales_order_system')->insert($systemNameArray);

        for($i = 0; $i <count($request->documentname); $i++) {

            $documentname = DB::select("SELECT * FROM sales_order.`documentlist` a WHERE a.`id` = '".$request->documentname[$i]."'" );
            $documentname = $documentname[0]->DocumentName;

            $documentnameArray[] = [
                'SOID' => $salesOrderID,
                'DocID'=> $request->documentname[$i],
                'DocName' => $documentname,
                'imported_from_excel' => '0'
            ];

        }

        // dd($documentnameArray);
        DB::table('sales_order.sales_order_docs')->insert($documentnameArray);

        // Actual Sign
        for ($x = 0; $x < 5; $x++) {
            $array[] = array(
                'PROCESSID'=>$salesOrderID,
                'USER_GRP_IND'=>'0',
                'FRM_NAME'=>'Sales Order - Demo',
                'TaskTitle'=>'',
                'NS'=>'',
                'FRM_CLASS'=>'SALES_ORDER_FRM',
                'REMARKS'=>$request->scopeOfWork,
                'STATUS'=>'Not Started',
                // 'UID_SIGN'=>'0',
                'TS'=>now(),
                'DUEDATE'=>$projectEnd,
                // 'SIGNDATETIME'=>'',
                'ORDERS'=>$x,
                'REFERENCE'=>$soNumber,
                'PODATE'=>$poDate,
                'PONUM'=>$request->poNumber,
                'DATE'=>$projectEnd,
                'INITID'=>session('LoggedUser'),
                'FNAME'=>session('LoggedUser_FirstName'),
                'LNAME'=>session('LoggedUser_LastName'),
                // 'MI'=>'',
                'DEPARTMENT'=>session('LoggedUser_DepartmentName'),
                'RM_ID'=> '0',
                'REPORTING_MANAGER'=> 'Chua, Konrad A.',

                'PROJECTID'=>$soIDPrj,
                'PROJECT'=>$request->projectName,

                'COMPID'=>session('LoggedUser_CompanyID'),
                'COMPANY'=>session('LoggedUser_CompanyName'),
                'TYPE'=>'Sales Order - Project',
                'CLIENTID'=>$request->clientID,
                'CLIENTNAME'=>$request->client,
                // 'VENDORID'=>'0',
                // 'VENDORNAME'=>'',
                'Max_approverCount'=>'5',
                // 'GUID_GROUPS'=>'',
                'DoneApproving'=>'0',
                'WebpageLink'=>'so_approve.php',
                // 'ApprovedRemarks'=>'',
                'Payee'=>'',
                // 'CurrentSender'=>'0',
                // 'CurrentReceiver'=>'0',
                // 'NOTIFICATIONID'=>'0',
                // 'SENDTOID'=>'0',
                // 'NRN'=>'imported',
                // 'imported_from_excel'=>'0',
                'Amount'=>$request->projectCost,

            );
          }

        if ($array[0]['ORDERS'] == 0){
            $array[0]['USER_GRP_IND'] = 'Sales Reviewer';
            $array[0]['STATUS'] = 'In Progress';
        }

        if ($array[1]['ORDERS'] == 1){
            $array[1]['USER_GRP_IND'] = 'Approval of Account Manager';
        }

        if ($array[2]['ORDERS'] == 2){
            $array[2]['USER_GRP_IND'] = 'Accounting Acknowledgement';
        }

        if ($array[3]['ORDERS'] == 3){
            $array[3]['USER_GRP_IND'] = 'MM Acknowledgement';
        }

        if ($array[4]['ORDERS'] == 4){
            $array[4]['USER_GRP_IND'] = 'Initiator';
        }



        DB::table('general.actual_sign')->insert($array);

        if($request->hasFile('file')){
            foreach($request->file as $file) {
                $completeFileName = $file->getClientOriginalName();
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $randomized = rand();
                $newFileName = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                // $path = '/uploads/attachments/'.$GUID;
                // $ref = $request->referenceNumber;
                $soNumber = str_replace('-', '_', $soNumber);
                // For moving the file
                $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                // For preview
                $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                $symPath ="public/Attachments/SOF";
                $file->storeAs($destinationPath, $completeFileName);
                $fileDestination = $storagePath.'/'.$completeFileName;
                
                $image = base64_encode(file_get_contents($file));

                
                DB::table('repository.so_attachment')->insert([
                    'REFID' => $salesOrderID,
                    'FileName' => $completeFileName,
                    'IMG' => $image,
                    'UID' => session('LoggedUser'),
                    'Ext' => $extension
                ]);


                $insert_doc = DB::table('general.attachments')->insert([
                    'INITID' => session('LoggedUser'),
                    'REQID' => $salesOrderID, 
                    'filename' => $completeFileName,
                    'filepath' => $storagePath, 
                    'fileExtension' => $extension,
                    'newFilename' => $newFileName,
                    'fileDestination'=>$destinationPath,
                    'formName' => 'Sales Order - Demo',
                    'created_at' => date('Y-m-d H:i:s')
           
                ]);
            }
        } 


        return back()->with('form_submitted', 'Your SOF Demo request was successfully submitted.');

    }
























    public function createSofPoc() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        $businesslist = DB::select("SELECT * FROM general.`business_list` a WHERE a.`status` LIKE 'Active%' AND a.`title_id` = '".session('LoggedUser_CompanyID')."' AND a.`Type` = 'CLIENT' ORDER BY a.`business_fullname` ASC");
        $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a ORDER BY a.`id` DESC");
        $documentlist = DB::select("SELECT * FROM sales_order.`documentlist` a ORDER BY a.`ID` DESC" );
        return view('SalesOrderRequest.create-sof-poc', compact('posts','businesslist','systemName','documentlist'));
    //return $posts;
    }

    
    public function savePOC(Request $request){
        
        $request->validate([
            'poNumber'=>'required',
            'poDate'=>'required',
            'scopeOfWork'=>'required',
            // 'accountingRemarks'=>'required',
            'clientID' => 'required|not_in:0',
            'projectCode'=>'required',
            'projectShortText'=>'required',
            'projectName'=>'required',
            'contactPerson'=>'required', 
            'contactNumber'=>'required',
            'deliveryAddress'=>'required',
            'billingAddress'=>'required',

            // 'paymentTerms'=>'required',
            // 'projectStart'=>'required',
            // 'projectEnd'=>'required',
            // 'warranty'=>'required',   
            // 'currency'=>'required',
            // 'projectCost'=>'required',

            'systemname'=>'required',
            'documentname'=>'required',

            // 'accountmanager'=>'required',
            // 'downpaymentrequired' => 'required|bool',
            // 'downPaymentPercentage' => 'required_if:downpaymentrequired,1|numeric|between:1,100',
            // 'invoicerequired' => 'required|bool',
            // 'invoiceDateNeeded' => 'required_if:invoicerequired,1',

            'file'=>'required'
        ]);

        if(!empty($request->downPaymentPercentage)){
            $downPaymentPercentage = $request->downPaymentPercentage;
        } else {
            $downPaymentPercentage = 0;
        }

        $projectStart = date_create($request->projectStart);
        $projectEnd = date_create($request->projectEnd);
        $poDate = date_create($request->poDate);
        $dateCreated = date_create($request->dateCreated);

        if(!empty($request->invoiceDateNeeded)){
        $invoiceDateNeeded = date_create($request->invoiceDateNeeded);
        $invoiceDateNeeded = date_format($invoiceDateNeeded, 'Y-m-d');
        } else {
        $invoiceDateNeeded = null;
        }

     
        $projectStart = date_format($projectStart, 'Y-m-d');
        $projectEnd = date_format($projectEnd, 'Y-m-d');
        $poDate = date_format($poDate, 'Y-m-d');
        $dateCreated = date_format($dateCreated, 'Y-m-d');


        
        $projectStartConverted = strtotime($projectStart);
        $projectEndConverted = strtotime($projectEnd);

        $projectDuation = ($projectEndConverted - $projectStartConverted)/60/60/24;
      
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

        $ref = DB::select("SELECT IFNULL ((SELECT MAX(SUBSTR(a.`soNum`,10)) FROM sales_order.`sales_orders` a WHERE YEAR(TS) = YEAR(NOW()) AND a.`titleid` = '1'), FALSE) +1 AS 'ref'");
        $ref = $ref[0]->ref;
        $ref = str_pad($ref, 4, "0", STR_PAD_LEFT); 
        $soNumber = "SOF-" . date('Y') . "-" . $ref;
        

        $soIDPrj = DB::table('general.setup_project')->insertGetId([

            'project' => 'Project Site',
            'project_name' => $request->projectName,
            'project_shorttext' => $request->projectShortText,
            'project_type' => 'Project Site',
            'project_location' => $request->deliveryAddress, 
            'project_remarks' => $request->scopeOfWork ,
            'date_saved' => now(),
            'title_id' => session('LoggedUser_CompanyID'),
            'project_no' => $request->projectCode,
            // 'project_amount' => $request->projectCost,
            'project_duration' => '0',
            'project_effectivity' => $poDate,
            'project_expiry' => $poDate,
            'status' => 'ACTIVE', 
            'Main_office_id' => session('LoggedUser_CompanyID'),
            'OfficeAlias_Code' => '',
            'OfficeAlias' => '',
            // 'telno' => '0',
            'fax' => '225',
            'PROJECT_TYPES' => '',
            // 'logoname' =>   $request->clientID,
            'DeptHead' => '',
            'Coordinator' => '0',
            'ClientID' => $request->clientID,
            'total_cost' => '0',
            'GID' => '0',
            'ProjectStatus' => 'On-Going', 
            'imported_from_excel' => '0',
            'SOID' => '', //wala pa
            // 'short_text' => '0',
            'last_edit_by' => '0',
            // 'last_edit_datetime' => $request->projectName,
            'branch_name' => '',
            'IncludeEmail' => '0'

        ]);

        $salesOrderID = DB::table('sales_order.sales_orders')->insertGetId([

            'titleid' => session('LoggedUser_CompanyID'),
            'DraftNum' => '',
            'MAINID' => '1', // wala pa
            'projID' => $soIDPrj,
            'pcode' => $request->projectCode,
            'project' => $request->projectName,
            'clientID' =>  $request->clientID,
            'client' => $request->client,
            'DraftStat' => '0',
            'Contactid' => $request->contactPerson,
            'Contact' => $request->contactPersonName,
            'ContactNum' => $request->contactNumber,
            'SubConID' => '', //wala pa
            'SubConName' => '',  // wala pa
            'sodate' => $dateCreated, // wala pa
            'soNum' => $soNumber, // wala pa
            'podate' => $poDate,
            'poNum' => $request->poNumber,
            'ParentOrderId' => '',  // wala pa
            'ParentSONum' => '',  // wala pa
            'DeliveryAddress' =>  $request->deliveryAddress,
            'BillTo' => $request->billingAddress,
            'currency' => $request->currency,
            'amount' => $request->projectCost,
            'UID' => session('LoggedUser'),
            'fname' => session('LoggedUser_FirstName'),
            'lname' => session('LoggedUser_LastName'),
            'department' => session('LoggedUser_DepartmentName'),
            'reportmanager' => 'Chua, Konrad A.', 
            'position' => session('LoggedUser_PositionName'),
            'remarks' => $request->scopeOfWork,
            'Remarks2' => $request->accountingRemarks,
            'purpose' => 'Sales Order - Delivery', 
            'DateAndTimeNeeded' => $poDate,
            'Terms' => $request->paymentTerms,
            'GUID' => $GUID, 
            'DeadLineDate' => $poDate,
            'Status' => 'In Progress',
            'TS' => now(),
            'InvolvedCost' =>'', //wala pa
            'DeliveryStatus' => 'On-Going', //wala pa
            'Coordinator' => '0', //wala pa
            'IsInvoiceRequired' => $request->invoicerequired,
            'invDate' => $invoiceDateNeeded,
            'IsInvoiceReleased' => '0', //wala pa
            'IsBeginning' => 'No', //wala pa
            'deliveryOption' => '', //wala pa
            // 'InvoiceDate' => '', //wala pa
            'InvoiceNumber' => '', // wala pa
            'imported_from_excel' => '0', //wala pa
            'dp_required' => $request->downpaymentrequired,
            'dp_percentage' => $downPaymentPercentage,
            'project_shorttext' => $request->projectShortText,
            'ForwardProcess' => '', // wala apa
            'warranty' => $request->warranty,
            'erpweb' => '1'
            
        ]);
            
        DB::update("UPDATE general.`setup_project` a SET a.`SOID` = '".$salesOrderID."' WHERE a.`project_id` = '".$soIDPrj."' ;");

        for($i = 0; $i <count($request->systemname); $i++) {

            $systemName = DB::select("SELECT * FROM sales_order.`systems_type` a WHERE a.`id` = '".$request->systemname[$i]."'" );
            $systemName = $systemName[0]->type_name;

            $systemNameArray[] = [
                'soid' => $salesOrderID,
                'systemType'=> $systemName,
                'sysID' => $request->systemname[$i],
                'imported_from_excel' => '0'
            ];

        }


        DB::table('sales_order.sales_order_system')->insert($systemNameArray);

        for($i = 0; $i <count($request->documentname); $i++) {

            $documentname = DB::select("SELECT * FROM sales_order.`documentlist` a WHERE a.`id` = '".$request->documentname[$i]."'" );
            $documentname = $documentname[0]->DocumentName;

            $documentnameArray[] = [
                'SOID' => $salesOrderID,
                'DocID'=> $request->documentname[$i],
                'DocName' => $documentname,
                'imported_from_excel' => '0'
            ];

        }

        // dd($documentnameArray);
        DB::table('sales_order.sales_order_docs')->insert($documentnameArray);

        // Actual Sign
        for ($x = 0; $x < 5; $x++) {
            $array[] = array(
                'PROCESSID'=>$salesOrderID,
                'USER_GRP_IND'=>'0',
                'FRM_NAME'=>'Sales Order - POC',
                'TaskTitle'=>'',
                'NS'=>'',
                'FRM_CLASS'=>'SALES_ORDER_FRM',
                'REMARKS'=>$request->scopeOfWork,
                'STATUS'=>'Not Started',
                // 'UID_SIGN'=>'0',
                'TS'=>now(),
                'DUEDATE'=>$projectEnd,
                // 'SIGNDATETIME'=>'',
                'ORDERS'=>$x,
                'REFERENCE'=>$soNumber,
                'PODATE'=>$poDate,
                'PONUM'=>$request->poNumber,
                'DATE'=>$projectEnd,
                'INITID'=>session('LoggedUser'),
                'FNAME'=>session('LoggedUser_FirstName'),
                'LNAME'=>session('LoggedUser_LastName'),
                // 'MI'=>'',
                'DEPARTMENT'=>session('LoggedUser_DepartmentName'),
                'RM_ID'=> '0',
                'REPORTING_MANAGER'=> 'Chua, Konrad A.',

                'PROJECTID'=>$soIDPrj,
                'PROJECT'=>$request->projectName,

                'COMPID'=>session('LoggedUser_CompanyID'),
                'COMPANY'=>session('LoggedUser_CompanyName'),
                'TYPE'=>'Sales Order - Project',
                'CLIENTID'=>$request->clientID,
                'CLIENTNAME'=>$request->client,
                // 'VENDORID'=>'0',
                // 'VENDORNAME'=>'',
                'Max_approverCount'=>'5',
                // 'GUID_GROUPS'=>'',
                'DoneApproving'=>'0',
                'WebpageLink'=>'so_approve.php',
                // 'ApprovedRemarks'=>'',
                'Payee'=>'',
                // 'CurrentSender'=>'0',
                // 'CurrentReceiver'=>'0',
                // 'NOTIFICATIONID'=>'0',
                // 'SENDTOID'=>'0',
                // 'NRN'=>'imported',
                // 'imported_from_excel'=>'0',
                // 'Amount'=>$request->projectCost,

            );
          }

        if ($array[0]['ORDERS'] == 0){
            $array[0]['USER_GRP_IND'] = 'Sales Reviewer';
            $array[0]['STATUS'] = 'In Progress';
        }

        if ($array[1]['ORDERS'] == 1){
            $array[1]['USER_GRP_IND'] = 'Approval of Project Head';
        }

        if ($array[2]['ORDERS'] == 2){
            $array[2]['USER_GRP_IND'] = 'Accounting Acknowledgement';
        }

        if ($array[3]['ORDERS'] == 3){
            $array[3]['USER_GRP_IND'] = 'MM Acknowledgement';
        }

        if ($array[4]['ORDERS'] == 4){
            $array[4]['USER_GRP_IND'] = 'Initiator';
        }



        DB::table('general.actual_sign')->insert($array);

        if($request->hasFile('file')){
            foreach($request->file as $file) {
                $completeFileName = $file->getClientOriginalName();
                $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $randomized = rand();
                $newFileName = str_replace(' ', '', $fileNameOnly).'-'.$randomized.''.time().'.'.$extension;
                // $path = '/uploads/attachments/'.$GUID;
                // $ref = $request->referenceNumber;
                $soNumber = str_replace('-', '_', $soNumber);
                // For moving the file
                $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                // For preview
                $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/SOF/".$soNumber;
                $symPath ="public/Attachments/SOF";
                $file->storeAs($destinationPath, $completeFileName);
                $fileDestination = $storagePath.'/'.$completeFileName; 
                
                $image = base64_encode(file_get_contents($file));

                
                DB::table('repository.so_attachment')->insert([
                    'REFID' => $salesOrderID,
                    'FileName' => $completeFileName,
                    'IMG' => $image,
                    'UID' => session('LoggedUser'),
                    'Ext' => $extension
                ]);


                $insert_doc = DB::table('general.attachments')->insert([

                    'INITID' => session('LoggedUser'),
                    'REQID' => $salesOrderID, 
                    'filename' => $completeFileName,
                    'filepath' => $storagePath, 
                    'fileExtension' => $extension,
                    'newFilename' => $newFileName,
                    'fileDestination'=>$destinationPath,
                    'formName' => 'Sales Order - POC',
                    'created_at' => date('Y-m-d H:i:s')
                    
                ]);
            }
        } 


        return back()->with('form_submitted', 'Your SOF POC request was successfully submitted.');
    }




    public function SofPending() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        $pendings = DB::select("SELECT ID,SOID, PO,REF 'REF',FRMNAME,CLIENTNAME 'Client',PROJECTNAME 'Project',AMOUNT 'ProjectCost' ,OUTSTANDING 'Outstanding',`STATUS` 'Status',DEADLINED 'Deadline',Initiator FROM sales_order.forapprovalso WHERE STATUS='PENDING'");
        return view('SalesOrderRequest.sof-pending', compact('posts','pendings'));
    }


        public function pendingApproved($id){
         
            DB::update("UPDATE sales_order.`forapprovalso` a SET a.`STATUS` = 'Approved'  WHERE a.`SOID` = $id ");
            DB::update("UPDATE sales_order.`sales_orders` a SET a.`Status` = 'In Progress'  WHERE a.`id` = $id ");
            DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'Not Started'  WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '1'  ");
            DB::update("UPDATE general.`actual_sign` a SET a.`STATUS` = 'In Progress'  WHERE a.`PROCESSID` = $id AND a.`FRM_CLASS` = 'SALES_ORDER_FRM' AND a.`COMPID` = '1' AND a.`ORDERS` = '0' ");

            return back()->with('form_submitted', 'The request is now In Progress.');
        }


    public function datatable(){
        return view('SalesOrderRequest.datatable');
    }
}
