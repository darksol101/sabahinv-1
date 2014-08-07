<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Purchase_Details extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_purchase_details';
		$this->primary_key = 'sst_purchase_details.purchase_details_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' part_number ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getPurchaseDetails($purchase_id){
        $this->db->select('pd.purchase_details_id,p.part_number,pd.part_quantity,part_desc AS part_description,c.company_title');
		$this->db->from($this->table_name.' as pd');
		$this->db->join($this->mdl_parts->table_name.' AS p','pd.part_id = p.part_id');
		$this->db->join($this->mdl_company->table_name.' AS c','pd.company_name = c.company_id');
		$this->db->where('pd.purchase_id =',$purchase_id);
		$result = $this->db->get();
		$details = $result->result();
		
        return	$details;
	}
	function getPurchaseByDetailId($purchase_details_id){
		$this->db->select('pd.purchase_id');
		$this->db->from($this->table_name.' as pd');
		$this->db->where('pd.purchase_details_id =',$purchase_details_id);
		
		$result = $this->db->get();
		$details = $result->row();

		return	$details;
	}
	
	function deleteuploadedparts(){
		$this->db->query("DELETE FROM sst_purchase_details WHERE TIMESTAMPDIFF(MINUTE,purchase_details_created_ts,NOW()) >10 AND purchase_id = 0");
		}
	function deleteparts($purchase_id){
		$this->db->query("DELETE FROM sst_purchase_details WHERE  purchase_id = $purchase_id");
		}
}
?>