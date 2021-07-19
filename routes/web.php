<?php

use App\Http\Controllers\AccountingRequestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\HumanResourceRequestController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\MasterListRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OperationRequestController;
use App\Http\Controllers\PurchasingRequestController;
use App\Http\Controllers\SalesOrderRequestController;
use App\Http\Controllers\SupplyChainRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//login-route
// Route::get('/',[LoginController::class, 'login'])->name('auth.login');
Route::post('/auth/check',[LoginController::class,'check'])->name('auth.check');
Route::get('/auth/logout',[LoginController::class,'logouts'])->name('auth.logout');


// // Dashbaord
//  Route::get('/dashboard', [LoginController::class,'dashboard'])->name('admin.dashboard');

// //My Workflow Routes
// Route::get('/myworkflow/participants', [WorkflowController::class, 'participants']);
// Route::get('/myworkflow/inputs', [WorkflowController::class, 'inputs']);
// Route::get('/myworkflow/approvals', [WorkflowController::class, 'approvals']);
// Route::get('/myworkflow/in-progress', [WorkflowController::class, 'inProgress']);
// Route::get('/myworkflow/clarifications', [WorkflowController::class, 'clarification']);
// Route::get('/myworkflow/approved', [WorkflowController::class, 'approved']);
// Route::get('/myworkflow/withdrawn', [WorkflowController::class, 'withdrawn']);
// Route::get('/myworkflow/rejected', [WorkflowController::class, 'rejected']);


// // Accounting & Finance (Routes)
// Route::get('/accountingrequest/payment', [AccountingRequestController::class, 'createRfp']);
// Route::get('/accountingrequest/reimbursement', [AccountingRequestController::class, 'createReimbursement']);
// Route::get('/accountingrequest/pettycash', [AccountingRequestController::class, 'createPettyCash']);
// Route::get('/accountingrequest/cashadvance', [AccountingRequestController::class, 'createCashAdvance']);

// // Human Resource (Routes)
// Route::get('/humanresourcerequest/overtime', [HumanResourceRequestController::class, 'createOt']);
// Route::get('/humanresourcerequest/leave', [HumanResourceRequestController::class, 'createLeave']);
// Route::get('/humanresourcerequest/itinerary', [HumanResourceRequestController::class, 'createItinerary']);
// Route::get('/humanresourcerequest/incidentreport', [HumanResourceRequestController::class, 'createIncedentReport']);


// Middleware authentication check if users is logged in or not
Route::group(['middleware'=>['AuthCheck']], function(){
    // Login form route /index
    Route::get('/',[LoginController::class, 'login'])->name('auth.login');
    Route::get('/',[LoginController::class, 'getCompanyLists'])->name('get.companylists');


    // Dashbaord
    Route::get('/dashboard', [LoginController::class,'dashboard'])->name('admin.dashboard');

    
    // Route::get('/base', [LoginController::class,'layoutNotif'])->name('base.dashboard');


    
    // Notification Routes
    Route::get('/notification-status/',[NotificationController::class,'getLoggedUserNotif'])->name('notif.status');
    


    // Workflow Controller
    Route::get('/participants', [WorkflowController::class, 'getParticipants']);//no-entry
        // view participants by id 
        Route::get('/participants/{class}/{id}/{frmname}',[WorkflowController::class, 'getParticipantsByID'])->name(('part.single.post'));






    // Export to csv
    Route::get('/export-to-csv', [LayoutController::class, 'exporttoCSV'])->name('export.csv');





        

    Route::get('/inputs', [WorkflowController::class, 'getInputs']);
        // View by ID
        // Route::get('/inputs/{id}',[WorkflowController::class, 'getInputsByID'])->name(('npu.single.post'));
        Route::get('/inputs/{class}/{id}/{frmname}',[WorkflowController::class, 'getInputsByID'])->name(('npu.single.post'));

            // RE
            // Approve // Rejected
            Route::post('/inputs/approved-re',[WorkflowController::class, 'approvedRENpu'])->name(('npu.approved.re'));
            Route::post('/inputs/rejected-re',[WorkflowController::class, 'rejectedRENpu'])->name(('npu.rejected.re'));


            // RFP // Inputs
            // Approve, Clarify, Withdraw, 
            Route::post('/inputs/approved',[WorkflowController::class, 'approvedByIDRemarksInputs'])->name(('npu.approved.post'));
            Route::post('/inputs/clarify',[WorkflowController::class, 'clarifyBtnInputs'])->name(('npu.clarify.post'));
            Route::post('/inputs/reject',[WorkflowController::class, 'rejectBtnInputs'])->name(('npu.reject.post'));


            




    Route::get('/approvals', [WorkflowController::class, 'getApprovals']);
        // Route::get('/approval-status/{id}',[WorkflowController::class,'viewAppStatus'])->name('app.status');
        Route::get('/approval-status/{FRM_CLASS}/{id}',[WorkflowController::class,'viewAppStatus'])->name('app.status');

        // Route::get('/test/{FRM_CLASS}/{id}',[WorkflowController::class,'viewAppStatuss'])->name('app.statuss');


        
        // view by single id approvals
        // Route::get('/approvals/{id}',[WorkflowController::class, 'getApprovalByID'])->name(('app.single.post'));

        Route::get('/approvals/{class}/{id}/{frmname}',[WorkflowController::class, 'getApprovalByID'])->name(('app.single.post'));

            // RFP
            //Modal Approve, Rejected, Clarity bytton
            Route::post('/approvals/approved',[WorkflowController::class, 'approvedByIDRemarks'])->name(('app.approved.post'));

            Route::post('/approvals/save-files',[WorkflowController::class, 'saveFilesAndTable'])->name(('save.table.attachment'));

            Route::post('/approvals/rejected',[WorkflowController::class, 'rejectedByIDRemarks'])->name(('app.rejected.post'));
            
            Route::post('/approvals/clarity',[WorkflowController::class, 'clarificationByIDRemarks'])->name(('app.clarification.post'));


            // RE
            //Approve Reimbursement in Approvals by Reporting Manager 
            Route::post('/approvals/approved-re',[WorkflowController::class, 'approvedREApp'])->name(('app.approved.re'));
            //Rejected Reimbursement in Approvals by Reporting Manager 
            Route::post('/approvals/rejected-re',[WorkflowController::class, 'rejectedREApp'])->name(('app.rejected.re'));
            //Clarify Reimbursement in Approvals by Reporting Manager // Clarify also works with Inputs Clarification
            Route::post('/approvals/clarify-re',[WorkflowController::class, 'clarifyREApp'])->name(('app.clarify.re'));

            // PC
            // Approval
            Route::post('/approvals/approved-pc',[WorkflowController::class, 'approvedPCApp'])->name(('app.approved.pc'));
            // Initiator Part -- Approval
            Route::post('/approvals/approved-pc-init',[WorkflowController::class, 'approvedPCAppInit'])->name(('app.approved.pc.init'));
            // Rejected
            Route::post('/approvals/rejected-pc',[WorkflowController::class, 'rejectedPCApp'])->name(('app.rejected.pc'));
            // Clarify
            Route::post('/approvals/clarify-pc',[WorkflowController::class, 'clarifyPCApp'])->name(('app.clarify.pc'));




 

      



                
  






    Route::get('/in-progress', [WorkflowController::class, 'getInProgress']); 
        // view by single in progress post
        // Route::get('/in-progress/{id}',[WorkflowController::class, 'getInProgressByID'])->name(('inp.single.post'));
        // View by id single post
        Route::get('/in-progress/{class}/{id}/{frmname}',[WorkflowController::class, 'getInProgressByID'])->name(('inp.single.post'));

            // RFP withdraw in-progress Workflow
            Route::post('/approvals/withdraw',[WorkflowController::class, 'withdrawnByIDRemarks'])->name(('inp.withdraw.post'));
            // RE
            Route::post('/in-progress/withdraw',[WorkflowController::class, 'inpREWithdraw'])->name(('inp.withdraw.re'));
            // PC
            Route::post('/in-progress/withdraw-pc',[WorkflowController::class, 'inpPCWithdraw'])->name(('inp.withdraw.pc'));






    // Clarification
    Route::get('/clarifications', [WorkflowController::class, 'getClarification']);//no-entry
        //Clarification by Id
        Route::get('/clarifications/{class}/{id}/{frmname}',[WorkflowController::class, 'getClarificationByID'])->name(('cla.single.post'));
        // Get Comments
        // Route::get('/clarifications-comments/{id}',[WorkflowController::class,'viewClaComments'])->name('cla.comments');

        Route::get('/clarifications-comments/{FRM_CLASS}/{id}',[WorkflowController::class,'viewClaComments'])->name('cla.comments');




        // RFP
        // Edit Table
            // Modal Withdraw button with Remarks
            Route::post('/clarifications/withdraw',[WorkflowController::class, 'clarifyWithdrawBtnRemarks'])->name(('cla.withdraw.post'));

            Route::post('/clarifications/approve',[WorkflowController::class, 'clarifyApproveBtnRemarks'])->name(('cla.approve.post'));

            Route::post('/clarifications/reply',[WorkflowController::class, 'clarifyReplyBtnRemarks'])->name(('cla.reply.post'));

            Route::post('/clarifications/replynoedit',[WorkflowController::class, 'clarifyReplyBtnNoEdit'])->name(('cla.replynoedit.post'));

            Route::post('/clarifications/reject',[WorkflowController::class, 'clarifyRejectBtnRemarks'])->name(('cla.reject.post'));

            Route::post('/clarifications/saveEdit',[WorkflowController::class, 'saveEditable'])->name(('cla.saveEdit.post'));


        // RE
        // Reply button
            Route::post('/clarification/reply-re',[WorkflowController::class, 'claREReply'])->name('cla.reply.re');

            Route::post('/clarification/approved-re',[WorkflowController::class, 'claREapproved'])->name('cla.approved.re');


        // PC
        // Reply button
            Route::post('/clarification/reply-pc',[WorkflowController::class, 'claPCReply'])->name('cla.reply.pc');
        // Reply Initiator - no edit main table
            Route::post('/clarification/reply-pc-init',[WorkflowController::class, 'claPCReplyInit'])->name('cla.reply.pc.init');

        
        // Reply Clarification Approver
            Route::post('/clarification/reply-pc-apprvr',[WorkflowController::class, 'claPCReplyApprvr'])->name('cla.reply.pc.apprvr');


        // Withdraw
            Route::post('/clarification/withdraw-pc',[WorkflowController::class, 'claPCWithdraw'])->name(('cla.withdraw.pc'));

            






    // Approved
    Route::get('/approved', [WorkflowController::class, 'getApproved']);
        //Approved by Id
        // Route::get('/approved/{id}', [WorkflowController::class, 'getApprovedByID']);
        Route::get('/approved/{class}/{id}/{frmname}',[WorkflowController::class, 'getApprovedByID'])->name(('appd.single.re'));



    //Withdrawn
    Route::get('/withdrawn', [WorkflowController::class, 'getWithdrawn']);
        //Withdrawn by Id
        // Route::get('/withdrawn/{id}', [WorkflowController::class, 'getWithdrawByID']);

        Route::get('/withdrawn/{class}/{id}/{frmname}',[WorkflowController::class, 'getWithdrawByID'])->name(('wit.single.re'));


        


    // Rejected
    Route::get('/rejected', [WorkflowController::class, 'getRejected']);
        //Rejected by id
        // Route::get('/rejected/{id}', [WorkflowController::class, 'getrejectedByID']);
        
        Route::get('/rejected/{class}/{id}/{frmname}',[WorkflowController::class, 'getrejectedByID'])->name(('rej.single.re'));













    // Accounting Request Controller
    // RFP
        Route::get('/create-rfp', [AccountingRequestController::class, 'getReportingMgr']);
        Route::get('/get-client/{clientID}', [AccountingRequestController::class, 'getClientName']);
        Route::get('/get-ref', [AccountingRequestController::class, 'getReqRef']);


        Route::post('/create-rfp', [AccountingRequestController::class, 'saveRFP'])->name('save.rfp');
            Route::post('/create-rfp/upload-files',[AccountingRequestController::class, 'rfpUploadFiles'])->name('rfp.uploadfiles'); // Upload Files
            // Route::post('/create-rfp/store-files', [AccountingRequestController::class, 'storeFiles'])->name('store.files');
           

    // RE 
    Route::get('/create-re', [AccountingRequestController::class, 'createReimbursement']);

    Route::post('/create-re', [AccountingRequestController::class, 'saveRE'])->name('save.re');



    // PC
    Route::get('/create-pc', [AccountingRequestController::class, 'createPettyCash']);

    Route::post('/create-pc', [AccountingRequestController::class, 'savePC'])->name('save.pc');




    Route::get('/create-ca', [AccountingRequestController::class, 'createCashAdvance']);

    // HR Request Controller
    Route::get('/create-ot', [HumanResourceRequestController::class, 'createOt']);
    Route::get('/create-leave', [HumanResourceRequestController::class, 'createLeave']);
    Route::get('/create-itinerary', [HumanResourceRequestController::class, 'createItinerary']);
    Route::get('/create-ir', [HumanResourceRequestController::class, 'createIncedentReport']);

    // get clientname and clientid 
    Route::get('getClientNameAnd/{id}',[HumanResourceRequestController::class, 'getClient'])->name('get.client');
    // get OT date and time
    // Route::get('getotdatetime/{employeeID}',[HumanResourceRequestController::class, 'getotdatetime'])->name('get.ot.date.time');
    Route::get('getotdatetime/{employeeID}/{overtimeDate}/{authTimeStart}/{authTimeEnd}',[HumanResourceRequestController::class, 'getotdatetime'])->name('get.ot.date.time');

    
    
    // HR - OVER TIME ROUTES
    Route::post('/save-OT', [HumanResourceRequestController::class, 'saveOT'])->name('save.ot.post');
    Route::post('/withdraw-hr', [HumanResourceRequestController::class, 'withdrawHR'])->name('withdraw.hr');
    Route::post('/rejected-hr', [HumanResourceRequestController::class, 'rejectedHR'])->name('rejected.hr');
    Route::post('/approved-hr', [HumanResourceRequestController::class, 'approvedHR'])->name('approved.hr');
    Route::post('/approved-init', [HumanResourceRequestController::class, 'approvedInit'])->name('approved.init');
    Route::post('/clarify-hr', [HumanResourceRequestController::class, 'clarifyHR'])->name('clarify.hr');
    Route::post('/reply-hr', [HumanResourceRequestController::class, 'replyHR'])->name('reply.hr');
    Route::post('/approvedApprvr-hr', [HumanResourceRequestController::class, 'approvedApprvrHR'])->name('approvedApprvr.hr');
    Route::post('/rejectedApprvr-hr', [HumanResourceRequestController::class, 'rejectedApprvrHR'])->name('rejectedApprvr.hr');


    
    


    // HR - LEAVE DATE ROUTES
    Route::post('/save-leave', [HumanResourceRequestController::class, 'saveLeave'])->name('save.leave.post');
    Route::post('/withdraw-leave', [HumanResourceRequestController::class, 'withdrawLeave'])->name('withdraw.leave');
    Route::post('/rejected-leave', [HumanResourceRequestController::class, 'rejectedLeave'])->name('rejected.leave');
    Route::post('/approved-leave', [HumanResourceRequestController::class, 'approvedLeave'])->name('approved.leave');
    Route::post('/clarify-leave', [HumanResourceRequestController::class, 'clarifyLeave'])->name('clarify.leave');
    Route::post('/withdraw-init-leave', [HumanResourceRequestController::class, 'withdrawInitLeave'])->name('withdraw.init.leave');
    Route::post('/reply-init-leave', [HumanResourceRequestController::class, 'replyInitLeave'])->name('reply.init.leave');
    Route::post('/approvedApprvr-leave', [HumanResourceRequestController::class, 'approvedApprvrLeave'])->name('approvedApprvr.leave');
    Route::post('/rejectedApprvr-leave', [HumanResourceRequestController::class, 'rejectedApprvrLeave'])->name('rejectedApprvr.leave');







    // HR - ITINERARY REQUEST ROUTES
    Route::post('/save-itinerary', [HumanResourceRequestController::class, 'saveItinerary'])->name('save.itinerary.post');
    Route::post('/withdraw-itinerary', [HumanResourceRequestController::class, 'withdrawItinerary'])->name('withdraw.itinerary');
    


    




    // Master List Controller
    Route::get('/create-ce', [MasterListRequestController::class, 'createCustomerEntry']);
    Route::get('/create-ie', [MasterListRequestController::class, 'createItemEntry']);
    Route::get('/create-se', [MasterListRequestController::class, 'createSupplierEntry']);

    // Operations Controller
    Route::get('/create-lr', [OperationRequestController::class, 'createLaborResources']);

    // Purchasing Controller
    Route::get('/create-pr', [PurchasingRequestController::class, 'createPR']);
    Route::get('/create-po', [PurchasingRequestController::class, 'createPO']);
    Route::get('/create-dpo', [PurchasingRequestController::class, 'createDPO']);

    // Sales Order Controller
    Route::get('/create-sof-delivery', [SalesOrderRequestController::class, 'createSofDelivery']);

        // Save Delivery
        Route::post('saveDLV',[SalesOrderRequestController::class, 'saveDLV'])->name('post.saveDLV');






    Route::get('/create-sof-project', [SalesOrderRequestController::class, 'createSofProject']);

        // Get Address
        Route::get('getaddress/{id}',[SalesOrderRequestController::class, 'getAddress'])->name('get.address');

        // Get Contacts
        Route::get('getcontacts/{id}',[SalesOrderRequestController::class, 'getContacts'])->name('get.contacts');

        // Get Business List
        Route::get('getbusinesslist/{id}',[SalesOrderRequestController::class, 'getBusinessList'])->name('get.businesslist');

        // Route::get('/getbusinesslist/{id}',[SalesOrderRequestController::class, 'getBusinessList'])->name('get.businesslist');


        // Get Setup Project
        Route::get('getsetupproject/{id}',[SalesOrderRequestController::class, 'getSetupProject'])->name('get.setupproject');

        // Get Setup Project
        Route::get('getdelegates/{id}',[SalesOrderRequestController::class, 'getdelegates'])->name('get.delegates');

        // get Coordinator
        Route::get('getcoordinator/{id}/{coordID}',[SalesOrderRequestController::class, 'getcoordinator'])->name('get.coordinator');
        
        // Ajax insert System Name
        Route::post('postsystemname',[SalesOrderRequestController::class, 'postSystemName'])->name('post.systemname');

        // Ajax insert Document Name
        Route::post('postdocumentname',[SalesOrderRequestController::class, 'postDocumentName'])->name('post.documentname');

        // save prj
        Route::post('savePRJ',[SalesOrderRequestController::class, 'savePRJ'])->name('post.savePRJ');

        // withdaaw
        Route::post('/withdraw-sof', [WorkflowController::class, 'withdrawSOF'])->name('withdraw.sof');

        // approved
        Route::post('/approved-sof', [WorkflowController::class, 'approvedSOF'])->name('approved.sof');

        // rejected
        Route::post('/rejected-sof', [WorkflowController::class, 'rejectedSOF'])->name('rejected.sof');

        // clarify
        Route::post('/clarify-sof', [WorkflowController::class, 'clarifySOF'])->name('clarify.sof');

        // Reply
        Route::post('/reply-sof', [WorkflowController::class, 'replySOF'])->name('reply.sof');

        // Approved by Sender in Clarification
        Route::post('/approved-sof-by-sender', [WorkflowController::class, 'approvedSOFsender'])->name('approved.sof.sender');







    Route::get('/create-sof-demo', [SalesOrderRequestController::class, 'createSofDemo']);
    // save Demo
        Route::post('saveDMO',[SalesOrderRequestController::class, 'saveDMO'])->name('post.saveDMO');





    Route::get('/create-sof-poc', [SalesOrderRequestController::class, 'createSofPoc']);
    // save PCO 
        Route::post('savePOC',[SalesOrderRequestController::class, 'savePOC'])->name('post.savePOC');




    Route::get('/sof-pending', [SalesOrderRequestController::class, 'SofPending']);
    Route::get('/datatable', [SalesOrderRequestController::class, 'datatable'])->name('datatable.get');
    Route::get('pending-approved/{id}',[SalesOrderRequestController::class, 'pendingApproved'])->name('pending.approved');



    // Supply Chain Controller

        // Materials Request Controller
        Route::get('/create-mr-project', [SupplyChainRequestController::class, 'createMRProject']);
        Route::get('/create-mr-delivery', [SupplyChainRequestController::class, 'createMRDelivery']);
        Route::get('/create-mr-demo', [SupplyChainRequestController::class, 'createMRDemo']);

        // Assets Request Controller
        Route::get('/create-ar-project', [SupplyChainRequestController::class, 'createARProject']);
        Route::get('/create-ar-delivery', [SupplyChainRequestController::class, 'createARDelivery']);
        Route::get('/create-ar-demo', [SupplyChainRequestController::class, 'createARDemo']);
        Route::get('/create-ar-poc', [SupplyChainRequestController::class, 'createARPOC']);

        Route::get('/create-ar-internal', [SupplyChainRequestController::class, 'createARInternal']);
        Route::get('/create-ar-internal-ajax', [SupplyChainRequestController::class, 'createARInternalAjax']);
        Route::get('/create-ar-internal-details', [SupplyChainRequestController::class, 'createARInternaldetails']);

        Route::get('/ar-internal-listitem-details/{id}',[SupplyChainRequestController::class,'getListitemDetails']);

        Route::get('/mr-cart', [SupplyChainRequestController::class, 'mrCart']);
        


        // Supplies Request Controller
        Route::get('/create-sr-project', [SupplyChainRequestController::class, 'createSRProject']);
        Route::get('/create-sr-internal', [SupplyChainRequestController::class, 'createSRInternal']);

    
    Route::get('/sc-releasestocks', [SupplyChainRequestController::class, 'createReleaseStocks']);
    Route::get('/sc-rma', [SupplyChainRequestController::class, 'createRMA']);
});

// Route::get('/', [LoginController::class, 'getCompanyList']);


// Route::get('logout', '\App\Http\Controllers\LoginController@logout');

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('index');
// })->name('index'); 

// Route::get('/index', function () {
//     return view('index');
// });


// //Accounting Requests Routes
// Route::get('/create-rfp', function () {
//     return view('AccountingRequest.create-rfp');
// });
// Route::get('/create-rfp', [WorkflowController::class, 'getRFP_InitData']);
// Route::get('/get-client/{clientID}', [WorkflowController::class, 'getClientName']); paul edit
// Route::post('/submit-rfp', [WorkflowController::class, 'createRFP'])->name('wfm.createRFP');
// Route::get('/get-reference/{reqForm}', [WorkflowController::class, 'getReference']);

// Route::get('/create-re', function () {
//     return view('AccountingRequest.create-reimbursement');
// });

// Route::get('/create-pc', function () {
//     return view('AccountingRequest.create-pettycash');
// });

// Route::get('/create-ca', function () {
//     return view('AccountingRequest.create-cashadvance');
// });

// //HR Requests Routes
// Route::get('/create-ot', function () {
//     return view('HumanResourceRequest.create-ot');
// });

// Route::get('/create-leave', function () {
//     return view('HumanResourceRequest.create-leave');
// });

// Route::get('/create-incidentreport', function () {
//     return view('HumanResourceRequest.create-incidentreport');
// });

// Route::get('/create-itinerary', function () {
//     return view('HumanResourceRequest.create-itinerary');
// });


// //Sales Order Request Routes
// Route::get('/create-sof-project', function () {
//     return view('SalesOrderRequest.create-sof-project');
// });

// Route::get('/create-sof-delivery', function () {
//     return view('SalesOrderRequest.create-sof-delivery');
// });

// Route::get('/create-sof-poc', function () {
//     return view('SalesOrderRequest.create-sof-poc');
// });

// Route::get('/create-sof-demo', function () {
//     return view('SalesOrderRequest.create-sof-demo');
// });

// Route::get('/sof-pending', function () {
//     return view('SalesOrderRequest.sof-pending');
// });

// //Work Flow Manager updated // this is the same inside the middleware
// Route::get('/participants', [WorkflowController::class, 'getParticipants']);
// Route::get('/approvals', [WorkflowController::class, 'getApprovals']);
// Route::get('/inputs', [WorkflowController::class, 'getInputs']);
// Route::get('/in-progress', [WorkflowController::class, 'getInProgress']);
// Route::get('/clarifications', [WorkflowController::class, 'getClarification']);
// Route::get('/approved', [WorkflowController::class, 'getApproved']);
// Route::get('/withdrawn', [WorkflowController::class, 'getWithdrawn']);
// Route::get('/rejected', [WorkflowController::class, 'getRejected']);


// Route::get('/approved', function () {
//     return view('MyWorkflow.approved');
// });

// Route::get('/clarifications', function () {
//     return view('MyWorkflow.clarification');
// });

// Route::get('/in-progress', function () {
//     return view('MyWorkflow.in-progress');
// });

// Route::get('/inputs', function () {
//     return view('MyWorkflow.input');
// });

// Route::get('/participants', function () {
//     return view('MyWorkflow.participant');
// });

// Route::get('/rejected', function () {
//     return view('MyWorkflow.rejected');
// });

// Route::get('/withdrawn', function () {
//     return view('MyWorkflow.withdrawn');
// });

// Route::get('/users',[WorkflowController::class, 'getAllUsers'])->name('user.getallusers');