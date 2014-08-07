<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Sales extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_sales';
		$this->primary_key		=	'sst_sales.sales_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'sales_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
public function getmaxid(){
		$this->db->select('MAX(sales_id) AS maxid');
		$this->db->from($this->table_name);
		$result=$this->db->get();
		$maxid= $result->row();
		return $maxid;
		}
		
function getPartslist($part_number){
		$searchtxt=$this->input->get('term');
		$sc_id = $this->input->get('sc_id');
		$pdesc=$this->input->get('pdesc');
		if(!empty($pdesc)){
			$this->db->or_like('pp.part_desc', $pdesc);
		}
		
		$this->db->select('pp.unit AS unit,pp.part_id,pp.part_number AS id,pp.part_number AS label,pp.part_number AS value,pp.part_desc AS pdesc,pp.part_customer_price as price');
		$this->db->from($this->mdl_parts->table_name.' AS pp');
		//$this->db->join($this->mdl_model_parts->table_name.' AS ppm', 'ppm.part_number=pp.part_number');
		$this->db->join($this->mdl_parts_stocks->table_name.' AS pst','pst.part_id = pp.part_id','INNER');
		$this->db->where('pst.sc_id =',$sc_id);
		// $this->db->where('pst.stock_quantity > 0');
		$this->db->group_by('pp.part_number');
		//$this->db->limit(10,900);
		//$this->db->where('pp.part_number =',$part_number);
		$this->db->order_by('pp.part_number ASC');
		$this->db->like('pp.part_number', $searchtxt);
		//$this->db->or_like('pp.part_number', $pdesc);
		//$this->db->or_like('ppm.model_number',$searchtxt);
		//$this->db->order_by('pp.part_number');
		
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result();
	}

	function saleslist($page){
		$searchtxt = $this->input->post('searchtxt');
		$datefrom = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$status= $this->input->post('sales_status');
		$sc_id = $this->input->post('sc_id');
		//dump($this->input->post());

		if($sc_id){
			$this->db->where('s.sc_id = ',$sc_id);
		}
		
		if ($status){
			$this->db->where('s.sales_status =',$status);
		}
			
		if($searchtxt){
			$this->db->like('s.sales_number',$searchtxt);
		}
		if($datefrom)
		{			
			$this->db->where('s.sales_date >=', date("Y-m-d",date_to_timestamp($datefrom)));	
		}
		
		if($todate)
		{			
			$this->db->where('s.sales_date <=', date("Y-m-d",date_to_timestamp($todate)));	
		}

		if ($this->session->userdata('usergroup_id')!=1 && $this->session->userdata('usergroup_id')!= 2  && $this->session->userdata('usergroup_id')!=6 ){
            //$this->db->where('s.sc_id=' , $this->session->userdata('sc_id'));
            $user_sc_id = (int) $this->session->userdata('sc_id');
		    $this->db->where('s.sc_id =', $user_sc_id);
		}
		
		$this->db->select('COUNT(b.bill_id) AS cnt_bill,s.sales_id,sales_number,s.sales_status,s.sc_id,s.sales_date,sc.sc_name,s.call_id');
		$this->db->from($this->table_name.' AS s');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc' ,'sc.sc_id = s.sc_id','left',false);
		$this->db->join($this->mdl_bills->table_name.' AS b','b.sales_id=s.sales_id','left');
		$this->db->group_by('s.sales_id');
		$this->db->order_by('s.sales_status ASC,s.sales_date DESC');
		$this->db->having('cnt_bill',0);

		if(isset($page['limit'])){
			$this->db->limit((int)$page['limit'],(int)$page['start']);
		}
	
		$result = $this->db->get();
		
        if($sc_id){
			$this->db->where('s.sc_id = ',$sc_id);
		}
		
		if ($status){
			$this->db->where('s.sales_status =',$status);
		}
			
		if($searchtxt){
			$this->db->like('s.sales_number',$searchtxt);
		}
		if($datefrom){			
			$this->db->where('s.sales_date >=', date("Y-m-d",date_to_timestamp($datefrom)));
		}
		
		if($todate){			
			$this->db->where('s.sales_date <=', date("Y-m-d",date_to_timestamp($todate)));
		}
		if ($this->session->userdata('usergroup_id')!=1 && $this->session->userdata('usergroup_id')!= 2  && $this->session->userdata('usergroup_id')!=6 ){
            //$this->db->where('s.sc_id=' , $this->session->userdata('sc_id'));
            $user_sc_id = (int) $this->session->userdata('sc_id');
            $this->db->where('s.sc_id =', $user_sc_id);
		}

		$this->db->select('COUNT(b.bill_id) AS cnt_bill,s.sales_id,sales_number,s.sales_status,s.sc_id,s.sales_date,sc.sc_name');
		$this->db->from($this->table_name.' AS s');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc' ,'sc.sc_id=s.sc_id','left');
		$this->db->join($this->mdl_bills->table_name.' AS b','b.sales_id=s.sales_id','left');
		$this->db->group_by('s.sales_id');
		$this->db->order_by('s.sales_status ASC,s.sales_date DESC');
		$this->db->having('cnt_bill',0);
		$result_total = $this->db->get();
		//dump($this->db->last_query());
		$sales['list'] = $result->result();
		$sales['total'] = $result_total->num_rows();
		return $sales;
	}
	
	function salesdetails($sales_id){	
		$this->db->select('sales_id,bill_type,sales_number,sales_date,total_price,tax,sales_status,sc_id,sales_remarks,customer_name,customer_address,discount_type,discount_amount,call_id,taxed_amount,grand_total,cust_vat,ledger_id,discounted_price,ledger_id,sales_calc_tax_price,sales_rounded_total_price,call_serial_no,call_uid,model_number,warranty_sale');
		$this->db->from($this->table_name.' AS s');
		$this->db->where('s.sales_id =',$sales_id);
		$result = $this->db->get();
		$sales = $result->row();
		if($result->num_rows()==0){
			$sales = new stdClass();
			$sales->sales_id= 0;
			$sales->sales_number= 0;
			$sales->sales_status = 0;
			$sales->sc_id = 0;
			$sales->sales_date= date('Y-m-d');
			$sales->sales_remarks= '';
			$sales->customer_address = '';
			$sales->customer_name ='';
			$sales->discount_type ='';
			$sales->discount_amount =0;
			$sales->total_price = '';
			$sales->tax = '13';
			$sales->call_id ='';
			$sales->taxed_amount = '';
			$sales->grand_total ='';
			$sales->cust_vat = '';
			$sales->ledger_id = '';
			$sales->discounted_price = '';
			$sales->bill_type = 0;
			$sales->sales_calc_tax_price = 0;
			$sales->sales_rounded_total_price = 0;
			$sales->call_serial_no = '';
			$sales->model_number = '';
			$sales->call_uid = '';
			$sales->warranty_sale= 0;
		}
		return $sales;	
		}
		

	function saveSales($generate_bill  = false){

		$sales_id = $this->input->post('sales_id');
		$part_number = $this->input->post('pnum');
		$part_quantity = $this->input->post('pqty');
		$company = $this->input->post('comp');
		$p_rate = $this->input->post('prate');

        //discounted rate from salesmaker
        $d_rate = $this->input->post('drate');

		//$data['sales_number'] = $this->input->post('sales_number');
		$sc_id = $this->input->post('service_center');
		$data['sc_id'] = $this->input->post('service_center');
		$data['customer_name'] = $this->input->post('customer_name');
		$data['customer_address'] = $this->input->post('customer_address');
		$data['sales_date'] = date("Y-m-d",date_to_timestamp($this->input->post('sales_date')));
		$data['sales_remarks'] = $this->input->post('sales_remarks');
		$data['warranty_sale'] = $this->input->post('warranty_sale');
		$data['discount_type'] = $this->input->post('discount_type');
		$data['discount_amount']= $this->input->post('discount');
		$data['tax'] = '13';
	//	$data['total_price'] = $this->input->post('total_price');		
		$data['call_id'] = $this->input->post('call_id');
		$data['cust_vat'] = ($this->input->post('cust_vat'))?$this->input->post('cust_vat'):0;		
		$data['call_serial_no'] = $this->input->post('call_serial_no');
		$data['call_uid'] = $this->input->post('call_uid');
		$data['model_number'] = $this->input->post('model_number');
		//echo '<pre>';
		//print_r($this->input->post());
		//die();
			// function to calculate total amount
		$total_price = 0;
		if($part_number){
			$j = 0;
			foreach ($part_number as $part){
				//$company[$j];
				$total_price = $total_price + ($part_quantity[$j] * $d_rate[$j]);
				$j++;
				}
				
			
		}
		// calculation ends
		$data['total_price'] = $total_price;

		$sales_status ='1';
		$data['sales_status'] = $sales_status;
		if ($generate_bill == true){
			$sales_status = 2;
		}
			
			
	if(	$data['discount_type'] == 1){ 
		$discount_amount = (($data['discount_amount'] / 100)*	$data['total_price']) ; 
	}elseif ($data['discount_type'] == 2) { 
		$discount_amount =  $data['discount_amount']; }
	else
	{
		$discount_amount = 0 ; 
		$data['discount_amount']= 0 ;
	}
	$data['taxed_amount'] = (($data['tax']/100)*($data['total_price']-$discount_amount));
	$data['discounted_price'] = $discount_amount;
	$data['grand_total'] = $data['total_price']-$discount_amount + $data['taxed_amount'];



        if ($this->input->post('bill_type')){
			$bill_type = 2;
		}	
		else{
			
			if($data['grand_total'] > 5000){
				
				$bill_type = 2;
			}	
			else{
				$bill_type = 1;
		}
	}

	$data['bill_type'] = $bill_type;

	   if($part_number == ''){
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved_enter_part_number'));
			$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved_enter_part_number'));
			if ((int)$sales_id== 0 ){
				redirect($this->uri->uri_string());
			}else{ 
				redirect('sales/sale/'.$sales_id);
			}
		}
	
		if((int)$sales_id==0){
			    $data["sales_created_ts"]=date("Y-m-d H:i:s");
				$data["sales_created_by"]=$this->session->userdata('user_id');
				$maxid= $this->mdl_sales->getmaxid();
				$data['sales_id']= $maxid->maxid+1;
				$data['sales_number'] = $this->salesNumber($this->input->post('service_center'));
				if ($data['discount_type']==''){$data['discount_type']=0;}
				if ($data['discount_amount']==''){$data['discount_amount']=0;}
				if ($data['tax']==''){$data['tax']=0;}
				if ($data['call_id']==''){$data['call_id']=0;}
				/**modified by ghanshyam*/
				$data['sales_calc_price'] = $data['total_price'];
			    $data['sale_calc_discount_price'] = $data['discounted_price'];
			    $data['sales_calc_tax_price'] = $data['taxed_amount'];
				if($data['bill_type']==1){
						$data['sales_calc_price'] = $data['total_price']*(1+$data['tax']/100);				    		
			    		if($data['discount_type']==1){ 	
							$data['sale_calc_discount_price'] = $data['sales_calc_price']*($data['discount_amount']/100);			    			
						}	else{
							$data['sale_calc_discount_price'] =  $data['discount_amount']; 
						}	
						$data['sales_calc_tax_price'] = $data['total_price']*($data['tax']/100);	
						$data['sales_calc_total_price'] = $data['sales_calc_price'] - $data['sale_calc_discount_price'];    	
			    }else{
			    		$data['sales_calc_price'] = $data['total_price'];				    		
			    		if($data['discount_type']==1){ 	
							$data['sale_calc_discount_price'] = $data['total_price']*($data['discount_amount']/100);			    			
						}	else{
							$data['sale_calc_discount_price'] =  $data['discount_amount']; 
						}	
						$data['sales_calc_tax_price'] = ($data['total_price'] - $data['sale_calc_discount_price'])*($data['tax']/100);	
						$data['sales_calc_total_price'] = ($data['total_price']-$data['sale_calc_discount_price'] + $data['sales_calc_tax_price']);
			    }

			    $data['sales_rounded_total_price'] = round($data['sales_calc_total_price'],0,PHP_ROUND_HALF_UP);
			    $data['sales_rounded_off'] = ($data['sales_rounded_total_price'] - $data['sales_calc_total_price']);
			    
			   //$data['sales_rounded_total_price'] = $data['sales_calc_total_price'];
			   // $data['sales_rounded_off'] = $data['sales_rounded_total_price'] ;
			    /**end here*/
				
				if($this->save($data)){
						$sales_id = $data['sales_id'];
									//$this->db->insert_id();
					$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved').'.'.'   '.'Sales Number is'.'  '.$data['sales_number']);
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved').'   '.'Purchase Number is'.$data['sales_id']);
				}else{

					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved'));
				}
			}else{
			if ($data['discount_type']==''){$data['discount_type']=0;}
				if ($data['discount_amount']==''){$data['discount_amount']=0;}
				if ($data['tax']==''){$data['tax']=0;}
				if ($data['call_id']==''){$data['call_id']=0;}
				$data["sales_last_modified_ts"]=date("Y-m-d");
				$data["sales_last_modified_by"]=$this->session->userdata('user_id');

                /**modified by ghanshyam*/

                $data['sales_calc_price'] = $data['total_price'];

			    $data['sale_calc_discount_price'] = $data['discounted_price'];
			    $data['sales_calc_tax_price'] = $data['taxed_amount'];
				if($data['bill_type']==1){
						$data['sales_calc_price'] = $data['total_price']*(1+$data['tax']/100);				    		
			    		if($data['discount_type']==1){ 	
							$data['sale_calc_discount_price'] = $data['sales_calc_price']*($data['discount_amount']/100);			    			
						}	else{
							$data['sale_calc_discount_price'] =  $data['discount_amount']; 
						}	
						$data['sales_calc_tax_price'] = $data['total_price']*($data['tax']/100);	
						$data['sales_calc_total_price'] = $data['sales_calc_price'] - $data['sale_calc_discount_price'];    	
			    }else{
			    		$data['sales_calc_price'] = $data['total_price'];				    		
			    		if($data['discount_type']==1){ 	
							$data['sale_calc_discount_price'] = $data['total_price']*($data['discount_amount']/100);			    			
						}	else{
							$data['sale_calc_discount_price'] =  $data['discount_amount']; 
						}	
						$data['sales_calc_tax_price'] = ($data['total_price'] - $data['sale_calc_discount_price'])*($data['tax']/100);	
						$data['sales_calc_total_price'] = ($data['total_price']-$data['sale_calc_discount_price'] + $data['sales_calc_tax_price']);
			    }
			    $data['sales_rounded_total_price'] = round($data['sales_calc_total_price'],0,PHP_ROUND_HALF_UP);
			    $data['sales_rounded_off'] = ($data['sales_rounded_total_price'] - $data['sales_calc_total_price']);
				// $data['sales_rounded_total_price'] = $data['sales_calc_total_price'];
			  //  $data['sales_rounded_off'] = $data['sales_rounded_total_price'] ;
			    
				/**end here*/				
				if($this->save($data,$sales_id)){
					$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}else{
					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved'));
				}
			}
			$company = $this->input->post('comp');
			$part_number = $this->input->post('pnum');
			$part_quantity = $this->input->post('pqty');
			$part_rate = $this->input->post('prate');
			$call_ids = $this->input->post('call_ids');
			$part_id = $this->input->post('p_id');
            //added tej NEPO
            $dis_rate = $this->input->post('drate');
            $maker_id = $this->input->post('maker_id');
 
			$sales_details_arr = array();
			$stock_data = array();
			$sales_details_arr= $this->input->post('sales_details_id');
				
		if($sales_id){
				$i=0;
				if(is_array($sales_details_arr )){
					foreach($sales_details_arr   as $sales_details_id){
						if((int)$sales_details_id==0){
							$datadetails['company_id'] =  $this->mdl_company->getcompanyid($company[$i]);
							$datadetails['sales_id'] = $sales_id;
							$datadetails['part_number'] = $part_number[$i];
							$datadetails['part_id'] = $part_id[$i];
							$datadetails['part_quantity'] = $part_quantity[$i];
							$datadetails['price'] = $part_rate[$i];
							$datadetails['maker_id'] = $maker_id[$i];
                            $datadetails['price_with_discount'] = $dis_rate[$i];
							$datadetails['call_id'] = ($call_ids[$i])?$call_ids[$i]:0;
							if($data['bill_type']==1){ 
						    	$datadetails['sales_calc_price'] = sprintf("%.2f",(1+$data['tax']/100)*$datadetails['price_with_discount']);
						    }else{
						    	$datadetails['sales_calc_price'] = $datadetails['price_with_discount'];
						    }							
							$datadetails['sales_detail_status'] = '0';
							$datadetails['sales_details_created_by'] = $this->session->userdata('user_id');
							$this->mdl_sales_details->save($datadetails);
						}
						else{
							$datadetails['sales_detail_status'] = '0';
							$datadetails['part_number'] = $part_number[$i];
							$datadetails['part_id'] = $part_id[$i];
							$datadetails['part_quantity'] = $part_quantity[$i];
							$datadetails['company_id'] =  $this->mdl_company->getcompanyid($company[$i]);
							$datadetails['sales_id'] = $sales_id;
						    $datadetails['price'] = $part_rate[$i];
						    $datadetails['maker_id'] = $maker_id[$i];
                            $datadetails['price_with_discount'] = $dis_rate[$i];
						   	$datadetails['call_id'] = ($call_ids[$i])?$call_ids[$i]:0;
							if($data['bill_type']==1){ 
						    	$datadetails['sales_calc_price'] = sprintf("%.2f",(1+$data['tax']/100)*$datadetails['price_with_discount']);
						    }else{
						    	$datadetails['sales_calc_price'] = $datadetails['price_with_discount'];
						    }
							$datadetails["sales_details_last_modified_ts"]=date("Y-m-d");
							$datadetails["sales_details_last_modified_by"]=$this->session->userdata('user_id');
							$this->mdl_sales_details->save($datadetails,$sales_details_id);
						}
						$i++;
					}
					
					/*for direct bill generation*/
					$stock_avail = true;
					if($sales_status == 2 && $data['call_id'] < 1  ){	
						if($data['warranty_sale'] !=1){
							$j=0;
							foreach ($part_id as $part){
								$company_id = $this->mdl_company->getcompanyid($company[$j]);
								$check_stock = $this->mdl_parts_stocks->checkPartsStock($this->input->post('service_center'),$part,$company_id);									
								if($check_stock->stock_quantity<$part_quantity[$j]){									
										$stock_avail = false;
								}
								$j++;
							}
						}
					}
					
					
					if($stock_avail == false){
						$this->session->set_flashdata('custom_warning', $this->lang->line('stock_not_unavailable'));
						redirect('sales/sale/'.$sales_id);
						return false;
					}else{
						$i = 0;
						foreach($sales_details_arr   as $sales_details_id){
							$stockdata['part_id'] = $part_id[$i] ;
							$stockdata['stock_quantity_out'] = $part_quantity[$i];
			
							if( $sales_status == 2 && $data['call_id'] == 0){
								if($data['warranty_sale'] !=1){
									$this->load->model('stocks/mdl_stocks');
									$stockdata['company_id'] =  $this->mdl_company->getcompanyid($company[$i]);
									$stockdata['sc_id'] = $sc_id;
									$stockdata['stock_dt'] = date('Y-m-d');
									$stockdata['stock_tm'] = date('H:i:s');
									$stockdata['stock_created_by'] = $this->session->userdata('user_id');
									$stockdata['stock_created_ts'] = date('Y-m-d H:i:s');
									$this->mdl_stocks->stockoutUpdate($stockdata, "sales", $sales_details_id);
								}
							}
							$i++;
						}	
						if($sales_status == 2 && $stock_avail == true){
							$sql = "CALL sp_account_create_bills(".$this->session->userdata('sc_id').",".$this->session->userdata('user_id').",$sales_id)";		
							$result = $this->db->query($sql);
							$result->free_result();	
							$this->session->set_flashdata('success_save','Bill generated successfully');
						}
					}						

				}
					if ($data['warranty_sale'] == 1){
						$this->load->model(array('callcenter/mdl_callcenter'));
					$this->mdl_callcenter->saleCreated($call_ids);
				}
				redirect('sales/sale/'.$sales_id);
			
			}else{
				redirect('sales/sale/');
			}
			return $sales_id;
		}
		
		
function salesNumber($requestion_sc_id){
		
	$sql = " SELECT CONCAT(UCASE(sc.sc_name),DATE_FORMAT(CURDATE(),'%d%m%Y'),
 LPAD((SELECT COUNT(*)+1 FROM sst_sales  WHERE date(sales_created_ts) <= date(NOW()) AND date(sales_created_ts) > date(SUBDATE(NOW(),1)) ),3,'0'),'SL'
 ) AS sales_number
FROM sst_service_centers AS sc
WHERE  sc.sc_id = ? ";
$result=$this->db->query($sql,array($requestion_sc_id));

		$result = ($result->row());
		//print_r($result->sales_number);
		 return $result->sales_number;
	
		
		}
	function getSalesId($call_id){
		$this->db->select('s.sales_id');
		$this->db->from($this->table_name.' AS s');
		$this->db->where('s.call_id =',$call_id);
		$result = $this->db->get();
		$sales_id= $result->row();
		if($result->num_rows()==0){
			$sales_id = new stdClass();
			$sales_id->sales_id= 0;
		}
		return $sales_id;	
		}
		function getCallUidBySales($sales_number){
		$this->load->model(array('sales/mdl_sales','bills/mdl_bills','productmodel/mdl_productmodel','brands/mdl_brands'));
		$this->db->select('c.call_uid,c.call_serial_no,pm.model_number,b.brand_name');
		$this->db->from($this->table_name.' AS s');
		$this->db->join($this->mdl_callcenter->table_name.' AS c','c.call_id= s.call_id','INNER');
		$this->db->join($this->mdl_productmodel->table_name.' AS pm','pm.model_id= c.model_id','INNER');
		$this->db->join($this->mdl_brands->table_name.' AS b','b.brand_id= pm.brand_id','INNER');
		$this->db->where('s.sales_id =',$sales_number);
		$result = $this->db->get();
		if ($result->num_rows()>0){
		$result = $result->row();
		}else{
			$result = new stdClass;
			$result->call_uid = '';
			$result->call_serial_no = '';
			$result->model_number = '';
			$result->brand_name = '';
		}
		return $result;
	}
	
	
	function saveSalesmod(){
		$sales_id = $this->input->post('sales_id');
		$part_number = $this->input->post('pnum');
		$part_quantity = $this->input->post('pqty');
		$company = $this->input->post('comp');
        $p_rate = $this->input->post('prate');
		//$data['sales_number'] = $this->input->post('sales_number');
		$sc_id = $this->input->post('service_center');
		$data['sc_id'] = $this->input->post('service_center');
		$data['customer_name'] = $this->input->post('customer_name');
		$data['customer_address'] = $this->input->post('customer_address');
		$data['sales_date'] = date("Y-m-d",date_to_timestamp($this->input->post('sales_date')));
		$data['sales_remarks'] = $this->input->post('sales_remarks');
		
		$data['discount_type'] = $this->input->post('discount_type');
		$data['discount_amount']= $this->input->post('discount');
		$data['tax'] = '13';
		$data['total_price'] = $this->input->post('total_price');		
		$data['call_id'] = $this->input->post('call_id');
		$data['cust_vat'] = ($this->input->post('cust_vat'))?$this->input->post('cust_vat'):0;
		$data['ledger_id'] = $this->input->post('ledger_id');
		$data['call_serial_no'] = $this->input->post('call_serial_no');
		$data['call_uid'] = $this->input->post('call_uid');
		$data['model_number'] = $this->input->post('model_number');
		
		$sales_status ='1';
		if ($this->input->post('deliver_status'))
			{
				$sales_status = 2;
			}
			
			
	if(	$data['discount_type'] == 1)
	{ $discount_amount = (($data['discount_amount'] / 100)*	$data['total_price']) ; } 
	elseif ($data['discount_type'] == 2) 
	{ $discount_amount =  $data['discount_amount']; }
	else
	{$discount_amount = 0 ;}
	$data['taxed_amount'] = (($data['tax']/100)*($data['total_price']-$discount_amount));
	$data['discounted_price'] = $discount_amount;
	$data['grand_total'] = $data['total_price']-$discount_amount + $data['taxed_amount'];	
	$data['sales_status'] = $sales_status;

	if ($this->input->post('bill_type')){
			$bill_type = 2;
		}	
		else{
			
			if($data['grand_total'] > 5000){
				
				$bill_type = 2;
			}	
			else{
				$bill_type = 1;
			}
			
			
		}
		$data['bill_type'] = $bill_type;
		//echo '<pre>';
		//print_r($_POST);
		//die();
				if($part_number == '')
				{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved_enter_part_number'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved_enter_part_number'));
					
					
					if ((int)$sales_id== 0 ){
					redirect($this->uri->uri_string());
					}else{ redirect('sales/sale/'.$sales_id);
							 }
				}
		
				
		if((int)$sales_id==0){
			
				$data["sales_created_ts"]=date("Y-m-d H:i:s");
				$data["sales_created_by"]=$this->session->userdata('user_id');
				$maxid= $this->mdl_sales->getmaxid();
				$data['sales_id']= $maxid->maxid+1;
				$data['sales_number'] = $this->salesNumber($this->input->post('service_center'));
				if ($data['discount_type']==''){$data['discount_type']=0;}
				if ($data['discount_amount']==''){$data['discount_amount']=0;}
				if ($data['tax']==''){$data['tax']=0;}
				if ($data['call_id']==''){$data['call_id']=0;}
				/**modified by ghanshyam*/
				$data['sales_calc_price'] = $data['total_price'];
			    $data['sale_calc_discount_price'] = $data['discounted_price'];
			    $data['sales_calc_tax_price'] = $data['taxed_amount'];
				if($data['bill_type']==1){
						$data['sales_calc_price'] = $data['total_price']*(1+$data['tax']/100);				    		
			    		if($data['discount_type']==1){ 	
							$data['sale_calc_discount_price'] = $data['sales_calc_price']*($data['discount_amount']/100);			    			
						}	else{
							$data['sale_calc_discount_price'] =  $data['discount_amount']; 
						}	
						$data['sales_calc_tax_price'] = $data['total_price']*($data['tax']/100);	
						$data['sales_calc_total_price'] = $data['sales_calc_price'] - $data['sale_calc_discount_price'];    	
			    }else{
			    		$data['sales_calc_price'] = $data['total_price'];				    		
			    		if($data['discount_type']==1){ 	
							$data['sale_calc_discount_price'] = $data['total_price']*($data['discount_amount']/100);			    			
						}	else{
							$data['sale_calc_discount_price'] =  $data['discount_amount']; 
						}	
						$data['sales_calc_tax_price'] = ($data['total_price'] - $data['sale_calc_discount_price'])*($data['tax']/100);	
						$data['sales_calc_total_price'] = ($data['total_price']-$data['sale_calc_discount_price'] + $data['sales_calc_tax_price']);
			    }
			    $data['sales_rounded_total_price'] = round($data['sales_calc_total_price'],0,PHP_ROUND_HALF_UP);
			    $data['sales_rounded_off'] = ($data['sales_rounded_total_price'] - $data['sales_calc_total_price']);
				/**end here*/	
				
				if($this->save($data)){
						$sales_id = $data['sales_id'];
									//$this->db->insert_id();
			
				
					$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved').'.'.'   '.'Sales Number is'.'  '.$data['sales_number']);
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved').'   '.'Purchase Number is'.$data['sales_id']);
				}else{

					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved'));
				}
			}else{
			if ($data['discount_type']==''){$data['discount_type']=0;}
				if ($data['discount_amount']==''){$data['discount_amount']=0;}
				if ($data['tax']==''){$data['tax']=0;}
				if ($data['call_id']==''){$data['call_id']=0;}
				$data["sales_last_modified_ts"]=date("Y-m-d");
				$data["sales_last_modified_by"]=$this->session->userdata('user_id');
				/**modified by ghanshyam*/
				$data['sales_calc_price'] = $data['total_price'];
			    $data['sale_calc_discount_price'] = $data['discounted_price'];
			    $data['sales_calc_tax_price'] = $data['taxed_amount'];
				if($data['bill_type']==1){
						$data['sales_calc_price'] = $data['total_price']*(1+$data['tax']/100);				    		
			    		if($data['discount_type']==1){ 	
							$data['sale_calc_discount_price'] = $data['sales_calc_price']*($data['discount_amount']/100);			    			
						}	else{
							$data['sale_calc_discount_price'] =  $data['discount_amount']; 
						}	
						$data['sales_calc_tax_price'] = $data['total_price']*($data['tax']/100);	
						$data['sales_calc_total_price'] = $data['sales_calc_price'] - $data['sale_calc_discount_price'];    	
			    }else{
			    		$data['sales_calc_price'] = $data['total_price'];				    		
			    		if($data['discount_type']==1){ 	
							$data['sale_calc_discount_price'] = $data['total_price']*($data['discount_amount']/100);			    			
						}	else{
							$data['sale_calc_discount_price'] =  $data['discount_amount']; 
						}	
						$data['sales_calc_tax_price'] = ($data['total_price'] - $data['sale_calc_discount_price'])*($data['tax']/100);	
						$data['sales_calc_total_price'] = ($data['total_price']-$data['sale_calc_discount_price'] + $data['sales_calc_tax_price']);
			    }
			    $data['sales_rounded_total_price'] = round($data['sales_calc_total_price'],0,PHP_ROUND_HALF_UP);
			    $data['sales_rounded_off'] = ($data['sales_rounded_total_price'] - $data['sales_calc_total_price']);
				/**end here*/				
				if($this->save($data,$sales_id)){
					$this->session->set_flashdata('success_save', $this->lang->line('this_record_has_been_saved'));
					$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}else{
					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
					$this->session->set_flashdata('custom_warning', $this->lang->line('this_record_can_not_be_saved'));
				}
			}
			$company = $this->input->post('comp');
			$part_number = $this->input->post('pnum');
			$part_quantity = $this->input->post('pqty');
			$part_rate = $this->input->post('prate');
			
			$sales_details_arr = array();
			$stock_data = array();
			$sales_details_arr= $this->input->post('sales_details_id');
	
		
			
				
		if($sales_id){
				$i=0;
				if(is_array($sales_details_arr )){
					foreach($sales_details_arr   as $sales_details_id){
						$stockdata['part_number'] = $part_number[$i] ;
						$stockdata['stock_quantity_in'] = $part_quantity[$i];
						if((int)$sales_details_id==0){
							$datadetails['company_id'] =  $this->mdl_company->getcompanyid($company[$i]);
							$datadetails['sales_id'] = $sales_id;
							$datadetails['part_number'] = $part_number[$i];
							$datadetails['part_quantity'] = $part_quantity[$i];
							$datadetails['price'] = $part_rate[$i];
							if($data['bill_type']==1){ 
						    	$datadetails['sales_calc_price'] = sprintf("%.2f",(1+$data['tax']/100)*$datadetails['price']);
						    }else{
						    	$datadetails['sales_calc_price'] = $datadetails['price'];
						    }							
							$datadetails['sales_detail_status'] = '0';
							$datadetails['sales_details_created_by'] = $this->session->userdata('user_id');
							$this->mdl_sales_details->save($datadetails);
						}
						else{
							$datadetails['sales_detail_status'] = '0';
							$datadetails['part_number'] = $part_number[$i];
							$datadetails['part_quantity'] = $part_quantity[$i];
							$datadetails['company_id'] =  $this->mdl_company->getcompanyid($company[$i]);
							$datadetails['sales_id'] = $sales_id;
						    $datadetails['price'] = $part_rate[$i]; 
							if($data['bill_type']==1){ 
						    	$datadetails['sales_calc_price'] = sprintf("%.2f",(1+$data['tax']/100)*$datadetails['price']);
						    }else{
						    	$datadetails['sales_calc_price'] = $datadetails['price'];
						    }
							$datadetails["sales_details_last_modified_ts"]=date("Y-m-d");
							$datadetails["sales_details_last_modified_by"]=$this->session->userdata('user_id');
							$this->mdl_sales_details->save($datadetails,$sales_details_id);
							
						}
						$i++;
					}
				}
				redirect('sales/modsale/'.$sales_id);
			}else{
				redirect('sales/modsale/');
			}
			return $sales_id;
		}
		
	function getValues($sales_id){
		$this->db->select('total_price,total_price - discounted_price as tot,taxed_amount,grand_total');
		$this->db->from($this->table_name.' AS sa');
		$this->db->where('sa.sales_id =',$sales_id);
	    $result = $this->db->get();
	   // print_r($result->row());
	    return $result->row();
	}

	public function getMakerDetails($sales_id)
	{
		$this->db->select('sd.part_id, sd.maker_id,sm.sale_deduction_type,sm.sale_deduction_value,sm.sale_name');
		$this->db->from($this->table_name.' AS sa');
		$this->db->join($this->mdl_sales_details->table_name.' AS sd','sa.sales_id = sd.sales_id','INNER');
		$this->db->join($this->mdl_salesmaker->table_name.' AS sm','sd.maker_id = sm.maker_id','INNER');
		$this->db->where('sa.sales_id =',$sales_id);
		$result = $this->db->get();
		return  $result->result();
	}
}
?>