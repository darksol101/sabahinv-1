<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Vendors extends MY_Model{
    
    public function __construct(){
        parent::__construct();
        $this->table_name= 'sst_vendors';
        $this->primary_key='sst_vendors.vendor_id';
        $this->select_fields = "SQL_CALC_FOUND_ROWS *";
        $this->order_by='vendor_id';
	    $this->logged=$this->createlogtable($this->table_name);
        
    }
    public function getvenderlist($page){
		$phnsearch=$this->input->post('phnsearch');
		$namesearch=$this->input->post('namesearch');
		if($namesearch){
			$this->db->like('v.vendor_name',$namesearch);
		}
		if($phnsearch)
		{ $this->db->like('v.vendor_phone',$phnsearch);
		}
		$this->db->select('v.*');
		$this->db->from($this->table_name.' AS v');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$vendor = $this->db->get();
		//echo $this->db->last_query();
		$result['list']= $vendor->result();
		
		
		if($namesearch){
			$this->db->like('v.vendor_name',$namesearch);
		}
		if($phnsearch)
		{ $this->db->like('v.vendor_phone',$phnsearch);
		}
		$this->db->select('v.*');
		$this->db->from($this->table_name.' AS v');
		$vendor = $this->db->get();
        $result['total'] = $vendor->num_rows();
		
        return $result;
    } 
	
	
	function getVendorOptions()
	{
		$params=array(
					 "select"=>"vendor_name as text, vendor_id as value",
					 //"where"=>array("brand_status"=>1),
					 "order_by"=>'vendor_name'
					 );
					 $vendor_options=$this->get($params);
					 return $vendor_options;
	}   
 	function getexcelDownload()
	{
		
		$namesearch = $this->session->userdata('namesearch_s');
		$phnsearch = $this->session->userdata('phnsearch_s');
			if($phnsearch){
			$this->db->like('vendor_phone',$phnsearch);	}
			
			if($namesearch){
		$this->db->like('vendor_name',$namesearch); 
							}
		$this->db->select('vendor_id AS sn,vendor_name,vendor_phone,vendor_address');
		$this->db->from($this->table_name);
		$result=$this->db->get();
		return $result->result();
		
		
	}
	
}

?>