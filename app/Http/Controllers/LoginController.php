<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function logout() {
        Auth::logout();
        $companyList = DB::select("SELECT title_id, title_name FROM general.`project_title` WHERE `status` = 'Active'");
        return view('auth.login', compact('companyList'));
    }

    public function getCompanyList() {
        $companyList = DB::select("SELECT title_id, title_name FROM general.`project_title` WHERE `status` = 'Active'");
        return view('auth.login', compact('companyList'));
    }
}