<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
	
	/**
	 * [create description]
	 * 
	 * @return [type] [description]
	 */
    public function create(){

    	return view('sessions.create');

    }


    /**
     * [store description]
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){

    	$credentials = $this->validate($request,[
    		'email'=>'required|email|max:255',
    		'password'=>'required'
    		]);
    	
    	if(Auth::attempt($credentials,$request->has('remember'))){

            //TODO: 登录成功后操作
            session()->flash('success','欢迎回来！');
            return redirect()->route('users.show',[Auth::user()]);

        }else{

            //TODO: 登录失败后操作          
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput($request->except('password'));

        }

    }
    /**
     * logout
     * 
     * @return [type] [description]
     */
    public function destroy(){

        Auth::logout();
        session()->flash('success','您已成功退出');
        return redirect('login');
    }

}