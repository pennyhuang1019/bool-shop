<?php 

//file goodslist.php
header("content-type:text/html; charset=utf8");

define('ACC', true);
require('../include/init.php');

/*实例化model；
调用select()方法；
用foreach（）
到view中渲染
*/

$goods = new goodsModel();
$goodslist = $goods->getGoods();
require(ROOT . 'view/admin/templates/goodslist.html');


?>