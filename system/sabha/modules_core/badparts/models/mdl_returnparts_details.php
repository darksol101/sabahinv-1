<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Returnparts_details extends MY_Model {
	public function __construct() {
            parent::__construct();
            $this->table_name = 'sst_return_parts_details';
            $this->primary_key = 'sst_return_parts_details.return_parts_detail_id';
            $this->select_fields = "
            SQL_CALC_FOUND_ROWS *";
            $this->order_by = ' part_number ASC';
            //$this->logged=$this->createlogtable($this->table_name);
	}
	
function getretrunlist(){
	$sc_id = $this->input->post('sc_id');
	$engineer_id =  $this->input->post('engineer_id');
	$fromdate = $this->input->post('fromdate');
	$todate = $this->input->post('todate');
	
	if($engineer_id)
	{
		$this->db->where('rpd.engineer_id =',$engineer_id);
	}
	$this->db->select('rpd.return_parts_detail_id,rpd.part_number,pd.part_desc,rpd.part_quantity,rpd.returned_date,rpd.signed,s.sc_name,e.engineer_name');
	$this->db->from($this->table_name.' AS rpd');
	$this->db->join($this->mdl_parts->table_name.' AS pd','rpd.part_number = pd.part_number','inner');
	$this->db->join($this->mdl_servicecenters->table_name.' AS s','s.sc_id = rpd.sc_id','inner');
	$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = rpd.engineer_id','inner');
	$this->db->where('rpd.sc_id =',$sc_id);
	$this->db->where("date_format(date(rpd.returned_date),'%d/%m/%Y') >=",$fromdate);
	$this->db->where("date_format(date(rpd.returned_date),'%d/%m/%Y') <=",$todate);
	$result=$this->db->get();
	//echo $this->db->last_query();
	//echo'<pre>';
	//print_r($result->result());
	$lists['list'] = $result->result();
	
	if($engineer_id)
	{
		$this->db->where('rpd.engineer_id =',$engineer_id);
	}
	$this->db->select('rpd.return_parts_detail_id,rpd.part_number,pd.part_desc,rpd.part_quantity,rpd.returned_date,rpd.signed,s.sc_name,e.engineer_name');
	$this->db->from($this->table_name.' AS rpd');
	$this->db->join($this->mdl_parts->table_name.' AS pd','rpd.part_number = pd.part_number','inner');
	$this->db->join($this->mdl_servicecenters->table_name.' AS s','s.sc_id = rpd.sc_id','inner');
	$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = rpd.engineer_id','inner');
	$this->db->where('rpd.sc_id =',$sc_id);
	$this->db->where("date_format(date(rpd.returned_date),'Y-m-d') >=",$fromdate);
	$this->db->where("date_format(date(rpd.returned_date),'Y-m-d') <=",$todate);
	$result1=$this->db->get();
	$lists['total'] = $result1->num_rows();
	
	return $lists;
}

function getReturnReportExcel()
{
	$sc_id = $this->session->userdata('sc_id_rl');
	$engineer_id =  $this->session->userdata('engineer_id_rl');
	$fromdate = $this->session->userdata('fromdate_rl');
	$todate =  $this->session->userdata('todate_rl');
	if($engineer_id)
	{
		$this->db->where('rpd.engineer_id =',$engineer_id);
	}
	$this->db->select('rpd.return_parts_detail_id,s.sc_name,e.engineer_name,rpd.part_number,pd.part_desc,rpd.part_quantity,rpd.returned_date,rpd.signed');
	$this->db->from($this->table_name.' AS rpd');
	$this->db->join($this->mdl_parts->table_name.' AS pd','rpd.part_number = pd.part_number','inner');
	$this->db->join($this->mdl_servicecenters->table_name.' AS s','s.sc_id = rpd.sc_id','inner');
	$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = rpd.engineer_id','inner');
	$this->db->where('rpd.sc_id =',$sc_id);
	$this->db->where("date_format(date(rpd.returned_date),'%d/%m/%Y') >=",$fromdate);
	$this->db->where("date_format(date(rpd.returned_date),'%d/%m/%Y') <=",$todate);
	$result=$this->db->get();
	return $result->result();
}

function getRetunlist_print(){
	$sc_id = $this->input->get('sc_id');
	$engineer_id =  $this->input->get('engineer_id');
	$fromdate = $this->input->get('fromdate');
	$todate = $this->input->get('todate');
	
	$this->db->select('rpd.return_parts_detail_id,rpd.part_number,pd.part_desc,rpd.part_quantity,rpd.returned_date,rpd.signed,s.sc_name,e.engineer_name');
	$this->db->from($this->table_name.' AS rpd');
	$this->db->join($this->mdl_parts->table_name.' AS pd','rpd.part_number = pd.part_number','inner');
	$this->db->join($this->mdl_servicecenters->table_name.' AS s','s.sc_id = rpd.sc_id','inner');
	$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = rpd.engineer_id','inner');
	$this->db->where('rpd.sc_id =',$sc_id);
	$this->db->where('rpd.engineer_id =',$engineer_id);
	
	$result=$this->db->get();
	
	return $result->result();
}

function getUnsignedList(){
	$sc_id = $this->input->post('sc_id');
	$engineer_id =  $this->input->post('engineer');
	if($engineer_id){
	$this->db->where('rpd.engineer_id =',$engineer_id);	
	}
	$this->db->select('rpd.return_parts_detail_id,rpd.part_number,pd.part_desc,rpd.part_quantity,date(rpd.returned_date) AS returned_date,rpd.signed,s.sc_name,e.engineer_name,rpd.engineer_id,rpd.sc_id');
	$this->db->from($this->table_name.' AS rpd');
	$this->db->join($this->mdl_parts->table_name.' AS pd','rpd.part_number = pd.part_number','inner');
	$this->db->join($this->mdl_servicecenters->table_name.' AS s','s.sc_id = rpd.sc_id','inner');
	$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = rpd.engineer_id','inner');
	$this->db->where('rpd.sc_id =',$sc_id);
	
	$this->db->where('rpd.signed = 0');
	
	$result=$this->db->get();
	$lists['list'] = $result->result();
	
	if($engineer_id){
	$this->db->where('rpd.engineer_id =',$engineer_id);	
	}
	$this->db->select('rpd.return_parts_detail_id,rpd.part_number,pd.part_desc,rpd.part_quantity,date(rpd.returned_date) AS returned_date,rpd.signed,s.sc_name,e.engineer_name');
	$this->db->from($this->table_name.' AS rpd');
	$this->db->join($this->mdl_parts->table_name.' AS pd','rpd.part_number = pd.part_number','inner');
	$this->db->join($this->mdl_servicecenters->table_name.' AS s','s.sc_id = rpd.sc_id','inner');
	$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = rpd.engineer_id','inner');
	$this->db->where('rpd.sc_id =',$sc_id);
	
	$this->db->where('rpd.signed = 0');
	
	$result1=$this->db->get();
	$lists['total'] = $result1->num_rows();
	
	return $lists;
}

function getUnsignedDownload(){
	$sc_id = $this->session->userdata('sc_id_bad_unsign');
	$engineer_id =  $this->session->userdata('engineer__bad_unsign');
	if($engineer_id){
	$this->db->where('rpd.engineer_id =',$engineer_id);	
	}
	$this->db->select('rpd.return_parts_detail_id,s.sc_name,e.engineer_name,rpd.part_number,pd.part_desc,rpd.part_quantity,date(rpd.returned_date) AS returned_date');
	$this->db->from($this->table_name.' AS rpd');
	$this->db->join($this->mdl_parts->table_name.' AS pd','rpd.part_number = pd.part_number','inner');
	$this->db->join($this->mdl_servicecenters->table_name.' AS s','s.sc_id = rpd.sc_id','inner');
	$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = rpd.engineer_id','inner');
	$this->db->where('rpd.sc_id =',$sc_id);
	$this->db->where('rpd.signed = 0');
	
	$result=$this->db->get();
	return $result->result();
	
	
}
	
}
?>