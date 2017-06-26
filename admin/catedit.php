<?php 





//file catedit.php

define('ACC', true);
require('../include/init.php');

//接收数据
$cat_id = $_GET['cat_id'] +0;

$catedit = new cateModel();
$cate = $catedit->find($cat_id);

$catelist = $catedit->select();
$catelist = $catedit->getaddTree($catelist);


require(ROOT . 'view/admin/templates/catedit.html');





?>