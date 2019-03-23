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
        $url=empty($_GET['url'])?'':$_GET['url'];
        return view("login.login",['url'=>$url]);
    }
    public function doLogin(Request $request){
        $data=$_POST;
        $pwd=$data['pwd'];
        $where=[
            'email'=>$data['email']
        ];
        $userInfo=UserModel::where($where)->first();
        if(empty($userInfo)){
            $info=[
                'code'=>40001,
                'msg'=>'登录失败',
            ];
            echo json_encode($info);
        }elseif(md5($pwd)!=$userInfo['password']){
            $info=[
                'code'=>40002,
                'msg'=>'登录失败',
            ];
            echo json_encode($info);
        }else{
            $key="token:app:".$userInfo->id;
            $token=substr(md5(time().rand(0,99999)),10,10);
            setcookie('uid',$userInfo->id,time()+60*60*24,'/','tactshan.com',false,true);
            setcookie('token',$token,time()+86400,'/','tactshan.com',false,true);
            Redis::set($key,$token);
            Redis::expire($key,86400);
            $info=[
                'code'=>1,
                'msg'=>'登录成功',
                'token'=>$token
            ];
            echo json_encode($info);



        }
    }
    public function pcLogin(Request $request){
        $email=$request->input('email');
        $url=$request->input('url');
        $pwd=$request->input('password');
        $where=[
            'email'=>$email
        ];
        $userInfo=UserModel::where($where)->first();
        if(empty($userInfo)){
            $info=[
                'code'=>40001,
                'msg'=>'登录失败',
            ];
            echo json_encode($info);
        }elseif(md5($pwd)!=$userInfo['password']){
            $info=[
                'code'=>40002,
                'msg'=>'登录失败',
            ];
            echo json_encode($info);
        }else{

            $key="token:pc:".$userInfo->id;
            $token=substr(md5(time().rand(0,99999)),10,10);
            setcookie('uid',$userInfo->id,time()+60*60*24,'/','tactshan.com',false,true);
            setcookie('token',$token,time()+86400,'/','tactshan.com',false,true);
            Redis::set($key,$token);
            Redis::expire($key,86400);
            $info=[
                'code'=>1,
                'msg'=>'登录成功',
                'url'=>$url,
                'token'=>$token
            ];
            echo json_encode($info);



        }
    }
    //注册视图
    public function regView(){
        $url=empty($_GET['url'])?'':$_GET['url'];
        return view("login.reg",['url'=>$url]);
    }
    //注册
    public function doReg(Request $request){
        $name=$request->input('name');
        $email=$request->input('email');
        $url=$request->input('url');
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
            $info=[
                'code'=>1,
                'msg'=>'注册成功',
                'url'=>$url
            ];
            echo json_encode($info);
            $key="token:".$id;
            $token=substr(md5(time().rand(0,99999)),10,10);
            setcookie('uid',$id,time()+60*60*24,'/','tactshan.com',false,true);
            setcookie('token',$token,time()+86400,'/','tactshan.com',false,true);
            Redis::set($key,$token);
            Redis::expire($key,86400);

        }else{
            $info=[
                'code'=>0,
                'msg'=>'注册失败',
            ];
            echo json_encode($info);
        }
    }


    //pc退出
    public function pcQuit(Request $request){
        $uid=$_GET['uid'];
        $key='token:pc:'.$uid;
        Redis::del($key);
        setcookie('token','',time()-3600,'/','tactshan.com',false,true);
        echo "退出成功";
        header("refresh:2;url='http://kings.tactshan.com'");
    }

}
