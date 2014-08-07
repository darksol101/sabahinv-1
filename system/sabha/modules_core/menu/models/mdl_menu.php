<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Menu extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'sst_menu';

		$this->primary_key = 'sst_menu.id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";

		$this->order_by = 'ordering';
	}

	public function getMenuByParentId($parent_id)
	{
		$user_id = $this->session->userdata('user_id');
		$query = $this->db->query('SELECT usergroup_id FROM mcb_users WHERE user_id='.$user_id.' LIMIT 1');
		$usergroupArr = $query->row();
		$user_group = $usergroupArr->usergroup_id;
		$sql = 'SELECT id,title,menu_link,parent_id,menu_class FROM '.$this->table_name.' WHERE status=1 AND parent_id='.$parent_id." AND user_groups REGEXP '(^|,| )".$user_group."($|,)' order by ordering ASC";
		$query = $this->db->query($sql);
		$menus = $query->result();
		return $menus;
	}
	public function childNumber($id){
		$params=array(
					 "select"=>"count(*) as cnt",
					 "where"=>array("parent_id"=>$id),
					 'limit'=>1
		);
		$cntarr=$this->get($params);
		return $cntarr[0]->cnt;
	}
	
  public function getParentMenu(){
  	$this->db->select('title,id,menu_class');
  	$this->db->from($this->table_name);
  	$this->db->where();
  }
  
public function menucCheckByUserID(){
		$user_id = $this->session->userdata('user_id');
		$query = $this->db->query('SELECT usergroup_id FROM mcb_users WHERE user_id='.$user_id.' LIMIT 1');
		$usergroupArr = $query->row();
		$user_group = $usergroupArr->usergroup_id;
		
		$sql = 'SELECT id,title,menu_link FROM '.$this->table_name." WHERE user_groups REGEXP '(^|,| )".$user_group."($|,)' order by ordering ASC";
	
		$query = $this->db->query($sql);
		$menus = $query->result();
		$this->load->helper('url');
		$currentComponent = explode(base_url(),current_url());
		$checkComponent = explode('/',$currentComponent[1]);
		if(count($checkComponent)>2){
			$comLink = $checkComponent[0].'/'.$checkComponent[1];
		}else{
			$comLink = $currentComponent[1];
		}
		foreach($menus as $menu){
			$accessMenu[] = $menu->menu_link;
		}
		if($comLink=='sessions/logout'||$comLink=='userprofile/changepassword' || $comLink=='access/noaccess') {
			
		}
		else{
			if(!in_array($comLink,$accessMenu)){
				redirect('access/noaccess');			
				//die('<font  style="margin:0 auto; float:left;  color:#990000" ><b>ACCESS RESTRICTED</b></font>');
				//$this->load->view('access/rejected');
				
			}
		}
		

	}
  
  
  
  
  
  
  
}

?>