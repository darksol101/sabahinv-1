<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Account extends Account_Controller{
	function __construct(){
		parent::__construct();
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   				
	}
	function index()
	{
		$this->load->model('Ledger_model');
		$this->load->helper('custom');
		$data = array();
		//$this->template->set('page_title', 'Chart Of Accounts');
		$data['nav_links'] = array('account/group/add' => 'Add Group', 'account/ledger/add' => 'Add Ledger');
		/* Calculating difference in Opening Balance */
		$total_op = $this->Ledger_model->get_diff_op_balance();
		if ($total_op > 0)
		{
			$this->form_validation->_error_array[] = 'Difference in Opening Balance is Dr ' . convert_cur($total_op) . '.';
		} else if ($total_op < 0) {
			$this->form_validation->_error_array[] = 'Difference in Opening Balance is Cr ' . convert_cur(-$total_op) . '.';
		}

		$this->load->view( 'account/account/index',$data);
		return;
	}
	function home()
	{
		$this->load->model('Ledger_model');
		$this->load->library('accountlist');
		//$this->template->set('page_title', 'Welcome to Webzash');

		/* Bank and Cash Ledger accounts */
		$this->db->from('ledgers')->where('type', 1)->where('sc_id',$this->session->userdata('sc_id'));
		$bank_q = $this->db->get();
		if ($bank_q->num_rows() > 0)
		{
			foreach ($bank_q->result() as $row)
			{
				$data['bank_cash_account'][] = array(
					'id' => $row->id,
					'name' => $row->name,
					'balance' => $this->Ledger_model->get_ledger_balance($row->id),
				);
			}
		} else {
			$data['bank_cash_account'] = array();
		}

		/* Calculating total of Assets, Liabilities, Incomes, Expenses */
		$asset = new Accountlist();
		$asset->init(1);
		$data['asset_total'] = $asset->total;

		$liability = new Accountlist();
		$liability->init(2);
		$data['liability_total'] = $liability->total;

		$income = new Accountlist();
		$income->init(3);
		$data['income_total'] = $income->total;

		$expense = new Accountlist();
		$expense->init(4);
		$data['expense_total'] = $expense->total;

		/* Getting Log Messages */
		$data['logs'] = $this->logger->read_recent_messages();
		$this->load->view('welcome', $data);
		return;
	}
}

/* End of file account.php */
/* Location: ./system/application/controllers/account.php */
