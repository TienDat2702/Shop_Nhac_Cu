<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(){
        return view('user.login');
    }
    public function register(){
        return view('user.register');
    }
}
