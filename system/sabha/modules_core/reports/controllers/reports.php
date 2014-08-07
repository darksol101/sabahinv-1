<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Reports extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("reports",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model('reports/mdl_reports');	
	
	}
	function index()
	{
		$this->redir->set_last_index();		
		$this->load->view('index');
	}
	function getreportslist()
	{
	    $this->redir->set_last_index();
		$ajaxaction=$this->input->post('ajaxaction');
		
		$total = $this->mdl_reports->getTotalCallsByDate();
		$reports['total_call_registered'] = $total;
		
		$totalpending = $this->mdl_reports->getTotalPendingCallsByDate();
		$reports['total_pending_calls'] = $totalpending;
		
		$totalopen = $this->mdl_reports->getTotalOpenCallsByDate();
		$reports['total_open_calls'] = $totalopen;
		
		$totalpartpending = $this->mdl_reports->getTotalPartPendingCallsByDate();
		$reports['total_part_pending'] = $totalpartpending;
		
		$totalcancelled = $this->mdl_reports->getTotalCancelledCallsByDate();
		$reports['total_cancelled'] = $totalcancelled;
		
		$totalclosed = $this->mdl_reports->getTotalClosedCallsByDate();
		$reports['total_closed_calls'] = $totalclosed;
		
		$averageagingcurrent = $this->mdl_reports->getAverageAgingTimeCurrentPendingByDate();
		$reports['average_aging_current'] = $averageagingcurrent;
		
		$averageagingpart = $this->mdl_reports->getAverageAgingTimePartPendingByDate();
		$reports['average_aging_part'] = $averageagingpart;
		
		$averageclosing = $this->mdl_reports->getAverageClosingTimeByDate();
		$reports['average_closing'] = $averageclosing;
		
		$longclosure = $this->mdl_reports->getLongClosureByDate();
		$reports['long_closure'] = $longclosure;
		
		$closedcalls = $this->mdl_reports->getClosedCallsByTimeLess();
		$reports['closed_calls'] = $closedcalls;
		
		$closedcallsbetween = $this->mdl_reports->getClosedCallsByTimeBetween();
		$reports['closed_calls_between'] = $closedcallsbetween;
		
		$closedcallsgreater = $this->mdl_reports->getClosedCallsByTimeGreater();
		$reports['closed_calls_greater'] = $closedcallsgreater;
		$data=array(
			'reports'=>$reports,
			"ajaxaction"=>$this->input->post('ajaxaction')
		);
		$this->load->view('process', $data);
	
	}
	
}
?>