<?php 


define('ACC', true);
require('./include/init.php');

$user = new userModel();
$data = array();

//自动验证
if(!$user->_validate($_POST)){
	$msg = implode('<br />',$user->getErr());
	require(ROOT. 'view/front/msg.html');
	exit;
}

//检验用户名是否已经存在
if($user->checkUser($_POST['username'])){
	$msg = "用户名已经存在";
	require(ROOT. 'view/front/msg.html');
	exit;
}

//自动过滤
$data = $user->_facade($_POST);

//自动填充
$data = $user->_autoFill($data);

if($user->reg($data)){
	$msg = '用户注册成功';
}else{
	$msg = '用户注册失败';
}

require(ROOT. 'view/front/msg.html');





?>