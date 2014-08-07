<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_destinations extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'tbl_destination';

		$this->primary_key = 'tbl_destination.id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";

		$this->order_by = 'ent_date desc';

	}
	
	function getCountryList($scheme, $zone=NULL){
		$params=array(
					 "select"=>"id as value, details as text",
					 "order_by"=>"details",
					// "debug"=>true
					 );
		$params["where"][]=$zone?"schemeid = '".$scheme."' and zones= '".$zone."'":"schemeid = '".$scheme."'";
		$lists=$this->get($params);
		return	$lists;
		}
		
		function getZoneList($scheme){
		$params=array(
					 "select"=>"zones as value, zones as text",
					 "order_by"=>"zones",
					 "where" =>"schemeid = '".$scheme."' group by zones",
					// "debug"=>true
					 );
		$lists=$this->get($params);
		return	$lists;
		}
		
		function getZonebyCountry($country){
			$row=$this->get_by_id($country);
			return $row->zones;
			}
		
		function getCountryById($id){
			$row=$this->get_by_id($id);
			return $row->details;
			} 
}
?>