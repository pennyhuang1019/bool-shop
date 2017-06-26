<?php 

/*
水印图片，缩略图 ，验证码类
图片类型、图片的宽 高
*/

class ImageTool{

	//获取图片类型、宽高
	public static function imageinfro($image){
		//判断图片是否存在
		if(!file_exists($image)){
			return false;
		}
		/*
		getimagesize() 函数将测定任何 GIF，JPG，PNG，SWF，SWC，PSD，TIFF，BMP，IFF，JP2，JPX，JB2，JPC，XBM或 WBMP 图像文
        件的大小并返回图像的尺寸以及文件类型和一个可以用于普通 HTML 文件中 IMG 标记中的 height/width 文本字符串。

        如果不能访问 filename指定的图像或者其不是有效的图像，getimagesize()将返回 FALSE 并产生一条 E_WARNING 级的错误。 
    	*/
		$infro = getimagesize($image);

		if($infro == false){ //判断是否是图片类型
			return false;
		}

		$img['width'] = $infro[0];
		$img['height'] = $infro[1];
		$img['ext'] = substr($infro['mime'],strpos($infro['mime'], '/')+1);

		return $img;
	}

	//图片水印
	public static function getwater($des,$src,$save=null,$pos=2,$alphha='40'){
		//判断两张图片是否存在
		if(!self::imageinfro($des) || !self::imageinfro($src)){
			return false;
		}

		$ides = self::imageinfro($des);
		$isrc = self::imageinfro($src);

		if($ides['width'] < $isrc['width'] || $ides['height'] < $isrc['height']){
			return false;
		}

		//判断加载函数是否存在
		$dfunc = 'imagecreatefrom' . $ides['ext'];
		$sfunc = 'imagecreatefrom' . $isrc['ext'];
		if(!function_exists($dfunc) || !function_exists($sfunc)){
			return false;
		}

		//创建画布
		$dim = $dfunc($des);
		$sim = $sfunc($src);

		$pwidth = $ides['width'] - $isrc['width'];
		$pheight = $ides['height'] - $isrc['height'];

		//判断要复制过来的位置
		switch ($pos) {
			case 0: //左上角
				$dst_x = 0;
				$dst_y = 0;
				break;
			case 1: //右上角
				$dst_x = $pwidth;
				$dst_y = 0;
				break;
			case 3: //左下角
				$dst_x = 0;
				$dst_y = $pheight;
				break;
			
			default:
				$dst_x = $pwidth;
				$dst_y = $pheight;
				break;
		}

		//复制图片,填充画布
		imagecopymerge($dim, $sim, $dst_x, $dst_y, 0, 0, $isrc['width'], $isrc['height'],$alphha);

		//保存画布
		if(!$save){
			$save = $des;
			unlink($des);
		}
		$creates = 'image' . $ides['ext'];
		$creates($dim,$save);

		//毁坏画布
		imagedestroy($dim);
		imagedestroy($sim);

		return true;

	}

	//图片缩略
	public static function thumb_img($src,$save=null,$width=200,$height=200){
		//获取源图片信息
		$isrc = self::imageinfro($src);
		if($isrc == false){
			return false;
		}
		$sfunc = 'imagecreatefrom' . $isrc['ext'];

		//创建画布
		$imdes = imagecreatetruecolor($width, $height); //目的画布
		$imsrc = $sfunc($src);  //被缩略画布

		//创建画布颜料笔
		$bg = imagecolorallocate($imdes, 255, 255, 255);

		//计算缩放比例
		$calc = min($width/$isrc['width'],$height/$isrc['height']);
		$dwidth = (int)$isrc['width'] * $calc;
		$dheight = (int)$isrc['height'] * $calc;
		$padding_x = (int)($width - $dwidth)/2;
		$padding_y = (int)($height - $dheight)/2;


		//填充画布，变成缩略图
		imagefill($imdes, 0, 0, $bg);
		imagecopyresampled($imdes, $imsrc, $padding_x, $padding_y, 0, 0, $dwidth, $dheight, $isrc['width'], $isrc['height']);
		
		//保存画布
		if(!$save){
			$save = $src;
			unlink($src);
		}
		$creates = 'image' . $isrc['ext'];
		$creates($imdes,$save);

		//毁坏画布
		imagedestroy($imdes);
		imagedestroy($imsrc);

		return true;
	}

	//验证码
	public static function captcha($width=50,$height=25){
		//创建画布
		$im = imagecreatetruecolor($width, $height);

		//创建背景颜料笔
		$bggray = imagecolorallocate($im, 220, 220, 220);

		//填充背景颜色
		imagefill($im, 0, 0, $bggray);

		//造随机字体颜色
		$color = imagecolorallocate($im, mt_rand(0,125), mt_rand(0,125), mt_rand(0,125));

		//造随机线条颜色
		$color1 = imagecolorallocate($im, mt_rand(100,125), mt_rand(100,125), mt_rand(100,125));
		$color2 = imagecolorallocate($im, mt_rand(100,125), mt_rand(100,125), mt_rand(100,125));
		$color3 = imagecolorallocate($im, mt_rand(100,125), mt_rand(100,125), mt_rand(100,125));

		//在画布上划线
		imageline($im, mt_rand(0,50), mt_rand(0,25), mt_rand(0,50), mt_rand(0,25), color1);
		imageline($im, mt_rand(0,50), mt_rand(0,20), mt_rand(0,50), mt_rand(0,20), color2);
		imageline($im, mt_rand(0,50), mt_rand(0,20), mt_rand(0,50), mt_rand(0,20), color3);

		//在画布上写字
		$str = substr(str_shuffle('abcdefghjkmnpqrstuvxywABCDEFGHJKMNPQRSTUVXYW23456789'), 0,4);
		imagestring($im, 5, 7, 5, $str, $color);

		//输出画布
		header("content-type:image/jpeg");
		imagejpeg($im);

		//毁坏画布
		imagedestroy($im);

	}

}











?>