<?php 






define('ACC', true);
require('../include/init.php');

//接收数据
$cat_id = $_GET['cat_id'] +0;

$catedel = new cateModel();
$sons = $catedel->getSon($cat_id);
if(!empty($sons)){
	echo "有子栏目，不能删除";
	exit;
}

if($catedel->delete($cat_id)){
	echo '删除成功';
}else{
	echo '删除失败';
}


?>