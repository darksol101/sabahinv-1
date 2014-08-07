<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Orderreport extends Admin_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->language("orderreport", $this->mdl_mcb_data->setting('default_language'));
		
		}
		
		
	function index() {
		$this->redir->set_last_index();
		$this->load->model(array('engineers/mdl_engineers','mcb_data/mdl_mcb_data', 'utilities/mdl_html','servicecenters/mdl_servicecenters'));
		$sc_id = $this->session->userdata('sc_id');
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Stores'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($sc_id);
		}
		
		$servicecenters_from=$this->mdl_html->genericlist( $scentersOptions, "sc_id_from",array('class'=>'text-input'),'value','text',$this->mdl_mcb_data->get('main_service_center'));
		$servicecenters_to=$this->mdl_html->genericlist( $scentersOptions, "sc_id_to",array('class'=>'text-input'),'value','text',$sc_id);
		
		
		//$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($this->session->userdata('sc_id'));
		//array_unshift($engineerOption,$this->mdl_html->option('','All Engineers'));
		//$engineerOptions = $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>'text-input'),'value','text','');
		
		$data=array(
					'servicecenters_to'=>$servicecenters_to,
					'servicecenters_from'=>$servicecenters_from
		);
		
		
		
		$this->load->view('reports/orderreport/index',$data);
		}
		
		
		
	function getorderreport(){
	$this->load->model(array('orders/mdl_orders','orders/mdl_order_parts','orders/mdl_order_parts_details','servicecenters/mdl_servicecenters','parts/mdl_parts'));
		
	$report =$this->mdl_orders->getOrderReport();
	
	$data = array(
			'results' =>$report
			);
	$this->load->view('reports/orderreport/orderreportlist', $data)	;
	}
		
}
?>