<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Dailyservicereport extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_calls';
		$this->primary_key = 'sst_calls.call_id';
		$this->logged=$this->createlogtable($this->table_name);
	}	

	public function getReportsOptions($call_id){
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
	
	function getServiceCentersOptions(){
		$params=array(
					 "select"=>"sc_id as value, sc_name as text",
					 "order_by"=>'text'
					 );
		$servicecenters=$this->get($params);
		return $servicecenters;
	}
	
	public function getTotalRegisteredCalls(){
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt =",$to_date);

		if((int)$sc_id>0){
			$this->db->where("$this->table_name.sc_id =",$sc_id);
		}
		$this->db->select("COUNT(call_id) AS cnt");
		
	    $this->db->from($this->table_name);
		$result = $this->db->get();
		if($result->num_rows()>0)
		{
				$arr = $result->row();
				$total = $arr->cnt;			
				return $total;				
		}
	}
	
	public function getTotalOpenCallsByDate(){
		$sc_id = $this->input->post('sc_id');
		
		$this->db->where('call_status = 0');
		if(!empty($sc_id)){
			$this->db->where("sc_id = '$sc_id'");
		}
		$this->db->select("COUNT(call_id) AS cnt");		
		$this->db->from($this->table_name);
		$result  = $this->db->get();
			
		$totalopen = $result->row();
		
		return $totalopen->cnt;
	
	}
	public function getTotalPendingCallsByDate(){
		$sc_id = $this->input->post('sc_id');
		
		$this->db->where('call_status = 1 AND call_reason_pending!="Part Pending"');
		if(!empty($sc_id)){
			$this->db->where("sc_id = '$sc_id'");
		}
		
		$this->db->select("call_dt, call_tm");		
		$this->db->from($this->table_name);
		$query  = $this->db->get();
			
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
			
			$list['total'] = count($result);
			$list['avg_aging'] = $avg_aging;
		}else{
			$list['total'] = 0;
			$list['avg_aging'] = 0;
		}
		return $list;
			
	}
	public function getTotalPartpendingCallsByDate(){
		$sc_id = $this->input->post('sc_id');
		
		$this->db->where('call_status = 1 AND call_reason_pending="Part Pending"');
		if(!empty($sc_id)){
			$this->db->where("sc_id = '$sc_id'");
		}
		
		$this->db->select("call_dt, call_tm");		
		$this->db->from($this->table_name);
		$query  = $this->db->get();
			
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
			
			$list['total'] = count($result);
			$list['avg_aging'] = $avg_aging;
		}else{
			$list['total'] = 0;
			$list['avg_aging'] = 0;
		}
		return $list;
	}	
	
	public function getTotalClosedCallsByDate(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		
		$this->db->where("call_dt =",$to_date);
		$this->db->where('call_status = 3');
		if(!empty($sc_id)){
			$this->db->where("sc_id = '$sc_id'");
		}
		
		$this->db->select("call_dt, call_tm");		
		$this->db->from($this->table_name);
		$query  = $this->db->get();
			
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
			
			$list['total'] = count($result);
			$list['avg_aging'] = $avg_aging;
		}else{
			$list['total'] = 0;
			$list['avg_aging'] = 0;
		}
		return $list;
	}
	
	public function getTotalCancelledCallsByDate(){
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		
		$this->db->where("DATE(call_last_mod_ts) =",$to_date);
		$this->db->where("call_status =",4);
				
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		
		$this->db->select('COUNT(call_id) AS cnt ');
		$this->db->from($this->table_name);
		$result = $this->db->get();
		//echo $this->db->last_query();
		
		$cancelledcalls = $result->result();
		return $cancelledcalls[0]->cnt;
	}
	function getTotalPendingCallsLess2(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",2);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending !=","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
	function getTotalPendingCallsBetween2and7(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",2);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",7);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending !=","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
	function getTotalPendingCallsBetween7and15(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",7);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",15);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending !=","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
	function getTotalPendingCallsBetween15and30(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",15);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",30);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending !=","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
	function getTotalPendingCallsGreaterthan30(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",30);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending !=","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
	function getTotalPartPendingCallsLess2(){
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",2);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending =","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
	function getTotalPartPendingCallsBetween2and7(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",2);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",7);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending =","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
	function getTotalPartPendingCallsBetween7and15(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",7);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",15);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending =","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
	function getTotalPartPendingCallsBetween15and30(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",15);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",30);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending =","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
	function getTotalPartPendingCallsGreaterthan30(){
		
		$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		if((int)$sc_id > 0){
			$this->db->where("sc_id =",$sc_id);
		}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",30);
		$this->db->where('call_status',1);
		$this->db->where("call_reason_pending =","Part Pending");
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return NULL;
		}
	}
}
?>