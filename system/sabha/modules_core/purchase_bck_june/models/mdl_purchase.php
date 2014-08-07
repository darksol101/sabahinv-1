<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Purchase extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_purchase';
		$this->primary_key = 'sst_purchase.purchase_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' purchase_number ASC';
		$this->logged=$this->createlogtable($this->table_name);
	}


public function getmaxid(){
		$this->db->select('MAX(purchase_id) AS maxid');
		$this->db->from($this->table_name);
		$result=$this->db->get();
		$maxid= $result->row();
		return $maxid;
		}
	// 
	public function getPurchase($purchase_id){

		$this->db->select('purchase_id,vendor_id,sc_id,purchase_status,purchase_date,purchase_notes,invoice_number,lc_number,pp_number,pp_date');
		$this->db->from($this->table_name);
		$this->db->where('purchase_id =',$purchase_id);
		$result = $this->db->get();
		$purchase = $result->row();
		if($result->num_rows()==0){

			$purchase = new StdClass;
			$purchase->purchase_id=0;
			$purchase->purchase_number='';
			$purchase->invoice_number='';
			$purchase->vendor_id='';
			$purchase->sc_id='';
			$purchase->purchase_status='';
			$purchase->purchase_date= date("Y-m-d");
			$purchase->purchase_notes='';
			$purchase->lc_number='';
			$purchase->pp_number= '';
			$purchase->pp_date = date("Y-m-d");
			
			
		}
		return	$purchase;
	}
	
	public function getPurchaselist($page){
		$sc_id = $this->input->post('sc_id');
		$searchtxt = $this->input->post('searchtxt');
		$datefrom = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$vendor_name = $this->input->post('vendor_name');
		$purchase_status = $this->input->post('purchase_status');
		
		if($sc_id){
			$this->db->where('pur.sc_id =',$sc_id);
		}
		if($searchtxt){
			$this->db->like('purchase_id',$searchtxt);
		}
		if($datefrom)
		{			
			$this->db->where('purchase_date >=', date("Y-m-d",date_to_timestamp($datefrom)));	
		}
		if($vendor_name)
		{			
			$this->db->where('pur.vendor_id =', $vendor_name);	
		}
		
		if($todate)
		{			
			$this->db->where('purchase_date <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		if($purchase_status)
		{
			$this->db->where('pur.purchase_status =',$purchase_status);
		}
		if ($purchase_status != '' && $purchase_status == 0 ){
			$this->db->where('pur.purchase_status = 0');
			}
		
		
		if($this->session->userdata('usergroup_id')!=1){
			$this->db->where('pur.sc_id ='.$this->session->userdata('sc_id'));
		}
		$this->db->select('pur.purchase_id,pur.vendor_id,pur.purchase_date,pur.purchase_status,ven.vendor_name,sc.sc_name');
		$this->db->from($this->table_name.' as pur');
		$this->db->join($this->mdl_vendors->table_name.' AS ven','ven.vendor_id=pur.vendor_id','left');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=pur.sc_id','left');
		$this->db->order_by("pur.purchase_date DESC, pur.purchase_status");
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		
		$result = $this->db->get();
		/*echo ($this->db->last_query());
		die();*/
		$list['list'] = $result->result();
		
		
		if($sc_id){
			$this->db->where('pur.sc_id =',$sc_id);
		}
		if($searchtxt){
			$this->db->like('purchase_id',$searchtxt);
		}
		if($datefrom)
		{			
			$this->db->where('purchase_date >=', date("Y-m-d",date_to_timestamp($datefrom)));	
		}
		if($vendor_name)
		{			
			$this->db->where('pur.vendor_id =', $vendor_name);	
		}
		if($todate)
		{			
			$this->db->where('purchase_date <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		if($purchase_status)
		{
			$this->db->where('pur.purchase_status =',$purchase_status);
		}
		if ($purchase_status != '' && $purchase_status == 0 ){
			$this->db->where('pur.purchase_status = 0');
			}
		
		if($this->session->userdata('usergroup_id')!=1){
			$this->db->where('pur.sc_id ='.$this->session->userdata('sc_id'));
		}
		
		$this->db->select('pur.purchase_id');
		$this->db->from($this->table_name.' as pur');
		//$this->db->join($this->mdl_vendors->table_name.' AS ven','ven.vendor_id=pur.vendor_id','left');
		$this->db->order_by("purchase_id DESC");
		$result_total = $this->db->get();
		$list['list'] = $result->result();

		$list['total'] = $result_total->num_rows();
		return $list;
		}
	
	
}

?>