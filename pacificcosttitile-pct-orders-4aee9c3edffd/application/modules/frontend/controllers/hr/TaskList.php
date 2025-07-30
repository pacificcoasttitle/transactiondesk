<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TaskList extends MX_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('hr/template');
        $this->load->library('hr/common');
        // $this->load->model('hr/hr'); 
		$this->load->model('admin/hr/task_list_category');
		$this->load->model('admin/hr/task_list_model');
		$this->load->model('admin/hr/users_model');
		$this->load->model('admin/hr/users_type_model');
        $this->common->is_user();
		$this->common->is_onboarding_user();
    }

	public function empoyees()
	{
		$data['title'] = 'HR-Center Onboarding';
        $data['errors'] = '';
		$data['success'] = '';
		if ($this->session->userdata('errors')) {
			$data['errors'] = $this->session->userdata('errors');
			$this->session->unset_userdata('errors');
		}
		if ($this->session->userdata('success')) {
			$data['success'] = $this->session->userdata('success');
			$this->session->unset_userdata('success');
		}
        $this->template->show("hr", "onboarding_employees", $data);
	}

	public function getEmpoyees()
	{
		$data = array();
		$users_list = $this->users_model->with('type')->get_all();
		$i = 1;
		foreach($users_list as $record){
			if(trim(strtolower($record->type->name)) == 'employee') {

				$tmp_array = array();
				$status = '<span class="badge badge-info">In-active</span>';
				if($record->status == 1) {
					$status = '<span class="badge badge-success">Active</span>';
				}
				$tmp_array[] = $i;
				$tmp_array[] = $record->first_name.' '.$record->last_name;
				$tmp_array[] = $record->email;
				$tmp_array[] =  date("m/d/Y", strtotime($record->hire_date)); ;
				// $tmp_array[] = $record->created_at;
				$task_url = base_url('hr/onboarding/employee-task/'.$record->id);
				$tmp_array[] = "<div class=''>
				<button onclick=\"location.href='".$task_url."'\" class='btn generate btn-info' type='submit'>Tasks</button>
			</div>";
				$data[]=$tmp_array;
				$i++;
			}
		}

		$json_data['recordsTotal'] = intval(count($data));
		$json_data['recordsFiltered'] = intval(count($data));
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}

    

    public function tasks($id)
    {
		$this->load->model('admin/hr/users_tasks_model');
		$data = array();
		$data['title'] = 'HR-Center New Rep Checklist';
        $data['page_title'] = 'New Rep Checklist';
		$this->load->model('admin/hr/users_model');
		$user_record = $this->users_model->with('type')->get($id);
		if($user_record && trim(strtolower($user_record->type->name)) == 'employee'){

			$tasks = $this->task_list_category->with('tasks')->get_many_by('status','1');
			$this->load->model('admin/hr/task_position');
	
			$hr_task_positions = $this->task_position->get_many_by('position_id',$user_record->position_id);
			$data['hr_task_positions'] = array_column($hr_task_positions,'task_id');

			$users_tasks_all = $this->users_tasks_model->get_tasks($id);
			$users_tasks = array_column($users_tasks_all,"task_id");
			if ($this->session->userdata('errors')) {
				$data['errors'] = $this->session->userdata('errors');
				$this->session->unset_userdata('errors');
			}
			if ($this->session->userdata('success')) {
				$data['success'] = $this->session->userdata('success');
				$this->session->unset_userdata('success');
			}
			if ($this->input->post()) {
				$users_tasks_add = array();
				$task_done = $this->input->post('task_done');
				foreach($task_done as $task_id) {
					if(!(in_array($task_id,$users_tasks))){
						$users_task = array();
						$users_task['task_id'] = $task_id;
						$users_task['employee_id'] = $id;
						$users_tasks_add[] = $users_task;
					}
				}
				if(count($users_tasks_add)) {
					$this->users_tasks_model->insert_many($users_tasks_add);
				}
				//Delete records if task unchecked
				$delete_id_array = array();
				foreach($users_tasks_all as $users_task) {
					if(!(in_array($users_task['task_id'],$task_done))){
						$delete_id_array[] = $users_task['id'];
					}
				}
				if(count($delete_id_array)) {
					$this->users_tasks_model->delete_many($delete_id_array);
				}
				$successMsg = 'Task List Updated';
				$this->session->set_userdata('success', $successMsg);
				redirect(base_url().'hr/onboarding/employee-task/'.$id);
			}
	
			
			$data['tasks'] = $tasks;
			$data['users_tasks'] = $users_tasks;
			$this->template->show("hr", "users_tasks", $data);
		}
    }

}
