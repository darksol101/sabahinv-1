<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Check if the currently logger in user has the necessary permissions
 * to permform the given action
 *
 * Valid permissions strings are given below :
 *
 * 'view entry'
 * 'create entry'
 * 'edit entry'
 * 'delete entry'
 * 'print entry'
 * 'email entry'
 * 'download entry'
 * 'create ledger'
 * 'edit ledger'
 * 'delete ledger'
 * 'create group'
 * 'edit group'
 * 'delete group'
 * 'create tag'
 * 'edit tag'
 * 'delete tag'
 * 'view reports'
 * 'view log'
 * 'clear log'
 * 'change account settings'
 * 'cf account'
 * 'backup account'
 * 'administer'
 */

if ( ! function_exists('check_access'))
{
	function check_access($action_name)
	{
		$CI =& get_instance();
		$user_role = $CI->session->userdata('usergroup_id');
		$global_admin = $CI->session->userdata('global_admin');
		$permissions[$user_role] = array();
		$CI->db->select('access ')->from('access')->where('usergroup_id',$user_role);
		$result = $CI->db->get();

		if($result->num_rows()>0){
			$tasks =  json_decode( $result->row()->access );
			$permissions[$user_role] = $tasks->task ;
		} 
		if ( ! isset($user_role))
			return FALSE;

		/* If user is administrator then always allow access */
		if ($global_admin == 1)
			return TRUE;

		if ( ! isset($permissions[$user_role]))
			return FALSE;

		if (in_array($action_name, $permissions[$user_role]))
			return TRUE;
		else
			return FALSE;
	}
}

/* End of file access_helper.php */
/* Location: ./system/application/helpers/access_helper.php */
