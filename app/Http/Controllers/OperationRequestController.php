<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationRequestController extends Controller
{
    public function createLaborResources() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('OperationRequest.create-laborresources', compact('posts'));
    //return $posts;
    }
}
