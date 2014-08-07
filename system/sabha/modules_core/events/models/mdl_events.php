<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Events extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_bulletin_board';
		$this->primary_key = 'sst_bulletin_board.bulletin_board_id';
		$this->order_by = 'bulletin_board_name ASC';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	public function getEventslist($id)
	{
		$params=array(
					 "select"=>"bulletin_board_id, bulletin_board_name,bulletin_board_desc,bulletin_board_start_dt,bulletin_board_end_dt",
					 "where"=>array("bulletin_board_id"=>(int)$id),
					 "limit"=>1
					 );
		$bulletin_arr=$this->get($params);
		$bulletin=$bulletin_arr[0];
		$bulletin->bulletin_board_start_dt = format_date(strtotime($bulletin->bulletin_board_start_dt));
		$bulletin->bulletin_board_end_dt = format_date(strtotime($bulletin->bulletin_board_end_dt));

		return $events;
	}
	
	public function db_array() {

		$db_array = parent::db_array();
		
		return $db_array;
		

	}
	public function validate() {
		$this->form_validation->set_rules('bulletin', $this->lang->line('bulletin'), 'required');
		return parent::validate();
	}
}
?>