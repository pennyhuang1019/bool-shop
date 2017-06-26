<?php 


//file goodsadd.php

define('ACC', true);
require('../include/init.php');

$cateadd = new cateModel();
$catelist = $cateadd->select();
$catelist = $cateadd->getaddTree($catelist);

require(ROOT . 'view/admin/templates/goodsadd.html');


?>