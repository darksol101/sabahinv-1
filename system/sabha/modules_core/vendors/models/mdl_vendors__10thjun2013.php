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
    public function getvenderlist(){
        $params=array(
                "select"=>"*"
                );
		
        $result= $this->get($params);
		
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
}

?>