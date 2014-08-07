<?php defined('BASEPATH') or exit('Direct access is not allowed');
class Mdl_Returnservice_center extends MY_Model{    
    function __construct() {
        parent::__construct();
        $this->table_name = 'sst_return_sc';
        $this->primary_key = 'sst_return_sc.return_sc_id';
    }
    function save($data,$id=0){
        if($id==0){
            return parent::save($data);
        }else{
            return parent::save($data,$id);
        }
    }
    function getPartsOrderList(){
        $sc_id = $this->input->post('sc_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        if((int)$sc_id >0){
                $this->db->where('rs.from_sc_id =',$sc_id);
        }
        if($from_date){
                $this->db->where('rs.return_sc_created_ts >=',date("Y-m-d",date_to_timestamp($from_date)));
        }
        if($to_date){
                $this->db->where('rs.return_sc_created_ts <=',date("Y-m-d",date_to_timestamp($to_date)));
        }	
        $this->db->select('rs.return_sc_id,sc.sc_name AS frm_sc_name,rs.return_sc_status');
        $this->db->from($this->table_name.' AS rs');
        $this->db->join($this->mdl_servicecenters->table_name.' AS sc', 'sc.sc_id=rs.from_sc_id','left');
        //$this->db->join($this->mdl_servicecenters->table_name.' AS tsc', 'tsc.sc_id=rs.to_sc_id','left');
        $result = $this->db->get();
        return $result->result();
    }
    function getPartsOrderDetails($return_sc_id){
        $this->db->select('rs.return_sc_id,rs.part_number,rs.part_quantity,rs.from_sc_id, rs.to_sc_id,rs.return_sc_status');        
        $this->db->from($this->table_name.' AS rs');
        $this->db->join($this->mdl_servicecenters->table_name.' AS sc', 'sc.sc_id=rs.from_sc_id','left');
        $this->db->join($this->mdl_servicecenters->table_name.' AS tsc', 'tsc.sc_id=rs.to_sc_id','left');
        $this->db->where('rs.return_sc_id =',$return_sc_id);
        $result = $this->db->get();
        return $result->row();
    }
    function checkPartsInSc($sc_id,$part_number){
        $this->db->select('sum(rs.part_quantity) AS total');
        $this->db->from($this->table_name.' AS rs');
        $this->db->where('rs.part_number',$part_number);
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
    /*function totalPartsInSc($sc_id,$part_number){
        $this->db->select('sum(part_quantity) AS total');
        $this->db->from($this->table_name);
        $this->db->where('part_number',$part_number);
        $this->db->where('from_sc_id',$sc_id);
        $this->db->where('return_sc_status',3);
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
    }*/
	function getDetails($return_sc_id){
    	$this->db->select('rd.return_sc_id,rd.from_sc_id,rd.to_sc_id,rd.return_sc_status');
        $this->db->from($this->table_name.' AS rd');
        $this->db->where('rd.return_sc_id',$return_sc_id);
        $result = $this->db->get();
        if($result->num_rows()==0){
        	$details = new stdClass();
        	$details->return_sc_id=0;
        	$details->from_sc_id = $this->session->userdata('sc_id');
        	$details->to_sc_id = $this->mdl_mcb_data->get('main_factory');
        	$details->return_sc_status = 0;
        	return  $details;
        }else{
        	return $result->row();
        }
        
    }
}
?>
