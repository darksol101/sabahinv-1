<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function CalculateAgingDuration($startDate,$startTime){
	$CI =& get_instance();
	$CI->load->helper('date');
	if($startDate){
		$startdt = strtotime($startDate." ".$startTime);
		$now = time();		
		return timespan($startdt, $now);
	}
}