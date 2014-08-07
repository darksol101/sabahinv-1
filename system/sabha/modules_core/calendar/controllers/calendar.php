<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Calendar extends Admin_Controller {
	function __construct() {
		parent::__construct();
	}
	function index() {
		$this->load->library('nepalicalendar');
		
		$links=1;
		$start_day 	= 0;
		$numMonths 	= 1;
		
		// if links are in use, create the link address and get our month offsetting parameter
		// our parameter is &cal_offset=nnx 
		// where nn is the current offset and x is 'p' for the previous month or 'n' for the next month
		
		$day_length	=1;
		$link = 1;
		$current_offset = 0;
		if ($links)
		{
		$uri = $_SERVER['REQUEST_URI'];
		$pos =  strpos($uri,'&cal_offset');				// we need the uri minus our parameter
		if ($pos)
		{
		$cal_offset = $this->input->get('cal_offset');
		$len = strlen($cal_offset);
		$more = substr($uri,$pos+strlen('&cal_offset=')+$len);	// could be more params after ours
		$link = substr($uri,0,$pos).$more;
		$command = $cal_offset{$len-1};					// get the p or the n
		$current_offset = substr($cal_offset,0,$len-1);	// strip off the p or the n
		if($current_offset > 283 || $current_offset < -794) {
		echo '<script> alert("The calendar can display only from Magh 2000 to Mangsir 2090 \n You cannot display the calender prior to it.")</script>';
		$current_offset = 0;
		}
		if ($command == 'p')
		$current_offset -= 1;						// request the previous month
		if ($command == 'n')
		$current_offset += 1;						// request the next month
		}
		else
		{
		$link = $uri;
		$command = '';
		$current_offset = 0;
		}
		if (!strstr($uri,'&'))
		$link = $uri.'?';
		$link .= '&cal_offset='.$current_offset;		// make the link
		$link = htmlspecialchars($link);
		}
		
		// Set the initial month and year, defaulting to the current month
		date_default_timezone_set('Asia/Katmandu');
		$year 	= date('Y');
		$month 	= date('m');
		$day    = date('d');
		
		// Add in the current offset
		$startdate = mktime(0,0,0,$month + $current_offset, $day, $year);
		
		$day = date('d', $startdate);
		$month = date('m',$startdate);
		$year = date('Y',$startdate);
		
		//Get Nepali Date by Converting English Date
		$nepali_date = $this->nepalicalendar->eng_to_nep($year, $month, $day);
		
		$month = $nepali_date['month'];
		$year = $nepali_date['year'];
		
		$calender =  $this->nepalicalendar->make_npcalendar($year, $month, $start_day, $day_length, $link, $nepali_date['nmonth'], $nepali_date['date'], $nepali_date['num_day'], $this->nepalicalendar, $links);
		$data = array('calender'=>$calender);
		$this->load->view('index',$data);
	}
	function getengdate(){
		$this->load->library('nepalicalendar');
		/*$str = $this->input->post('str');
		$arr = explode("_",$str);*/
		$this->load->library('nepalicalendar');
		$date = $this->nepalicalendar->nep_to_eng($this->input->post('year'),$this->input->post('month'),$this->input->post('day'));
		//$date = $this->nepalicalendar->nep_to_eng($arr[0],$arr[1],$arr[2]);
		$array = array();
		$array ['eng_date'] = sprintf("%02d",$date['date']).'/'.sprintf("%02d",$date['month']).'/'.$date['year'];
		$array ['nep_date'] = sprintf("%02d",$this->input->post('day')).'/'.sprintf("%02d",$this->input->post('month')).'/'.$this->input->post('year');
		echo json_encode($array);
		die();
		echo (sprintf("%02d",$date['date']).'/'.sprintf("%02d",$date['month']).'/'.$date['year']);
	}
	function cal(){
		$this->load->view('calendar/calendar/index');
	}
	function getcalendar(){
		$year 	= date('Y');
		$month 	= date('m');
		$day    = date('d');
		$this->load->library('nepalicalendar');
		$nepali_date = $this->nepalicalendar->eng_to_nep($year, $month, $day);
		
		$nep_date =1;
		$start_day = 0;
		$day_name_length	=1;
		$link = 1;
		
		$links= 0;
		$cal =  $this->nepalicalendar;
		$eng_date = $this->nepalicalendar->nep_to_eng($this->input->post('year'), $this->input->post('month'),$nep_date );
		$nepali_date = $this->nepalicalendar->eng_to_nep($eng_date['year'], $eng_date['month'], $eng_date['date']);
		$array = ($this->nepalicalendar->bs);
		
		echo $this->nepalicalendar->make_npcalendar($this->input->post('year'), $this->input->post('month'), $start_day, $day_name_length, $link = '', $nepali_date['nmonth'], $nepali_date['date'], $nepali_date['num_day'], $cal, $links);
	}
}