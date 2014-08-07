<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class authorization{
	
	var $CI;

	/**
	 * Constructor
	 *
	 */	
	 function __construct()
	 {
		$CI =& get_instance();
		$CI->load->library('session');
		$CI->load->helper('auth');
		$user_id = $CI->session->userdata('user_id');
		$CI->load->model('users/mdl_users');
		$userarr = json_decode($CI->mdl_users->getUser($user_id));
		$last_index = $CI->session->userdata('last_index');
		
		if($userarr->usergroup!=1){
			$CI->session->set_flashdata('message', 'You are not authorized to view this resource ! !');
			redirect('dashboard');
		}
	}
	function getModules($module)
	{
		$modules = array(
						 'items',
						 'users',
						 'settings',
						 'mcb_modules',
						 'branches'
						 );
		if(in_array($module,$modules)){
			return false;
		}else{
			return true;
		}
	}
}
/* Location: ./system/libraries/authorization.php */