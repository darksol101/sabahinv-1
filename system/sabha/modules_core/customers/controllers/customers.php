<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Customers extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("customers",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model(array('customers/mdl_customers'));
	}
	function getcustomerlist()
	{
		$this->redir->set_last_index();
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();		
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		$list = $this->mdl_customers->getCustomers($page);
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array('customers'=>$list['list'],'ajaxaction'=>$ajaxaction,'navigation'=>$navigation,'page'=>$page);
		$this->load->view('process',$data);
	}
	function getcustomer()
	{
		$this->redir->set_last_index();
		$cust_id = $this->input->post('cust_id');
		$customer = $this->mdl_customers->getCustomer($cust_id);
		echo json_encode($customer);
	}
	function getcustomerpop()
	{
		$this->redir->set_last_index();
		$this->load->view('listcustomers');
	}
}
?>