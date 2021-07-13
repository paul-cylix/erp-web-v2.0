<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
class LayoutController extends Controller
{
    public function layoutNotif(){


        //  $key=Session::get('key');

        // return view('layouts.base',compact('key'));






    }


    public function exporttoCSV(){
        $posts = DB::select("call general.Display_Inprogress_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");


        if(!empty($posts)){

            $delimiter = ",";
            $filename = "in-progress_" . date('Y-m-d') . ".csv";

            $f = fopen('php://memory', 'w');

                //set column headers
            $fields = array('Due Date', 'Request Date', 'Request Type', 'Reference', 'Project', 'Business', 'Payee', 'Initiator', 'PO #', 'Remarks / Scope', 'Amount');
            fputcsv($f, $fields, $delimiter);

            foreach ($posts as $post) {
                

                $lineData = array($post->Date);
            }



        }






    }

















}
