<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            input{
                width:300px;
                height:30px;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <input type="hidden" id="url" value="{{$url}}">
        <div style="width:1200px; height:50px;"><h1 align="center">登录页面</h1></div>
        <div style="width:500px;height:500px; border:1px solid black; float: left;">
            <h1 align="center">欢迎登录</h1>
            <div align="center" style="margin-top:150px;">
                <p >
                    <font color="red">邮箱</font>： <input type="text" id="email">
                </p>
                <p>
                    <font color="red">密码</font>：&nbsp;&nbsp;&nbsp;<input type="password" id="password">
                </p>
                <p>
                    <button style="width:100px;height:30px" id="login"><font color="blue">立即登录</font></button>
                    <button style="width:100px;height:30px" id="reg"><font color="blue">我要注册</font></button>
                </p>
            </div>
        </div>
        <div style="width:0px;height:500px; border:1px solid red; float:left; margin-left: 100px;">

        </div>
        <div style="width:500px;height:500px; border:1px solid red; float:left; margin-left: 100px;">
                <h3 align="center">
                    <p>版</p>
                    <p>权</p>
                    <p>所</p>
                    <p>有</p>
                    <p>,</p>
                    <p>侵</p>
                    <p>权</p>
                    <p>必</p>
                    <p>究</p>
                    <p>!</p>
                </h3>
        </div>
    </body>
</html>
<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<script>
    $(function(){
        $('#reg').click(function(){
            window.location="/reg";
        });
        $('#login').click(function(){
            var email=$('#email').val();
            var url=$('#url').val();
            var password=$('#password').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"/pclogin",
                data:{email:email,url:url,password:password},
                method:'POST',
                success:function(msg){
                    alert(msg.msg);
                    if(msg.code==1){
                        window.location=""+msg.url+"";
                    }
                },
                dataType:'json'

            });
        })
    })
</script>
