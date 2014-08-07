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
		$this->load->model(array('badparts/mdl_badparts','badparts/mdl_returnparts','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
		$this->form_validation->set_rules('from_sc_id','From Store','required');
		$this->form_validation->set_rules('to_sc_id','To Store','required');
		if($this->form_validation->run()==FALSE){
	        $serviceCentersOptions_from = $this->mdl_servicecenters->getServiceCentersOptions();
	        array_unshift($serviceCentersOptions_from, $this->mdl_html->option( '', 'Select Store'));
	        $servicecenter_select_from  =  $this->mdl_html->genericlist($serviceCentersOptions_from,'from_sc_id',array('onchange'=>'getPartBySc()'),'value','text',$this->session->userdata('sc_id'));
	
	        $sc_id = $this->session->userdata('sc_id');
	        $serviceCentersOptions = $this->mdl_servicecenters->getServiceCentersOptionsExclude($sc_id);
	            
	        $main_factory = $this->mdl_mcb_data->get('main_factory');
	        array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
	        $servicecenter_select_to  =  $this->mdl_html->genericlist($serviceCentersOptions,'to_sc_id',array('class'=>'validate[required]'),'value','text',$main_factory);
	
	        $partsOptions = $this->mdl_returnparts->getreturnparts();
	        array_unshift($partsOptions, $this->mdl_html->option( '', 'Select Item'));
	        $part_select  =  $this->mdl_html->genericlist($partsOptions,'part_number',array('class'=>'validate[required]'),'value','text','');
	        $data = array(
	        	'servicecenter_select_from'=>$servicecenter_select_from,
	            'servicecenter_select_to'=>$servicecenter_select_to,
	            'part_select'=>$part_select
	            );		
	        $this->load->view('badparts/parts_transfer/index',$data);
		}else{
			$this->load->model(array('mdl_returnservice_center','mdl_returnservice_center_details'));	
			$part_numbers = $this->input->post('part_numbers');
			$part_quantities = $this->input->post('part_quantities');
			$from_sc_id = $this->input->post('from_sc_id');
            $to_sc_id = $this->input->post('to_sc_id');
            $part_number = $this->input->post('part_number');
            $part_quantity = $this->input->post('part_quantity');
            $return_sc_id = $this->input->post('return_sc_id');
			
            $data = array(
                'from_sc_id'=>$from_sc_id,
                'to_sc_id'=>$to_sc_id,
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
						if($this->mdl_returnservice_center_details->save($data)){
							$this->session->set_flashdata('save_success','Saved');
						}		
						$i++;		
					}
				}	
            }else{
            	
            }
			redirect('badparts/transfer/servicecenter');
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
            $this->load->model(array('mdl_returnparts'));
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
            if($this->mdl_returnparts->save($data)){
                $error = array(
                    'type'=>'success',
                    'message'=>'Part return successfully'
                );
            }else{
                $error = array(
                    'type'=>'warning',
                    'message'=>'Not enough quantity to return'
                );
            }
            $this->load->view('dashboard/ajax_messages',$error);
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
        function edit(){
            $this->load->model(array('badparts/mdl_returnservice_center','servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data','utilities/mdl_html'));
            $this->load->library('form_validation');
            $this->form_validation->set_rules('return_sc_id', 'Return', 'required');
            if($this->form_validation->run()==FALSE){
                $return_sc_id = $this->uri->segment(4);    
                $order_parts = $this->mdl_returnservice_center->getPartsOrderDetails($return_sc_id);
                $serviceCentersOptions_from = $this->mdl_servicecenters->getServiceCentersOptions();
                array_unshift($serviceCentersOptions_from, $this->mdl_html->option( '', 'Select Store'));
                $servicecenter_select_from  =  $this->mdl_html->genericlist($serviceCentersOptions_from,'from_sc_id',array('class'=>'validate[required]'),'value','text',$order_parts->from_sc_id);

                $serviceCentersOptions = $this->mdl_servicecenters->getServiceCentersOptionsExclude($order_parts->from_sc_id);            
                array_unshift($serviceCentersOptions, $this->mdl_html->option( '', 'Select Store'));
                $servicecenter_select_to  =  $this->mdl_html->genericlist($serviceCentersOptions,'to_sc_id',array('class'=>'validate[required]'),'value','text',$order_parts->to_sc_id);

                $statusOptions = $this->mdl_mcb_data->getStatusOptions('return_order_status');
                //array_unshift($statusOptions, $this->mdl_html->option( '', 'Select Status'));
                if(($this->session->userdata('sc_id') == $order_parts->from_sc_id )){
                    if($order_parts->return_sc_status<=2){
                        unset($statusOptions[3]);
                        $status_select = $this->mdl_html->genericlist($statusOptions,'order_parts_status',array('class'=>'text-input'),'value','text',$order_parts->return_sc_status);
                    }else{
                        $status_select = $this->mdl_mcb_data->getStatusDetails($order_parts->return_sc_status,'return_order_status');
                    }
                    //unset($statusOptions[3]);
                    //$status_select = $this->mdl_mcb_data->getStatusDetails($order_parts->return_sc_status,'return_order_status');
                }
                if($this->session->userdata('sc_id') == $order_parts->to_sc_id){
                    if($order_parts->return_sc_status<2){
                        $status_select = $this->mdl_mcb_data->getStatusDetails($order_parts->return_sc_status,'return_order_status');
                    }else{
                      unset($statusOptions[0]);
                      unset($statusOptions[1]);
                      $status_select = $this->mdl_html->genericlist($statusOptions,'order_parts_status',array('class'=>'text-input'),'value','text',$order_parts->return_sc_status);      
                    }
                }
                $data = array(
                    'order_parts'=>$order_parts,
                    'servicecenter_select_from'=>$servicecenter_select_from,
                    'servicecenter_select_to'=>$servicecenter_select_to,
                    'status_select'=>$status_select
                );
                $this->load->view('transfer/edit',$data);
            }else{
                $this->load->model(array('badparts/mdl_returnparts','badparts/mdl_returnservice_center'));
                $return_sc_id = $this->input->post('return_sc_id');
                if($return_sc_id>0){			
                    $return_sc_status = $this->input->post('order_parts_status');
                    $from_sc_id = $this->input->post('from_sc_id');
                    $to_sc_id = $this->input->post('to_sc_id');
                           
                    $data = array(
                        'return_sc_last_mod_by'=>  $this->session->userdata('user_id'),
                        'return_sc_last_mod_ts'=>  date('Y-m-d H:i:s')
                    );
                    if($return_sc_status!=''){
                            $data['return_sc_status'] = $return_sc_status;
                    }
                    if($from_sc_id){
                            $data['from_sc_id'] = $from_sc_id;
                    }
                    if($to_sc_id){
                            $data['to_sc_id'] = $to_sc_id;
                    }
                    if($this->mdl_returnservice_center->save($data,$return_sc_id)){
                        $this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
                        redirect('badparts/transfer/edit/'.$return_sc_id);
                    }
                }
            }
        }
        function getjsonparts(){
            $this->redir->set_last_index();
            $this->load->model(array('badparts/mdl_returnparts','utilities/mdl_html'));
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