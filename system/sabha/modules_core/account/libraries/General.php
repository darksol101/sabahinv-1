<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General {
	var $error_messages = array();

	function General()
	{
		return;
	}

	/* Check format of config/accounts ini files */
	function check_account($account_name)
	{
		return TRUE;
	}

	/* Check for valid account database */
	function check_database()
	{
		return TRUE;
	}

	/* Check config/settings/general.ini file */
	function check_setting()
	{
		$CI =& get_instance();

		$setting_ini_file = $CI->config->item('config_path') . "settings/general.ini";

		/* Set default values */
		$CI->config->set_item('row_count', "20");
		$CI->config->set_item('log', "1");

		/* Check if general application settings ini file exists */
		if (get_file_info($setting_ini_file))
		{
			/* Parsing general application settings ini file */
			$cur_setting = parse_ini_file($setting_ini_file);
			if ($cur_setting)
			{
				if (isset($cur_setting['row_count']))
				{
					$CI->config->set_item('row_count', $cur_setting['row_count']);
				}
				if (isset($cur_setting['log']))
				{
					$CI->config->set_item('log', $cur_setting['log']);
				}
			}
		}
	}

	function check_user($user_name)
	{
		$CI =& get_instance();
	}

	function check_database_version()
	{
		$CI =& get_instance();		
	}

	function setup_entry_types()
	{
		$CI =& get_instance();

		$CI->db->from('entry_types')->order_by('id', 'asc');
		$entry_types = $CI->db->get();
		if ($entry_types->num_rows() < 1)
		{
			$CI->session->set_flashdata('custom_warning','You need to create a entry type before you can create any entries.');
		}
		$entry_type_config = array();
		foreach ($entry_types->result() as $id => $row)
		{
			$entry_type_config[$row->id] = array(
				'label' => $row->label,
				'name' => $row->name,
				'description' => $row->description,
				'base_type' => $row->base_type,
				'numbering' => $row->numbering,
				'prefix' => $row->prefix,
				'suffix' => $row->suffix,
				'zero_padding' => $row->zero_padding,
				'bank_cash_ledger_restriction' => $row->bank_cash_ledger_restriction,
			);
		}
		$CI->config->set_item('account_entry_types', $entry_type_config);
	}
}

/* End of file General.php */
/* Location: ./system/application/libraries/General.php */
