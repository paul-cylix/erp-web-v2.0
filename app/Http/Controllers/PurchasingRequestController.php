<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasingRequestController extends Controller
{
    public function createPR() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('PurchasingRequest.create-pr', compact('posts'));
    //return $posts;
    }

    public function createPO() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('PurchasingRequest.create-po', compact('posts'));
    //return $posts;
    }

    public function createDPO() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('PurchasingRequest.create-dpo', compact('posts'));
    //return $posts;
    }
}
