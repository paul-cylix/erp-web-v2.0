<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function getLoggedUserNotif(){
        $participantsPosts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        $participantsCount = count($participantsPosts);
    
        $inputsPosts = DB::select("call general.Display_Input_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        $inputsCount = count($inputsPosts);

        $approvalsPosts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        $approvalsCount = count($approvalsPosts);
   
        $inProgressPosts = DB::select("call general.Display_Inprogress_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        $inProgressCount = count($inProgressPosts);
     
        $clarificationPosts = DB::select("call general.Display_Clarification_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        $clarificationCount = count($clarificationPosts);
       
        $approvedPosts = DB::select("call general.Display_Completed_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        $approvedCount = count($approvedPosts);
     
        $withdrawnPosts = DB::select("call general.Display_withdrawn_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        $withdrawnCount = count($withdrawnPosts);

        $rejectedPosts = DB::select("call general.Display_Rejected_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        $rejectedCount = count($rejectedPosts);

        $myNotif = [
            "participantsCount" => $participantsCount,
            "inputsCount" => $inputsCount,
            "approvalsCount" => $approvalsCount,
            "inProgressCount" => $inProgressCount,
            "clarificationCount" => $clarificationCount,
            "approvedCount" => $approvedCount,
            "withdrawnCount" => $withdrawnCount,
            "rejectedCount" => $rejectedCount,
        ];

        return response()->json($myNotif);
        // dd($myNotif);
    }
}
