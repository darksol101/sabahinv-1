<?php defined('BASEPATH') or exit('Direct access script is not allowed');
class Mdl_Badparts_order extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table_name = 'sst_badparts_orders';
		$this->primary_key		=	'sst_badparts_orders.badparts_order_id';
		$this->select_fields	= '
		SQL_CALC_FOUND_ROWS *
		';
		$this->order_by			= 'badparts_order_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	
	
	function orderlist($page){
		$searchtxt = $this->input->post('searchtxt');
		$datefrom = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$status= $this->input->post('order_status');
		$sc_id = $this->input->post('sc_id');
		
		if($sc_id){
			$this->db->like("concat(badparts_from_sc_id,'-',badparts_to_sc_id)",$sc_id);
		}
		
		if ($status){
			$this->db->where('o.badparts_order_status =',$status);
			}
		if($searchtxt){
			$this->db->like('o.badparts_order_number',$searchtxt);
		}
		if($datefrom)
		{			
			$this->db->where('o.badparts_order_dt >=', date("Y-m-d",date_to_timestamp($datefrom)));	
		}
		
		if($todate)
		{			
			$this->db->where('o.badparts_order_dt <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		if ($this->session->userdata('usergroup_id')!=1 && $this->session->userdata('usergroup_id')!= 2  && $this->session->userdata('usergroup_id')!=6 ){		
			
			$this->db->like("concat(badparts_from_sc_id,'-',badparts_to_sc_id)",$this->session->userdata('sc_id'));
			//$this->db->where('o.requesting_sc_id = '.$this->session->userdata('sc_id'));
		}
		
		$this->db->select('o.badparts_order_id,o.badparts_order_number,o.badparts_order_dt,o.badparts_order_status,o.badparts_order_dt,sc.sc_name');
		$this->db->from($this->table_name.' AS o');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc' ,'sc.sc_id=o.badparts_from_sc_id','left');
		$this->db->order_by('o.badparts_order_status ASC,o.badparts_order_dt DESC,o.badparts_order_created_ts DESC');
		if(isset($page['limit'])){
			$this->db->limit((int)$page['limit'],(int)$page['start']);
		}
	
		$result = $this->db->get();
			//echo $this->db->last_query(); 
		if($sc_id){
			$this->db->like("concat(badparts_from_sc_id,'-',badparts_to_sc_id)",$sc_id);
		}
		
		if ($status){
			$this->db->where('o.badparts_order_status =',$status);
			}
		if($searchtxt){
			$this->db->like('o.badparts_order_number',$searchtxt);
		}
		if($datefrom)
		{			
			$this->db->where('o.badparts_order_dt >=', date("Y-m-d",date_to_timestamp($datefrom)));	
		}
		
		if($todate)
		{			
			$this->db->where('o.badparts_order_dt <=', date("Y-m-d",date_to_timestamp($todate)));	
		}
		if ($this->session->userdata('usergroup_id')!=1 && $this->session->userdata('usergroup_id')!= 2  && $this->session->userdata('usergroup_id')!=6 ){		
			
			$this->db->like("concat(badparts_from_sc_id,'-',badparts_to_sc_id)",$this->session->userdata('sc_id'));
			//$this->db->where('o.requesting_sc_id = '.$this->session->userdata('sc_id'));
		}
		
		$this->db->select('o.badparts_order_id,o.badparts_order_number,o.badparts_order_dt,o.badparts_order_status,o.badparts_order_dt,sc.sc_name');
		$this->db->from($this->table_name.' AS o');
		$this->db->join($this->mdl_servicecenters->table_name.' AS sc' ,'sc.sc_id=o.badparts_from_sc_id','left');
		$this->db->order_by('o.badparts_order_status ASC,o.badparts_order_dt DESC,o.badparts_order_created_ts DESC');
		$result_total = $this->db->get();
		
		$orders['list'] = $result->result();
		$orders['total'] = $result_total->num_rows();
		return $orders;
	}
	
function detailorder($badpart_orderid){
	$this->db->select('badparts_order_id,badparts_order_number,badparts_order_dt,badparts_order_status,o.badparts_order_created_ts,o.badparts_order_last_mod_ts,o.badparts_from_sc_id,o.badparts_to_sc_id,o.badparts_order_remarks');
		$this->db->from($this->table_name.' As o');
		$this->db->where('o.badparts_order_id =',$badpart_orderid);
		$result=$this->db->get();
		$order = $result->row();
		if($result->num_rows()==0){
			$order = new stdClass();
			$order->badparts_order_id = 0;
			$order->badparts_order_status = 0;
			$order->badparts_order_number = 0;
			$order->badparts_from_sc_id = 0;
			$order->badparts_to_sc_id = 0;
			$order->badparts_order_dt='';
			$order->badparts_order_remarks = '';
			$order->badparts_order_last_mod_by ='';
			$order->badparts_order_created_by = 0;
			$order->badparts_order_last_mod_ts = 0;
			$order->badparts_order_created_ts = '';
		}
		return $order;	
	
	}
	
	function saveOrder(){
		
		
		$badparts_order_id = $this->input->post('badparts_order_id');
		$badparts_order_part_id_arr = array();
		$badparts_part_number_arr = array();
		$badparts_part_quantity_arr = array();
		$badparts_order_part_id_arr = $this->input->post('badparts_order_part_id');
		$badparts_part_number_arr = $this->input->post('pnum');
		$badparts_part_quantity_arr = $this->input->post('pqty');
		
	
		$data = array(
					
					'badparts_from_sc_id'=>$this->input->post('badparts_from_sc_id'),
					'badparts_to_sc_id'=>$this->input->post('badparts_to_sc_id'),
					'badparts_order_remarks'=>$this->input->post('badparts_order_remarks'),
					'badparts_order_status'=>$this->input->post('status')
		);
		if ($badparts_order_id == 0){
			//echo '<pre>';
			//print_r($this->input->post());
			//print_r($data);  die();
			
		$data['badparts_order_created_by']= $this->session->userdata('user_id');
			
		$this->save($data);
		$badparts_order_id = $this->db->insert_id();
		
		}
		else{
			//print_r($data); die();
			//$data['badparts_order_dt'] =>'';
			$data['badparts_order_last_mod_by']=$this->session->userdata('user_id');
			$data['badparts_order_last_mod_ts']=date("Y-m-d H:i:s");
			
			parent::save($data,$badparts_order_id);
			}
			
			
			
			
			$i=0;
			
			
		if(is_array($badparts_order_part_id_arr) && (int)$badparts_order_id>0){
			foreach($badparts_order_part_id_arr as $badparts_order_part_id){
				$part_number = $badparts_part_number_arr[$i];
				$part_quantity = $badparts_part_quantity_arr[$i];
				
				$order_data = array(
									'badparts_order_id'=> $badparts_order_id,
									'part_number'=>$part_number,
									'part_quantity'=>$part_quantity
									);
				if((int)$badparts_order_part_id==0){
					$order_data["badparts_order_part_created_ts"]=date("Y-m-d H:i:s");
					$order_data["badparts_order_part_created_by"]=$this->session->userdata('user_id');
			
					$this->mdl_badparts_order_parts->save($order_data);
					$order_part_id = $this->db->insert_id();
				}else{
					$order_data["badparts_order_part_last_mod_ts"]= date("Y-m-d H:i:s");
					$order_data["badparts_order_part_last_mod_by"]= $this->session->userdata('user_id');
					$this->mdl_badparts_order_parts->save($order_data,$badparts_order_part_id);
				}
				
				
				
					
		if($this->input->post('status')==4){
					
					$this->load->model(array('badparts/mdlreturnparts'));
					$stockdata['part_number'] = $part_number;
					$stockdata['part_quantity'] = $part_quantity;
					$stockdata['sc_id'] = $this->input->post('badparts_to_sc_id');
					$this->mdl_returnparts->removePart($stockdata);
					
					
					$stockdata['sc_id'] = $this->input->post('badparts_from_sc_id');
					$this->mdl_returnparts->addpart($stockdata);
					
				}
		
				
			
				$i++;
			}
		}
		
	
		
			
			return  $badparts_order_id;
		}
	
}
?>