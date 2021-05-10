<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesOrderRequestController extends Controller
{
    //
    public function createSofDelivery() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('SalesOrderRequest.create-sof-delivery', compact('posts'));
    //return $posts;
    }

    public function createSofProject() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('SalesOrderRequest.create-sof-project', compact('posts'));
    //return $posts;
    }

    public function createSofDemo() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('SalesOrderRequest.create-sof-demo', compact('posts'));
    //return $posts;
    }

    public function createSofPoc() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('SalesOrderRequest.create-sof-poc', compact('posts'));
    //return $posts;
    }

    public function SofPending() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('SalesOrderRequest.sof-pending', compact('posts'));
    //return $posts;
    }
}
