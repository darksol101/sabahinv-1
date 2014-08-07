<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function showLoginDetails(){
	$CI =& get_instance();
	$session_details = $CI->session->userdata;
	$data = array('session_details'=>$session_details);
	$CI->load->view('dashboard/welcome_message',$data);
}