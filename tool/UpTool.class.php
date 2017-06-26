<?php 

//defined('ACC') || exit('ACC Denied');

//文件上传类

class UpTool{
	protected $allowExt = 'jpg,bmp,png,jpeg,doc';
	protected $maxsize = 1 ;//1M
	protected $errno = 0; //错误代码
	protected $error = array(
		0=>'无错',
		1=>'上传文件超出系统限制',
		2=>'上传文件大小超出网页表单页面',
		3=>'文件只有部分被上传',
		4=>'没有文件被上传',
		6=>'找不到临时文件夹',
		7=>'文件写入失败',
		8=>'不允许的文件后缀',
		9=>'文件大小超出的类的允许范围',
		10=>'创建目录失败',
		11=>'移动失败'
		);

	//将临时文件存储至电脑硬盘上
	public function up($key){
		if(!isset($_FILES[$key])){
			return false;
		}
		$file = $_FILES[$key];

		//判断文件是否上传成功
		if($file['error']){ //如果存在，说明不等于0，即上传失败
			$this->errno = $file['error'];
			return false;
		}
		//获取文件格式
		$ext = $this->getExt($file['name']);

		//判断文件格式是否在允许范围
		if(!$this->isallowExt($ext)){
			$this->errno = 8;
			return false;
		}
		//判断文件大小是否在允许范围内
		if(!$this->isallowsize($file['size'])){
			$this->errno = 9;
			return false;
		}
		$newname = $this->getname() . '.' . $ext;

		//创建文件目录
		$dir = $this->mk_dir();
		if($dir == false){
			$this->errno = 10;
			return false;
		}
		$dir = $dir . '/' . $newname;

		//移动存储
		if(!move_uploaded_file($file['tmp_name'], $dir)){
			$this->errno = 11;
			return false;
		}
		return str_replace(ROOT, '', $dir);
	}
		
	//获取错误信息
	public function geterror(){
		return $this->error[$this->errno];
	}

	//允许的文件格式
	public function allowExt($ext){
		$this->allowExt = $ext;
	}

	//类文件允许上传的最大文件容量
	public function maxsize($size){
		$this->maxsize = $size;
	}
	
	//获取文件的格式
	public function getExt($file){
		$tmp = explode('.', $file);
		return end($tmp);
	}

	//检查文件格式是否允许上传
	public function isallowExt($ext){
		return in_array(strtolower($ext), explode(',', strtolower($this->allowExt)));	
	}

	//检查文件大小
	public function isallowsize($size){
		return $size <= $this->maxsize * 1024 * 1024;
	}	

	//创建目录方法
	public function mk_dir(){
		$dir = ROOT . 'data/images/' . date('ym/d',time());
		if(is_dir($dir) || mkdir($dir,0777,true) ){
			return $dir;
		}else{
			return false;
		}
	}

	//获取文件的随机名字
	public function getName($length = 6){
		$str = 'abcdefghijlmnopqrstuvwxyz23456789';
		return substr(str_shuffle($str), 0,$length);
	}

}




?>