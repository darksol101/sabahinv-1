<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Warranty extends Admin_Controller {
	function __construct() {
		parent::__construct(TRUE);
		$this->load->language("warranty",  $this->mdl_mcb_data->setting('default_language'));
		$this->_post_handler();
	}
	function uploadform()
	{
		$this->redir->set_last_index();
		$this->load->model(array('manual/mdl_manual'));
		$call_id = $this->input->get('call_id');
		$data = array(
					  'call_id'=>$call_id
					  );
		$this->load->view('upload',$data);
	}
	function getwarrantyuploads()
	{
		$this->redir->set_last_index();
		$this->load->model(array('warranty/mdl_warranty_upload'));
		$call_id = $this->input->post('call_id');
		$warranty_uploads = $this->mdl_warranty_upload->getWarrantyUpload($call_id);
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'warranty_uploads'=>$warranty_uploads
					  );
		$this->load->view('process',$data);
	}
	function upload(){
		$this->load->model(array('warranty/mdl_warranty_upload'));
		$config['upload_path'] = 'uploads/product_warranty/';
		$config['allowed_types'] = $this->mdl_mcb_data->get('file_type');
		$config['max_size']	=  1024*(int)$this->mdl_mcb_data->get('max_upload_size');

		$this->load->library('upload', $config);
		
		$field_name = "files";
		if ( ! $this->upload->do_upload($field_name))
		{
			$error = array(
						   'error' => '<span class="message_text"><div class="notification error png_bg"><div>'.$this->upload->display_errors().'</div></div></span>',
						   'call_id'=>$this->input->post('call_id'),
						   );
			echo json_encode($error);
		}
		else
		{
			$arr  = $this->upload->data();	
			$params = array(
							'call_id'=>$this->input->post('call_id'),
							'warranty_file_name'=>$arr['file_name'],
							'warranty_full_path'=>$arr['full_path'],
							'warranty_file_ext'=>$arr['file_ext'],
							'warranty_upload_created_ts'=>date("Y-m-d H:i:s"),
							'warranty_upload_created_by'=>$this->session->userdata('user_id')
							);
			$this->mdl_warranty_upload->save($params);
			$warranty_upload_id = $this->db->insert_id();
			$data = array(
						  'error'=>'<span class="message_text"><div class="notification success png_bg"><div>Uploaded successfully</div></div></span>',
						  'call_id'=>$this->input->post('call_id'),
						  'upload_data' => $this->upload->data()
						  );
			echo json_encode($data);
		}
	}
	function deletewarrantyuplaod()
	{
		$this->load->model(array('warranty/mdl_warranty_upload'));
		$this->load->helper('file');
		$file_name = $this->input->post('file_name');
		$warranty_upload_id = $this->input->post('warranty_upload_id');
		$path = 'uploads/product_warranty/'.$file_name;
		if(@unlink($path)){
			$this->mdl_warranty_upload->delete(array('warranty_upload_id'=>$warranty_upload_id));
		}
	}
	function download_manual()
	{
		ini_set("memory_limit","128M");
		$this->redir->set_last_index();
		$this->load->library('zip');
		$this->load->model(array('manual/mdl_manual','productmodel/mdl_productmodel'));
		$model_id = $this->input->get('model_id');
		if((int)$model_id>0){
			$manuals = $this->mdl_manual->getManuals($model_id);
			$model_details = $this->mdl_productmodel->getModelDetails($model_id);
			if(count($model_details)>0){
				$model_number = $model_details->model_number;
			}else{
				$model_number='';
			}
			foreach($manuals as $manual){
				$path =  'uploads/product_models/'.$manual->file_name;
				$data[$manual->file_name] = file_get_contents($path);
			}
			$this->load->library('security');
			$name ='Manual_'.$this->security->sanitize_filename($model_number).'.zip';
			$this->zip->add_data($data);		
			$this->zip->download($name);
		}
	}
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
	function _post_handler() {		
	}
}

?>