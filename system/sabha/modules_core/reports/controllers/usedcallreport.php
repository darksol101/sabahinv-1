<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Usedcallreport extends Admin_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->language("usedcallreport", $this->mdl_mcb_data->setting('default_language'));
		
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
		
		$servicecenters_search=$this->mdl_html->genericlist( $scentersOptions, "sc_id_search",array('onchange'=>'getengineer($(this).val()); ','class'=>'text-input'),'value','text',$sc_id);
		
		
		$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($this->session->userdata('sc_id'));
		array_unshift($engineerOption,$this->mdl_html->option('','All Engineers'));
		$engineerOptions = $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>'text-input'),'value','text','');
		
		$data=array(
					'engineerOption'=>$engineerOptions,
					'servicecenters_search'=>$servicecenters_search);
		
		$this->load->view('reports/usedcallreport/index',$data);
		}
		
	function getengineer(){
		$sc_id = $this->input->post('sc_id');
		$this->load->model(array('engineers/mdl_engineers','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$engineerOption = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
		array_unshift($engineerOption, $this->mdl_html->option( '', 'Select Engineer'));
		$engineer_select  =  $this->mdl_html->genericlist($engineerOption,'engineer_select',array('class'=>'select-one'),'value','text','');
		echo $engineer_select;
		die();
		}
		
		function generateReport()
		{
			$this->redir->set_last_index();
			$fromdate=$this->input->post('fromdate');
			$todate=$this->input->post('todate');
			$this->load->model(array('parts/mdl_parts_used','callcenter/mdl_callcenter','servicecenters/mdl_servicecenters','engineers/mdl_engineers','parts/mdl_parts'));
			
			$results = $this->mdl_parts_used->generateUsedPartReport();
			$data = array('results'=>$results,'fromdate'=>$fromdate,'todate'=>$todate);
			$this->load->view('reports/usedcallreport/usedcallreportlist',$data);
			
		}
		

function excel_ready(){
		
		$sc_id = $this->input->post('sc_id');
		$engineer_id = $this->input->post('engineer_id');
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$this->session->set_userdata('sc_id_rl',$sc_id);
		$this->session->set_userdata('fromdate_rl',$fromdate);
		$this->session->set_userdata('todate_rl',$todate);
		$this->session->set_userdata('engineer_id_rl',$engineer_id);
		}

function create_excel()
	{
		ini_set("memory_limit","256M");
		$this->load->model(array('mcb_data/mdl_mcb_data', 'utilities/mdl_html','parts/mdl_parts_used','callcenter/mdl_callcenter','servicecenters/mdl_servicecenters','engineers/mdl_engineers','parts/mdl_parts','productmodel/mdl_productmodel','products/mdl_products'));
		$this->load->plugin('to_excel');
		$this->load->helper('calls');
		$list = $this->mdl_parts_used->getUsedPartsDownload();
		//print_r($list); die();
		$data = array();
		$i = 1;
		foreach($list as $row){
			$row->parts_used_id = $i;
			$data[]=$row;
			$i++;
		}
		
		$fields = array('S.No','Call Id','Product','Model','Store','Engineer Name','Item Number','Item description','Part Quantity','Used Date');
		$this->load->helper('download');
		$data = convert_to_table($data,$fields);
		$name = 'Used_call_parts_report'.date("Y_m_d_H_i_s").'.xls';
		/*
		 **keep track of downloads
		 */
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'calls',
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