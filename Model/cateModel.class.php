<?php 
/****
布尔教育 高端PHP培训
培  训: http://www.itbool.com
论  坛: http://www.zixue.it
****/

defined('ACC')||exit('ACC Denied');
class cateModel extends Model{
	protected $table ='category';
	
	//插入数据insert
	public function add($data){
		return $this->db->autoExecute($this->table,$data);
	}
	//修改数据update
	public function update($data,$cat_id=0){
		$this->db->autoExecute($this->table,$data,'update','where cat_id=' . $cat_id);
		return $this->db->affected_rows();
	}

	//获取全部数据
	public function select(){
		$sql = "select cat_id,cat_name,parent_id from  " . $this->table ;
		return $this->db->getAll($sql);
	}

	//获取一条数据
	public function find($cat_id){
		$sql = "select * from " . $this->table . " where cat_id=" . $cat_id;
		return $this->db->getRow($sql);
	}

	//获取子孙树（获取全部栏目）
	public function getaddTree($arr,$id=0,$lev=0){ //从祖宗往下找
		$tree = array();
		foreach ($arr as $v) {
			if($v['parent_id'] == $id){
				$v['lev'] = $lev;
				$tree[] = $v;
				$tree = array_merge($tree,$this->getaddTree($arr,$v['cat_id'],$lev+1));
			}
		}

		return $tree;
	}

	//获取子栏目
	public function getSon($cat_id){
		$sql = "select cat_id,cat_name,parent_id from " . $this->table . " where parent_id=" . $cat_id;
		return $this->db->getAll($sql);
	}

	//删除一条数据
	public function delete($cat_id=0){
		$sql = "delete from  " . $this->table . " where cat_id=" . $cat_id;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	//获取$cat_id的家谱树（面包屑导航）（从下面往上找，由子孙找祖宗）
	public function getditTree($id=0){
		$tree = array();
		$arr = $this->select();
		while($id > 0){
			foreach ($arr as $v) {
				if( $v['cat_id'] == $id){
					$tree[] = $v;
					$id = $v['parent_id'];
					break;
				}
			}
			
		}
		return array_reverse($tree); //（得到结果再返着从祖宗排到子孙）
	}

}

?>