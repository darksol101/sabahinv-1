<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Badparts_order_parts_details extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_badparts_order_parts_details';
		$this->primary_key		=	'sst_badparts_order_parts_details.badparts_order_part_details_id';
		$this->select_fields	= 'SQL_CALC_FOUND_ROWS *';
		$this->order_by			= 'badparts_order_part_details_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function getPartOderDetailsByOrderParts($order_part_id){
		$this->db->select('ptd.order_part_details_id, ptd.part_quantity,ptd.order_part_status');
		$this->db->from($this->table_name.' AS ptd');
		$this->db->where('ptd.order_part_id =',$order_part_id);
		$this->db->order_by('ptd.order_parts_details_created_by ASC');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
	
	function getDispatchedQuantity($badparts_order_part_id){
		$this->db->select('sum(pd.part_quantity+pd.differance) as dispatched_quantity');
		$this->db->from($this->table_name.' AS pd');
		$this->db->where('pd.badparts_order_part_id =',$badparts_order_part_id);
		//$this->db->where('pd.order_part_status = 0');
		$result = $this->db->get();
		$result = $result->row();
		return $result;
	}
	function deliveredDifferance($order_part_id){
		$this->db->select('sum(pd.differance) as differance');
		$this->db->from($this->table_name.' AS pd');
		$this->db->where('pd.order_part_id =',$order_part_id);
		$this->db->where('pd.order_part_status = 1');
		$result = $this->db->get();
		$result = $result->row();
		return $result;
	}
	
	function getDeliveredQuantity($badparts_order_part_id){
		$this->db->select('sum(pd.part_quantity) as delivered_quantity');
		$this->db->from($this->table_name.' AS pd');
		$this->db->where('pd.badparts_order_part_id =',$badparts_order_part_id);
		$this->db->where('pd.order_part_status = 1');
		$result = $this->db->get();
		//echo $this->db->last_query();
		$result = $result->row();
		return $result;
		}
	function getDispatchedPartByChalan($order_part_id,$transit_id){
		$this->db->select('(pd.part_quantity+pd.differance) as part_quantity');
		$this->db->from($this->table_name.' AS pd');
		$this->db->where('pd.badparts_order_part_id =',$order_part_id);
		$this->db->where('pd.badparts_transit_id =',$transit_id);
		//$this->db->where('pd.order_part_status = 0');
		$result = $this->db->get();
		if ($result->num_rows()>0){
		$data = $result->row();
		}else{
			$data = new stdClass();
			$data->part_quantity ='';
			
			}
		return $data;
		
		}
		
	function orderPartDetailId($order_part_id,$transit_id){
		$this->db->select('pd.badparts_order_part_details_id');
		$this->db->from($this->table_name.' AS pd');
		$this->db->where('pd.badparts_order_part_id =',$order_part_id);
		$this->db->where('pd.badparts_transit_id =',$transit_id);
		$result = $this->db->get();
		if ($result->num_rows()>0){
		$data = $result->row();
		}else{
			$data = new stdClass();
			$data->badparts_order_part_details_id ='';
			}
		return $data;
		}
		
		
	function getDifferance($order_part_id,$transit_id){
		
		$this->db->select('pd.differance');
		$this->db->from($this->table_name.' AS pd');
		$this->db->where('pd.badparts_order_part_id =',$order_part_id);
		$this->db->where('pd.badparts_transit_id =',$transit_id);
		//$this->db->where('pd.order_part_status = 1');
		$result = $this->db->get();
		if ($result->num_rows()>0){
		$data = $result->row();
		}else{
			$data = new stdClass();
			$data->differance =0;
			
			}
		return $data;
		
		}
	function getReceivedQuantityByChalan($order_part_id,$transit_id){
		$this->db->select('pd.part_quantity');
		$this->db->from($this->table_name.' AS pd');
		$this->db->where('pd.badparts_order_part_id =',$order_part_id);
		$this->db->where('pd.badparts_transit_id =',$transit_id);
		$this->db->where('pd.order_part_status = 1');
		$result = $this->db->get();
		if ($result->num_rows()>0){
		$data = $result->row();
		}else{
			$data = new stdClass();
			$data->part_quantity ='';
			
			}
		return $data;
		
		}
	function getOrderPartDetailTransit($order_part_detail_id){
		$this->db->select('pd.part_quantity,pd.differance,pd.order_part_status');
		$this->db->from($this->table_name.' AS pd');
		$this->db->where('pd.badparts_order_part_details_id =',$order_part_detail_id);
		$result = $this->db->get();
		$data = $result->row();
		return $data;
		
		}
	function gethighest(){
		$this->db->select('count(order_part_detail_id) as num');
		$this->db->from($this->table_name.' AS pd');
		$result = $this->db->get();
		$data = $result->num_rows();
		return $data;
			
			
			}
		
	}
?>