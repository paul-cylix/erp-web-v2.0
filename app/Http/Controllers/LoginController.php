<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 

class LoginController extends Controller
{
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }

    public function getCompanyList() {

    }
}
