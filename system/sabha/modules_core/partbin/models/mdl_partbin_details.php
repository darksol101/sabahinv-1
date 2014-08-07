<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_partbin_details extends MY_Model{
    
    public function __construct(){
        parent::__construct();
        $this->table_name= 'sst_partsbin_details';
        $this->primary_key='sst_partsbin_details.partbin_detail_id';
        $this->select_fields = "SQL_CALC_FOUND_ROWS *";
        $this->order_by='partbin_detail_id';
	  // $this->logged=$this->createlogtable($this->table_name);
        
    }
   
   public function getdetail($page){
	   $bin_name = $this->input->post('bin');
	   $part= $this->input->post('part');
	   $sc_id = $this->input->post('sc_id');
	   $bin = $this->input->post('partbin_id');
	  // print_r($bin_name);
		if ($bin_name)
		{
				$this->db->where("pb.partbin_name like '%".$bin_name."%' or pb.partbin_desc like '%".$bin_name."%' ");
			//$this->db->like('pbd.part_number',$searchtxt);
		}
		if ($part)
		{
				$this->db->where("p.part_number like '%".$part."%' or p.part_desc like '%".$part."%' ");
			//$this->db->like('pbd.part_number',$searchtxt);
		}
		if ($sc_id){
			$this->db->where('pbd.sc_id =',$sc_id);
		}
		if($bin){
			$this->db->where('pbd.partbin_id =',$bin);
		}
		$this->db->select('p.part_number,pbd.sc_id,sc.sc_name,pb.partbin_name,pb.partbin_desc,p.part_desc');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id = pbd.sc_id','left');
		$this->db->join($this->mdl_partbin->table_name.' AS pb','pb.partbin_id = pbd.partbin_id','INNER');
		$this->db->join($this->mdl_parts->table_name.' AS p','p.part_id = pbd.part_id','INNER');
		$this->db->from($this->table_name.' AS pbd');
		$this->db->order_by('pb.partbin_name ASC');
		
		$this->db->limit((int)$page['limit'],(int)$page['start']);

		$result_t = $this->db->get();
		//echo $this->db->last_query();
        
        $result['list'] = $result_t->result();
	   
	   if ($bin_name)
		{
				$this->db->where("pb.partbin_name like '%".$bin_name."%' or pb.partbin_desc like '%".$bin_name."%' ");
			//$this->db->like('pbd.part_number',$searchtxt);
		}
		if ($part)
		{
				$this->db->where("p.part_number like '%".$part."%' or p.part_desc like '%".$part."%' ");
			//$this->db->like('pbd.part_number',$searchtxt);
		}
		if ($sc_id){
			$this->db->where('pbd.sc_id =',$sc_id);
		}
		if($bin){
			$this->db->where('pbd.partbin_id =',$bin);
		}
		$this->db->select('p.part_number,pbd.sc_id,pbd.company_id, sc.sc_name,c.company_title,pb.partbin_name,p.part_desc');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id = pbd.sc_id','left');
		$this->db->join($this->mdl_company->table_name.' AS c','c.company_id = pbd.company_id','left');
		$this->db->join($this->mdl_partbin->table_name.' AS pb','pb.partbin_id = pbd.partbin_id','INNER');
		$this->db->join($this->mdl_parts->table_name.' AS p','p.part_id = pbd.part_id','INNER');
		$this->db->from($this->table_name.' AS pbd');
		$this->db->order_by('pb.partbin_name ASC');
		$result_total = $this->db->get();
       $result['total'] = $result_total->num_rows();
	   
	   
        return $result;
	   
	   
	   
	   }
	   
	   
	function checkbin($sc_id,$partbin){
		
		$this->db->select('p.part_number');
		$this->db->from($this->table_name.' AS pbd');
		$this->db->join('sst_prod_parts as p', 'pbd.part_id = p.part_id', 'INNER');
		$this->db->where('pbd.sc_id =',$sc_id);
		$this->db->where('pbd.partbin_id =',$partbin);
		$result = $this->db->get();
		if ($result->num_rows()>0){
			return 1;
			}
		else{
			return 0;
			}
		
		}
   
}

?>