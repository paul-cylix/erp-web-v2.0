<?php

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
    return view('HumanResourceRequest.create-leave');
});