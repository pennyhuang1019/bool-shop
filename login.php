<?php

define('ACC',true);
require('./include/init.php');

if(isset($_POST['act'])){//登陆之后的信息;因为在登录表单那里隐藏了一个input name='act';所以如果是已经进行提交，就会收到$_POST['act']
    $u = $_POST['username'];
    $p = $_POST['passwd'];
    $user = new userModel();

    if(!$user->checkUser($u)){
	$msg = "用户名不存在";
	require(ROOT. 'view/front/msg.html');
	exit;
    }

    
    $row = $user->checkUser($u,$p);
    if(empty($row)){
       $msg = '密码错误';      
    }else{
        $msg = "登陆成功";
        $_SESSION = $row; //session 里面有user_id,username,email

        if(isset($_POST['remember'])){
            setcookie('remuser',$_POST['username'],time()+14*24*3600); //如果选择记住登录名，那就把用户的名字放入cookie里面
        }else{
            setcookie('remuser','',0);
        }

    }

    include(ROOT . 'view/front/msg.html');


}else{//登陆界面
    $remuser = isset($_COOKIE['remuser'])?$_COOKIE['remuser']:''; //从cookie中读取用户名字
    include(ROOT . 'view/front/denglu.html');
}

















?>