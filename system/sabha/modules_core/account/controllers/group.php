<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Group extends Account_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->language("account",  $this->mdl_mcb_data->setting('default_language'));	   
		$this->load->model('Group_model');
		return;
	}

	function index()
	{
		redirect('group/add');
		return;
	}

	function add()
	{
		$this->load->library('form_validation');
		//$this->template->set('page_title', 'New Group');

		/* Check access */
		if ( ! check_access('create group'))
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
		$data['group_name'] = array(
			'name' => 'group_name',
			'id' => 'group_name',
			'maxlength' => '100',
			'size' => '40',
			'value' => '',
			'class' =>'text-input'
		);
		$data['group_parent'] = $this->Group_model->get_all_groups();
		$data['group_parent_active'] = 0;
		$data['affects_gross'] = 0;

		/* Form validations */
		$this->form_validation->set_rules('group_name', 'Group name', 'trim|required|min_length[2]|max_length[100]|unique[groups.name]');
		$this->form_validation->set_rules('group_parent', 'Parent group', 'trim|required|is_natural_no_zero');

		/* Re-populating form */
		if ($_POST)
		{
			$data['group_name']['value'] = $this->input->post('group_name', TRUE);
			$data['group_parent_active'] = $this->input->post('group_parent', TRUE);
			$data['affects_gross'] = $this->input->post('affects_gross', TRUE);
		}

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('custom_warning',validation_errors());
			$this->load->view('account/group/add', $data);
			return;
		}
		else
		{
			$data_name = $this->input->post('group_name', TRUE);
			$data_parent_id = $this->input->post('group_parent', TRUE);

			/* Check if parent group id present */
			$this->db->select('id')->from('groups')->where('id', $data_parent_id);
			if ($this->db->get()->num_rows() < 1)
			{
				$this->session->set_flashdata('custom_error','Invalid Parent group.');
				$this->load->view('account/group/add', $data);
				return;
			}

			/* Only if Income or Expense can affect gross profit loss calculation */
			$data_affects_gross = $this->input->post('affects_gross', TRUE);
			if ($data_parent_id == "3" || $data_parent_id == "4")
			{
				if ($data_affects_gross == "1")
					$data_affects_gross = 1;
				else
					$data_affects_gross = 0;
			} else {
				$data_affects_gross = 0;
			}

			$this->db->trans_start();
			$insert_data = array(
				'name' => $data_name,
				'parent_id' => $data_parent_id,
				'affects_gross' => $data_affects_gross,
				'sc_id' => $this->session->userdata('sc_id')
			);
			if ( ! $this->db->insert('groups', $insert_data))
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('custom_error','Error addding Group account - ' . $data_name . '.');
				$this->logger->write_message("error", "Error adding Group account called " . $data_name);
				$this->load->view('group/add', $data);
				return;
			} else {
				$this->db->trans_complete();
				$this->session->set_flashdata('success_save','Added Group account - ' . $data_name . '.');
				$this->logger->write_message("success", "Added Group account called " . $data_name);
				redirect('account');
				return;
			}
		}
		return;
	}

	function edit($id)
	{
		$this->load->helper('html');
		$this->load->helpers(array('my_path','access'));
		//$this->template->set('page_title', '');

		/* Check access */
		if ( ! check_access('edit group'))
		{
			$this->session->set_flashdata('custom_warning', 'Permission denied.');
			redirect('account');
			return;
		}

		/* Check for account lock */
		if ($this->config->item('account_locked') == 1)
		{
			//$this->messages->add('Account is locked.', 'error');
			//redirect('account');
			//return;
		}

		/* Checking for valid data */
		$id = $this->input->_clean_input_data($id);
		$id = (int)$id;
		if ($id < 1) {
			//$this->messages->add('Invalid Group account.', 'error');
			redirect('account');
			return;
		}
		if ($id <= 4) {
			//$this->messages->add('Cannot edit System Group account.', 'error');
			redirect('account');
			return;
		}

		/* Loading current group */
		$this->db->from('groups')->where('id', $id);
		$group_data_q = $this->db->get();
		if ($group_data_q->num_rows() < 1)
		{
			//$this->messages->add('Invalid Group account.', 'error');
			redirect('account');
			return;
		}
		$group_data = $group_data_q->row();

		/* Form fields */
		$data['group_name'] = array(
			'name' => 'group_name',
			'id' => 'group_name',
			'maxlength' => '100',
			'size' => '40',
			'value' => $group_data->name,
			'class'=>"text-input"
		);
		$data['group_parent'] = $this->Group_model->get_all_groups($id);
		$data['group_parent_active'] = $group_data->parent_id;
		$data['group_id'] = $id;
		$data['sc_id'] = $group_data->sc_id;
		$data['affects_gross'] = $group_data->affects_gross;

		/* Form validations */
		$this->form_validation->set_rules('group_name', 'Group name', 'trim|required|min_length[2]|max_length[100]|uniquewithid[groups.name.' . $id . ']');
		$this->form_validation->set_rules('group_parent', 'Parent group', 'trim|required|is_natural_no_zero');

		/* Re-populating form */
		if ($_POST)
		{
			$data['group_name']['value'] = $this->input->post('group_name', TRUE);
			$data['group_parent_active'] = $this->input->post('group_parent', TRUE);
			$data['affects_gross'] = $this->input->post('affects_gross', TRUE);
		}

		if ($this->form_validation->run() == FALSE)
		{
			//$this->messages->add(validation_errors(), 'error');
			$this->load->view('group/edit', $data);
			return;
		}
		else
		{
			$data_name = $this->input->post('group_name', TRUE);
			$data_parent_id = $this->input->post('group_parent', TRUE);
			$sc_id = $this->input->post('sc_id', TRUE);
			$data_id = $id;

			/* Check if parent group id present */
			$this->db->select('id')->from('groups')->where('id', $data_parent_id);
			if ($this->db->get()->num_rows() < 1)
			{
				$this->messages->add('Invalid Parent group.', 'error');
				$this->template->load('template', 'group/edit', $data);
				return;
			}

			/* Check if parent group same as current group id */
			if ($data_parent_id == $id)
			{
				$this->messages->add('Invalid Parent group', 'error');
				$this->template->load('template', 'group/edit', $data);
				return;
			}

			/* Only if Income or Expense can affect gross profit loss calculation */
			$data_affects_gross = $this->input->post('affects_gross', TRUE);
			if ($data_parent_id == "3" || $data_parent_id == "4")
			{
				if ($data_affects_gross == "1")
					$data_affects_gross = 1;
				else
					$data_affects_gross = 0;
			} else {
				$data_affects_gross = 0;
			}

			$this->db->trans_start();
			$update_data = array(
				'name' => $data_name,
				'parent_id' => $data_parent_id,
				'affects_gross' => $data_affects_gross,
				'sc_id' =>$sc_id
			);
			if ( ! $this->db->where('id', $data_id)->update('groups', $update_data))
			{
				$this->db->trans_rollback();
				$this->form_validation->_error_array[] = 'Error updating Group account - ' . $data_name . '.';
				$this->logger->write_message("error", "Error updating Group account called " . $data_name . " [id:" . $data_id . "]");
				$this->load->view('group/edit', $data);
				return;
			} else {
				$this->db->trans_complete();
				$this->session->set_userdata('success_save','Updated Group account - ' . $data_name . '.');
				$this->logger->write_message("success", "Updated Group account called " . $data_name . " [id:" . $data_id . "]");
				redirect('account');
				return;
			}
		}
		return;
	}

	function delete($id)
	{
		/* Check access */
		if ( ! check_access('delete group'))
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

		/* Checking for valid data */
		$id = $this->input->_clean_input_data($id);
		$id = (int)$id;
		if ($id < 1) {
			$this->session->set_flashdata('custom_error','Invalid Group account.');
			redirect('account');
			return;
		}
		if ($id <= 4) {
			$this->session->set_flashdata('custom_error','Cannot delete System Group account.');
			redirect('account');
			return;
		}
		$this->db->from('groups')->where('parent_id', $id);
		if ($this->db->get()->num_rows() > 0)
		{
			$this->session->set_flashdata('custom_error','Cannot delete non-empty Group account.');
			redirect('account');
			return;
		}
		$this->db->from('ledgers')->where('group_id', $id);
		if ($this->db->get()->num_rows() > 0)
		{
			$this->session->set_flashdata('custom_error','Cannot delete non-empty Group account.');
			redirect('account');
			return;
		}

		/* Get the group details */
		$this->db->from('groups')->where('id', $id);
		$group_q = $this->db->get();
		if ($group_q->num_rows() < 1)
		{
			$this->session->set_flashdata('custom_error','Invalid Group account.');
			redirect('account');
			return;
		} else {
			$group_data = $group_q->row();
		}

		/* Deleting group */
		$this->db->trans_start();
		if ( ! $this->db->delete('groups', array('id' => $id)))
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('custom_error','Error deleting Group account - ' . $group_data->name . '.');
			$this->logger->write_message("error", "Error deleting Group account called " . $group_data->name . " [id:" . $id . "]");
			redirect('account');
			return;
		} else {
			$this->db->trans_complete();
			$this->session->set_flashdata('success_delete','Deleted Group account - ' . $group_data->name . '.');
			$this->logger->write_message("success", "Deleted Group account called " . $group_data->name . " [id:" . $id . "]");
			redirect('account');
			return;
		}
		return;
	}
}

/* End of file group.php */
/* Location: ./system/application/controllers/group.php */
