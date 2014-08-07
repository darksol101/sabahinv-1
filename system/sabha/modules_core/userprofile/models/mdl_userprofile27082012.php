<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Mdl_Userprofile extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'mcb_users';
		$this->primary_key = 'mcb_users.user_id';
		$this->order_by = ' user_name ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function validate() {
		$this->form_validation->set_error_delimiters('<span class="validationerror">', '</span>');
		$this->form_validation->set_rules('oldpassword', 'Old Password', 'required|callback_oldpassword_check');
		$this->form_validation->set_rules('newpassword', 'New Password', 'required');
		$this->form_validation->set_rules('repassword', 'Password Confirmation', 'required|matches[newpassword]');
		return parent::validate($this);
	}
	function oldpassword_check($str)
	{
		$this->load->helper('auth');
		$this->load->model('users/mdl_users');
		$user = json_decode($this->mdl_users->getUser($this->session->userdata('user_id')));
		if($str!=$user->password){
			$this->form_validation->set_message('oldpassword_check', 'The old password is incorrect.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function save() {
		$params['password'] = md5_encrypt($this->input->post('newpassword'),$this->session->userdata('username'));
		return parent::save($params, $this->session->userdata('user_id'));
	}
}
?>