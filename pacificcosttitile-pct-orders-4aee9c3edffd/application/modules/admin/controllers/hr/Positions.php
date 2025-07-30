<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Positions extends MX_Controller {

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
        $data['title'] = 'HR-Center Positions';
        $data['page_title'] = 'Positions';
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
        $this->admintemplate->show("hr", "positions", $data);
    }

    public function getPositions()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $positions = $this->hr->getPositions($params);
            $json_data['draw'] = intval( $params['draw'] );
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $positions = $this->hr->getPositions($params);            
        }

        $data = array(); 
        $count = $params['start'] + 1;
	    if (isset($positions['data']) && !empty($positions['data'])) {
	    	foreach ($positions['data'] as $key => $value)  {
	    		$nestedData=array();
                $nestedData[] = $count;
	            $nestedData[] = $value['name'];
                if(isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url().'hr/admin/edit-position/'.$value['id'];
                    
                    $nestedData[] = '<div style="display:inline-flex;">
                                        <a href="'.$editUrl.'" class="btn btn-info btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-pencil-alt"></i>
                                            </span>
                                            <span class="text">Edit</span>
                                        </a>
                                        <a style="margin-left: 5px;" href="#" onclick="deletePosition('.$value["id"].')" class="btn btn-danger btn-icon-split btn-sm">
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
        $json_data['recordsTotal'] = intval( $positions['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $positions['recordsFiltered'] );
        $json_data['data'] = $data;
	    echo json_encode($json_data);
    }

    public function addPosition()
    {
        $data['title'] = 'HR-Center Positions';
        $data['page_title'] = 'Add Position';
    
        if ($this->input->post()) {
            $this->load->library('hr/common');
            $this->form_validation->set_rules('position_name', 'Position Name', 'required', array('required'=> 'Please Enter Position Name'));
        
            if ($this->form_validation->run() == true) {
                $positionData = array(
                    'name' =>  $this->input->post('position_name'),
                    'status' =>  1
                );
                $this->hr->insert($positionData, 'pct_hr_position');
                $successMsg = 'Position added successfully.';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url().'hr/admin/positions');
            } else {
                $data['position_name_error_msg'] = form_error('position_name');
            }                                       
        }
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "add_position", $data);
    }

    public function editPosition()
    {
        $id = $this->uri->segment(4);
        $data['title'] = 'HR-Center Positions';
        $data['page_title'] = 'Edit Position';
    
        if(isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->load->library('hr/common');
                $this->form_validation->set_rules('position_name', 'Position Name', 'required', array('required'=> 'Please Enter Position Name'));
            
                if ($this->form_validation->run() == true) {
                    $userTypeData = array(
                        'name' =>  $this->input->post('position_name'),
                        'status' =>  1
                    );
                    $condition = array('id' => $id);
                    $this->hr->update($userTypeData, $condition, 'pct_hr_position');
                    $successMsg = 'Position edited successfully.';
                    $this->session->set_userdata('success', $successMsg);
                    redirect(base_url().'hr/admin/positions');
                } else {
                    $data['position_name_error_msg'] = form_error('position_name');
                }                                       
            }
            $data['positionInfo'] = $this->hr->getPositionInfo($id);
        } else { 
            redirect(base_url().'hr/admin/positions');
        }
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "edit_position", $data);
    }

    public function deletePosition()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $userData = array('status' => 0);
            $condition = array('id' => $id);
            $update = $this->hr->update($userData, $condition, 'pct_hr_position');
            if ($update) {
                $successMsg = 'Position deleted successfully.';
                $response = array('status'=>'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Position ID is required.';
            $response = array('status' => 'error','message'=>$msg);
        }
        echo json_encode($response);
    }
}
