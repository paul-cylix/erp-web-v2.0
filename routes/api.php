<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\RFP;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AccountingRequestController;
use App\Http\Controllers\TheApiController;
use App\Http\Controllers\WorkflowController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/in-progress', function(){
//     // return RFP::all();
    
// });

Route::get('/in-progress',  [WorkflowController::class, 'getInProgress']);

Route::get('createRFP', [AccountingRequestController::class, 'createRFP']);

Route::get('managers', [AccountingRequestController::class, 'getManagers']);
Route::get('projects', [AccountingRequestController::class, 'getProjects']);
Route::get('expense-type', [AccountingRequestController::class, 'getExpenseType']);
Route::get('currency-type', [AccountingRequestController::class, 'getCurrencyType']);


Route::post('register-user', [TheApiController::class, 'saveUserAttendance']);




