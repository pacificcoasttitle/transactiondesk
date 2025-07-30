<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProposedInsured extends MX_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
		$this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/branches_model');
        $this->load->library('order/common');
        $this->common->is_super_admin();
    }

	public function index()
	{
		$data = array();
        $data['title'] = 'PCT Order: Proposed Insured Branches';
		if($this->input->post()) {
			$inserted_id = $updated_id = null;
			if($this->input->post('branch_id') > 0) {
				$branch_id = $this->input->post('branch_id');
				$branch_data = [
					'address' => $this->input->post('address'),
					'city' => $this->input->post('city'),
					'zip'=> $this->input->post('zip'),
					'updated_at'=>date('Y-m-d H:i:s')
				];
				$updated_id = $this->branches_model->update($branch_id, $branch_data);
			} else {
				$branch_data = [
					'address' => $this->input->post('address'),
					'city' => $this->input->post('city'),
                    'state' => 'CA',
					'zip'=> $this->input->post('zip'),
					'created_at'=>date('Y-m-d H:i:s')
				];
				$inserted_id = $this->branches_model->insert($branch_data);
			}
			if($inserted_id) {
				/** Save user Activity */
				$activity = 'Proposed Insured Branch added successfully: .' . $this->input->post('address') . ' ' . $this->input->post('city') . ' ' . $this->input->post('zip');
				$this->common->logAdminActivity($activity);
				/** End save user activity */
				$flash_data['success'] = 'Branch added successfully.';
			}
			elseif($updated_id) {
				/** Save user Activity */
				$activity = 'Proposed Insured Branch updated successfully: .' . $this->input->post('address') . ' ' . $this->input->post('city') . ' ' . $this->input->post('zip');
				$this->common->logAdminActivity($activity);
				/** End save user activity */
				$flash_data['success'] = 'Branch updated successfully.';
			}
			else {
				$flash_data['error'] = 'Branch not added.Please try again';
			}
			$this->session->set_flashdata($flash_data);
			redirect(base_url('order/admin/proposed-branches'));
		}
		$data['proposed_branches'] = $this->branches_model->order_by('id','asc')->get_all();
		$this->admintemplate->show("order/proposed", "index", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/proposed/index', $data);
        // $this->load->view('order/layout/footer', $data);
	}

	public function get_branch_details()
	{
		$branch_id = $this->input->post('branch_id');
		$check_exist = $this->branches_model->get($branch_id);
		$return_data = array('status'=>false,'data'=>[]);
		if($check_exist) {
			$return_data['status']=true;
			$return_data['data']=$check_exist;
		}
		echo json_encode($return_data);
	}
		
    public function delete_proposed_branch($id)
    {
		$status = false;
		if($this->input->post('action') == 'delete') {
			$branch = $this->branches_model->get($id);
			$delete_status = $this->branches_model->delete($id);
			if ($delete_status) {

				/** Save user Activity */
				$activity = 'Proposed Insured Branch deleted successfully: .' . $branch->address . ' ' . $branch->city . ' ' . $branch->zip;
				$this->common->logAdminActivity($activity);
				/** End save user activity */
				
				$flash_data['success'] = 'Branch deleted successfully.';
				$status = true;
			} else {
				$flash_data['error'] = 'Branch not deleted.';
			}
			$this->session->set_flashdata($flash_data);

		}
		echo json_encode(['status'=>$status]);
    }

}
