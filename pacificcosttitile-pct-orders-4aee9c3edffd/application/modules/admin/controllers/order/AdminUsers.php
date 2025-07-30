<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminUsers extends MX_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
		$this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/admin_user_model');
        $this->load->model('order/users_roles_model');
        $this->load->library('order/common');
        $this->common->is_super_admin();
    }

	public function index()
	{
		$data = array();
        $data['title'] = 'PCT Order: Admins';
		if($this->input->post()) {
			$inserted_id = $updated_id = null;
			if($this->input->post('admin_id') > 0) {
				$admin_id=$this->input->post('admin_id');
				$firstName = $this->input->post('first_name');
				$lastName = $this->input->post('last_name');
				$admin_data = [
					'first_name' => $firstName,
					'last_name' => $lastName,
					'role_id'=> $this->input->post('role_id'),
					'updated_at'=>date('Y-m-d H:i:s')
				];
				$is_password_updated = $this->input->post('password_update');
				if($is_password_updated) {
					$admin_data['password'] = password_hash($this->input->post('password'),PASSWORD_DEFAULT);
				}
				$updated_id = $this->admin_user_model->update($admin_id,$admin_data);
				/** Save user Activity */
				$activity = 'Admin user id :'. $admin_id . ' details updated (name) - ' . $firstName . '  ' . $lastName;
				$this->common->logAdminActivity($activity);
				/** End Save user activity */
			}
			else {
				$email = $this->input->post('email_id');
				$admin_data = [
					'email_id'=> $email,
					'first_name'=>$this->input->post('first_name'),
					'last_name'=>$this->input->post('last_name'),
					'password'=> password_hash($this->input->post('password'),PASSWORD_DEFAULT),
					'role_id'=> $this->input->post('role_id'),
					'created_at'=>date('Y-m-d H:i:s')
				];
				$inserted_id = $this->admin_user_model->insert($admin_data);
				/** Save user Activity */
				$activity = 'New Admin user created details - ' . $email;
				$this->common->logAdminActivity($activity);
				/** End Save user activity */
			}
			if($inserted_id) {
				$flash_data['success'] = 'User added successfully.';
			}
			elseif($updated_id) {
				$flash_data['success'] = 'User updated successfully.';
			}
			else {
				$flash_data['error'] = 'User not added.Please try again';
			}
			$this->session->set_flashdata($flash_data);
			redirect(base_url('order/admin/admin_users'));
		}
		$data['admin_users'] = $this->admin_user_model->with('role_obj')->order_by('id','DESC')->get_all();
		$data['users_roles'] = $this->users_roles_model->get_all();
		$this->admintemplate->show("order/admin", "index", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/admin/index', $data);
        // $this->load->view('order/layout/footer', $data);
	}

	public function email_check()
	{
		$email = $this->input->post('email_id');
		$check_exist = $this->admin_user_model->get_by('email_id',$email);
		if($check_exist) {
			echo 'false';
		}
		else {
			echo 'true';
		}
	}
	public function admin_details()
	{
		$admin_id = $this->input->post('admin_id');
		$check_exist = $this->admin_user_model->get($admin_id);
		$return_data = array('status'=>false,'data'=>[]);
		if($check_exist) {
			$return_data['status']=true;
			$return_data['data']=$check_exist;
		}
		echo json_encode($return_data);
	}

   
    public function delete_admin_user($id)
    {
		$status = false;
		if($this->input->post('action') == 'delete') {
			$adminDetails = $this->admin_user_model->get($id);
			$delete_status = $this->admin_user_model->delete($id);
			if ($delete_status) {
				$flash_data['success'] = 'Admin user deleted successfully.';
				$status = true;
				/** Save user Activity */
				$activity = 'Admin user deleted - ' . $adminDetails->email_id;
				$this->common->logAdminActivity($activity);
				/** End Save user activity */
			} else {
				$flash_data['error'] = 'Admin user not deleted.';
			}
			$this->session->set_flashdata($flash_data);

		}
		echo json_encode(['status'=>$status]);
    }

}
