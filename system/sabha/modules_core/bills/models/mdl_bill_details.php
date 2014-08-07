<?php defined('BASEPATH') or die('Direct access script in not allowed');
class Mdl_bill_details extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->table_name = 'sst_bills_details';
		$this->primary_key		=	'sst_bills_details.bill_details_id';
	}

	function getBillDetailInfo($bill_id = 0){
		$this->db->select('b.bill_id,b.part_number,b.part_id,b.part_quantity,part_rate,b.part_desc,b.price as item_rate');
		$this->db->from($this->table_name.' AS b');
		$this->db->where('b.bill_id',$bill_id);
		$result = $this->db->get();
		if($result->num_rows()==0){
			$bill = new stdClass();
			$bill->bill_id ='';
			$bill->part_number ='';
			$bill->part_quantity ='';
			$bill->part_rate='';
			$bill->part_desc='';
			$bill->item_rate='';
			return $bill;
		}
		else{
		
			return $result->result();
		}
	}
function getBillPartDetailInfo($bill_id = 0){
		$this->db->select('b.bill_id,b.part_number,b.part_id,b.part_quantity,part_rate,b.price,b.part_desc,b.company_id,c.company_title');
		$this->db->from($this->table_name.' AS b');
		$this->db->join($this->mdl_company->table_name.' AS c', 'c.company_id=b.company_id','left');
		$this->db->where('b.bill_id',$bill_id);
		$result = $this->db->get();
		if($result->num_rows()==0){
			$bill = new stdClass();
			$bill->bill_id ='';
			$bill->part_number ='';
			$bill->part_quantity ='';
			$bill->part_rate='';
			$bill->company_id=0;
			$bill->part_desc='';
			return $bill;
		}
		else{
			return $result->result();
		}
	}
	
	
	function getbillId($call_id){
	$this->db->select('bill_id');
	$this->db->from($this->table_name.' AS bd');
	$this->db->where('bd.call_id =',$call_id);
	$this->db->group_by('bill_id');
	$result = $this->db->get();
	$bill_id= $result->row();
	if($result->num_rows()==0){
			$bill_id = new stdClass();
			$bill_id->bill_id= 0;
		}
		return $bill_id;	
	}
}