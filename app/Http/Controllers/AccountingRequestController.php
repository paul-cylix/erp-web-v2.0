<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestForPayment;
use Illuminate\Support\Facades\Auth;

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
}
