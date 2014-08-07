<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Order_details extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_order_parts';
		$this->primary_key		=	'sst_order_parts.order_part_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'order_part_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
}
?>