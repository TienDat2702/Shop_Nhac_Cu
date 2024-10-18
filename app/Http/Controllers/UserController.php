<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login() {
        
        return view('login');
    }
    public function dologin(Request $request) {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
       
        
    }
    public function register(){


        
        return view('register');
    }
}
