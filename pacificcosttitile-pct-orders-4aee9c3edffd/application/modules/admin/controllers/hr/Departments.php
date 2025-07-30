<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departments extends MX_Controller {

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
        $this->load->library('form_validation');
        $this->load->library('hr/adminTemplate');
        $this->load->model('hr/hr'); 
        $this->load->library('hr/common');
        $this->common->is_hr_admin();
    }

    public function index()
    {
        $data['title'] = 'HR-Center Departments';
        $data['page_title'] = 'Departments';
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
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "departments", $data);
    }

    public function getDepartments()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $departments = $this->hr->getDepartments($params);
            $json_data['draw'] = intval( $params['draw'] );
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $departments = $this->hr->getDepartments($params);            
        }

        $data = array(); 
        $count = $params['start'] + 1;
	    if (isset($departments['data']) && !empty($departments['data'])) {
	    	foreach ($departments['data'] as $key => $value)  {
	    		$nestedData=array();
                $nestedData[] = $count;
	            $nestedData[] = $value['name'];
                if(isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url().'hr/admin/edit-department/'.$value['id'];
                    $nestedData[] = '<div style="display:inline-flex;">
                                        <a href="'.$editUrl.'" class="btn btn-info btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-pencil-alt"></i>
                                            </span>
                                            <span class="text">Edit</span>
                                        </a>
                                        <a style="margin-left: 5px;" href="#" onclick="deleteDepartment('.$value["id"].')" class="btn btn-danger btn-icon-split btn-sm">
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
        $json_data['recordsTotal'] = intval( $departments['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $departments['recordsFiltered'] );
        $json_data['data'] = $data;
	    echo json_encode($json_data);
    }

    public function addDepartment()
    {
        $data['title'] = 'HR-Center Department';
        $data['page_title'] = 'Add Department';
    
        if ($this->input->post()) {
            $this->load->library('hr/common');
            $this->form_validation->set_rules('department_name', 'Department Name', 'required', array('required'=> 'Please Enter Department Name'));
        
            if ($this->form_validation->run() == true) {
                $departmentData = array(
                    'name' =>  $this->input->post('department_name'),
                    'status' =>  1
                );
                $this->hr->insert($departmentData, 'pct_hr_departments');
                $successMsg = 'Department name added successfully.';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url().'hr/admin/departments');
            } else {
                $data['department_name_error_msg'] = form_error('department_name');
            }                                       
        }
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "add_department", $data);
    }

    public function editDepartment()
    {
        $id = $this->uri->segment(4);
        $data['title'] = 'HR-Center Departments';
        $data['page_title'] = 'Edit Department';
    
        if(isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->load->library('hr/common');
                $this->form_validation->set_rules('department_name', 'Department Name', 'required', array('required'=> 'Please Enter Department Name'));
            
                if ($this->form_validation->run() == true) {
                    $userTypeData = array(
                        'name' =>  $this->input->post('department_name'),
                        'status' =>  1
                    );
                    $condition = array('id' => $id);
                    $this->hr->update($userTypeData, $condition, 'pct_hr_departments');
                    $successMsg = 'Department name edited successfully.';
                    $this->session->set_userdata('success', $successMsg);
                    redirect(base_url().'hr/admin/departments');
                } else {
                    $data['department_name_error_msg'] = form_error('department_name');
                }                                       
            }
            $data['departmentInfo'] = $this->hr->getDepartmentInfo($id);
        } else { 
            redirect(base_url().'hr/admin/departments');
        }
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "edit_department", $data);
    }

    public function deleteDepartment()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $userData = array('status' => 0);
            $condition = array('id' => $id);
            $update = $this->hr->update($userData, $condition, 'pct_hr_departments');
            if ($update) {
                $successMsg = 'Department deleted successfully.';
                $response = array('status'=>'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Department ID is required.';
            $response = array('status' => 'error','message'=>$msg);
        }
        echo json_encode($response);
    }
}
