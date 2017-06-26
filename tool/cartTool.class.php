<?php 

//defined('ACC') || exit('ACC Denied');
header("content-type:text/html; charset=utf8");

class cartTool{
	protected static $ins = NULL;
	protected $items = array();


	protected final function __construct(){

	}

	protected final function __clone(){

	}

	//实例化自身
	protected static function getIns(){
		if(!self::$ins instanceof self){
			self::$ins = new self();
		}
		return self::$ins;
	}

	//把购物车自身单例对象放到session里面
	public static function getCart(){
        if(!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof self)) {
            $_SESSION['cart'] = self::getIns(); //$_SESSION['cart']就是一个对象
        }
		return $_SESSION['cart'];
	}

    //添加商品至购物车
    public function addItem($id,$name,$price,$num=1){
        //先判断商品是否存在,存在直接添加数量
        if($this->hasItem($id)){
            $this->incNum($id,$num);
            return;
        }
        $item = array();
        $item['name'] = $name;
        $item['price'] = $price ;
        $item['num'] = $num;
        
        $this->items[$id] = $item;
    
    }

    //将商品从购物车中清除出去
    public function delItem($id){
        unset($this->items[$id]);
    }


    //清空购物车
    public function clear(){
        $this->items = array();
    }

    //改变购物车商品的数量
    public function modNum($id,$num){
        if(!$this->hasItem($id)){
            return false;
        }
        $this->items[$id]['num'] = $num;
    }

    //增加购物车商品的数量
    public function incNum($id,$num=1){
        if(!$this->hasItem($id)){
            return false;
        }
        $this->items[$id]['num'] += $num;
    }

    //减少购物车商品的数量
    public function desNum($id,$num=1){
        if(!$this->hasItem($id)){
            return false;
        }
        $this->items[$id]['num'] -= $num;

        //如果商品已经减到0，则可以将该商品从购物车中删除出去
        if($this->items[$id]['num'] <1){
            $this->delItem($id);
        }
    }

    //查看购物车中商品的种类
    public function getCnt(){
        return count($this->items);
    }

    //查看购物车中产品的数量
    public function getNum(){
        if($this->getCnt() == 0){
            return 0;
        }
        $sum = 0;
        foreach($this->items as $item){
            $sum += $item['num'];
        }
        return $sum;
    }

    //查看购物车中商品的总金额
    public function getPrice(){
        if($this->getCnt() == 0){
            return 0;
        }
        $price = 0.0;
        foreach($this->items as $item){
            $price += $item['num'] * $item['price'];
        }
        return $price;
    }
    
    //查看购物车中所有商品
    public function all(){
        return $this->items;
    }

     //判断商品是否存在
    public function hasItem($id) {
        return array_key_exists($id,$this->items);
    }
}


//测试实验


session_start();

// print_r(CartTool::getCart());

$cart = CartTool::getCart();


if(!isset($_GET['test'])) {
   $_GET['test'] = '';
}

if($_GET['test'] == 'addwangba') {
    $cart->addItem(1,'王八',23.4,1);
    echo 'add wangba ok';
} else if($_GET['test'] == 'addfz') {
    $cart->addItem(2,'方舟',2347.56,1);
    echo 'add fangzhou ok';
} else if($_GET['test'] == 'clear') {
    $cart->clear();
} else if($_GET['test'] == 'show') {
    print_r($cart->all());
    echo '<br />';
    echo '共',$cart->getCnt(),'种',$cart->getNum(),'个商品<br />';
    echo '共',$cart->getPrice(),'元';
} else {
    print_r($cart);
}


?>