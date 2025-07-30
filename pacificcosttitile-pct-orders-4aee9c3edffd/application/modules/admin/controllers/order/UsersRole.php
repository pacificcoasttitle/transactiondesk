<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UsersRole extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(
			array('file', 'url', 'form')
		);
		$this->load->library('order/adminTemplate');
		$this->load->library('form_validation');
		$this->load->model('order/admin_user_model');
		$this->load->model('order/users_roles_model');
		$this->load->library('order/common');
		$this->load->library('order/order');
		$this->common->is_super_admin();
	}

	public function index()
	{
		$data = array();
		$data['title'] = 'PCT Order: Roles';
		if ($this->input->post()) {
			$inserted_id = $updated_id = null;
			$roles_data = [
				'title' => $this->input->post('title'),
			];
			if ($this->input->post('role_id') > 0) {
				$user_id = $this->input->post('role_id');

				$updated_id = $this->users_roles_model->update($user_id, $roles_data);
			} else {
				$inserted_id = $this->users_roles_model->insert($roles_data);
			}
			if ($inserted_id) {
				/** Save user Activity */
				$activity = 'Role Added successfully: .' . $this->input->post('title');
				$this->order->logAdminActivity($activity);
				/** End save user activity */
				$flash_data['success'] = 'Role added successfully.';
			} elseif ($updated_id) {
				/** Save user Activity */
				$activity = 'Role updated successfully: .' . $this->input->post('title');
				$this->order->logAdminActivity($activity);
				/** End save user activity */
				$flash_data['success'] = 'Role updated successfully.';
			} else {
				$flash_data['error'] = 'Role not added.Please try again';
			}
			$this->session->set_flashdata($flash_data);
			redirect(base_url('order/admin/roles'));
		}

		$data['users_roles'] = $this->users_roles_model->order_by('id', 'DESC')->get_all();
		$this->admintemplate->show("order/role", "index", $data);
		// $this->load->view('order/layout/header', $data);
		// $this->load->view('order/role/index', $data);
		// $this->load->view('order/layout/footer', $data);
	}


	public function delete_user_role($id)
	{
		$status = false;
		if ($this->input->post('action') == 'delete') {
			$role = $this->users_roles_model->get($id);
			$delete_status = $this->users_roles_model->delete($id);
			if ($delete_status) {

				/** Save user Activity */
				$activity = 'Role deleted successfully: .' . $role->title;
				$this->order->logAdminActivity($activity);
				/** End save user activity */

				$flash_data['success'] = 'User Role deleted successfully.';
				$status = true;
			} else {
				$flash_data['error'] = 'User Role not deleted.';
			}
			$this->session->set_flashdata($flash_data);

		}
		echo json_encode(['status' => $status]);
	}

}