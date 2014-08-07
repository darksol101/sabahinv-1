<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_Reports extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_calls';
		$this->primary_key = 'sst_calls.call_id';
		$this->logged=$this->createlogtable($this->table_name);
	}	

	public function getReportsOptions($call_id)
	{
		$params = array(
						'select'=>'call_id as value,call_uid as value',
						'order_by'=>'value'
						);
		$this->db->where("district_id =",(int)$district_id);
		$result = $this->get($params);
		return $result;
	}
	public function getTotalCallsByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		$access = array(1,2,3,5);
		
		if(!in_array($this->session->userdata('usergroup_id'),$access)){
			//$this->db->where('sc_id ='.$this->session->userdata('sc_id'));
		}
		$this->db->select("COUNT(call_id) AS cnt");
		
	    $this->db->from($this->table_name);
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0)
		{
				$arr = $result->row();
				$total = $arr->cnt;			
				return $total;				
		}
	
	}
	
	public function getTotalOpenCallsByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$this->db->select("COUNT(call_id) AS cnt")->where('call_status =', '0');
		$this->db->from($this->table_name);
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0)
		{
				$arr = $result->row();
				$totalopen = $arr->cnt;			
				return $totalopen;				
		}
	}	
	
	public function getTotalPendingCallsByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$this->db->select("COUNT(call_id) AS cnt")->where('call_status =', '1');
		$this->db->from($this->table_name);
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0)
		{
				$arr = $result->row();
				$totalpending = $arr->cnt;			
				return $totalpending;				
		}
	}	
	
	
	public function getTotalPartpendingCallsByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$this->db->select("COUNT(call_id) AS cnt")->where('call_status =', '2');
		$this->db->from($this->table_name);
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0)
		{
				$arr = $result->row();
				$totalpartpending = $arr->cnt;			
				return $totalpartpending;				
		}		
	}	
	
	public function getTotalClosedCallsByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$this->db->select("COUNT(call_id) AS cnt")->where('call_status =', '3');
	
		$this->db->from($this->table_name);
		$result = $this->db->get();
			
		if($result->num_rows()>0)
		{
				$arr = $result->row();
				$totalclosed = $arr->cnt;							
				return $totalclosed;
								
		}
	}
	
	public function getTotalCancelledCallsByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$this->db->select("COUNT(call_id) AS cnt")->where('call_status =', '4');
		$this->db->from($this->table_name);
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0)
		{
				$arr = $result->row();
				$totalcancelled = $arr->cnt;			
				return $totalcancelled;				
		}
	}	
	
	public function getAverageAgingTimeCurrentPendingByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		$query = $this->db->query("SELECT call_dt, call_tm FROM sst_calls WHERE call_status=1 AND call_dt >= '$from_date' AND call_dt <= '$to_date'");
		if($query->num_rows()>0){
		$result = $query->result();
		$this->load->helper('calls');
		$aging  = 0;
		$i=0;
		foreach($result as $row){
			$aging+=CalculateAgingTimeStamp($row->call_dt,$row->call_tm);
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
	
	public function getAverageAgingTimePartPendingByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		$query = $this->db->query("SELECT call_dt, call_tm FROM sst_calls WHERE call_status=2 AND call_dt >= '$from_date' AND call_dt <= '$to_date'");
		if($query->num_rows()>0){
		$result = $query->result();
		$this->load->helper('calls');
		$aging  = 0;
		$i=0;
		foreach($result as $row){
			$aging+=CalculateAgingTimeStamp($row->call_dt,$row->call_tm);
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
	
	
	public function getAverageClosingTimeByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		$query = $this->db->query("SELECT closure_dt, closure_tm, call_dt, call_tm FROM sst_calls WHERE call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date'");
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
		}
		else{
			return 0;
		}

	}
	
	
	public function getLongClosureByDate()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		$query = $this->db->query("SELECT closure_dt, closure_tm FROM sst_calls WHERE call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date'");
		if($query->num_rows()>0){
		$result = $query->result();
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
	
	public function getClosedCallsByTimeLess()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
							
		$query = $this->db->query("SELECT COUNT(call_id) as cnt FROM sst_calls where HOUR( TIMEDIFF(concat( closure_dt, ' ', closure_tm ),concat( call_dt, ' ', call_tm ) ))<12 AND call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date'");
			
		$closedcalls = $query->result();
		return $closedcalls[0]->cnt;
	}
	public function getClosedCallsByTimeBetween()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
	
		$query = $this->db->query("SELECT COUNT(call_id) as cnt FROM sst_calls where HOUR( TIMEDIFF(concat( closure_dt, ' ', closure_tm ),concat( call_dt, ' ', call_tm ) ))>12 AND HOUR( TIMEDIFF(concat( closure_dt, ' ', closure_tm ),concat( call_dt, ' ', call_tm ) ))<24 AND call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date'");
		$closedcalls = $query->result();
		return $closedcalls[0]->cnt;
	}
	public function getClosedCallsByTimeGreater()
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
							
		$query = $this->db->query("SELECT COUNT(call_id) as cnt FROM sst_calls where HOUR( TIMEDIFF(concat( closure_dt, ' ', closure_tm ),concat( call_dt, ' ', call_tm ) ))>24 AND call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date'");
			
		$closedcalls = $query->result();
		return $closedcalls[0]->cnt;
	}

}
?>