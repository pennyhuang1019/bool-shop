<?php

define('ACC',true);
require("./include/init.php");


$goods = new goodsModel();

$goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;

$g = $goods->find($goods_id);

if(empty($g)){
    header('location:index.php');
    exit;
}

//面包屑导航
$cat = new cateModel();
$nav = $cat->getditTree($g['cat_id']);


require(ROOT. "view/front/shangpin.html");