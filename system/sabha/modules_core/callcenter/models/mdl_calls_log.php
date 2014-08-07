<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Calls_log extends MY_Model {
	public function __construct() {
		parent::__construct();
		$this->table_name = 'sst_calls_log';
		$this->primary_key = 'sst_calls_log.call_id';
		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";
		$this->order_by = ' call_id';
	}
	function getCallReasonPending($page)
	{

		$call_id = $this->input->post('call_id');
		$sql  = "(SELECT call_reason_pending ,call_engineer_remark ,call_id,call_last_mod_by as log_user,call_last_mod_ts AS log_date,username,e.engineer_name,sc.sc_name
				FROM sst_calls 
				LEFT JOIN mcb_users AS u ON u.user_id=sst_calls.call_last_mod_by
				LEFT JOIN sst_engineers AS e on e.engineer_id=sst_calls.engineer_id
				LEFT JOIN sst_service_centers AS sc ON sc.sc_id=sst_calls.sc_id
				WHERE call_id='$call_id' AND call_reason_pending!='' AND call_reason_pending!='0')
				UNION ALL
				(SELECT call_reason_pending ,call_engineer_remark ,call_id,log_user,log_date,username,e.engineer_name,sc.sc_name
				FROM sst_calls_log 
				LEFT JOIN mcb_users AS u ON u.user_id=sst_calls_log.log_user
				LEFT JOIN sst_engineers AS e on e.engineer_id=sst_calls_log.engineer_id
				LEFT JOIN sst_service_centers AS sc ON sc.sc_id=sst_calls_log.sc_id
				WHERE call_id='$call_id' AND call_reason_pending!='' AND call_reason_pending!='0')
				ORDER BY log_date DESC"
		;
		$query = $this->db->query($sql." LIMIT ".(int)$page['start'].",".$page['limit']);
		$query_total = $this->db->query($sql);

		$list = $query->result();

		$calls_log['list'] = $list;
		$calls_log['total'] = count($query_total->result());
		return $calls_log;
	}
}

?>