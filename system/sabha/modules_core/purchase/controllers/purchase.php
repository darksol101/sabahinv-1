<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Purchase extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("purchase",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index() {
		$this->redir->set_last_index();
		$this->load->model(array('vendors/mdl_vendors','mcb_data/mdl_mcb_data', 'utilities/mdl_html','servicecenters/mdl_servicecenters'));
		$statusOptions = $this->mdl_mcb_data->getStatusOptions('purchase_status');
		array_unshift($statusOptions, $this->mdl_html->option( '', 'All Status'));
		$status_select = $this->mdl_html->genericlist($statusOptions,'purchase_status',array('class'=>'text-input'),'value','text','');
		
		$vendorOptions = $this->mdl_vendors->getVendorOptions();
		array_unshift($vendorOptions, $this->mdl_html->option( '', 'All Vendor'));
		$vendor_select = $this->mdl_html->genericlist($vendorOptions,'vendor_name',array('class'=>'validate[required] text-input'),'value','text','');
		
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		
		
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'text-input'),'value','text','');
		

		$data = array(
					  'status_select'=>$status_select,
					  'vendor_select'=>$vendor_select,
					  'servicecenters'=>$servicecenters
					  );
		$this->load->view('purchases/index',$data);
	}


	function addrecord() {

		$this->redir->set_last_index();
		$purchase_id = $this->uri->segment(3);
		$ses = array('purchase_id'=>$purchase_id);
		$this->session->set_userdata($ses);
		
		$error = array();
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');
		//$this->form_validation->set_rules('purchase_number', 'Purchase number', 'required');
		$this->form_validation->set_rules('vendor_select', 'vendor select', 'required');
		//$this->form_validation->set_rules('purchase_notes', 'Additional notes', 'required');
		
		
		
		$this->load->model(array('purchase/mdl_purchase','parts/mdl_parts','vendors/mdl_vendors','servicecenters/mdl_servicecenters','purchase/mdl_purchase_details','mcb_data/mdl_mcb_data', 'utilities/mdl_html','parts/mdl_parts','company/mdl_company'));
		
		$purchase = $this->mdl_purchase->getPurchase($purchase_id);
		$company_options = $this->mdl_company->getCompanyOptions();
		//array_unshift($company_options, $this->mdl_html->option( '', ''));
		$company_options = $this->mdl_html->genericlist($company_options,'select_company',array('class'=>' select-one'),'text','text','');
		
		
		
		
		$vendorOptions = $this->mdl_vendors->getVendorOptions();
		array_unshift($vendorOptions, $this->mdl_html->option( '', 'Select Vendor'));
		$vendor_select = $this->mdl_html->genericlist($vendorOptions,'vendor_select',array('class'=>'validate[required] text-input'),'value','text',$purchase->vendor_id);
		$purchasedetails = $this->mdl_purchase_details->getPurchaseDetails($purchase_id);
		
		$sc_id = $this->session->userdata('sc_id');
			//$sc_id = 1; 
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
	if ($purchase->sc_id == ''){
		$scenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'validate[required] text-input'),'value','text',$sc_id);
	}else {
		$scenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'validate[required] text-input'),'value','text',$purchase->sc_id);
		}
		if($this->form_validation->run() == FALSE){
			$data = array(
					  'vendor_select'=>$vendor_select,
					  'purchasedetails'=>$purchasedetails,
					  'purchase'=>$purchase,
					  'scenters'=>$scenters,
					  'company_options'=>$company_options
					   );
			$this->load->view('purchase/purchase/add',$data);
		}else{
			//save for purchase data
				$purchase_id = $this->input->post('purchase_id');
				
				$part_number = $this->input->post('pnum');
				if($part_number == ''){
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved_enter_part_number'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved_enter_part_number'));
					
					
					if ((int)$purchase_id== 0 ){
					redirect('purchase/addrecord');
					}else{ redirect('purchase/addrecord/'.$purchase_id);
							 }
				}
		
			$invoice_number= $this->input->post('invoice_number');
			$vendor_id = $this->input->post('vendor_select');
			$pp_number = $this->input->post('pp_number');
			$lc_number= $this->input->post('lc_number');
			$pp_date = $this->input->post('pp_date');
			$purchase_status = 0;
			$sc_id = $this->input->post('sc_id');
			if ($this->input->post('deliver_status'))
			{
				$purchase_status = 1;
			}
			$purchase_date = date("Y-m-d",date_to_timestamp($this->input->post('purchase_date')));
			$pp_date = date("Y-m-d",date_to_timestamp($this->input->post('pp_date')));
			$purchase_notes = $this->input->post('purchase_notes');
			$data['invoice_number']= $invoice_number;
			$data['purchase_id'] = $purchase_id;
			$data['vendor_id'] = $vendor_id;
			$data['purchase_date'] = $purchase_date;
			$data['purchase_notes'] = $purchase_notes;
			$data['purchase_status'] = $purchase_status;
			$data['sc_id'] = $sc_id;
			$data['lc_number']= $lc_number;
			$data['pp_number'] = $pp_number;
			$data['pp_date'] = $pp_date;
			
			//add
			if((int)$purchase_id==0){
				$data["purchase_created_ts"]=date("Y-m-d H:i:s");
				$data["purchase_created_by"]=$this->session->userdata('user_id');
				$maxid= $this->mdl_purchase->getmaxid();
				$data['purchase_id']= $maxid->maxid+1;
				
				if($this->mdl_purchase->save($data)){

					$purchase_id = $this->db->insert_id();
				
				
					$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved').'.'.'   '.'Purchase Number is'.'  '.$data['purchase_id']);
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved').'   '.'Purchase Number is'.$data['purchase_id']);
				}else{

					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved'));
				}
			}else{
				$data["purchase_last_mod_ts"]=date("Y-m-d");
				$data["purchase_last_mod_by"]=$this->session->userdata('user_id');
				if($this->mdl_purchase->save($data,$purchase_id)){
					$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}else{
					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved'));
				}
			}
			//save details


			$part_number = $this->input->post('pnum');
			$part_quantity = $this->input->post('pqty');
			$company_name = $this->input->post('comp');
			$purchase_details_arr = array();
			$stock_data = array();
			$purchase_details_arr = $this->input->post('purchase_details_id');
			$deliver_status = $this->input->post('deliver_status');
			
			$part_id=$this->mdl_parts->getPartsId($part_number);
	
			if($purchase_id){
				$i=0;
				if(is_array($purchase_details_arr)){
					foreach($purchase_details_arr  as $purchase_details_id){

						$stockdata['part_id'] = $part_id[$i] ;

						$company_id = $this->mdl_company->getcompanyid($company_name[$i]);
					    
					    $stockdata['company_id'] = $company_id;
						$stockdata['stock_quantity_in'] = $part_quantity[$i];
						
						if((int)$purchase_details_id==0){
							$datadetails['company_name']= $company_id;
							$datadetails['purchase_id'] = $purchase_id;
							$datadetails['part_id'] = $part_id[$i];
							$datadetails['part_quantity'] = $part_quantity[$i];
							//$datadetails['part_rate'] = $part_rate[$i];
							$datadetails["purchase_details_created_ts"]=date("Y-m-d H:i:s");
							$datadetails["purchase_details_created_by"]=$this->session->userdata('user_id');
							$this->mdl_purchase_details->save($datadetails);
						}
						else{
							$datadetails['company_name']= $company_id;
							$datadetails['part_id'] = $part_id[$i];
							$datadetails['part_quantity'] = $part_quantity[$i];
							$datadetails['purchase_id'] = $purchase_id;
							//$datadetails['part_rate'] = $part_rate[$i]; 
							$datadetails["purchase_details_last_mod_ts"]=date("Y-m-d");
							$datadetails["purchase_details_last_mod_by"]=$this->session->userdata('user_id');
							$this->mdl_purchase_details->save($datadetails,$purchase_details_id);
							
							if($deliver_status){
								$this->load->model('stocks/mdl_stocks');
								$stockdata['sc_id'] = $sc_id;
								$stockdata['stock_dt'] = date('Y-m-d');
								$stockdata['stock_tm'] = date('H:i:s');
								$stockdata['stock_created_by'] = $this->session->userdata('user_id');
								$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
								$this->mdl_stocks->stockinUpdate($stockdata, "purchase", $purchase_details_id);
							}
						}
						$i++;
					}
				}
				redirect('purchase/editrecord/'.$purchase_id);
			}else{
				redirect('purchase/addrecord/');
			}
		}
	}
	
	function editrecord(){
		$this->addrecord();
		
	}
	
	function deleteparts(){


		$this->redir->set_last_index();
		$this->load->model('purchase/mdl_purchase_details');
		
		$purchase_details_id = (int) $this->input->post('purchase_details_id');
		
		if($purchase_details_id!=0 && $this->mdl_purchase_details->delete(array('purchase_details_id'=>$purchase_details_id))){
			//hide save message on page refresh
			$this->session->set_flashdata('success_delete', false);
			$error = array('type'=>'delete','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}

		//$this->load->view('dashboard/ajax_messages',$error);
	//

	}


	function deletepurchase()
	{
		$this->redir->set_last_index();
		$this->load->model(array('purchase/mdl_purchase','purchase/mdl_purchase_details'));
		$purchase_id = $this->input->post('purchase_id');
		
		if($this->mdl_purchase->delete(array('purchase_id'=>$purchase_id,'purchase_status'=>'0'))){
			$error = array('type'=>'delete','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->mdl_purchase_details->deleteparts($purchase_id);
		$this->load->view('dashboard/ajax_messages',$error);
	}
	
	function showpurchaselist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('purchase/mdl_purchase','servicecenters/mdl_servicecenters','vendors/mdl_vendors','mcb_data/mdl_mcb_data'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$purchase_id = (int)$this->input->post('purchase_id');
		$list = $this->mdl_purchase->getPurchaselist($page);

		$purchases = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		$data=array("purchases"=>$purchases, "ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('purchases/purchaselist', $data);
	}

	
	function getjsonparts(){
		$this->load->model(array('parts/mdl_model_parts','parts/mdl_parts'));
		$parts = $this->mdl_parts->getPartslist('');
		$json = array();
		echo json_encode($parts);
	}
	
	function getjsonpartsmodel(){
		$this->load->model(array('parts/mdl_model_parts','parts/mdl_parts'));
		$parts = $this->mdl_parts->getPartslistmodel('');
		$json = array();
		echo json_encode($parts);
	}

	function checkpart(){
		
		$this->load->model(array('salesmaker/mdl_salesmaker','salesmaker/mdl_salesmakerlist'));
		
		$part_quantity= $this->input->post('part_quantity');
		$part_number= $this->input->post('part_number');
		$company = $this->input->post('company');
		$page = $this->input->post('page');	
		if ($part_number == '' || $part_quantity =='' || $company == '' ){ die();}
		
		
		$this->load->model(array('parts/mdl_parts'));

			$partlist= $this->mdl_parts->getpart();

		/*//dump($this->db->last_query());
		dump($partlist); die();*/
		
		

		if ( (strpos( $part_quantity, "." ) == false ) && is_numeric($part_quantity)==true) {
			foreach ($partlist as $part){
			     if ($part->part_number == $part_number){
			         echo 1;
			         die();
					}
				} 
			echo 3; die();
			
			}else {
				foreach ($partlist as $part){
			if ($part->part_number == $part_number){
			echo 2;
			die();
				}
			} 
			echo 4; die();}
		 }
		 
function checkpart_sale(){
		$part_quantity= $this->input->post('part_quantity');
		$part_number= $this->input->post('part_number');
		$company = $this->input->post('company');
		$page = $this->input->post('page');	
		if ($part_number == '' || $part_quantity =='' || $company == '' ){ die();}
		
		
		$this->load->model(array('parts/mdl_parts'));

		$partlist = $this->mdl_parts->getpart();

		/*//dump($this->db->last_query());*/

		
		

		if ( (strpos( $part_quantity, "." ) == false ) && is_numeric($part_quantity)==true) {
			foreach ($partlist as $part){
			     if ($part->part_number == $part_number){
			     	$json = array($part);
			     	echo json_encode($json);
			        die();
					}
				}
			echo 3;
			die();
			
			}else {
				foreach ($partlist as $part){
			if ($part->part_number == $part_number){
			echo 2;
			die();
				}
			} 
			echo 4;
			 die();}
		 }

function uploadform()
	{
		$this->redir->set_last_index();
		$this->load->view('purchase/purchase/uploadform');
	}

function upload()
	{
		$this->load->model(array('utilities/mdl_html','parts/mdl_parts','company/mdl_company'));
		$config['upload_path'] = './uploads/temp/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['file_name'] = $this->session->userdata('session_id').'_'.date('Y_m_d_H_i_s');
		//$config['file_name'] = 'excel_file';
		$this->load->library('upload', $config);
		$file_name = 'excel_file';
		if ( ! $this->upload->do_upload($file_name))
		{
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
			$arr = $this->upload->data();
			$data = array('upload_data' => $arr);
			$this->load->library('spreadsheet_excel_reader');
			/*$path = str_replace("/","\\",$arr['full_path']);*/
			$path = $arr['full_path'];
			$this->spreadsheet_excel_reader->read($path);
			$rows = $this->spreadsheet_excel_reader->sheets[0]['cells'];
			$row_count = count($this->spreadsheet_excel_reader->sheets[0]['cells']);
			$this->load->model(array('parts/mdl_parts'));
			$parts = $this->mdl_parts->getPartOptions();
			$companys = $this->mdl_company->getCompanyOptions();
			$part_arr = array();
			$company_arr = array();
			foreach ($companys as $company){
				$company_arr[] = trim($company->text," ");
				
				}
			foreach($parts as $part){
				$part_arr[] = trim($part->text," ");
			}
			
			$data = array(
						  'rows'=>$rows,
						  'row_count'=>$row_count,
						  'company_arr'=>$company_arr,
						  'part_arr'=>$part_arr
						  );
			@unlink($path);
			
			$this->load->view('purchase/purchase/excel_list',$data);
		}
	}
	
	
	function savep()
	{
		
			

			$this->load->model(array('purchase/mdl_purchase_details','company/mdl_company','parts/mdl_parts'));
			$part_no = $this->input->post('part_no');

			$part_no = $this->mdl_parts->getPartsId($part_no);

			$company = $this->input->post('company');

			$quantity = $this->input->post('quantity');
			
			$task_select = $this->input->post('task_select');



			$i=0;
			$cnt = 0;
			$error = array();
			foreach($task_select as $task){
				if($task==1){
					$company_id = $this->mdl_company->getcompanyid($company[$i]);
						$data = array(
									  'part_id'=>$part_no[$i],
									  'company_name'=>$company_id,
									  'part_quantity'=>$quantity[$i],
									  'purchase_id'=> $this->session->userdata('purchase_id'),
									  'purchase_details_created_ts' => date("Y-m-d H:i:s"),
									  'purchase_details_created_by'=>$this->session->userdata('user_id')
									  );
						$this->db->insert($this->mdl_purchase_details->table_name,$data);
						
						
				}
				$i++;
			}


			//$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_uploaded'));
			redirect('purchase/editrecord/'.$this->session->userdata('purchase_id'));
			//$this->load->view('dashboard/ajax_messages',$error);
	}
	


	function generatetemplateexl(){
		$this->load->library('parser');
		$this->load->helper('file');
		$data = array();

		//$data = $this->parser->parse('exl_template',$data,TRUE);
		$data = file_get_contents("uploads/temp/purchase_template.xls");
		$this->load->helper('download');
		$name = 'purchase_template_'.date("Y_m_d_H_i_s").'.xls';
		/*
		 **keep track of downloads
		 */
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'excel_template',
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
	
	function deleteuploadedparts(){
		
		$this->load->model('purchase/mdl_purchase_details');
		$this->mdl_purchase_details->deleteuploadedparts();
		
		}
	
	

}
?>
