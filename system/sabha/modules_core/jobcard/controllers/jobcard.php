<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Jobcard extends Admin_Controller {
	function __construct() {
		parent::__construct(TRUE);
		$this->load->language("jobcard",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$data = array();
		$this->load->view('index',$data);
	}
	function getcallslist()
	{
		$this->load->model(array('callcenter/mdl_callcenter'));
		$from_date = $this->input->post('from');
		$to_date = $this->input->post('to');
		$list = $this->mdl_callcenter->getcalList();
		
		$data = array(
					  'list'=>$list,
					  'ajaxaction'=>$this->input->post('ajaxaction')
					  );
		$this->load->view('process',$data);
	}
	function getjobcardspop()
	{
		ini_set("memory_limit","128M");
		$this->load->view('getjobcardpop');
	}
	function getjobcardpreview(){
		ini_set("memory_limit","128M");
		$this->redir->set_last_index();
		$this->redir->set_last_index();
		$this->load->helper('url');
		$this->load->model(array(
								 'callcenter/mdl_callcenter',
								 'products/mdl_products',
								 'productmodel/mdl_productmodel',
								 'brands/mdl_brands',
								 'zones/mdl_zones',
								 'zones/mdl_districts',
								 'cities/mdl_cities',
								 'customers/mdl_customers',
								 'engineers/mdl_engineers',
								 'servicecenters/mdl_servicecenters',
								 'mcb_data/mdl_mcb_data',
								 'utilities/mdl_html'
								 )
						   );
		
		$call_id = $this->input->post('call_id');
		if(empty($call_id)){
			echo 'No selection has been made';
			die();
		}
		$jobcard = $this->mdl_callcenter->getCallJobCardDetailsByRange();
		
		$data = array(
					  'jobcard'=>$jobcard
					  );
		$this->load->view('jobcard',$data);
	}
	function changecallstatus(){
		$this->redir->set_last_index();
		$this->load->model(array('callcenter/mdl_callcenter'));
		$call_id = $this->input->post('call_id');
		$arr = explode(",",$call_id);
		$call_id = implode(",",array_filter($arr, 'strlen'));
		$sql = 'UPDATE '.$this->mdl_callcenter->table_name.' SET call_print_jobcard=1 WHERE call_id IN('.$call_id.')';
		$this->db->query($sql);
	}
}

?>