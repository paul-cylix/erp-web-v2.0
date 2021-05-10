<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterListRequestController extends Controller
{
    //Master List
    public function createCustomerEntry() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('MasterListRequest.create-customerentry', compact('posts'));
    //return $posts;
    }

    public function createItemEntry() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('MasterListRequest.create-itementry', compact('posts'));
        //return $posts;
    }

    public function createSupplierEntry() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('MasterListRequest.create-supplierentry', compact('posts'));
        //return $posts;
    }
}
