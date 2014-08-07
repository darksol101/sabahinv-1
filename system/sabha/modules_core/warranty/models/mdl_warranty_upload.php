<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Warranty_upload extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_warranty_uploads';
		$this->primary_key = 'sst_warranty_uploads.warranty_upload_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = 'warranty_upload_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function getManuals($model_id){		
		$this->db->select('manual_id,model_id,file_name,full_path,file_ext');
		$this->db->from($this->table_name);
		$this->db->where('model_id =',$model_id);
		$manuals = $this->db->get();
		if((int)$model_id>0){
		//echo $this->db->last_query();
			return $manuals->result();
		}else{
			return array();
		}
	}
	function getWarrantyUpload($call_id){
		$this->db->select('warranty_upload_id,call_id,warranty_file_name,warranty_full_path,warranty_file_ext');
		$this->db->from($this->table_name);
		$this->db->where('call_id =',$call_id);
		$result = $this->db->get();
		$warranty_uploads = $result->result();
		return $warranty_uploads;
	}
}
?>