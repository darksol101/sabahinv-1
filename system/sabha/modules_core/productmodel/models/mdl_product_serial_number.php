<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Product_serial_number extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_call_serial_numbers';
		$this->primary_key = 'sst_product_serial_numbers.call_serial_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' call_serial_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function getProductSerialNumbersByCall($call_id)
	{
		$params = array(
						'select'=>'call_serial_id,call_id,call_serial_no',
						'where'=>array('call_id'=>$call_id)
						);
		$result = $this->get($params);
		return $result;
	}
}
?>