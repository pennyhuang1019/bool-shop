<?php 


// file userModel.class.php

defined('ACC')||exit('ACC Denied');

class OGModel extends Model{
	protected $table = 'ordergoods'; //关于商品的订单
	protected $pk = 'og_id';

	//将订单上的商品加入ordergoods表中
	public function addOG($data){
		if($this->add($data)){
			$sql = "update goods set goods_number = goods_number - " . $data['goods_number'] . " where goods_id = " . $data['goods_id'];
			return $this->db->query($sql);
		}
		return false;
	}
}

?>