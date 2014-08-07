<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Tags extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_tags';
		$this->primary_key = 'sst_tags.tag_id';
	}	

	public function getTags($tag_type='0'){
		$this->db->select('tag_id,tag_text');
		$this->db->from($this->table_name);
		$this->db->where("tag_type =",$tag_type);
		$this->db->where("tag_created_by =",$this->session->userdata('user_id'));
		$this->db->order_by('tag_id');
		$result = $this->db->get();
		return $result->result();
	}
	public function save($db_array){
		$this->db->select('count(tag_id) AS cnt');
		$this->db->from($this->table_name);
		$this->db->where('tag_text =',$db_array['tag_text']);
		$result = $this->db->get();
		$tags = $result->row();
		if($tags->cnt==0){
			parent::save($db_array);
		}
	}
}
?>