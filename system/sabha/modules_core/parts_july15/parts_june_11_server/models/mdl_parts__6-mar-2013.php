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
			$this->db->like('pt.part_number', $searchtxt);
		}
		$this->db->select('pt.part_id,pt.part_number,part_desc,pt.part_landing_price,pt.part_customer_price,pt.part_sc_price');
		$this->db->from($this->table_name.' as pt');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		$parts = $result->result();

		//count total
		if(!empty($searchtxt))
		{
			$this->db->like('pt.part_number', $searchtxt);
		}
		$this->db->select('pt.part_id');
		$this->db->from($this->table_name.' as pt');
		$result_total = $this->db->get();

		$list['list'] = $parts;
		$list['total'] = $result_total->num_rows();
		return	$list;
	}

	public function getPartDetails($part_id){
		$this->db->select('pt.part_id,pt.part_number,part_desc,pt.part_customer_price,pt.part_sc_price,pt.part_landing_price');
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
			$this->db->like('pt.part_number', $searchtxt);
		}
		$this->db->select('pt.part_id,pt.part_number,part_desc,pt.part_landing_price,pt.part_customer_price,pt.part_sc_price,pm.model_number');
		$this->db->from($this->table_name.' as pt');
		$this->db->join($this->mdl_model_parts->table_name.' AS ptm','ptm.part_number=pt.part_number','inner');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_number=ptm.model_number','inner');
		$this->db->where('pm.model_id =',$model_id);
		//$this->db->where('pt.part_number NOT IN (Select Item_number FROM '.$this->mdl_call_parts->table_name.' WHERE call_id='.$call_id.')');
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		$parts = $result->result();
		
		//count total
		if(!empty($searchtxt))
		{
			$this->db->like('pt.part_number', $searchtxt);
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
		//$this->db->limit(10,900);
		//$this->db->where('pp.part_number =',$part_number);
		$this->db->order_by('pp.part_number ASC');
		$this->db->like('pp.part_number', $searchtxt);
		
		$result = $this->db->get();
		
		return $result->result();
	}
	public function getPartsDownload(){
		$this->load->helper('mcb_date');
		$this->db->select('part_number, part_landing_price, part_customer_price, part_sc_price, part_desc');
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
		$this->db->select('part_number');
		$this->db->from($this->table_name);
		$result= $this->db->get();
		return $result->result();
		}
	
}

?>