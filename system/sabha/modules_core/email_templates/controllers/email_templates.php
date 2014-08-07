<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class email_templates extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->_post_handler();
	}
	function index(){
		if($this->session->userdata('global_admin')<1){
			$this->session->set_flashdata('custom_warning','You are not authorised to access');
			redirect('');
		}
		$this->redir->set_last_index();
		$this->load->helper('file');
		$call_string = read_file('system/nepotech/modules_core/email_templates/views/calls_email_template.php');
		$dsr_string = read_file('system/nepotech/modules_core/email_templates/views/dsr_email_template.php');
		$closecall_string = read_file('system/nepotech/modules_core/email_templates/views/closedcall_email_template.php');
		$data = array(
					  'call_string'=>$call_string,
					  'dsr_string'=>$dsr_string,
					  'closecall_string'=>$closecall_string
					  );
		$this->load->view('index',$data);
	}
	function _post_handler(){
		if($this->input->post('save')){
			$this->load->helper('file');
			$call_string = $this->input->post('call_string');
			if ( ! write_file('system/nepotech/modules_core/email_templates/views/calls_email_template.php', $call_string)){
				$this->session->set_flashdata('save_success','Unable to write the file');
			}else{
				$this->session->set_flashdata('save_success','File written!');
			}
			$dsr_string = $this->input->post('dsr_string');
			if ( ! write_file('system/nepotech/modules_core/email_templates/views/dsr_email_template.php', $dsr_string)){
				$this->session->set_flashdata('save_success','Unable to write the file');
			}
			$closecall_string = $this->input->post('closecall_string');
			if ( ! write_file('system/nepotech/modules_core/email_templates/views/closedcall_email_template.php', $closecall_string)){
				$this->session->set_flashdata('save_success','Unable to write the file');
			}
			redirect('email_templates');
		}
	}
}
?>