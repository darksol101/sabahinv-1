<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Bills extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_bills';
		$this->primary_key		=	'sst_bills.bill_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'bill_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	function getBillNumber(){
		
		$sql = "SELECT LPAD((SELECT COUNT(*)+1 FROM sst_bills),5,'0') AS bill_number";
		$result=$this->db->query($sql);
		$result = ($result->row());
		//print_r($result->sales_number);
		 return $result->bill_number;
		
	}
	
	function billList($page){

		$searchtxt = $this->input->post('searchtxt');

		$datefrom = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$status= $this->input->post('bill_status');
		$sc_id = $this->input->post('sc_id');
		$bill_type = $this->input->post('bill_type');
		
		if ($status){
			$this->db->where('b.bill_status =',$status);
			}
			
		if($searchtxt){
			//$this->db->like('concat(b.bill_number,s.sales_number)',$searchtxt);
			$this->db->like('b.bill_number',$searchtxt);
		}

		if($datefrom)
		{			
			$this->db->where('DATE(b.bill_created_ts) >=', date("Y-m-d",date_to_timestamp($datefrom)));	
		}
		
		if($todate)
		{			
			$this->db->where('DATE(b.bill_created_ts) <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		if($sc_id)
		{			
			$this->db->where('b.sc_id =', $sc_id);	
		}

		/*if($bill_type){
			$this->db->where('b.bill_type = ',$bill_type);
		}*/
		
		$this->db->select('sql_calc_found_rows
		 b.bill_number,b.bill_created_ts,b.bill_id,b.bill_status,b.sc_id,b.bill_sale_date,sc.sc_name,b.sales_id,b.bill_customer_name,b.bill_customer_vat,b.bill_rounded_grand_total_price,b.sc_code,bill_type',false);
		$this->db->from($this->table_name.' AS b');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc' ,'sc.sc_id=b.sc_id','left');
		$this->db->order_by('b.bill_created_ts DESC');
		if(isset($page['limit'])){
			$this->db->limit((int)$page['limit'],(int)$page['start']);
		}
	    $result = $this->db->get();
		//echo $this->db->last_query(); die();
		$query = $this->db->query('select found_rows() total;');
		$total_found = $query->row()->total;
		$bills['list'] = $result->result();
		$bills['total'] = $total_found; 
		return $bills;
	}
	
	function getBillBySales($sales_id){
		$this->db->select('bill_id,bill_number,bill_status,printed,grand_total,bill_type');
		$this->db->from($this->table_name.' AS b');
		$this->db->where('b.sales_id =',$sales_id);
		$result = $this->db->get();
			if($result->num_rows()==0){
				$bill = new StdClass;
				$bill->bill_id ='';
				$bill->bill_number ='';
				$bill->bill_status ='';
				$bill->printed='';
				$bill->grand_total='';
				$bill->bill_type = '';
				return $bill;
			}
			else{
				return $result->row();
			}
	}
	function getBillInfoBySalesID($sales_id){
		$this->db->select('b.bill_id,b.bill_number,b.bill_status,b.sc_id,b.printed,b.grand_total,b.bill_type,b.total_price,b.discount_amount,b.discount_value,b.discount_type,b.tax,b.tax_amount,b.grand_total,b.bill_rounded_off,b.bill_rounded_grand_total_price,b.bill_service_charge_price,sc.sc_name,sc.sc_phone1,sc.sc_address,b.bill_sale_date,b.call_uid,b.bill_customer_name,b.bill_customer_address,b.bill_customer_vat,b.call_serial_no,b.model_number,b.sc_code');
		$this->db->from($this->table_name.' AS b');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=b.sc_id','left');
		$this->db->where('b.sales_id =',$sales_id);
		$result = $this->db->get();
		return $result->row();
	}
	function getBillInfoByBillID($bill_id){
		$this->db->select('b.bill_id,b.sales_id,b.bill_number,b.bill_status,b.sc_id,b.printed,b.grand_total,b.bill_type,b.total_price,b.discount_amount,b.discount_value,b.discount_type,b.tax,b.tax_amount,b.grand_total,b.bill_rounded_off,b.bill_rounded_grand_total_price,b.bill_service_charge_price,sc.sc_name,sc.sc_phone1,sc.sc_address,b.bill_sale_date,b.call_uid,b.bill_customer_name,b.bill_customer_address,b.bill_customer_vat,b.call_serial_no,b.model_number,b.sc_code,b.bill_type');
		$this->db->from($this->table_name.' AS b');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=b.sc_id','left');
		$this->db->where('b.bill_id =',$bill_id);
		$result = $this->db->get();
		return $result->row();
	}
	function getBillInfoByID($bill_id){
		$this->db->select('b.bill_id,b.bill_number,b.bill_status,b.sc_id,b.printed,b.grand_total,b.bill_type,b.total_price,b.discount_amount,b.discount_value,b.discount_type,b.tax,b.tax_amount,b.grand_total,b.bill_rounded_off,b.bill_rounded_grand_total_price,b.bill_service_charge_price,sc.sc_name,sc.sc_phone1,sc.sc_address,b.bill_sale_date,b.call_uid,b.bill_customer_name,b.bill_customer_address,b.bill_customer_vat,b.call_serial_no,b.model_number,sc.sc_name,b.sc_code,b.bill_type');
		$this->db->from($this->table_name.' AS b');
		$this->db->from($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=b.sc_id');
		$this->db->where('b.bill_id =',$bill_id);
		$result = $this->db->get();
		return $result->row();
	}
	function getBillByID($bill_id){
		$this->db->select('b.bill_id,b.bill_number,b.bill_status,b.sc_id,b.printed,b.grand_total,b.bill_type,b.total_price,b.discount_amount,b.discount_value,b.discount_type,b.tax,b.tax_amount,b.grand_total,b.bill_rounded_off,b.bill_rounded_grand_total_price,b.bill_service_charge_price,sc.sc_name,sc.sc_phone1,sc.sc_address,b.bill_sale_date,b.call_uid,b.bill_customer_name,b.bill_customer_address,b.bill_customer_vat,b.call_serial_no,b.model_number,sc.sc_name,b.sales_id,b.sc_code,b.bill_type');
		$this->db->from($this->table_name.' AS b');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc','sc.sc_id=b.sc_id','left');
		$this->db->join($this->mdl_sales->table_name.' AS s','s.sales_id=b.sales_id','left');
		$this->db->where('b.bill_id =',$bill_id);
		$result = $this->db->get();
		return $result->row();
	}

	function getbills($sc_id = 0,$bill_type = 0){

		$searchtxt=$this->input->get('term');
		$this->db->select('s.sales_number,b.bill_id,b.bill_number AS label,b.bill_number,b.bill_number AS value,b.bill_type,b.sc_id,b.bill_customer_name,b.bill_customer_address,b.bill_customer_vat,b.call_serial_no,b.bill_rounded_grand_total_price,b.total_price,b.tax,b.tax_amount,b.discount_value,b.discount_type,b.discount_amount');
		$this->db->from($this->table_name.' AS b');
		$this->db->join($this->mdl_sales->table_name.' AS s', 's.sales_id=b.sales_id','inner');
		$this->db->join($this->mdl_sales_return->table_name.' AS sr', 'sr.bill_id=b.bill_id','left');		
		$this->db->where('b.sc_id',$sc_id);
		$this->db->where('COALESCE(sr.bill_id,0)',0);
		//$this->db->where('b.bill_type',$bill_type);
		$this->db->where_in('b.bill_type',array('1','2'));
		$this->db->order_by('b.bill_number ASC');
		$this->db->like('b.bill_number', $searchtxt);
		$result = $this->db->get();

		return $result->result();
	}	
}
?>