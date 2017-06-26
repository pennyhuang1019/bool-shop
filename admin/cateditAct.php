<?php 





header("content-type:text/html; charset=utf-8");

define('ACC', true);
require('../include/init.php');

//接收数据


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

$cat_id = $_POST['cat_id'] + 0;

//实例化model,将数据输入数据库
$cateditAct = new cateModel();

//查找新父栏目的家谱树
$trees = $cateditAct->getditTree($data['parent_id']);

//判断栏目是否是在新父栏目的家谱树中
$flag = true;
foreach ($trees as $v) {
	if($v['cat_id'] == $cat_id){
		$flag = false;
	}
}

if(!$flag){
	echo '上级栏目选取错误，不能修改';
}
exit;


if($cateditAct->update($data,$cat_id)){
	echo'栏目编辑成功';
	exit;
}else{
	echo '栏目编辑失败';
	exit;
}
	







?>