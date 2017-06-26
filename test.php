<?php

//分页类

/*
class page{
    protected $total = 0;
    protected $page = 9;
    protected $perpage = 10;

    public function __construct($total,$page=null,$perpage=null){
        $this->total = $total;
        if($page){
            $this->page = $page;
        }
        if($perpage){
            $this->perpage = $perpage;
        }
    
    }

    public function show(){
        $cnt = ceil($this->total/$this->perpage);

        $uri = $_SERVER['REQUEST_URI'];

        $parse = parse_url($uri); //拆成数组了如Array ( [path] => /test.php [query] => aa=hello&worl=hjsdh )

        $param = array();
        if(isset($parse['query'])){
            parse_str($parse['query'],$param);
        }

        $url = $parse['path'] . '?';
        unset($param['page']);

        if(!empty($param)){           
            $param = http_build_query($param);
            $url = $url . $param . '&';
        }

        //计算导航页码
        $nav = array();
        $nav[0] = '<span class="page_now" >' . $this->page . '</span>';

        for($left = $this->page-1,$right = $this->page+1; ($left>1 || $right<=$cnt) && count($nav)<5;){
            if($left >=1){
                array_unshift($nav,'<a href=" ' . $url . 'page=' . $left . '" >[' . $left . ']</a>');
                $left -= 1;
            }

            if($right <= $cnt){
                array_push($nav,'<a href=" ' . $url . 'page=' . $right . '" >[' . $right . ']</a>');
                $right += 1;
            }

        }

        return implode('',$nav);  
        //print_r($nav);
    }


}

//测试
$page = new page(100,7,10);
echo $page->show();

*/


class page{
    protected $total = 0;
    protected $page = 3;
    protected $perpage = 10;
    
    public function __construct($total,$page=null,$perpage=null){
        $this->total = $total;
        if($page){
            $this->page = $page;
        }
        if($perpage){
            $this->perpage = $perpage;
        }
    }

    public function show(){
        //计算总页数
        $cnt = ceil($this->total/$this->perpage);

        //获取地址栏信息
        $uri = $_SERVER['REQUEST_URI'];
        //echo $uri, '<br/>';

        $parse = parse_url($uri); //拆成数组 如Array ( [path] => /test.php [query] => age=6&page=12&name=panny ) 
        //print_r($parse);

        $param = array(); 
        if(isset($parse['query'])){
            parse_str($parse['query'],$param);
        }
        //print_r($param);
        //echo '<br/>';
        $url = $parse['path'] . '?';
        unset($param['page']);

        if(!empty($param)){
            $param = http_build_query($param);
            $url = $url . $param . '&';
        }
        //echo $param,'<br/>';
        //echo $url;

        //分页导航计算
        $nav = array();
        $nav[0] = '<span class="page_now">' . $this->page . '</span>';
        
        for($left = $this->page-1,$right= $this->page+1;($left>=1 || $right <= $cnt) && count($nav) < 5;){
            if($left>=1){
                array_unshift($nav,'<a href="' . $url . 'page=' . $left . '">[' . $left . ']</a>');
                $left -= 1;
            }
            if($right <= $cnt){
                array_push($nav,'<a href="' . $url . 'page=' . $right . '">[' . $right . ']</a>');
                $right +=1;
            }
        }

        //print_r($nav);
        return implode('',$nav);
        
    
    }


}

$page = new page(100,3,10);
echo $page->show();
       
?>