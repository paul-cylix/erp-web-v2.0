<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // public function logout() {
    //     Auth::logout();
    //     $companyList = DB::select("SELECT title_id, title_name FROM general.`project_title` WHERE `status` = 'Active'");
    //     return view('auth.login', compact('companyList'));
    // }

    // public function getCompanyList() {
    //     $companyList = DB::select("SELECT title_id, title_name FROM general.`project_title` WHERE `status` = 'Active'");
    //     return view('/', compact('companyList'));
    // }

    public function getCompanyLists(){
            $companyList = DB::select("SELECT title_id, title_name FROM general.`project_title` WHERE `status` = 'Active'");
            return view('auth.login', compact('companyList'));
    }

    // Login
    function login(){
        return view('auth.login');
    }
    // login check
    function check(Request $request){
        
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:8|max:25'
        ]);

        // $userInfo = User::where('email','=',$request->email)->first();


        $userInfo = DB::table('erpweb.users as a')
        ->join('humanresource.employees as b', 'b.SysPK_Empl', '=', 'a.employee_id')        
        ->select(['b.SysPK_Empl','b.CompanyID','a.id','a.name','a.password','a.email'])
        ->where('a.email',$request->email)
        ->where('b.CompanyID',$request->company)
        ->first();

        // $userInfo_Full = DB::select("call erpweb.getUserLoggedInfo('" . $userInfo->id . "','" .$request->company. "')");
            $userInfo_Full = DB::select("call erpweb.getUserLoggedInfo('" . $userInfo->id . "')");
        

        if(!$userInfo){
            return back()->with('fail','We do not recognize your email address');
        }else{

            // $userInfo_Full = DB::select("call erpweb.getUserLoggedInfo('" . $userInfo->id . "')");

            //check password /removing hash
            if(Hash::check($request->password, $userInfo->password)){
                $request->session()->put('LoggedUser',$userInfo->id);
                $request->session()->put('LoggedUserName',$userInfo->email);                
                $request->session()->put('LoggedUser_FullName',$userInfo_Full[0]->employeeName);
                $request->session()->put('LoggedUser_FirstName',$userInfo_Full[0]->employeeFName);
                $request->session()->put('LoggedUser_LastName',$userInfo_Full[0]->employeeLName);
                $request->session()->put('LoggedUser_CompanyID',$userInfo_Full[0]->companyID);
                $request->session()->put('LoggedUser_CompanyName',$userInfo_Full[0]->companyName);
                $request->session()->put('LoggedUser_DepartmentName',$userInfo_Full[0]->departmentName);
                $request->session()->put('LoggedUser_PositionName',$userInfo_Full[0]->positionName);
                // $request->session()->put('img',$userInfo_Full[0]->IMG);






                return redirect('dashboard');

            }else{
                return back()->with('fail','Incorrect Password');
            }
        }
    }

    function logouts(){
        if(session()->has('LoggedUser')){
            session()->pull('LoggedUser');
            return redirect('/');
        }
    }

    function dashboard(){

        // $participantsPosts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        // $participantsCount = count($participantsPosts);
        // session()->put('participantsCount',$participantsCount);

        // $inputsPosts = DB::select("call general.Display_Input_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        // $inputsCount = count($inputsPosts);
        // session()->put('inputsCount',$inputsCount);

        // $approvalsPosts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        // $approvalsCount = count($approvalsPosts);
        // session()->put('approvalsCount',$approvalsCount);

        // $inProgressPosts = DB::select("call general.Display_Inprogress_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        // $inProgressCount = count($inProgressPosts);
        // session()->put('inProgressCount',$inProgressCount);

        // $clarificationPosts = DB::select("call general.Display_Clarification_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        // $clarificationCount = count($clarificationPosts);
        // session()->put('clarificationCount',$clarificationCount);

        // $approvedPosts = DB::select("call general.Display_Completed_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        // $approvedCount = count($approvedPosts);
        // session()->put('approvedCount',$approvedCount);

        // $withdrawnPosts = DB::select("call general.Display_withdrawn_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        // $withdrawnCount = count($withdrawnPosts);
        // session()->put('withdrawnCount',$withdrawnCount);

        // $rejectedPosts = DB::select("call general.Display_Rejected_Company_web('%', '" . session('LoggedUser') . "','', '1', '2020-01-01', '2020-12-31', 'True')");
        // $rejectedCount = count($rejectedPosts);
        // session()->put('rejectedCount',$rejectedCount);

        $data = ['LoggedUserInfo'=>User::where('id','=',session('LoggedUser'))->first()];
        return view('index', $data);
    }

    
   
}