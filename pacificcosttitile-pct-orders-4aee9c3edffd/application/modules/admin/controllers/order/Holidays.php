<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holidays extends MX_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/holidays_model');
        $this->load->library('order/common');
        $this->common->is_admin();
    }

    public function index()
	{
		$data = array();
        $data['title'] = 'PCT Order: Holidays';
        $this->admintemplate->show("order/holiday", "holidays", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/holiday/holidays', $data);
        // $this->load->view('order/layout/footer', $data);
	}

    public function add_holiday()
    {
        $data = array();
        $data['title'] = 'PCT Order: Add holiday';
        $holidayData = array();

        if($this->input->post()) {
            $this->form_validation->set_rules('holiday_name', 'Holiday name', 'required',array('required'=> 'Enter Holiday Name'));
            $this->form_validation->set_rules('holiday_date', 'Holiday Date', 'required',array('required'=> 'Select Holiday Date'));
            
            if($this->form_validation->run() == true) {
                $holidayData = array(
                    'name' => $_POST['holiday_name'],
                    'holiday_date' => date("Y-m-d", strtotime($_POST['holiday_date'])),
                    'created_at' =>  date('Y-m-d H:i:s')
                );
                $insert = $this->holidays_model->insert($holidayData);
                
                if ($insert) {
                    /** Save user Activity */
                    $activity = 'Holiday Added successfully: .' . $_POST['holiday_name'];
                    $this->common->logAdminActivity($activity);
                    /** End save user activity */
                    $data['success_msg'] = 'Holiday added successfully.';
                } else {
                    $data['error_msg'] = 'Holiday not added.';
                } 
            } else {
                $data['holiday_name_error_msg'] = form_error('holiday_name');
                $data['holiday_date_error_msg'] = form_error('holiday_date');
            }                                       
        }
        $this->admintemplate->addJS( base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->show("order/holiday", "add_holiday", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/holiday/add_holiday', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_holidays()
    {
    	$params = array();
        if(isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length'])+1;
            $holidays_list = $this->holidays_model->getHolidays($params);
            $json_data['draw'] = intval( $params['draw'] );
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $holidays_list = $this->holidays_model->getHolidays($params);          
        }

        $data = array();
        if(isset($holidays_list['data']) && !empty($holidays_list['data'])) {
            $count = $params['start'] + 1;

            foreach ($holidays_list['data'] as $key => $value) {
                $nestedData=array();
                $nestedData[] = $count;
                $nestedData[] = $value['name'];
                $nestedData[] = date("m/d/Y", strtotime($value['holiday_date']));
                $editUrl = base_url().'order/admin/edit-holiday/'.$value['id'];
                $action = '<div class="table-action"><a href="'.$editUrl.'"><span class="fas fa-edit " aria-hidden="true"></span></a>';
                $action .= '<a href="javascript:void(0);" onclick="deleteHoliday('.$value['id'].');"><span class="fas fa-trash" aria-hidden="true"></span></a></div>';
                $nestedData[] = $action;
                $data[] = $nestedData;
                $count++;
            }
        }
        $json_data['recordsTotal'] = intval( $holidays_list['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $holidays_list['recordsFiltered'] );
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function delete_holiday()
    {
    	$id = $this->input->post('id');
        $data = array();
        if ($id) {
            $con = array('id' => $id);
            $holiday_info = $this->holidays_model->get_rows($con);
            $this->db->where('id', $id);
            $this->db->delete('pct_holidays');
            /** Save user Activity */
            $activity = 'Holiday deleted successfully: .' . $holiday_info['name'];
            $this->common->logAdminActivity($activity);
            /** End save user activity */
            $successMsg = 'Holiday deleted successfully.';
            $data = array('status'=>'success', 'message' => $successMsg);
        } else {
        	$errorMsg = 'Holiday Id is required.';
            $data = array('status'=>'error', 'message' => $errorMsg);
        }
        echo json_encode($data); exit;
    }

    public function edit_holiday()
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Holiday';
        $id = $this->uri->segment('4');

        if (isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('holiday_name', 'Holiday name', 'required',array('required'=> 'Enter Holiday Name'));
                $this->form_validation->set_rules('holiday_date', 'Holiday Date', 'required',array('required'=> 'Select Holiday Date'));

                if($this->form_validation->run() == true) {
                    $holidayData = array(
                        'name' => $_POST['holiday_name'],
                        'holiday_date' => date("Y-m-d", strtotime($_POST['holiday_date'])),
                        'created_at' =>  date('Y-m-d H:i:s')
                    );
                    $condition = array('id' => $id);
                    $update = $this->holidays_model->update($holidayData, $condition);   
                    if ($update) {
                        /** Save user Activity */
                        $activity = 'Holiday updated successfully: .' . $_POST['holiday_name'];
                        $this->common->logAdminActivity($activity);
                        /** End save user activity */
                        $data['success_msg'] = 'Fees updated successfully.';
                    } else {
                        $data['error_msg'] = 'Error occurred while updating holiday.';
                    }
                } else {
                    $data['holiday_name_error_msg'] = form_error('holiday_name');
                    $data['holiday_date_error_msg'] = form_error('holiday_date');
                }
            }
            $con = array('id' => $id);
            $holiday_info = $this->holidays_model->get_rows($con);
        } else {
            redirect(base_url().'holidays');
        }
        $data['holiday_info'] = $holiday_info;
        $this->admintemplate->addJS( base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->show("order/holiday", "edit_holiday", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/holiday/edit_holiday', $data);
		// $this->load->view('order/layout/footer', $data);
    }
}