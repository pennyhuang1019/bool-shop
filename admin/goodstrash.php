<?php 

//file goods.php 
header("content-type:text/html; charset=utf8");

define('ACC', true);
require('../include/init.php');

/*接收$id
实例化model；
调用方法find（）；

*/

if( isset($_GET['act']) && $_GET['act'] == 'show' ){
	//这个部分是打印商品回收站里面的商品
	$goods = new goodsModel();
	$goodslist = $goods->getTrash();
	require(ROOT . 'view/admin/templates/trashlist.html');


}else{
	$id = $_GET['goods_id'] + 0;

	$goods = new goodsModel();
	$trash = $goods->trash($id);

	if($trash){
		echo '加入回收站成功';
	}else{
		echo "加入回收站失败";
	}

}





?>