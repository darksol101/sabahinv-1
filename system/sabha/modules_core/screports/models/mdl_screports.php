<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_screports extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_calls';
		$this->primary_key = 'sst_calls.call_id';
		$this->logged=$this->createlogtable($this->table_name);
	}	
	public function getservicecenter()
	{		
		$query = $this->db->query("SELECT sc_id,sc_name AS diff FROM sst_service_centers ");
		$diff_result = $query->result();
		return $diff_result;					
	}
	
	public function getTotalCallsByDate($sc_id)
	{
		
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$query = $this->db->query(" SELECT COUNT(call_id) AS cnt From sst_calls WHERE call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id ");
		$diff_result = $query->result();
		return $diff_result[0];
	
	}
	
	
	public function getTotalOpenCallsByDate($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$query = $this->db->query("SELECT COUNT(call_id) AS cnt From sst_calls WHERE call_status = 0 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
		$diff_result = $query->result();
		return $diff_result[0];
	
	}	
	
	public function getTotalPendingCallsByDate($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$query = $this->db->query("SELECT COUNT(call_id) AS cnt From sst_calls WHERE call_status = 1 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
		$diff_result = $query->result();
		return $diff_result[0];
	}	
	
	public function getTotalPartpendingCallsByDate($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
			$query = $this->db->query("SELECT COUNT(call_id) AS cnt From sst_calls WHERE call_status = 2 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
			$diff_result = $query->result();
			return $diff_result[0];
	
		
	}
	
	public function getTotalClosedCallsByDate($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
			$query = $this->db->query("SELECT COUNT(call_id) AS cnt From sst_calls WHERE call_status = 3 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
		$diff_result = $query->result();
		return $diff_result[0];
	
	}
	
		
	public function getTotalCancelledCallsByDate($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$query = $this->db->query("SELECT COUNT(call_id) AS cnt From sst_calls WHERE call_status = 4 AND sc_id= $sc_id");
		$diff_result = $query->result();
		return $diff_result[0];
	
	
	}	
	public function getAverageAgingTimeCurrentPendingByDate($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		$query = $this->db->query("SELECT call_dt, call_tm FROM sst_calls WHERE call_status=1 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
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
	
	public function getAverageAgingTimePartPendingByDate($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		$query = $this->db->query("SELECT call_dt, call_tm FROM sst_calls WHERE call_status=2 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
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
	
	public function getAverageClosingTimeByDate($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		$query = $this->db->query("SELECT closure_dt, closure_tm, call_dt, call_tm FROM sst_calls WHERE call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
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
	
	
	public function getLongClosureByDate($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		$query = $this->db->query("SELECT closure_dt, closure_tm FROM sst_calls WHERE call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
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
	
	public function getClosedCallsByTimeLess($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
							
		$query = $this->db->query("SELECT COUNT(call_id) as cnt FROM sst_calls where HOUR( TIMEDIFF(concat( closure_dt, ' ', closure_tm ),concat( call_dt, ' ', call_tm ) ))<12 AND call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
			//echo $this->db->last_query();
		$closedcalls = $query->result();
		return $closedcalls[0];
	}
	public function getClosedCallsByTimeBetween($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
		
		$query = $this->db->query("SELECT COUNT(call_id) as cnt FROM sst_calls where HOUR( TIMEDIFF(concat( closure_dt, ' ', closure_tm ),concat( call_dt, ' ', call_tm ) ))>12 AND HOUR( TIMEDIFF(concat( closure_dt, ' ', closure_tm ),concat( call_dt, ' ', call_tm ) ))<24 AND call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
			
		$closedcalls = $query->result();
		return $closedcalls[0];
	}
	public function getClosedCallsByTimeGreater($sc_id)
	{
		$from_date = date("Y-m-d",date_to_timestamp($this->input->post('from_date')));
		$to_date = date("Y-m-d",date_to_timestamp($this->input->post('to_date')));
		
		if(!empty($from_date)){
			$this->db->where("call_dt >=",$from_date);
		}
		if(!empty($to_date)){
			$this->db->where("call_dt <=",$to_date);
		}
							
		$query = $this->db->query("SELECT COUNT(call_id) as cnt FROM sst_calls where HOUR( TIMEDIFF(concat( closure_dt, ' ', closure_tm ),concat( call_dt, ' ', call_tm ) ))>24 AND call_status=3 AND call_dt >= '$from_date' AND call_dt <= '$to_date' AND sc_id= $sc_id");
		$closedcalls = $query->result();
		return $closedcalls[0];
		
	}
}
?>