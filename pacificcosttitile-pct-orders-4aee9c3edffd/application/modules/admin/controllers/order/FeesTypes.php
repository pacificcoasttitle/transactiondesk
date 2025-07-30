<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FeesTypes extends MX_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/feesTypes_model');
        $this->load->library('order/common');
        $this->common->is_admin();
    }

    public function index()
	{
		$data = array();
        $data['title'] = 'PCT Order: Fees Types';
        $this->admintemplate->show("order/home", "fees_types", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/fees_types', $data);
        // $this->load->view('order/layout/footer', $data);
	}

    public function add_fee_type()
    {
        $data = array();

        $data['title'] = 'PCT Order: Add Fee Type';

        $feesData = array();

        // If import request is submitted
        if($this->input->post())
        {
            $this->form_validation->set_rules('fee_type', 'Fee Type', 'required',array('required'=> 'Please enter fee type'));

            if($this->form_validation->run() == true)
            {
                $id = $this->input->post('fee_id');
                // Prepare data for DB insertion
                $feesData = array(
                    'name' =>  $_POST['fee_type'],
                    'status' => 1
                );

                if($id)
                {
                    $condition = array('id' => $id);

                    $update = $this->feesTypes_model->update($feesData, $condition);

                    if($update)
                    {
                        /** Save user Activity */
                        $activity = 'Fees type updated successfully : ' . $_POST['fee_type'];
                        $this->common->logAdminActivity($activity);
                        /** End Save user activity */
                        $successMsg = 'Fee Type updated successfully.';
                        $this->session->set_userdata('success_msg', $successMsg);
                    }
                }
                else
                {                  
                    // Insert member data
                    $insert = $this->feesTypes_model->insert($feesData);
                    
                    if($insert){
                        /** Save user Activity */
                        $activity = 'New Fees type created successfully : ' . $_POST['fee_type'];
                        $this->common->logAdminActivity($activity);
                        /** End Save user activity */
                        $data['success_msg'] = 'Fees type added successfully.';
                    }
                    else
                    {
                        $data['error_msg'] = 'Fee type not added.';
                    } 
                }
                
            }
            else
            {
                $data['name_error_msg'] = form_error('fee_type');
            }                                       
        }
        $this->admintemplate->show("order/home", "add_fee_type", $data);
    }

    public function get_fees_types()
    {
    	$params = array();

        if(isset($_POST['draw']) && !empty($_POST['draw']))
        {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            

            $pageno = ($params['start'] / $params['length'])+1;

            $fees_list = $this->feesTypes_model->getFeesTypes($params);

            $json_data['draw'] = intval( $params['draw'] );
        }
        else
        {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $fees_list = $this->feesTypes_model->getFeesTypes($params);          
        }
        $data = array();

        if(isset($fees_list['data']) && !empty($fees_list['data']))
        {
            $count = $params['start'] + 1;
            foreach ($fees_list['data'] as $key => $value) 
            {
                $nestedData=array();
                
                $nestedData[] = $count;
                $nestedData[] = $value['name'];
                // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created_at']));
                $editUrl = base_url().'order/admin/edit-fee-type/'.$value['id'];
                

                $action = '<div class="table-action" ><a href="'.$editUrl.'" ><span class="fas fa-edit " aria-hidden="true"></span></a>';
                $action .= '<a href="javascript:void(0);" onclick="deleteFeesType('.$value['id'].');" ><span class="fas fa-trash aria-hidden="true"></span></a></div>';
                $nestedData[] = $action;
                $data[] = $nestedData;
                $count++;
                
            }
        }
        $json_data['recordsTotal'] = intval( $fees_list['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $fees_list['recordsFiltered'] );
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function delete_fee_type()
    {
    	$id = $this->input->post('id');
        $data = array();
        if($id)
        {
            $feesData = array('status' => 0);
            $condition = array('id' => $id);
            $fees_info = $this->feesTypes_model->get_rows($condition);
            $update = $this->feesTypes_model->update($feesData, $condition);

            if($update)
            {
                /** Save user Activity */
                $activity = 'Fees type deleted successfully : ' . $fees_info['name'];
                $this->common->logAdminActivity($activity);
                /** End Save user activity */

                $successMsg = 'Fee type deleted successfully.';
                $data = array('status'=>'success', 'message'=>$successMsg);
            }
        }
        else
        {
        	$errorMsg = 'Fee Id is required.';
            $data = array('status'=>'error', 'message'=>$errorMsg);
        }

        echo json_encode($data); exit;
    }

    public function edit_fee_type()
    {
        $data = array();
        
        $data['title'] = 'PCT Order: Edit Fee Type';

        $id = $this->uri->segment('4');

        if(isset($id) && !empty($id))
        {
            if($this->input->post())
            {
                // Validations
                $this->form_validation->set_rules('fee_type', 'Fee Type', 'required',array('required'=> 'Please enter fee type'));

                if($this->form_validation->run() == true)
                {
                    $feesData = array(
                        'name' =>  $_POST['fee_type'],
                        'status' => 1
                    );

                    $condition = array('id' => $id);

                    $update = $this->feesTypes_model->update($feesData,$condition);
                        
                    if($update){
                        /** Save user Activity */
                        $activity = 'Fees type updated successfully : ' . $_POST['fee_type'];
                        $this->common->logAdminActivity($activity);
                        /** End Save user activity */
                        $data['success_msg'] = 'Fees type updated successfully.';
                    }
                    else
                    {
                        $data['error_msg'] = 'Error occurred while updating fees type.';
                    }
                }
                else
                {                    
                    $data['name_error_msg'] = form_error('fee_type');
                }
            }
            $con = array('id' => $id);
            $fees_info = $this->feesTypes_model->get_rows($con);
        }
        else
        {
            redirect(base_url().'fees');
        }

        $data['fees_info'] = $fees_info;
        $this->admintemplate->show("order/home", "edit_fee_type", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/edit_fee_type', $data);
		// $this->load->view('order/layout/footer', $data);
    }
}