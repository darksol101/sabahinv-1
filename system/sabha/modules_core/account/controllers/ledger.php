<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Ledger extends Account_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));
		$this->load->model('Ledger_model');
		$this->load->model('Group_model');
		return;
	}

	function index()
	{
		redirect('account/ledger/add');
		return;
	}

	function add()
	{
		//$this->template->set('page_title', 'New Ledger');

		/* Check access */
		if ( ! check_access('create ledger'))
		{
			$this->session->set_flashdata('custom_error','Permission denied.');
			redirect('account');
			return;
		}

		/* Check for account lock */
		if ($this->config->item('account_locked') == 1)
		{
			$this->session->set_flashdata('custom_error','Account is locked.');
			redirect('account');
			return;
		}

		/* Form fields */
		$data['ledger_name'] = array(
			'name' => 'ledger_name',
			'id' => 'ledger_name',
			'maxlength' => '100',
			'size' => '40',
			'value' => '',
			'class' => 'text-input'
		);
		$data['ledger_group_id'] = $this->Group_model->get_ledger_groups();
		$data['op_balance'] = array(
			'name' => 'op_balance',
			'id' => 'op_balance',
			'maxlength' => '15',
			'size' => '15',
			'value' => '',
			'class' => 'text-input',
			'style'=>'width:40%'
		);
		$data['ledger_group_active'] = 0;
		$data['op_balance_dc'] = "D";
		$data['ledger_type_cashbank'] = FALSE;
		$data['reconciliation'] = FALSE;

		/* Form validations */
		$this->form_validation->set_rules('ledger_name', 'Ledger name', 'trim|required|min_length[2]|max_length[100]|unique[ledgers.name]');
		$this->form_validation->set_rules('ledger_group_id', 'Parent group', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('op_balance', 'Opening balance', 'trim|currency');
		$this->form_validation->set_rules('op_balance_dc', 'Opening balance type', 'trim|required|is_dc');

		/* Re-populating form */
		if ($_POST)
		{
			$data['ledger_name']['value'] = $this->input->post('ledger_name', TRUE);
			$data['op_balance']['value'] = $this->input->post('op_balance', TRUE);
			$data['ledger_group_active'] = $this->input->post('ledger_group_id', TRUE);
			$data['op_balance_dc'] = $this->input->post('op_balance_dc', TRUE);
			$data['ledger_type_cashbank'] = $this->input->post('ledger_type_cashbank', TRUE);
			$data['reconciliation'] = $this->input->post('reconciliation', TRUE);
		}

		if ($this->form_validation->run() == FALSE)
		{
			//$this->session->set_flashdata('custom_error',validation_errors());
			$this->load->view('ledger/add', $data);
			return;
		}
		else
		{
			$data_name = $this->input->post('ledger_name', TRUE);
			$data_group_id = $this->input->post('ledger_group_id', TRUE);
			$data_op_balance = $this->input->post('op_balance', TRUE);
			$data_op_balance_dc = $this->input->post('op_balance_dc', TRUE);
			$data_ledger_type_cashbank_value = $this->input->post('ledger_type_cashbank', TRUE);
			$data_reconciliation = $this->input->post('reconciliation', TRUE);

			if ($data_group_id < 5)
			{
				$this->form_validation->_error_array[] = 'Invalid Parent group.';
				$this->load->view('account/ledger/add', $data);
				return;
			}

			/* Check if parent group id present */
			$this->db->select('id')->from('groups')->where('id', $data_group_id);
			if ($this->db->get()->num_rows() < 1)
			{
				$this->form_validation->_error_array[] = 'Invalid Parent group.';
				$this->template->load('template', 'ledger/add', $data);
				return;
			}

			if (!$data_op_balance) {
				$data_op_balance = "0.00";
			}

			if ($data_ledger_type_cashbank_value == "1")
			{
				$data_ledger_type = 1;
			} else {
				$data_ledger_type = 0;
			}

			if ($data_reconciliation == "1")
			{
				$data_reconciliation = 1;
			} else {
				$data_reconciliation = 0;
			}

			$this->db->trans_start();
			$insert_data = array(
				'name' => $data_name,
				'group_id' => $data_group_id,
				'op_balance' => $data_op_balance,
				'op_balance_dc' => $data_op_balance_dc,
				'type' => $data_ledger_type,
				'reconciliation' => $data_reconciliation,
				'sc_id' => $this->session->userdata('sc_id')
			);
			if ( ! $this->db->insert('ledgers', $insert_data))
			{
				$this->db->trans_rollback();
				$this->form_validation->_error_array[] = 'Error addding Ledger account - ' . $data_name . '.';
				$this->logger->write_message("error", "Error adding Ledger account called " . $data_name);
				$this->load->view('account/group/add', $data);
				return;
			} else {
				$this->db->trans_complete();
				$this->session->set_flashdata('success_save','Added Ledger account - ' . $data_name . '.');
				$this->logger->write_message("success", "Added Ledger account called " . $data_name);
				redirect('account');
				return;
			}
		}
		return;
	}

	function edit($id)
	{
		$this->load->helpers(array('my_form'));
		//$this->template->set('page_title', 'Edit Ledger');

		/* Check access */
		if ( ! check_access('edit ledger'))
		{
			$this->session->set_falshdata('custom_error','Permission denied.');
			redirect('account');
			return;
		}

		/* Check for account lock */
		if ($this->config->item('account_locked') == 1)
		{
			$this->session->set_flashdata('custom_error','Account is locked.');
			redirect('account');
			return;
		}

		/* Checking for valid data */
		$id = $this->input->_clean_input_data($id);
		$id = (int)$id;
		if ($id < 1)
		{
			$this->session->set_flashdata('custom_error','Invalid Ledger account.');
			redirect('account');
			return;
		}

		/* Loading current group */
		$this->db->from('ledgers')->where('id', $id);
		$ledger_data_q = $this->db->get();
		if ($ledger_data_q->num_rows() < 1)
		{
			$this->session->set_flashdata('custom_error','Invalid Ledger account.');
			redirect('account');
			return;
		}
		$ledger_data = $ledger_data_q->row();

		/* Form fields */
		$data['ledger_name'] = array(
			'name' => 'ledger_name',
			'id' => 'ledger_name',
			'maxlength' => '100',
			'size' => '40',
			'value' => $ledger_data->name,
		);
		$data['ledger_group_id'] = $this->Group_model->get_ledger_groups();
		$data['op_balance'] = array(
			'name' => 'op_balance',
			'id' => 'op_balance',
			'maxlength' => '15',
			'size' => '15',
			'value' => $ledger_data->op_balance,
		);
		$data['ledger_group_active'] = $ledger_data->group_id;
		$data['op_balance_dc'] = $ledger_data->op_balance_dc;
		$data['ledger_id'] = $id;
		$data['sc_id'] = $ledger_data->sc_id;
		if ($ledger_data->type == 1)
			$data['ledger_type_cashbank'] = TRUE;
		else
			$data['ledger_type_cashbank'] = FALSE;
		$data['reconciliation'] = $ledger_data->reconciliation;

		/* Form validations */
		$this->form_validation->set_rules('ledger_name', 'Ledger name', 'trim|required|min_length[2]|max_length[100]|uniquewithid[ledgers.name.' . $id . ']');
		$this->form_validation->set_rules('ledger_group_id', 'Parent group', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('op_balance', 'Opening balance', 'trim|currency');
		$this->form_validation->set_rules('op_balance_dc', 'Opening balance type', 'trim|required|is_dc');

		/* Re-populating form */
		if ($_POST)
		{
			$data['ledger_name']['value'] = $this->input->post('ledger_name', TRUE);
			$data['ledger_group_active'] = $this->input->post('ledger_group_id', TRUE);
			$data['op_balance']['value'] = $this->input->post('op_balance', TRUE);
			$data['op_balance_dc'] = $this->input->post('op_balance_dc', TRUE);
			$data['ledger_type_cashbank'] = $this->input->post('ledger_type_cashbank', TRUE);
			$data['reconciliation'] = $this->input->post('reconciliation', TRUE);
		}

		if ($this->form_validation->run() == FALSE)
		{
			//$this->messages->add(validation_errors(), 'error');
			$this->load->view('account/ledger/edit', $data);
			return;
		}
		else
		{
			$data_name = $this->input->post('ledger_name', TRUE);
			$data_group_id = $this->input->post('ledger_group_id', TRUE);
			$data_op_balance = $this->input->post('op_balance', TRUE);
			$data_op_balance_dc = $this->input->post('op_balance_dc', TRUE);
			$data_id = $id;
			$data_ledger_type_cashbank_value = $this->input->post('ledger_type_cashbank', TRUE);
			$data_reconciliation = $this->input->post('reconciliation', TRUE);

			if ($data_group_id < 5)
			{
				$this->form_validation->_error_array[] = 'Invalid Parent group.';
				$this->template->load('account/ledger/edit', $data);
				return;
			}

			/* Check if parent group id present */
			$this->db->select('id')->from('groups')->where('id', $data_group_id);
			if ($this->db->get()->num_rows() < 1)
			{
				$this->form_validation->_error_array[] = 'Invalid Parent group.';
				$this->load->view('account/ledger/edit', $data);
				return;
			}

			if (!$data_op_balance) {
				$data_op_balance = "0.00";
			}

			/* Check if bank_cash_ledger_restriction both entry present */
			if ($data_ledger_type_cashbank_value != "1")
			{
				$entry_type_all = $this->config->item('account_entry_types');
				foreach ($entry_type_all as $entry_type_id => $row)
				{
					/* Check for Entry types where bank_cash_ledger_restriction is for all ledgers */
					if ($row['bank_cash_ledger_restriction'] == 4)
					{
						$this->db->from('entry_items')->join('entries', 'entry_items.entry_id = entries.id')->where('entries.entry_type', $entry_type_id)->where('entry_items.ledger_id', $id);
						$all_ledger_bank_cash_count = $this->db->get()->num_rows();
						if ($all_ledger_bank_cash_count > 0)
						{
							$this->form_validation->_error_array[] = 'Cannot remove the Bank or Cash Account status of this Ledger account since it is still linked with ' . $all_ledger_bank_cash_count . ' ' . $row['name'] . ' entries.';
							$this->load->view('account/ledger/edit', $data);
							return;
						}
					}
				}
			}

			if ($data_ledger_type_cashbank_value == "1")
			{
				$data_ledger_type = 1;
			} else {
				$data_ledger_type = 0;
			}

			if ($data_reconciliation == "1")
			{
				$data_reconciliation = 1;
			} else {
				$data_reconciliation = 0;
			}

			$this->db->trans_start();
			$update_data = array(
				'name' => $data_name,
				'group_id' => $data_group_id,
				'op_balance' => $data_op_balance,
				'op_balance_dc' => $data_op_balance_dc,
				'type' => $data_ledger_type,
				'reconciliation' => $data_reconciliation,
			);
			if ( ! $this->db->where('id', $data_id)->update('ledgers', $update_data))
			{
				$this->db->trans_rollback();
				$this->form_validation->_error_array[] = 'Error updating Ledger account - ' . $data_name . '.';
				$this->logger->write_message("error", "Error updating Ledger account called " . $data_name . " [id:" . $data_id . "]");
				$this->load->view('account/ledger/edit', $data);
				return;
			} else {
				/* Deleting reconciliation data if reconciliation disabled */
				if (($ledger_data->reconciliation == 1) AND ($data_reconciliation == 0))
				{
					$this->Ledger_model->delete_reconciliation($data_id);
				}
				$this->db->trans_complete();
				$this->session->set_flashdata('success_save','Updated Ledger account - ' . $data_name . '.');
				$this->logger->write_message("success", "Updated Ledger account called " . $data_name . " [id:" . $data_id . "]");
				redirect('account');
				return;
			}
		}
		return;
	}

	function delete($id)
	{
		/* Check access */
		if ( ! check_access('delete ledger'))
		{
			$this->session->set_flashdata('custom_warning','Permission denied.');
			redirect('account');
			return;
		}

		/* Checking for valid data */
		$id = $this->input->_clean_input_data($id);
		$id = (int)$id;
		if ($id < 1)
		{
			$this->session->set_flashdata('custom_warning','Invalid Ledger account.');
			redirect('account');
			return;
		}
		$this->db->from('entry_items')->where('ledger_id', $id);
		if ($this->db->get()->num_rows() > 0)
		{
			$this->session->set_flashdata('custom_warning','Cannot delete non-empty Ledger account.');
			redirect('account');
			return;
		}

		/* Get the ledger details */
		$this->db->from('ledgers')->where('id', $id);
		$ledger_q = $this->db->get();
		if ($ledger_q->num_rows() < 1)
		{
			$this->session->set_flashdata('custom_warning','Invalid Ledger account.');
			redirect('account');
			return;
		} else {
			$ledger_data = $ledger_q->row();
		}

		/* Deleting ledger */
		$this->db->trans_start();
		if ( ! $this->db->delete('ledgers', array('id' => $id)))
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('custom_warning','Error deleting Ledger account - ' . $ledger_data->name . '.');
			$this->logger->write_message("error", "Error deleting Ledger account called " . $ledger_data->name . " [id:" . $id . "]");
			redirect('account');
			return;
		} else {
			/* Deleting reconciliation data if present */
			$this->Ledger_model->delete_reconciliation($id);
			$this->db->trans_complete();
			$this->session->set_flashdata('success_delete','Deleted Ledger account - ' . $ledger_data->name . '.');
			$this->logger->write_message("success", "Deleted Ledger account called " . $ledger_data->name . " [id:" . $id . "]");
			redirect('account');
			return;
		}
		return;
	}

	function balance($ledger_id = 0)
	{
		if ($ledger_id > 0)
			echo $this->Ledger_model->get_ledger_balance($ledger_id);
		else
			echo "";
		return;
	}
}

/* End of file ledger.php */
/* Location: ./system/application/controllers/ledger.php */
