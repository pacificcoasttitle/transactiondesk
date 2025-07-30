<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EscrowInstruction extends MX_Controller 
{
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
        $this->load->model('hr/escrow_instruction_model');
        $this->load->model('hr/escrow_instruction_value_model');
        $this->load->library('hr/common');
        $this->common->is_hr_admin();
    }
    
    public function index()
    {
        $data['title'] = 'HR-Center Escrow Instruction';
        $data['page_title'] = 'Escrow Instruction';
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
        $this->admintemplate->addJS( base_url('assets/plugins/ckeditor/ckeditor.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "escrow_instruction", $data);
    }

    public function get_escrow_instruction_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 1;
            $pageno = ($params['start'] / $params['length'])+1;
            $escrow_ins_lists = $this->hr->get_escrow_instruction_list($params);
            $json_data['draw'] = intval( $params['draw'] );
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $escrow_ins_lists = $this->hr->get_escrow_instruction_list($params);            
        }

        $data = array(); 
	    if (isset($escrow_ins_lists['data']) && !empty($escrow_ins_lists['data'])) {
	    	foreach ($escrow_ins_lists['data'] as $key => $value) {
	    		$nestedData=array();
                
	            $nestedData[] = $value['instruction_name'];
	            $nestedData[] = $value['custom_field_value_id'];
	            $nestedData[] = $value['custom_field_id'];
	            $nestedData[] = $value['name'];
	            $nestedData[] = $value['value'];  
	                
                if(isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url().'hr/admin/edit-escrow-instruction/'.$value['id'];
                    $nestedData[] = '<div style="display:inline-flex;">
                                        <a href="'.$editUrl.'" class="btn btn-info btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-pencil-alt"></i>
                                            </span>
                                            <span class="text">Edit</span>
                                        </a>
                                        <a style="margin-left: 5px;" href="#" onclick="deleteEscrowInstruction('.$value["id"].')" class="btn btn-danger btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span class="text">Delete</span>
                                        </a>
                                    </div>';
                }
	            $data[] = $nestedData;
	    	}
	    }
        $json_data['recordsTotal'] = intval( $escrow_ins_lists['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $escrow_ins_lists['recordsFiltered'] );
        $json_data['data'] = $data;
	    echo json_encode($json_data);
    }

    public function import()
    {    
        $data = array();
        $data['title'] = 'PCT Order: Import Escrow Instruction';
        $data['escrow_instruction_list'] = $this->escrow_instruction_model->get_all();

    	if ($this->input->post()) {

            if (empty($_FILES['file']['name'])) {
				$this->form_validation->set_rules('file', 'CSV file', 'required');
			} else {
				$allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
				$mime = get_mime_by_extension($_FILES['file']['name']);
				$fileAr = explode('.', $_FILES['file']['name']);
				$ext = end($fileAr);
                //echo $ext;exit;
				if (($ext == 'csv') && in_array($mime, $allowed_mime_types)) {

				} else {
					$this->form_validation->set_rules('file', 'CSV file', 'required',
                        array('required' => 'Please select only CSV file to upload.')
                    );
				}
			
			}
            //echo $this->form_validation->run()."-----hhh";exit;
            //$this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');
        
            if ($this->form_validation->run() == true) {
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;
                
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $this->load->library('CSVReader');
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
                    
                    if (!empty($csvData)) {                        
                        foreach ($csvData as $row) {
                            $rowCount++;
                            //print_r($row);exit;
                            $check_condition = [
                                'escrow_instruction_id' => $this->input->post('escrow_instruction') ,
                                'custom_field_value_id' => $row['CustomFieldValueID'],
                            ];
                            $this->escrow_instruction_value_model->delete_by($check_condition);
                            
                            if (isset($row['ValueVarchar']) && !empty($row['ValueVarchar'])) {
                                $escrowInsData = array(
                                    'escrow_instruction_id' => $this->input->post('escrow_instruction') ,
                                    'custom_field_value_id' => $row['CustomFieldValueID'],
                                    'custom_field_id' => $row['CustomFieldID'],
                                    'name' => $row['Name'],
                                    'value' => $row['ValueVarchar'],
                                );

                                $insert = $this->escrow_instruction_value_model->insert($escrowInsData);
    
                                if ($insert){
                                    $insertCount++;
                                }
                            }   
                        }
                
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Customers imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
                        $data['success_msg'] = $successMsg;
                    }
                } else {
                    $data['error_msg'] = 'Error on file upload, please try again.';
                }
            } 
        }
		$this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "import", $data);
    }

    public function addEscrowInstruction()
    {
        $userdata = $this->session->userdata('hr_admin');
        $data['title'] = 'HR-Center Escrow Instruction';
        $data['page_title'] = 'Add Escrow Instruction';
        $data['escrow_instruction_list'] = $this->escrow_instruction_model->get_all();

        if ($this->input->post()) {
            $this->form_validation->set_rules('escrow_instruction', 'Escrow Instruction Type', 'required', array('required'=> 'Select Escrow Instruction Type'));
            $this->form_validation->set_rules('custom_field_value_id', 'Custom Field Value ID', 'required', array('required'=> 'Please enter Custom Field Value ID'));
            $this->form_validation->set_rules('custom_field_id', 'Custom Field ID', 'required', array('required'=> 'Please enter Custom Field ID'));
            $this->form_validation->set_rules('name', 'Name', 'required', array('required'=> 'Please ente Name'));
           
            if ($this->form_validation->run() == true) {
                $escrowInsData = array(
                    'escrow_instruction_id' =>  $this->input->post('escrow_instruction'),
                    'custom_field_value_id' => $this->input->post('custom_field_value_id'),
                    'custom_field_id' => $this->input->post('custom_field_id'),
                    'name' => $this->input->post('name'),
                    'value' => $this->input->post('escrow_instruction_value')
                );
                $escrowId = $this->hr->insert($escrowInsData, 'pct_order_escrow_instruction_columns_values');
                $successMsg = 'Escrow instruction added successfully.';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url().'hr/admin/escrow-instruction');
            } else {
                $data['escrow_instruction_error_msg'] = form_error('escrow_instruction');
                $data['custom_field_value_id_error_msg'] = form_error('custom_field_value_id');
                $data['custom_field_id_error_msg'] = form_error('custom_field_id');
                $data['escrow_instruction_value_error_msg'] = form_error('escrow_instruction_value');
            }                                       
        }
        $this->admintemplate->addJS( base_url('assets/plugins/ckeditor/ckeditor.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/escrow_instruction.js') );
        $this->admintemplate->show("hr", "add_escrow_instruction", $data);
    }

    public function editEscrowInstruction()
    {
        $userdata = $this->session->userdata('hr_admin');
        $id = $this->uri->segment(4);
        $data['title'] = 'HR-Center Escrow Instruction';
        $data['page_title'] = 'Add Escrow Instruction';
        $data['escrow_instruction_list'] = $this->escrow_instruction_model->get_all();
        $data['escrow_instruction_data'] = $this->escrow_instruction_value_model->get_by('id', $id);

        if(isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('escrow_instruction', 'Escrow Instruction Type', 'required', array('required'=> 'Select Escrow Instruction Type'));
                $this->form_validation->set_rules('custom_field_value_id', 'Custom Field Value ID', 'required', array('required'=> 'Please enter Custom Field Value ID'));
                $this->form_validation->set_rules('custom_field_id', 'Custom Field ID', 'required', array('required'=> 'Please enter Custom Field ID'));
                $this->form_validation->set_rules('name', 'Name', 'required', array('required'=> 'Please ente Name'));
            
                if ($this->form_validation->run() == true) {
                    $escrowInsData = array(
                        'escrow_instruction_id' =>  $this->input->post('escrow_instruction'),
                        'custom_field_value_id' => $this->input->post('custom_field_value_id'),
                        'custom_field_id' => $this->input->post('custom_field_id'),
                        'name' => $this->input->post('name'),
                        'value' => $this->input->post('escrow_instruction_value')
                    );
                    $condition = array('id' => $id);
                    $this->hr->update($escrowInsData, $condition, 'pct_order_escrow_instruction_columns_values');
                    $successMsg = 'Escrow instruction updated successfully.';
                    $this->session->set_userdata('success', $successMsg);
                    redirect(base_url().'hr/admin/escrow-instruction');
                } else {
                    $data['escrow_instruction_error_msg'] = form_error('escrow_instruction');
                    $data['custom_field_value_id_error_msg'] = form_error('custom_field_value_id');
                    $data['custom_field_id_error_msg'] = form_error('custom_field_id');
                    $data['escrow_instruction_value_error_msg'] = form_error('escrow_instruction_value');
                }                                       
            }
        }
        $this->admintemplate->addCSS( base_url('assets/css/bootstrap-select.css') );
        $this->admintemplate->addJS( base_url('assets/plugins/ckeditor/ckeditor.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/escrow_instruction.js') );
        $this->admintemplate->show("hr", "edit_escrow_instruction", $data);
    }

    public function deleteEscrowInstruction()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $this->escrow_instruction_value_model->delete($id);
            $successMsg = 'Escrow Instruction deleted successfully.';
            $response = array('status'=>'success', 'message' => $successMsg);
        } else {
            $msg = 'Escrow Instruction ID is required.';
            $response = array('status' => 'error','message'=>$msg);
        }
        echo json_encode($response);
    }

}