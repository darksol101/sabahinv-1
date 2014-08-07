<?php
/* 
 *@property Ledger_model $ledger_model
 */
class Ledger_model extends MY_Model {

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'ledgers';
		$this->primary_key = 'ledgers.id';
	}

	function get_all_ledgers()
	{
		$options = array();
		$options[0] = "(Please Select)";
		$this->db->from('ledgers')->where('sc_id',$this->session->userdata('sc_id'))->order_by('name', 'asc');
		$ledger_q = $this->db->get();
		foreach ($ledger_q->result() as $row)
		{
			$options[$row->id] = $row->name;
		}
		return $options;
	}

	function get_all_ledgers_bankcash()
	{
		$options = array();
		$options[0] = "(Please Select)";
		$this->db->from('ledgers')->where('type', 1)->where('sc_id',$this->session->userdata('sc_id'))->order_by('name', 'asc');
		$ledger_q = $this->db->get();
		foreach ($ledger_q->result() as $row)
		{
			$options[$row->id] = $row->name;
		}
		return $options;
	}

	function get_all_ledgers_nobankcash()
	{
		$options = array();
		$options[0] = "(Please Select)";
		$this->db->from('ledgers')->where('type !=', 1)->where('sc_id',$this->session->userdata('sc_id'))->order_by('name', 'asc');
		$ledger_q = $this->db->get();
		foreach ($ledger_q->result() as $row)
		{
			$options[$row->id] = $row->name;
		}
		return $options;
	}

	function get_all_ledgers_reconciliation()
	{
		$options = array();
		$options[0] = "(Please Select)";
		$this->db->from('ledgers')->where('reconciliation', 1)->where('sc_id',$this->session->userdata('sc_id'))->order_by('name', 'asc');
		$ledger_q = $this->db->get();
		foreach ($ledger_q->result() as $row)
		{
			$options[$row->id] = $row->name;
		}
		return $options;
	}

	function get_name($ledger_id)
	{
		$this->db->from('ledgers')->where('id', $ledger_id)->limit(1);
		$ledger_q = $this->db->get();
		if ($ledger = $ledger_q->row())
			return $ledger->name;
		else
			return "(Error)";
	}

	function get_entry_name($entry_id, $entry_type_id)
	{
		/* Selecting whether to show debit side Ledger or credit side Ledger */
		$current_entry_type = entry_type_info($entry_type_id);
		$ledger_type = 'C';

		if ($current_entry_type['bank_cash_ledger_restriction'] == 3)
			$ledger_type = 'D';

		$this->db->select('ledgers.name as name,reference_id');
		$this->db->from('entry_items')
		->join('ledgers', 'entry_items.ledger_id = ledgers.id')
		->join('entries','entries.id=entry_items.entry_id')
		->where('entry_items.entry_id', $entry_id)->where('entry_items.dc', $ledger_type);
		$ledger_q = $this->db->get();
		if ( ! $ledger = $ledger_q->row())
		{
			return "(Invalid)";
		} else {
			$ledger_multiple = ($ledger_q->num_rows() > 1) ? TRUE : FALSE;
			$html = '';
			if ($ledger_multiple){
				$html .= anchor(($ledger->reference_id>0)?('bills/view/' . $ledger->reference_id):('account/entry/view/' . $current_entry_type['label'] . "/" . $entry_id), "(" . $ledger->name . ")", array('title' => 'View ' . $current_entry_type['name'] . ' Entry', 'class' => 'anchor-link-a'));
			}else{
				$html .= anchor(($ledger->reference_id>0)?('bills/view/' . $ledger->reference_id):('account/entry/view/' . $current_entry_type['label'] . "/" . $entry_id), $ledger->name, array('title' => 'View ' . $current_entry_type['name'] . ' Entry', 'class' => 'anchor-link-a'));
			}
			return $html;
		}
		return;
	}

	function get_opp_ledger_name($entry_id, $entry_type_label, $ledger_type, $output_type)
	{
		$output = '';
		if ($ledger_type == 'D')
			$opp_ledger_type = 'C';
		else
			$opp_ledger_type = 'D';
		$this->db->from('entry_items')->join('entries','entries.id=entry_items.entry_id')->where('entry_id', $entry_id)->where('dc', $opp_ledger_type);
		$opp_entry_name_q = $this->db->get();
		if ($opp_entry_name_d = $opp_entry_name_q->row())
		{
			$opp_ledger_name = $this->get_name($opp_entry_name_d->ledger_id);
			if ($opp_entry_name_q->num_rows() > 1)
			{
				if ($output_type == 'html')
					$output = anchor(($opp_entry_name_d->reference_id>0)?('bills/view/'.$opp_entry_name_d->reference_id):('account/entry/view/' . $entry_type_label . '/' . $entry_id), "(" . $opp_ledger_name . ")", array('title' => 'View ' . ' Entry', 'class' => 'anchor-link-a'));
				else
					$output = "(" . $opp_ledger_name . ")";
			} else {
				if ($output_type == 'html')
					$output = anchor(($opp_entry_name_d->reference_id>0)?('bills/view/'.$opp_entry_name_d->reference_id):('account/entry/view/' . $entry_type_label . '/' . $entry_id), $opp_ledger_name, array('title' => 'View ' . ' Entry', 'class' => 'anchor-link-a'));
				else
					$output = $opp_ledger_name;
			}
		}
		return $output;
	}

	function get_ledger_balance($ledger_id)
	{
		list ($op_bal, $op_bal_type) = $this->get_op_balance($ledger_id);

		$dr_total = $this->get_dr_total($ledger_id);
		$cr_total = $this->get_cr_total($ledger_id);

		$total = float_ops($dr_total, $cr_total, '-');
		
		if ($op_bal_type == "D")
			$total = float_ops($total, $op_bal, '+');
		else
			$total = float_ops($total, $op_bal, '-');

		return $total;
	}

	function get_op_balance($ledger_id)
	{
		$this->db->from('ledgers')->where('id', $ledger_id)->limit(1);
		$op_bal_q = $this->db->get();
		if ($op_bal = $op_bal_q->row())
			return array($op_bal->op_balance, $op_bal->op_balance_dc);
		else
			return array(0, "D");
	}

	function get_diff_op_balance()
	{
		/* Calculating difference in Opening Balance */
		$total_op = 0;
		$this->db->from('ledgers')->order_by('id', 'asc');
		$ledgers_q = $this->db->get();
		foreach ($ledgers_q->result() as $row)
		{
			list ($opbalance, $optype) = $this->get_op_balance($row->id);
			if ($optype == "D")
			{
				$total_op = float_ops($total_op, $opbalance, '+');
			} else {
				$total_op = float_ops($total_op, $opbalance, '-');
			}
		}
		return $total_op;
	}

	/* Return debit total as positive value */
	function get_dr_total($ledger_id)
	{
		$this->db->select_sum('amount', 'drtotal')->from('entry_items')->join('entries', 'entries.id = entry_items.entry_id')->where('entry_items.ledger_id', $ledger_id)->where('entry_items.dc', 'D');
		$dr_total_q = $this->db->get();
		if ($dr_total = $dr_total_q->row())
			return $dr_total->drtotal;
		else
			return 0;
	}

	/* Return credit total as positive value */
	function get_cr_total($ledger_id)
	{
		$this->db->select_sum('amount', 'crtotal')->from('entry_items')->join('entries', 'entries.id = entry_items.entry_id')->where('entry_items.ledger_id', $ledger_id)->where('entry_items.dc', 'C');
		$cr_total_q = $this->db->get();
		if ($cr_total = $cr_total_q->row())
			return $cr_total->crtotal;
		else
			return 0;
	}

	/* Delete reconciliation entries for a Ledger account */
	function delete_reconciliation($ledger_id)
	{
		$update_data = array(
			'reconciliation_date' => NULL,
		);
		$this->db->where('ledger_id', $ledger_id)->update('entry_items', $update_data);
		return;
	}
	function getLedgerOptions(){
		$this->db->select('id AS value,name AS text');
		$this->db->from('ledgers')->where('sc_id',$this->session->userdata('sc_id'))->order_by('name', 'asc');
		$ledger_q = $this->db->get();
		$options = $ledger_q->result();
		//echo $this->db->last_query();
		
		return $options;
	}
	function get_group_op_balance($group_id)
	{
		$sql = "SELECT 
				CASE WHEN(temp.d_op_bal<temp.c_op_bal)
				THEN temp.c_op_bal-temp.d_op_bal
				ELSE temp.d_op_bal-temp.c_op_bal
				END AS op_balance
				,
				CASE WHEN(temp.d_op_bal<temp.c_op_bal)
				THEN 'C'
				ELSE 'D'
				END AS op_balance_dc
				FROM (
					SELECT  
					SUM(CASE WHEN(l.op_balance_dc='D')
					THEN COALESCE(l.op_balance,0)
					ELSE 0
					END ) AS d_op_bal,
				
					SUM(CASE WHEN(l.op_balance_dc='C')
					THEN COALESCE(l.op_balance,0)
					ELSE 0
					END ) AS  c_op_bal
					FROM ledgers AS l
					LEFT JOIN groups AS g ON g.id=l.group_id
					WHERE (g.id = '$group_id' OR g.parent_id='$group_id')
				) AS temp";
		$op_bal_q = $this->db->query( $sql );
		if ($op_bal = $op_bal_q->row()){
			return array($op_bal->op_balance, $op_bal->op_balance_dc);
		}
		else{
			return array(0, "D");
		}
	}
	function get_group_balance($group_id)
	{
		list ($op_bal, $op_bal_type) = $this->get_group_op_balance($group_id);

		$dr_total = $this->get_group_dr_total($group_id);
		$cr_total = $this->get_group_cr_total($group_id);
		$total = float_ops($dr_total, $cr_total, '-');
		
		if ($op_bal_type == "D")
			$total = float_ops($total, $op_bal, '+');
		else
			$total = float_ops($total, $op_bal, '-');

		return $total;
	}
/* Return debit total as positive value */
	function get_group_dr_total($group_id)
	{
		$sql = "SELECT SUM(amount) AS drtotal 
				FROM (entry_items) 
				WHERE ledger_id IN(
				SELECT l.id 
				FROM ledgers AS l
				LEFT JOIN groups AS g on g.id=l.group_id
				WHERE (l.group_id=$group_id or g.parent_id=$group_id )
				GROUP BY l.id
				) AND entry_items.dc = 'D'";
		$dr_total_q =  $this->db->query($sql);
		if ($dr_total = $dr_total_q->row())
			return $dr_total->drtotal;
		else
			return 0;
	}

	/* Return credit total as positive value */
	function get_group_cr_total($group_id)
	{
		$sql = "SELECT SUM(amount) AS crtotal 
				FROM (entry_items) 
				WHERE ledger_id IN(
				SELECT l.id 
				FROM ledgers AS l
				LEFT JOIN groups AS g on g.id=l.group_id
				WHERE (l.group_id=$group_id or g.parent_id=$group_id )
				GROUP BY l.id
				) AND entry_items.dc = 'C'";
		
		$cr_total_q = $this->db->query($sql);
		
		if ($cr_total = $cr_total_q->row())
			return $cr_total->crtotal;
		else
			return 0;
	}
function getPartyLedger($sc_id){
		$this->db->select('ledgers.id AS value,ledgers.name AS text');
		$this->db->from('ledgers');
		$this->db->where('ledgers.sc_id =',$sc_id);
		$this->db->join('groups','groups.id= ledgers.group_id','INNER');
		$this->db->where('(group_id = 41 OR groups.id = 41)');
		//$this->db->
		$this->db->order_by('ledgers.name', 'asc');
		$ledger_q = $this->db->get();
		$options = $ledger_q->result();
		//echo $this->db->last_query();
		
		return $options;
		
	}
	function getCreditorsLedger($sc_id){
		$this->db->select('ledgers.id AS value,ledgers.name AS text');
		$this->db->from('ledgers');
		$this->db->where('ledgers.sc_id =',$sc_id);
		$this->db->join('groups','groups.id= ledgers.group_id','INNER');
		$this->db->where('(group_id = 47 OR groups.id = 47)');
		
		$this->db->order_by('ledgers.name', 'asc');
		$ledger_q = $this->db->get();
		$options = $ledger_q->result();
		//echo $this->db->last_query();
		
		return $options;
	}
	/*
	 * 1 for debrots and 2 for creditors ledgers
	 * */
	function getParties($party_type,$sc_id){
		$this->db->select('ledgers.id AS value,ledgers.name AS text');
		$this->db->from('ledgers');
		$this->db->where('ledgers.sc_id =',$sc_id);
		$this->db->where('party_type',$party_type);
		
		$this->db->order_by('ledgers.name', 'asc');
		$ledger_q = $this->db->get();
		$options = $ledger_q->result();
		//echo $this->db->last_query();
		
		return $options;
	}
	function getLedgerByID($ledger_id){
		$this->db->select('ledgers.id AS value,ledgers.name AS text');
		$this->db->from('ledgers');
		$this->db->where('ledgers.id =',$ledger_id);
		$ledger_q = $this->db->get();
		$options = $ledger_q->result();
		
		return $options;
	}
	function get_parent_sub_ledgers($sc_id){
		$options = array();
		$options[0] = "(Please Select)";
		$this->db->from('ledgers')->where('has_sub_ledger', 1)->where('sc_id',$sc_id)->order_by('name', 'asc');
		$ledger_q = $this->db->get();
		foreach ($ledger_q->result() as $row)
		{
			$options[$row->id] = $row->name;
		}
		return $options;	
	}
	function getParentSubLedgerOptions($sc_id){
		$params = array(
					'select'=>'id AS value , name AS text',
					'from'=>'ledgers',
					'where'=>array('has_sub_ledger'=>1,'sc_id'=>$sc_id),
					'order_by'=>'name ASC'
					);
		$ledger_options=$this->get($params);
		return $ledger_options;			
	}
}
