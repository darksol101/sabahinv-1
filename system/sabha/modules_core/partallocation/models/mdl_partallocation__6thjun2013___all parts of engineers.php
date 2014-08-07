<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Partallocation extends MY_Model {
	public function __construct() {
    parent::__construct();
	$this->table_name = 'sst_parts_allocation';
	$this->primary_key = 'sst_parts_allocation.parts_allocation_id';
	$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
	$this->order_by = ' parts_allocation_id ';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	
	function checkallocation ($data){
		
		$this->db->select('allocated_quantity,parts_allocation_id');
		$this->db->from($this->table_name);
		$this->db->where('engineer_id =',$data['engineer_id']);
		$this->db->where('sc_id =',$data['sc_id']);
		$this->db->where('part_number =',$data['part_number']);
		$this->db->where('company_id =',$data['company_id']);
		$quantity = $this->db->get();
		//print_r($quantity->row);
		//echo $this->db->last_query();
		
		if ($quantity->num_rows() == 0){
				$data['parts_allocation_created_ts'] = date ("Y-m-d H:i:s") ;
				$data['parts_allocation_created_by'] = $this->session->userdata('user_id');
				$this->save($data);
		}
		else{
			$quantity = $quantity->row();
		    $id = $quantity->parts_allocation_id;
		    $data['parts_allocation_modified_ts'] = date("Y-m-d H:i:s");
			 $data['parts_allocation_modified_by'] = $this->session->userdata('user_id');
			 $data['allocated_quantity'] = $quantity ->allocated_quantity + $data['allocated_quantity'];
			 $this->save($data,$id);
			}
			//$this->mdl_partallocation_details->adddetail($data);
			return 2;
		
		}
		
	
	function getallocatedlist ($page){
		$sc_id = $this->input->post('sc_id');
		$company = $this->input->post('company');
		$engineer = $this->input->post('engineer');
		//print_r($this->input->post());
		
		if ($sc_id){
			
			$this->db->where('a.sc_id =',$sc_id);
			}
		if ($company){
			$this->db->where('a.company_id =',$company);
			}
		if ($engineer){
			$this->db->where('a.engineer_id =',$engineer);
			}
		
		$this->db->select ('e.engineer_name, a.part_number, c.company_title, a.allocated_quantity,a.engineer_id,a.company_id,a.sc_id,sc.sc_name');
		$this->db->from($this->table_name.' AS a');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = a.company_id','INNER');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = a.engineer_id','INNER');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id = a.sc_id','INNER');
		//$this->db->where('a.sc_id =',$sc_id);
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by('a.parts_allocation_modified_ts DESC,parts_allocation_created_ts DESC');
		$result = $this->db->get();
		//echo $this->db->last_query();
		$list['list']=$result->result();
		
		
		if ($sc_id){
			$this->db->where('a.sc_id =',$sc_id);
			}
		
		if ($company){
			$this->db->where('a.company_id =',$company);
			}
		if ($engineer){
			$this->db->where('a.engineer_id =',$engineer);
			}
		$this->db->select ('e.engineer_name, a.part_number, c.company_title, a.allocated_quantity,a.engineer_id,a.company_id');
		$this->db->from($this->table_name.' AS a');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = a.company_id','INNER');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = a.engineer_id','INNER');
		$this->db->order_by('a.parts_allocation_modified_ts DESC,parts_allocation_created_ts DESC');
		//$this->db->where('a.sc_id =',$sc_id);
		$result = $this->db->get();
		$list['total'] = $result->num_rows();
		return $list;
		
		
		}
		
	function revoke (){
		$part_number = $this->input->post('part_number');
		$engineer_id = $this->input->post('engineer_id');
		$quantity = $this->input->post('quantity');
		$sc_id= $this->input->post('sc_id');
		$part = explode (':',$part_number);
		$part_number = $part[0];
		$company_id = $part[1];
		$this->db->select('allocated_quantity, parts_allocation_id');
		$this->db->from($this->table_name);
		$this->db->where('part_number =',$part_number);
		$this->db->where('engineer_id =',$engineer_id);
		$this->db->where('company_id =',$company_id);
		$this->db->where('sc_id =',$sc_id);
		$result = $this->db->get();
		$removeallocation['part_number']=$part_number;
		$removeallocation['company_id']=$company_id;
		$removeallocation['quantity']=$quantity;
		$removeallocation['sc_id']=$sc_id;
		$removeallocation['engineer_id']=$engineer_id;
		//print_r($result);
		
		if($result->num_rows() != 0){
			$result = $result->row();
			if ($result->allocated_quantity >= $quantity){
					$data['allocated_quantity']= $result->allocated_quantity - $quantity;
					$this->save($data,$result->parts_allocation_id);
					$this->mdl_parts_stocks->removeallocatedpart($removeallocation);
					$this->mdl_partallocation_details->revokedetail($removeallocation);
					$ret = 3;
				}
				else {
					$ret =1;
					}
			} 
		else
			{
			$ret = 2;
			}
		
		return $ret;
		
		}
		
		
function getAvailableStocks($engineer_id,$sc_id){
	
		
		 $this->db->select('DISTINCT(ps.part_number),ps.allocated_quantity,pp.part_desc,c.company_title,');
		$this->db->from($this->table_name.' AS ps');
		$this->db->join($this->mdl_parts->table_name.' AS pp','pp.part_number=ps.part_number','inner');
		$this->db->join($this->mdl_model_parts->table_name.' AS ppm','ppm.part_number=ps.part_number','inner');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = ps.company_id','inner');
		$this->db->where('ps.engineer_id =',$engineer_id);
		$this->db->where('ps.allocated_quantity >',0);
		$this->db->where('ps.sc_id =',$sc_id);
		
		$result = $this->db->get();
		$parts = $result->result();
		//echo $this->db->last_query();
		return $parts;
	}
	
	function checkEngineerParts($sc_id,$part_number,$company_id,$engineer_id){
		$parts = new stdClass;
		$parts->stock_quantity = 0;
		$parts->parts_available = false;
		$this->db->select('ps.allocated_quantity');
		$this->db->from($this->table_name.' AS ps');
		$this->db->where('ps.sc_id =',$sc_id);
		$this->db->where('ps.part_number =',$part_number);
		$this->db->where('ps.company_id =',$company_id);
		$this->db->where('ps.engineer_id =',$engineer_id);
		$result = $this->db->get();
		//echo $this->db->last_query();
		$arr = $result->row();
		//print_r($arr->allocated_quantity);
		if($result->num_rows()>0){
			$parts->stock_quantity = $arr->allocated_quantity;
			$parts->parts_available = true;
		}
		return $parts;
	}




	function updateUsedPart($datas,$engineer_id){
		$this->db->select('allocated_quantity, parts_allocation_id');
		$this->db->from($this->table_name);
		$this->db->where('part_number =',$datas['part_number']);
		$this->db->where('engineer_id =',$engineer_id);
		$this->db->where('sc_id =',$datas['sc_id']);
		$this->db->where('company_id =',$datas['company_id']);
		$result = $this->db->get();
		$result = $result->row();
		$data['allocated_quantity']= $result->allocated_quantity - $datas['stock_quantity_out'];
					$this->save($data,$result->parts_allocation_id);
		
		
		
		}
		
		
		public function allocatedquantity($sc_id,$partnumber,$company){
			$this->db->select('sum(allocated_quantity) as allocated_quantity');
			$this->db->where('sc_id =',$sc_id);
			$this->db->where('part_number =',$partnumber);
			$this->db->where('company_id =',$company);
			$this->db->from($this->table_name);
			$res= $this->db->get();
			//echo $this->db->last_query();
			$result = $res->row();
			return ($result->allocated_quantity);
			}
		
		// function
	function getallocatedlist_edit()
	{   
		
		$part_number=$this->input->post('part_number');
		$sc_id = $this->input->post('sc_id');
		$engineer = $this->input->post('engineer');
		$searchdate=$this->input->post('searchdate');
		$allco_select=$this->input->post('allco_select');
	    if($allco_select==1)
		{	$this->db->select ('pd.parts_allocation_details_id,e.engineer_name, a.part_number, a.allocated_quantity,sc.sc_name, pd.created_date,pd.signed,pd.quantity_in as quantity');
			$this->db->where('pd.event =','Assigned');
			}
			if($allco_select==2)
		{	$this->db->select ('pd.parts_allocation_details_id,e.engineer_name, a.part_number, a.allocated_quantity,sc.sc_name, pd.created_date,pd.signed,pd.quantity_out as quantity');
			$this->db->where('pd.event =','Revoked');
			}
		if ($sc_id){
			$this->db->where('a.sc_id =',$sc_id);
					}
		
		if ($engineer){
			$this->db->where('a.engineer_id =',$engineer);
			}
		if($searchdate){
		$this->db->where('date(pd.created_date) =',$searchdate);
				}
		if ($part_number){
		$this->db->where('a.part_number = ',$part_number);	
				}
		$this->db->from($this->mdl_partallocation_details->table_name.' AS pd');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = pd.engineer_id','INNER');
		$this->db->join($this->table_name.' AS a','a.part_number =pd.part_number AND a.engineer_id = pd.engineer_id');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id = a.sc_id');
			
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
		
		}
		
		function getallocatedlist_print(){ 
		$part_number=$this->input->get('part_number');
		$sc_id = $this->input->get('sc_id');
		$engineer = $this->input->get('engineer');
		$searchdate=$this->input->get('searchdate');
		$allco_select=$this->input->get('allco_select');
	    if($allco_select==1)
		{$this->db->select ('e.engineer_name, a.part_number,sc.sc_name, pd.created_date,pd.signed,e.`engineer_phone,pd.quantity_in as quantity');
			$this->db->where('pd.event =','Assigned');
			}
			if($allco_select==2)
		{$this->db->select ('e.engineer_name, a.part_number,sc.sc_name, pd.created_date,pd.signed,e.`engineer_phone,pd.quantity_out as quantity');
			$this->db->where('pd.event =','Revoked');
			}
						
		if ($sc_id){
			$this->db->where('a.sc_id =',$sc_id);
					}
		
		if ($engineer){
			$this->db->where('a.engineer_id =',$engineer);
			}
		if($searchdate){
		$this->db->where('date(pd.created_date) =',$searchdate);
				}
		if ($part_number){
		$this->db->where('a.part_number = ',$part_number);	
				}
		$this->db->from($this->mdl_partallocation_details->table_name.' AS pd');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = pd.engineer_id','INNER');
		$this->db->join($this->table_name.' AS a','a.part_number =pd.part_number AND a.engineer_id = pd.engineer_id');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id = a.sc_id');
				
				
		$result = $this->db->get();
		
		return $result->result();
		
		}
		
	function getUnsignedList($page){
		$sc_id = $this->input->post('sc_id');
		$engineer = $this->input->post('engineer');
								
		if ($sc_id){
			$this->db->where('a.sc_id =',$sc_id);
					}
		
		if ($engineer){
			$this->db->where('a.engineer_id =',$engineer);
			}
		
		$this->db->select ('e.engineer_name, e.engineer_id,a.part_number, a.allocated_quantity,sc.sc_name,pd.event, pd.created_date,pd.signed,pd.quantity_in,pd.quantity_out,sc.sc_id,');
		$this->db->from($this->mdl_partallocation_details->table_name.' AS pd');

		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = pd.engineer_id','INNER');
		$this->db->join($this->table_name.' AS a','a.part_number =pd.part_number AND a.engineer_id = pd.engineer_id');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id = a.sc_id');
		$this->db->where("(pd.event = 'Assigned' OR pd.event = 'Revoked') ");
		$this->db->where('pd.signed = 0');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		//echo $this->db->last_query();
		//echo '<pre>'; print_r($result->result());
		$list['list']=$result->result();	
		
		$sc_id = $this->input->post('sc_id');
		$engineer = $this->input->post('engineer');
						
		if ($sc_id){
			$this->db->where('a.sc_id =',$sc_id);
					}
		
		if ($engineer){
			$this->db->where('a.engineer_id =',$engineer);
			}
		
		$this->db->select ('e.engineer_name, a.part_number, a.allocated_quantity,sc.sc_name,pd.event, pd.created_date,pd.signed,pd.quantity_in,pd.quantity_out');
		$this->db->from($this->mdl_partallocation_details->table_name.' AS pd');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = pd.engineer_id','INNER');
		$this->db->join($this->table_name.' AS a','a.part_number =pd.part_number AND a.engineer_id = pd.engineer_id');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id = a.sc_id');
		$this->db->where("(pd.event = 'Assigned' OR pd.event = 'Revoked') ");
		$this->db->where('pd.signed = 0');
		$result_count = $this->db->get();
		$list['total'] = $result_count->num_rows();
		
		return $list;
			
		}
function getAllocReportExcel()
{
	
		$sc_id = $this->session->userdata('sc_id_ptall');
		$engineer = $this->session->userdata('engineer_ptall');
					
		if ($sc_id){
			$this->db->where('a.sc_id =',$sc_id);
					}
		
		if ($engineer){
			$this->db->where('a.engineer_id =',$engineer);
			}
		
		$this->db->select ('pd.parts_allocation_details_id as sn,sc.sc_name,e.engineer_name, a.part_number,pd.event, pd.quantity_in + pd.quantity_out as quantity, pd.created_date');
		$this->db->from($this->mdl_partallocation_details->table_name.' AS pd');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = pd.engineer_id','INNER');
		$this->db->join($this->table_name.' AS a','a.part_number =pd.part_number AND a.engineer_id = pd.engineer_id');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id = a.sc_id');
		$this->db->where("(pd.event = 'Assigned' OR pd.event = 'Revoked') ");
		$this->db->where('pd.signed = 0');
		$result= $this->db->get();
		
		return $result->result();
}
}

?>