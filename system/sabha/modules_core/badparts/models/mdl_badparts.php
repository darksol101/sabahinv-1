<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Badparts extends MY_Model {

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
		$searchtxt=$this->input->post('searchtxt');
		$sc_id = $this->input->post('sc_id');
		$engineer_id = $this->input->post('engineer_id');
		
		
		if(!empty($searchtxt)){
			$this->db->like("concat(bp.part_number, ' ' , pt.part_desc)", $searchtxt);

		}
		if (!empty($sc_id)){
			$this->db->where('bp.sc_id =',$sc_id);
			}
		if(!empty($engineer_id)){
			$this->db->where('bp.engineer_id =',$engineer_id);
			}
		$this->db->select('bp.part_number,bp.part_quantity,sc.sc_name,e.engineer_name,pt.part_desc,bp.sc_id,bp.engineer_id');
		$this->db->from($this->table_name.' AS bp');
		$this->db->where('bp.part_quantity >',0);
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=bp.sc_id','left');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id=bp.engineer_id','left');
		$this->db->join($this->mdl_parts->table_name.' AS pt','pt.part_number=bp.part_number','left');
		if(is_array($page)){
			$this->db->limit((int)$page['limit'],(int)$page['start']);
		}
		$this->db->order_by('bp.part_number ASC');
		
		
		$result = $this->db->get();
		$list['list'] = $result->result();
		//echo $this->db->last_query();
		
		//for total
		if(!empty($searchtxt)){
		$this->db->like("concat(bp.part_number, ' ' , pt.part_desc)", $searchtxt);

			//$this->db->or_like('bp.part_number', $searchtxt);
		}
		if (!empty($sc_id)){
			$this->db->where('bp.sc_id =',$sc_id);
			}
		if(!empty($engineer_id)){
			$this->db->where('bp.engineer_id =',$engineer_id);
			}
		
		$this->db->select('bp.part_number,bp.part_quantity,sc.sc_name,e.engineer_name,pt.part_desc,bp.sc_id');
		$this->db->from($this->table_name.' AS bp');
		$this->db->where('bp.part_quantity >',0);
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=bp.sc_id','left');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id=bp.engineer_id','left');
		$this->db->join($this->mdl_parts->table_name.' AS pt','pt.part_number=bp.part_number','left');
		$this->db->order_by('bp.part_number ASC');
		
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