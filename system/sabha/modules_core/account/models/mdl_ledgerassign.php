<?php if( !defined('BASEPATH')) exit('Direct script access not allowed');
class Mdl_ledgerassign extends MY_Model{
	function __construct(){
		parent::__construct();
		$this->table_name = 'sst_ledger_assigns';
		$this->primary_key = 'sst_ledger_assigns.ledger_assign_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' ledger_assign_id';
	}
	function getLedgerAssignList($page){
		$this->db->select('la.ledger_assign_id,la.ledger_assign_name,sc.sc_name,l.name,la.ledger_assign_type');
		$this->db->from($this->table_name.' AS la');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc', 'sc.sc_id=la.sc_id','left');
		$this->db->join('ledgers AS l', 'l.id=la.ledger_id','left');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$this->db->order_by('la.ledger_assign_order');
		$result = $this->db->get();
		$assign_list = $result->result();
		
		$this->db->select('la.ledger_assign_id');
		$this->db->from($this->table_name.' AS la');
		$this->db->join($this->mdl_servicecenters->table_name.' AS s', 's.sc_id=la.sc_id','left');
		$result_total = $this->db->get();
		$list = array();
		$list['list'] = $assign_list;
		$list['total'] = ($result_total->num_rows());

		return	$list;
	}
	function getLedgerAssignDetails($ledger_assign_id){
		$this->db->select('la.ledger_assign_id,la.ledger_assign_name,la.ledger_assign_key,la.ledger_id,la.sc_id,la.ledger_assign_type');
		$this->db->from($this->table_name.' AS la');
		$this->db->where('la.ledger_assign_id',$ledger_assign_id);
		$result = $this->db->get();
		if($result->num_rows()>0){
			return $result->row();
		}else{
			$details = stdClass();
			$details->ledger_assign_id = 0;
			$details->ledger_assign_name = '';
			$details->ledger_assign_key = '';
			$details->ledger_id = 0;
			$details->sc_id = 0;
			$details->ledger_assign_type = 0;
			return $details;
		}
	}
	function getBillingHeads($sc_id,$ledger_assign_key){
		$this->db->select('la.ledger_assign_id,la.ledger_assign_name,la.ledger_assign_key,la.ledger_id,la.sc_id,la.ledger_assign_type');
		$this->db->from($this->table_name.' AS la');
		$this->db->join('ledgers AS l', 'l.id=la.ledger_id','left');
		$this->db->where('la.sc_id',$sc_id);
		$this->db->where('la.ledger_assign_key',$ledger_assign_key);
		$result = $this->db->get();
		if($result->num_rows()>0){
			return $result->row();
		}else{
			$details = new stdClass();
			$details->ledger_assign_id = 0;
			$details->ledger_assign_name = '';
			$details->ledger_assign_key = '';
			$details->ledger_id = 0;
			$details->sc_id = 0;
			$details->ledger_assign_type= '';
			return $details;
		}
	}
	function getBillingHeadOptions($sc_id,$ledger_assign_key){
		$this->db->select('l.name AS text,l.id AS value');
		$this->db->from($this->table_name.' AS la');
		$this->db->join('ledgers AS l', 'l.id=la.ledger_id','left');
		$this->db->where('l.sc_id',$sc_id);
		$this->db->where('la.ledger_assign_key ="'.$ledger_assign_key.'"');
		$result = $this->db->get();		
		return $result->result();
		
	}
	function getBillingHeadByKey($ledger_assign_key,$sc_id){
		$this->db->select('ledger_id,ledger_assign_type');
		$this->db->where('ledger_assign_key',$ledger_assign_key);
		$this->db->where('sc_id',$sc_id);
		$this->db->from($this->table_name);
		$result = $this->db->get();
		return $result->row();
	}
}
?>
