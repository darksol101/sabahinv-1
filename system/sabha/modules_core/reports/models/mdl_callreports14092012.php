<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class mdl_callreports extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_calls';
		$this->primary_key = 'sst_calls.call_id';
		$this->logged=$this->createlogtable($this->table_name);
	}

	function getCallReports($page)
	{
		$cid=$this->input->get('cid');
		$cn=$this->input->get('cn');
		$pn=$this->input->get('pn');
		$sn=$this->input->get('sn');
		$ph=$this->input->get('ph');
		$eg=$this->input->get('eg');
		$sc=$this->input->get('sc');
		$products=$this->input->get('products');
		$call_status = $this->input->get('cs');
		$from_date = $this->input->get('from');
		$to_date = $this->input->get('to');
		$this->load->helper('mcb_date');

		$where = array();
		if($cid){
			$this->db->like('c.call_uid',$cid);
		}
		if($cn){
			$this->db->where("(cs.cust_first_name LIKE '%$cn%' OR cs.cust_last_name LIKE '%$cn%')");
		}
		if($pn){
			$this->db->like('p.product_name',$pn);
		}
		if($sn){
			$this->db->like('c.call_serial_no',$sn);
		}
		if($ph){
			$this->db->where("(cs.cust_phone_mobile LIKE '%$ph%' OR cs.cust_phone_office LIKE '%$ph%' OR cs.cust_phone_home LIKE '%$ph%')");
		}
		if($eg!=''){
			$this->db->where('c.engineer_id =',$eg);
		}
		if($sc!=''){
			$this->db->where('c.sc_id',$sc);
		}
		$products = ($products=="")?"0":$products;
		if($products!='null'){
			$this->db->where('p.product_id IN ('.$products.')');
		}
		$this->db->where('b.brand_status =','1');

		$arr = explode(",",$call_status);

		if($call_status!=''){
				if(!in_array('1', $arr) && !in_array('2', $arr)){
					$this->db->where('c.call_status IN('.$call_status.')');
				}else{
					if(in_array('1', $arr) && in_array("2", $arr)){
						$arr = array_diff($arr,array("2"));
						$arr = array_values($arr);
						$this->db->where('c.call_status IN('.$call_status.')');	
					}else{
						if(in_array('1', $arr)){
							$arr = array_diff($arr,array('1'));
							$arr = array_values($arr);
						if(count($arr)>0){
								$str = implode(",", $arr);
								$this->db->where("(c.call_status IN(".$str.") OR (c.call_status=1 AND call_reason_pending!='Part Pending'))");
							}else{
								$this->db->where("(c.call_status=1 AND call_reason_pending!='Part Pending')");
							}
								
						}else{
							$arr = array_diff($arr,array('2'));
							$arr = array_values($arr);
							if(count($arr)>0){
								$str = implode(",", $arr);
								$this->db->where("(c.call_status IN(".$str.") OR (c.call_status=1 AND call_reason_pending='Part Pending'))");
							}else{
								$this->db->where("(c.call_status=1 AND call_reason_pending='Part Pending')");
							}
						}
					}
				}
			}
		$access = array(1,2,3,5);

		if(!in_array($this->session->userdata('usergroup_id'),$access)){
			$this->db->where('c.sc_id ='.$this->session->userdata('sc_id'));
		}
		if($from_date){
			$this->db->where('c.call_dt >=',date("Y-m-d",date_to_timestamp($from_date)));
		}
		if($to_date){
			$this->db->where('c.call_dt <=',date("Y-m-d",date_to_timestamp($to_date)));
		}
		//filter for user assigned products
		if($this->session->userdata('usergroup_id')>1){
			$this->db->where("p.product_id IN (".$this->session->userdata('user_products').")");
		}

		$this->db->select('c.call_id,c.call_uid,c.call_dt,c.call_tm,cs.cust_first_name,cs.cust_last_name,ct.city_name,cs.cust_address,cs.cust_phone_office,cs.cust_phone_home,cs.cust_phone_mobile,b.brand_name,p.product_name,pm.model_number,en.engineer_name,c.call_dt AS call_aging,sc_name,c.call_service_desc,c.call_engineer_remark,c.call_detail_wrk_done,c.call_reason_pending,c.call_status,c.pending_dt,c.pending_tm,c.closure_dt,c.closure_tm,c.call_created_by,c.call_last_mod_by,call_serial_no,call_serial_no as call_old_serial_no,call_purchase_dt,call_type,call_dealer_name,c.call_last_mod_ts');
		$this->db->from($this->table_name.' as c',$this->mdl_productmodel->table_name.' as pm',$this->mdl_brands->table_name.' as b',$this->mdl_products->table_name.' as p',$this->mdl_customers->table_name.' as cs');
		$this->db->join($this->mdl_productmodel->table_name.' as pm','pm.model_id=c.model_id','left');
		$this->db->join($this->mdl_brands->table_name.' as b','b.brand_id=c.brand_id','left');
		$this->db->join($this->mdl_products->table_name.' as p','p.product_id=pm.product_id','left');
		$this->db->join($this->mdl_customers->table_name.' as cs','cs.cust_id=c.call_cust_id','left');
		$this->db->join($this->mdl_cities->table_name.' as ct','ct.city_id=cs.city_id','left');
		$this->db->join($this->mdl_engineers->table_name.' as en','en.engineer_id=c.engineer_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=c.sc_id','left');
		$this->db->order_by('call_status asc,call_id desc');
		if(isset($page['limit'])){
			$this->db->limit((int)$page['limit'],(int)$page['start']);
		}
		$result = $this->db->get();
		//echo $this->db->last_query();

		$where = array();
		if($cid){
			$this->db->like('c.call_uid',$cid);
		}
		if($cn){
			$this->db->where("(cs.cust_first_name LIKE '%$cn%' OR cs.cust_last_name LIKE '%$cn%')");
		}
		if($pn){
			$this->db->like('p.product_name',$pn);
		}
		if($sn){
			$this->db->like('c.call_serial_no',$sn);
		}
		if($ph){
			$this->db->where("(cs.cust_phone_mobile LIKE '%$ph%' OR cs.cust_phone_office LIKE '%$ph%' OR cs.cust_phone_home LIKE '%$ph%')");
		}
		if($eg!=''){
			$this->db->where('c.engineer_id =',$eg);
		}
		if($sc!=''){
			$this->db->where('c.sc_id',$sc);
		}
		$products = ($products=="")?"0":$products;
		if($products!='null'){
			$this->db->where('p.product_id IN ('.$products.')');
		}
		$this->db->where('b.brand_status =','1');

		$arr = explode(",",$call_status);

	if($call_status!=''){
	
				if(!in_array('1', $arr) && !in_array('2', $arr)){
					$this->db->where('c.call_status IN('.$call_status.')');
				}else{
					if(in_array('1', $arr) && in_array('2', $arr)){
						$arr = array_diff($arr,array('2'));
							$arr = array_values($arr);
						$this->db->where('c.call_status IN('.$call_status.')');	
					}else{
						if(in_array('1', $arr)){
							$arr = array_diff($arr,array('1'));
							$arr = array_values($arr);
						if(count($arr)>0){
								$str = implode(",", $arr);
								$this->db->where("(c.call_status IN(".$str.") OR (c.call_status=1 AND call_reason_pending!='Part Pending'))");
							}else{
								$this->db->where("(c.call_status=1 AND call_reason_pending!='Part Pending')");
							}
								
						}else{
							$arr = array_diff($arr,array('2'));
							$arr = array_values($arr);
							if(count($arr)>0){
								$str = implode(",", $arr);
								$this->db->where("(c.call_status IN(".$str.") OR (c.call_status=1 AND call_reason_pending='Part Pending'))");
							}else{
								$this->db->where("(c.call_status=1 AND call_reason_pending='Part Pending')");
							}
						}
					}
				}
			}
		$access = array(1,2,3,5);

		if(!in_array($this->session->userdata('usergroup_id'),$access)){
			$this->db->where('c.sc_id ='.$this->session->userdata('sc_id'));
		}
		if($from_date){
			$this->db->where('c.call_dt >=',date("Y-m-d",date_to_timestamp($from_date)));
		}
		if($to_date){
			$this->db->where('c.call_dt <=',date("Y-m-d",date_to_timestamp($to_date)));
		}
		//filter for user assigned products
		if($this->session->userdata('usergroup_id')>1){
			$this->db->where("p.product_id IN (".$this->session->userdata('user_products').")");
		}

		$this->db->select('c.call_id,b.brand_name,p.product_name,pm.model_number,c.call_serial_no,c.call_status,cs.cust_first_name,cs.cust_last_name');
		$this->db->from($this->table_name.' as c',$this->mdl_productmodel->table_name.' as pm',$this->mdl_brands->table_name.' as b',$this->mdl_products->table_name.' as p',$this->mdl_customers->table_name.' as cs');
		$this->db->join($this->mdl_productmodel->table_name.' as pm','pm.model_id=c.model_id','left');
		$this->db->join($this->mdl_brands->table_name.' as b','b.brand_id=c.brand_id','left');
		$this->db->join($this->mdl_products->table_name.' as p','p.product_id=pm.product_id','left');
		$this->db->join($this->mdl_customers->table_name.' as cs','cs.cust_id=c.call_cust_id','left');
		$this->db->join($this->mdl_cities->table_name.' as ct','ct.city_id=cs.city_id','left');
		$this->db->join($this->mdl_engineers->table_name.' as en','en.engineer_id=c.engineer_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=c.sc_id','left');
		$this->db->order_by('call_status asc,call_id desc');
		$result_total = $this->db->get();
		//echo $this->db->last_query();

		$list['list'] = $result->result();
		$list['result'] = $result;
		$list['total'] = $result_total->num_rows();

		return $list;

	}
}
?>
