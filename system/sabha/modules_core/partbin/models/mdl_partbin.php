<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_partbin extends MY_Model{
    
    public function __construct(){
        parent::__construct();
        $this->table_name= 'sst_partsbin';
        $this->primary_key='sst_partsbin.partbin_id';
        $this->select_fields = "SQL_CALC_FOUND_ROWS *";
        $this->order_by='partbin_id';
	  // $this->logged=$this->createlogtable($this->table_name);
        
    }
    public function getpartbinlist($page){
		$searchtxt= $this->input->post('searchtxt');
		
		$sc_id = $this->input->post('sc_id_search');
		if ($searchtxt)
		{
			$this->db->where("pb.partbin_name like '%".$searchtxt."%' or pb.partbin_desc like '%".$searchtxt."%' ");
			//$this->db->like('pb.partbin_name',$searchtxt);
		}
		if ($sc_id)
		{
			$this->db->where('pb.sc_id =',$sc_id);
		}
		
		$this->db->select('pb.*,sc.sc_name ');
		$this->db->from($this->table_name.' AS pb');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','pb.sc_id = sc.sc_id');
		
		$this->db->order_by('SUBSTR(pb.partbin_name FROM 1 FOR 1), CAST(SUBSTR(pb.partbin_name FROM 2) AS UNSIGNED)'); 
		
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result_t = $this->db->get();
        $result['list'] = $result_t->result();
	    if ($searchtxt)
		{
			$this->db->where("pb.partbin_name like '%".$searchtxt."%' or pb.partbin_desc like '%".$searchtxt."%' ");
			//$this->db->like('pb.partbin_name',$searchtxt);
		}
		if ($sc_id)
		{
			$this->db->where('pb.sc_id =',$sc_id);
		}
		$this->db->select('pb.*');
		$this->db->from($this->table_name.' AS pb');
		$result_total = $this->db->get();
       $result['total'] = $result_total->num_rows();
	   
	   
        return $result;
    } 
	function getPartbinOptions()
	{
		$params=array(
					 "select"=>"partbin_name as text, partbin_id as value",
					 "order_by"=>'partbin_name'
					 );
					 $partbin_options=$this->get($params);
					 return $partbin_options;
	}  
	
	function getpartbindetail($partbin_id){
	$params=array(
					 "select"=>"partbin_id,partbin_name,partbin_desc,sc_id",
					 "where"=>array("partbin_id"=>$partbin_id),
					 "limit"=>1
					);
	$result = $this->get($params);
	
	return $result;
	
	}
	
	function getbinnamebyscid($sc_id){
		
		$params=array(
					 "select"=>"partbin_name as text, partbin_id as value",
					  "where"=>array("sc_id"=>$sc_id),
					 "order_by"=>'partbin_name'
					 );
					 $partbin_options=$this->get($params);
					 return $partbin_options;
		}
	
	
	
	
	
}

?>