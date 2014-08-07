<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Account_Controller extends MX_Controller {

	public static $is_loaded;

	function __construct() {

		parent::__construct();

		$this->load->library('session');
		$this->load->library('general');
		$this->load->library('logger');
		$this->load->library('security');
		$this->config->set_item('asset_path', 'assets/');
		$this->config->set_item('csrf_protection', TRUE);
		$this->config->set_item('global_xss_filtering', TRUE);
		$this->load->helpers(array('my_path','my_date','date','custom','access','html','url','my_form'));
		$user_id = $this->session->userdata('user_id');

		if (!$user_id) {

			//print_r('agfhajkbaiu agvbiua ajgvbiua'); die();
			redirect('sessions/login'); 

		}

		if (!isset(self::$is_loaded)) {

			self::$is_loaded = TRUE;

			$this->load->config('mcb_menu/mcb_menu');


			$this->load->database();

			$this->load->helper(array('uri', 'mcb_date', 'mcb_icon', 'mcb_custom', 'mcb_app','logindetails'));

			$this->load->model(array('mcb_modules/mdl_mcb_modules','mcb_data/mdl_mcb_data','mcb_data/mdl_mcb_userdata'));

			$this->mdl_mcb_modules->set_module_data();

			$this->mdl_mcb_data->set_session_data();


			$this->mdl_mcb_modules->load_custom_languages();

			$this->load->language('mcb', $this->mdl_mcb_data->setting('default_language'));

			$this->general->setup_entry_types();
			
			$this->load->library(array('form_validation', 'redir'));
			$this->form_validation->set_error_delimiters('', '');

		}

	}

}

?>