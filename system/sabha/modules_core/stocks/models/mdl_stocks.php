<?php defined('BASEPATH') or die ('Direct access script is not allowed');
class Mdl_Stocks extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_stocks';
		$this->primary_key	=	'sst_stocks.stock_id';
		$this->select_fields = '
		SQL_CALC_FOUND_ROWS *';

		$this->order_by = 'stock_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	public function stockin($data,$event,$eventid){
		$data['event']= $event;
		$data['event_id']=$eventid;

		if($this->save($data)){
			$this->load->model("stocks/mdl_parts_stocks");

			$partBal=$this->getPartQuantity($data['part_id'],$data['sc_id'],$data['company_id']);
			

			$this->mdl_parts_stocks->updatePartsBalance($data['sc_id'],$data['part_id'],$data['company_id'], $partBal);
			
		}
	}
	public function stockinUpdate($data, $event,$eventid){


		$params=array(
					  "select" => "stock_id",
					  "limit" => 1,
					  "where" => "event_id = ".$eventid." and company_id = ".$data['company_id']." and event = '".$event."'"
				 		);
		$result=$this->get($params);



		if(empty($result)){     
			$this->stockin($data,$event,$eventid);
		}else{
			($this->save($data, (int)$result[0]->stock_id));

			$this->load->model("stocks/mdl_parts_stocks");
			$partBal=$this->getPartQuantity($data['part_id'],$data['sc_id'],$data['company_id']);
			$this->mdl_parts_stocks->updatePartsBalance($data['sc_id'],$data['part_id'],$data['company_id'], $partBal);
		}
	}
	
	public function stockInDelete($data){
		$params=array(
					  "select" => "item_id",
					  "limit" => 1,
					  "where" => "event_id = ".$data['event_id']." and event ='".$data['event']."'"
				 		);
		$result=$this->get($params);
		if($this->delete($data)){

			$this->load->model("stocks/mdl_parts_stocks");
			$partBal=$this->getPartQuantity($data['part_id'],$data['sc_id'],$data);
			$this->mdl_parts_stocks->updatePartsBalance($data['sc_id'],$data['part_id'],$data['company_id'] ,$partBal);
		}		
	}
	function stockout($data,$event,$eventid)
	{
		$data['event']= $event;
		$data['event_id']=$eventid;

		if($this->save($data)){
			$stock_id = $this->db->insert_id();
			$this->load->model("stocks/mdl_parts_stocks");
			$partBal=$this->getPartQuantity($data['part_id'],$data['sc_id'],$data['company_id']);
			$this->mdl_parts_stocks->updatePartsBalance($data['sc_id'],$data['part_id'],$data['company_id'] ,$partBal);
			//for bad part quantity
			if($event=='stockout_used'){
				$this->updateBadparts($data,$stock_id);
			}
		}
	}
	public function stockoutUpdate($data, $event,$eventid){
		//print_r ($data);

		
		$params=array(
					  "select" => "stock_id",
					  "limit" => 1,
					  "where" => "event_id = ".$eventid." and company_id = ".$data['company_id']." and event ='".$event."'"
				 		);

		$result=$this->get($params);
	

		if(empty($result)){
		
			$this->stockout($data,$event,$eventid);
		}else{
			if($this->save($data, (int)$result[0]->stock_id)){ 

				$stock_id = (int)$result[0]->stock_id;

				$this->load->model("stocks/mdl_parts_stocks");
				
				$partBal=$this->getPartQuantity($data['part_id'],$data['sc_id'],$data['company_id']);
				
				$this->mdl_parts_stocks->updatePartsBalance($data['sc_id'],$data['part_id'],$data['company_id'], $partBal);
				
			}
			//for bad part quantity
			if($event=='stockout_used'){
				$this->updateBadparts($data,$stock_id);
			}
		}
	}
	
	public function getPartQuantity($part_id,$sc_id,$company_id){
		/*$params=array( 
					  'select'=>'(sum(stock_quantity_in)-sum(stock_quantity_out)) as total',
					  'where'=>'part_number = '.$part_number
					  );*/
		$this->db->select('(sum(stock_quantity_in)-sum(stock_quantity_out)) as total');
		$this->db->from($this->table_name);
		$this->db->where('part_id =',$part_id);
		$this->db->where('sc_id =',$sc_id);
		$this->db->where('company_id =',$company_id);
		$result = $this->db->get();
		$partBal = $result->row();
		
		return $partBal->total;
	}


	public function getStocklist(){


		$searchtxt = $this->input->post('searchtxt');
		$part_number = $this->input->post('part_number');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		$sc_id = $this->input->post('sc_id');
		$company_id= $this->input->post('company_id');
		
		//print_r ($company_id);
		if($from_date)
		{
			$this->db->where('s.stock_dt >=', date("Y-m-d",date_to_timestamp($from_date)));
			
		}
		if($to_date)
		{
			$this->db->where('s.stock_dt <=', date("Y-m-d",date_to_timestamp($to_date)));
		}
		
		$this->db->where('s.part_id =',$part_number);
		$this->db->where('s.sc_id =',$sc_id);
		$this->db->where('s.company_id =',$company_id);	
		$this->db->select('part.part_number,s.part_id,s.stock_quantity_in,s.stock_quantity_out,s.stock_dt,sc.sc_name,s.event_id,s.event,c.company_title');
		$this->db->from($this->table_name.' AS s');
		$this->db->join($this->mdl_parts->table_name.' as part','s.part_id=part.part_id','inner');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=s.sc_id','inner');
		$this->db->join($this->mdl_company->table_name.' as c','s.company_id = c.company_id','left');
		$this->db->order_by('s.stock_dt DESC, s.part_id ASC');
		$result = $this->db->get();
		//echo $this->db->last_query();
		
		$stocklist = $result->result();
		
		return $stocklist;
		
	}
	
	
	public function deleteCallPart($id){
		
		$event = 'stockout_used';
		$this->db->select('stock_id');
		$this->db->from($this->table_name);
		$this->db->where('event =', $event);
		$this->db->where('event_id =',$id);
		$result = $this->db->get();
		$result= $result->row();
		//print_r($result);
		$this->delete($result);
		}
	
	public function stockoutUpdatecall($data, $event, $eventid){
		
$data['event']= $event;
$data['event_id']=$eventid;

$params=array(
					  "select" => "stock_id",
					  "limit" => 1,
					  "where" => "event_id = ".$eventid." and company_id = ".$data['company_id']." and event ='".$event."'"
				 		);
		$result=$this->get($params);
		if(empty($result)){
		if($this->save($data)){
				$stock_id = $this->db->insert_id();				
				$this->load->model("stocks/mdl_parts_stocks");
				$this->mdl_parts_stocks->updatecallused($data);
				//if($event=='stockout_used'){
				//	$this->updateBadparts($data,$stock_id);
				//}
			}	
		}else{
			if($this->save($data, (int)$result[0]->stock_id)){
				$stock_id = $this->db->insert_id();
				$this->load->model("stocks/mdl_parts_stocks");
				//if($event=='stockout_used'){
					//$this->updateBadparts($data,$stock_id);
				//}
			}
		}
	}
	function updateBadparts($data){ 
		$this->load->model(array('badparts/mdl_badparts','badparts/mdl_badparts_engineer_details'));
		$this->db->select('part_number,sc_id,engineer_id,part_quantity,bad_parts_id');
		$this->db->where('part_number =',$data['part_id']);
		$this->db->where('sc_id =',$data['sc_id']);
		$this->db->where('engineer_id =',$data['engineer_id']);
		$query = $this->db->get($this->mdl_badparts->table_name);
		$result = $query->row();
		//print_r($result);die();
		if($query->num_rows==0){
			$bad_parts_data['part_number']=$data['part_id'];
			$bad_parts_data['sc_id']= $data['sc_id'];
			$bad_parts_data['engineer_id']=$data['engineer_id'];
			$bad_parts_data['part_quantity'] = $data['stock_quantity_out'];
			$bad_parts_data['bad_parts_created_by'] = $this->session->userdata('user_id');
			$bad_parts_data['bad_parts_created_ts'] = date('Y-m-d H:i:s');	
			$this->mdl_badparts->save($bad_parts_data);
		}else{
			$bad_parts_data['part_quantity'] = $result->part_quantity+$data['stock_quantity_out'];
			$bad_parts_data['bad_parts_last_mod_by'] = $this->session->userdata('user_id');
			$bad_parts_data['bad_parts_last_mod_ts'] = date('Y-m-d H:i:s');
			$this->mdl_badparts->save($bad_parts_data,$result->bad_parts_id);
		}
		    $bad_parts_data['part_number']=$data['part_id'];
			$bad_parts_data['sc_id']= $data['sc_id'];
			$bad_parts_data['engineer_id']=$data['engineer_id'];
			$bad_parts_data['quantity_in'] = $data['stock_quantity_out'];
			$bad_parts_data['badparts_engineer_details_created_by'] = $this->session->userdata('user_id');	
			$bad_parts_data['event']='Used on call';
			//$bad_parts_data['call_id']=$data['call_id'];
		$this->mdl_badparts_engineer_details->save($bad_parts_data);
	}
	
	public function stockOutDelete($data){
		$params=array(
					  "select" => "stock_id",
					  "limit" => 1,
					  "where" => "event_id = ".$eventid." and company_id = ".$data['company_id']." and event ='".$event."'"
				 		);
		$result=$this->get($params);
		if($this->delete($data)){
			$this->load->model("items/mdl_items");
			$itemBal=$this->getItemQuantity($result[0]->item_id);
			$this->mdl_items->updateItemBalance($result[0]->item_id, $itemBal);
		}		
	}
	
	
	
	function stockoutcall($data,$event,$eventid)
	{
		$data['event']= $event;
		$data['event_id']=$eventid;
		if($this->save($data)){
			$stock_id = $this->db->insert_id();
			
			//for bad part quantity
			if($event=='stockout_used'){
				$this->updateBadparts($data,$stock_id);
			}
		}
	}
	
	
	function gethighest(){
		$this->db->select('count(pd.stock_id) as num');
		$this->db->from($this->table_name.' AS pd');
		$result = $this->db->get();
		$data = $result->num_rows();
		return $data;
			
			
			}
			
			
	public function stockoutUpdate1($data, $event,$eventid){

		$data['event']= $event;
		$data['event_id']=$eventid;
		//echo '<pre>';
		////print_r ($data);
		$this->save($data);
				$this->load->model("stocks/mdl_parts_stocks");
				
				$partBal=$this->getPartQuantity($data['part_id'],$data['sc_id'],$data['company_id']);
				
				$this->mdl_parts_stocks->updatePartsBalance($data['sc_id'],$data['part_id'],$data['company_id'], $partBal);
				
			
	}
	public function stockinUpdate1($data, $event,$eventid){
		$data['event']= $event;
		$data['event_id']=$eventid;
	//	echo '<pre>';
		//print_r ($data);
			$this->save($data);
		
				$this->load->model("stocks/mdl_parts_stocks");
				
				$partBal=$this->getPartQuantity($data['part_id'],$data['sc_id'],$data['company_id']);
				
				$this->mdl_parts_stocks->updatePartsBalance($data['sc_id'],$data['part_id'],$data['company_id'], $partBal);
				
			
	}
	
	
}
?>