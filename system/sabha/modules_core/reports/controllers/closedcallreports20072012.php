<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class closedcallreports extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("closedcallreports",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model('mdl_closedcallreports');	
		$this->load->model('servicecenters/mdl_servicecenters');
	
	}
	function index()
	{
		$this->redir->set_last_index();		
		//$this->load->view('index');
		$this->load->model(array('utilities/mdl_html','servicecenters/mdl_servicecenters'));
		
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		array_unshift($servicecenterOptions, $this->mdl_html->option( '', 'Select Store'));
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_select",array('class'=>'validate[required] text-input'));
		
		$data = array(
					'servicecenter_select'=>$servicecenter_select
					);
		$this->load->view('closedcall/index',$data);

	}
	function getclosedcallreportslist()
	{
	    $this->redir->set_last_index();
		$this->load->model(array('utilities/mdl_html','servicecenters/mdl_servicecenters'));
	
		$ajaxaction=$this->input->post('ajaxaction');
		
		$totalclosed = $this->mdl_closedcallreports->getTotalClosedCallsByDate();
		$closedcallreports['total_closed_calls'] = $totalclosed;
		
		$averageclosing = $this->mdl_closedcallreports->getAverageClosingTimeByDate();
		$closedcallreports['average_closing'] = $averageclosing;
		
		$longclosure = $this->mdl_closedcallreports->getLongClosureByDate();
		$closedcallreports['long_closure'] = $longclosure;
		
		$closedcalls = $this->mdl_closedcallreports->getClosedCallsByTimeLess();
		$closedcallreports['closed_calls'] = $closedcalls;
		
		$closedcallsbetween1 = $this->mdl_closedcallreports->getClosedCallsByTimeBetween1();
		$closedcallreports['closed_calls_between1'] = $closedcallsbetween1;
		
		$closedcallsbetween2 = $this->mdl_closedcallreports->getClosedCallsByTimeBetween2();
		$closedcallreports['closed_calls_between2'] = $closedcallsbetween2;
		
		$closedcallsgreater = $this->mdl_closedcallreports->getClosedCallsByTimeGreater();
		$closedcallreports['closed_calls_greater'] = $closedcallsgreater;			
				
		$data=array(
			'closedcallreports'=>$closedcallreports,
			"ajaxaction"=>$this->input->post('ajaxaction')
		);
		$this->load->view('closedcall/process', $data);
	}
	function generateexlreport()
	{
		$data = '<table width="50%">'.$this->input->get('dt').'</table>';
		$this->load->helper('download');
		$name = 'daily_service_report_'.date("Y_m_d_H_i_s").'.xls';
		/*
		**keep track of downloads
		*/
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'closed_callreports',
						  'user_agent'	=> $this->agent->agent_string(),
						  'user_ip'=>$this->input->ip_address(),
						  'download_by'=>$this->session->userdata('user_id'),
						  'download_ts'=>date('Y-m-d H:i:s')
						  );
		$this->mdl_downloads->save($download_data);
		/*
		**ends here
		*/
		force_download($name, $data);
	}
}
?>