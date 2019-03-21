<?php

namespace App\Http\Controllers\Login;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //登录视图
    public function loginView(){
        return view("login.login");
    }
    public function doLogin(Request $request){
        $email=$request->input('email');
        $pwd=$request->input('password');
        $where=[
            'email'=>$email
        ];
        $userInfo=UserModel::where($where)->first();
        if(empty($userInfo)){
            echo "登录失败";
        }elseif(md5($pwd)!=$userInfo['password']){
            echo "登录失败";
        }else{
            echo "登录成功";
        }
    }
    //注册视图
    public function regView(){
        return view("login.reg");
    }
    //注册
    public function doReg(Request $request){
        $name=$request->input('name');
        $email=$request->input('email');
        $re_pwd=$request->input('re_pwd');
        $pwd=$request->input('password');
        if($re_pwd!=$pwd) {
            echo "密码与确认密码一致";
            exit;
        }
        $new_pwd=md5($pwd);
        $data['password']=$new_pwd;
        $data['name']=$name;
        $data['email']=$email;
        $id=UserModel::insertGetId($data);
        if($id){
            echo "注册成功";
        }else{
            echo "注册失败";
        }
    }
}
