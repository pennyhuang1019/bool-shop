<?php 


//file catedit.php
header("content-type:text/html; charset=utf8");

define('ACC', true);
require('../include/init.php');

//接收数据
//print_r($_POST);

//print_r($_FILES); //接收数据成功，文件上传就存在全局变量$_FILES;

//检验数据
/*
$data = array();
if(empty($_POST['goods_name'])){
	echo "商品名称不能为空";
	exit;
}
$data['goods_name'] = trim($_POST['goods_name']);
//同理检验其他数据
$data['goods_sn'] = trim($_POST['goods_sn']);
$data['cat_id'] = $_POST['cat_id'] + 0;
$data['shop_price'] = $_POST['shop_price'] + 0;
$data['market_price'] = $_POST['market_price'] + 0;
$data['goods_weight'] = $_POST['goods_weight'] * $_POST['weight_unit'];
$data['goods_desc'] = $_POST['goods_desc'];
$data['goods_brief'] = trim($_POST['goods_brief']);
$data['is_best'] = isset($_POST['is_best'])?1:0;
$data['is_new'] = isset($_POST['is_new'])?1:0;
$data['is_hot'] = isset($_POST['is_hot'])?1:0;
$data['is_on_sale'] = isset($_POST['is_on_sale'])?1:0;
$data['add_time'] = time( );
*/
$_POST['goods_weight'] *= $_POST['weight_unit'];

$data = array(); //用来存放接收过来的数据

$goodsadd = new goodsModel(); //实例化model

$data = $goodsadd->_facade($_POST); //自动过滤

$data = $goodsadd->_autoFill($data);//自动填充


if(!$goodsadd->_validate($data)){  //自动验证
	echo "数据不合法<br/>";
	echo implode(',',$goodsadd->getErr());
	exit;
}

//自动生成商品货号
if(empty($data['goods_sn'])){
	$data['goods_sn'] = $goodsadd->createSn();
}

//上传图片
$uppic = new UpTool();
if(!$ori_img = $uppic->up('ori_img')){
	echo '图片上传失败';
	echo $uppic->geterror();
	exit;
}

$data['ori_img'] = $ori_img;

//上传缩略图
if($ori_img){
	$ori_img = ROOT . $ori_img; //转换成绝对路径
	$goods_img = dirname($ori_img) . '/goods_' . basename($ori_img);

	//上传中等图片 300*400
	if(ImageTool::thumb_img($ori_img,$goods_img,300,400)){
		$data['goods_img'] = str_replace(ROOT, '', $goods_img);
	}

	//上传更小的缩略图 160*220
	$thumb_img = dirname($ori_img) . '/thumb_' . basename($ori_img);
	if(ImageTool::thumb_img($ori_img,$thumb_img,160,220)){
		$data['thumb_img'] = str_replace(ROOT, '', $thumb_img);
	}
}

//调用add方法存入数据

if($goodsadd->add($data)){
	echo '商品添加成功';
}else{
	echo '商品添加失败';
}















//调用model模型，将数据入库








?>