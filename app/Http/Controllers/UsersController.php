<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{  

    /**
     * [__construct description]
     */
    public function __construct(){
        $this->middleware('auth',[
            'except'=>['show','create','store']
            ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    /**
     * 展示注册页面
     * @return [type] [description]
     */
    public function create(){

    	return view('users.create');

    }


    /**
     * 展示用户个人信息
     *
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function show(User $user){

    	return view('users.show',compact('user'));

    }


    /**
     * 显示所有用户列表
     * 
     * @return [type] [description]
     */
    public function index(){
    	

    }


    /**
     * 创建用户(存储)
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
     public function store(Request $request){

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
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
    	return redirect()->route('users.show',[$user->id]);

    }

    
    /**
     * 编辑个人资料的页面
     * 
     * @return [type] [description]
     */
     public function edit(User $user){

        try{
            $this->authorize('update',$user);
        }catch(\Exception $e){
            session()->flash('danger','Invalid Operation');
            redirect()->back();
        }

    	return view('users.edit',compact('user'));

    }


    /**
     * 更新用户
     * 
     * @return [type] [description]
     */
     public function update(User $user,Request $request){

    	$this->validate($request,[
            'name'=>'required|max:50', 
            'password'=>'nullable|confirmed|min:6'
            ]);

        try{
            $this->authorize('update',$user);
        }catch(\Exception $e){
            session()->flash('danger','Invalid Operation');
            redirect()->back();
        }

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = $request->password;
        }

        $user->update($data);

        session()->flash('success','个人资料更新成功');

        return redirect()->route('users.show',$user->id);

    }
 


    /**
     * 删除用户
     * 
     * @return [type] [description]
     */
     public function destory(){

    	echo '6';

    }
    
}
