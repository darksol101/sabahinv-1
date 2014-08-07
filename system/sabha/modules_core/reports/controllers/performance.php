<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Performance extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("performance",  $this->mdl_mcb_data->setting('default_language'));	
	}
	function index()
	{
		$this->callcenter();
	}
	function callcenter()
	{
		$this->load->view('performance/tab_performance_callcenter');
	}
	function getperformancereportslist()
	{
		if($this->input->is_ajax_request()==1){
			$this->redir->set_last_index();
			$this->load->model(array('reports/mdl_performance','users/mdl_users'));
			$list = $this->mdl_performance->getcallsbyusers();
			$data = array(
						  'list'=>$list
						  );
			$this->load->view('performance/performance',$data);
		}
	}
	function engineers()
	{
		$tab = 'tab_performance';
		$segment = $this->uri->segment(3);
		$this->load->model(array('servicecenters/mdl_servicecenters', 'utilities/mdl_html'));
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_id",array('class'=>'validate[required] text-input'),'value','text',$this->session->userdata('sc_id'));
		if($segment){
			$tab = 'tab_'.$segment;
		}
		$data = array(
					  'servicecenter_select'=>$servicecenter_select
					  );
		$this->load->view('performance/tab_performance_engineers',$data);
	}
	function getengineerperformance()
	{
		$this->redir->set_last_index();
		$this->load->model(array('reports/mdl_performance','engineers/mdl_engineers'));
		$list = $this->mdl_performance->getCallsByEngineers();
		
		$data = array(
					  'list'=>$list
					  );
		$this->load->view('performance/engineers',$data);
	}
}
?>