<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    //展示注册页面
    public function create()
    {
    	return view('users.create');
    }
}
