<?php 


// file userModel.class.php

defined('ACC')||exit('ACC Denied');

class OIModel extends Model{
	protected $table = 'orderinfo'; //关于收货人的表格
	protected $pk = 'order_id';

	protected $field = array('order_id','order_sn','user_id','username','zone','address','zipcode','reciver','email','tel','mobile','building','best_time','add_time','order_amount','pay');

	protected $_auto = array(
		array('add_time','function','time')
		);
	protected $_valid = array(
		array('reciver',1,'收货人姓名不能为空','require'),
		array('tel',1,'收货人电话不能为空','require'),
		array('email',1,'email非法格式','email'),
		array('address',1,'地址不能为空','require'),
		array('zone',1,'配送区域不能为空','require'),
		array('pay',1,'支付方式不能为空','in','4,5'),
		);

	//自动生成订单号方法
	public function orderSn(){
		$sn = 'OI' . date('Ymd') . rand(10000,99999);
		$sql = "select count(*) from " . $this->table . " where order_sn = '" . $sn . "'" ;
		return $this->db->getOne($sql)? $this->orderSn():$sn;
	}

	//取消订单
	public function invoke($order_id){
		$this->delete($order_id); //删除订单
		$sql = "delect from ordergoods where order_id=" . $order_id; //删除对应的入库商品数据
		return $this->db->query($sql); 
	}



}

?>