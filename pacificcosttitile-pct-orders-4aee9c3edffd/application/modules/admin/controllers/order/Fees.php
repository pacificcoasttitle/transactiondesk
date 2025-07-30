<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fees extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/fees_model');
        $this->load->model('order/feesTypes_model');
        $this->load->model('order/titleOfficer');
        $this->load->library('order/common');
        $this->common->is_admin();
    }

    public function index()
    {
        $data = array();
        $data['title'] = 'PCT Order: Fees';
        $this->admintemplate->show("order/home", "fees", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/fees', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function add_fee()
    {
        $data = array();

        $data['title'] = 'PCT Order: Add Fee';

        $feesData = array();

        // If import request is submitted
        if ($this->input->post()) {
            $this->form_validation->set_rules('fee_name', 'Fee name', 'required', array('required' => 'Enter fee name'));
            $this->form_validation->set_rules('fee_value', 'Fee value', 'required', array('required' => 'Enter Fee value'));
            $this->form_validation->set_rules('txn_type', 'Transaction Type', 'required', array('required' => 'Select transaction type'));
            $this->form_validation->set_rules('fee_type', 'Fee Type', 'required', array('required' => 'Select fee type'));

            if ($this->form_validation->run() == true) {
                $id = $this->input->post('fee_id');
                // Prepare data for DB insertion
                $feesData = array(
                    'transaction_type' => $_POST['txn_type'],
                    'fee_type_id' => $_POST['fee_type'],
                    'name' => $_POST['fee_name'],
                    'title_officer' => $_POST['title_officer'] ?? 0,
                    'value' => $_POST['fee_value'],
                    'status' => 1,
                );

                if ($id) {
                    $condition = array('id' => $id);

                    $update = $this->fees_model->update($feesData, $condition);

                    if ($update) {
                        /** Save user Activity */
                        $activity = 'Fees updated successfully : ' . $_POST['fee_name'];
                        $this->common->logAdminActivity($activity);
                        /** End Save user activity */
                        $successMsg = 'Fees updated successfully.';
                        $this->session->set_userdata('success_msg', $successMsg);
                    }
                } else {
                    // Insert member data
                    $insert = $this->fees_model->insert($feesData);

                    if ($insert) {
                        /** Save user Activity */
                        $activity = 'Fees created successfully : ' . $_POST['fee_name'];
                        $this->common->logAdminActivity($activity);
                        /** End Save user activity */
                        $data['success_msg'] = 'Fees added successfully.';
                    } else {
                        $data['error_msg'] = 'Fees not added.';
                    }
                }

            } else {
                $data['txn_type_error_msg'] = form_error('txn_type');
                $data['fee_type_id_error_msg'] = form_error('fee_type');
                $data['name_error_msg'] = form_error('fee_name');
                $data['value_error_msg'] = form_error('fee_value');
            }
        }
        $con = array(
            'where' => array(
                'status' => 1,
            ),
        );
        $fee_types = $this->feesTypes_model->get_rows($con);
        $data['fee_types'] = $fee_types;
        $condition = array(
            'where' => array(
                'status' => 1,
            ),
        );

        $data['titleOfficer'] = $this->titleOfficer->getTitleOfficerDetails($condition);
        $this->admintemplate->show("order/home", "add_fee", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/add_fee', $data);
        // $this->load->view('order/layout/footer', $data);

        // redirect('index.php?admin/fees');
    }

    public function get_fees()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';

            $pageno = ($params['start'] / $params['length']) + 1;

            $fees_list = $this->fees_model->getFees($params);

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $fees_list = $this->fees_model->getFees($params);
        }
        $data = array();
        if (isset($fees_list['data']) && !empty($fees_list['data'])) {
            $count = $params['start'] + 1;
            foreach ($fees_list['data'] as $key => $value) {
                $nestedData = array();

                $nestedData[] = $count;
                $nestedData[] = $value['transaction_type'];
                $nestedData[] = $value['fees_type_name'];
                $nestedData[] = $value['name'];
                $nestedData[] = $value['value'];
                // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created_at']));
                $editUrl = base_url() . 'order/admin/edit-fee/' . $value['id'];

                $action = '<div class="table-action" ><a href="' . $editUrl . '" ><span class="fas fa-edit " aria-hidden="true"></span></a>';
                $action .= '<a href="javascript:void(0);" onclick="deleteFees(' . $value['id'] . ');" ><span class="fas fa-trash" aria-hidden="true"></span></a></div>';
                $nestedData[] = $action;
                $data[] = $nestedData;
                $count++;

            }
        }
        $json_data['recordsTotal'] = intval($fees_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($fees_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function delete_fees()
    {
        $id = $this->input->post('id');
        $data = array();
        if ($id) {
            $feesData = array('status' => 0);

            $condition = array('id' => $id);
            $fees_info = $this->fees_model->get_rows($condition);
            $update = $this->fees_model->update($feesData, $condition);

            if ($update) {
                /** Save user Activity */
                $activity = 'Fees deleted successfully : ' . $fees_info['name'];
                $this->common->logAdminActivity($activity);
                /** End Save user activity */
                $successMsg = 'Fees deleted successfully.';
                $data = array('status' => 'success', 'message' => $successMsg);
            }
        } else {
            $errorMsg = 'Fee Id is required.';
            $data = array('status' => 'error', 'message' => $errorMsg);
        }

        echo json_encode($data);exit;
    }

    public function edit_fee()
    {
        $data = array();

        $data['title'] = 'PCT Order: Edit Fee';

        $id = $this->uri->segment('4');

        if (isset($id) && !empty($id)) {
            if ($this->input->post()) {
                // Validations
                $this->form_validation->set_rules('fee_name', 'Fee name', 'required', array('required' => 'Enter fee name'));
                $this->form_validation->set_rules('fee_value', 'Fee value', 'required', array('required' => 'Enter Fee value'));
                $this->form_validation->set_rules('fee_value', 'Fee value', 'required', array('required' => 'Enter Fee value'));
                $this->form_validation->set_rules('txn_type', 'Transaction Type', 'required', array('required' => 'Select transaction type'));

                if ($this->form_validation->run() == true) {
                    $feesData = array(
                        'transaction_type' => $this->input->post('txn_type'),
                        'name' => $this->input->post('fee_name'),
                        'title_officer' => $_POST['title_officer'] ?? 0,
                        'fee_type_id' => $this->input->post('fee_type'),
                        'value' => $this->input->post('fee_value'),
                        'status' => 1,
                    );

                    $condition = array('id' => $id);

                    $update = $this->fees_model->update($feesData, $condition);

                    if ($update) {
                        /** Save user Activity */
                        $activity = 'Fees updated successfully : id : ' . $id . ' - Name: ' . $_POST['fee_name'];
                        $this->common->logAdminActivity($activity);
                        /** End Save user activity */
                        $data['success_msg'] = 'Fees updated successfully.';
                    } else {
                        $data['error_msg'] = 'Error occurred while updating fees.';
                    }
                } else {
                    $data['section_error_msg'] = form_error('section');
                    $data['txn_type_error_msg'] = form_error('txn_type');
                    $data['name_error_msg'] = form_error('fee_name');
                    $data['value_error_msg'] = form_error('fee_value');
                    $data['fee_type_id_error_msg'] = form_error('fee_type');
                }
            }
            $con = array('id' => $id);
            $fees_info = $this->fees_model->get_rows($con);
        } else {
            redirect(base_url() . 'fees');
        }
        $data['fees_info'] = $fees_info;
        $con = array(
            'where' => array(
                'status' => 1,
            ),
        );
        $fee_types = $this->feesTypes_model->get_rows($con);
        $data['fee_types'] = $fee_types;
        $condition = array(
            'where' => array(
                'status' => 1,
            ),
        );

        $data['titleOfficer'] = $this->titleOfficer->getTitleOfficerDetails($condition);
        $this->admintemplate->show("order/home", "edit_fee", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/edit_fee', $data);
        // $this->load->view('order/layout/footer', $data);
    }
}
