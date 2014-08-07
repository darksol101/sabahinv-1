<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Bad_parts extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_bad_parts';
		$this->primary_key = 'sst_bad_parts.bad_parts_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' part_number ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function getbadparts($page){
		$searchtxt=$this->input->get('term');
		$pdesc=$this->input->get('pdesc');
		if(!empty($pdesc)){
			$this->db->or_like('p.part_number', $pdesc);
		}
		
		$this->db->select('bp.bad_parts_id,bp.stock_id,bp.part_number,bp.part_quantity,bp.bad_parts_status,s.event,s.event_id,s.sc_id,s.engineer_id,stock_quantity_out,s.stock_dt,s.stock_tm,s.company_id,sc.sc_name, e.engineer_name');
		$this->db->from($this->table_name.' AS bp');
		$this->db->join($this->mdl_stocks->table_name.' AS s','s.stock_id=bp.stock_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=bp.sc_id','left');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id=bp.engineer_id','left');
		$this->db->where('s.event = "stockout_used"');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by('bp.part_number ASC');
		if(!empty($searchtxt)){
			$this->db->like('bp.part_number', $searchtxt);
		}
		
		$result = $this->db->get();
		$list['list'] = $result->result();
		//echo $this->db->last_query();
		
		//for total
		if(!empty($pdesc)){
			$this->db->or_like('p.part_number', $pdesc);
		}
		
		$this->db->select('bp.bad_parts_id,bp.stock_id,bp.part_number,bp.part_quantity,bp.bad_parts_status,s.event,s.event_id,s.sc_id,s.engineer_id,stock_quantity_out,s.stock_dt,s.stock_tm,s.company_id,sc.sc_name');
		$this->db->from($this->table_name.' AS bp');
		$this->db->join($this->mdl_stocks->table_name.' AS s','s.stock_id=bp.stock_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=bp.sc_id','left');
		//$this->db->limit(10,900);
		$this->db->where('s.event = "stockout_used"');
		$this->db->order_by('bp.part_number ASC');
		if(!empty($searchtxt)){
			$this->db->like('bp.part_number', $searchtxt);
		}
		
		$result_total = $this->db->get();		
		$list['total'] = $result_total->num_rows();
		
		return $list;
	}
	function getbadpartdetails($bad_parts_id){
		$this->db->select('bp.bad_parts_id,bp.stock_id,bp.part_number,bp.part_quantity,bp.bad_parts_status,s.event,s.event_id,bp.sc_id,s.engineer_id,stock_quantity_out,s.stock_dt,s.stock_tm,s.company_id,sc.sc_name');
		$this->db->from($this->table_name.' AS bp');
		$this->db->join($this->mdl_stocks->table_name.' AS s','s.stock_id=bp.stock_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=bp.sc_id','left');
		$this->db->where('bp.bad_parts_id =', $bad_parts_id);
		$result = $this->db->get();
		
		return $result->row();
	}
}

?>