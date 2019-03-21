<?php

namespace App\Http\Controllers\Login;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

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
            $info=[
                'code'=>0,
                'msg'=>'登录失败',
            ];
            echo json_encode($info);
        }elseif(md5($pwd)!=$userInfo['password']){
            $info=[
                'code'=>0,
                'msg'=>'登录失败',
            ];
            echo json_encode($info);
        }else{
            $info=[
                'code'=>1,
                'msg'=>'登录成功',
            ];
            echo json_encode($info);
            $key="token:".$userInfo->id;
            $token=substr(md5(time().rand(0,99999)),10,10);
            setcookie('uid',$userInfo->id,time()+60*60*24,'/','tactshan.com',false,true);
            setcookie('token',$token,time()+86400,'/','tactshan.com',false,true);
            Redis::set($key,$token);
            Redis::expire($key,86400);



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