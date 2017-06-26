<?php 





header("content-type:text/html; charset=utf-8");

define('ACC', true);
require('../include/init.php');

//接收数据
//print_r($_POST);

$data = array();

//检验数据
if(empty($_POST['cat_name'])){
	exit('栏目名称不能为空');
}
$data['cat_name'] = $_POST['cat_name'];

if(($_POST['parent_id']) === null){
	exit('上级目录不能为空');
}

$data['parent_id'] = $_POST['parent_id'];

if(empty($_POST['intro'])){
	exit('栏目简介不能为空');
}
$data['intro'] = $_POST['intro'];


//实例化model,将数据输入数据库
$cateaddAct = new cateModel();
if($cateaddAct->add($data)){
	echo'栏目添加成功';
	exit;
}else{
	echo '栏目添加失败';
	exit;
}
	







?>