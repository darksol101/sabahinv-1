<?php  defined('BASEPATH') or exit('Direct access script is not allowed');
 class Mdl_Calls_orders extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_calls_orders';
		$this->primary_key		=	'sst_calls_orders.calls_orders_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'calls_orders_id';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	
	
	function savecallorder($datas){
	 foreach ($datas  as $data){
		$call_data['call_id']=$data->call_id ;
		$call_data['engineer_id']= $data->engineer_id;
		$call_data['part_number']=$data->part_number;
		$call_data['part_quantity']=$data->part_quantity;
		$call_data['sc_id']= $data->sc_id;
		$this->save($call_data);
		}
	}
		
		function callorders($page){
			
			$sc_id = $this->input->post('sc_id');
			$fromdate = $this->input->post('fromdate');
			$todate = $this->input->post('todate');
			 
			 if ($sc_id){
				 $this->db->where('cd.sc_id =',$sc_id);
				 }
			if (empty($sc_id) && $this->session->userdata('usergroup_id')==1){
				 $this->db->where('cd.sc_id =',$sc_id);
				 }
			
		/*	if($datefrom)
			{			
				$this->db->where('cd.calls_orders_created_ts >=', date("Y-m-d",date_to_timestamp($datefrom)));	
			}
			
			if($todate)
			{			
				$this->db->where('o.order_dt <=', date("Y-m-d",date_to_timestamp($todate)));	
			}
		*/
			$this->db->select('part_number,part_quantity,calls_orders_id,sc.sc_name,e.engineer_name,c.call_uid');
			$this->db->from($this->table_name.' AS cd');
			$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id=cd.engineer_id','left');
			$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=cd.sc_id','left');
			$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id=cd.call_id','left');
			$this->db->order_by('sc.sc_name');
			if(isset($page['limit'])){
			$this->db->limit((int)$page['limit'],(int)$page['start']);
			}
			$result = $this->db->get();
			//echo $this->db->last_query();
			
			if ($sc_id){
				 $this->db->where('cd.sc_id =',$sc_id);
				 }
			
			if (empty($sc_id) && $this->session->userdata('usergroup_id')==1){
				 $this->db->where('cd.sc_id =',$sc_id);
				 }
			$this->db->select('part_number,part_quantity,calls_orders_id,sc.sc_name,e.engineer_name,c.call_uid');
			$this->db->from($this->table_name.' AS cd');
			$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id=cd.engineer_id','left');
			$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=cd.sc_id','left');
			$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id=cd.call_id','left');
			$this->db->order_by('sc.sc_name');
			$result_total = $this->db->get();
			$parts['list'] = $result->result();
			$parts['total'] = $result_total->num_rows();
			
			
			return $parts;
			
			}
		
	function getparts(){
		$value = ($this->input->post('values'));

		
		$this->db->select('calls_orders_id,call_id,engineer_id,part_number,part_quantity,sc_id,order_part_id');
		$this->db->from($this->table_name);
		$this->db->where('calls_orders_id IN ('.$value.')');
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		return $result->result();
		
		}
		
	function deleteparts(){
		$value = ($this->input->post('values'));
        $this->db->query('delete  from '.$this->table_name.' where calls_orders_id in ('.$value.')');
		}

	
	function getrequestedparts($call_id){
		
		$this->db->select('co.part_number,co.part_quantity,pt.part_desc,co.order_part_id,co.order_number,co.order_status,co.order_id,co.calls_orders_id');
		$this->db->from($this->table_name.' AS co');
		$this->db->join($this->mdl_parts->table_name.' AS pt','pt.part_number=co.part_number','LEFT');
		$this->db->where('co.call_id =',$call_id);
		$result = $this->db->get();
		return $result->result();
		}
	
	function getPoRequestpart($call_id){
	$this->db->select('co.part_number,co.part_quantity,pt.part_desc,pt.part_customer_price');
		$this->db->from($this->table_name.' AS co');
		$this->db->join($this->mdl_parts->table_name.' AS pt','pt.part_number=co.part_number','LEFT');
		$this->db->where('co.call_id =',$call_id);
		$result = $this->db->get();
		return $result->result();
	
	}
}