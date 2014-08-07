<?php
function getDetailsByModel($model_id)
{
	$CI =& get_instance();
	$CI->load->model(array('brands/mdl_brands','products/mdl_products','product/mdl_productmodel'));
	$CI->db->select('b.brand_status,b.brand_name,b.brand_id,p.product_name,p.product_id,pm.model_id,pm.model_number');
	$CI->db->from($CI->mdl_productmodel->table_name.' as pm');
	$CI->db->join($CI->mdl_products->table_name.' as p','p.product_id=pm.product_id','left');	
	$CI->db->join($CI->mdl_brands->table_name.' as b','b.brand_id=p.brand_id','left');
	$CI->db->where('pm.model_id =',$model_id);
	$result = $CI->db->get();
	$details = $result->row();
	if($result->num_rows()==0){
		$details->status =0;
		$details->brand_name='';
		$details->brand_id='';
		$details->product_name='';
		$details->product_id='';
		$details->model_id='';
		$details->model_number='';
	}
	return $details;
}
?>