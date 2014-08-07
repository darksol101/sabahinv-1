<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class screports extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("screports",  $this->mdl_mcb_data->setting('default_language'));
		//$this->load->model(array('cities/mdl_cities','zones/mdl_zones','zones/mdl_districts','servicecenters/mdl_servicecenters'));
		$this->load->model('screports/mdl_screports');
				$this->load->helper('date');	
	
	}
	function index()
	{
		$this->redir->set_last_index();
		
		$this->load->view('index');
	}
	
		
	function getscreportslist()
	{
	    $this->redir->set_last_index();
		$ajaxaction=$this->input->post('ajaxaction');
		
		$scname = $this->mdl_screports->getservicecenter();
		
		$count_sc=count($scname);
		$i=0;
		foreach ($scname as $service_center)
		{			
			$sc_name[$i] = $service_center->diff;
	
			$total = $this->mdl_screports->getTotalCallsByDate($service_center->sc_id);		
		    $sc_total[$i] = $total->cnt;
	
			$scopen = $this->mdl_screports->getTotalOpenCallsByDate($service_center->sc_id);
			$sc_open[$i] = $scopen->cnt;
			
			$scpending = $this->mdl_screports->getTotalPendingCallsByDate($service_center->sc_id);
			$sc_pending[$i] = $scpending->cnt;
	
			$partpending = $this->mdl_screports->getTotalPartPendingCallsByDate($service_center->sc_id);
			$sc_partpending[$i] = $partpending->cnt;
	
			$closed = $this->mdl_screports->getTotalClosedCallsByDate($service_center->sc_id);
			$sc_closed[$i] = $closed->cnt;
		
			$averageagingcurrent = $this->mdl_screports->getAverageAgingTimeCurrentPendingByDate($service_center->sc_id);
			$sc_currentpending[$i] = $averageagingcurrent;
			
				
			$averageagingpart = $this->mdl_screports->getAverageAgingTimePartPendingByDate($service_center->sc_id);
			$sc_avgpartpending[$i] = $averageagingpart;
			
			$averageclosing = $this->mdl_screports->getAverageClosingTimeByDate($service_center->sc_id);
			$sc_avgclosing[$i] = $averageclosing;
			
			$longclosure = $this->mdl_screports->getLongClosureByDate($service_center->sc_id);
			$sc_longclosure[$i] = $longclosure;
			
			$closedless = $this->mdl_screports->getClosedCallsByTimeLess($service_center->sc_id);
			$sc_less[$i] = $closedless->cnt;
			$closedcallsbetween = $this->mdl_screports->getClosedCallsByTimeBetween($service_center->sc_id);
			$sc_between[$i] = $closedcallsbetween->cnt;
			
			$closedcallsgreater = $this->mdl_screports->getClosedCallsByTimeGreater($service_center->sc_id);
			$sc_greater[$i] = $closedcallsgreater->cnt;
								
			$i++;
		}
		
			$data=array(
			'sc_name'=>$sc_name,
			'sc_total'=>$sc_total,
			'sc_open'=>$sc_open,
			'sc_pending'=>$sc_pending,
			'sc_partpending'=>$sc_partpending,
			'sc_closed'=>$sc_closed,
			'sc_currentpending'=>$sc_currentpending,
			'sc_avgpartpending'=>$sc_avgpartpending,
			'sc_avgclosing'=>$sc_avgclosing,
			'sc_longclosure'=>$sc_longclosure,
			'sc_less'=>$sc_less,
			'sc_between'=>$sc_between,
			'sc_greater'=>$sc_greater,
			"ajaxaction"=>$this->input->post('ajaxaction')
		);
		$this->load->view('process', $data);
	
	}
	
}
?>