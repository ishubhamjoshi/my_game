<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        // if(Auth::check()){
        //     return redirect()->route('dashboard');
        // }
        return view('login.index');
    }

    public function loginUser(Request $request)
    {

        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);
        $loginData = $request->only('email', 'password');
        if (Auth::attempt($loginData)) {
            return redirect()->route('dashboard')->withSuccess('Welcome ' . Auth::user()->name);
        } else {
            return redirect()->route('login')->withError('Email Password Do Not Match ');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->withSuccess('Logged Out');
    }
}
