<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{  

    /**
     * [__construct description]
     */
    public function __construct(){
        $this->middleware('auth',[
            'except'=>['show','create','store','index','confirmEmail']
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

        $statuses = $user->statuses()
                         ->orderBy('created_at','desc')
                         ->paginate(30);

    	return view('users.show',compact('user','statuses'));

    }


    /**
     * 显示所有用户列表
     * 
     * @return [type] [description]
     */
    public function index(){
    	
        $users = User::paginate(10);
        return view('users.index',compact('users'));
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

        //发送激活邮件
        $this -> sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已经发送到你的注册邮箱上，请注意查收。');
    	return redirect('/');

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
     public function destroy(User $user){

        $this->authorize('destroy',$user);

    	$user->delete();

        session()->flash('success','成功删除用户');

        return back();

    }

    /**
     * [sendEmailConfirmationTo description]
     * 
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    protected function sendEmailConfirmationTo($user){

        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'root@email.com';
        $name = 'root';
        $to = $user->email;
        $subject = "感谢注册sample应用！ 请确认你的邮箱。";

        Mail::send($view,$data,function($message) use($from,$name,$to,$subject){

            $message->
            //from($from,$name)->  配置在.env中  MAIL
            to($to)->subject($subject);
        });
    }

    /**
     * [confirmEmail description]
     * 
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    public function confirmEmail($token){

        $user = User::where('activation_token',$token)->firstOrFail();

        if($token != $user->activation_token){

            session()->flash('warning','对不起，激活失败......');

            return redirect('/');

        }
        $user->activated = true;
        $user->activation_token = '';
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功！');
        return redirect()->route('users.show',[$user]);
    }


    public function followings(User $user){

        $users = $user->followings()->paginate(30);
        $title = '关注的人';
        return view('users.show_follow',compact('users','title'));
    }



    public function followers(User $user){

        $users = $user->followers()->paginate(30);
        $title = '粉丝';
        return view('users.show_follow',compact('users','title'));
    }
    
}
