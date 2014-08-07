<?php

class Log extends Account_Controller {
	function index()
	{
		$this->load->helper('text');
		//$this->template->set('page_title', 'Logs');
		$data['nav_links'] = array('account/log/clear' => 'Clear Log');
		$this->load->view('log/index',$data);

		/* Check access */
		if ( ! check_access('view log'))
		{
			$this->session->set_flashdata('custom_warning','Permission denied.');
			redirect('account/report');
			return;
		}
		return;
	}

	function clear()
	{
		/* Check access */
		if ( ! check_access('clear log'))
		{
			$this->session->set_flashdata('custom_warning','Permission denied.');
			redirect('account/log');
			return;
		}

		/* Check for account lock */
		if ($this->config->item('account_locked') == 1)
		{
			$this->session->set_flashdata('custom_warning','Account is locked.');
			redirect('account/log');
			return;
		}

		if ($this->db->truncate('logs'))
		{
			$this->session->set_flashdata('success_save','Log cleared.');
			redirect('account/log');
		} else {
			$this->session->set_flashdata('custom_error','Error clearing Log.');
			redirect('account/log');
		}
		return;
	}
}

/* End of file log.php */
/* Location: ./system/application/controllers/log.php */
