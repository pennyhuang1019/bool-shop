<?php 

define('ACC', true);
require('../include/init.php');

$cateadd = new cateModel();
$catelist = $cateadd->select(); //选择所有的目录
$catelist = $cateadd->getaddTree($catelist); //相当于重新排序，排家谱树进行排列

require(ROOT . 'view/admin/templates/cateadd.html');

?>