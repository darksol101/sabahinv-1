<?php defined('BASEPATH') or die('Direct access script is not allowed');

class Menu extends Admin_Controller
{
	function __construct()
	{
		parent::__construct(TRUE);

		$this->_post_handler();		

	}
	function index()
	{
		$this->redir->set_last_index();
		$this->load->view('index');
	}
	function _post_handler()
	{
		
	}
}

?>