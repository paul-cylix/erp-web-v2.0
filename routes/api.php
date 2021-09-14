<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\RFP;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AccountingRequestController;

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
