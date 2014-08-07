<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Menus extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_menu';
		$this->primary_key = 'sst_menu.id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		//$this->order_by = 'ordering, parent_id';
		$this->logged=$this->createlogtable($this->table_name);
	}



	function getMenuOptions()
	{	
		$params = array(
						"select" => "id as value, title as text, parent_id, user_groups",
												
						);		
		$result = $this->get($params);
		//echo $this->db->last_query();
		if (count($result) > 0){
			return $result;
		
		}else{
			return '';
		}
	}
	function getMenusByGroupId($groupID){

		$query = "SELECT id,title FROM sst_menu where FIND_IN_SET($groupID,concat(user_groups,'')) ORDER BY ordering,parent_id";
		$result  = $this->db->query($query);
		return $result->result();
		//return $result;
	
	}
	function getMenusByGroup($groupID){

		$query = "SELECT id,title FROM sst_menu where FIND_IN_SET($groupID,concat(user_groups,''))";
		$result  = $this->db->query($query);
		echo json_encode($result->result());
	}
	
	function getMenu(){
		$params = array(
						"select" => "*",
						"where"=>'parent_id = 0');
		$result = $this->get($params);
		if (count($result) > 0){
			return $result;
		
		}else{
			return '';
		}
	}
function getSubmenus($menu){
		$params = array(
						"select" => "*",
						"where"=>"parent_id ='".$menu."'");
		$result = $this->get($params);
		if (count($result) > 0){
			return $result;
		
		}else{
			return '';
		}
	}
	
	function saveGroup($unselected,$menuId, $groupID){

		for($i=0;$i<count($menuId);$i++){
			$params = array ("select" => "id,user_groups",
							 "where" => array("id"=>$menuId[$i])
							 );
			$result = $this->get($params);
			$result_array = explode(",",$result[0]->user_groups);
			$status =TRUE;
			for($j=0;$j<count($result_array);$j++){
					if($result_array[$j]==$groupID)
						$status = FALSE;				
				}			
			if($status){
				$result_array[] = $groupID;
				$data = array(
							  "user_groups"=>implode(',',$result_array),
							  "up_date" => date("Y-m-d"),
							 // "ent_by" => $this->session->userdata('user_id')
							  );
			    $this->save($data,$result[0]->id);
				
			}			
		}
		for($j=0;$j<count($unselected);$j++){			
			$params = array ("select" => "id,user_groups",
							 "where" => array("id"=>$unselected[$j])
							 );
			$result = $this->get($params);
			$result_array = explode(",",$result[0]->user_groups);
			$new_array =array();
			$status =FALSE;
			for($k=0;$k<count($result_array);$k++){				
					if($result_array[$k]!=$groupID)	{
						$new_array[] = $result_array[$k];
					}
					else{
						$status = TRUE;
					}
				}			
				if($status){
					$data = array(
							  "user_groups"=>implode(',',$new_array),
							  "up_date" => date("Y-m-d"),
							 // "ent_by" =>  $this->session->userdata('user_id')
							  );
					$this->save($data,$result[0]->id);				
				}
				
			}
			
		}
	}



?>