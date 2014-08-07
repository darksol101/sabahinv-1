<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Cronedailyservicereport extends MY_Model {
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
	public function getTotalRegisteredCalls($sc_id){
		$to_date = date("Y-m-d");
		$this->db->where("call_dt =",$to_date);

		//if((int)$sc_id>0){
		$this->db->where("$this->table_name.sc_id =",(int)$sc_id);
		//}
		$this->db->select("COUNT(call_id) AS cnt");

		$this->db->from($this->table_name);
		$result = $this->db->get();
		if($result->num_rows()>0){
			$arr = $result->row();
			$total = $arr->cnt;
			return $total;
		}
	}
	public function getTotalOpenCallsByDate(){
		$sc_id = $this->input->post('sc_id');


		//if((int)$sc_id>0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select("COUNT(call_id) AS cnt");
		$this->db->where('call_status = 0');
		$this->db->from($this->table_name);
		$result  = $this->db->get();
			
		$totalopen = $result->row();

		return $totalopen->cnt;

	}
	public function getTotalPendingCallsByDate($sc_id){
		//if(intval($sc_id)>0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}

		$this->db->select("COUNT(call_id) AS cnt");
		$this->db->from($this->table_name);
		$this->db->where('call_status <=',1);
		$result  = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	public function getTotalPartpendingCallsByDate($sc_id){
		//if((int)$sc_id>0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select("COUNT(call_id) AS cnt");
		$this->db->from($this->table_name);
		$this->db->where('call_status = 1 AND call_reason_pending="Part Pending"');
		$result  = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	public function getTotalOtherpendingCallsByDate($sc_id){
		//if((int)$sc_id>0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select("COUNT(call_id) AS cnt");
		$this->db->where('(call_status = 1 AND call_reason_pending!="Part Pending")');
		$this->db->from($this->table_name);
		$result  = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	public function getTotalCallsNotAssignedByDate($sc_id){
		//if((int)$sc_id>0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select("COUNT(call_id) AS cnt");
		$this->db->where("call_status = 0");
		$this->db->from($this->table_name);
		$result  = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}

	public function getTotalClosedCallsByDate($sc_id){
		$to_date = date("Y-m-d");
		$this->db->where("closure_dt =",$to_date);
		$this->db->where('call_status = 3');
		//if((int)$sc_id>0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select("call_dt, call_tm");
		$this->db->from($this->table_name);
		$query  = $this->db->get();
		return $query->num_rows();
	}

	public function getTotalCancelledCallsByDate($sc_id){
		$to_date = date("Y-m-d");

		$this->db->where("DATE(call_last_mod_ts) =",$to_date);
		$this->db->where("call_status =",4);

		//if((int)$sc_id > 0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}

		$this->db->select('COUNT(call_id) AS cnt ');
		$this->db->from($this->table_name);
		$result = $this->db->get();
		//echo $this->db->last_query();

		$cancelledcalls = $result->result();
		return $cancelledcalls[0]->cnt;
	}
	function getTotalPendingCallsLess12Hrs($sc_id){
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		//if((int)$sc_id > 0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(HOUR,CONCAT( call_dt, ' ', call_tm ),now() ) <",12);
		$this->db->where('call_status <=',1);
		$result = $this->db->get();

		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	function getTotalPendingCallsBetween12and24hrs($sc_id){
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		//if((int)$sc_id > 0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(HOUR,CONCAT( call_dt, ' ', call_tm ),now() ) >=",12);
		$this->db->where("TIMESTAMPDIFF(HOUR,CONCAT( call_dt, ' ', call_tm ),now() ) <",24);
		$this->db->where('call_status <=',1);
		$result = $this->db->get();

		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	function getTotalPendingCallsBetween1and2($sc_id){
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		//if((int)$sc_id > 0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",1);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",2);
		$this->db->where('call_status <=',1);
		$result = $this->db->get();

		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	function getTotalPendingCallsBetween2and7($sc_id){
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		//if((int)$sc_id > 0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",2);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",7);
		$this->db->where('call_status <=',1);
		$result = $this->db->get();

		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	function getTotalPendingCallsBetween7and15($sc_id){
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		//if((int)$sc_id > 0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",7);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",15);
		$this->db->where('call_status <=',1);
		$result = $this->db->get();

		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	function getTotalPendingCallsBetween15and30($sc_id){
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		//if((int)$sc_id > 0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",15);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) <",30);
		$this->db->where('call_status <=',1);
		$result = $this->db->get();

		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	function getTotalPendingCallsGreaterthan30($sc_id){
		$to_date = date("Y-m-d");
		$this->db->where("call_dt <=",$to_date);
		//if((int)$sc_id > 0){
		$this->db->where("sc_id =",(int)$sc_id);
		//}
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),now() ) >=",30);
		$this->db->where('call_status <=',1);
		$result = $this->db->get();

		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
}
?>