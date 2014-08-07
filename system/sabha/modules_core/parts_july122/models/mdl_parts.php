<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Parts extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_prod_parts';
		$this->primary_key = 'sst_prod_parts.part_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' part_number ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}
	

	public function getParts($page){
		$searchtxt=$this->input->post('searchtxt');

		if(!empty($searchtxt))
		{
			
			// $this->db->where("pt.part_number like '%".$searchtxt."%' or part_desc like '%".$searchtxt."%' ");
			$this->db->like('pt.part_number', $searchtxt) ;
			$this->db->or_like('part_desc', $searchtxt) ;
			//$this->db->like('('.'pt.part_number '.$searchtxt.' OR part_desc '.$searchtxt.')');
			$this->db->or_like('ppm.model_number',$searchtxt);
		}
		
		$this->db->select('pt.order_level,pt.order_level_max,pt.part_initial_no,pt.part_id,pt.part_size,pt.part_color,pt.part_number,part_desc,pt.part_landing_price,pt.part_customer_price,pt.part_sc_price');
		$this->db->from($this->table_name.' as pt');
		$this->db->join($this->mdl_model_parts->table_name.' AS ppm', 'ppm.part_number=pt.part_number','left');
			
		$this->db->group_by('pt.part_number');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		//echo $this->db->last_query();
		$parts = $result->result();

		//count total
		if(!empty($searchtxt))
		{
			$this->db->like('pt.part_number', $searchtxt);
			$this->db->or_like('part_desc', $searchtxt);
			//$this->db->where("pt.part_number like '%".$searchtxt."%' or part_desc like '%".$searchtxt."%' ");
			$this->db->or_like('ppm.model_number',$searchtxt);
		}
		
		$this->db->select('pt.part_id');
		$this->db->from($this->table_name.' as pt');
		$this->db->join($this->mdl_model_parts->table_name.' AS ppm', 'ppm.part_number=pt.part_number','left');
	//	$this->db->or_like('ppm.model_number',$searchtxt);
		$this->db->group_by('pt.part_number');
		$result_total = $this->db->get();

		$list['list'] = $parts;
		$list['total'] = $result_total->num_rows();
		return	$list;
	}

	public function getPartDetails($part_id){
		$this->db->select('pt.unit,pt.part_id,pt.order_level,pt.order_level_max,pt.part_number,pt.part_initial_no,pt.part_color,pt.part_size,part_desc,pt.part_customer_price,pt.part_sc_price,pt.part_landing_price');
		$this->db->from($this->table_name.' as pt');
		$this->db->where('pt.part_id =',$part_id);
		$result=$this->db->get();
		$part=$result->row();
		return $part;
	}
	function getPartOptions()
	{
		$params=array(
					 "select"=>"part_number as text, part_id as value",
					 "order_by"=>'part_number'
					 );
		 $part_options=$this->get($params);
		 return $part_options;
	}
	function getPartsInclude($page){
		$searchtxt=$this->input->post('searchtxt');
		$call_id = $this->input->post('call_id');
		$model_id = $this->input->post('model_id');
		
		if(!empty($searchtxt))
		{
			$this->db->where("(pt.part_number LIKE '%$searchtxt%' OR part_desc LIKE '%$searchtxt%')");
		}
		$this->db->select('pt.part_id,pt.part_color,pt.part_size,pt.part_number,part_desc,pt.part_landing_price,pt.part_customer_price,pt.part_sc_price,pm.model_number');
		$this->db->from($this->table_name.' as pt');
		$this->db->join($this->mdl_model_parts->table_name.' AS ptm','ptm.part_number=pt.part_number','inner');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_number=ptm.model_number','inner');
		$this->db->where('pm.model_id =',$model_id);
		//$this->db->where('pt.part_number NOT IN (Select Item_number FROM '.$this->mdl_call_parts->table_name.' WHERE call_id='.$call_id.')');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		$parts = $result->result();
		
		if(!empty($searchtxt))
		{
			$this->db->where("(pt.part_number LIKE '%$searchtxt%' OR part_desc LIKE '%$searchtxt%')");
		}
		$this->db->select('pt.part_id');
		$this->db->from($this->table_name.' as pt');
		$this->db->join($this->mdl_model_parts->table_name.' AS ptm','ptm.part_number=pt.part_number','left');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_number=ptm.model_number','left');
		$this->db->where('pm.model_id =',$model_id);
		$result_total = $this->db->get();

		$list['list'] = $parts;
		$list['total'] = $result_total->num_rows();
		return	$list;
	}
	function getPartslist($part_number){
		$searchtxt=$this->input->get('term');
		$pdesc=$this->input->get('pdesc');
		if(!empty($pdesc)){
			$this->db->or_like('pp.part_desc', $pdesc);
		}
		
		$this->db->select('pp.part_number AS id,pp.part_number AS label,pp.part_number AS value,pp.part_desc AS pdesc');
		$this->db->from($this->table_name.' AS pp');
		//$this->db->join($this->mdl_model_parts->table_name.' AS ppm', 'ppm.part_number=pp.part_number');
		//$this->db->limit(10,900);
		//$this->db->where('pp.part_number =',$part_number);
		$this->db->order_by('pp.part_number ASC');
		$this->db->like('pp.part_number', $searchtxt);
		//$this->db->or_like('pp.part_number', $pdesc);
		//$this->db->or_like('ppm.model_number',$searchtxt);
		//$this->db->order_by('pp.part_number');
		
		$result = $this->db->get();
		
		return $result->result();
	}
	
function getPartslistmodel($part_number){
		$searchtxt=$this->input->get('term');
		$pdesc=$this->input->get('pdesc');
		if(!empty($pdesc)){
			$this->db->or_like('pp.part_desc', $pdesc);
		}
		
		$this->db->select('pp.part_number AS id,pp.part_number AS label,pp.part_number AS value1,pp.part_desc AS pdesc,ppm.model_number as value');
		$this->db->from($this->table_name.' AS pp');
		$this->db->join($this->mdl_model_parts->table_name.' AS ppm', 'ppm.part_number=pp.part_number');
		//$this->db->limit(10,900);
		//$this->db->where('pp.part_number =',$part_number);
		$this->db->order_by('pp.part_number ASC');
		//$this->db->like('pp.part_number', $searchtxt);
		$this->db->or_like('ppm.model_number',$searchtxt);
		
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}
	
	
	
	public function getPartsDownload(){
		$this->load->helper('mcb_date');
		$this->db->select('part_number,part_color,part_size,part_desc,order_level,part_landing_price, part_customer_price, part_sc_price');
        $this->db->from($this->table_name);
		$result = $this->db->get();
        $list['result'] = $result;
		return $list;
	}
	
	function getProductOptionsByBrands($brands){
		if(empty($brands)){
			$brands = '0';
		}
		$this->db->select('product_id as value,CONCAT(brand_name," / ",product_name) as text,b.brand_name,b.brand_id',false);
		$this->db->from($this->table_name.' AS p',$this->mdl_brands->table_name.' AS b');
		$this->db->join($this->mdl_brands->table_name.' AS b','b.brand_id = p.brand_id','left');
		$this->db->where("p.brand_id IN ($brands)");
		$this->db->order_by('b.brand_name ASC,p.product_name ASC');
		$result = $this->db->get();
		return $result->result();
	}
	
	function getModelByProduct($product){
		if(empty($product)){
			$brands = '0';
		}
		$this->db->select('model_id as value,CONCAT(product_name," / ",model_name) as text,b.brand_name,b.brand_id',false);
		$this->db->from($this->mdl_productmodel.' AS pm',$this->mdl_brands->table_name.' AS b');
		$this->db->join($this->mdl_brands->table_name.' AS b','b.brand_id = p.brand_id','left');
		$this->db->where("p.brand_id IN ($brands)");
		$this->db->order_by('b.brand_name ASC,p.product_name ASC');
		$result = $this->db->get();
		return $result->result();
	}
	
	function getpart(){
		$this->db->select('part_number,part_id');
		$this->db->from($this->table_name);
		$result= $this->db->get();
		return $result->result();
	}

	function getPartWithDis($part_id){
		$this->db->select('m.maker_id,sm.sale_name,sm.sale_deduction_value,sm.sale_deduction_type');
		$this->db->from($this->table_name.' pp');
		$this->db->join($this->mdl_salesmakerlist->table_name.' m', 'm.part_id = pp.part_id', 'inner');
		$this->db->join($this->mdl_salesmaker->table_name.' sm', 'sm.maker_id = m.maker_id', 'inner');
		$this->db->where('pp.part_id', $part_id);
		$this->db->where('sm.sale_date_end >=', date('Y-m-d'));
		$this->db->where("sm.sale_status = ", "1");
		$result = $this->db->get();
		return $result->result();

	}

		public function getPartNumber($part_id)
		{
			$this->db->select('part_number');
			$this->db->from($this->table_name);
			$this->db->where('part_id', $part_id);
			$result= $this->db->get();
			return $result->result_array();
		}

		
		function checkpart($part_number){
		$this->db->select('part_number');
		$this->db->from($this->table_name);
		$this->db->where('part_number =',$part_number);
		$result= $this->db->get();
		
		return $result->num_rows();
			
			}
			
		function getPartsorder($page){
		$searchtxt=$this->input->post('searchtxt');
		if(!empty($searchtxt))
		{
			$this->db->like('pt.part_number', $searchtxt);
			$this->db->or_like('pt.part_desc', $searchtxt);
			
		}
		$this->db->select('DISTINCT(pt.part_number),part_desc');
		$this->db->from($this->table_name.' as pt',$this->mdl_model_parts->table_name.' AS ptm');
		$this->db->order_by('pt.part_number');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		$parts = $result->result();
		
		if(!empty($searchtxt))
		{
			$this->db->like('pt.part_number', $searchtxt);
			$this->db->or_like('pt.part_desc', $searchtxt);
		}
		$this->db->select('DISTINCT(pt.part_number),part_desc');
		$this->db->from($this->table_name.' as pt',$this->mdl_model_parts->table_name.' AS ptm');
		$result_total = $this->db->get();

		$list['list'] = $parts;
		$list['total'] = $result_total->num_rows();
		return	$list;
	}

	//get part id from part Number
	function getPartsId($part_numbers){
	if(is_array($part_numbers)){
		$part_ids=array();
		
		foreach ($part_numbers as $part_number){
		
		$this->db->select('part_id')
			->from($this->table_name)
			->where('part_number',$part_number);
			$id=$this->db->get()->row();
			$part_ids[]=$id->part_id;
		}

		return $part_ids;

	}else{

			$this->db->select('part_id')
			->from($this->table_name)
			->where('part_number',$part_numbers);
			$id=$this->db->get()->row();
			return $id->part_id;
	}
		
	}

	//get model id from model number
	
	function getModelsId($model_numbers){
	if(is_array($model_numbers)){
		
		foreach ($model_numbers as $model_number){
		
		$this->db->select('model_id')
			->from('sst_prod_models')
			->where('model_number',$model_number);
			$id=$this->db->get()->row();
			$model_ids[]=$id->model_id;
		}
		return $model_ids;

	}
	else
	{
		$this->db->select('model_id')
			->from('sst_prod_models')
			->where('model_number',$model_numbers);
			$id=$this->db->get()->row();
			return $id->model_id;
	}
		
}
	
function getCusPrice($part_num){
		$this->db->select('part_customer_price');
		$this->db->from($this->mdl_parts->table_name.' AS p');
		$this->db->where('p.part_number =',$part_num);
		$result = $this->db->get();
		//echo $this->db->last_query();
		if ($result->num_rows() > 0){
			$result = $result->row()->part_customer_price;
		}else{
			$result = 0;
		}
		return $result;
	}	
		
		
}

?>