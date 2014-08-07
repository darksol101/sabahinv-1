<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Entry extends Account_Controller {

	function __construct(){
		parent::__construct();
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   						
		$this->load->model('Entry_model');
		$this->load->model('Ledger_model');
		$this->load->model('Tag_model');
		return;
	}

	function index()
	{
		redirect('account/entry/show/all');
		return;
	}

	function show($entry_type)
	{
		/* Check access */
		if ( ! check_access('view entry'))
		{
			$this->session->set_flashdata('custom_warning','Permission denied.');
			redirect('account');
			return;
		}
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   						
		$this->load->model('Tag_model');

		$data['tag_id'] = 0;
		$entry_type_id = 0;

		if ($entry_type == 'tag')
		{
			$tag_id = (int)$this->uri->segment(4);
			if ($tag_id < 1)
			{
				$this->session->set_flashdata('error','Invalid Tag.');
				redirect('account/entry/show/all');
				return;
			}
			$data['tag_id'] = $tag_id;
			$tag_name = $this->Tag_model->tag_name($tag_id);
			//$this->template->set('page_title', 'Entries Tagged "' . $tag_name . '"');
		} else if ($entry_type == 'all') {
			$entry_type_id = 0;
			//$this->template->set('page_title', 'All Entries');
		} else {
			$entry_type_id = entry_type_name_to_id($entry_type);
			if ( ! $entry_type_id)
			{
				$this->session->set_flashdata('warning','Invalid Entry type specified. Showing all Entries.');
				redirect('account/entry/show/all');
				return;
			} else {
				$current_entry_type = entry_type_info($entry_type_id);				
				//$this->template->set('page_title', $current_entry_type['name'] . ' Entries');
				$data['nav_links'] = array('account/entry/add/' . $current_entry_type['label'] => 'New ' . $current_entry_type['name'] . ' Entry');
			}
		}
		$data['entry_type_id'] = $entry_type_id;
		$entry_q = NULL;

		/* Pagination setup */
		$this->load->library('pagination');

		if ($entry_type == "tag")
			$page_count = (int)$this->uri->segment(6);
		else
			$page_count = (int)$this->uri->segment(5);

		$page_count = $this->input->_clean_input_data($page_count);
		if ( ! $page_count)
			$page_count = "0";

		/* Pagination configuration */
		if ($entry_type == 'tag')
		{
			$config['base_url'] = site_url('account/entry/show/tag' . $tag_id);
			$config['uri_segment'] = 6;
		} else if ($entry_type == 'all') {
			$config['base_url'] = site_url('account/entry/show/all');
			$config['uri_segment'] = 5;
		} else {
			$config['base_url'] = site_url('account/entry/show/' . $current_entry_type['label']);
			$config['uri_segment'] = 5;
		}
		$pagination_counter = $this->mdl_mcb_data->get('per_page');
		$config['num_links'] = 10;
		$config['per_page'] = $pagination_counter;
		$config['full_tag_open'] = '<div class="pagination">';
		$config['full_close_open'] = '</div>';
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';

		if ($entry_type == "tag") {
			$this->db->from('entries')->where('tag_id', $tag_id)->where('sc_id', $this->session->userdata('sc_id'))->order_by('date', 'desc')->order_by('number', 'desc')->limit($pagination_counter, $page_count);
			$entry_q = $this->db->get();
			$config['total_rows'] = $this->db->from('entries')->where('tag_id', $tag_id)->where('sc_id', $this->session->userdata('sc_id'))->get()->num_rows();
		} else if ($entry_type_id > 0) {
			$this->db->from('entries')->where('entry_type', $entry_type_id)->where('sc_id', $this->session->userdata('sc_id'))->order_by('date', 'desc')->order_by('number', 'desc')->limit($pagination_counter, $page_count);
			$entry_q = $this->db->get();
			$config['total_rows'] = $this->db->from('entries')->where('entry_type', $entry_type_id)->where('sc_id', $this->session->userdata('sc_id'))->get()->num_rows();
		} else {
			$this->db->from('entries')->order_by('date', 'desc')->where('sc_id', $this->session->userdata('sc_id'))->order_by('number', 'desc')->limit($pagination_counter, $page_count);
			$entry_q = $this->db->get();
			$config['total_rows'] = $this->db->count_all('entries');
		}

		/* Pagination initializing */
		$this->pagination->initialize($config);

		/* Show entry add actions */
		if ($this->session->userdata('entry_added_show_action'))
		{
			$entry_added_id_temp = $this->session->userdata('entry_added_id');
			$entry_added_type_id_temp = $this->session->userdata('entry_added_type_id');
			$entry_added_type_label_temp = $this->session->userdata('entry_added_type_label');
			$entry_added_type_name_temp = $this->session->userdata('entry_added_type_name');
			$entry_added_number_temp = $this->session->userdata('entry_added_number');
			$entry_added_message = 'Added ' . $entry_added_type_name_temp . ' Entry number ' . full_entry_number($entry_added_type_id_temp, $entry_added_number_temp) . ".";
			$entry_added_message .= " You can [ ";
			$entry_added_message .= anchor('entry/view/' . $entry_added_type_label_temp . "/" . $entry_added_id_temp, 'View', array('class' => 'anchor-link-a')) . " | ";
			$entry_added_message .= anchor('entry/edit/' . $entry_added_type_label_temp . "/" . $entry_added_id_temp, 'Edit', array('class' => 'anchor-link-a')) . " | ";
			$entry_added_message .= anchor_popup('entry/printpreview/' . $entry_added_type_label_temp . "/" . $entry_added_id_temp , 'Print', array('class' => 'anchor-link-a', 'width' => '600', 'height' => '600')) . " | ";
			$entry_added_message .= anchor_popup('entry/email/' . $entry_added_type_label_temp . "/" . $entry_added_id_temp, 'Email', array('class' => 'anchor-link-a', 'width' => '500', 'height' => '300')) . " | ";
			$entry_added_message .= anchor('entry/download/' . $entry_added_type_label_temp . "/" . $entry_added_id_temp, 'Download', array('class' => 'anchor-link-a'));
			$entry_added_message .= " ] it.";
			$this->session->set_flashdata('successs_save',$entry_added_message);
			$this->session->unset_userdata('entry_added_show_action');
			$this->session->unset_userdata('entry_added_id');
			$this->session->unset_userdata('entry_added_type_id');
			$this->session->unset_userdata('entry_added_type_label');
			$this->session->unset_userdata('entry_added_type_name');
			$this->session->unset_userdata('entry_added_number');
		}

		/* Show entry edit actions */
		if ($this->session->userdata('entry_updated_show_action'))
		{
			$entry_updated_id_temp = $this->session->userdata('entry_updated_id');
			$entry_updated_type_id_temp = $this->session->userdata('entry_updated_type_id');
			$entry_updated_type_label_temp = $this->session->userdata('entry_updated_type_label');
			$entry_updated_type_name_temp = $this->session->userdata('entry_updated_type_name');
			$entry_updated_number_temp = $this->session->userdata('entry_updated_number');
			$entry_updated_message = 'Updated ' . $entry_updated_type_name_temp . ' Entry number ' . full_entry_number($entry_updated_type_id_temp, $entry_updated_number_temp) . ".";
			$entry_updated_message .= " You can [ ";
			$entry_updated_message .= anchor('account/entry/view/' . $entry_updated_type_label_temp . "/" . $entry_updated_id_temp, 'View', array('class' => 'anchor-link-a')) . " | ";
			$entry_updated_message .= anchor('account/entry/edit/' . $entry_updated_type_label_temp . "/" . $entry_updated_id_temp, 'Edit', array('class' => 'anchor-link-a')) . " | ";
			$entry_updated_message .= anchor_popup('account/entry/printpreview/' . $entry_updated_type_label_temp . "/" . $entry_updated_id_temp , 'Print', array('class' => 'anchor-link-a', 'width' => '600', 'height' => '600')) . " | ";
			$entry_updated_message .= anchor_popup('account/entry/email/' . $entry_updated_type_label_temp . "/" . $entry_updated_id_temp, 'Email', array('class' => 'anchor-link-a', 'width' => '500', 'height' => '300')) . " | ";
			$entry_updated_message .= anchor('account/entry/download/' . $entry_updated_type_label_temp . "/" . $entry_updated_id_temp, 'Download', array('class' => 'anchor-link-a'));
			$entry_updated_message .= " ] it.";
			$this->session->set_flashdata('success_save',$entry_updated_message);

			if ($this->session->userdata('entry_updated_has_reconciliation'))
				$this->session->set_flashdata('success_save','Previous reconciliations for this entry are no longer valid. You need to redo the reconciliations for this entry.');

			$this->session->unset_userdata('entry_updated_show_action');
			$this->session->unset_userdata('entry_updated_id');
			$this->session->unset_userdata('entry_updated_type_id');
			$this->session->unset_userdata('entry_updated_type_label');
			$this->session->unset_userdata('entry_updated_type_name');
			$this->session->unset_userdata('entry_updated_number');
			$this->session->unset_userdata('entry_updated_has_reconciliation');
		}

		$data['entry_data'] = $entry_q;

		$this->load->view('account/entry/index', $data);
		return;

	}

	function view($entry_type, $entry_id = 0)
	{
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   								
		/* Entry Type */
		$entry_type_id = entry_type_name_to_id($entry_type);
		if ( ! $entry_type_id)
		{
			$this->messages->add('Invalid Entry type.', 'error');
			redirect('entry/show/all');
			return;
		} else {
			$current_entry_type = entry_type_info($entry_type_id);
		}

		//$this->template->set('page_title', 'View ' . $current_entry_type['name'] . ' Entry');

		/* Load current entry details */
		if ( ! $cur_entry = $this->Entry_model->get_entry($entry_id, $entry_type_id))
		{
			$this->session->set_flashdata('custom_error','Invalid Entry.');
			redirect('account/entry/show/' . $current_entry_type['label']);
			return;
		}
		if($cur_entry->sc_id!=$this->session->userdata('sc_id')){
			$this->session->set_flashdata('custom_error','Invalid Entry.');
			redirect('account/entry/show/' . $current_entry_type['label']);
			return;
		}
		/* Load current entry details */
		$this->db->from('entry_items')->where('entry_id', $entry_id)->order_by('id', 'asc');
		$cur_entry_ledgers = $this->db->get();
		if ($cur_entry_ledgers->num_rows() < 1)
		{
			$this->session->set_flashdata('custom_error','Entry has no associated Ledger accounts.');
		}
		$data['cur_entry'] = $cur_entry;
		$data['cur_entry_ledgers'] = $cur_entry_ledgers;
		$data['entry_type_id'] = $entry_type_id;
		$data['current_entry_type'] = $current_entry_type;
		$this->load->view('account/entry/view', $data);
		return;
	}

	function add($entry_type)
	{
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   			
		/* Check access */
		if ( ! check_access('create entry'))
		{
			$this->session->set_flashdata('custom_error','Permission denied.');
			redirect('account/entry/show/' . $entry_type);
			return;
		}

		/* Check for account lock */
		if ($this->config->item('account_locked') == 1)
		{
			$this->session->set_flashdata('custom_error','Account is locked.');
			redirect('account/entry/show/' . $entry_type);
			return;
		}

		/* Entry Type */
		$entry_type_id = entry_type_name_to_id($entry_type);
		if ( ! $entry_type_id)
		{
			$this->session->set_flashdata('custom_error','Invalid Entry type.');
			redirect('account/entry/show/all');
			return;
		} else {
			$current_entry_type = entry_type_info($entry_type_id);
		}

		//$this->template->set('page_title', 'New ' . $current_entry_type['name'] . ' Entry');

		/* Form fields */
		$data['entry_number'] = array(
			'name' => 'entry_number',
			'id' => 'entry_number',
			'maxlength' => '11',
			'size' => '11',
			'value' => '',
			'class' => 'text-input'
		);
		$data['entry_date'] = array(
			'name' => 'entry_date',
			'id' => 'entry_date',
			'maxlength' => '11',
			'size' => '11',
			'value' => date_today_php(),
			'class' => 'text-input'
		);
		$data['entry_narration'] = array(
			'name' => 'entry_narration',
			'id' => 'entry_narration',
			'cols' => '50',
			'rows' => '4',
			'value' => '',
		);
		$data['entry_type_id'] = $entry_type_id;
		$data['current_entry_type'] = $current_entry_type;
		$data['entry_tags'] = $this->Tag_model->get_all_tags();
		$data['entry_tag'] = 0;

		/* Form validations */
		if ($current_entry_type['numbering'] == '2')
			$this->form_validation->set_rules('entry_number', 'Entry Number', 'trim|required|is_natural_no_zero|uniqueentryno[' . $entry_type_id . ']');
		else if ($current_entry_type['numbering'] == '3')
			$this->form_validation->set_rules('entry_number', 'Entry Number', 'trim|is_natural_no_zero|uniqueentryno[' . $entry_type_id . ']');
		else
			$this->form_validation->set_rules('entry_number', 'Entry Number', 'trim|is_natural_no_zero|uniqueentryno[' . $entry_type_id . ']');
		$this->form_validation->set_rules('entry_date', 'Entry Date', 'trim|required|is_date|is_date_within_range');
		$this->form_validation->set_rules('entry_narration', 'trim');
		$this->form_validation->set_rules('entry_tag', 'Tag', 'trim|is_natural');

		/* Debit and Credit amount validation */
		if ($_POST)
		{
			foreach ($this->input->post('ledger_dc', TRUE) as $id => $ledger_data)
			{
				$this->form_validation->set_rules('dr_amount[' . $id . ']', 'Debit Amount', 'trim|currency');
				$this->form_validation->set_rules('cr_amount[' . $id . ']', 'Credit Amount', 'trim|currency');
			}
		}

		/* Repopulating form */
		if ($_POST)
		{
			$data['entry_number']['value'] = $this->input->post('entry_number', TRUE);
			$data['entry_date']['value'] = $this->input->post('entry_date', TRUE);
			$data['entry_narration']['value'] = $this->input->post('entry_narration', TRUE);
			$data['entry_tag'] = $this->input->post('entry_tag', TRUE);

			$data['ledger_dc'] = $this->input->post('ledger_dc', TRUE);
			$data['ledger_id'] = $this->input->post('ledger_id', TRUE);
			$data['dr_amount'] = $this->input->post('dr_amount', TRUE);
			$data['cr_amount'] = $this->input->post('cr_amount', TRUE);
		} else {
			for ($count = 0; $count <= 3; $count++)
			{
				if ($count == 0 && $entry_type == "payment")
					$data['ledger_dc'][$count] = "C";
				else if ($count == 1 && $entry_type != "payment")
					$data['ledger_dc'][$count] = "C";
				else
					$data['ledger_dc'][$count] = "D";
				$data['ledger_id'][$count] = 0;
				$data['dr_amount'][$count] = "";
				$data['cr_amount'][$count] = "";
			}
		}

		if ($this->form_validation->run() == FALSE)
		{
			//$this->session->set_flashdata('custom_error',validation_errors());
			$this->load->view('account/entry/add', $data);
			return;
		}
		else
		{
			/* Checking for Valid Ledgers account and Debit and Credit Total */
			$data_all_ledger_id = $this->input->post('ledger_id', TRUE);
			$data_all_ledger_dc = $this->input->post('ledger_dc', TRUE);
			$data_all_dr_amount = $this->input->post('dr_amount', TRUE);
			$data_all_cr_amount = $this->input->post('cr_amount', TRUE);
			$dr_total = 0;
			$cr_total = 0;
			$bank_cash_present = FALSE; /* Whether atleast one Ledger account is Bank or Cash account */
			$non_bank_cash_present = FALSE;  /* Whether atleast one Ledger account is NOT a Bank or Cash account */
			foreach ($data_all_ledger_dc as $id => $ledger_data)
			{
				if ($data_all_ledger_id[$id] < 1)
					continue;

				/* Check for valid ledger id */
				$this->db->from('ledgers')->where('id', $data_all_ledger_id[$id])->where('(sc_id='.$this->session->userdata('sc_id').')');
				$valid_ledger_q = $this->db->get();
				if ($valid_ledger_q->num_rows() < 1)
				{
					$this->form_validation->_error_array[] = 'Invalid Ledger account.';
					$this->load->view('account/entry/add', $data);
					return;
				} else {
					/* Check for valid ledger type */
					$valid_ledger = $valid_ledger_q->row();
					if ($current_entry_type['bank_cash_ledger_restriction'] == '2')
					{
						if ($data_all_ledger_dc[$id] == 'D' && $valid_ledger->type == 1)
						{
							$bank_cash_present = TRUE;
						}
						if ($valid_ledger->type != 1)
							$non_bank_cash_present = TRUE;
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '3')
					{
						if ($data_all_ledger_dc[$id] == 'C' && $valid_ledger->type == 1)
						{
							$bank_cash_present = TRUE;
						}
						if ($valid_ledger->type != 1)
							$non_bank_cash_present = TRUE;
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '4')
					{
						if ($valid_ledger->type != 1)
						{
							$this->form_validation->_error_array[] = 'Invalid Ledger account. ' . $current_entry_type['name'] . ' Entries can have only Bank and Cash Ledgers accounts.';
							$this->load->view('account/entry/add', $data);
							return;
						}
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '5')
					{
						if ($valid_ledger->type == 1)
						{
							$this->form_validation->_error_array[] = 'Invalid Ledger account. ' . $current_entry_type['name'] . ' Entries cannot have Bank and Cash Ledgers accounts.';
							$this->load->view('account/entry/add', $data);
							return;
						}
					}
				}

				if ($data_all_ledger_dc[$id] == "D")
				{
					$dr_total = float_ops($data_all_dr_amount[$id], $dr_total, '+');
				} else {
					$cr_total = float_ops($data_all_cr_amount[$id], $cr_total, '+');
				}
			}
			if (float_ops($dr_total, $cr_total, '!='))
			{
				$this->form_validation->_error_array[] = 'Debit and Credit Total does not match!';
				$this->load->view('account/entry/add', $data);
				return;
			} else if (float_ops($dr_total, 0, '==') && float_ops($cr_total, 0, '==')) {
				$this->form_validation->_error_array[] = 'Cannot save empty Entry.';
				$this->load->view('account/entry/add', $data);
				return;
			}
			/* Check if atleast one Bank or Cash Ledger account is present */
			if ($current_entry_type['bank_cash_ledger_restriction'] == '2')
			{
				if ( ! $bank_cash_present)
				{
					$this->form_validation->_error_array[] = 'Need to Debit atleast one Bank or Cash account.';
					$this->load->view('account/entry/add', $data);
					return;
				}
				if ( ! $non_bank_cash_present)
				{
					$this->form_validation->_error_array[] = 'Need to Debit or Credit atleast one NON - Bank or Cash account.';
					$this->load->view('account/entry/add', $data);
					return;
				}
			} else if ($current_entry_type['bank_cash_ledger_restriction'] == '3')
			{
				if ( ! $bank_cash_present)
				{
					$this->form_validation->_error_array[] = 'Need to Credit atleast one Bank or Cash account.';
					$this->load->view('account/entry/add', $data);
					return;
				}
				if ( ! $non_bank_cash_present)
				{
					$this->form_validation->_error_array[] = 'Need to Debit or Credit atleast one NON - Bank or Cash account.';
					$this->load->view('account/entry/add', $data);
					return;
				}
			}

			/* Adding main entry */
			if ($current_entry_type['numbering'] == '2')
			{
				$data_number = $this->input->post('entry_number', TRUE);
			} else if ($current_entry_type['numbering'] == '3') {
				$data_number = $this->input->post('entry_number', TRUE);
				if ( ! $data_number)
					$data_number = NULL;
			} else {
				if ($this->input->post('entry_number', TRUE))
					$data_number = $this->input->post('entry_number', TRUE);
				else
					$data_number = $this->Entry_model->next_entry_number($entry_type_id);
			}

			$data_date = $this->input->post('entry_date', TRUE);
			$data_narration = $this->input->post('entry_narration', TRUE);
			$data_tag = $this->input->post('entry_tag', TRUE);
			if ($data_tag < 1)
				$data_tag = NULL;
			$data_type = $entry_type_id;
			$data_date = date_php_to_mysql($data_date); // Converting date to MySQL
			$entry_id = NULL;

			$this->db->trans_start();
			$insert_data = array(
				'number' => $data_number,
				'date' => $data_date,
				'narration' => $data_narration,
				'entry_type' => $data_type,
				'tag_id' => $data_tag,
				'sc_id' => $this->session->userdata('sc_id')
			);
			if ( ! $this->db->insert('entries', $insert_data))
			{
				$this->db->trans_rollback();
				$this->form_validation->_error_array[] = 'Error addding Entry.';
				$this->logger->write_message("error", "Error adding " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " since failed inserting entry");
				$this->load->view('account/entry/add', $data);
				return;
			} else {
				$entry_id = $this->db->insert_id();
			}

			/* Adding ledger accounts */
			$data_all_ledger_dc = $this->input->post('ledger_dc', TRUE);
			$data_all_ledger_id = $this->input->post('ledger_id', TRUE);
			$data_all_dr_amount = $this->input->post('dr_amount', TRUE);
			$data_all_cr_amount = $this->input->post('cr_amount', TRUE);

			$dr_total = 0;
			$cr_total = 0;
			foreach ($data_all_ledger_dc as $id => $ledger_data)
			{
				$data_ledger_dc = $data_all_ledger_dc[$id];
				$data_ledger_id = $data_all_ledger_id[$id];
				if ($data_ledger_id < 1)
					continue;
				$data_amount = 0;
				if ($data_all_ledger_dc[$id] == "D")
				{
					$data_amount = $data_all_dr_amount[$id];
					$dr_total = float_ops($data_all_dr_amount[$id], $dr_total, '+');
				} else {
					$data_amount = $data_all_cr_amount[$id];
					$cr_total = float_ops($data_all_cr_amount[$id], $cr_total, '+');
				}
				$insert_ledger_data = array(
					'entry_id' => $entry_id,
					'ledger_id' => $data_ledger_id,
					'amount' => $data_amount,
					'dc' => $data_ledger_dc,
				);
				if ( ! $this->db->insert('entry_items', $insert_ledger_data))
				{
					$this->db->trans_rollback();
					$this->form_validation->_error_array[] = 'Error adding Ledger account - ' . $data_ledger_id . ' to Entry.';
					$this->logger->write_message("error", "Error adding " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " since failed inserting entry ledger item " . "[id:" . $data_ledger_id . "]");
					$this->load->view('account/entry/add', $data);
					return;
				}
			}

			/* Updating Debit and Credit Total in entries table */
			$update_data = array(
				'dr_total' => $dr_total,
				'cr_total' => $cr_total,
			);
			if ( ! $this->db->where('id', $entry_id)->update('entries', $update_data))
			{
				$this->db->trans_rollback();
				$this->form_validation->_error_array[] = 'Error updating Entry total.';
				$this->logger->write_message("error", "Error adding " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " since failed updating debit and credit total");
				$this->load->view('account/entry/add', $data);
				return;
			}

			/* Success */
			$this->db->trans_complete();

			$this->session->set_userdata('entry_added_show_action', TRUE);
			$this->session->set_userdata('entry_added_id', $entry_id);
			$this->session->set_userdata('entry_added_type_id', $entry_type_id);
			$this->session->set_userdata('entry_added_type_label', $current_entry_type['label']);
			$this->session->set_userdata('entry_added_type_name', $current_entry_type['name']);
			$this->session->set_userdata('entry_added_number', $data_number);

			/* Showing success message in show() method since message is too long for storing it in session */
			$this->logger->write_message("success", "Added " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " [id:" . $entry_id . "]");
			redirect('account/entry/show/' . $current_entry_type['label']);
			$this->load->view('account/entry/add', $data);
			return;
		}
		return;
	}

	function edit($entry_type, $entry_id = 0)
	{
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   			
		/* Check access */
		if ( ! check_access('edit entry'))
		{
			$this->session->set_flashdata('warning','Permission denied.');
			redirect('account/entry/show/' . $entry_type);
			return;
		}

		/* Check for account lock */
		if ($this->config->item('account_locked') == 1)
		{
			$this->session->set_flashdata('custom_error','Account is locked.');
			redirect('account/entry/show/' . $entry_type);
			return;
		}

		/* Entry Type */
		$entry_type_id = entry_type_name_to_id($entry_type);
		if ( ! $entry_type_id)
		{
			$this->session->set_flashdata('custom_error','Invalid Entry type.');
			redirect('account/entry/show/all');
			return;
		} else {
			$current_entry_type = entry_type_info($entry_type_id);
		}

		//$this->template->set('page_title', 'Edit ' . $current_entry_type['name'] . ' Entry');

		/* Load current entry details */
		if ( ! $cur_entry = $this->Entry_model->get_entry($entry_id, $entry_type_id))
		{
			$this->session->set_flashdata('custom_error','Invalid Entry.');
			redirect('account/entry/show/' . $current_entry_type['label']);
			return;
		}
		if($cur_entry->sc_id!=$this->session->userdata('sc_id')){
			$this->session->set_flashdata('custom_error','Invalid Entry.');
			redirect('account/entry/show/' . $current_entry_type['label']);
			return;
		}

		/* Form fields - Entry */
		$data['entry_number'] = array(
			'name' => 'entry_number',
			'id' => 'entry_number',
			'maxlength' => '11',
			'size' => '11',
			'value' => $cur_entry->number,
			'class' =>'text-input'
		);
		$data['entry_date'] = array(
			'name' => 'entry_date',
			'id' => 'entry_date',
			'maxlength' => '11',
			'size' => '11',
			'value' => date_mysql_to_php($cur_entry->date),
			'class' =>'text-input datepicker-restrict',
			'style' => 'width:80%'
		);
		$data['entry_narration'] = array(
			'name' => 'entry_narration',
			'id' => 'entry_narration',
			'cols' => '50',
			'rows' => '4',
			'value' => $cur_entry->narration,
		);
		$data['entry_id'] = $entry_id;
		$data['entry_type_id'] = $entry_type_id;
		$data['current_entry_type'] = $current_entry_type;
		$data['entry_tag'] = $cur_entry->tag_id;
		$data['entry_tags'] = $this->Tag_model->get_all_tags();
		$data['has_reconciliation'] = FALSE;
		$data['sc_id'] = $cur_entry->sc_id;

		/* Load current ledger details if not $_POST */
		if ( ! $_POST)
		{
			$this->db->from('entry_items')->where('entry_id', $entry_id);
			$cur_ledgers_q = $this->db->get();
			if ($cur_ledgers_q->num_rows <= 0)
			{
				$this->session->set_flashdata('custom_error','No Ledger accounts found!');
			}
			$counter = 0;
			foreach ($cur_ledgers_q->result() as $row)
			{
				$data['ledger_dc'][$counter] = $row->dc;
				$data['ledger_id'][$counter] = $row->ledger_id;
				if ($row->dc == "D")
				{
					$data['dr_amount'][$counter] = $row->amount;
					$data['cr_amount'][$counter] = "";
				} else {
					$data['dr_amount'][$counter] = "";
					$data['cr_amount'][$counter] = $row->amount;
				}
				if ($row->reconciliation_date)
					$data['has_reconciliation'] = TRUE;
				$counter++;
			}
			/* Two extra rows */
			$data['ledger_dc'][$counter] = 'D';
			$data['ledger_id'][$counter] = 0;
			$data['dr_amount'][$counter] = "";
			$data['cr_amount'][$counter] = "";
			$counter++;
			$data['ledger_dc'][$counter] = 'D';
			$data['ledger_id'][$counter] = 0;
			$data['dr_amount'][$counter] = "";
			$data['cr_amount'][$counter] = "";
			$counter++;
		}

		/* Form validations */
		if ($current_entry_type['numbering'] == '3')
			$this->form_validation->set_rules('entry_number', 'Entry Number', 'trim|is_natural_no_zero|uniqueentrynowithid[' . $entry_type_id . '.' . $entry_id . ']');
		else
			$this->form_validation->set_rules('entry_number', 'Entry Number', 'trim|required|is_natural_no_zero|uniqueentrynowithid[' . $entry_type_id . '.' . $entry_id . ']');
		$this->form_validation->set_rules('entry_date', 'Entry Date', 'trim|required|is_date|is_date_within_range');
		$this->form_validation->set_rules('entry_narration', 'trim');
		$this->form_validation->set_rules('entry_tag', 'Tag', 'trim|is_natural');

		/* Debit and Credit amount validation */
		if ($_POST)
		{
			foreach ($this->input->post('ledger_dc', TRUE) as $id => $ledger_data)
			{
				$this->form_validation->set_rules('dr_amount[' . $id . ']', 'Debit Amount', 'trim|currency');
				$this->form_validation->set_rules('cr_amount[' . $id . ']', 'Credit Amount', 'trim|currency');
			}
		}

		/* Repopulating form */
		if ($_POST)
		{
			$data['entry_number']['value'] = $this->input->post('entry_number', TRUE);
			$data['entry_date']['value'] = $this->input->post('entry_date', TRUE);
			$data['entry_narration']['value'] = $this->input->post('entry_narration', TRUE);
			$data['entry_tag'] = $this->input->post('entry_tag', TRUE);
			$data['has_reconciliation'] = $this->input->post('has_reconciliation', TRUE);

			$data['ledger_dc'] = $this->input->post('ledger_dc', TRUE);
			$data['ledger_id'] = $this->input->post('ledger_id', TRUE);
			$data['dr_amount'] = $this->input->post('dr_amount', TRUE);
			$data['cr_amount'] = $this->input->post('cr_amount', TRUE);
		}

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('warning',validation_errors());
			$this->load->view('account/entry/edit', $data);
		} else	{
			/* Checking for Valid Ledgers account and Debit and Credit Total */
			$data_all_ledger_id = $this->input->post('ledger_id', TRUE);
			$data_all_ledger_dc = $this->input->post('ledger_dc', TRUE);
			$data_all_dr_amount = $this->input->post('dr_amount', TRUE);
			$data_all_cr_amount = $this->input->post('cr_amount', TRUE);
			$dr_total = 0;
			$cr_total = 0;
			$bank_cash_present = FALSE; /* Whether atleast one Ledger account is Bank or Cash account */
			$non_bank_cash_present = FALSE;  /* Whether atleast one Ledger account is NOT a Bank or Cash account */
			foreach ($data_all_ledger_dc as $id => $ledger_data)
			{
				if ($data_all_ledger_id[$id] < 1)
					continue;

				/* Check for valid ledger id */
				$this->db->from('ledgers')->where('id', $data_all_ledger_id[$id]);
				$valid_ledger_q = $this->db->get();
				if ($valid_ledger_q->num_rows() < 1)
				{
					$this->session->set_flashdata('custom_error','Invalid Ledger account.');
					$this->load->view('account/entry/edit', $data);
					return;
				} else {
					/* Check for valid ledger type */
					$valid_ledger = $valid_ledger_q->row();
					if ($current_entry_type['bank_cash_ledger_restriction'] == '2')
					{
						if ($data_all_ledger_dc[$id] == 'D' && $valid_ledger->type == 1)
						{
							$bank_cash_present = TRUE;
						}
						if ($valid_ledger->type != 1)
							$non_bank_cash_present = TRUE;
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '3')
					{
						if ($data_all_ledger_dc[$id] == 'C' && $valid_ledger->type == 1)
						{
							$bank_cash_present = TRUE;
						}
						if ($valid_ledger->type != 1)
							$non_bank_cash_present = TRUE;
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '4')
					{
						if ($valid_ledger->type != 1)
						{
							$this->form_validation->_error_array[] = 'Invalid Ledger account. ' . $current_entry_type['name'] . ' Entries can have only Bank and Cash Ledgers accounts.';
							$this->load->view('account/entry/edit', $data);
							return;
						}
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '5')
					{
						if ($valid_ledger->type == 1)
						{
							$this->form_validation->_error_array[] = 'Invalid Ledger account. ' . $current_entry_type['name'] . ' Entries cannot have Bank and Cash Ledgers accounts.';
							$this->load('account/entry/edit', $data);
							return;
						}
					}
				}
				if ($data_all_ledger_dc[$id] == "D")
				{
					$dr_total = float_ops($data_all_dr_amount[$id], $dr_total, '+');
				} else {
					$cr_total = float_ops($data_all_cr_amount[$id], $cr_total, '+');
				}
			}
			if (float_ops($dr_total, $cr_total, '!='))
			{
				$this->form_validation->_error_array[] = 'Debit and Credit Total does not match!';
				$this->load->view('account/entry/edit', $data);
				return;
			} else if (float_ops($dr_total, 0, '==') || float_ops($cr_total, 0, '==')) {
				$this->form_validation->_error_array[] = 'Cannot save empty Entry.';
				$this->load->view('account/entry/edit', $data);
				return;
			}
			/* Check if atleast one Bank or Cash Ledger account is present */
			if ($current_entry_type['bank_cash_ledger_restriction'] == '2')
			{
				if ( ! $bank_cash_present)
				{
					$this->form_validation->_error_array[] = 'Need to Debit atleast one Bank or Cash account.';
					$this->load->view('account/entry/edit', $data);
					return;
				}
				if ( ! $non_bank_cash_present)
				{
					$this->form_validation->_error_array[] = 'Need to Debit or Credit atleast one NON - Bank or Cash account.';
					$this->load->view('account/entry/edit', $data);
					return;
				}
			} else if ($current_entry_type['bank_cash_ledger_restriction'] == '3')
			{
				if ( ! $bank_cash_present)
				{
					$this->form_validation->_error_array[] = 'Need to Credit atleast one Bank or Cash account.';
					$this->load->view('account/entry/edit', $data);
					return;
				}
				if ( ! $non_bank_cash_present)
				{
					$this->form_validation->_error_array[] = 'Need to Debit or Credit atleast one NON - Bank or Cash account.';
					$this->load->view('account/entry/edit', $data);
					return;
				}
			}

			/* Updating main entry */
			if ($current_entry_type['numbering'] == '3') {
				$data_number = $this->input->post('entry_number', TRUE);
				if ( ! $data_number)
					$data_number = NULL;
			} else {
				$data_number = $this->input->post('entry_number', TRUE);
			}

			$data_date = $this->input->post('entry_date', TRUE);
			$data_narration = $this->input->post('entry_narration', TRUE);
			$data_tag = $this->input->post('entry_tag', TRUE);
			if ($data_tag < 1)
				$data_tag = NULL;
			$data_type = $entry_type_id;
			$data_date = date_php_to_mysql($data_date); // Converting date to MySQL
			$data_has_reconciliation = $this->input->post('has_reconciliation', TRUE);
			$sc_id = $this->input->post('sc_id', TRUE);
			
			$this->db->trans_start();
			$update_data = array(
				'number' => $data_number,
				'date' => $data_date,
				'narration' => $data_narration,
				'tag_id' => $data_tag,
				'sc_id' =>$sc_id
			);
			if ( ! $this->db->where('id', $entry_id)->update('entries', $update_data))
			{
				$this->db->trans_rollback();
				$this->form_validation->_error_array[] = 'Error updating Entry account.';
				$this->logger->write_message("error", "Error updating entry details for " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " [id:" . $entry_id . "]");
				$this->load->view('account/entry/edit', $data);
				return;
			}

			/* TODO : Deleting all old ledger data, Bad solution */
			if ( ! $this->db->delete('entry_items', array('entry_id' => $entry_id)))
			{
				$this->db->trans_rollback();
				$this->form_validation->_error_array[] = 'Error deleting previous Ledger accounts from Entry.';
				$this->logger->write_message("error", "Error deleting previous entry items for " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " [id:" . $entry_id . "]");
				$this->load->view('account/entry/edit', $data);
				return;
			}
			
			/* Adding ledger accounts */
			$data_all_ledger_dc = $this->input->post('ledger_dc', TRUE);
			$data_all_ledger_id = $this->input->post('ledger_id', TRUE);
			$data_all_dr_amount = $this->input->post('dr_amount', TRUE);
			$data_all_cr_amount = $this->input->post('cr_amount', TRUE);

			$dr_total = 0;
			$cr_total = 0;
			foreach ($data_all_ledger_dc as $id => $ledger_data)
			{
				$data_ledger_dc = $data_all_ledger_dc[$id];
				$data_ledger_id = $data_all_ledger_id[$id];
				if ($data_ledger_id < 1)
					continue;
				$data_amount = 0;
				if ($data_all_ledger_dc[$id] == "D")
				{
					$data_amount = $data_all_dr_amount[$id];
					$dr_total = float_ops($data_all_dr_amount[$id], $dr_total, '+');
				} else {
					$data_amount = $data_all_cr_amount[$id];
					$cr_total = float_ops($data_all_cr_amount[$id], $cr_total, '+');
				}

				$insert_ledger_data = array(
					'entry_id' => $entry_id,
					'ledger_id' => $data_ledger_id,
					'amount' => $data_amount,
					'dc' => $data_ledger_dc,
				);
				if ( ! $this->db->insert('entry_items', $insert_ledger_data))
				{
					$this->db->trans_rollback();
					$this->form_validation->_error_array[] = 'Error adding Ledger account - ' . $data_ledger_id . ' to Entry.';
					$this->logger->write_message("error", "Error adding Ledger account item [id:" . $data_ledger_id . "] for " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " [id:" . $entry_id . "]");
					$this->load->view('account/entry/edit', $data);
					return;
				}
			}

			/* Updating Debit and Credit Total in entries table */
			$update_data = array(
				'dr_total' => $dr_total,
				'cr_total' => $cr_total,
			);
			if ( ! $this->db->where('id', $entry_id)->update('entries', $update_data))
			{
				$this->db->trans_rollback();
				$this->form_validation->_error_array[] = 'Error updating Entry total.';
				$this->logger->write_message("error", "Error updating entry total for " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " [id:" . $entry_id . "]");
				$this->load->view('account/entry/edit', $data);
				return;
			}

			/* Success */
			$this->db->trans_complete();

			$this->session->set_userdata('entry_updated_show_action', TRUE);
			$this->session->set_userdata('entry_updated_id', $entry_id);
			$this->session->set_userdata('entry_updated_type_id', $entry_type_id);
			$this->session->set_userdata('entry_updated_type_label', $current_entry_type['label']);
			$this->session->set_userdata('entry_updated_type_name', $current_entry_type['name']);
			$this->session->set_userdata('entry_updated_number', $data_number);
			if ($data_has_reconciliation)
				$this->session->set_userdata('entry_updated_has_reconciliation', TRUE);
			else
				$this->session->set_userdata('entry_updated_has_reconciliation', FALSE);

			/* Showing success message in show() method since message is too long for storing it in session */
			$this->logger->write_message("success", "Updated " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " [id:" . $entry_id . "]");

			redirect('account/entry/show/' . $current_entry_type['label'],'refresh');
			return;
		}
		return;
	}

	function delete($entry_type, $entry_id = 0)
	{
		/* Check access */
		if ( ! check_access('delete entry'))
		{
			$this->session->set_flashdata('custom_error','Permission denied.');
			redirect('account/entry/show/' . $entry_type);
			return;
		}

		/* Check for account lock */
		if ($this->config->item('account_locked') == 1)
		{
			$this->session->set_flashdata('custom_error','Account is locked.');
			redirect('account/entry/show/' . $entry_type);
			return;
		}

		/* Entry Type */
		$entry_type_id = entry_type_name_to_id($entry_type);
		if ( ! $entry_type_id)
		{
			$this->session->set_flashdata('custom_error','Invalid Entry type.');
			redirect('account/entry/show/all');
			return;
		} else {
			$current_entry_type = entry_type_info($entry_type_id);
		}

		/* Load current entry details */
		if ( ! $cur_entry = $this->Entry_model->get_entry($entry_id, $entry_type_id))
		{
			$this->session->set_flashdata('custom_error','Invalid Entry.');
			redirect('account/entry/show/' . $current_entry_type['label']);
			return;
		}

		$this->db->trans_start();
		if ( ! $this->db->delete('entry_items', array('entry_id' => $entry_id)))
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('custom_error','Error deleting Entry - Ledger accounts.');
			$this->logger->write_message("error", "Error deleting ledger entries for " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $cur_entry->number) . " [id:" . $entry_id . "]");
			redirect('account/entry/view/' . $current_entry_type['label'] . '/' . $entry_id);
			return;
		}
		if ( ! $this->db->delete('entries', array('id' => $entry_id)))
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('custom_error','Error deleting Entry entry.');
			$this->logger->write_message("error", "Error deleting Entry entry for " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $cur_entry->number) . " [id:" . $entry_id . "]");
			redirect('account/entry/view/' . $current_entry_type['label'] . '/' . $entry_id);
			return;
		}
		$this->db->trans_complete();
		$this->session->set_flashdata('success_delete','Deleted ' . $current_entry_type['name'] . ' Entry.');
		$this->logger->write_message("success", "Deleted " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $cur_entry->number) . " [id:" . $entry_id . "]");
		redirect('account/entry/show/' . $current_entry_type['label']);
		return;
	}

	function download($entry_type, $entry_id = 0)
	{
		$this->load->helper('download');
		$this->load->model('Setting_model');
		$this->load->model('Ledger_model');

		/* Check access */
		if ( ! check_access('download entry'))
		{
			$this->messages->add('Permission denied.', 'error');
			redirect('entry/show/' . $entry_type);
			return;
		}

		/* Entry Type */
		$entry_type_id = entry_type_name_to_id($entry_type);
		if ( ! $entry_type_id)
		{
			$this->messages->add('Invalid Entry type.', 'error');
			redirect('entry/show/all');
			return;
		} else {
			$current_entry_type = entry_type_info($entry_type_id);
		}

		/* Load current entry details */
		if ( ! $cur_entry = $this->Entry_model->get_entry($entry_id, $entry_type_id))
		{
			$this->messages->add('Invalid Entry.', 'error');
			redirect('entry/show/' . $current_entry_type['label']);
			return;
		}

		$data['entry_type_id'] = $entry_type_id;
		$data['current_entry_type'] = $current_entry_type;
		$data['entry_number'] =  $cur_entry->number;
		$data['entry_date'] = date_mysql_to_php_display($cur_entry->date);
		$data['entry_dr_total'] =  $cur_entry->dr_total;
		$data['entry_cr_total'] =  $cur_entry->cr_total;
		$data['entry_narration'] = $cur_entry->narration;

		/* Getting Ledger details */
		$this->db->from('entry_items')->where('entry_id', $entry_id)->order_by('dc', 'desc');
		$ledger_q = $this->db->get();
		$counter = 0;
		$data['ledger_data'] = array();
		if ($ledger_q->num_rows() > 0)
		{
			foreach ($ledger_q->result() as $row)
			{
				$data['ledger_data'][$counter] = array(
					'id' => $row->ledger_id,
					'name' => $this->Ledger_model->get_name($row->ledger_id),
					'dc' => $row->dc,
					'amount' => $row->amount,
				);
				$counter++;
			}
		}

		/* Download Entry */
		$file_name = $current_entry_type['name'] . '_entry_' . $cur_entry->number . ".xls";
		$download_data = $this->load->view('entry/downloadpreview', $data, TRUE);
		force_download($file_name, $download_data);
		return;
	}

	function printpreview($entry_type, $entry_id = 0)
	{
		$this->load->model('Setting_model');
		$this->load->model('Ledger_model');

		/* Check access */
		if ( ! check_access('print entry'))
		{
			$this->session->set_flashdata('custom_error','Permission denied.');
			redirect('account/entry/show/' . $entry_type);
			return;
		}

		/* Entry Type */
		$entry_type_id = entry_type_name_to_id($entry_type);
		if ( ! $entry_type_id)
		{
			$this->session->set_flashdata('custom_error','Invalid Entry type.');
			redirect('account/entry/show/all');
			return;
		} else {
			$current_entry_type = entry_type_info($entry_type_id);
		}

		/* Load current entry details */
		if ( ! $cur_entry = $this->Entry_model->get_entry($entry_id, $entry_type_id))
		{
			$this->session->set_flashdata('custom_error','Invalid Entry.');
			redirect('account/entry/show/' . $current_entry_type['label']);
			return;
		}

		$data['entry_type_id'] = $entry_type_id;
		$data['current_entry_type'] = $current_entry_type;
		$data['entry_number'] =  $cur_entry->number;
		$data['entry_date'] = date_mysql_to_php_display($cur_entry->date);
		$data['entry_dr_total'] =  $cur_entry->dr_total;
		$data['entry_cr_total'] =  $cur_entry->cr_total;
		$data['entry_narration'] = $cur_entry->narration;

		/* Getting Ledger details */
		$this->db->from('entry_items')->where('entry_id', $entry_id)->order_by('dc', 'desc');
		$ledger_q = $this->db->get();
		$counter = 0;
		$data['ledger_data'] = array();
		if ($ledger_q->num_rows() > 0)
		{
			foreach ($ledger_q->result() as $row)
			{
				$data['ledger_data'][$counter] = array(
					'id' => $row->ledger_id,
					'name' => $this->Ledger_model->get_name($row->ledger_id),
					'dc' => $row->dc,
					'amount' => $row->amount,
				);
				$counter++;
			}
		}

		$this->load->view('entry/printpreview', $data);
		return;
	}

	function email($entry_type, $entry_id = 0)
	{
		$this->load->model('Setting_model');
		$this->load->model('Ledger_model');
		$this->load->library('email');

		/* Check access */
		if ( ! check_access('email entry'))
		{
			$this->messages->add('Permission denied.', 'error');
			redirect('entry/show/' . $entry_type);
			return;
		}

		/* Entry Type */
		$entry_type_id = entry_type_name_to_id($entry_type);
		if ( ! $entry_type_id)
		{
			$this->messages->add('Invalid Entry type.', 'error');
			redirect('entry/show/all');
			return;
		} else {
			$current_entry_type = entry_type_info($entry_type_id);
		}



		/* Load current entry details */
		if ( ! $cur_entry = $this->Entry_model->get_entry($entry_id, $entry_type_id))
		{
			$this->session->set_flashdata('custom_warning','Invalid Entry.');
			redirect('entry/show/' . $current_entry_type['label']);
			return;
		}

		$data['entry_type_id'] = $entry_type_id;
		$data['current_entry_type'] = $current_entry_type;
		$data['entry_id'] = $entry_id;
		$data['entry_number'] = $cur_entry->number;
		$data['email_to'] = array(
			'name' => 'email_to',
			'id' => 'email_to',
			'rows'=>'2',
			'cols' => '10',
			'class'=>'text-input',
			'value'=>''
		);

		/* Form validations */
		$this->form_validation->set_rules('email_to', 'Email to', 'trim|valid_emails|required');

		/* Repopulating form */
		if ($_POST)
		{
			$data['email_to']['value'] = $this->input->post('email_to', TRUE);
		}

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->model(array('reports/mdl_email_log'));
			$email_tags = $this->mdl_email_log->getTags();
			$data['email_tags'] = $email_tags;
			$data['error'] = validation_errors();
			$this->load->view('entry/email', $data);
			return;
		}
		else
		{
			$entry_data['entry_type_id'] = $entry_type_id;
			$entry_data['current_entry_type'] = $current_entry_type;
			$entry_data['entry_number'] =  $cur_entry->number;
			$entry_data['entry_date'] = date_mysql_to_php_display($cur_entry->date);
			$entry_data['entry_dr_total'] =  $cur_entry->dr_total;
			$entry_data['entry_cr_total'] =  $cur_entry->cr_total;
			$entry_data['entry_narration'] = $cur_entry->narration;
	
			/* Getting Ledger details */
			$this->db->from('entry_items')->where('entry_id', $entry_id)->order_by('dc', 'desc');
			$ledger_q = $this->db->get();
			$counter = 0;
			$entry_data['ledger_data'] = array();
			if ($ledger_q->num_rows() > 0)
			{
				foreach ($ledger_q->result() as $row)
				{
					$entry_data['ledger_data'][$counter] = array(
						'id' => $row->ledger_id,
						'name' => $this->Ledger_model->get_name($row->ledger_id),
						'dc' => $row->dc,
						'amount' => $row->amount,
					);
					$counter++;
				}
			}

			/* Preparing message */
			$message = $this->load->view('entry/emailpreview', $entry_data, TRUE);
			//$this->load->helper('mailer/phpmailer');

			/* Getting email configuration */
			$this->load->library('email');
			
			//echo '<pre>';
			//print_r($this->email);
			//die();

			$email_to = $this->input->post('email_to');
			$arr = explode(",",$email_to);
			$to ='';
			$arr = array_filter($arr);
			
			/* Sending email */
			$this->email->from($this->email->smtp_user, 'TELECARE');
			//$this->email->to($this->input->post('email_to', TRUE));
			$this->email->subject($current_entry_type['name'] . ' Entry No. ' . full_entry_number($entry_type_id, $cur_entry->number));
			//$this->email->subject('test');
			$this->email->message($message);
			$i= 0;
			$j=0;
			$this->load->model(array('reports/mdl_email_log'));
			$this->load->library('user_agent');
			$error = array('type'=>'error','message'=>$this->lang->line('email_could_not_be_sent_successfully'));
					
			foreach($arr as $to){
				$this->email->to($to);
				if($this->email->send()){
					$error = array('type'=>'success','message'=>$this->lang->line('email_has_been_sent_successfully'));
					
					$email_data = array(
										'email_receipient'=>$to,
										'email_sender_session'=>$this->session->userdata('session_id'),
										'email_report_type'=>$current_entry_type['name'],
										'email_sent_by'=>$this->session->userdata('user_id'),
										'user_agent'	=> $this->agent->agent_string(),
										'user_ip'=>$this->input->ip_address(),
										'email_sent_ts'=>date('Y-m-d H:i:s')
					);
					$this->mdl_email_log->save($email_data);
					$i++;
				}else{
					$this->logger->write_message("error", "Error emailing " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $cur_entry->number) . " [id:" . $entry_id . "]");
					$error = array('type'=>'error','message'=>$this->lang->line('email_could_not_be_sent_successfully').$this->email->print_debugger());
					$j++;
				}
			}
			/*if ($this->email->send())
			{
				$data['message'] = "Email sent.";
				$error = array('type'=>'success','message'=>$this->lang->line('email_has_been_sent_successfully').$msg);
				
				$this->logger->write_message("success", "Emailed " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $cur_entry->number) . " [id:" . $entry_id . "]");
			} else {
				$data['error'] = "Error sending email. Check you email settings.";
				$error = array('type'=>'error','message'=>$this->lang->line('email_could_not_be_sent_successfully'));
				
				$this->logger->write_message("error", "Error emailing " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $cur_entry->number) . " [id:" . $entry_id . "]");
			}*/
			$this->load->view('dashboard/ajax_messages',$error);
		}
		return;
	}

	function addrow($add_type = 'all')
	{
		$i = time() + rand  (0, time()) + rand  (0, time()) + rand  (0, time());
		$dr_amount = array(
			'name' => 'dr_amount[' . $i . ']',
			'id' => 'dr_amount[' . $i . ']',
			'maxlength' => '15',
			'size' => '15',
			'value' => '',
			'class' => 'dr-item text-input',
			'disabled' => 'disabled',
		);
		$cr_amount = array(
			'name' => 'cr_amount[' . $i . ']',
			'id' => 'cr_amount[' . $i . ']',
			'maxlength' => '15',
			'size' => '15',
			'value' => '',
			'class' => 'cr-item text-input',
			'disabled' => 'disabled',
		);

		echo '<tr class="new-row">';
		echo '<td>';
		echo form_dropdown_dc('ledger_dc[' . $i . ']');
		echo '</td>';

		echo '<td>';
		if ($add_type == 'bankcash')
			echo form_input_ledger('ledger_id[' . $i . ']', 0, '', $type = 'bankcash');
		else if ($add_type == 'nobankcash')
			echo form_input_ledger('ledger_id[' . $i . ']', 0, '', $type = 'nobankcash');
		else
			echo form_input_ledger('ledger_id[' . $i . ']');
		echo '</td>';

		echo '<td>';
		echo form_input($dr_amount);
		echo '</td>';
		echo '<td>';
		echo form_input($cr_amount);
		echo '</td>';
		echo '<td>';
		echo img(array('src' => asset_url() . "style/img/icons/add_1.png", 'border' => '0', 'alt' => 'Add Ledger', 'class' => 'addrow'));
		echo '</td>';
		echo '<td>';
		echo img(array('src' => asset_url() . "style/img/icons/delete.png", 'border' => '0', 'alt' => 'Remove Ledger', 'class' => 'deleterow'));
		echo '</td>';
		echo '<td class="ledger-balance"><div></div>';
		echo '</td>';
		echo '</tr>';
		return;
	}
function print_bill(){
		$error = array();				
		$this->form_validation->set_rules('entry_date', 'Entry Date', 'trim|required|is_date|is_date_within_range');		
		if ($_POST){
				foreach ($this->input->post('ledger_dc', TRUE) as $id => $ledger_data)
				{
					$this->form_validation->set_rules('dr_amount[' . $id . ']', 'Debit Amount', 'trim|currency');
					$this->form_validation->set_rules('cr_amount[' . $id . ']', 'Credit Amount', 'trim|currency');
				}
			}
		if ($this->form_validation->run() == FALSE)
		{
			$error['error'] = true;
			$error['message'] = 'Empty values can not be saved';
			$this->session->set_flashdata('success_save',$error['message']);
		}else{
			$temp = $_POST; 
			$sales_id = $this->input->post('sales_id');
			$entry_type = 'journal';
			$error_j = $this->_add_bill($entry_type);
			if($error_j['error']==true){
				$this->session->set_flashdata('custom_warning',$error_j['message']);
				redirect('sales/sale/'.$sales_id);
			}
			unset($_POST);
			$_POST['entry_date'] = date('d/m/Y');
			if($this->input->post('in_warranty')==0){
					$entry_type = 'receipt';
					$_POST['ledger_id'][0] = $temp['cash_ledger_id'];
					$_POST['dr_amount'][0] = $temp['dr_amount'][0];
					$_POST['ledger_dc'][0] = "D";
					
					$_POST['ledger_id'][1] = $temp['ledger_id'][0];
					$_POST['ledger_dc'][1] = "C";
					$_POST['cr_amount'][1] = $temp['dr_amount'][0];
				
				$error_r = $this->_add_bill($entry_type);
				if($error_r['error']==true){
					$this->session->set_flashdata('custom_warning',$error_r['message']);
					redirect('sales/sale/'.$sales_id);
				}
			}else{
				$error_r['error'] = false;
			}
			//for discount
			if($temp['dis_dr_amount']>0){
					
					$dis_ledger_id = $temp['dis_ledger_id'];
					$dis_ledger_dc = $temp['dis_ledger_dc'];
					$dis_dr_amount = $temp['dis_dr_amount'];
					
					$party_ledger_id = $temp['ledger_id'][0];
					$party_cr_amt = $dis_dr_amount;
					
					$_POST['ledger_id'][0] = $dis_ledger_id;
					$_POST['ledger_dc'][0] = $dis_ledger_dc;
					$_POST['dr_amount'][0] = $dis_dr_amount;
					
					$_POST['ledger_id'][1] = $party_ledger_id;
					$_POST['ledger_dc'][1] = "C";
					$_POST['cr_amount'][1] = $party_cr_amt;
					$this->_add_bill('journal');
			}
			
			if($error_j['error']==false && $error_r['error']==false){
				//call stored procedure
				$result = $this->db->query("call sp_create_bill($sales_id,".$this->session->userdata('user_id').")");
				$result->free_result();	
				$this->session->set_flashdata('success_save','Bill generated successfully');
			}else{
				$this->session->set_flashdata('success_save','Error');
			}
		}
		redirect('sales/sale/'.$sales_id);
		
	}
	function _add_bill($entry_type){
		
		$error = array(
						'error'=>'false',
						'message'=>''
		);
		if($entry_type=='journal' || $entry_type=='receipt'){
			/* Entry Type */
			$entry_type_id = entry_type_name_to_id($entry_type);
			$current_entry_type = entry_type_info($entry_type_id);
					
			/* Checking for Valid Ledgers account and Debit and Credit Total */
			$data_all_ledger_id = $this->input->post('ledger_id', TRUE);
			$data_all_ledger_dc = $this->input->post('ledger_dc', TRUE);
			$data_all_dr_amount = $this->input->post('dr_amount', TRUE);
			$data_all_cr_amount = $this->input->post('cr_amount', TRUE);
			$dr_total = 0;
			$cr_total = 0;
			$bank_cash_present = FALSE; /* Whether atleast one Ledger account is Bank or Cash account */
			$non_bank_cash_present = FALSE;  /* Whether atleast one Ledger account is NOT a Bank or Cash account */
			foreach ($data_all_ledger_dc as $id => $ledger_data)
			{
				if ($data_all_ledger_id[$id] < 1)
					continue;

				/* Check for valid ledger id */
				$this->db->from('ledgers')->where('id', $data_all_ledger_id[$id])->where('(sc_id='.$this->session->userdata('sc_id').')');
				$valid_ledger_q = $this->db->get();
				if ($valid_ledger_q->num_rows() < 1)
				{
					$error['error'] = true;
					$error['message'] = 'Invalid Ledger account.';
					return $error;
				} else {
					/* Check for valid ledger type */
					$valid_ledger = $valid_ledger_q->row();
					if ($current_entry_type['bank_cash_ledger_restriction'] == '2')
					{
						if ($data_all_ledger_dc[$id] == 'D' && $valid_ledger->type == 1)
						{
							$bank_cash_present = TRUE;
						}
						if ($valid_ledger->type != 1)
							$non_bank_cash_present = TRUE;
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '3')
					{
						if ($data_all_ledger_dc[$id] == 'C' && $valid_ledger->type == 1)
						{
							$bank_cash_present = TRUE;
						}
						if ($valid_ledger->type != 1)
							$non_bank_cash_present = TRUE;
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '4')
					{
						if ($valid_ledger->type != 1)
						{
							$error['error'] = true;
							$error['message'] = 'Invalid Ledger account. ' . $current_entry_type['name'] . ' Entries can have only Bank and Cash Ledgers accounts.';
							return $error;
						}
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '5')
					{
						if ($valid_ledger->type == 1)
						{
							$error['error'] = true;
							$error['message'] = 'Invalid Ledger account. ' . $current_entry_type['name'] . ' Entries cannot have Bank and Cash Ledgers accounts.';
							return $error;
						}
					}
				}

				if ($data_all_ledger_dc[$id] == "D")
				{
					$dr_total = float_ops($data_all_dr_amount[$id], $dr_total, '+');
				} else {
					$cr_total = float_ops($data_all_cr_amount[$id], $cr_total, '+');
				}
			}
			if (float_ops($dr_total, $cr_total, '!='))
			{
				$error['error'] = true;
				$error['message'] = 'Debit and Credit Total does not match!';
				return $error;
			} else if (float_ops($dr_total, 0, '==') && float_ops($cr_total, 0, '==')) {
				$error['message'] = 'Cannot save empty Entry.';
				return $error;
			}
			/* Check if atleast one Bank or Cash Ledger account is present */
			if ($current_entry_type['bank_cash_ledger_restriction'] == '2')
			{
				if ( ! $bank_cash_present)
				{
					$error['error'] = true;
					$error['message'] = 'Need to Debit atleast one Bank or Cash account.';
					return $error;
				}
				if ( ! $non_bank_cash_present)
				{
					$error['error'] =true;
					$error['message'] = 'Need to Debit or Credit atleast one NON - Bank or Cash account.';
					return $error;
				}
			} else if ($current_entry_type['bank_cash_ledger_restriction'] == '3')
			{
				if ( ! $bank_cash_present)
				{
					$error['error'] = true;
					$error['message'] = 'Need to Credit atleast one Bank or Cash account.';
					return $error;
				}
				if ( ! $non_bank_cash_present)
				{
					$error['error'] = true;
					$error['message'] = 'Need to Debit or Credit atleast one NON - Bank or Cash account.';
					return $error;
				}
			}

			/* Adding main entry */
			if ($current_entry_type['numbering'] == '2')
			{
				$data_number = $this->input->post('entry_number', TRUE);
			} else if ($current_entry_type['numbering'] == '3') {
				$data_number = $this->input->post('entry_number', TRUE);
				if ( ! $data_number)
					$data_number = NULL;
			} else {
				if ($this->input->post('entry_number', TRUE))
					$data_number = $this->input->post('entry_number', TRUE);
				else
					$data_number = $this->Entry_model->next_entry_number($entry_type_id);
			}

			$data_date = $this->input->post('entry_date', TRUE);
			$data_narration = $this->input->post('entry_narration', TRUE);
			$data_tag = $this->input->post('entry_tag', TRUE);
			if ($data_tag < 1)
				$data_tag = NULL;
			$data_type = $entry_type_id;
			$data_date = date_php_to_mysql($data_date); // Converting date to MySQL
			$entry_id = NULL;

			$this->db->trans_start();
			$insert_data = array(
				'number' => $data_number,
				'date' => $data_date,
				'narration' => $data_narration,
				'entry_type' => $data_type,
				'tag_id' => $data_tag,
				'sc_id' => $this->session->userdata('sc_id')
			);
			if ( ! $this->db->insert('entries', $insert_data))
			{
				$this->db->trans_rollback();
				$error['error'] = true;
				$error['message'] = 'Error addding Entry.';
				$this->logger->write_message("error", "Error adding " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " since failed inserting entry");
				return $error;
			} else {
				$entry_id = $this->db->insert_id();
			}

			/* Adding ledger accounts */
			$data_all_ledger_dc = $this->input->post('ledger_dc', TRUE);
			$data_all_ledger_id = $this->input->post('ledger_id', TRUE);
			$data_all_dr_amount = $this->input->post('dr_amount', TRUE);
			$data_all_cr_amount = $this->input->post('cr_amount', TRUE);

			$dr_total = 0;
			$cr_total = 0;
			foreach ($data_all_ledger_dc as $id => $ledger_data)
			{
				$data_ledger_dc = $data_all_ledger_dc[$id];
				$data_ledger_id = $data_all_ledger_id[$id];
				if ($data_ledger_id < 1)
					continue;
				$data_amount = 0;
				if ($data_all_ledger_dc[$id] == "D")
				{
					$data_amount = $data_all_dr_amount[$id];
					$dr_total = float_ops($data_all_dr_amount[$id], $dr_total, '+');
				} else {
					$data_amount = $data_all_cr_amount[$id];
					$cr_total = float_ops($data_all_cr_amount[$id], $cr_total, '+');
				}
				$insert_ledger_data = array(
					'entry_id' => $entry_id,
					'ledger_id' => $data_ledger_id,
					'amount' => $data_amount,
					'dc' => $data_ledger_dc,
				);
				if ( ! $this->db->insert('entry_items', $insert_ledger_data))
				{
					$this->db->trans_rollback();
					$error['error'] = true;
					$error['message'] = 'Error adding Ledger account - ' . $data_ledger_id . ' to Entry.';
					$this->logger->write_message("error", "Error adding " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " since failed inserting entry ledger item " . "[id:" . $data_ledger_id . "]");
					return $error;
				}
			}

			/* Updating Debit and Credit Total in entries table */
			$update_data = array(
				'dr_total' => $dr_total,
				'cr_total' => $cr_total,
			);
			if ( ! $this->db->where('id', $entry_id)->update('entries', $update_data))
			{
				$this->db->trans_rollback();
				$error['error'] = true;
				$error['message'] = 'Error updating Entry total.';
				$this->logger->write_message("error", "Error adding " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " since failed updating debit and credit total");
				return $error;
			}

			/* Success */
			$this->db->trans_complete();

			/* Showing success message in show() method since message is too long for storing it in session */
			$this->logger->write_message("success", "Added " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " [id:" . $entry_id . "]");
			$error['error'] = false;
			$error['message'] = $this->lang->line('bill_printed_successfully');
			return $error;		
		}else{
			$error['error'] = true;
			$error['message'] = 'Specify type';
			return $error;
		}
	}
function _check_account($entry_type){
		$temp = $_POST;
		$error = array(
						'error'=>'false',
						'message'=>''
		);
		if($entry_type=='journal' || $entry_type=='receipt'){
			/* Entry Type */
			$entry_type_id = entry_type_name_to_id($entry_type);
			$current_entry_type = entry_type_info($entry_type_id);
					
			/* Checking for Valid Ledgers account and Debit and Credit Total */
			$data_all_ledger_id = $this->input->post('ledger_id', TRUE);
			$data_all_ledger_dc = $this->input->post('ledger_dc', TRUE);
			$data_all_dr_amount = $this->input->post('dr_amount', TRUE);
			$data_all_cr_amount = $this->input->post('cr_amount', TRUE);
			$dr_total = 0;
			$cr_total = 0;
			$bank_cash_present = FALSE; /* Whether atleast one Ledger account is Bank or Cash account */
			$non_bank_cash_present = FALSE;  /* Whether atleast one Ledger account is NOT a Bank or Cash account */
			foreach ($data_all_ledger_dc as $id => $ledger_data)
			{
				if ($data_all_ledger_id[$id] < 1)
					continue;

				/* Check for valid ledger id */
				$this->db->from('ledgers')->where('id', $data_all_ledger_id[$id])->where('(sc_id='.$this->session->userdata('sc_id').')');
				$valid_ledger_q = $this->db->get();
				if ($valid_ledger_q->num_rows() < 1)
				{
					$error['error'] = true;
					$error['message'] = 'Invalid Ledger account.';
					return $error;
				} else {
					/* Check for valid ledger type */
					$valid_ledger = $valid_ledger_q->row();
					if ($current_entry_type['bank_cash_ledger_restriction'] == '2')
					{
						if ($data_all_ledger_dc[$id] == 'D' && $valid_ledger->type == 1)
						{
							$bank_cash_present = TRUE;
						}
						if ($valid_ledger->type != 1)
							$non_bank_cash_present = TRUE;
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '3')
					{
						if ($data_all_ledger_dc[$id] == 'C' && $valid_ledger->type == 1)
						{
							$bank_cash_present = TRUE;
						}
						if ($valid_ledger->type != 1)
							$non_bank_cash_present = TRUE;
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '4')
					{
						if ($valid_ledger->type != 1)
						{
							$error['error'] = true;
							$error['message'] = 'Invalid Ledger account. ' . $current_entry_type['name'] . ' Entries can have only Bank and Cash Ledgers accounts.';
							return $error;
						}
					} else if ($current_entry_type['bank_cash_ledger_restriction'] == '5')
					{
						if ($valid_ledger->type == 1)
						{
							$error['error'] = true;
							$error['message'] = 'Invalid Ledger account. ' . $current_entry_type['name'] . ' Entries cannot have Bank and Cash Ledgers accounts.';
							return $error;
						}
					}
				}

				if ($data_all_ledger_dc[$id] == "D")
				{
					$dr_total = float_ops($data_all_dr_amount[$id], $dr_total, '+');
				} else {
					$cr_total = float_ops($data_all_cr_amount[$id], $cr_total, '+');
				}
			}
			if (float_ops($dr_total, $cr_total, '!='))
			{
				$error['error'] = true;
				$error['message'] = 'Debit and Credit Total does not match!';
				return $error;
			} else if (float_ops($dr_total, 0, '==') && float_ops($cr_total, 0, '==')) {
				$error['error'] = true;
				$error['message'] = 'Cannot save empty Entry.';
				return $error;
			}
			if($this->input->post('reverse_entry')==1){
			
			}else{
			/* Check if atleast one Bank or Cash Ledger account is present */
				if ($current_entry_type['bank_cash_ledger_restriction'] == '2')
				{
					if ( ! $bank_cash_present)
					{
						$error['error'] = true;
						$error['message'] = 'Need to Debit atleast one Bank or Cash account.';
						return $error;
					}
					if ( ! $non_bank_cash_present)
					{
						$error['error'] =true;
						$error['message'] = 'Need to Debit or Credit atleast one NON - Bank or Cash account.';
						return $error;
					}
				} else if ($current_entry_type['bank_cash_ledger_restriction'] == '3')
				{
					if ( ! $bank_cash_present)
					{
						$error['error'] = true;
						$error['message'] = 'Need to Credit atleast one Bank or Cash account.';
						return $error;
					}
					if ( ! $non_bank_cash_present)
					{
						$error['error'] = true;
						$error['message'] = 'Need to Debit or Credit atleast one NON - Bank or Cash account.';
						return $error;
					}
				}
			}
			/* Adding main entry */
			if ($current_entry_type['numbering'] == '2')
			{
				$data_number = $this->input->post('entry_number', TRUE);
			} else if ($current_entry_type['numbering'] == '3') {
				$data_number = $this->input->post('entry_number', TRUE);
				if ( ! $data_number)
					$data_number = NULL;
			} else {
				if ($this->input->post('entry_number', TRUE))
					$data_number = $this->input->post('entry_number', TRUE);
				else
					$data_number = $this->Entry_model->next_entry_number($entry_type_id);
			}

			$data_date = $this->input->post('entry_date', TRUE);
			$data_narration = $this->input->post('entry_narration', TRUE);
			$data_tag = $this->input->post('entry_tag', TRUE);
			if ($data_tag < 1)
				$data_tag = NULL;
			$data_type = $entry_type_id;
			$data_date = date_php_to_mysql($data_date); // Converting date to MySQL
			$entry_id = NULL;

			//$this->db->trans_start();
			$insert_data = array(
				'number' => $data_number,
				'date' => $data_date,
				'narration' => $data_narration,
				'entry_type' => $data_type,
				'tag_id' => $data_tag,
				'sc_id' => $this->session->userdata('sc_id')
			);
			/** Create enties*/

			/* Adding ledger accounts */
			$data_all_ledger_dc = $this->input->post('ledger_dc', TRUE);
			$data_all_ledger_id = $this->input->post('ledger_id', TRUE);
			$data_all_dr_amount = $this->input->post('dr_amount', TRUE);
			$data_all_cr_amount = $this->input->post('cr_amount', TRUE);

			$dr_total = 0;
			$cr_total = 0;
			$array = array();
			foreach ($data_all_ledger_dc as $id => $ledger_data)
			{
				$data_ledger_dc = $data_all_ledger_dc[$id];
				$data_ledger_id = $data_all_ledger_id[$id];
				if ($data_ledger_id < 1)
					continue;
				$data_amount = 0;
				if ($data_all_ledger_dc[$id] == "D")
				{
					$data_amount = $data_all_dr_amount[$id];
					$dr_total = float_ops($data_all_dr_amount[$id], $dr_total, '+');
				} else {
					$data_amount = $data_all_cr_amount[$id];
					$cr_total = float_ops($data_all_cr_amount[$id], $cr_total, '+');
				}
				$insert_ledger_data = array(
					'entry_id' => $entry_id,
					'ledger_id' => $data_ledger_id,
					'amount' => $data_amount,
					'dc' => $data_ledger_dc,
				);
				/** Insert into items*/
				$array[] = $insert_ledger_data;
			}
			$error['data'] = $array;
			
			/* Showing success message in show() method since message is too long for storing it in session */
			$this->logger->write_message("success", "Added " . $current_entry_type['name'] . " Entry number " . full_entry_number($entry_type_id, $data_number) . " [id:" . $entry_id . "]");
			$error['error'] = false;
			$error['message'] = $current_entry_type['name'].' checked successfully.';
			return $error;		
		}else{
			$error['error'] = true;
			$error['message'] = 'Specify type';
			return $error;
		}
	}
	
	function create_bill(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('sales_id', 'Sales ID', 'required');
		if ($this->form_validation->run() == FALSE){
			redirect('sales');
		}else{
			$temp = $_POST;
			//print_r($temp);
			unset($_POST);
			$sales_id = $temp['sales_id'];
			
			/** For Entry */
			$_POST['entry_number'] = '';
			$_POST['entry_date'] = date('d/m/Y');
			/** For party*/
			$_POST['ledger_id'][0] = $temp['party_ledger_id'];
			$_POST['dr_amount'][0] = $temp['dr_amount'];
			$_POST['ledger_dc'][0] = $temp['party_ledger_dc'];
			
			/** For sales*/
			$_POST['ledger_id'][1] = $temp['sales_ledger_id'];
			$_POST['cr_amount'][1] = $temp['sales_amount'];
			$_POST['ledger_dc'][1] = $temp['sales_ledger_dc'];
			
			/** For Vat*/
			$_POST['ledger_id'][2] = $temp['vat_ledger_id'];
			$_POST['cr_amount'][2] = $temp['vat_amount'];
			$_POST['ledger_dc'][2] = $temp['vat_ledger_dc'];
			
			
			
			/** Check for journal entry*/
			$data_journal  = $this->_check_account('journal');	
			if($data_journal['error']==false){
				$party_ldgr_id = $temp['party_ledger_id'];
				$party_ldgr_dc = $temp['party_ledger_dc'];
				$party_ldgr_amt = $temp['dr_amount'];
				
				$sales_ldgr_id = $temp['sales_ledger_id'];
				$sales_cr_amt = $temp['sales_amount'];
				$sales_dc = $temp['sales_ledger_dc'];
				
				$vat_ldgr_id = $temp['vat_ledger_id'];
				$vat_cr_amt = $temp['vat_amount'];
				$vat_dc = $temp['vat_ledger_dc'];		
	
			}
			//print_r($data_journal);
			
			unset($_POST);
			
			/**Check for receipt entry*/
			$_POST['entry_number'] = '';
			$_POST['entry_date'] = date('d/m/Y');
	
			/**Entry for cash*/
			$_POST['ledger_id'][0] = $temp['cash_ledger_id'];
			$_POST['dr_amount'][0] = $temp['dr_amount'];
			$_POST['ledger_dc'][0] = $temp['cash_ledger_dc'];
			
			/** Entry for party*/
			$_POST['ledger_id'][1] = $temp['party_ledger_id'];
			$_POST['cr_amount'][1] = $temp['dr_amount'];
			$_POST['ledger_dc'][1] = ($temp['party_ledger_dc']=='C')?'D':'C';
			$data_receipt  = $this->_check_account('receipt');
			//print_r($data_receipt);
			
			if($data_receipt['error'] == false )
			{
				$cash_ldgr_id = $temp['cash_ledger_id'];
				$cash_amount = $temp['dr_amount'];
				$cash_dc = $temp['cash_ledger_dc'];
				
				$receipt_party_ledger_id = $temp['party_ledger_id'];
				$receipt_party_amt = $temp['dr_amount'];
				$receipt_party_dc = ($temp['party_ledger_dc']=='C')?'D':'C';
			}		
			
			$sales_type= $temp['salestype'];	
			if($data_journal['error'] == false && $data_receipt['error']==false){
				$sql = "CALL sp_account_create_bills($party_ldgr_id ,$party_ldgr_amt ,$sales_ldgr_id,$sales_cr_amt ,$vat_ldgr_id ,$vat_cr_amt ,".$this->session->userdata('sc_id').",".$this->session->userdata('user_id').",$cash_ldgr_id,$sales_id,'$party_ldgr_dc','$sales_dc','$vat_dc','$receipt_party_dc','$cash_dc','$sales_type')";		
				$result = $this->db->query($sql);
				$result->free_result();	
				
				$this->session->set_flashdata('success_save','Bill generated successfully');	
			}else{
				$this->session->set_flashdata('success_save',$data_journal['message'].$data_receipt['message']);
			}
			redirect('sales/sale/'.$sales_id);
		}
	}
	function cancel_bill(){
		$_POST['party_ledger_dc'] = ($_POST['party_ledger_dc']=="C")?"D":"C";
		$_POST['sales_ledger_dc'] = ($_POST['sales_ledger_dc']=="C")?"D":"C";
		$_POST['vat_ledger_dc'] = ($_POST['vat_ledger_dc']=="C")?"D":"C";
		$_POST['cash_ledger_dc'] = ($_POST['cash_ledger_dc']=="C")?"D":"C";
		$temp = $_POST;
		//print_r($temp);
		unset($_POST);
		$sales_id = $temp['sales_id'];
		
		/** For Entry */
		$_POST['entry_number'] = '';
		$_POST['entry_date'] = date('d/m/Y');
		/** For party*/
		$_POST['ledger_id'][0] = $temp['party_ledger_id'];
		$_POST['cr_amount'][0] = $temp['dr_amount'];
		$_POST['ledger_dc'][0] = $temp['party_ledger_dc'];
		
		/** For sales*/
		$_POST['ledger_id'][1] = $temp['sales_ledger_id'];
		$_POST['dr_amount'][1] = $temp['sales_amount'];
		$_POST['ledger_dc'][1] = $temp['sales_ledger_dc'];
		
		/** For Vat*/
		$_POST['ledger_id'][2] = $temp['vat_ledger_id'];
		$_POST['dr_amount'][2] = $temp['vat_amount'];
		$_POST['ledger_dc'][2] = $temp['vat_ledger_dc'];
		
		
		/** Check for journal entry*/
		$data_journal  = $this->_check_account('journal');	
		if($data_journal['error']==false){
			$party_ldgr_id = $temp['party_ledger_id'];
			$party_ldgr_dc = $temp['party_ledger_dc'];
			$party_ldgr_amt = $temp['dr_amount'];
			
			$sales_ldgr_id = $temp['sales_ledger_id'];
			$sales_cr_amt = $temp['sales_amount'];
			$sales_dc = $temp['sales_ledger_dc'];
			
			$vat_ldgr_id = $temp['vat_ledger_id'];
			$vat_cr_amt = $temp['vat_amount'];
			$vat_dc = $temp['vat_ledger_dc'];		
		}
		//print_r($data_journal);
		
		unset($_POST);
		
		/**Check for receipt entry*/
		$_POST['entry_number'] = '';
		$_POST['entry_date'] = date('d/m/Y');

		/**Entry for cash*/
		$_POST['ledger_id'][0] = $temp['cash_ledger_id'];
		$_POST['cr_amount'][0] = $temp['dr_amount'];
		$_POST['ledger_dc'][0] = $temp['cash_ledger_dc'];
		
		/** Entry for party*/
		$_POST['ledger_id'][1] = $temp['party_ledger_id'];
		$_POST['dr_amount'][1] = $temp['dr_amount'];
		$_POST['ledger_dc'][1] = ($temp['party_ledger_dc']=='C')?'D':'C';
		
		$_POST['reverse_entry']  = 1;
		$data_receipt  = $this->_check_account('receipt');
		//print_r($data_receipt);
		
		if($data_receipt['error'] == false )
		{
			$cash_ldgr_id = $temp['cash_ledger_id'];
			$cash_amount = $temp['dr_amount'];
			$cash_dc = $temp['cash_ledger_dc'];
			
			$receipt_party_ledger_id = $temp['party_ledger_id'];
			$receipt_party_amt = $temp['dr_amount'];
			$receipt_party_dc = ($temp['party_ledger_dc']=='C')?'D':'C';
		}		
		$sales_type= $temp['salestype'];
		if($data_journal['error'] == false && $data_receipt['error']==false ){
			$sql = "CALL sp_account_create_bils_reverse($party_ldgr_id ,$party_ldgr_amt ,$sales_ldgr_id,$sales_cr_amt ,$vat_ldgr_id ,$vat_cr_amt ,".$this->session->userdata('sc_id').",".$this->session->userdata('user_id').",$cash_ldgr_id,$sales_id,'$party_ldgr_dc','$sales_dc','$vat_dc','$receipt_party_dc','$cash_dc','$sales_type')";
			
			$result = $this->db->query($sql);
			$result->free_result();	
			$this->session->set_flashdata('success_save','Bill cancelled successfully');	
		}else{
			$this->session->set_flashdata('success_save',$data_journal['message'].$data_receipt['message']);
		}
		redirect('sales/sale/'.$sales_id);
	}
		
}

/* End of file entry.php */
/* Location: ./system/application/controllers/entry.php */
