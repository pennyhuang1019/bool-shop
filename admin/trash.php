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
$goodslist = $goods->delete($id);
if($goodslist){
	echo '删除成功';
}else{
	echo '删除失败';
}



?>