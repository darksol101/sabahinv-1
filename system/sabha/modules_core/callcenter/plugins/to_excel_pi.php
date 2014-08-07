<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Excel library for Code Igniter applications
* Author: Derek Allard, Dark Horse Consulting, www.darkhorse.to, April 2006
*/

function to_excel($arrays, $filename='exceloutput',$fields)
{
     $headers = ''; // just creating the var for field headers to append to below
     $data = ''; // just creating the var for field data to append to below
     
     $obj =& get_instance();
     
     //$fields = $query->field_data();
     if (count($data) == 0) {
          echo '<p>The table appears to have no data.</p>';
     } else {
          foreach ($fields as $field) {
             $headers .= $field . "\t";
          }
          foreach ($arrays as $row) {
               $line = '';
               foreach($row as $value) {                                            
                    if ((!isset($value)) OR ($value == "")) {
                         $value = "\t";
                    } else {
                         $value = str_replace('"', '""', $value);
                         $value = '"' . $value . '"' . "\t";
                    }
                    $line .= $value;
               }
               $data .= trim($line)."\n";
          }
          
          $data = str_replace("\r","",$data);
                         
          header("Content-type: application/x-msdownload");
          header("Content-Disposition: attachment; filename=$filename.xls");
          echo "$headers\n$data";  
     }
}
function convert_to_table($arrays,$fields){
	$CI =& get_instance();
	$CI->load->library('table');
	$tmpl = array (
				   'table_open'  => '<table border="1" cellpadding="2" cellspacing="1">',
				   'heading_row_start'   => '<tr>',
				    'heading_row_end'     => '</tr>',
					  'heading_cell_start'  => '<th style="background: none repeat scroll 0 0 #00689C;color:#FFF;">'
				   );	
	$CI->table->set_template($tmpl); 
	$CI->table->set_heading($fields);
	 foreach ($arrays as $row) {
		 $arr = array();
		  foreach($row as $value) {  
	 		$arr[]  = $value;
		  }
		  $CI->table->add_row($arr);
		  unset($arr);
	 }
	return $CI->table->generate(); 
}
?>