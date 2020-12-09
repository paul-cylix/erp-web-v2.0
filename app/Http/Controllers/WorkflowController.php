<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class WorkflowController extends Controller
{
    public function getPost() {
        $posts = DB::select("call general.Display_Approver_Company_1('%', '11', '%', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('MyWorkflow.approval', compact('posts'));
        //return $posts;
    }
}
