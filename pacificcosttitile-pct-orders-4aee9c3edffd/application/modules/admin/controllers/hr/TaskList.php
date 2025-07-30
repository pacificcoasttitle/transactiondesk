<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class TaskList extends MX_Controller 
{
	private $custom_js_version = '02';
	function __construct() 
    {
        parent::__construct();
		$this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('hr/adminTemplate');
        $this->load->library('hr/common');
		$this->load->model('hr/task_list_category');
		$this->load->model('hr/task_list_model');
        $this->common->is_hr_admin();
	}
	
	function category()
	{
		$data['title'] = 'HR-Center Task Category';
        $data['page_title'] = 'Task Category';
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
		$this->admintemplate->addCSS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.css'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/jquery.dataTables.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=task_'.$this->custom_js_version) );
        $this->admintemplate->show("hr", "task_category", $data);
	}
	
	public function  getCategory()
    {
        $params = array();  $data = array();
		
		$task_list_category = $this->task_list_category->get_all();
		$i = 1;
		foreach($task_list_category as $record){
			$tmp_array = array();
			$status = '<span class="badge badge-info">In-active</span>';
			if($record->status == 1) {
				$status = '<span class="badge badge-success">Active</span>';
			}
			$tmp_array[] = $i;
			$tmp_array[] = $record->name;
			$tmp_array[] = strlen($record->description) > 50 ? substr($record->description,0,50)."..." : $record->description;
			$tmp_array[] = $status;
			// $tmp_array[] = $record->created_at;
			$editUrl = base_url().'hr/admin/edit-task-category/'.$record->id;
			$deleteUrl = base_url().'hr/admin/delete-task-category/'.$record->id;
			$tmp_array[] = '<div style="display:inline-flex;">
				<a href="'.$editUrl.'" class="btn btn-info btn-icon-split btn-sm">
					<span class="icon text-white-50">
						<i class="fas fa-pencil-alt"></i>
					</span>
					<span class="text">Edit</span>
				</a>
				<a style="margin-left: 5px;" data-id="'.$record->id.'" data-href="'.$deleteUrl.'" data-toggle="modal" data-target="#pct__delete_modal" class="btn btn-danger btn-icon-split btn-sm pct__btn_delete">
					<span class="icon text-white-50">
						<i class="fas fa-trash"></i>
					</span>
					<span class="text">Delete</span>
				</a>
			</div>';
			$data[]=$tmp_array;
			$i++;
		}
		
		$json_data['recordsTotal'] = intval(count($data));
		$json_data['recordsFiltered'] = intval(count($data));
		$json_data['data'] = $data;
		echo json_encode($json_data);
    }

	public function  addCategory()
    {
		$data['title'] = 'HR-Center Task Category';
        $data['page_title'] = 'Add Category';
		if ($this->input->post()) {
            $this->load->library('hr/common');
            $this->form_validation->set_rules('category_name', 'Category Name', 'required');
        
            if ($this->form_validation->run() == true) {
                $categoryData = array(
                    'name' =>  $this->input->post('category_name'),
                    'description' =>  $this->input->post('category_description'),
                    'status' =>  $this->input->post('check_status') ? 1 : 0,
                );
                $this->task_list_category->insert($categoryData);
                $successMsg = 'Category added successfully.';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url().'hr/admin/task-category');
            } else {
                $data['category_name_error_msg'] = form_error('category_name');
            }                                       
        }
		$this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=task_'.$this->custom_js_version) );
        $this->admintemplate->show("hr", "add_task_category", $data);
	}

	public function  editCategory($id)
    {
		$record = $this->task_list_category->get($id);
		if($record) {
			$data['title'] = 'HR-Center Task Category';
			$data['page_title'] = 'Edit Category';
			$data['record'] = $record;
			if ($this->input->post()) {
				$this->load->library('hr/common');
				$this->form_validation->set_rules('category_name', 'Category Name', 'required');
			
				if ($this->form_validation->run() == true) {
					$categoryData = array(
						'name' =>  $this->input->post('category_name'),
						'description' =>  $this->input->post('category_description'),
						'status' =>  $this->input->post('check_status') ? 1 : 0,
					);
					$this->task_list_category->update($id,$categoryData);
					$successMsg = 'Category updated successfully.';
					$this->session->set_userdata('success', $successMsg);
					redirect(base_url().'hr/admin/task-category');
				} else {
					$data['category_name_error_msg'] = form_error('category_name');
				}                                       
			}
			$this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=task_'.$this->custom_js_version) );
			$this->admintemplate->show("hr", "edit_task_category", $data);
		}
		else {
			$this->session->set_userdata('errors', 'Invalid Request');
			redirect(base_url().'hr/admin/task-category');
		}
	}

	public function deleteCategory()
    {
		$id = $this->input->post('id');
		$action = $this->input->post('action');
		$record = $this->task_list_category->with('tasks')->get($id);
        if ($record && $action == 'delete') {
			$tasks = ($record->tasks);
			if($tasks) {
				$this->session->set_userdata('errors', 'You can not delete this category because there is task/tasks associate with this. You can In-active this instead.');
			}
			else {
				$this->task_list_category->delete($id);
				$this->session->set_userdata('success', 'Category deleted');
			}
        } else {
            $this->session->set_userdata('errors', 'Invalid Request');
        }
		redirect(base_url().'hr/admin/task-category');
    }

	/** Task List start */
	public function index()
	{
		$data['title'] = 'HR-Center Task List';
        $data['page_title'] = 'Task List';
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
		$this->admintemplate->addCSS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.css'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/jquery.dataTables.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=task_'.$this->custom_js_version) );
        $this->admintemplate->show("hr", "task_list", $data);
	}
	
	public function  getTask()
    {
        $params = array();  $data = array();
		
		$task_list = $this->task_list_model->with('category')->with('positions')->get_all();
		// echo "<pre>";
		// var_dump($task_list);die;
		$i = 1;
		foreach($task_list as $record){
			$tmp_array = array();
			$status = '<span class="badge badge-info">In-active</span>';
			if($record->status == 1) {
				$status = '<span class="badge badge-success">Active</span>';
			}
			$positions_display = '<span class="badge badge-danger">No Position</span>';
			if(count($record->positions)) {
				$positions_display = '<span class="badge badge-success">'.count($record->positions).' Positions</span>';
			} 
			$tmp_array[] = $i;
			$tmp_array[] = $record->name;
			$tmp_array[] = $record->category->name;
			$tmp_array[] = strlen($record->description) > 50 ? substr($record->description,0,50)."..." : $record->description;
			$tmp_array[] = $positions_display;
			$tmp_array[] = $status;
			// $tmp_array[] = $record->created_at;
			$editUrl = base_url().'hr/admin/edit-task-list/'.$record->id;
			$deleteUrl = base_url().'hr/admin/delete-task-list/'.$record->id;
			$tmp_array[] = '<div style="display:inline-flex;">
			<a href="'.$editUrl.'" class="btn btn-info btn-icon-split btn-sm">
				<span class="icon text-white-50">
						<i class="fas fa-pencil-alt"></i>
					</span>
					<span class="text">Edit</span>
				</a>
				<a style="margin-left: 5px;" data-id="'.$record->id.'" data-href="'.$deleteUrl.'" data-toggle="modal" data-target="#pct__delete_modal" class="btn btn-danger btn-icon-split btn-sm pct__btn_delete">
					<span class="icon text-white-50">
						<i class="fas fa-trash"></i>
					</span>
					<span class="text">Delete</span>
				</a>
			</div>';
			$data[]=$tmp_array;
			$i++;
		}
		
		$json_data['recordsTotal'] = intval(count($data));
		$json_data['recordsFiltered'] = intval(count($data));
		$json_data['data'] = $data;
		echo json_encode($json_data);
    }

	public function  addTask()
    {
		$data['title'] = 'HR-Center Task Category';
        $data['page_title'] = 'Add Category';
		$this->load->model('hr/users_position');
		$task_list_category = $this->task_list_category->get_many_by('status', '1');
		$users_position = $this->users_position->get_many_by('status', '1');
        $data['task_list_category'] = $task_list_category;
        $data['users_position'] = $users_position;

		if ($this->input->post()) {
            $this->load->library('hr/common');
            $this->form_validation->set_rules('task_name', 'Task Name', 'required');
            $this->form_validation->set_rules('task_category', 'Task Category', 'required');
            $this->form_validation->set_rules('task_position[]', 'Position', 'required');//task_position
        
            if ($this->form_validation->run() == true) {
                $taskData = array(
                    'category_id' =>  $this->input->post('task_category'),
                    'name' =>  $this->input->post('task_name'),
                    'description' =>  $this->input->post('task_description'),
                    'status' =>  $this->input->post('check_status') ? 1 : 0,
                );
                $task_id = $this->task_list_model->insert($taskData);
				if($task_id) {
					$this->load->model('hr/task_position');
					$position_ids = $this->input->post('task_position');
					$task_positions = array();
					foreach($position_ids as $position_id) {
						$tmp_array = array();
						$tmp_array['task_id'] = $task_id;
						$tmp_array['position_id'] = $position_id;
						$task_positions[] = $tmp_array;
					}
					$this->task_position->insert_many($task_positions);

				}
                $successMsg = 'Task added successfully.';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url().'hr/admin/task-list');
            } else {
                $data['task_name_error_msg'] = form_error('task_name');
                $data['task_category_error_msg'] = form_error('task_category');
                $data['task_position_error_msg'] = form_error('task_position');
            }                                       
        }
		$this->admintemplate->addCSS(base_url('assets/backend/hr/css/bootstrap-multiselect.min.css'));
		$this->admintemplate->addJS( base_url('assets/backend/hr/js/plugins/bootstrap-multiselect.min.js') );
		$this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=task_'.$this->custom_js_version) );
        $this->admintemplate->show("hr", "add_task_list", $data);
	}

	public function  editTask($id)
    {
		$record = $this->task_list_model->get($id);
		// $task_list_category = $this->task_list_category->get_many_by(['status'=> '1']);
		$task_list_category = $this->task_list_category->get_many_by("(status='1' OR id={$record->category_id})");
		$this->load->model('hr/users_position');
		$users_position = $this->users_position->get_many_by('status', '1');
		$this->load->model('hr/task_position');
		$hr_task_positions = $this->task_position->get_many_by('task_id',$id);
		
        $data['task_list_category'] = $task_list_category;
        $data['users_position'] = $users_position;
        $data['hr_task_positions'] = array_column($hr_task_positions,'position_id');
		
		if($record) {
			$data['title'] = 'HR-Center Task Category';
			$data['page_title'] = 'Edit Category';
			$data['record'] = $record;
			if ($this->input->post()) {
				$this->load->library('hr/common');
				$this->form_validation->set_rules('task_name', 'Task Name', 'required');
				$this->form_validation->set_rules('task_category', 'Task Category', 'required');
				$this->form_validation->set_rules('task_position[]', 'Position', 'required');//task_position

			
				if ($this->form_validation->run() == true) {
					$taskData = array(
						'category_id' =>  $this->input->post('task_category'),
						'name' =>  $this->input->post('task_name'),
						'description' =>  $this->input->post('task_description'),
						'status' =>  $this->input->post('check_status') ? 1 : 0,
					);
					$this->task_list_model->update($id,$taskData);
					$position_ids = $this->input->post('task_position');
					$task_positions = array();
					foreach($position_ids as $position_id) {
						if(!(in_array($position_id,$data['hr_task_positions']))){
							$tmp_array = array();
							$tmp_array['task_id'] = $id;
							$tmp_array['position_id'] = $position_id;
							$task_positions[] = $tmp_array;
						}
					}
					if(count($task_positions)) {
						$this->task_position->insert_many($task_positions);
					}
					//Delete records if task unchecked
					$delete_id_array = array();
					foreach($hr_task_positions as $task_position) {
						if(!(in_array($task_position->position_id,$position_ids))){
							$delete_id_array[] = $task_position->id;
						}
					}
					if(count($delete_id_array)) {
						$this->task_position->delete_many($delete_id_array);
					}
					$successMsg = 'Task updated successfully.';
					$this->session->set_userdata('success', $successMsg);
					redirect(base_url().'hr/admin/task-list');
				} else {
					$data['task_name_error_msg'] = form_error('task_name');
					$data['task_category_error_msg'] = form_error('task_category');
					$data['task_position_error_msg'] = form_error('task_position');

				}                                       
			}
			$this->admintemplate->addCSS(base_url('assets/backend/hr/css/bootstrap-multiselect.min.css'));
			$this->admintemplate->addJS( base_url('assets/backend/hr/js/plugins/bootstrap-multiselect.min.js') );
			$this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=task_'.$this->custom_js_version) );
			$this->admintemplate->show("hr", "edit_task_list", $data);
		}
		else {
			$this->session->set_userdata('errors', 'Invalid Request');
			redirect(base_url().'hr/admin/task-list');
		}
	}

	public function deleteTask()
    {
		$id = $this->input->post('id');
		$action = $this->input->post('action');
		$record = $this->task_list_model->with('users')->get($id);
        if ($record && $action == 'delete') {
			$users = ($record->users);
			if($users) {
				$this->session->set_userdata('errors', 'You can not delete this task because there is user/userss associate with this. You can In-active this instead.');
			}
			else {
				$this->task_list_model->delete($id);
				$this->session->set_userdata('success', 'Task deleted');
			}
			
			
        } else {
            $this->session->set_userdata('errors', 'Invalid Request');
        }
		redirect(base_url().'hr/admin/task-list');
    }

}
