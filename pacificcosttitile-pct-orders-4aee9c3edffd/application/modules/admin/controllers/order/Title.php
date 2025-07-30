<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Title extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/title_model');
        $this->load->library('order/common');
        $this->common->is_admin();
    }

    public function index()
    {
        $data = array();
        $data['title'] = 'PCT Order: Title Officers';
        $this->admintemplate->show("order/title", "title", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/title/title', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_title_officer_list()
    {
        $params = array();
        $data = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $title_officer_lists = $this->title_model->get_title_officers($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $title_officer_lists = $this->title_model->get_title_officers($params);
        }

        if (isset($title_officer_lists['data']) && !empty($title_officer_lists['data'])) {
            foreach ($title_officer_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['telephone_no'];
                $nestedData[] = $value['partner_id'];
                $nestedData[] = $value['partner_type_id'];
                $id = $value['id'];
                /*if ($value['email_receive_flag'] == 1) {
                $checked = 'checked';
                } else {
                $checked = '';
                }*/
                // $nestedData[] = "<input $checked onclick='updateTitleOfficerEmailReceiveFlag();' style='height:30px;width:20px;' type='checkbox' id='$id' name='$id'>";

                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editOrderUrl = base_url() . 'order/admin/edit-title-officer/' . $value['id'];
                    $action = "<div style='display: flex;justify-content: space-evenly;' ><a href='" . $editOrderUrl . "' class='edit-agent'title ='Edit Title Officer Detail'><i class='fas fa-edit' aria-hidden='true'></i></a>";
                    $action .= "<a href='javascript:void(0);' onclick='deleteTitleOfficer(" . $value['id'] . ")' title='Delete Title Officer'><i class='fas fa-trash' aria-hidden='true'></i></a></div>";
                    $nestedData[] = $action;
                }
                $data[] = $nestedData;
            }
        }

        $json_data['recordsTotal'] = intval($title_officer_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($title_officer_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function add_title_officer()
    {
        $data = array();
        $data['title'] = 'PCT Order: Add Title Officer.';
        $titleOfficerData = array();

        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required', array('required' => 'Please Enter First Name'));
            $this->form_validation->set_rules('last_name', 'Last Name', 'required', array('required' => 'Please Enter Last Name'));
            $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
            $this->form_validation->set_rules('telephone', 'Phone Number', 'required', array('required' => 'Please Enter Phone Number'));
            $this->form_validation->set_rules('partner_id', 'Partner Id', 'trim|required|numeric', array('required' => 'Please Enter Partner Id'));
            $this->form_validation->set_rules('partner_type_id', 'Partner Type Id', 'trim|required|numeric', array('required' => 'Please Enter Partner Type Id'));

            if ($this->form_validation->run() == true) {
                $titleOfficerData = array(
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'email_address' => $_POST['email_address'],
                    'telephone_no' => $_POST['telephone'],
                    'partner_id' => $_POST['partner_id'],
                    'partner_type_id' => $_POST['partner_type_id'],
                    'is_password_updated' => 1,
                    'is_title_officer' => 1,
                    'status' => 1,
                );
                $insert = $this->title_model->insert($titleOfficerData);
                /** Save user Activity */
                $activity = 'Title officer created :- ' . $_POST['email_address'];
                $this->common->logAdminActivity($activity);
                /** End save user activity */
                if ($insert) {
                    $data['success_msg'] = 'Title Officer added successfully.';
                } else {
                    $data['error_msg'] = 'Title Officer not added.';
                }

            } else {
                $data['first_name_error_msg'] = form_error('first_name');
                $data['last_name_error_msg'] = form_error('last_name');
                $data['email_error_msg'] = form_error('email_address');
                $data['phone_error_msg'] = form_error('telephone');
                $data['partner_id_error_msg'] = form_error('partner_id');
                $data['partner_type_id_error_msg'] = form_error('partner_type_id');
            }
        }

        $this->admintemplate->show("order/title", "add_title_officer", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/title/add_title_officer', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function edit_title_officer()
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Title Officer';
        $id = $this->uri->segment('4');

        if (isset($id) && !empty($id)) {
            if (isset($_POST) && !empty($_POST)) {

                $this->form_validation->set_rules('first_name', 'First Name', 'required', array('required' => 'Please Enter First Name'));
                $this->form_validation->set_rules('last_name', 'Last Name', 'required', array('required' => 'Please Enter Last Name'));
                $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
                $this->form_validation->set_rules('telephone', 'Phone Number', 'required', array('required' => 'Please Enter Phone Number'));
                $this->form_validation->set_rules('partner_id', 'Partner Id', 'trim|required|numeric', array('required' => 'Please Enter Partner Id'));
                $this->form_validation->set_rules('partner_type_id', 'Partner Type Id', 'trim|required|numeric', array('required' => 'Please Enter Partner Type Id'));

                if ($this->form_validation->run() == true) {
                    $titleOfficerData = array(
                        'first_name' => $_POST['first_name'],
                        'last_name' => $_POST['last_name'],
                        'email_address' => $_POST['email_address'],
                        'telephone_no' => $_POST['telephone'],
                        'partner_id' => $_POST['partner_id'],
                        'partner_type_id' => $_POST['partner_type_id'],
                        'is_password_updated' => 1,
                        'is_title_officer' => 1,
                        'status' => 1,
                    );
                    $condition = array('id' => $id);
                    $update = $this->title_model->update($titleOfficerData, $condition);
                    /** Save user Activity */
                    $activity = 'Title officer updated :- ' . $_POST['email_address'];
                    $this->common->logAdminActivity($activity);
                    /** End save user activity */
                    if ($update) {
                        $data['success_msg'] = 'Title Officer updated successfully.';
                    } else {
                        $data['error_msg'] = 'Error occurred while updating Title Officer';
                    }
                } else {
                    $data['first_name_error_msg'] = form_error('first_name');
                    $data['last_name_error_msg'] = form_error('last_name');
                    $data['email_error_msg'] = form_error('email_address');
                    $data['phone_error_msg'] = form_error('telephone');
                    $data['partner_id_error_msg'] = form_error('partner_id');
                    $data['partner_type_id_error_msg'] = form_error('partner_type_id');
                }
            }
            $con = array('id' => $id);
            $title_officer_info = $this->title_model->getTitleOfficers($con);
        } else {
            redirect('order/admin/title-officers');
        }

        $data['title_officer_info'] = $title_officer_info;
        $this->admintemplate->show("order/title", "edit_title_officer", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/title/edit_title_officer', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function delete_title_officer()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $titleOfficerData = array('status' => 0);
            $condition = array('id' => $id);
            $titleOfficer = $this->title_model->getTitleOfficers($condition);
            $update = $this->title_model->update($titleOfficerData, $condition);
            if ($update) {
                /** Save user Activity */
                $activity = 'Title officer deleted :- ' . $titleOfficer['email_address'];
                $this->common->logAdminActivity($activity);
                /** End save user activity */
                $successMsg = 'Title Officer deleted successfully.';
                $response = array('status' => 'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Title Officer ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }
        echo json_encode($response);
    }

    public function updateTitleOfficerEmailFlag()
    {
        $title_officer_id = $this->input->post('title_officer_id');
        $displayFlag = $this->input->post('displayFlag');
        $data['email_receive_flag'] = $displayFlag;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $title_officer_id,
        );
        $this->db->update('customer_basic_details', $data, $condition);
        $data = array('status' => 'success', 'msg' => 'Flag updated successfully.');
        echo json_encode($data);
    }
}
