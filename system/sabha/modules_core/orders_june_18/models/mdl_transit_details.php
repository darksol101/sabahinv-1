<?php defined('BASEPATH') or exit('Direct access script is not allowed');
error_reporting(0);
class Mdl_Transit_details extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_transit_details';
		$this->primary_key		=	'sst_transit_details.transit_detail_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'transit_detail_id';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	
	
	function gettransitdetails($order_id){
		$this->db->select('courior_date,courior_number,transit_detail_id,vehicle_number,transit_number,box_number,chalan_number');
		$this->db->from($this->table_name.' AS td');
		$this->db->where('td.order_id =',$order_id);
		$result = $this->db->get();
		if ($result->num_rows()>0){
		return $result->result();
		}
		else{
			$array = array();

			$data = new stdClass();
			$data->courior_date = '';
			$data->courior_number ='';
			$date->transit_detail_id= '';
			$data->vehicle_number ='';
			$data->transit_number ='';
			$data->box_number ='';
			$data->transit_detail_id='';
			$data->docket_number='';
			$data->chalan_date='';
			
			$array[]=$data;
			
			return $array;
			}
}
	
	
	function getTransitByOrder($order_id){
	
		$this->db->select('td.transit_detail_id,td.transit_number,td.courior_number,td.box_number,td.vehicle_number,td.chalan_number');
		$this->db->from($this->table_name.' AS td');
		$this->db->where('td.order_id =',$order_id);
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
	
	function getOrderId($transit_id){
		$this->db->select('td.transit_detail_id,td.order_id,td.transit_number,td.courior_number,td.box_number,td.vehicle_number,td.courior_date,o.requesting_sc_id,o.requested_sc_id,td.chalan_number');
		$this->db->from($this->table_name.' AS td');
		$this->db->join($this->mdl_orders->table_name.' AS o','o.order_id=td.order_id','INNER');
		$this->db->where('td.transit_detail_id =',$transit_id);
		$result = $this->db->get();
		return $result->row();
		//print_r($result->row());
		}
		
	function getChalanNumber($requestion_sc_id,$order_id){
		
	$sql = "SELECT CONCAT(UCASE(sc.sc_name),DATE_FORMAT(CURDATE(),'%d%m%Y'),
																LPAD((SELECT COUNT(*)+1
FROM sst_transit_details AS td WHERE transit_detail_created_ts <= NOW() AND transit_detail_created_ts > SUBDATE(NOW(),1) ),3,'0')
																,SUBSTR(o.order_number,-2)) as chalan_number
FROM sst_orders as o
INNER JOIN sst_service_centers as sc ON sc.sc_id = o.requesting_sc_id
WHERE o.order_id= ? AND o.requesting_sc_id = ? ";
$result=$this->db->query($sql,array($order_id,$requestion_sc_id));
		return ($result->row());
	
		
		}
		
	function gettransitdetailsprint($transit_id)
		{
		$this->db->select('courior_date,courior_number,transit_detail_id,vehicle_number,transit_number,box_number,chalan_number');
		$this->db->from($this->table_name.' AS td');
		$this->db->where('td.transit_detail_id =',$transit_id);
		$result = $this->db->get();
		if ($result->num_rows()>0){
		return $result->row();
		}
		else{
			$array = array();

			$data = new stdClass();
			
			$data->courior_date = '';
			$data->courior_number ='';
			$date->transit_detail_id= '';
			$data->vehicle_number ='';
			$data->transit_number ='';
			$data->box_number ='';
			$data->transit_detail_id='';
			$data->docket_number='';
			$data->chalan_date='';
			
			$array[]=$data;
			
			return $array;
			}
}
	
	
}
	
?>