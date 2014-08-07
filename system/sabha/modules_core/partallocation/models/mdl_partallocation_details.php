<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Partallocation_details extends MY_Model {
	public function __construct() {
    parent::__construct();
	$this->table_name = 'sst_parts_allocation_details';
	$this->primary_key = 'sst_parts_allocation_details.parts_allocation_details_id';
	$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
	$this->order_by = ' parts_allocation_details_id ';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	
	public function updateallocation($data,$event){
		
		
		
		}
		
	function detaillist(){
		
		$engineer_id = $this->input->post('engineer_id');
		$part_number = $this->input->post('part_number');
		$company = $this->input->post('company_id');
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		
		if($fromdate)
		{
			$this->db->where('pd.created_date >=', date("Y-m-d",date_to_timestamp($fromdate)));
			}
		if($todate)
		{
			$this->db->where('pd.created_date <=', date("Y-m-d",date_to_timestamp($todate)));
			}
		
		$this->db->select('pd.created_date,pd.event,pd.quantity_in,pd.quantity_out,c.company_title,e.engineer_name');
		$this->db->from($this->table_name.' AS pd');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id =pd.company_id','INNER');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = pd.engineer_id','INNER');
		$this->db->where('pd.engineer_id =',$engineer_id);
		$this->db->where('pd.company_id =',$company);
		$this->db->where('pd.part_number =',$part_number);
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();

		
		}
	
		function adddetail($data){
			
			$adddata['engineer_id']= $data['engineer_id'];
			$adddata['company_id']= $data['company_id'];
			$adddata['part_number']= $data['part_number'];
			$adddata['quantity_in']= $data['allocated_quantity'];
			$adddata['event']= 'Assigned';
			$adddata['quantity_out'] = 0;
			//$adddata['sc_id'] = $data['sc_id'];
			
			$this->save($adddata);
			}
			
		function revokedetail($data){
			
		    $adddata['engineer_id']= $data['engineer_id'];
			$adddata['company_id']= $data['company_id'];
			$adddata['part_number']= $data['part_number'];
			$adddata['quantity_in']= 0;
			$adddata['event']= 'Revoked';
			$adddata['quantity_out'] =$data['quantity'];
			
			$this->save($adddata);
			}	
		function usedparts($data,$engineer_id){
			//echo '<pre>';
//			print_r($data);
//			die();
			$adddata['engineer_id']= $engineer_id;
			$adddata['company_id']= $data['company_id'];
			$adddata['part_number']= $data['part_number'];
			$adddata['quantity_in']= 0;
			$adddata['event']= 'Used on Call';
			$adddata['quantity_out'] = $data['stock_quantity_out'];
			
			$this->save($adddata);
		}
		function revokefromcall($data){
		    $adddata['engineer_id']= $data['engineer_id'];
			$adddata['company_id']= $data['company_id'];
			$adddata['part_number']= $data['part_number'];
			$adddata['quantity_out']= 0;
			$adddata['event']= 'Revoked From Call';
			$adddata['quantity_in'] =$data['allocated_quantity'];
			
			$this->save($adddata);
			}	
		
		
		
		
		
		
}

?>