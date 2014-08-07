<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Adjustment extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_adjustments';
		$this->primary_key = 'sst_adjustments.adjustment_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' part_number ASC';
		//$this->logged=$this->createlogtable($this->table_name);
	}
	function getadjustments($page){
		$searchtxt=$this->input->get('term');
		$pdesc=$this->input->get('pdesc');
		if(!empty($pdesc)){
                    $this->db->or_like('a.part_number', $pdesc);
		}
		$from_date = $this->input->post('from_date');
                $to_date = $this->input->post('to_date');
                if($from_date){
			$this->db->where('DATE(a.adjustment_created_ts) >=',date("Y-m-d",date_to_timestamp($from_date)));
		}
		if($to_date){
			$this->db->where('DATE(a.adjustment_created_ts) <=',date("Y-m-d",date_to_timestamp($to_date)));
		}
                
		$this->db->select('a.adjustment_id,a.part_number,a.part_quantity,a.approved,a.action,a.sc_id,sc.sc_name');
		$this->db->from($this->table_name.' AS a');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=a.sc_id','left');
		if(is_array($page)){
			$this->db->limit((int)$page['limit'],(int)$page['start']);
		}
		$this->db->order_by('a.part_number ASC');
		if(!empty($searchtxt)){
			$this->db->like('a.part_number', $searchtxt);
		}
		
		$result = $this->db->get();
		$list['list'] = $result->result();
		//echo $this->db->last_query();
		
		
		$this->db->select('a.adjustment_id,a.part_number,a.part_quantity,a.approved,a.action,a.sc_id,sc.sc_name');
		$this->db->from($this->table_name.' AS a');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=a.sc_id','left');
		$this->db->order_by('a.part_number ASC');
		if(!empty($searchtxt)){
			$this->db->like('a.part_number', $searchtxt);
		}
		
		$result_total = $this->db->get();		
		$list['total'] = $result_total->num_rows();
		
		return $list;
	}
	function getpartadjustmentdetails($adjustment_id){
            $this->db->select('a.adjustment_id,a.part_number,a.part_quantity,a.approved,a.action,a.sc_id,sc.sc_name');
            $this->db->from($this->table_name.' AS a');
            $this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=a.sc_id','left');
            $this->db->where('a.adjustment_id =', $adjustment_id);
            $result = $this->db->get();

            return $result->row();
	}
	public function getBadPartsOptionsByEngineer($engineer_id){
            //$this->db->select('bp.part_number as text,bp.part_number as value, sum(bp.part_quantity)-COALESCE(sum(rp.part_quantity),0) AS bad_part_qty',false);
            $this->db->select('bp.part_number as text,bp.part_number as value');
            $this->db->from($this->table_name.' AS bp');
            //$this->db->join($this->mdl_returnparts->table_name.' AS rp','rp.part_number=bp.part_number','left');
            $this->db->where('bp.engineer_id =', (int)$engineer_id);
            $this->db->group_by('bp.part_number');
            //$this->db->having('bad_part_qty>0');
            $result = $this->db->get();
            $bad_parts = $result->result();
            //echo $this->db->last_query();
            return $bad_parts;
	}
}
?>