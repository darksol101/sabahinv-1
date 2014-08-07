<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Recover extends MY_Model {
    function validate_recover() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'required');
        return parent::validate();
    }
    function recover_password($username) {
        $this->db->where('username', $username);
		$this->load->model('users/mdl_users');
        $query = $this->db->get('mcb_users');
        if ($query->num_rows()>0) {
            $this->load->helper('mailer/phpmailer');
            $this->load->helper('text');
			$this->load->helper('auth');
            $user = $query->row();
            if ($user->email_address) {
                $password = random_string();
                //$params['reset_times'] = $user->reset_times+1;
				$params['password'] = md5_encrypt($password,$username);
				$this->mdl_users->save($params,$user->user_id);
				$from = $user->email_address;
                $to = $user->email_address;
                $this->mdl_mcb_data->set_session_data();
				$this->load->library('email');
				$this->email->from($this->email->smtp_user, 'Ghanshyam Chaudhari');
				$this->email->to($to);
				$this->email->subject('Password Reset CCMS');
				$messsage = sprintf($this->lang->line('password_recovery_email'),$user->username,application_title(),$password,anchor(site_url(), $this->lang->line('password_recovery_email_2')));
                $this->email->message($messsage);
				if(@$this->email->send()){
					$this->session->set_flashdata('success_save','Password has been sent to the email address associated with your user account.');
				}else{
					$this->session->set_flashdata('customerror','Sorry !! mail could not be sent.');
				}
            }
        }else{
			$this->session->set_flashdata('custom_warning','User does not exist.');
		}
    }
}

?>