<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Sales_Return extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_sales_return';
		$this->primary_key		=	'sst_sales_return.sales_return_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'sales_return_id';
		$this->logged=$this->createlogtable($this->table_name);
	}	
	function getSalesReturnDetails($sales_return_id = 0){
		$this->db->select('sr.sales_return_id,sr.bill_id,sr.bill_type,sr.bill_number,sr.sales_return_status,sr.sc_id,sr.sales_return_date,sr.sales_return_number,sr.sales_return_remarks,sr.customer_name,sr.customer_vat,sr.customer_address,sr.sales_return_total_price,sr.sales_return_discount_type,sr.sales_return_discounted_price,sr.sales_return_discount_amount,sr.sales_return_tax,sr.sales_return_tax_price,sr.call_id,sr.call_uid,sr.model_number,sr.call_serial_no,sr.sales_return_service_charge_price,sr.sales_return_rounded_off,sr.sales_return_grand_total,sr.sales_return_rounded_grand_total_price,s.sc_name,
		CONCAT(sr.sc_code,(CASE WHEN(sr.bill_type=1) THEN "SI" ELSE "TI" END),"/",bill_number)
AS bill_number_full',false);
		$this->db->from($this->table_name.' AS sr');
		$this->db->join($this->mdl_servicecenters->table_name.' AS s', 's.sc_id=sr.sc_id','left');
		$this->db->where('sr.sales_return_id',$sales_return_id);
		$result = $this->db->get();
		
		if($result->num_rows()==0){
			$sales_return = new stdClass();
			$sales_return->sales_return_id= 0;
			$sales_return->bill_id = 0;
			$sales_return->sc_name= '';
			$sales_return->bill_number_full = '';
			$sales_return->bill_type = 0;
			$sales_return->bill_number = '';
			$sales_return->sales_return_status = 0;
			$sales_return->sc_id= 0;
			$sales_return->sales_return_date= date("Y-m-d");
			$sales_return->sales_return_number = '';
			$sales_return->sales_return_remarks ='';
			$sales_return->customer_name = '';
			$sales_return->customer_vat = '';
			$sales_return->customer_address = '';
			$sales_return->sales_return_total_price = '0.00';
			$sales_return->sales_return_discount_type = '';
			$sales_return->sales_return_discounted_price = '0.00';
			$sales_return->sales_return_discount_amount = '0.00';
			$sales_return->sales_return_tax = '0.00';
			$sales_return->sales_return_tax_price = '0.00';
			$sales_return->call_id = 0;
			$sales_return->call_uid = '';
			$sales_return->model_number = '';
			$sales_return->call_serial_no = '';
			$sales_return->sales_return_service_charge_price = '0.00';
			$sales_return->sales_return_rounded_off = '0.00';
			$sales_return->sales_return_grand_total = '0.00';
			$sales_return->sales_return_rounded_grand_total_price = '0.00';
		}else{
			$sales_return = $result->row();
		}
		return $sales_return;	
	}
	function getSalesReturnPrintDetails($sales_return_id){
		$this->db->select('sr.sales_return_id,sr.sales_return_number,sr.bill_id,sr.bill_type,sr.bill_number,sr.sales_return_status,sr.sc_id,sr.sc_code,,sr.sales_return_date,sr.sales_return_number,sr.sales_return_remarks,sr.customer_name,sr.customer_vat,sr.customer_address,sr.sales_return_total_price,sr.sales_return_discount_type,sr.sales_return_discounted_price,sr.sales_return_discount_amount,sr.sales_return_tax,sr.sales_return_tax_price,sr.call_id,sr.call_uid,sr.model_number,sr.call_serial_no,sr.sales_return_service_charge_price,sr.sales_return_rounded_off,sr.sales_return_grand_total,sr.sales_return_rounded_grand_total_price,s.sc_name,s.sc_address,s.sc_email,s.sc_fax');
		$this->db->from($this->table_name.' AS sr');
		$this->db->join($this->mdl_servicecenters->table_name.' AS s', 's.sc_id=sr.sc_id','left');
		$this->db->where('sr.sales_return_id',$sales_return_id);
		$result = $this->db->get();
		
		if($result->num_rows()==0){
			$sales_return = new stdClass();
			$sales_return->sales_return_id = 0;
			$sales_return->sales_return_number='';
			$sales_return->bill_id = 0;
			$sales_return->bill_type = 0;
			$sales_return->bill_number = '';
			$sales_return->sales_return_status = 0;
			$sales_return->sc_id= 0;
			$sales_return->sc_code= 0;
			$sales_return->sales_return_date= date("Y-m-d");
			$sales_return->sales_return_number = '';
			$sales_return->sales_return_remarks ='';
			$sales_return->customer_name = '';
			$sales_return->customer_vat = '';
			$sales_return->customer_address = '';
			$sales_return->sales_return_total_price =0.00;
			$sales_return->sales_return_discount_type = '';
			$sales_return->sales_return_discounted_price =0.00;
			$sales_return->sales_return_discount_amount = 0.00;
			$sales_return->sales_return_tax = 0.00;
			$sales_return->sales_return_tax_price = 0.00;
			$sales_return->call_id = 0;
			$sales_return->call_uid = '';
			$sales_return->model_number = '';
			$sales_return->call_serial_no = '';
			$sales_return->sales_return_service_charge_price = 0.00;
			$sales_return->sales_return_rounded_off = 0.00;
			$sales_return->sales_return_grand_total = 0.00;
			$sales_return->sales_return_rounded_grand_total_price = 0.00;
		}else{
			$sales_return = $result->row();
		}
		return $sales_return;	
	}
	function saveReturn($data,$sales_return_id){
		$sales_return_details_ids = $this->input->post('sales_return_details_id');
		$sales_return_part_number = $this->input->post('pnum');
		$sales_return_part_desc = $this->input->post('pdesc');
		$sales_return_part_stock_type = $this->input->post('comp');
		$sales_return_part_qty = $this->input->post('pqty');
		$sales_return_part_rate = $this->input->post('prate');
		$part_id = $this->input->post('part_id');
		$sales_return_part_price = $this->input->post('price');		
		$sales_return_part_return = $this->input->post('p_return_pqty');		
		if($sales_return_id){
			parent::save($data,$sales_return_id);
		}else{
			parent::save($data,$sales_return_id);
			$sales_return_id = $this->db->insert_id();
		}		
		/* sales return details*/
		$i=0;
		foreach ($sales_return_details_ids as $details_id){
			$details_data['part_return_quantity'] = $sales_return_part_return[$i];
			if($details_id==0){
				$details_data['sales_return_id'] = $sales_return_id;
				$details_data['part_number'] = $sales_return_part_number[$i];
				$details_data['part_id'] = $part_id[$i];
				$details_data['part_quantity'] = $sales_return_part_qty[$i];
				$details_data['part_rate'] = $sales_return_part_price[$i];
				$details_data['part_desc'] = $sales_return_part_desc[$i];
				$details_data['sales_details_created_by'] = $this->session->userdata('user_id');
				$details_data['sales_details_created_ts'] = date('Y-m-d H:i:s');
			}
			$details_data['sales_return_calc_price'] = $sales_return_part_rate[$i];
			$details_data['company_id'] = $sales_return_part_stock_type[$i];
			$this->mdl_sales_return_details->save($details_data,$details_id);
			$i++;
		}
		return $sales_return_id;
	}
	function getSalesReturnList($page){
		$searchtxt=$this->input->post('searchtxt');

		if(!empty($searchtxt)){
			$this->db->like('sr.sales_return_number', $searchtxt);
			$this->db->or_like('sr.bill_number', $searchtxt);
		}
		
		$this->db->select('sr.sales_return_id,sr.bill_number,sr.bill_type,sr.sc_code,sr.sales_return_date,sr.sales_return_status,sr.sales_return_number,sr.sales_return_rounded_grand_total_price,s.sc_name');
		$this->db->from($this->table_name.' as sr');
		$this->db->join($this->mdl_servicecenters->table_name.' AS s', 's.sc_id=sr.sc_id','left');
			
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		//echo $this->db->last_query();
		$salesreturns = $result->result();

		//count total
		if(!empty($searchtxt))
		{
			$this->db->like('sr.sales_return_number', $searchtxt);
			$this->db->or_like('sr.bill_number', $searchtxt);			
		}
		
		$this->db->select('COUNT(sr.sales_return_id) AS total');
		$this->db->from($this->table_name.' as sr');
		$this->db->join($this->mdl_servicecenters->table_name.' AS s', 's.sc_id=sr.sc_id','left');
		$result_total = $this->db->get();

		$list['list'] = $salesreturns;
		$list['total'] = $result_total->row()->total;
		return	$list;
	}
	function getSalesReturnViewDetails($sales_return_id){
		$this->db->select('sr.sales_return_id,sr.bill_id,sr.bill_type,sr.sales_return_status,sr.sc_id,sr.sales_return_date,sr.sales_return_number,sr.sales_return_remarks,sr.customer_name,sr.customer_vat,sr.customer_address,sr.sales_return_total_price,sr.sales_return_discount_type,sr.sales_return_discounted_price,sr.sales_return_discount_amount,sr.sales_return_tax,sr.sales_return_tax_price,sr.call_id,sr.call_uid,sr.model_number,sr.call_serial_no,sr.sales_return_service_charge_price,sr.sales_return_rounded_off,sr.sales_return_grand_total,sr.sales_return_rounded_grand_total_price,s.sc_name,s.sc_address,s.sc_phone1,s.sc_fax,
				CONCAT(sr.sc_code,(CASE WHEN(sr.bill_type=1) THEN "SI" ELSE "TI" END),"/",bill_number)
AS bill_number,sr.sc_code',false);
		$this->db->from($this->table_name.' AS sr');
		$this->db->join($this->mdl_servicecenters->table_name.' AS s', 's.sc_id=sr.sc_id','left');
		$this->db->where('sr.sales_return_id',$sales_return_id);
		$result = $this->db->get();
		
		if($result->num_rows()==0){
			$sales_return = new stdClass();
			$sales_return->sales_return_id = 0;
			$sales_return->bill_id = 0;
			$sales_return->bill_type = 0;
			$sales_return->bill_number = '';
			$sales_return->sales_return_status = 0;
			$sales_return->sc_id= 0;
			$sales_return->sales_return_date= date("Y-m-d");
			$sales_return->sales_return_number = '';
			$sales_return->sales_return_remarks ='';
			$sales_return->customer_name = '';
			$sales_return->customer_vat = '';
			$sales_return->customer_address = '';
			$sales_return->sales_return_total_price =0.00;
			$sales_return->sales_return_discount_type = '';
			$sales_return->sales_return_discounted_price =0.00;
			$sales_return->sales_return_discount_amount = 0.00;
			$sales_return->sales_return_tax = 0.00;
			$sales_return->sales_return_tax_price = 0.00;
			$sales_return->call_id = 0;
			$sales_return->call_uid = '';
			$sales_return->model_number = '';
			$sales_return->call_serial_no = '';
			$sales_return->sales_return_service_charge_price = 0.00;
			$sales_return->sales_return_rounded_off = 0.00;
			$sales_return->sales_return_grand_total = 0.00;
			$sales_return->sales_return_rounded_grand_total_price = 0.00;
		}else{
			$sales_return = $result->row();
		}
		return $sales_return;	
	}
}
?>