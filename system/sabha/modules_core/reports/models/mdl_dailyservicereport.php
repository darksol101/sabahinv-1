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
	public function getTotalRegisteredCalls($sc_id){
	//$sc_id = $this->input->post('sc_id');
		$to_date = date("Y-m-d");
		$product_id = $this->input->post('product_id');
		$this->db->where("c.call_dt =",$to_date);
		//if((int)$sc_id>0){
			$this->db->where("c.sc_id =",$sc_id);
		//}
		$this->db->select("COUNT(c.call_id) AS cnt");

		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			$total = $arr->cnt;
			return $total;
		}
	}
	public function getTotalOpenCallsByDate(){
		$sc_id = $this->input->post('sc_id');
		$product_id = $this->input->post('product_id');

		$this->db->select("COUNT(c.call_id) AS cnt");
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where('c.call_status = 0');
		$this->db->where("c.sc_id =",(int)$sc_id);
		$result  = $this->db->get();
			
		$totalopen = $result->row();

		return $totalopen->cnt;

	}
	public function getTotalPendingCallsByDate($sc_id){
		//if(intval($sc_id)>0){
		$this->db->where("c.sc_id =",(int)$sc_id);
		//}
		$product_id = $this->input->post('product_id');
		$this->db->select("COUNT(c.call_id) AS cnt");
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where('c.call_status <=',1);
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
		$this->db->where("c.sc_id =",(int)$sc_id);
		$product_id = $this->input->post('product_id');
		
		$this->db->select("COUNT(c.call_id) AS cnt");
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where('c.call_status = 1 AND c.call_reason_pending="Part Pending"');
		$result  = $this->db->get();
		
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	public function getTotalOtherpendingCallsByDate($sc_id){
		$this->db->where("c.sc_id =",(int)$sc_id);
		$product_id = $this->input->post('product_id');
		
		$this->db->select("COUNT(c.call_id) AS cnt");
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where('(c.call_status = 1 AND c.call_reason_pending!="Part Pending")');		
		$result  = $this->db->get();
		
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	public function getTotalCallsNotAssignedByDate($sc_id){
		
		$this->db->where("c.sc_id =",(int)$sc_id);
		$product_id = $this->input->post('product_id');
		
		$this->db->select("COUNT(c.call_id) AS cnt");
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("c.call_status = 0");
		$result  = $this->db->get();
		
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}

	public function getTotalClosedCallsByDate($sc_id){
		$to_date = date("Y-m-d");
		$product_id = $this->input->post('product_id');
		
		$this->db->select("c.call_dt, c.call_tm");
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("c.closure_dt =",$to_date);
		$this->db->where('c.call_status = 3');
		$this->db->where("c.sc_id =",(int)$sc_id);
		
		$query  = $this->db->get();
		
		return $query->num_rows();
	}

	public function getTotalCancelledCallsByDate($sc_id){
		$to_date = date("Y-m-d");
		$product_id = $this->input->post('product_id');
		
		$this->db->select('COUNT(c.call_id) AS cnt ');
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("DATE(c.call_last_mod_ts) =",$to_date);
		$this->db->where("c.call_status =",4);
		$this->db->where("c.sc_id =",(int)$sc_id);
		$result = $this->db->get();
		//echo $this->db->last_query();

		$cancelledcalls = $result->result();
		return $cancelledcalls[0]->cnt;
	}
	function getTotalPendingCallsLess12Hrs($sc_id){
		$to_date = date("Y-m-d");
		$product_id = $this->input->post('product_id');
		
		$this->db->select('COUNT(c.call_id) as cnt');
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("TIMESTAMPDIFF(HOUR,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) <",12);
		$this->db->where('c.call_status <=',1);
		$this->db->where("c.call_dt <=",$to_date);
		$this->db->where("c.sc_id =",(int)$sc_id);
		$result = $this->db->get();

	
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	function getTotalPendingCallsBetween12and24hrs($sc_id){
		$to_date = date("Y-m-d");
		$product_id = $this->input->post('product_id');
		
		
		$this->db->select('COUNT(c.call_id) as cnt');
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("TIMESTAMPDIFF(HOUR,CONCAT( call_dt, ' ', c.call_tm ),now() ) >=",12);
		$this->db->where("TIMESTAMPDIFF(HOUR,CONCAT( call_dt, ' ', c.call_tm ),now() ) <",24);
		$this->db->where('c.call_status <=',1);
		$this->db->where("c.call_dt <=",$to_date);
		$this->db->where("c.sc_id =",(int)$sc_id);
		
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
		$product_id = $this->input->post('product_id');
		
		$this->db->select('COUNT(c.call_id) as cnt');
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) >=",1);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) <",2);
		$this->db->where('c.call_status <=',1);
		$this->db->where("c.call_dt <=",$to_date);
		
		$this->db->where("c.sc_id =",(int)$sc_id);
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
		$product_id = $this->input->post('product_id');
		
		$this->db->select('COUNT(c.call_id) as cnt');
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) >=",2);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) <",7);
		$this->db->where('c.call_status <=',1);
		$this->db->where("c.call_dt <=",$to_date);
		
		$this->db->where("c.sc_id =",(int)$sc_id);
		
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
		$product_id = $this->input->post('product_id');
		
		$this->db->select('COUNT(call_id) as cnt');
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) >=",7);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) <",15);
		$this->db->where('c.call_status <=',1);
		$this->db->where("c.call_dt <=",$to_date);
		$this->db->where("c.sc_id =",(int)$sc_id);
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
		$product_id = $this->input->post('product_id');
		
		$this->db->select('COUNT(c.call_id) as cnt');
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) >=",15);
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) <",30);
		$this->db->where('c.call_status <=',1);
		$this->db->where("c.call_dt <=",$to_date);
		$this->db->where("c.sc_id =",(int)$sc_id);
		
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
		$product_id = $this->input->post('product_id');
		
		$this->db->select('COUNT(c.call_id) as cnt');
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id=c.model_id','left');
		$this->db->where('pm.product_id IN ('.$product_id.')');
		$this->db->where("TIMESTAMPDIFF(DAY,CONCAT( c.call_dt, ' ', c.call_tm ),now() ) >=",30);
		$this->db->where('c.call_status <=',1);
		$this->db->where("c.call_dt <=",$to_date);
		$this->db->where("c.sc_id =",(int)$sc_id);
		
		$result = $this->db->get();

		//echo $this->db->last_query();
		if($result->num_rows()>0){
			$arr = $result->row();
			return $arr->cnt;
		}else{
			return 0;
		}
	}
	
	
	
	function getreportsingle(){
		$sc_ids = $this->input->post('sc_id');
		$prod_id = $this->input->post('product_id');
		$single = "SELECT
ser.sc_name,ser.sc_id,
SUM(CASE WHEN call_dt = CURDATE() THEN 1 ELSE 0 END) AS OPENCALLS,
SUM(CASE WHEN CALL_STATUS = '3' AND closure_dt = CURDATE() THEN 1 ELSE 0 END) AS CLOSEDCALLS,
SUM(CASE WHEN CALL_STATUS = '4' AND DATE (call_last_mod_ts)= CURDATE()THEN 1 ELSE 0 END) AS CANCELLEDCALLS,
SUM(CASE WHEN CALL_STATUS <= '1' THEN 1 ELSE 0 END) AS TOTALPENDINGCALLS,
SUM(CASE WHEN CALL_STATUS = '1' AND call_reason_pending='Part Pending' THEN 1 ELSE 0 END) AS PARTPENDINGCALLS,
SUM(CASE WHEN CALL_STATUS = '1' AND call_reason_pending != 'Part Pending' THEN 1 ELSE 0 END) AS OTHERPENDINGCALLS,
SUM(CASE WHEN CALL_STATUS = '0' THEN 1 ELSE 0 END) AS CALLSNOTASSIGNED,
SUM(CASE WHEN TIMESTAMPDIFF(HOUR,CONCAT( call_dt, ' ', call_tm ),NOW() ) < 12 AND CALL_STATUS  <= '1' THEN 1 ELSE 0 END) AS LESSTHAN12HRS,
SUM(CASE WHEN TIMESTAMPDIFF(HOUR,CONCAT( call_dt, ' ', call_tm ),NOW() ) >= 12 AND TIMESTAMPDIFF(HOUR,CONCAT( call_dt, ' ', call_tm ),NOW() ) < 24  AND call_dt <= CURDATE() AND CALL_STATUS  <= '1' THEN 1 ELSE 0 END) AS C12TO24HRS,
SUM(CASE WHEN TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),NOW() ) >= 1 AND TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),NOW() ) < 2  AND call_dt <= CURDATE() AND CALL_STATUS  <= '1' THEN 1 ELSE 0 END) AS C1TO2DAYS,
SUM(CASE WHEN TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),NOW() ) >= 2 AND TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),NOW() ) < 7  AND call_dt <= CURDATE() AND CALL_STATUS  <= '1' THEN 1 ELSE 0 END) AS C2TO7DAYS,
SUM(CASE WHEN TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),NOW() ) >= 7 AND TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),NOW() ) < 15  AND call_dt <= CURDATE() AND CALL_STATUS  <= '1' THEN 1 ELSE 0 END) AS C7TO15DAYS,
SUM(CASE WHEN TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),NOW() ) >= 15 AND TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),NOW() ) < 30   AND call_dt <= CURDATE() AND  CALL_STATUS  <= '1' THEN 1 ELSE 0 END) AS C15TO30DAYS,
SUM(CASE WHEN TIMESTAMPDIFF(DAY,CONCAT( call_dt, ' ', call_tm ),NOW() ) >= 30  AND CALL_STATUS  <= '1' THEN 1 ELSE 0 END) AS GREATERTHAN30DAYS
FROM 
sst_service_centers ser 
LEFT OUTER JOIN
(
     select sc.*,pm.product_id from sst_calls sc INNER JOIN sst_prod_models pm
     on sc.model_id = pm.model_id
) as scp
on ser.sc_id = scp.sc_id and scp.product_id in (".$prod_id.")
where ser.sc_id in (".$sc_ids.")
GROUP BY ser.sc_name";

		$result = $this->db->query($single);
		return $result->result();
	
	}
}
?>