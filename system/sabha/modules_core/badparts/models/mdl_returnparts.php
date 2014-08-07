<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Returnparts extends MY_Model {
	public function __construct() {
            parent::__construct();
            $this->table_name = 'sst_return_parts';
            $this->primary_key = 'sst_return_parts.return_part_id';
            $this->select_fields = "
            SQL_CALC_FOUND_ROWS *";
            $this->order_by = ' part_number ASC';
            //$this->logged=$this->createlogtable($this->table_name);
	}
	function getreturnparts(){
            $params=array(
                        "select"=>"part_number as value,part_number as text",
                        "group_by"=>'part_number'
                        );
                        $engineers=$this->get($params);
            return $engineers;
	}
	
	function savereturnpart($data){
		
		//print_r($data); die();
		    $engineer_id = $this->input->post('engineer_id');
            $part_number = $this->input->post('part_number');
            $part_quantity = $this->input->post('part_quantity');
            $this->load->model(array('mdl_badparts'));
            $this->db->select('bp.part_quantity,bad_parts_id');
            $this->db->from($this->mdl_badparts->table_name.' AS bp');
            $this->db->where('bp.engineer_id =',$engineer_id);
            $this->db->where('bp.part_number =',$part_number);
            $result = $this->db->get();
			$resulttt = $result->row();
		
		
		if ($resulttt->part_quantity < $data['part_quantity']){
			
			echo 1;
			die();
			
			}
		else{
		$this->db->select('part_number,part_quantity,sc_id,return_part_id');
		$this->db->from($this->table_name);
		$this->db->where('part_number =',$data['part_number']);
		$this->db->where('sc_id =',$data['sc_id']);
		
		$result= $this->db->get();
		$resul= $result->row();
		
		if($result->num_rows() == 0){
			
			$badpartupdate['part_quantity'] =  $resulttt->part_quantity - $data['part_quantity'];
			$this->mdl_badparts->save($badpartupdate,$resulttt->bad_parts_id);
			
			$returnpart['part_number']=$data['part_number'];
			$returnpart['part_quantity']=$data['part_quantity'];
			$returnpart['sc_id']=$data['sc_id'];
			$this->save($returnpart);
			
			}
		
		else{
			$badpartupdate['part_quantity'] =  $resulttt->part_quantity - $data['part_quantity'];
			$this->mdl_badparts->save($badpartupdate,$resulttt->bad_parts_id);
			$returnpart['part_quantity']=$data['part_quantity']+$resul->part_quantity;
			$this->save($returnpart,$resul->return_part_id);
			}
		
		
		}
		}

	/*function save($db_array,$id=0){
            $engineer_id = $this->input->post('engineer_id');
            $part_number = $this->input->post('part_number');
            $part_quantity = $this->input->post('part_quantity');
            $this->load->model(array('mdl_badparts'));
            $this->db->select('sum(bp.part_quantity) AS bad_part_qty');
            $this->db->from($this->mdl_badparts->table_name.' AS bp');
            $this->db->where('bp.engineer_id =',$engineer_id);
            $this->db->where('bp.part_number =',$part_number);
            $result_b = $this->db->get();
            $row_b = $result_b->row();
            $total_b = $row_b->bad_part_qty;
            
            //get total returned parts
            $this->db->select('sum(rp.part_quantity) AS bad_return_qty');
            $this->db->from($this->table_name.' AS rp');
            $this->db->where('rp.engineer_id =',$engineer_id);
            $this->db->where('rp.part_number =',$part_number);
            $result_r = $this->db->get();
            $row_r = $result_r->row();
            $total_r = $row_r->bad_return_qty;
            $total = $total_b-$total_r;
            
            if($total>=$part_quantity){
                return parent::save($db_array);
            }else{
                return  false;				
            }
	}*/
	function getReturnPartsOptionsBySc($sc_id){
		$this->db->select('rp.part_number as text,rp.part_number as value');
		$this->db->from($this->table_name.' AS rp');
		$this->db->where('rp.sc_id =', (int)$sc_id);
		$this->db->group_by('rp.part_number');
		$result = $this->db->get();
		$bad_parts = $result->result();
		return $bad_parts;
	}
	function getReturnPartsBySc($sc_id){
		$searchtxt=$this->input->get('term');
		
		$this->db->select('rp.part_number AS id,rp.part_number AS level,rp.part_number AS value,pd.part_desc AS pdesc');
		$this->db->from($this->table_name.' AS rp');
		$this->db->join($this->mdl_parts->table_name.' AS pd','pd.part_number=rp.part_number');
		$this->db->where('rp.sc_id =', (int)$sc_id);
		$this->db->group_by('rp.part_number');
		$this->db->like('rp.part_number', $searchtxt);
		$result = $this->db->get();
		//echo $this->db->last_query();
		$bad_parts = $result->result();
		return $bad_parts;
   }
   
   
   function getStocksList($page){
		$this->load->model(array('parts/mdl_parts'));
		$sc_id = $this->input->post('sc_id');
		$searchtxt = $this->input->post('searchtxt');
		
		if($sc_id){
			$this->db->where('bp.sc_id =',$sc_id);
		}
		if($searchtxt){
			 $this->db->where("bp.part_number like '%".$searchtxt."%' or pd.part_desc like '%".$searchtxt."%' ");
			//$this->db->like('bp.part_number',$searchtxt);
		}
		
		$this->db->select('bp.part_number,bp.part_quantity,sc.sc_name,pd.part_desc,bp.sc_id');
		$this->db->from($this->table_name.' AS bp');
		$this->db->join($this->mdl_parts->table_name.' AS pd','pd.part_number=bp.part_number');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=bp.sc_id');
		
		$this->db->order_by('bp.part_number ASC');
		
		$this->db->limit((int)$page['limit'],(int)$page['start']);
		$result = $this->db->get();
		//echo $this->db->last_query();
		$stocklist['list'] = $result->result();
		
		if($sc_id){
			$this->db->where('bp.sc_id =',$sc_id);
		}
		if($searchtxt){
			 $this->db->where("bp.part_number like '%".$searchtxt."%' or pd.part_desc like '%".$searchtxt."%' ");
			//$this->db->like('bp.part_number',$searchtxt);
		}
		
		$this->db->select('bp.part_number,bp.part_quantity,sc.sc_name,pd.part_desc');
		$this->db->from($this->table_name.' AS bp');
		$this->db->join($this->mdl_parts->table_name.' AS pd','pd.part_number=bp.part_number');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=bp.sc_id');
		$this->db->order_by('bp.part_number ASC');
		$result_total = $this->db->get();
		$stocklist['total'] = $result_total->num_rows();
		//echo $this->db->last_query(); 
		return $stocklist;
	}
   
   
   
     function getStocksListdownload(){
		$this->load->model(array('parts/mdl_parts'));
		
		
		$sc_id = $this->session->userdata('sc_id_badstock');
		$searchtxt = $this->session->userdata('searchtxt_badstock');
		
		if($sc_id){
			$this->db->where('bp.sc_id =',$sc_id);
		}
		if($searchtxt){
			 $this->db->where("bp.part_number like '%".$searchtxt."%' or pd.part_desc like '%".$searchtxt."%' ");
		}
		
		$this->db->select('bp.return_part_id, sc.sc_name,bp.part_number,pd.part_desc,bp.part_quantity');
		$this->db->from($this->table_name.' AS bp');
		$this->db->join($this->mdl_parts->table_name.' AS pd','pd.part_number=bp.part_number');
		$this->db->join($this->mdl_servicecenters->table_name.' as sc','sc.sc_id=bp.sc_id');
		
		$this->db->order_by('bp.part_number ASC');
		$stocklist = $this->db->get();
		
		return $stocklist;
	}
	
	
	function goodtobadpart($data){
		
		$this->db->select('part_number,part_quantity,sc_id,return_part_id');
		$this->db->from($this->table_name);
		$this->db->where('part_number =',$data['part_number']);
		$this->db->where('sc_id =',$data['sc_id']);
		
		$result= $this->db->get();
		$resul= $result->row();
	
		if($result->num_rows() == 0){
			$returnpart['part_number']=$data['part_number'];
			$returnpart['part_quantity']=$data['quantity'];
			$returnpart['sc_id']=$data['sc_id'];
			$this->save($returnpart);
			}
		
		else{
			$returnpart['part_quantity']=$data['quantity']+$resul->part_quantity;
			$this->save($returnpart,$resul->return_part_id);
			}
		
		}
		
		
		
	function addPartTransit(){
		$part_number = $this->input->post('part_number');
		$quantity = $this->input->post('quantity');
		$sc_id = $this->input->post('sc_id');
		
		$this->db->select('part_quantity,return_part_id,parts_in_transit');
		$this->db->from($this->table_name.' AS p');
		$this->db->where('p.sc_id =',$sc_id);
		$this->db->where('p.part_number =',$part_number);
		$result = $this->db->get();
		$detail = $result->row();
		
		$data['part_quantity']= $detail->part_quantity - $quantity;
		$data['parts_in_transit'] = $detail->parts_in_transit + $quantity;
		$this->save($data,$detail->return_part_id);
		}
   
  
  	function removePartTransit(){
		$part_number = $this->input->post('part_number');
		$quantity = $this->input->post('quantity');
		$sc_id = $this->input->post('sc_id');
		$this->db->select('return_part_id,parts_in_transit');
		$this->db->from($this->table_name.' AS p');
		$this->db->where('p.sc_id =',$sc_id);
		$this->db->where('p.part_number =',$part_number);
		$result = $this->db->get();
		$detail = $result->row();
		$data['parts_in_transit'] = $detail->parts_in_transit - $quantity;
		$this->save($data,$detail->return_part_id);
		
		}
  
  
  function addPart(){
		$part_number = $this->input->post('part_number');
		$quantity = $this->input->post('quantity');
		$sc_id = $this->input->post('sc_id');
		
		$this->db->select('part_quantity,return_part_id');
		$this->db->from($this->table_name.' AS p');
		$this->db->where('p.sc_id =',$sc_id);
		$this->db->where('p.part_number =',$part_number);
		$result = $this->db->get();
		$detail = $result->row();
		if ($result->num_rows() < 1){
			$data['part_number'] = $part_number;
			$data['part_quantity'] = $quantity;
			$data['sc_id']=$sc_id;
			$data['return_part_created_by'] = $this->session->userdata('sc_id');
			$data['return_part_created_ts'] = date('Y-m-d H:i:s');
			
			}
		else{
		$data['part_quantity']= $detail->part_quantity + $quantity;
		
		$this->save($data,$detail->return_part_id);
			}
		}
   
  
  	function removePart(){
		$part_number = $this->input->post('part_number');
		$quantity = $this->input->post('quantity');
		$sc_id = $this->input->post('sc_id');
		$this->db->select('return_part_id,part_quantity');
		$this->db->from($this->table_name.' AS p');
		$this->db->where('p.sc_id =',$sc_id);
		$this->db->where('p.part_number =',$part_number);
		$result = $this->db->get();
		$detail = $result->row();
		
		$data['part_quantity'] = $detail->part_quantity - $quantity;
		$this->save($data,$detail->return_part_id);
		
		}
		
		
	function getBadPartsList($from_sc_id){
		
		$this->db->select('bp.part_number,bp.part_quantity,p.part_desc');
		$this->db->from($this->table_name.' AS bp');
		$this->db->join($this->mdl_parts->table_name.' AS p','p.part_number = bp.part_number');
		$this->db->where('bp.sc_id =',$from_sc_id);
		$this->db->where('bp.part_quantity > 0');
		$result = $this->db->get();
		//echo ($this->db->last_query());
		return $result->result();
		
		
		}		
		
		
  
  
}
?>