<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Call_happy extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_call_happy';
		$this->primary_key = 'sst_call_happy.happy_call_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' happy_call_id';
		$this->logged=$this->createlogtable($this->table_name);
	}
	function getHappyCallDetail($callid){
		$this->db->select ('ch.call_id, ch.question_id, ch.answer, chq.question ');
		$this->db->from ($this->table_name.' AS ch');
		$this->db->where('ch.call_id =',$callid);
		$this->db->join($this->mdl_call_happy_question->table_name.' AS chq','ch.question_id = chq.question_id','INNER');
		$result = $this->db->get();
		return $result->result();
		
		
		}
	function getAnsByQuestion($question,$callid){
		
		$this->db->select('answer');
		$this->db->from($this->table_name);
		$this->db->where('question_id =',$question);
		$this->db->where('call_id =',$callid);
		$result= $this->db->get();
	   // print_r($result);
		if($result->num_rows()> '')
		{return $result->row('answer');}
		else{return '';}
		
		}
	function getid($questionid,$callid){
			
		$this->db->select('happy_call_id');
		$this->db->from($this->table_name);
		$this->db->where('question_id =',$questionid);
	    $this->db->where('call_id =',$callid);
		$result =$this->db->get();
		if ($result->num_rows ==0)
		{return '';}
		else{return $result->row('happy_call_id');}
		}
	function getHappyCallList($page){
				
		$searchtxt = $this->input->post('text');
		$datefrom = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$sc_id=$this->input->post('sc_id');
		$product_id=$this->input->post('product_id');
		
		
		
		if(!empty($sc_id))
		{
			$this->db->where('hcd.sc_id IN('.$sc_id.')');
		}
			if($product_id!="null")
				{
	$var=$this->db->query('SELECT DISTINCT pm.model_id FROM sst_prod_models AS pm JOIN sst_calls AS hcd ON hcd.`model_id`=pm.model_id AND pm.product_id IN('.$product_id.')');
			$num_count=$var->num_rows();
			$result1=$var->result();
			$model_list='';
			for($i=0;$i<$num_count;$i++)
					{	
				$model_id=$result1[$i]->model_id;
				$model_list=$model_list.$model_id.',';
					}
			$model_call_id=(chop($model_list,','));
			//print_r($model_call_id);
			$this->db->where('hcd.model_id IN('.$model_call_id.')');
				}
		if($searchtxt)
				{
			$this->db->where("(hcd.call_uid LIKE '%$searchtxt%' OR happy.call_id LIKE '%$searchtxt%')");
				}
		if($datefrom)
				{			
			$this->db->where('hcd.call_last_mod_ts >=', date("Y-m-d H:i:s",date_to_timestamp($datefrom)));	
				}
		if($todate)
				{			
			$this->db->where('hcd.call_last_mod_ts <=', date("Y-m-d H:i:s",date_to_timestamp($todate)));	
				}	
				
		if(isset($page['limit']))		{
			$this->db->limit((int)$page['limit'],(int)$page['start']);
										}
			$this->db->select('distinct(happy.call_id),hcd.call_uid');		
			$this->db->from($this->table_name.' AS happy');
			$this->db->join($this->mdl_callcenter->table_name.' AS hcd','hcd.call_id=happy.call_id');
			$this->db->where('hcd.happy_status',1);
		$result= $this->db->get();
		$list['result'] = $result->result();
		//echo $this->db->last_query();
		
		//for number of total rows
		if(!empty($sc_id))
				{
			$this->db->where('hcd.sc_id IN('.$sc_id.')');
				}
			if($product_id!="null")
				{
	$var=$this->db->query('SELECT DISTINCT pm.model_id FROM sst_prod_models AS pm JOIN sst_calls AS hcd ON hcd.`model_id`=pm.model_id AND pm.product_id IN('.$product_id.')');
			$num_count=$var->num_rows();
			$result=$var->result();
			$model_list='';
			for($i=0;$i<$num_count;$i++)
				{	
				$model_id=$result[$i]->model_id;
				$model_list=$model_list.$model_id.',';
				}
			$model_call_id=(chop($model_list,','));
			$this->db->where('hcd.model_id IN('.$model_call_id.')');
				}
		if($searchtxt)
				{
			$this->db->where("(hcd.call_uid LIKE '%$searchtxt%' OR happy.call_id LIKE '%$searchtxt%')");
				}
		if($datefrom)
				{			
			$this->db->where('hcd.call_last_mod_ts >=', date("Y-m-d",date_to_timestamp($datefrom)));	
				}
		if($todate)
				{			
			$this->db->where('hcd.call_last_mod_ts <=', date("Y-m-d",date_to_timestamp($todate)));	
				}	
			$this->db->select('distinct(happy.call_id),hcd.call_uid');		
			$this->db->from($this->table_name.' AS happy');
			$this->db->join($this->mdl_callcenter->table_name.' AS hcd','hcd.call_id=happy.call_id');
			$this->db->where('hcd.happy_status',1);
			
		$result_total = $this->db->get();
		
		$count=$result_total->num_rows();
		
		$list['count'] = $count;
				
		return $list;
		
		}
			
			
	function getReportAnswer($call_id){
		
		$this->db->select('hcd.call_uid,hc.call_id,hc.question_id,hca.answer_desc');
		$this->db->from($this->table_name.' AS hc');
		$this->db->join($this->mdl_callcenter->table_name.' AS hcd','hcd.call_id=hc.call_id');
		$this->db->join($this->mdl_calls_happy_answer->table_name.' AS hca','hca.answer_id = hc.answer');
		$this->db->where('hc.call_id =',$call_id);
		$this->db->order_by('hc.question_id');
		$result = $this->db->get();
		return $result->result();
		
					}
		
	function getAnswerDesc($call_id, $i){
		
		$this->db->select('hc.call_id,hca.answer_desc,hc.question_id ');
		$this->db->from($this->table_name.' AS hc');
		$this->db->join($this->mdl_calls_happy_answer->table_name.' AS hca','hca.answer_id = hc.answer');
		$this->db->where('hc.call_id =',$call_id);
		$this->db->where('hc.question_id =',$i);
		
		$result = $this->db->get();
		return $result->result();
		
						}
		
	function getRemarks($call_id)
			{
			$this->db->select('answer');
			$this->db->from($this->table_name);
			$this->db->where("answer REGEXP '[a-zA-Z]'");
			$this->db->where('call_id =',$call_id);
			$remarks=$this->db->get();
			return $remarks->result();
			}
		
	function getHappyCallListCreateExcel()
		{
					
		$searchtxt = $this->input->post('text');
		$datefrom = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		$sc_id=$this->input->post('sc_id');
		$product_id=$this->input->post('product_id');
		
			if(!empty($sc_id))
				{
			$this->db->where('hcd.sc_id IN('.$sc_id.')');
				}
			if($product_id!="null")
				{
	$var=$this->db->query('SELECT DISTINCT pm.model_id FROM sst_prod_models AS pm JOIN sst_calls AS hcd ON hcd.`model_id`=pm.model_id AND pm.product_id IN('.$product_id.')');
			$num_count=$var->num_rows();
			$result=$var->result();
			$model_list='';
			for($i=0;$i<$num_count;$i++)
				{	
				$model_id=$result[$i]->model_id;
				$model_list=$model_list.$model_id.',';
				}
			$model_call_id=(chop($model_list,','));
			$this->db->where('hcd.model_id IN('.$model_call_id.')');
				}
		if($searchtxt)
				{
			$this->db->where("(hcd.call_uid LIKE '%$searchtxt%' OR happy.call_id LIKE '%$searchtxt%')");
				}
		if($datefrom)
				{			
			$this->db->where('hcd.call_last_mod_ts >=', date("Y-m-d",date_to_timestamp($datefrom)));	
				}
		if($todate)
				{			
			$this->db->where('hcd.call_last_mod_ts <=', date("Y-m-d",date_to_timestamp($todate)));	
				}	
			$this->db->select('distinct(happy.call_id),hcd.call_uid,hcd.call_last_mod_by AS modified_by,hcd.call_last_mod_ts AS modified_date');		
			$this->db->from($this->table_name.' AS happy');
			$this->db->join($this->mdl_callcenter->table_name.' AS hcd','hcd.call_id=happy.call_id');
			$this->db->where('hcd.happy_status',1);
		
		$result = $this->db->get();
		return $result->result();
						
		}
		
	
}

?>
