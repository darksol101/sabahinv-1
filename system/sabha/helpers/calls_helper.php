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
function CalculateAgingDurationInDays($startDate,$startTime){
	$CI =& get_instance();
	if($startDate){
		$startdt = strtotime($startDate." ".$startTime);
		$now = time();
		$seconds = $now-$startdt;
		$days = round($seconds / 86400);
		$str = '';
		$str = $days.' '.$CI->lang->line((($days	> 1) ? 'date_days' : 'date_day'));
		
		return $str;
	}
}
function CalculateAgingTimeStamp($startDate,$startTime){
	$CI =& get_instance();
	if($startDate){
		$startdt = strtotime($startDate." ".$startTime);
		$now = time();
		$seconds = $now-$startdt;
		return $seconds;
	}
}

function CalculateAvgClosingTimeStamp($startDate,$startTime,$closedDate,$closedTime){
	$CI =& get_instance();
	if($startDate!=NULL AND $closedDate!=NULL){
		$startdt = strtotime($startDate." ".$startTime);
		//echo $startdt;
		$closeddt = strtotime($closedDate." ".$closedTime);
		//echo $closeddt;
		$seconds = $closeddt-$startdt;
		return $seconds;
	}
}

function CalculateSecondsToDays($seconds){
	$CI =& get_instance();
	$str = '';
	if($seconds>=0){
		$days = round($seconds / 86400);
		$str = $days.' '.$CI->lang->line((($days	> 1) ? 'date_days' : 'date_day'));
	}
	return $str;
}