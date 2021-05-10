<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HumanResourceRequestController extends Controller
{
    // HumanResource
    public function createOt() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('HumanResourceRequest.create-ot', compact('posts'));
        //return $posts;
    }

    public function createLeave() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('HumanResourceRequest.create-leave', compact('posts'));
        //return $posts;
    }

    public function createItinerary() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('HumanResourceRequest.create-itinerary', compact('posts'));
        //return $posts;
    }

    public function createIncedentReport() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('HumanResourceRequest.create-incidentreport', compact('posts'));
        //return $posts;
    }

    // function createOt(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('HumanResourceRequest.create-ot', $data);
    // }

    // function createLeave(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('HumanResourceRequest.create-leave', $data);
    // }

    // function createItinerary(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('HumanResourceRequest.create-itinerary', $data);
    // }

    // function createIncedentReport(){
    //     $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
    //     return view('HumanResourceRequest.create-incidentreport', $data);
    // }
}
