<?php

define('ACC',true);
require("./include/init.php");

//设置一个动作，判断用户的行为，比如是将商品加入购物车/写地址/提交/清空购物车。。。
$act = isset($_GET['act'])?$_GET['act']:'buy';

$cart = cartTool::getCart(); //实例化购物车类
$goods = new goodsModel();  //实例化商品类

if($act == 'buy'){ //说明用户想要将商品加入购物车内
    $goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;
    $num = isset($_GET['num'])?$_GET['num']+0:1;
    
    if($goods_id){

        $g = $goods->find($goods_id);

        if(!empty($g)){ //判断此商品是否存在，存在的话接着如下操作

            //判断此商品是否在架上销售或者已经存入回收站
            if($g['is_delete'] ==1 || $g['is_on_sale'] == 0){
                $msg = '此商品已经下架';
                include(ROOT . 'view/front/msg.html');
                exit;
            }

            //先把商品加入购物车
            $cart->addItem($g['goods_id'],$g['goods_name'],$g['shop_price'],$num);

            $items = $cart->all();

            if($items[$goods_id]['num'] > $g['goods_number']){ 
                //库存不够，把刚才加入到购物车的动作撤回，减少数量
                $cart->desNum($goods_id,$num);
                $msg = '此商品库存不足';
                include(ROOT . 'view/front/msg.html');
                exit;  
            }

        }
       
    }
    
    
    $items = $cart->all();

    if(empty($items)){ //如果购物车里面没有商品
        header('location:index.php');
        exit;
    }

    //如果不为空，则获取购物车里面的详细信息
    $items = $goods->getCartGoods($items);

    $total = $cart->getPrice(); //获得购物车里面所有商品的总金额
    $market_total = 0.0;
    foreach($items as $v){
        $market_total += $v['market_price'] * $v['num'];
    }

    $discount = $market_total - $total;
    $rate = round(100 * $discount/ $total,2); //round()小数点后面保留两位小数

    require(ROOT. "view/front/jiesuan.html");

}else if($act == 'clear'){
    $cart->clear();
    $msg = '已清空购物车';
    include(ROOT . 'view/front/msg.html');
}else if($act == 'tijiao'){
    $items = $cart->all();
    //如果不为空，则获取购物车里面的详细信息
    $items = $goods->getCartGoods($items);

    $total = $cart->getPrice(); //获得购物车里面所有商品的总金额
    $market_total = 0.0;
    foreach($items as $v){
        $market_total += $v['market_price'] * $v['num'];
    }

    $discount = $market_total - $total;

    if($total !== 0){
        $rate = round(100 * $discount/ $total,2); //round()小数点后面保留两位小数
    }else{
        $rate = '免费赠送';
    }
    

    require(ROOT. "view/front/tijiao.html");
}else if($act == 'done'){
    //提交订单的话，那么需要用两张表(orderinfo/ordergoods)将订单信息入库
    //首先是订单用户信息表即orderinfo
    
    //print_r($_POST);
    
    $orderinfo = new OIModel();
    
    //自动验证
    if(!$orderinfo->_validate($_POST)){
        $msg = implode(',',$orderinfo->getErr());
        include(ROOT . 'view/front/msg.html');
        exit;
    }
    
    //自动过滤
    $data = $orderinfo->_facade($_POST);

    //自动填充
    $data = $orderinfo->_autoFill($data);

    //写入订单号
    $data['order_sn'] = $orderinfo->orderSn();

    //写入总金额
    $total = $data['order_amount'] = $cart->getPrice();
    
    //在session中将user_id/username接收到订单中来
    $data['user_id'] = isset($_SESSION['user_id'])?$_SESSION['user_id']+0:0;
    $data['username'] = isset($_SESSION['username'])?$_SESSION['username']:'匿名';

    //将接收到的数据insert到orderinfo表中
    if(!$orderinfo->add($data)){
        $msg = '订单生成失败';
        include(ROOT . 'view/front/msg.html');
        exit;
    }

    //订单里商品信息加入到ordergoods表中
    $ordergoods = new OGModel();
   
    $order_id = $orderinfo->insert_id(); //获得自动增加的order_id;
    $order_sn = $data['order_sn'];

    //获取购物车里面的全部商品信息
    $items = $cart->all();
    $cnt = 0;
    foreach($items as $k=>$v){
        $data = array();
        $data['order_id'] = $order_id;
        $data['order_sn'] = $order_sn;
        $data['goods_id'] = $k;
        $data['goods_name'] = $v['name'];
        $data['goods_number'] = $v['num'];
        $data['shop_price'] = $v['price'];
        $data['subtotal'] = $v['price'] * $v['num'];
        
        if($ordergoods->addOG($data)){
            $cnt += 1; // 插入一条og成功,$cnt+1.
            // 因为,1个订单有N条商品,必须N条商品,都插入成功,才算订单插入成功!
        }
        
    }

    if(count($items) !== $cnt){ //说明还有商品未能插入成功
        $msg = '订单生成失败';
        $orderinfo->invoke($order_id); //如果失败，则取消订单
        include(ROOT . 'view/front/msg.html');
        exit;
    }

    $cart->clear(); //如果下单成功，则清除购物车
    include(ROOT . 'view/front/order.html');

}


