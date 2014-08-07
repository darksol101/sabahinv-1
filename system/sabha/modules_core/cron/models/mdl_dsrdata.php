<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Dsrdata extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_daily_service_report';
		$this->primary_key = 'sst_daily_service_report.daily_service_report_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' daily_service_report_id';
		//$this->logged=$this->createlogtable($this->table_name);
	}
}

?>