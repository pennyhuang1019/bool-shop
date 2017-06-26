<?php

defined('ACC')||exit('ACC Denied');
class Model {
    protected $table = NULL; // 是model所控制的表
    protected $db = NULL; // 是引入的mysql对象
    protected $pk = NULL; //是表中的主键
    protected $field = array(); //表中的字段
    protected $_auto = array(); //自动填充的字段
    protected $_valid = array(); //需要验证接收过来字段
    protected $error = array(); //用来存放错误信息提示的数组

    public function __construct() {
        $this->db = mysql::getIns(); //链接数据库，引入数据模型
    }

    public function table($table) {
        $this->table = $table;
    }

    //将接收过来的数据进行自动验证
    //验证的原则
    public function check($value,$rule = '',$parm = ''){
        switch ($rule) {
            case 'require':
                return !empty($value);

            case 'number':
                return is_numeric($value);

            case 'in':
                $temp = explode(',', $parm);
                return in_array($value, $temp);

            case 'between':
                list($min,$max) = explode(',', $parm);
                return $value >= $min && $value <= $max;

            case 'length':
                list($min,$max) = explode(',', $parm);
                return strlen($value) >= $min && strlen($value) <= $max;
            case 'email':
                //检验密码规则
                return (filter_var($value, FILTER_VALIDATE_EMAIL) !== false);
           
            default:
                return false;
        }
    }

    //具体验证过程
    public function _validate($data){
        if(empty($this->_valid)){
            return true;
        }
        $this->error = array();
        foreach ($this->_valid as $k => $v) {
            switch ($v[1]) {
                case 1:
                    if(!isset($data[$v[0]])){
                        $this->error[] = $v[2];
                        return false;
                    }

                    if(!isset($v[4])){
                        $v[4] = '';
                    }
                    
                    if(!$this->check($data[$v[0]],$v[3],$v[4])){
                        $this->error[] = $v[2];
                        return false;
                    }
                    break;

                case 0:
                    if(isset($data[$v[0]])){
                        if(!$this->check($data[$v[0]],$v[3],$v[4])){
                        $this->error[] = $v[2];
                        return false;
                        }
                    }                   
                    break;

                case 2:
                    if(isset($data[$v[0]]) && !empty($data[$v[0]])){
                        if(!$this->check($data[$v[0]],$v[3],$v[4])){
                        $this->error[] = $v[2];
                        return false;
                        }
                    }                 
            }
        }
        return true;
    }

    //验证过程中错误信息的数组方法
    public function getErr(){
        return $this->error;
    }

    //将接收过来的数据进行自动过滤
    public function _facade($array = array()){
        $data = array();
        foreach ($array as $k => $v) {
            if(in_array($k, $this->field)){  //判断$k是否是表中的字段
                $data[$k] = $v;
            }
        }
        return $data;
    }

    //将表中的字段自动填充到$data中
    public function _autoFill($data){
        foreach ($this->_auto as $k => $v) {
            if(!array_key_exists($v[0], $data)){
                switch ($v[1]) {
                    case 'value':
                        $data[$v[0]] = $v[2];
                        break;

                    case 'function':
                        $data[$v[0]] = call_user_func($v[2]);
                        break;
                }

            }
        }
        return $data;
    }

    //增：添加数据，插入数据add();
    public function add($data){
    	return $this->db->autoExecute($this->table,$data,'insert');
    }

    //删：删除数据delete();
    public function delete($id){
    	$sql = "delete from " . $this->table . "  where  " . $this->pk .'=' . $id;
    	if($this->db->query($sql)){
            return $this->db->affected_rows();
        }else{
            return false;
        }
    	
    } 

    //改: 修改$id的相关数据update();
    public function update($data,$id){
    	$rs = $this->db->autoExecute($this->table,$data,'update','  where  ' . $this->pk . '=' . $id);
        if($rs){
            return $this->db->affected_rows(); 
        }else{
            return false;
        }

    }

    //查：查看所有数据select();
    public function select(){
        $sql = "select * from " . $this->table;
        return $this->db->getAll($sql);
    }

    //查：根据$id查询数据
    public function find($id){
        $sql = "select * from " . $this->table . '  where  ' . $this->pk . '=' . $id;
        return $this->db->getRow($sql);
    }

    //获得自主增加的insert_id
    public function insert_id(){
        return $this->db->insert_id();
    }
}

    