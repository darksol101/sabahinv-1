<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Events extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("events",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->model(array('events/mdl_events','bulletin/mdl_bulletin'));
		$bulletinOptions = $this->mdl_bulletin->getBulletinOptions();
		$data = array();
		$this->load->view('events',$data);
	}
	function geteventslist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('events/mdl_events','bulletin/mdl_bulletin'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list=$this->mdl_bulletin->getEvents($page);	
		$events = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data=array("events"=>$events, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process', $data);
	}
	
}
?>