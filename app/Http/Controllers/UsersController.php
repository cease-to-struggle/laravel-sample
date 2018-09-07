<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    //展示注册页面
    public function create()
    {
    	return view('users.create');
    }

    //展示用户个人信息
    public function show(User $user)
    {
    	return view('users.show',compact('user'));
    }
    //显示所有用户列表
    public function index()
    {
    	
    }
    //创建用户(存储)
     public function store(Request $request)
    {
        //验证请求数据
    	$this->validate($request,[
    		'name'=>'required|max:50',
    		'email'=>'required|email|unique:users|max:255', 
    		'password'=>'required|confirmed|min:6'
    		]);

        //数据存储
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            ]);

        //消息提醒
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
    	return redirect()->route('users.show',[$user->id]);
    }
    //编辑个人资料的页面
     public function edit()
    {
    	echo '4';
    }
    //更新用户
     public function update()
    {
    	echo '5';
    }
    //删除用户
     public function destory()
    {
    	echo '6';
    }
}
