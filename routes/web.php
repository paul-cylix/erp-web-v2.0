<?php

use App\Http\Controllers\WorkflowController;
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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('logout', '\App\Http\Controllers\LoginController@logout');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('index');
})->name('index');

Route::get('/index', function () {
    return view('index');
});


//Accounting Requests Routes
Route::get('/create-rfp', function () {
    return view('AccountingRequest.create-rfp');
});

Route::get('/create-re', function () {
    return view('AccountingRequest.create-reimbursement');
});

Route::get('/create-pc', function () {
    return view('AccountingRequest.create-pettycash');
});

Route::get('/create-ca', function () {
    return view('AccountingRequest.create-cashadvance');
});


//HR Requests Routes
Route::get('/create-ot', function () {
    return view('HumanResourceRequest.create-ot');
});

Route::get('/create-leave', function () {
    return view('HumanResourceRequest.create-leave');
});

Route::get('/create-incidentreport', function () {
    return view('HumanResourceRequest.create-incidentreport');
});

Route::get('/create-itinerary', function () {
    return view('HumanResourceRequest.create-itinerary');
});


//Sales Order Request Routes
Route::get('/create-sof-project', function () {
    return view('SalesOrderRequest.create-sof-project');
});

Route::get('/create-sof-delivery', function () {
    return view('SalesOrderRequest.create-sof-delivery');
});

Route::get('/create-sof-poc', function () {
    return view('SalesOrderRequest.create-sof-poc');
});

Route::get('/create-sof-demo', function () {
    return view('SalesOrderRequest.create-sof-demo');
});

Route::get('/sof-pending', function () {
    return view('SalesOrderRequest.sof-pending');
});


//Work Flow Manager
Route::get('/approvals', [WorkflowController::class, 'getPost']);

Route::get('/approved', function () {
    return view('MyWorkflow.approved');
});

Route::get('/clarifications', function () {
    return view('MyWorkflow.clarification');
});

Route::get('/in-progress', function () {
    return view('MyWorkflow.in-progress');
});

Route::get('/inputs', function () {
    return view('MyWorkflow.input');
});

Route::get('/participants', function () {
    return view('MyWorkflow.participant');
});

Route::get('/rejected', function () {
    return view('MyWorkflow.rejected');
});

Route::get('/withdrawn', function () {
    return view('MyWorkflow.withdrawn');
});