<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Downloads extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_downloads';
		$this->primary_key = 'sst_downloads.download_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' download_id';
	}
}

?>