<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Admin_Controller extends MX_Controller {

	public static $is_loaded;

	function __construct() {

		parent::__construct();

		$this->load->library('session');

        $this->load->helper('url');
        $this->load->helper('cus');

		$user_id = $this->session->userdata('user_id');

        if (!$user_id) {

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


			$this->load->library(array('form_validation', 'redir'));

		}

	}

}

?>