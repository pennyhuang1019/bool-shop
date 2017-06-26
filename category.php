<?php

define('ACC',true);
require("./include/init.php");

$cat = new cateModel();

$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']+0:0; //从地址栏上获取cat_id;
$page = isset($_GET['page'])?$_GET['page']+0:1; //从地址栏上获取cat_id;

if($page<1){
    return $page = 1;
}

//每页取2条
$perpage = 10;
$offset = ($page-1) * $perpage;


$category = $cat->find($cat_id);


if(empty($category)){
    header('location:index.php');
    exit;
}


//取出树状导航
$cats = $cat->select();

$sort = $cat->getaddTree($cats,$id=0,$lev=1);


//面包屑导航(家谱树)
$nav = $cat->getditTree($cat_id);


//取出栏目下的所有商品
$goods = new goodsModel();
$goodslist = $goods->cateGoods($cat_id,$offset,$perpage);

require(ROOT. "view/front/lanmu.html");