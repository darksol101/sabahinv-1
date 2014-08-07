<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function mysqldatetime_to_timestamp($datetime = "")
{
  // function is only applicable for valid MySQL DATETIME (19 characters) and DATE (10 characters)
  $l = strlen($datetime);
    if(!($l == 10 || $l == 19))
      return 0;

    //
    $date = $datetime;
    $hours = 0;
    $minutes = 0;
    $seconds = 0;

    // DATETIME only
    if($l == 19)
    {
      list($date, $time) = explode(" ", $datetime);
      list($hours, $minutes, $seconds) = explode(":", $time);
    }

    list($year, $month, $day) = explode("-", $date);

    return mktime($hours, $minutes, $seconds, $month, $day, $year);
}
function mysqldatetime_to_date($datetime = "", $format = "d.m.Y, H:i:s")
{
    return date($format, mysqldatetime_to_timestamp($datetime));
}
function timestamp_to_mysqldatetime($timestamp = "", $datetime = true)
{
  if(empty($timestamp) || !is_numeric($timestamp)) $timestamp = time();

    return ($datetime) ? date("Y-m-d H:i:s", $timestamp) : date("Y-m-d", $timestamp);
}
function date_to_timestamp($datetime = "")
{
  if (!preg_match("/^(\d{1,2})[.\- \/](\d{1,2})[.\- \/](\d{2}(\d{2})?)( (\d{1,2}):(\d{1,2})(:(\d{1,2}))?)?$/", $datetime, $date))
    return FALSE;
    
  $day = $date[1];
  $month = $date[2];
  $year = $date[3];
  $hour = (empty($date[6])) ? 0 : $date[6];
  $min = (empty($date[7])) ? 0 : $date[7];
  $sec = (empty($date[9])) ? 0 : $date[9];

  return mktime($hour, $min, $sec, $month, $day, $year);
}
function date_to_mysqldatetime($date = "", $datetime = TRUE)
{
  return timestamp_to_mysqldatetime(date_to_timestamp($date), $datetime);
} 
function format_date($unix_timestamp_date = NULL) {

	if ($unix_timestamp_date) {

		global $CI;

		return date($CI->mdl_mcb_data->setting('default_date_format'), $unix_timestamp_date);

	}

	return '';

}

function standardize_date($date) {

	global $CI;

	if (strstr($date, '/')) {

		$delimiter = '/';

	}

	elseif (strstr($date, '-')) {

		$delimiter = '-';

	}

	elseif (strstr($date, '.')) {

		$delimiter = '.';

	}

	else {

		// do not standardize
		return $date;

	}

	$date_format = explode($delimiter, $CI->mdl_mcb_data->setting('default_date_format'));

	$date = explode($delimiter, $date);

	foreach ($date_format as $key=>$value) {

		$standard_date[strtolower($value)] = $date[$key];

	}

	return $standard_date['m'] . '/' . $standard_date['d'] . '/' . $standard_date['y'];

}

function date_formats($format = NULL, $element = NULL) {

	$date_formats = array(
		'm/d/Y' => array(
			'key' => 'm/d/Y',
			'picker' => 'mm/dd/yy',
			'mask' => '99/99/9999',
			'dropdown' => 'mm/dd/yyyy'),
		'm/d/y' => array(
			'key' => 'm/d/y',
			'picker' => 'mm/dd/y',
			'mask' => '99/99/99',
			'dropdown' => 'mm/dd/yy'),
		'Y/m/d' => array(
			'key' => 'Y/m/d',
			'picker' => 'yy/mm/dd',
			'mask' => '9999/99/99',
			'dropdown' => 'yyyy/mm/dd'),
		'd/m/Y' => array(
			'key' => 'd/m/Y',
			'picker' => 'dd/mm/yy',
			'mask' => '99/99/9999',
			'dropdown' => 'dd/mm/yyyy'),
		'd/m/y' => array(
			'key' => 'd/m/y',
			'picker' => 'dd/mm/y',
			'mask' => '99/99/99',
			'dropdown' => 'dd/mm/yy'),
		'm-d-Y' => array(
			'key' => 'm-d-Y',
			'picker' => 'mm-dd-yy',
			'mask' => '99-99-9999',
			'dropdown' => 'mm-dd-yyyy'),
		'm-d-y' => array(
			'key' => 'm-d-y',
			'picker' => 'mm-dd-y',
			'mask' => '99-99-99',
			'dropdown' => 'mm-dd-yy'),
		'Y-m-d' => array(
			'key' => 'Y-m-d',
			'picker' => 'yy-mm-dd',
			'mask' => '9999-99-99',
			'dropdown' => 'yyyy-mm-dd'),
		'y-m-d' => array(
			'key' => 'y-m-d',
			'picker' => 'y-mm-dd',
			'mask' => '99-99-99',
			'dropdown' => 'yy-mm-dd'),
		'd.m.Y' => array(
			'key' => 'd.m.Y',
			'picker' => 'dd.mm.yy',
			'mask' => '99.99.9999',
			'dropdown' => 'dd.mm.yyyy'),
		'd.m.y' => array(
			'key' => 'd.m.y',
			'picker' => 'dd.mm.y',
			'mask' => '99.99.99',
			'dropdown' => 'dd.mm.yy')
	);
	
	if ($format and $element) {
		
		return $date_formats[$format][$element];
		
	}
	
	elseif ($format) {
		
		return $date_formats[$format];
		
	}
	
	else {
		
		return $date_formats;
		
	}

}

?>