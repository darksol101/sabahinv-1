<?php  (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_company extends MY_Model{
    
    public function __construct(){
        parent::__construct();
        $this->table_name= 'sst_company';
        $this->primary_key='sst_company.company_id';
        $this->select_fields = "SQL_CALC_FOUND_ROWS *";
        $this->order_by='company_id';
	    $this->logged=$this->createlogtable($this->table_name);
        
    }
    public function getcompanylist($page){
		$searchtxt= $this->input->post('searchtxt');
		if ($searchtxt)
		{
			$this->db->like('c.company_title',$searchtxt);
		}
		
		
		$this->db->select('c.*');
		$this->db->from($this->table_name.' AS c');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result_t = $this->db->get();
        $result['list'] = $result_t->result();
	    if ($searchtxt)
		{
			$this->db->like('c.company_title',$searchtxt);
		}
		$this->db->select('c.*');
		$this->db->from($this->table_name.' AS c');
		$result_total = $this->db->get();
       $result['total'] = $result_total->num_rows();
	   
	   
        return $result;
    } 
	function getCompanyOptions()
	{
		$params=array(
					 "select"=>"company_title as text, company_id as value",
					 "order_by"=>'company_title'
					 );
					 $company_options=$this->get($params);
					 return $company_options;
	}  
	
	function getcompanydetail($company_id){
	$params=array(
					 "select"=>"company_id,company_title,address,company_desc,phone",
					 "where"=>array("company_id"=>$company_id),
					 "limit"=>1
					);
		$result = $this->get($params);
			return $result;
	}
	
	function getcompanyid($company_name){
		$this->db->select('company_id');
		$this->db->from($this->table_name);
		$this->db->where('company_title =',$company_name);
		$result= $this->db->get();
		//echo $this->db->last_query();
		//print_r($result); die();
		$x=($result->row('company_id'));
		return $x;
		}
	
	public function getCompanyNameById($id){
		$params=array(
					 "select"=>"company_title",
					 "where"=>array("company_id"=>(int)$id),
					 "limit"=>1
		);
		$arr=$this->get($params);
		if(count($arr)>0){
			$company=$arr[0]->company_title;
		}else{
			$company='';
		}

		return $company;
	}
	
	
	
}

?>