<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Loginmodal;
use Illuminate\Support\Facades\DB;

use App\Models\Loginmodal as Login;
use App\Http\Controllers\Session;

class LoginController extends Controller
{
    //
    public function index()
    {
        return view('login/login');
    }

    public function loginCheck(Request $request)
    {
        // $request->validate([
        //     'email' => 'required',
        //     'password' => 'required'
        // ]);
        // print_r($request->input());
        
        // $list =  Login::getUserData('2');

        // return view('dashboard/dashboard', ['user'=>$list]);
        $data = $request->input();
        $userlogin = $request->username;
        $userpassword = $request->password;
        
        $data = DB::table('admin')->where('email',$userlogin)->where('password',$userpassword)->get();

        if($data->count() > 0)
        {
            $data = $data->first();
            $request->session()->put('userid',$data->id);
            $request->session()->put('userrole',$data->type);
            // return view('dashboard/dashboard');
            return redirect('/dashboard');
        }
        else
        {
            return back();
        }

    }

    public function logout()
    {
      
       session()->pull('userid');
       session()->pull('userrole');
       session()->flush();

       return redirect('/');

    }

    public function dashboard()
    {
        // print_r(session()->get('userid'));
        if (session()->has('userid'))
        {
            return view('dashboard/dashboard');
        }
        else {
            return redirect('/');
        }
    }
}
