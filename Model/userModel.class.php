<?php 

// file userModel.class.php

defined('ACC')||exit('ACC Denied');

class userModel extends Model{
	protected $table = 'user';
	protected $pk = 'user_id';

	protected $field = array('user_id','username','email','passwd','regtime','lastlogin','agreement');

	protected $_auto = array(
		array('regtime','function','time')
		);
	protected $_valid = array(
		array('username',1,'用户名不能为空','require'),
		array('username',0,'用户名在4~16字符之间','length','4,16'),
		array('email',1,'email非法格式','email'),
		array('passwd',1,'密码不能为空','require'),
		array('agreement',1,'用户协议未同意','require'),

		);

	//检查用户名是否存在
	public function checkUser($username,$passwd=''){
        if($passwd == ''){ //检查用户名是否存在，用户注册和登录时用到
            $sql = "select count(*) from " . $this->table . " where username='" . $username . "'" ;
		    return $this->db->getOne($sql);
        }else{ //检查用户和密码是否一致，用户登录时用到
            $sql = "select user_id,username,email,passwd from " . $this->table . " where username='" . $username ."'";
            $row = $this->db->getRow($sql);
            
            if(empty($row)){
                return false;
            }
            if($row['passwd'] != $this->encPasswd($passwd)){
                return false;  
            }

            unset($row['passwd']);
            return $row;
        
        }
		
	}


	//密码加密
	protected function encPasswd($p){
		return md5($p);
	}

	//用户注册，插入数据
	public function reg($data){
		if($data['passwd']){
			$data['passwd'] = $this->encPasswd($data['passwd']);
		}
		return $this->add($data);
	}

}

?>

