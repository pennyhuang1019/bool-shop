<?php


class ImageTool{
    //获取图片类型，大小
    public static function imageinfro($image){
        if(!file_exists($image)){
            return false;
        }

        $info = getimagesize($image);
        if($info == false){
            return false;
        }

        //print_r($info);
        //echo '<br/>';

        $img['width'] = $info[0];
        $img['height'] = $info[1];
        $img['ext'] = substr($info['mime'],strpos($info['mime'],'/')+1);
        return $img;

    }
    
    //图片水印
    public static function getwater($des,$src,$save=null,$pos=2,$alphha=40){
        //获取两个文件的信息
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
        $ifunc = 'imagecreatefrom' . $isrc['ext'];

        if(!function_exists($dfunc) || !function_exists($ifunc)){
            return false;
        }

        //创建画布
        $dim = $dfunc($des);
        $sim = $ifunc($src);

        $pwidth = $ides['width']-$isrc['width'];
        $pheight = $ides['height'] - $isrc['height'];

        //要印水印的位置
        switch($pos){
            case 0: //左上角
                $dst_x=0;
                $dst_y=0;
                break;
            case 1: //右上角
                $dst_x=$pwidth;
                $dst_y=0;
                break;
            case 3: //左下角
                $dst_x=0;
                $dst_y=$pheight;
                break;
            default:
                $dst_x=$pwidth;
                $dst_y=$pheight;
                break;
        }

        //填充画布
        imagecopymerge($dim,$sim,$dst_x,$dst_y,0,0,$isrc['width'],$isrc['height'],$alphha);

        //保存
        if(!$save){
            $save = $des;
            unlink($des);
        }

        //保存图片
        $creates = 'image' . $ides['ext'];
        $creates($dim,$save);

        //关闭画布
        imagedestroy($dim);
        imagedestroy($sim);

        return true;

    }

    //图片缩略
    public static function thumb_img($src,$save=null,$width=200,$height=200){
        //获取图片相关信息
        $isrc = self::imageinfro($src);
        if($isrc == false){
            return false;
        }
        $sfunc = 'imagecreatefrom' . $isrc['ext'];

        //判断加载函数是否存在
        if(!function_exists($sfunc)){
            return false;
        }
        
        //创建画布
        $imdes = imagecreatetruecolor($width,$height);
        $imsrc = $sfunc($src);

        //创建填充颜料画笔
        $bg = imagecolorallocate($imdes,255,255,255);


        //计算缩放比例
        $calc = min($width/$isrc['width'],$height/$isrc['height']);
        $dwidth = (int)$isrc['width']* $calc;
        $dheight = (int)$isrc['height'] * $calc;
        $padding_x = (int)($width-$dwidth)/2;
        $padding_y = (int)($height-$dheight)/2;

        //填充画布
        imagefill($imdes,0,0,$bg);
        imagecopyresampled($imdes,$imsrc,$padding_x,$padding_y,0,0,$dwidth,$dheight,$isrc['width'],$isrc['height']);

        //保存画布
        if(!$save){
            $save= $src;
            unlink($src);
        }

        $create = 'image' . $isrc['ext'];
        $create($imdes,$save);

        //摧毁画布
        imagedestroy($imdes);
        imagedestroy($imsrc);

        return true;
    }

    //验证码
    public static function captha($width= 50,$height=25,$lenght=4){
        //创建画布
        $im = imagecreatetruecolor($width,$height);

        //创建背景颜料笔
        $bg = imagecolorallocate($im,200,200,200);

        //填充背景颜色
        imagefill($im,0,0,$bg);

        //创建文字颜料笔
        $color = imagecolorallocate($im,mt_rand(0,125),mt_rand(0,125),mt_rand(0,125));

        //创建三只颜料笔
        $color1 = imagecolorallocate($im,mt_rand(100,125),mt_rand(100,125),mt_rand(100,125));
        $color2 = imagecolorallocate($im,mt_rand(100,125),mt_rand(100,125),mt_rand(100,125));
        $color3 = imagecolorallocate($im,mt_rand(100,125),mt_rand(100,125),mt_rand(100,125));

        //文字
        $str = 'abcdefghijkmnpqrstuvwxwz23456789ABCDEFGHJKMNQRSTUVWXYZ';
        $str = substr(str_shuffle($str),0,4);

        //画线
        imageline($im,mt_rand(0,50),mt_rand(0,25),mt_rand(0,50),mt_rand(0,25),$color1);
        imageline($im,mt_rand(0,50),mt_rand(0,25),mt_rand(0,50),mt_rand(0,25),$color2);
        imageline($im,mt_rand(0,50),mt_rand(0,25),mt_rand(0,50),mt_rand(0,25),$color3);

        //写字
        imagestring($im,5,7,5,$str,$color);

        //输出
        //header('content-type:image/jpeg');
        header("content-type:image/jpeg");
        imagejpeg($im);

        //摧毁画布
        imagedestroy($im);
    }

}



//include('./tool/ImageTool.class.php');


//$img = ImageTool::imageinfro('./imagetool.jpg');

/*
if(ImageTool::getwater('./imagetool.jpg','./src.png',$save=null,$pos=1)){
    echo 'ok';
}else{
    echo 'fail';
}




if(ImageTool::thumb_img('./imagetool.jpg')){
    echo 'ok';
}else{
    echo 'fail';
}



ImageTool::captha();
*/

echo $_SERVER['SERVER_ADDR'];

echo '<br/>';

print_r($_SERVER);







?>