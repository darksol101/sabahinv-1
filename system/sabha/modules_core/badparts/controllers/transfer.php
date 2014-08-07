<?php defined('BASEPATH') or die('Direct access script is not allowed');
/*
 * @parameters mdl_returnparts 
 * @parameters mdl_servicecenters
 * @parameters mdl_mcb_data
 * @parameters mdl_returnservice_center
*/
class Transfer extends Admin_Controller{
	public function  __construct(){
		parent::__construct();
		$this->load->language("badparts",  $this->mdl_mcb_data->setting('default_language'));
		$this->return_sc_id=0;
	}
	function index(){
			$this->load->model(array('servicecenters/mdl_servicecenters','utilities/mdl_html'));
			$serviceCentersOptions = $this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
			$servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions,'sc_id',array(),'value','text',0);
            $data = array(
						  'servicecenter_select'=>$servicecenter_select
						  );
            $this->load->view('badparts/transfer/index',$data);
	}
    function partorderlist(){
	    $this->load->model(array('badparts/mdl_returnservice_center','servicecenters/mdl_servicecenters'));
	    $parts_order_list = $this->mdl_returnservice_center->getPartsOrderList();
	            
	    $data = array(
	    	'parts_order_list'=>$parts_order_list
	    );
	    $this->load->view('badparts/transfer/list',$data);
    }
	function servicecenter(){	
		$this->load->model(array('badparts/mdl_badparts','mdl_returnservice_center','mdl_returnservice_center_details','badparts/mdl_returnparts','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$this->form_validation->set_rules('from_sc_id','From Store','required');
		$this->form_validation->set_rules('to_sc_id','To Store','required');
		if($this->form_validation->run() == FALSE){
			$return_sc_details = $this->mdl_returnservice_center->getDetails($this->return_sc_id);
			$return_sc_details_list = $this->mdl_returnservice_center_details->getReturnedParts($this->return_sc_id);
	        
			$serviceCentersOptions_from = $this->mdl_servicecenters->getServiceCentersOptions();
	        array_unshift($serviceCentersOptions_from, $this->mdl_html->option( '', 'Select Store'));
	        $servicecenter_select_from  =  $this->mdl_html->genericlist($serviceCentersOptions_from,'from_sc_id',array('onchange'=>'getPartBySc()'),'value','text',$return_sc_details->from_sc_id);
	
	        $sc_id = $this->session->userdata('sc_id');
	        $serviceCentersOptions = $this->mdl_servicecenters->getServiceCentersOptionsExclude($sc_id);
	            
	        //$main_factory = $this->mdl_mcb_data->get('main_factory');
	        array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
	        $servicecenter_select_to  =  $this->mdl_html->genericlist($serviceCentersOptions,'to_sc_id',array('class'=>'validate[required]'),'value','text',$return_sc_details->to_sc_id);
	
	        $partsOptions = $this->mdl_returnparts->getreturnparts();
	        array_unshift($partsOptions, $this->mdl_html->option( '', 'Select Item'));
	        $part_select  =  $this->mdl_html->genericlist($partsOptions,'part_number',array('class'=>'validate[required]'),'value','text','');
			$statusOptions = $this->mdl_mcb_data->getStatusOptions('return_order_status');
			$status_select = $this->mdl_html->genericlist($statusOptions,'order_parts_status',array('class'=>'text-input'),'value','text',$return_sc_details->return_sc_status);
	        $data = array(
	        	'servicecenter_select_from'	=>$servicecenter_select_from,
	            'servicecenter_select_to'	=>$servicecenter_select_to,
	            'part_select'				=>$part_select,
	        	'return_sc_details' 		=>$return_sc_details,
				'return_sc_details_list'	=>$return_sc_details_list,
				'status_select' 			=>$status_select
	            );		
	        $this->load->view('badparts/parts_transfer/index',$data);
		}else{
			$this->load->model(array('mdl_returnservice_center','mdl_returnservice_center_details'));	
			$part_numbers = $this->input->post('part_numbers');
			$part_quantities = $this->input->post('part_quantities');			
			$return_sc_detail_id = $this->input->post('return_sc_detail_id');
			
			$from_sc_id = $this->input->post('from_sc_id');
            $to_sc_id = $this->input->post('to_sc_id');
            $part_number = $this->input->post('part_number');
            $part_quantity = $this->input->post('part_quantity');
            $return_sc_id = $this->input->post('return_sc_id');
			$return_sc_status = $this->input->post('order_parts_status');
			
            $data = array(
                'from_sc_id'=>$from_sc_id,
                'to_sc_id'=>$to_sc_id,
				'return_sc_status'=>$return_sc_status,
                'return_sc_created_by'=>$this->session->userdata('user_id'),
                'return_sc_created_ts'=>date('Y-m-d H:i:s')
            );
            if($this->mdl_returnservice_center->save($data,$return_sc_id)){
            	$insert_id = $this->db->insert_id();
            	if((int)$insert_id>0){ $return_sc_id = $insert_id;}
	            if(is_array($part_numbers)){
					$i=0;
					foreach($part_numbers as $v){
						$data = array(
									'part_number'=>$v,
									'return_sc_id'=>$return_sc_id,
									'part_quantity'=>$part_quantities[$i]
								);
						if($this->mdl_returnservice_center_details->save($data,$return_sc_detail_id[$i])){
							$this->session->set_flashdata('save_success','Saved');
						}		
						$i++;		
					}
				}	
            }else{
            	
            }
			redirect('badparts/transfer/edit/'.$return_sc_id);
		}
	}
	public function engineer(){
            $this->load->model(array('badparts/mdl_badparts','engineers/mdl_engineers','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
            $sc_id = $this->session->userdata('sc_id');
            $badpartsOptions = array();
            array_unshift($badpartsOptions, $this->mdl_html->option( '', 'Select Item'));
            $badparts_select  =  $this->mdl_html->genericlist($badpartsOptions,'part_number',array('class'=>'validate[required]'),'value','text','');

            $serviceCentersOptions= $this->mdl_servicecenters->getServiceCentersOptions();
            array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
            $servicecenter_select  =  $this->mdl_html->genericlist($serviceCentersOptions,'sc_id',array('class'=>'validate[required]','onchange'=>'getEngineerBySvc($(this).val());'),'value','text',$this->session->userdata('sc_id'));
            
            $engineerOptions = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
            array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
            $engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer_id',array('class'=>'validate[required]','onchange'=>'getPartsByEngineer($(this).val());'),'value','text','');
            
            $data = array(
                        'servicecenter_select'=>$servicecenter_select,
                        'engineer_select'=>$engineer_select,
                        'badparts_select'=>$badparts_select
                        );
            $this->load->view('badparts/engineer_transfer/index',$data);
	}
	function getengineersbysc(){
            $this->redir->set_last_index();
            $sc_id = $this->input->post('sc_id');
            $this->load->model(array('engineers/mdl_engineers','utilities/mdl_html'));
            $engineerOptions = $this->mdl_engineers->getEngineerOptionsBySc($sc_id);
            array_unshift($engineerOptions, $this->mdl_html->option( '', 'Select Engineer'));
            $engineer_select  =  $this->mdl_html->genericlist($engineerOptions,'engineer_id',array('class'=>'validate[required]','onchange'=>'getPartsByEngineer($(this).val());'),'value','text','');
            echo $engineer_select;
	}
	function getpartsbyengineer(){
            $this->redir->set_last_index();
            $this->load->model(array('badparts/mdl_badparts','badparts/mdl_returnparts','utilities/mdl_html'));
            $engineer_id = $this->input->post('engineer_id');
            $partsOptions = $this->mdl_badparts->getBadPartsOptionsByEngineer($engineer_id);
            array_unshift($partsOptions, $this->mdl_html->option( '', 'Select Item'));
            $part_select  =  $this->mdl_html->genericlist($partsOptions,'part_number',array('class'=>'validate[required]'),'value','text','');
            echo $part_select;
	}
	function returnparttosvc(){
            $this->load->model(array('badparts/mdl_returnparts'));
            $sc_id = $this->input->post('sc_id');
            $engineer_id = $this->input->post('engineer_id');
            $part_number = $this->input->post('part_number');
            $part_quantity = $this->input->post('part_quantity');
            $data = array(
                        'part_number'=>$part_number,
                        'part_quantity'=>$part_quantity,
                        'sc_id'=>$sc_id,
                        'engineer_id'=>$engineer_id,
                        'return_part_created_by'=>$this->session->userdata('user_id'),
                        'return_part_created_ts'=>date('Y-m-d H:i:s')
                        );
			
			
            $this->mdl_returnparts->savereturnpart($data);
			
			$this->load->model('badparts/mdl_badparts_engineer_details');
			$bad_parts_data['part_number']=$part_number;
			$bad_parts_data['sc_id']= $sc_id;
			$bad_parts_data['engineer_id']=$engineer_id;
			$bad_parts_data['quantity_out'] = $part_quantity;
			$bad_parts_data['badparts_engineer_details_created_by'] = $this->session->userdata('user_id');	
			$bad_parts_data['event']='Returned';
			
			$this->mdl_badparts_engineer_details->save($bad_parts_data);
               
	}
        function saveparttosc(){
            $this->load->model(array('badparts/mdl_returnservice_center','badparts/mdl_returnparts'));
            $from_sc_id = $this->input->post('from_sc_id');
            $to_sc_id = $this->input->post('to_sc_id');
            $part_number = $this->input->post('part_number');
            $part_quantity = $this->input->post('part_quantity');
            $data = array(
                'from_sc_id'=>$from_sc_id,
                'to_sc_id'=>$to_sc_id,
                'part_number'=>$part_number,
                'part_quantity'=>$part_quantity,
                'return_sc_created_by'=>$this->session->userdata('user_id'),
                'return_sc_created_ts'=>date('Y-m-d H:i:s')
            );
			$check_parts = $this->mdl_returnservice_center->checkPartsInSc($from_sc_id,$part_number);
            if($check_parts==true){
				if($this->mdl_returnservice_center->save($data)){
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}else{
					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
				}
			}else{
				$error = array('type'=>'warning','message'=>$this->lang->line('not_enougth_quantity'));
			}
            $this->load->view('dashboard/ajax_messages',$error);
        }
        function getpartsbysc(){
            $this->redir->set_last_index();
            $this->load->model(array('badparts/mdl_returnparts','utilities/mdl_html'));
            $sc_id = $this->input->post('sc_id');
            $partsOptions = $this->mdl_returnparts->getReturnPartsOptionsBySc($sc_id);
            array_unshift($partsOptions, $this->mdl_html->option( '', 'Select Item'));
            $part_select  =  $this->mdl_html->genericlist($partsOptions,'part_number',array('class'=>'validate[required]'),'value','text','');
            echo $part_select;
        }
        function edit($return_sc_id){
        	$this->return_sc_id = $return_sc_id;
        	$this->servicecenter();
        }
        function getjsonparts(){
            $this->redir->set_last_index();
            $this->load->model(array('badparts/mdl_returnparts','utilities/mdl_html','parts/mdl_parts'));
            $sc_id = $this->input->get('sc_id');
            $badparts = $this->mdl_returnparts->getReturnPartsBySc($sc_id);;
            $json = array();
            echo json_encode($badparts);
        }
        function checkparts(){
            $this->load->model(array('badparts/mdl_returnservice_center_details','badparts/mdl_returnservice_center','badparts/mdl_returnparts'));            
            $from_sc_id = $this->input->post('from_sc_id');
            $part_number = $this->input->post('part_number');
            $part_quantity = $this->input->post('part_quantity');
            $check_parts = $this->mdl_returnservice_center_details->checkPartsInSc($from_sc_id,$part_number);
            echo $check_parts;
        }
		
}
?>