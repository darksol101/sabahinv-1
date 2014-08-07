<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bills extends Account_Controller{
	function __construct(){
		parent::__construct();
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   				
	}
	function index(){
		/* Check access */
		$data = array();
		$this->load->view('bills/index',$data);
	}

}