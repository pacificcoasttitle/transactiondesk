<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends MX_Controller {

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

    private $task_js_version = '02';
	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('form_validation');
        $this->load->library('hr/adminTemplate');
        $this->load->model('hr/hr'); 
        $this->load->library('hr/common');
        $this->load->model('hr/branches_model');
        $this->load->model('escrow/tasks_model');
        $this->common->is_hr_admin();
    }

    public function index()
    {
        $data['title'] = 'Tasks';
        $data['page_title'] = 'Escrow Tasks';
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
        $this->admintemplate->addJS( base_url('assets/backend/escrow/js/tasks.js?v=tasks_'.$this->task_js_version) );
        $this->admintemplate->show("hr", "tasks", $data);
    }

    public function getTasks()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $taskData = $this->common->getTasks($params);
            $json_data['draw'] = intval( $params['draw'] );
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $taskData = $this->common->getTasks($params);            
        }

        $data = array(); 
        $count = $params['start'] + 1;
	    if (isset($taskData['data']) && !empty($taskData['data'])) {
	    	foreach ($taskData['data'] as $key => $value)  {
	    		$nestedData=array();
                $nestedData[] = $count;
	            $nestedData[] = $value['name'];
                $nestedData[] = ucfirst($value['prod_type']);
                if ($value['parent_task_id'] > 0) {
                    $parentTaskInfo = $this->tasks_model->get($value['parent_task_id']);
                    $parentTaskName = $parentTaskInfo->name;
                } else {
                    $parentTaskName = '';
                }
                $nestedData[] = $parentTaskName;
                $nestedData[] = $value['notes'];
                if(isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url().'hr/admin/edit-task/'.$value['id'];
                    
                    $nestedData[] = '<div style="display:inline-flex;">
                                        <a href="'.$editUrl.'" class="btn btn-info btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-pencil-alt"></i>
                                            </span>
                                            <span class="text">Edit</span>
                                        </a>
                                        <a style="margin-left: 5px;" href="#" onclick="deleteTask('.$value["id"].')" class="btn btn-danger btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span class="text">Delete</span>
                                        </a>
                                    </div>';
                }
	            $data[] = $nestedData;    
                $count++;          
	    	}
	    }
        $json_data['recordsTotal'] = intval( $taskData['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $taskData['recordsFiltered'] );
        $json_data['data'] = $data;
	    echo json_encode($json_data);
    }

    public function addTask()
    {
        $data['title'] = 'Escrow Tasks';
        $data['page_title'] = 'Add Task';
    
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Task Name', 'required', array('required'=> 'Please Enter Task Name'));
            $this->form_validation->set_rules('prod_type', 'Product Type', 'required', array('required'=> 'Please Check Product Type'));
        
            if ($this->form_validation->run() == true) {
                $taskData = array(
                    'name' =>  $this->input->post('name'),
                    'prod_type' =>  $this->input->post('prod_type'),
                    'notes' =>  $this->input->post('notes'),
                    'parent_task_id' => $this->input->post('parent_task_id') ? $this->input->post('parent_task_id') : 0,
                    'status' =>  1
                );
                $this->hr->insert($taskData, 'pct_escrow_tasks');
                $successMsg = 'Task added successfully.';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url().'hr/admin/tasks');
            } else {
                $data['name_error_msg'] = form_error('name');
                $data['prod_type_error_msg'] = form_error('prod_type');
            }                                       
        }
        $this->load->model('escrow/tasks_model');
		$data['tasks'] = $this->tasks_model->get_many_by("parent_task_id = 0");
        $this->admintemplate->show("hr", "add_task", $data);
    }

    public function editTask($id)
    {
        $data['title'] = 'Escrow Tasks';
        $data['page_title'] = 'Edit Task';
    
        if(isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('name', 'Task Name', 'required', array('required'=> 'Please Enter Task Name'));
                $this->form_validation->set_rules('prod_type', 'Product Type', 'required', array('required'=> 'Please Check Product Type'));
            
                if ($this->form_validation->run() == true) {
                    $taskData = array(
                        'name' =>  $this->input->post('name'),
                        'prod_type' =>  $this->input->post('prod_type'),
                        'parent_task_id' => $this->input->post('parent_task_id') ? $this->input->post('parent_task_id') : 0,
                        'notes' =>  $this->input->post('notes'),
                    );
                    $condition = array('id' => $id);
                    $this->hr->update($taskData, $condition, 'pct_escrow_tasks');
                    $successMsg = 'Task edited successfully.';
                    $this->session->set_userdata('success', $successMsg);
                    redirect(base_url().'hr/admin/tasks');
                } else {
                    $data['name_error_msg'] = form_error('name');
                    $data['prod_type_error_msg'] = form_error('prod_type');
                }                                       
            }
            $data['taskInfo'] = $this->tasks_model->get($id);
        } else { 
            redirect(base_url().'hr/admin/tasks');
        }
        $this->load->model('escrow/tasks_model');
		$data['tasks'] = $this->tasks_model->get_many_by("parent_task_id = 0");
        $this->admintemplate->show("hr", "edit_task", $data);
    }

    public function deleteTask()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $taskData = array('status' => 0);
            $condition = array('id' => $id);
            $update = $this->hr->update($taskData, $condition, 'pct_escrow_tasks');
            if ($update) {
                $successMsg = 'Task deleted successfully.';
                $response = array('status'=>'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Task ID is required.';
            $response = array('status' => 'error','message'=>$msg);
        }
        echo json_encode($response);
    }

    public function loanTasksPosition() 
    {
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
        $this->load->model('escrow/tasks_model');
        $data['tasks'] = json_decode(json_encode($this->tasks_model->order_by('loan_position', 'asc')->get_many_by("(status = 1 and (prod_type = 'both' or prod_type = 'loan') )")), true);
        $this->admintemplate->addJS( base_url('assets/backend/escrow/js/tasks.js?v=tasks_'.$this->task_js_version) );
        $this->admintemplate->show("hr", "loan_tasks_position", $data);
    }

    public function saleTasksPosition() 
    {
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
        $this->load->model('escrow/tasks_model');
        $data['tasks'] = json_decode(json_encode($this->tasks_model->order_by('sale_position', 'asc')->get_many_by("(status = 1 and (prod_type = 'both' or prod_type = 'sale') )")), true);
        $this->admintemplate->addJS( base_url('assets/backend/escrow/js/tasks.js?v=tasks_'.$this->task_js_version) );
        $this->admintemplate->show("hr", "sale_tasks_position", $data);
    }

    public function saveLoanTasksPosition() 
    {
        $this->load->model('escrow/tasks_model');
        $tasks = $this->tasks_model->get_many_by("(status = 1 and (prod_type = 'both' or prod_type = 'loan') )");
        foreach ($tasks as $task) {
            $taskData = array('loan_position' => $this->input->post('task_position_'.$task->id));
            $condition = array('id' => $task->id);
            $this->hr->update($taskData, $condition, 'pct_escrow_tasks');
        }
        $successMsg = 'Loan Tasks postion updated successfully.';
        $this->session->set_userdata('success', $successMsg);
        redirect(base_url().'hr/admin/loan-tasks-position');
    }

    public function saveSaleTasksPosition() 
    {
        $this->load->model('escrow/tasks_model');
        $tasks = $this->tasks_model->get_many_by("(status = 1 and (prod_type = 'both' or prod_type = 'sale') )");
        foreach ($tasks as $task) {
            $taskData = array('sale_position' => $this->input->post('task_position_'.$task->id));
            $condition = array('id' => $task->id);
            $this->hr->update($taskData, $condition, 'pct_escrow_tasks');
        }
        $successMsg = 'Sale Tasks postion updated successfully.';
        $this->session->set_userdata('success', $successMsg);
        redirect(base_url().'hr/admin/sale-tasks-position');
    }
}
