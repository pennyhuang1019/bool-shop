<?php 



//file goods.php 
header("content-type:text/html; charset=utf8");

define('ACC', true);
require('../include/init.php');

/*接收$id
实例化model；
调用方法find（）；

*/
$id = $_GET['goods_id'];

$goods = new goodsModel();
$goodslist = $goods->find($id);
print_r($goodslist);



?>