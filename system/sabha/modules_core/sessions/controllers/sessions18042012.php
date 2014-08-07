<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Sessions extends CI_Controller {

    function __construct() {

        parent::__construct();

        $this->load->library(array('session'));

        $this->load->database();
		
		if (!$this->db->table_exists('mcb_users')) {

			$this->load->helper('url');

			redirect('setup');

		}

        $this->load->helper('mcb_app');

        $this->load->model('mcb_data/mdl_mcb_data');

        $this->mdl_mcb_data->set_application_title();

    }

    function index() {

        redirect('sessions/login');

    }

    function login() {

		$this->_load_language();

		$this->load->helper(array('url', 'form', 'auth'));

		$this->load->model('mdl_sessions');
		if($this->session->userdata('user_id')){
			$this->load->view('check_login_session');
		}
		$this->load->model(array('servicecenters/mdl_servicecenters','utilities/mdl_html'));
		$this->load->model(array('users/mdl_users'));
		$username  = $this->input->post('username');

		if ($this->mdl_sessions->validate()) {

			$this->load->model('mdl_auth');

			if ($user = $this->mdl_auth->auth('mcb_users', 'username', 'password', $this->input->post('username'), $this->input->post('password'))) {
				if($username){
					$ss_details = $this->mdl_users->getScenterDetailsByUser($username);
					$sc_name = $ss_details->sc_name;
					$sc_id = $ss_details->sc_id;
					$city_id = $ss_details->city_id;
					$city_name = $ss_details->city_name;
					$zone_name = $ss_details->zone_name;
					$zone_code = $ss_details->zone_code;
					$district_name = $ss_details->district_name;
				}
				$object_vars = array('user_id', 'last_name', 'first_name', 'global_admin', 'userid','usergroup_id');

				// set the session variables
				$this->mdl_auth->set_session($user, $object_vars, array('is_admin'=>TRUE, 'is_loggedin'=>TRUE,'sc_id'=>$sc_id,'sc_name'=>$sc_name,'city_id'=>$city_id,'city_name'=>$city_name,'zone_name'=>$zone_name,'zone_code'=>$zone_code,'district_name'=>$district_name,'username'=>$username));

				// update the last login field for this user
				$this->mdl_auth->update_timestamp('mcb_users', 'id', $user->id, 'last_login', time());
				/*echo '<pre>';
				print_r($this->session->userdata);
				print_r($user);
				die();*/
				redirect('callcenter/calls');

			}else{
				redirect('sessions/login',$this->session->set_flashdata('login_error',$this->lang->line('invalid_username_or_password')));
			}

		}
		$data=array("title"=>"Log in", "headline"=>"User Log in");
		$this->load->view('login', $data);

	}

    function logout() {

        $this->load->helper('url');

        $this->session->sess_destroy();

        redirect('sessions/login');

    }

    function recover() {

        $this->_load_language();

        $this->load->model('mdl_recover');

        $this->load->helper(array('url', 'form'));

        if (!$this->mdl_recover->validate_recover()) {

            $this->load->view('recover');

        }

        else {

            $this->mdl_recover->recover_password($this->input->post('username'));

            $this->load->view('recover_email');

        }

    }

    function _load_language() {

        $this->load->model('mcb_data/mdl_mcb_data');

        $default_language = $this->mdl_mcb_data->get('default_language');

        if ($default_language) {

            $this->load->language('mcb', $default_language);

        }

        else {

            $this->load->language('mcb');

        }

    }

}

?>