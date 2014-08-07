<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Callcenter extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_calls';
		$this->primary_key = 'sst_calls.call_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' call_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function getCallById($call_id){
		$this->db->select('call_id,call_uid,call_dt,call_tm,call_cust_id,call_nocall_tm,call_service_desc,call_type,sc_id,engineer_id,call_status,model_id,call_serial_no,call_purchase_dt,call_dealer_name,call_visit_dt,call_visit_tm_in,call_visit_tm_out,call_engineer_remark,call_reason_pending,call_detail_wrk_done,pending_dt,pending_tm,closure_dt,closure_tm,call_cust_pref_dt,call_cust_pref_tm');
		$this->db->from($this->table_name);
		$this->db->where(array('call_id'=>$call_id));
		$result = $this->db->get();
		return $result->row();
	}
	public function getusergroup(){
		$params=array(
					  "select"=>"groupcode as value, details as text",
					  "order_by"=>"groupcode",
					  "where"=>"locked = 0"
					  );
					  return $this->get($params);
	}
	public function getCalls($page){
		
		$this->load->model(array('faultsettings/mdl_defect','faultsettings/mdl_repair','faultsettings/mdl_symptom'));
		
		$cid=$this->input->get('cid');
		$cn=$this->input->get('cn');
		$pn=$this->input->get('pn');
		$sn=$this->input->get('sn');
		$ph=$this->input->get('ph');
		$eg=$this->input->get('eg');
		$sc=$this->input->get('sc');
		$reopened= $this->input->get('reopened');
		$verified= $this->input->get('verified');
		$this->session->set_userdata('reo',$reopened);
		$this->session->set_userdata('ver',$verified);
		if ($verified == 1){$verchk = 1;}else {$verchk = 0;}
		$call_status = $this->input->get('cs');
		$from_date = $this->input->get('from');
		$to_date = $this->input->get('to');
		$this->load->helper('mcb_date');

		$where = array();
		if($from_date){
			$this->db->where('c.call_dt >=',date("Y-m-d",date_to_timestamp($from_date)));
		}
		if($to_date){
			$this->db->where('c.call_dt <=',date("Y-m-d",date_to_timestamp($to_date)));
		}
		if($eg!=''){
			$this->db->where('c.engineer_id =',$eg);
		}
		if($sc!=''){
			$this->db->where('c.sc_id',$sc);
		}
		if($cid){
			$this->db->like('c.call_uid',$cid);
		}
		if($cn){
			//$this->db->or_like('cs.cust_first_name',$cn);
			//$this->db->or_like('cs.cust_last_name',$cn);
			$this->db->like("concat(cs.cust_first_name,cs.cust_last_name)",$cn);
		}
		if($pn){
			$this->db->like('p.product_name',$pn);
		}
		if($sn){
			$this->db->like('c.call_serial_no',$sn);
		}
		if($ph){
			//$this->db->or_like('cs.cust_phone_home',$ph);
			//$this->db->or_like('cs.cust_phone_office',$ph);
			//$this->db->or_like('cs.cust_phone_mobile',$ph);
			$this->db->like("concat(cs.cust_phone_mobile,cs.cust_phone_office ,cs.cust_phone_home)",$ph);
		}
		
		//$this->db->or_like('b.brand_name',$searchtxt);
		//$this->db->or_like('pm.model_number',$searchtxt);
		$this->db->where('b.brand_status =','1');

		$arr = explode("_",$call_status);
		$call_status = implode(",",$arr);

		if ($call_status == ''  && $cid == '' && $cn == '' && $pn ==''&& $sn ==''&& $ph== '' && $eg == '' && $sc == '' && $reopened != 1 && $verified !=1 ){
			
			$this->db->where('c.call_status = 0');
			}




		if($call_status!=''){
		
				if(!in_array('1', $arr) && !in_array('2', $arr)){
					$this->db->where('c.call_status IN('.$call_status.')  AND c.happy_status = ('.$verchk.')');
				}else{
					if(in_array('1', $arr) && in_array("2", $arr)){
						$arr = array_diff($arr,array("2"));
						$arr = array_values($arr);
					$this->db->where('c.call_status IN('.$call_status.')  AND c.happy_status = ('.$verchk.')');
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
		$access = array(1,2,3,5,6,8);

		if(!in_array($this->session->userdata('usergroup_id'),$access)){
			$this->db->where('c.sc_id ='.$this->session->userdata('sc_id'));
		}
		
		//filter for user assigned products
		if($this->session->userdata('usergroup_id')>1 && $this->session->userdata('usergroup_id') !=6 && $this->session->userdata('usergroup_id') !=8){
			$this->db->where("p.product_id IN (".$this->session->userdata('user_products').")");
		}
		if ($reopened == 1){
			
			$this->db->where('c.reopened IN('.$reopened.')');
			//$this->db->where('reopened =',$reopened);
			}
			if ($verified == 1){
			
			$this->db->where('c.happy_status IN('.$verified.')');
			//$this->db->where('reopened =',$reopened);
			}
		
$this->db->select('c.call_id,c.call_uid,c.call_dt,c.call_tm,cs.cust_first_name,cs.cust_last_name,ct.city_name,cs.cust_address,cs.cust_phone_office,cs.cust_phone_home,cs.cust_phone_mobile,b.brand_name,p.product_name,pm.model_number,en.engineer_name,c.call_dt AS call_aging,sc_name,sym.symptom_code,def.defect_code,rep.repair_code,c.call_service_desc,c.call_engineer_remark,c.call_detail_wrk_done,c.call_reason_pending,c.call_status,c.pending_dt,c.pending_tm,c.closure_dt,c.closure_tm,c.call_created_by,c.call_last_mod_by,call_serial_no,call_serial_no as call_old_serial_no,call_purchase_dt,call_type,call_dealer_name,c.call_last_mod_ts,c.call_at,c.call_from,c.call_service_type,reopened,c.happy_status');
		$this->db->from($this->table_name.' as c',$this->mdl_productmodel->table_name.' as pm',$this->mdl_brands->table_name.' as b',$this->mdl_products->table_name.' as p',$this->mdl_customers->table_name.' as cs');
		$this->db->join($this->mdl_productmodel->table_name.' as pm','pm.model_id=c.model_id','left');
		$this->db->join($this->mdl_brands->table_name.' as b','b.brand_id=c.brand_id','left');
		$this->db->join($this->mdl_products->table_name.' as p','p.product_id=pm.product_id','left');
		$this->db->join($this->mdl_customers->table_name.' as cs','cs.cust_id=c.call_cust_id','left');
		$this->db->join($this->mdl_cities->table_name.' as ct','ct.city_id=cs.city_id','left');
		$this->db->join($this->mdl_engineers->table_name.' as en','en.engineer_id=c.engineer_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=c.sc_id','left');
		$this->db->join($this->mdl_defect->table_name.' as def','def.defect_id = c.defect_id','left');
		$this->db->join($this->mdl_symptom->table_name.' as sym','sym.symptom_id = c.symptom_id','left');
		$this->db->join($this->mdl_repair->table_name.' as rep','rep.repair_id = c.repair_id','left');
		$this->db->order_by('call_status asc,call_id desc');
		if(isset($page['limit'])){
			$this->db->limit((int)$page['limit'],(int)$page['start']);
		}
		$result = $this->db->get();
		//echo $this->db->last_query();
$cid=$this->input->get('cid');
		$cn=$this->input->get('cn');
		$pn=$this->input->get('pn');
		$sn=$this->input->get('sn');
		$ph=$this->input->get('ph');
		$eg=$this->input->get('eg');
		$sc=$this->input->get('sc');
		$reopened= $this->input->get('reopened');
		$verified= $this->input->get('verified');
		
		$call_status = $this->input->get('cs');
		$from_date = $this->input->get('from');
		$to_date = $this->input->get('to');

		$where = array();
		if($from_date){
			$this->db->where('c.call_dt >=',date("Y-m-d",date_to_timestamp($from_date)));
		}
		if($to_date){
			$this->db->where('c.call_dt <=',date("Y-m-d",date_to_timestamp($to_date)));
		}
		if($eg!=''){
			$this->db->where('c.engineer_id =',$eg);
		}
		if($sc!=''){
			$this->db->where('c.sc_id',$sc);
		}
		if($cid){
			$this->db->like('c.call_uid',$cid);
		}
		if($cn){
			//$this->db->or_like('cs.cust_first_name',$cn);
			//$this->db->or_like('cs.cust_last_name',$cn);
			$this->db->like("concat(cs.cust_first_name,cs.cust_last_name)",$cn);
		}
		if($pn){
			$this->db->like('p.product_name',$pn);
		}
		if($sn){
			$this->db->like('c.call_serial_no',$sn);
		}
		if($ph){
			//$this->db->or_like('cs.cust_phone_home',$ph);
			//$this->db->or_like('cs.cust_phone_office',$ph);
			//$this->db->or_like('cs.cust_phone_mobile',$ph);
			$this->db->like("concat(cs.cust_phone_mobile,cs.cust_phone_office ,cs.cust_phone_home)",$ph);
		}
		
		//$this->db->or_like('b.brand_name',$searchtxt);
		//$this->db->or_like('pm.model_number',$searchtxt);
		$this->db->where('b.brand_status =','1');

		$arr = explode("_",$call_status);
		$call_status = implode(",",$arr);

		if ($call_status == ''  && $cid == '' && $cn == '' && $pn ==''&& $sn ==''&& $ph== '' && $eg == '' && $sc == '' && $reopened != 1 && $verified !=1 ){
			
			$this->db->where('c.call_status = 0');
			}




		if($call_status!=''){
		
				if(!in_array('1', $arr) && !in_array('2', $arr)){
					$this->db->where('c.call_status IN('.$call_status.')  AND c.happy_status = ('.$verchk.')');
				}else{
					if(in_array('1', $arr) && in_array("2", $arr)){
						$arr = array_diff($arr,array("2"));
						$arr = array_values($arr);
					$this->db->where('c.call_status IN('.$call_status.')  AND c.happy_status = ('.$verchk.')');
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
		$access = array(1,2,3,5,6,8);

		if(!in_array($this->session->userdata('usergroup_id'),$access)){
			$this->db->where('c.sc_id ='.$this->session->userdata('sc_id'));
		}
		
		//filter for user assigned products
		if($this->session->userdata('usergroup_id')>1 && $this->session->userdata('usergroup_id') !=6 && $this->session->userdata('usergroup_id') !=8){
			$this->db->where("p.product_id IN (".$this->session->userdata('user_products').")");
		}
		if ($reopened == 1){
			
			$this->db->where('c.reopened IN('.$reopened.')');
			//$this->db->where('reopened =',$reopened);
			}
			if ($verified == 1){
			
			$this->db->where('c.happy_status IN('.$verified.')');
			//$this->db->where('reopened =',$reopened);
			}
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

		$list['list'] = $result->result();
		$list['result'] = $result;
		$list['total'] = $result_total->num_rows();

		return $list;
	}

	public function getCallDetails($id){

		$this->db->select('sc.call_id,sc.call_uid,sc.call_dt,sc.call_tm,sc.call_cust_id,sc.call_cust_pref_dt,sc.call_cust_pref_tm,sc.call_nocall_tm,sc.call_service_desc,sc.call_type,sc_id,sc.engineer_id,sc.call_status,b.brand_id,sc.model_id,sc.call_serial_no,sc.call_purchase_dt,call_dealer_name,sc.call_prod_detail_id,sc.call_print_jobcard,sc.call_visit_dt,sc.call_visit_tm_in,sc.call_visit_tm_out,sc.call_engineer_remark,sc.call_reason_pending,sc.call_detail_wrk_done,sc.call_print_jobcard,pm.model_number,cp.cust_first_name,cp.cust_last_name,cp.cust_address,cp.cust_landmark,cp.cust_phone_home,cp.cust_phone_office,cp.cust_phone_mobile,cp.zone_id,cp.district_id,cp.city_id,pm.product_id,sc.pending_dt,sc.pending_tm,sc.closure_dt,sc.closure_tm,sc.repair_id,sc.symptom_id,sc.defect_id,sc.call_delivery_dt,sc.call_at,sc.call_from,sc.call_service_type,happy_status');
		$this->db->from($this->table_name.' as sc',$this->mdl_productmodel->table_name.' as pm',$this->mdl_customers->table_name.' as cp');
		$this->db->join($this->mdl_productmodel->table_name.' as pm','pm.model_id=sc.model_id','left');
		$this->db->join($this->mdl_products->table_name.' as p','p.product_id=pm.product_id','left');
		$this->db->join($this->mdl_brands->table_name.' as b','b.brand_id=p.brand_id','left');
		$this->db->join($this->mdl_customers->table_name.' as cp','cp.cust_id=sc.call_cust_id','left');
		$this->db->where(array('call_id'=>$id));
		$result = $this->db->get();
		//echo $this->db->last_query();
		if($result->num_rows()>0){
			return $result->row();
		}else{
			$details = '';
			$details->call_id='';
			$details->call_uid='';
			$details->call_dt =date('Y-m-d');
			$details->call_tm ='';
			$details->call_cust_id=0;
			$details->call_status=0;
			$details->brand_id=0;
			$details->model_id=0;
			$details->call_type=0;
			$details->cust_first_name='';
			$details->cust_last_name='';
			$details->cust_address='';
			$details->cust_landmark='';
			$details->cust_phone_home='';
			$details->cust_phone_office='';
			$details->cust_phone_mobile='';
			$details->zone_id='';
			$details->district_id='0';
			$details->city_id='';
			$details->product_id='';
			$details->sc_id=0;
			$details->model_number='';
			$details->engineer_id = 0;
			$details->call_serial_no='';
			$details->call_dealer_name='';
			$details->call_service_desc='';
			$details->call_print_jobcard='';
			$details->call_visit_tm_in ='';
			$details->call_visit_tm_out ='';
			$details->call_cust_pref_dt =date("Y-m-d");
			$details->call_nocall_tm = '';
			$details->call_purchase_dt = date("Y-m-d");
			$details->call_cust_pref_tm=date("H:s",strtotime(time()));
			$details->call_reason_pending='';
			$details->repair_id = '';
			$details->defect_id = '';
			$details->call_at = '';
			$details->call_from = '';
			$details->call_service_type = '';
				
			return $details;
		}
		$params=array(
					 "select"=>"call_id,sc.call_uid,call_status,call_dt,call_tm,call_cust_id,call_cust_pref_dt,call_cust_pref_tm,call_nocall_tm,call_service_desc,call_type,sc_id,engineer_id,call_status,model_id,brand_id,call_serial_no,call_purchase_dt,call_dealer_name,call_prod_detail_id,call_print_jobcard,",
					 "where"=>array("call_id"=>$id),
					 "limit"=>1
		);
		$callarr=$this->get($params);
		if(count($callarr)>0){
			$call=$callarr[0];
			return $call;
		}
		else{
			$call_details->call_id=0;
			$call_details->call_status=0;
			return $call_details;
		}
	}
	function getCallPreviewDetails($call_id)
	{
		$this->db->select('cs.cust_first_name,cs.cust_last_name,cs.cust_address,ct.city_name,ds.district_name,z.zone_name,cs.cust_landmark,cs.cust_phone_home,cs.cust_phone_office,c.call_cust_pref_dt,c.call_cust_pref_tm,p.product_name,c.call_serial_no,c.call_service_desc,c.call_uid,c.call_dt,c.call_tm,c.call_dealer_name,c.call_purchase_dt,cs.cust_phone_mobile,sc.sc_name,b.brand_name,pm.model_number,pm.model_warranty,c.call_visit_tm_in,c.engineer_id,c.call_engineer_remark,c.call_detail_wrk_done,c.symptom_id,c.defect_id,c.repair_id');
		$this->db->from($this->table_name.' as c',$this->mdl_cities->table_name.' as ct');
		$this->db->join($this->mdl_productmodel->table_name.' as pm','pm.model_id=c.model_id','left');
		$this->db->join($this->mdl_products->table_name.' as p','p.product_id=pm.product_id','left');
		$this->db->join($this->mdl_brands->table_name.' as b','b.brand_id=p.brand_id','left');
		$this->db->join($this->mdl_customers->table_name.' as cs','cs.cust_id=c.call_cust_id','left');
		$this->db->join($this->mdl_cities->table_name.' as ct','ct.city_id=cs.city_id','left');
		$this->db->join($this->mdl_districts->table_name.' as ds','ds.district_id=cs.district_id','left');
		$this->db->join($this->mdl_zones->table_name.' as z','z.zone_id=cs.zone_id','left');
		$this->db->join($this->mdl_engineers->table_name.' as en','en.engineer_id=c.engineer_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=c.sc_id','left');
		$this->db->where(array('call_id'=>$call_id));
		$result = $this->db->get();
		return $result->row();
	}
	function AutoIncrementId(){
		$query = $this->db->query("SHOW TABLE STATUS LIKE '".$this->table_name."'");
		$user = $query->result();
		return $user[0]->Auto_increment;
	}
	function generateCallId(){
		$query = $this->db->query("SHOW TABLE STATUS LIKE '".$this->table_name."'");
		$table = $query->result();
		$id = $this->session->userdata('zone_code').date('d').date('m').date('y').$table[0]->Auto_increment;
		//$id = $this->session->userdata('zone_code').date('d').date('m').date('y').$this->getLastId();

		return $id;
	}
	public function getcalList(){

		$from_date = $this->input->post('from');
		$to_date = $this->input->post('to');
		$params=array(
					  "select"=>"call_uid,call_id",
					  "order_by"=>"call_id"
					  );
					  if($from_date){
					  	$params['where']['call_dt >='] = date("Y-m-d",date_to_timestamp($from_date));
					  }
					  if($to_date){
					  	$params['where']['call_dt <='] = date("Y-m-d",date_to_timestamp($to_date));
					  }
					  $params['where']['call_status <'] =3;
					  //$params['where']['call_print_jobcard'] =0;
					  return $this->get($params);
	}
	function getCallJobCardDetailsByRange()
	{
		$from_date = $this->input->post('from');
		$call_id = $this->input->post('call_id');
		$arr = explode(",",$call_id);
		$call_id = implode(",",array_filter($arr, 'strlen'));

		$to_date = $this->input->get('to');
		if($from_date){
			$this->db->where('c.call_dt >=',date("Y-m-d",date_to_timestamp($from_date)));
		}
		if($to_date){
			$this->db->where('c.call_dt <=',date("Y-m-d",date_to_timestamp($to_date)));
		}
		if($call_id){
			$this->db->where("c.call_id IN ($call_id)");
		}
		$this->db->select('cs.cust_first_name,cs.cust_last_name,cs.cust_address,ct.city_name,ds.district_name,z.zone_name,cs.cust_landmark,cs.cust_phone_home,cs.cust_phone_office,c.call_cust_pref_dt,c.call_cust_pref_tm,p.product_name,c.call_serial_no,c.call_service_desc,c.call_uid,c.call_dt,c.call_tm,c.call_dealer_name,c.call_purchase_dt,cs.cust_phone_mobile,sc.sc_name,b.brand_name,pm.model_number,pm.model_warranty');
		$this->db->from($this->table_name.' as c',$this->mdl_cities->table_name.' as ct');
		$this->db->join($this->mdl_productmodel->table_name.' as pm','pm.model_id=c.model_id','left');
		$this->db->join($this->mdl_products->table_name.' as p','p.product_id=pm.product_id','left');
		$this->db->join($this->mdl_brands->table_name.' as b','b.brand_id=p.brand_id','left');
		$this->db->join($this->mdl_customers->table_name.' as cs','cs.cust_id=c.call_cust_id','left');
		$this->db->join($this->mdl_cities->table_name.' as ct','ct.city_id=cs.city_id','left');
		$this->db->join($this->mdl_districts->table_name.' as ds','ds.district_id=cs.district_id','left');
		$this->db->join($this->mdl_zones->table_name.' as z','z.zone_id=cs.zone_id','left');
		$this->db->join($this->mdl_engineers->table_name.' as en','en.engineer_id=c.engineer_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=c.sc_id','left');
		$result = $this->db->get();
		return $result->result();
	}
	function generateCallIdByZone($zone_id){
		if($zone_id){
			$this->load->model('zones/mdl_zones');
			$arr = $this->mdl_zones->getZonecode($zone_id);
			$zone_code = $arr->zone_code;
			$query = $this->db->query("SHOW TABLE STATUS LIKE '".$this->table_name."'");
			$table = $query->result();
			$id = $zone_code.date('d').date('m').date('y').$table[0]->Auto_increment;
			return $id;
		}else{
			return false;
		}
	}
	function getLastId()
	{
		$query = $this->db->query("SELECT max(substr((call_uid),LENGTH(call_uid)-1,LENGTH(call_uid))) as id from ".$this->table_name." WHERE call_uid like '".$this->session->userdata('zone_code').date('d').date('m').date('y')."%'");
		$tb = $query->row();
		return $tb->id+1;
	}
	function checkopencallbyserial_number($call_id,$model_id,$call_serial_number){
		$this->db->select('COUNT(call_id) AS cnt,call_uid,call_id,call_serial_no');
		$this->db->from($this->table_name);
		$this->db->where('call_serial_no =',$call_serial_number);
		$this->db->where('model_id =',$model_id);
		$this->db->where('call_status <',3);
		$this->db->where('call_id !=',$call_id);
		$result = $this->db->get();
		$check = $result->row();
		
		if($check->cnt==0){
			$this->db->select('COUNT(cs.call_id) AS cnt,cs.call_id,c.call_uid,c.call_serial_no');
			$this->db->from($this->mdl_product_serial_number->table_name.' AS cs');
			$this->db->join($this->table_name.' AS c','c.call_id=cs.call_id');
			$this->db->where('c.call_status <',3);
			$this->db->where('cs.call_serial_no =',$call_serial_number);
			$this->db->where('cs.call_id !=',$call_id);
			$result_log = $this->db->get();
			$check_serial_log = $result_log->row();
			$check->cnt = $check->cnt+$check_serial_log->cnt;
			$check->call_id = $check_serial_log->call_id;
			$check->call_serial_no = $check_serial_log->call_serial_no;
		}
		return $check;
	}
	
	function getUidbyid($id){
		$this->db->select('call_uid,call_dt,call_tm,closure_dt,closure_tm');
		$this->db->from($this->table_name);
		$this->db->where('call_id =',$id);
		$result= $this->db->get();
		
		//echo $this->db->last_query();
		return $result->row();
		
		}
		function getcalltabledata($callid){
			$this->db->select('*');
			$this->db->from($this->table_name);
			$this->db->where('call_id =',$callid);
			$result= $this->db->get();
			
			
			}



function getengineerclosedcall($engineer_id){
		if ($this->input->post('fromdate')){
			$this->db->where('c.closure_dt >=',$this->input->post('fromdate'));
			}
		if ($this->input->post('todate')){
			$this->db->where('c.closure_dt <=',$this->input->post('todate'));
			}
			$this->db->select('COUNT(c.call_uid) as cnt');
			$this->db->from($this->table_name.' AS c');
			$this->db->where('c.call_status =','3');
			$this->db->where('c.engineer_id =',$engineer_id);
			$result= $this->db->get();
			return $result->row();
	}
		
	
	function getengineercancelledcall($engineer_id){
		if ($this->input->post('fromdate')){
			$this->db->where('c.closure_dt >=',$this->input->post('fromdate'));
			}
		if ($this->input->post('todate')){
			$this->db->where('c.closure_dt <=',$this->input->post('todate'));
			}
			$this->db->select('COUNT(c.call_uid) as cnt');
			$this->db->from($this->table_name.' AS c');
			$this->db->where('c.call_status =','4');
			$this->db->where('c.engineer_id =',$engineer_id);
			$result= $this->db->get();
			return $result->row();
			
	}
	function getengineerpartpendingcall($engineer_id){
		if ($this->input->post('fromdate')){
			$this->db->where('c.pending_dt >=',$this->input->post('fromdate'));
			}
		if ($this->input->post('todate')){
			$this->db->where('c.pending_dt <=',$this->input->post('todate'));
			}
			$this->db->select('COUNT(c.call_uid) as cnt');
			$this->db->from($this->table_name.' AS c');
			$this->db->where('c.call_status =','1');
			$this->db->where('c.call_reason_pending =','Part Pending');
			$this->db->where('c.engineer_id =',$engineer_id);
			$result= $this->db->get();
			return $result->row();
			
	}
	function getengineerverifiedcall($engineer_id){
		if ($this->input->post('fromdate')){
			$this->db->where('c.closure_dt >=',$this->input->post('fromdate'));
			}
		if ($this->input->post('todate')){
			$this->db->where('c.closure_dt <=',$this->input->post('todate'));
			}
			$this->db->select('COUNT(c.call_uid) as cnt');
			$this->db->from($this->table_name.' AS c');
			$this->db->where('c.happy_status =','1');
			$this->db->where('c.engineer_id =',$engineer_id);
			$result= $this->db->get();
			return $result->row();
			
	}
		

function verifieddetail(){
		
		$id = $this->uri->segment(4);
		$this->db->select('COUNT(c.model_id) AS counter,c.model_id,pm.model_number,p.product_name,b.brand_name');
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','c.model_id = pm.model_id');
		$this->db->join($this->mdl_products->table_name.' AS p','pm.product_id = p.product_id');
		$this->db->join($this->mdl_brands->table_name.' AS b','pm.brand_id = b.brand_id');
		$this->db->where('c.happy_status = 1');
		$this->db->group_by('model_id');
		$this->db->where('c.engineer_id =',$id);
	 $result=$this->db->get();
	// echo $this->db->last_query();
	 return $result->result();
		}


function getCalledAt($callid){
	
	
	
	$this->db->select('c.call_at');
	$this->db->from($this->table_name.' AS c');
	$this->db->where('c.call_id =',$callid);
	$result = $this->db->get();
	
	return $result->row();
	
	
	
	}

 function getreport211($page){
		$sc_id  = $this->input->post('sc_id');
		$engineer_id = $this->input->post('engineer_id');
		$fromdate = $this->input->post('fromdate');
		$todate  = $this->input->post('todate');
		if($fromdate)
		{			
			$this->db->where('call_dt >=', date("Y-m-d",date_to_timestamp($fromdate)));	
		}
		
		if($todate)
		{			
			$this->db->where('call_dt <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		if($engineer_id)
		{			
			$this->db->where('c.engineer_id =',$engineer_id);	
		}
		if($sc_id)
		{			
			$this->db->where('c.sc_id =', $sc_id);	
		}
		$this->db->select("c.call_uid,sc.sc_name,e.engineer_name,c.closure_dt,c.closure_tm,pm.model_number,p.product_name,cp.cust_first_name,cp.cust_last_name,c.call_dt,c.call_tm");
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','c.sc_id = sc.sc_id');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = c.engineer_id');
		$this->db->join($this->mdl_customers->table_name.' AS cp','c.call_cust_id = cp.cust_id');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id = c.model_id');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id = pm.product_id');
		$this->db->where("TIMESTAMPDIFF(HOUR,CONCAT( c.call_dt, ' ', c.call_tm ),CONCAT( c.closure_dt, ' ', c.closure_tm) ) < ",24);
		$this->db->where('c.call_status =', 3);
		$this->db->order_by('sc.sc_name asc, e.engineer_name asc');
		
		
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result_t = $this->db->get();
		//echo $this->db->last_query();
        $result['list'] = $result_t->result();
		
		if($fromdate)
		{			
			$this->db->where('call_dt >=', date("Y-m-d",date_to_timestamp($fromdate)));	
		}
		
		if($todate)
		{			
			$this->db->where('call_dt <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		if($engineer_id)
		{			
			$this->db->where('c.engineer_id =',$engineer_id);	
		}
		if($sc_id)
		{			
			$this->db->where('c.sc_id =', $sc_id);	
		}
		$this->db->select("c.call_uid,sc.sc_name,e.engineer_name,c.closure_dt,c.closure_tm,pm.model_number,p.product_name,cp.cust_first_name,cp.cust_last_name,c.call_dt,c.call_tm");
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','c.sc_id = sc.sc_id');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = c.engineer_id');
		$this->db->join($this->mdl_customers->table_name.' AS cp','c.call_cust_id = cp.cust_id');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id = c.model_id');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id = pm.product_id');
		$this->db->where("TIMESTAMPDIFF(HOUR,CONCAT( c.call_dt, ' ', c.call_tm ),CONCAT( c.closure_dt, ' ', c.closure_tm) ) < ",24);
		$this->db->where('c.call_status =', 3);
		$this->db->order_by('sc.sc_name asc, e.engineer_name asc');
		
		$result_total = $this->db->get();
       // $result['list'] = $result_t->result();
		
		
		
       $result['total'] = $result_total->num_rows();
	   
	   
        return $result;
    } 

	
	  function downloadreport211(){
	    $sc_id  =  $this->session->userdata('sc_id_dwnses');
		$engineer_id = $this->session->userdata('engineer_id_dwnses');
		$fromdate = $this->session->userdata('fromdate_dwnses');
		$todate  = $this->session->userdata('todate_dwnses');
		if($fromdate)
		{			
			$this->db->where('call_dt >=', date("Y-m-d",date_to_timestamp($fromdate)));	
		}
		
		if($todate)
		{			
			$this->db->where('call_dt <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		if($engineer_id)
		{			
			$this->db->where('c.engineer_id =',$engineer_id);	
		}
		if($sc_id) 
		{			
			$this->db->where('c.sc_id =', $sc_id);	
		}
		$this->db->select("c.call_id,c.call_uid,concat( cp.cust_first_name, ' ', cp.cust_last_name ) as cust_name,p.product_name,pm.model_number,sc.sc_name,e.engineer_name,concat(c.call_dt , ' ' , c.call_tm ) as call_dt,concat(c.closure_dt , ' ' , c.closure_tm )",false);
		$this->db->from($this->table_name.' AS c');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','c.sc_id = sc.sc_id');
		$this->db->join($this->mdl_engineers->table_name.' AS e','e.engineer_id = c.engineer_id');
		$this->db->join($this->mdl_customers->table_name.' AS cp','c.call_cust_id = cp.cust_id');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id = c.model_id');
		$this->db->join($this->mdl_products->table_name.' AS p','p.product_id = pm.product_id');
		$this->db->where("TIMESTAMPDIFF(HOUR,CONCAT( c.call_dt, ' ', c.call_tm ),CONCAT( c.closure_dt, ' ', c.closure_tm) ) < ",24);
		$this->db->where('c.call_status =', 3);
		$this->db->order_by('sc.sc_name asc, e.engineer_name asc');
		$result = $this->db->get();
		//echo $this->db->last_query();
      
	    return $result;
    } 



		
	
}

?>