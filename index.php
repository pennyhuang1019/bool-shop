<?php

define('ACC',true);
require("./include/init.php");

$goods = new goodsModel();

//取出新品
$newlist = $goods->getNew(5);

//取出指定栏目的商品
//取出女士大栏目下的商品
$female_id = 4 ;
$felist = $goods->cateGoods($female_id);

//取出男士大栏目下的商品
$man_id = 1 ;
$malist = $goods->cateGoods($man_id);

require(ROOT. "view/front/index.html");




?>