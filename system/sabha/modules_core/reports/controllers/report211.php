<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Report211 extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("report211",  $this->mdl_mcb_data->setting('default_language'));	
	}
	
	
	function index (){
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','engineers/mdl_engineers','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$sc_id = $this->session->userdata('sc_id');
		if($this->session->userdata('usergroup_id')==1 || $this->session->userdata('usergroup_id')==3){
            $serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptions();
		}else { 
		$serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptionsBySc($sc_id);}
			
			
			
			
            array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'All Store'));
            $servicecenter =  $this->mdl_html->genericlist($serviceCentersOptions,'sc_id',array('class'=>'validate[required]','onchange'=>'getEngineerBySvc($(this).val());'),'value','text',$this->session->userdata('sc_id'));
            
            $engineerOptions = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
            array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
            $engineer =  $this->mdl_html->genericlist($engineerOptions,'engineer_id',array('class'=>'validate[required]'),'value','text','');
            
            $data = array(
                        'servicecenter'=>$servicecenter,
                        'engineer'=>$engineer
                        );
		$this->load->view('reports/report211/index',$data);
		
		
		}
		
			function getengineersbysc(){
            $this->redir->set_last_index();
            $sc_id = $this->input->post('sc_id');
            $this->load->model(array('engineers/mdl_engineers','utilities/mdl_html'));
            $engineerOptions = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
            array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
            $engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer_id',array('class'=>'validate[required]'),'value','text','');
            echo $engineer_select;
	}
	
	function exceldownload(){
		$this->session->set_userdata('sc_id_dwnses',$this->input->post('sc_id'));
		$this->session->set_userdata('engineer_id_dwnses',$this->input->post('engineer_id'));
		$this->session->set_userdata('fromdate_dwnses',$this->input->post('fromdate'));
		$this->session->set_userdata('todate_dwnses',$this->input->post('todate'));
		
		}
		
		
		function create_excel()
	{
		ini_set("memory_limit","256M");
		$this->load->model(array('callcenter/mdl_callcenter','engineers/mdl_engineers','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html','products/mdl_products','productmodel/mdl_productmodel','customers/mdl_customers'));
		$this->load->plugin('to_excel');
		$this->load->helper('calls');
		$list = $this->mdl_callcenter->downloadreport211();
		
		$data = '';
		if($list->num_rows()==0){
			redirect('reports/report211');
		}
		$i=1;
		foreach($list->result() as $row){
			
			$row->call_id =$i;
			$data[]=$row;
			$i = $i+1;
		}
		
		$fields = array('S.No','Call Id','Customer Name','Product','Model Number','Store','Engineer','Registered Date','Closed Date');
		$this->load->helper('download');
		$data = convert_to_table($data,$fields);
		$name = '211Report'.date("Y_m_d_H_i_s").'.xls';
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