<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_closedcallreports extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_calls';
		$this->primary_key = 'sst_calls.call_id';
		$this->logged=$this->createlogtable($this->table_name);
	}	

	public function getclosedcallreportsOptions($call_id)
	{
		$params = array(
						'select'=>'call_id as value,call_uid as value',
						'order_by'=>'value'
						);
		$this->db->where("district_id =",(int)$district_id);
		$result = $this->get($params);
		return $result;
	}
	
	public function getServiceCenterList(){
		$params=array(
					 "select"=>"sc_id, sc_name",
					 "order_by"=>"sc_name"
					 );
			
		$result = $this->get($params);
		return $result;
	}
	
	function getServiceCentersOptions()
	{
		$params=array(
					 "select"=>"sc_id as value, sc_name as text",
					 "order_by"=>'text'
					 );
		$servicecenters=$this->get($params);
		return $servicecenters;
	}
	/**
	*  This function calculates the total number of calls closed between the
	/* range of dates[closure_dt is used for the date filtering]
	*/
	public function getTotalClosedCallsByDate(){
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		$sc_id = $this->input->post('sc_id');
		
		if(!empty($from_date)){
			$this->db->where("closure_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("closure_dt <=",$to_date);
		}
		if(!empty($sc_id)){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->where('call_status',3);
		
		$this->db->select('COUNT(call_id) AS cnt');
		$this->db->from($this->table_name);
		$query = $this->db->get();
		//echo $this->db->last_query();
		
		$closedcalls = $query->row();
		return $closedcalls->cnt;
	}
	/**
	* Function getTotalClosedCallsByDate ends here
	*/
	
	/**
	* This function calculates the Average closing date/time for all closed calls between the 
	/* range of dates[closure_dt is used for the date filtering]
	/* Formula for Average closing time
	/*								$closure_dt/closure_tm - $call_dt/call_tm
	/*	$average_closing_time = --------------------------------------------------
	/*										$total_number_of_calls
	*/
	public function getAverageClosingTimeByDate(){
		
		$sc_id = $this->input->post('sc_id');
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("closure_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("closure_dt <=",$to_date);
		}
		if(!empty($sc_id)){
			$this->db->where('sc_id =',$sc_id);
		}
		$this->db->where('call_status',3);		
		$this->db->select('closure_dt, closure_tm, call_dt, call_tm');
		$this->db->from($this->table_name);
		$query = $this->db->get();
		if($query->num_rows()>0){
			$result = $query->result();
			$this->load->helper('calls');
			$aging  = 0;
			$i=0;
			foreach($result as $row){
				$aging+=CalculateAvgClosingTimeStamp($row->call_dt,$row->call_tm,$row->closure_dt,$row->closure_tm);
				$i++;
			}		
			$avg = round($aging/$i);
			$avg_aging = CalculateSecondsToDays($avg);
			return $avg_aging;
		}else{
			return 0;
		}
	}
	/**
	* Function getAverageClosingTimeByDate ends here
	*/
	public function getLongClosureByDate(){
		
		$sc_id = $this->input->post('sc_id');		
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		if(!empty($sc_id)){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->where('call_status',3);
		
		$this->db->select('closure_dt, closure_tm');
		$this->db->from($this->table_name);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
		$result = $query->result();
		//echo $this->db->last_query();
		
		$this->load->helper('calls');
		$aging  = 0;
		$i=0;
		foreach($result as $row){
			$aging+=CalculateAgingTimeStamp($row->closure_dt,$row->closure_tm);
			$i++;
		}
		$avg = round($aging/$i);
		$avg_aging = CalculateSecondsToDays($avg);
		return $avg_aging;
		}
		else{
			return 0;
		}
	}
	/**
	* This function calculates the total number of calls which average closure time less
	* than 2 days i.e 28 hours between the range of dates[closure_dt is used for the date filtering]
	*/
	public function getClosedCallsByTimeLess()
	{
		$sc_id = $this->input->post('sc_id');
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("closure_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("closure_dt <=",$to_date);
		}
		if(!empty($sc_id)){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		//$this->db->where("HOUR( TIMEDIFF(CONCAT( closure_dt, ' ', closure_tm ),CONCAT( call_dt, ' ', call_tm ) )) <48");
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),CONCAT( closure_dt, ' ', closure_tm ) ) <",2);
		$this->db->where('call_status',3);
		$query = $this->db->get();
		
		$closedcalls = $query->row();
		//echo $this->db->last_query();
		return $closedcalls->cnt;
	}
	public function getClosedCallsByTimeBetween1()
	{
		$sc_id = $this->input->post('sc_id');
		
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("closure_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("closure_dt <=",$to_date);
		}
		if(!empty($sc_id)){
			$this->db->where("sc_id =",$sc_id);
		}
		
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),CONCAT( closure_dt, ' ', closure_tm ) ) >=",2);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),CONCAT( closure_dt, ' ', closure_tm ) ) <",7);
		$this->db->where('call_status',3);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		$closedcalls = $query->row();
		return $closedcalls->cnt;
	}
	public function getClosedCallsByTimeBetween2()
	{
		$sc_id = $this->input->post('sc_id');
		
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($sc_id)){
			$this->db->where("sc_id =",$sc_id);
		}
		if(!empty($from_date)){
			$this->db->where("closure_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("closure_dt <=",$to_date);
		}
		
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),CONCAT( closure_dt, ' ', closure_tm ) ) >=",7);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),CONCAT( closure_dt, ' ', closure_tm ) ) <=",15);
		$this->db->where("call_status",3);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		
		$closedcalls = $query->row();
		return $closedcalls->cnt;
	}
	public function getClosedCallsByTimeGreater()
	{
		$sc_id = $this->input->post('sc_id');
		
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("closure_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("closure_dt <=",$to_date);
		}
		if(!empty($sc_id)){
			$this->db->where("sc_id =",$sc_id);
		}
		
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),CONCAT( closure_dt, ' ', closure_tm ) ) >",15);
		$this->db->where('call_status',3);
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		$closedcalls = $query->row();
		return $closedcalls->cnt;
	}

}
?>