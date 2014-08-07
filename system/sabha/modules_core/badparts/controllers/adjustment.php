<?php defined('BASEPATH') or die('Direct access script is not allowed');
/**
 * @property mdl_adjustment mdl_adjustment
 */
class Adjustment extends Admin_Controller{
	public function  __construct(){
		parent::__construct();
		$this->load->language("badparts",  $this->mdl_mcb_data->setting('default_language'));
	}
	public function index(){
            $this->load->model(array('badparts/mdl_returnservice_center','badparts/mdl_returnservice_center_details','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
            $serviceCentersOptions_from = $this->mdl_servicecenters->getServiceCentersOptions();
            array_unshift($serviceCentersOptions_from, $this->mdl_html->option( '', 'Select Store'));
            $servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions_from,'sc_id',array('class'=>'validate[required]'),'value','text',  $this->session->userdata('user_id'));
            
            $partsOptions = $this->mdl_returnservice_center_details->getavailableparts();
            array_unshift($partsOptions, $this->mdl_html->option( '', 'Select Item'));
            $part_select  =  $this->mdl_html->genericlist($partsOptions,'part_number',array('class'=>'validate[required]'),'value','text','');
            
            $servicecenter_select_search  =  $this->mdl_html->genericlist($serviceCentersOptions_from,'search_sc_id',array('class'=>'validate[required]'),'value','text', 0);
            $data = array(
                'servicecenter_select'=>$servicecenter_select,
                'part_select'=>$part_select,
                'servicecenter_select_search'=>$servicecenter_select_search
            );
            $this->load->view('badparts/adjustment/index',$data);
	}
	public function saveadjustment(){
            $this->load->model(array('badparts/mdl_adjustment'));
            $sc_id = $this->input->post('sc_id');
            $part_number = $this->input->post('part_number');
            $part_quantity = $this->input->post('part_quantity');
			$action = $this->input->post('action');
            $data = array(
                'sc_id'=>$sc_id,
                'part_number'=>$part_number,
                'part_quantity'=>$part_quantity,
                'adjustment_created_by'=>  $this->session->userdata('user_id'),
				'action'=> $action
            );
			 if($this->mdl_adjustment->save($data)){
                    $error = array('type'=>'success','message'=>$this->lang->line('adjustment_saved'));
                }  else {
                    $error = array('type'=>'warning','message'=>'Saved');
                }
            $this->load->view('dashboard/ajax_messages',$error);
	}
        function getadjustments(){
            $this->load->model(array('badparts/mdl_adjustment','servicecenters/mdl_servicecenters','engineers/mdl_engineers'));
            $this->load->library('ajaxpagination');
            $config['base_url'] = base_url();
            $config['per_page'] = $this->mdl_mcb_data->get('per_page');
            $config['next_link'] = '&raquo;';
            $config['prev_link'] = '&laquo;';
            $page['limit'] = $config['per_page'];
            $page['start'] = $this->input->post('currentpage');

            $list = $this->mdl_adjustment->getadjustments($page);
            $adjustments = $list['list'];
            $config['total_rows'] = $list['total'];
            $this->ajaxpagination->cur_page=$this->input->post('currentpage');
            $this->ajaxpagination->initialize($config);
            $navigation = $this->ajaxpagination->create_links();
            $data = array(
                'adjustments'=>$adjustments,
                'navigation'=>$navigation
            );
            $this->load->view('badparts/adjustment/list',$data);
        }
        function edit(){
            $this->load->model(array('badparts/mdl_adjustment','servicecenters/mdl_servicecenters'));
            $adjustment_id = $this->uri->segment(4);
            $adjustment = $this->mdl_adjustment->getpartadjustmentdetails($adjustment_id);
                
            $this->load->library('form_validation');
            $this->form_validation->set_rules('adjustment_id','Adjustment','required');
            if($this->form_validation->run() == FALSE){
                $data = array(
                    'adjustment'=>$adjustment
                );

                $this->load->view('badparts/adjustment/edit',$data);   
            }else{
                $adjustment_id = $this->input->post('adjustment_id');
                $approved = $this->input->post('approved');
                $scraped = $this->input->post('scraped');
                $data = array(
                    'adjustment_last_mod_by' =>  $this->session->userdata('user_id')
                );
                if($approved == 'on'){
                    $data['approved'] = 1;
                }else{
                    $data['approved'] = 0;
                }
                if($scraped == 'on'){
                    $data['scraped'] = 1;
                }else{
                    $data['scraped'] = 0;
                }
                if($this->mdl_adjustment->save($data,$adjustment_id)){
                }
                redirect('badparts/adjustment/edit/'.$adjustment_id);
            }
        }
		
		
	function finaladjustment(){
		$this->redir->set_last_index();
		$this->load->model(array('badparts/mdl_returnparts','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		
	if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'text-input validate[required]'),'value','text',$this->session->userdata('sc_id'));
		
		
		$servicecenter_select_search=$this->mdl_html->genericlist( $scentersOptions, "sc_id_search",array('class'=>'text-input validate[required]'),'value','text',$this->session->userdata('sc_id'));
		
		$bad_part_number = $this->mdl_returnparts->getReturnPartsOptionsBySc($this->session->userdata('sc_id')); 
		array_unshift($bad_part_number, $this->mdl_html->option( '', 'Select Item Number'));
		$bad_part_number = $this->mdl_html->genericlist( $bad_part_number, "bad_part_number",array('class'=>'text-input validate[required]'),'value','text','');
		
		$badpartAction=$this->mdl_mcb_data->getStatusOptions('badpart_action');
		array_unshift($badpartAction, $this->mdl_html->option( '', 'Action'));
		$badparts_action = $this->mdl_html->genericlist($badpartAction,'action',array('class'=>'text-input validate[required]'),'value','text','');
		
		
		$data = array(
					  'servicecenters'=>$servicecenters,
					  'bad_part_number'=>$bad_part_number,
					  'servicecenter_select_search'=>$servicecenter_select_search,
					  'badparts_action'=>$badparts_action
					  );
		
		
		$this->load->view('badparts/adjustments/index',$data);
		
		
		}	
		
		
}
?>