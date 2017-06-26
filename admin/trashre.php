<?php 





//file trash.php
header("content-type:text/html; charset=utf8");

define('ACC', true);
require('../include/init.php');

/*实例化model；
调用delete()方法；

*/

$id = $_GET['goods_id'];

$goods = new goodsModel();
$goodslist = $goods->recovery($id);
if($goodslist){
	echo '已将商品加入商品列表';
}else{
	echo '将商品加入商品列表失败';
}



?>