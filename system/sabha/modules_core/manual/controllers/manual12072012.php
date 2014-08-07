<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Manual extends Admin_Controller {
	function __construct() {
		parent::__construct(TRUE);
		$this->load->language("manual",  $this->mdl_mcb_data->setting('default_language'));
		$this->_post_handler();
	}
	function uploadform()
	{
		$this->redir->set_last_index();
		$this->load->model(array('manual/mdl_manual'));
		$model_id = $this->input->get('model_id');
		$manuals = $this->mdl_manual->getManuals($model_id);
		$data = array(
					  'model_id'=>$model_id,
					  'manuals'=>$manuals
					  );
		$this->load->view('upload',$data);
	}
	function getmanuallist()
	{
		$this->redir->set_last_index();
		$this->load->model(array('manual/mdl_manual'));
		$model_id = $this->input->post('model_id');
		$manuals = $this->mdl_manual->getManuals($model_id);
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'manuals'=>$manuals
					  );
		$this->load->view('process',$data);
	}
	function upload(){
		$this->load->model(array('manual/mdl_manual'));
		$config['upload_path'] = 'uploads/product_models/';
		$config['allowed_types'] = $this->mdl_mcb_data->get('file_type');
		$config['max_size']	=  1024*(int)$this->mdl_mcb_data->get('max_upload_size');

		$this->load->library('upload', $config);
		
		$field_name = "files";
		if ( ! $this->upload->do_upload($field_name))
		{
			$error = array(
						   'error' => '<span class="message_text"><div class="notification error png_bg"><div>'.$this->upload->display_errors().'</div></div></span>',
						   'model_id'=>$this->input->post('model_id'),
						   );
			echo json_encode($error);
		}
		else
		{
			$arr  = $this->upload->data();	
			$params = array(
							'model_id'=>$this->input->post('model_id'),
							'file_name'=>$arr['file_name'],
							'full_path'=>$arr['full_path'],
							'file_ext'=>$arr['file_ext']
							);
			$this->mdl_manual->save($params);
			$manual_id = $this->db->insert_id();
			$data = array(
						  'error'=>'<span class="message_text"><div class="notification success png_bg"><div>Uploaded successfully</div></div></span>',
						  'manual_id'=>$manual_id,
						  'model_id'=>$this->input->post('model_id'),
						  'upload_data' => $this->upload->data()
						  );
			echo json_encode($data);
		}
	}
	function deletemanual()
	{
		$this->load->model(array('manual/mdl_manual'));
		$this->load->helper('file');
		$file_name = $this->input->post('file_name');
		$manual_id = $this->input->post('manual_id');
		$path = 'uploads/product_models/'.$file_name;
		if(unlink($path)){
			$this->mdl_manual->delete(array('manual_id'=>$manual_id));
		}
	}
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
	function _post_handler() {		
	}
}

?>