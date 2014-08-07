<?php defined('BASEPATH') or exit('Direct access is not allowed');
class Mdl_Returnservice_Center_Details extends MY_Model{    
    function __construct() {
        parent::__construct();
        $this->table_name = 'sst_return_sc_details';
        $this->primary_key = 'sst_return_sc_details.return_sc_detail_id';
    }
    function save($data,$id=0){
        if($id==0){
            return parent::save($data);
        }else{
            return parent::save($data,$id);
        }
    }
    
	function checkPartsInSc($sc_id,$part_number){
        $this->db->select('sum(rd.part_quantity) AS total');
        $this->db->from($this->table_name.' AS rd');
        $this->db->join($this->mdl_returnservice_center->table_name.' AS rs' ,'rs.return_sc_id=rd.return_sc_id','left');
        $this->db->where('rd.part_number',$part_number);
        $this->db->where('rs.from_sc_id',$sc_id);
        $result = $this->db->get();
        $sc_parts = $result->row();
	
        $this->db->select('sum(rp.part_quantity) AS total');
        $this->db->from($this->mdl_returnparts->table_name.' AS rp');
        $this->db->where('rp.part_number',$part_number);
        $this->db->where('rp.sc_id',$sc_id);
        $result = $this->db->get();
        $returned_parts = $result->row();
        $parts = ((int)$returned_parts->total - (int)$sc_parts->total);
        return $parts;
    }
    function getReturnedParts($return_sc_id){
    	$this->db->select('rd.return_sc_detail_id,rd.return_sc_id,rd.part_number,rd.part_quantity');
        $this->db->from($this->table_name.' AS rd');
        $this->db->where('rd.return_sc_id',$return_sc_id);
        $result = $this->db->get();
        return $result->result();
        
    }
	function getavailableparts(){
        $this->db->select('rd.part_number AS text,rd.part_number AS value');
        $this->db->from($this->table_name.' AS rd');
		$this->db->join($this->mdl_returnservice_center->table_name.' AS rc','rc.return_sc_id=rd.return_sc_id');
        $this->db->where('rc.return_sc_status',3);
        $this->db->order_by('rd.part_number');
        $this->db->group_by('rd.part_number');
        $result = $this->db->get();
        $sc_parts = $result->result();
        //echo $this->db->last_query();
        return $sc_parts;
    }
	function totalPartsInSc($sc_id,$part_number){
        $this->db->select('sum(rd.part_quantity) AS total');
        $this->db->from($this->table_name.' AS rd');
		$this->db->join($this->mdl_returnservice_center->table_name.' AS rc','rc.return_sc_id=rd.return_sc_id');
        $this->db->where('rd.part_number',$part_number);
        $this->db->where('rc.to_sc_id',$sc_id);
        $this->db->where('rc.return_sc_status',3);
        $result = $this->db->get();
		
        $sc_parts = $result->row();
        
        $this->db->select('sum(a.part_quantity) AS total');
        $this->db->from($this->mdl_adjustment->table_name.' AS a');
        $this->db->where('a.part_number',$part_number);
        $this->db->where('a.sc_id',$sc_id);
        $result = $this->db->get();
        $adjusted_parts = $result->row();
        $total = $sc_parts->total-$adjusted_parts->total;
        return $total;
    }
}
?>
