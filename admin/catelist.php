<?php 


define('ACC', true);
require('../include/init.php');


$cate = new cateModel();
$catelist = $cate->select();
$catelist = $cate->getaddTree($catelist);


require(ROOT . 'view/admin/templates/catelist.html');

?>