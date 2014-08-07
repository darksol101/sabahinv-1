<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Warranty extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_warranty';
		$this->primary_key = 'sst_warranty.warranty_id';
		$this->order_by = 'warranty ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getWarrantyOptions()
	{
		$params = array(
						'select'=>'warranty_id as value,warranty as text',
						'order_by'=>'text'
						);
		$result = $this->get($params);
		return $result;
	}
	public function getWarrantylist($page)
	{	
		$searchtxt=$this->input->post('searchtxt');
		if($searchtxt){
			$this->db->like("$this->table_name.warranty",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.warranty_id,$this->table_name.warranty,$this->table_name.warranty_status");
	    $this->db->from($this->table_name);
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by("warranty DESC");
		$result = $this->db->get();
		$list['list'] = $result->result();
		
		//echo $this->db->last_query();
		//for counting total
		if($searchtxt){
			$this->db->like("$this->table_name.warranty",$this->db->escape_like_str($searchtxt));
		}
		
		$this->db->select("$this->table_name.warranty_id,$this->table_name.warranty,$this->table_name.warranty_status");
	    $this->db->from($this->table_name);
		$result_total = $this->db->get();
		//echo $this->db->last_query();
		$total = $result_total->num_rows();
		
		$list['total'] = $total;
		return $list;
	}	
	public function getwarrantydetails($id)
	{
		$params=array(
					 "select"=>"warranty_id, warranty",
					 "where"=>array("warranty_id"=>(int)$id),
					 "limit"=>1
					 );
		$warranty_arr=$this->get($params);
		$warranty=$warranty_arr[0];

		return $warranty;
	}
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;

	}
	public function validate() {
		$this->form_validation->set_rules('warranty', $this->lang->line('warranty'), 'required');
		return parent::validate();
	}
}
?>