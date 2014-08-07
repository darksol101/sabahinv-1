<?php
function sorter($sort_field) {
	$CI =& get_instance();
	$CI->load->helper('sorter');
	$sort_field_request = $CI->input->post('sort_field');
	$sort_type = $CI->input->post('sort_type');
	$ext = 'png';
	if(strtolower($sort_type)=='desc'){
		$sort_type = 'ASC';
		$icon = 'black-asc';
	}else{
		$sort_type = 'DESC';
		$icon = 'black-desc';
	}
	
	if($sort_field == $sort_field_request){		
		return '<a class="btn" onclick="sortdata('."'".$sort_field."'".','."'".$sort_type."'".')"><img src="' . base_url() . 'assets/style/img/icons/' . $icon . '.' . $ext . '" /></a>';
	}else{
		$icon1 = 'black-asc';
		$icon2 = 'black-desc';
		$sort_type1 = 'ASC';
		$sort_type2 = 'DESC';
		return '<span class="ascdscarrow"><a title="Click to sort by '.$sort_field.'" class="btn" style="" onclick="sortdata('."'".$sort_field."'".','."'".$sort_type1."'".')"><img src="' . base_url() . 'assets/style/img/icons/' . $icon2 . '.' . $ext . '" /></a><div style="clear:both; height:1px;"></div><a class="btn" onclick="sortdata('."'".$sort_field."'".','."'".$sort_type2."'".')"><img src="' . base_url() . 'assets/style/img/icons/' . $icon1 . '.' . $ext . '" /></a></span>';
	}
}

?>
