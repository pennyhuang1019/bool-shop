<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

defined('ACC')||exit('ACC Denied');

class goodsModel extends Model{
	protected $table = 'goods';
	protected $pk = 'goods_id';
	protected $field = array('goods_id','goods_sn','cat_id','brand_id','goods_name','shop_price','market_price','goods_number','click_count','goods_weight','goods_brief','goods_desc','thumb_img','goods_img','ori_img','is_on_sale','is_delete','is_best','is_new','is_hot','add_time','last_update');
	protected $_auto = array(
		array('is_delete','value',0),
		array('is_best','value',0),
		array('is_new','value',0),
		array('is_hot','value',0),
		array('add_time','function','time')
		);
	protected $_valid = array(
		array('goods_name',1,'商品名不能为空','require'),
		array('cat_id',1,'栏目id不正确','number'),
		array('shop_price',1,'店内价格不正确','number'),
		array('market_price',1,'市场价格不正确','number'),
		array('is_new',0,'is_new只能是0或1','in','0,1'),
		array('is_hot',0,'is_hot只能是0或1','in','0,1'),
		array('is_best',0,'is_best只能是0或1','in','0,1'),
		array('goods_brief',2,'商品简介字数在10~100字符之间','length','10,100')
		);

	//将商品加入回收站
	public function trash($id){
		return $this->update(array('is_delete' =>1),$id);
	}

	//从回收站里面的商品进行恢复销售，放在商品列表中
	public function recovery($id){
		return $this->update(array('is_delete' =>0),$id);
	}

	//获取商品列表
	public function getGoods(){
		$sql = "select * from " . $this->table . '  where is_delete = 0';
		return $this->db->getAll($sql);  
	}

	//获取回收站里面的商品列表
	public function getTrash(){
		$sql = "select * from " . $this->table . "  where is_delete = 1 ";
		return $this->db->getAll($sql);
	}

	//自动生成商品货号
	public function createSn(){
		$sn = 'BL' . date('Ymd') . rand(10000,99999);
		$sql = "select count(*) from " . $this->table . " where goods_sn = '" . $sn . "'" ;
		return $this->db->getOne($sql)? $this->createSn():$sn;
	}

	//取出新品
	public function getNew($n=5){
		$sql = "select goods_id,goods_name,shop_price,market_price,thumb_img from " . $this->table . " where is_new=1 order by add_time limit " . $n;
		return $this->db->getAll($sql);
	}

	/*
	取出指定栏目的商品
	//$cat_id = $GET['cat_id'];
	$sql = select .. from goods where cat_id = $cat_id;
	//这是错的

	因为$cat_id对应的栏目可能是个大栏目，而大栏目下面没有商品。
	商品放在大栏目下面的小栏目下。

	因此，正确的做法是，找出$cat_id的所有子孙栏目，然后再查所有$cat-id及其子孙栏目下的商品

	*/
	public function cateGoods($cat_id,$offset=0,$perpage=5){
		$category = new cateModel();
		$cats = $category->select(); //取出所有栏目
		$sons = $category->getaddTree($cats,$cat_id); //找出指定$cat_id的子孙栏目

		$sub = array($cat_id);
		if(!empty($sons)){
			foreach ($sons as $v ) {
				$sub[] = $v['cat_id'];
			}
		}
		$in = implode(',', $sub);
		$sql = "select goods_id,goods_name,shop_price,market_price,thumb_img from " . $this->table . " where cat_id in (" .  $in  .") order by add_time limit " . $offset . ',' . $perpage;
		return $this->db->getAll($sql);
	}

	//获取购物车页面中商品的详细信息
	//$items购物车中的商品数组
	//return 购物车中的商品数组的详细信息
	public function getCartGoods($items){
		foreach ($items as $k => $v) {
			$sql = "select goods_id,goods_name,shop_price,market_price,thumb_img from " . $this->table . " where goods_id = " . $k;
			$row = $this->db->getRow($sql);
			$items[$k]['thumb_img'] = $row['thumb_img'];
			$items[$k]['market_price'] = $row['market_price'];

		}
		return $items;
		
	}

}

?>