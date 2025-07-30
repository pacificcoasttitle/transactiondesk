<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserTypes extends MX_Controller {

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
        $data['title'] = 'HR-Center User Types';
        $data['page_title'] = 'User Types';
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
        $this->admintemplate->show("hr", "user_types", $data);
    }

    public function getUserTypes()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $userTypes = $this->hr->getUserTypes($params);
            $json_data['draw'] = intval( $params['draw'] );
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $userTypes = $this->hr->getUserTypes($params);            
        }

        $data = array(); 
        $count = $params['start'] + 1;
	    if (isset($userTypes['data']) && !empty($userTypes['data'])) {
	    	foreach ($userTypes['data'] as $key => $value)  {
	    		$nestedData=array();
                $nestedData[] = $count;
	            $nestedData[] = $value['name'];
                if(isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url().'hr/admin/edit-user-type/'.$value['id'];
                    
                    $nestedData[] = '<div style="display:inline-flex;">
                                        <a href="'.$editUrl.'" class="btn btn-info btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-pencil-alt"></i>
                                            </span>
                                            <span class="text">Edit</span>
                                        </a>
                                        <a style="margin-left: 5px;" href="#" onclick="deleteUserType('.$value["id"].')" class="btn btn-danger btn-icon-split btn-sm">
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
        $json_data['recordsTotal'] = intval( $userTypes['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $userTypes['recordsFiltered'] );
        $json_data['data'] = $data;
	    echo json_encode($json_data);
    }

    public function addUserType()
    {
        $data['title'] = 'HR-Center User Types';
        $data['page_title'] = 'Add User Type';
    
        if ($this->input->post()) {
            $this->load->library('hr/common');
            $this->form_validation->set_rules('user_type_name', 'User Type Name', 'required', array('required'=> 'Please Enter User Type Name'));
        
            if ($this->form_validation->run() == true) {
                $userTypeData = array(
                    'name' =>  $this->input->post('user_type_name'),
                    'status' =>  1
                );
                $this->hr->insert($userTypeData, 'pct_hr_user_types');
                $successMsg = 'User Type added successfully.';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url().'hr/admin/user-types');
            } else {
                $data['user_type_name_error_msg'] = form_error('user_type_name');
            }                                       
        }
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "add_user_type", $data);
    }

    public function editUserType()
    {
        $id = $this->uri->segment(4);
        $data['title'] = 'HR-Center User Types';
        $data['page_title'] = 'Edit User Type';
    
        if(isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->load->library('hr/common');
                $this->form_validation->set_rules('user_type_name', 'User Type Name', 'required', array('required'=> 'Please Enter User Type Name'));
            
                if ($this->form_validation->run() == true) {
                    $userTypeData = array(
                        'name' =>  $this->input->post('user_type_name'),
                        'status' =>  1
                    );
                    $condition = array('id' => $id);
                    $this->hr->update($userTypeData, $condition, 'pct_hr_user_types');
                    $successMsg = 'User Type edited successfully.';
                    $this->session->set_userdata('success', $successMsg);
                    redirect(base_url().'hr/admin/user-types');
                } else {
                    $data['user_type_name_error_msg'] = form_error('user_type_name');
                }                                       
            }
            $data['userTypeInfo'] = $this->hr->getUserTypeInfo($id);
        } else { 
            redirect(base_url().'hr/admin/user-types');
        }
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "edit_user_type", $data);
    }

    public function deleteUserType()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $userData = array('status' => 0);
            $condition = array('id' => $id);
            $update = $this->hr->update($userData, $condition, 'pct_hr_user_types');
            if ($update) {
                $successMsg = 'User Type deleted successfully.';
                $response = array('status'=>'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'User Type ID is required.';
            $response = array('status' => 'error','message'=>$msg);
        }
        echo json_encode($response);
    }
}
