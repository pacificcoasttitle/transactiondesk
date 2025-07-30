<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends MX_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/agent_model');
        $this->load->library('order/common');
        $this->common->is_admin();
    }

	public function index()
	{
		$data = array();
        $data['title'] = 'PCT Order: Agents';
        $this->admintemplate->show("order/agent", "agents", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/agent/agents', $data);
        // $this->load->view('order/layout/footer', $data);
	}

    public function import_agents()
    {
        ini_set('max_execution_time', 0); 
        ini_set('memory_limit','2048M');
        $data = array();
        $data['title'] = 'PCT Order: Import Agents';
        if($this->input->post())
        {
            // Form field validation rules
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');
            
            // Validate submitted form data
            if($this->form_validation->run($this) == true)
            {
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;
                
                // If file uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name']))
                {
                    // Load CSV reader library
                    $this->load->library('CSVReader');
                    
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
                    $rowNumber = '';
                    // Insert/update CSV data into database
                    if(!empty($csvData))
                    {
                        foreach($csvData as $row)
                        {
                            $rowCount++;
                            if(isset($row['Email']) && !empty($row['Email']))
                            {
                                $name = $row['First Name']." ".$row['Last Name'];
                                $email_address = str_replace(' ','',$row['Email']);
                                $email_address = strtolower($email_address);

                                $agentData = array(
                                    'partner_id' => $row['Partner Company ID'],
                                    'partner_employee_id' => $row['Partner Employee ID'],
                                    'name' => $name,
                                    'email_address' => $email_address,
                                    'company' => ($row['Name']),
                                    'telephone_no' => $row['Cell Phone'],
                                    'address' => $row['Street1'],
                                    'city' => $row['City'],
                                    'zipcode' => $row['Zip'],
                                    'is_listing_agent' => 0,
                                    /*'list_unit' => $row['List Unit'],
                                    'list_volume' => $row['List Volume'],
                                    'selected_revenue' => $row['Selected Revenue'],*/
                                    'status'=> 1,
                                );

                                //$this->db->replace('agents', $agentData);
                                $con = array(
                                    'where' => array(
                                        'partner_id' => $row['Partner Company ID'],
                                        'partner_employee_id' => $row['Partner Employee ID']
                                    ),
                                    'returnType' => 'count'
                                );
                                $prevCount = $this->agent_model->get_rows($con);
                                
                                if($prevCount > 0){
                                                                  
                                    $condition = array('partner_id' => $row['Partner Company ID'], 'partner_employee_id' => $row['Partner Employee ID']);
                                    $update = $this->agent_model->update($agentData, $condition);
                                    
                                    if($update){
                                        $updateCount++;
                                    } else {
                                        $notAddCount++;
                                        $rowNumber .= $rowCount.",";
                                    }
                                }else{
                                    // Insert member data
                                    $insert = $this->agent_model->insert($agentData);
                                    
                                    if($insert){
                                        $insertCount++;
                                    } else {
                                        $notAddCount++; 
                                        $rowNumber .= $rowCount.",";
                                    }
                                }
                                $maxEmployeeid =  $this->agent_model->findMaxEmployeeId($email_address);
                                $statusCondition = array('email_address' => $email_address, 'partner_employee_id != ' => $maxEmployeeid);
                                $update = $this->agent_model->update( array('status' => 0), $statusCondition);
                            }
                            
                        }
                        
                        
                        // Status message with imported data count
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Agents imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
                        // $this->session->set_userdata('success_msg', $successMsg);
                        $data['success_msg'] = $successMsg;
                    }
                }
                else
                {
                   // $this->session->set_userdata('error_msg', 'Error on file upload, please try again.');
                    $data['error_msg'] = 'Error on file upload, please try again.';
                }
            }
            else
            {
                // $this->session->set_userdata('error_msg', 'Invalid file, please select only CSV file.');
                $data['error_msg'] = 'Invalid file, please select only CSV file.';
            }
        }
        $this->load->view('order/layout/header', $data);
        $this->load->view('order/agent/import', $data);
        $this->load->view('order/layout/footer', $data);
    }

    public function file_check($str)
    {
        $allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "")
        {
            $mime = get_mime_by_extension($_FILES['file']['name']);
            $fileAr = explode('.', $_FILES['file']['name']);
            $ext = end($fileAr);
            if(($ext == 'csv') && in_array($mime, $allowed_mime_types)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only CSV file to upload.');
                return false;
            }
        }
        else
        {
            $this->form_validation->set_message('file_check', 'Please select a CSV file to upload.');
            return false;
        }
    }

    public function get_agent_list()
    {
        $params = array();  $data = array();
        if(isset($_POST['draw']) && !empty($_POST['draw']))
        {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';

            $pageno = ($params['start'] / $params['length'])+1;

            $agent_lists = $this->agent_model->get_agents($params);
           
           // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;
            $json_data['draw'] = intval( $params['draw'] );
        }
        else
        {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $agent_lists = $this->agent_model->get_agents($params);
        }
        

        if(isset($agent_lists['data']) && !empty($agent_lists['data']))
        {
            foreach ($agent_lists['data'] as $key => $value) 
            {
                $nestedData=array();
                $partner_id = isset($value['partner_id']) && !empty($value['partner_id']) ? $value['partner_id'] : '-';
                $nestedData[] = $partner_id;
                $nestedData[] = $value['name'];
                /*$nestedData[] = $value['last_name'];*/
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['telephone_no'];
                $nestedData[] = $value['company'];
                $nestedData[] = $value['address']." ".$value['city']." ".$value['zipcode'];
                
                if(isset($_POST['draw']) && !empty($_POST['draw']))
                {
                    $editOrderUrl = base_url().'order/admin/edit-agent/'.$value['id'];
                    $action = "<div style='display: flex;justify-content: space-evenly;'><a href='".$editOrderUrl."' class='edit-agent' title ='Edit Agent Detail'><i class='fas fa-edit' aria-hidden='true'></i></a>";

                    $action .= "<a href='javascript:void(0);' onclick='deleteAgent(".$value['id'].")' title='Delete Customer'><i class='fas fa-trash' aria-hidden='true'></i></a> </div>";

                    $nestedData[] = $action;
                }
                
                $data[] = $nestedData;
            }
        }

        $json_data['recordsTotal'] = intval( $agent_lists['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $agent_lists['recordsFiltered'] );
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function delete_agent()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if($id)
        {
            $agentData = array('status' => 0);
            $condition = array('id' => $id);
            $agent = $this->agent_model->get_rows($condition);
            $update = $this->agent_model->update($agentData, $condition);
            if($update)
            {
                $successMsg = 'Agent deleted successfully.';
                $response = array('status'=>'success', 'message'=>$successMsg);
                /** Save user Activity */
                $activity = 'Deleted agent : '. $agent['email_address'];
                $this->common->logAdminActivity($activity);
                /** End Save user activity */
            }
        }
        else
        {
            $msg = 'Agent ID is required.';
            $response = array('status' => 'error','message'=>$msg);
        }

        echo json_encode($response);
    }

    public function edit()
    {
        $id = $this->uri->segment(4);        
        $data = array();
        $data['title'] = 'PCT Order: Edit Agent';
        if(isset($id) && !empty($id))
        {

            if(isset($_POST) && !empty($_POST))
            {
                // Validations
                $this->form_validation->set_rules('name', 'Name', 'required',array('required'=> 'Enter your name'));
                $this->form_validation->set_rules('email_address', 'Email Address', 'required',array('required'=> 'Enter your email address'));
                $this->form_validation->set_rules('telephone_no', 'Telephone', 'required',array('required'=> 'Enter your telephone no'));
                $this->form_validation->set_rules('company', 'Company', 'required',array('required'=> 'Enter your company name'));
                $this->form_validation->set_rules('address', 'Address', 'required',array('required'=> 'Enter your address'));
                $this->form_validation->set_rules('city', 'City', 'required',array('required'=> 'Enter your city'));
                $this->form_validation->set_rules('zipcode', 'Zipcode', 'required',array('required'=> 'Enter your zipcode'));
                $this->form_validation->set_rules('list_unit', 'List Unit', 'required',array('required'=> 'Enter your list unit'));
                $this->form_validation->set_rules('list_volume', 'List Volume', 'required',array('required'=> 'Enter your list volume'));
                $this->form_validation->set_rules('selected_revenue', 'Selected Revenue', 'required',array('required'=> 'Enter your list unit'));

                if($this->form_validation->run($this) == true)
                {
                    $agentData = array(
                        'name' => isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : NULL,
                        'email_address' => isset($_POST['email_address']) && !empty($_POST['email_address']) ? $_POST['email_address'] : NULL ,
                        'telephone_no' => isset($_POST['telephone_no']) && !empty($_POST['telephone_no']) ? $_POST['telephone_no'] : NULL ,
                        'company' => isset($_POST['company']) && !empty($_POST['company']) ? $_POST['company'] : NULL,
                        'address' => $_POST['address'],
                        'city' => $_POST['city'],
                        'zipcode' => $_POST['zipcode'],
                        'list_unit' => $_POST['list_unit'],
                        'list_volume' => $_POST['list_volume'],
                        'selected_revenue' => $_POST['selected_revenue'],
                        'status' => 1
                    );

                    $condition = array('id' => $id);

                    $update = $this->agent_model->update($agentData,$condition,'pctc_title_rates');
                        
                    if ($update) {
                        /** Save user Activity */
                        $activity = 'Agent details updated : '. $_POST['email_address'];
                        $this->common->logAdminActivity($activity);
                        /** End Save user activity */
                        $data['success_msg'] = 'Agent details updated successfully.';
                    } else {
                        $data['error_msg'] = 'Error occurred while updating agent details.';
                    }
                }
                else
                {
                    $data['name_error_msg'] = form_error('first_name');
                    $data['email_address_error_msg'] = form_error('email_address');
                    $data['telephone_no_error_msg'] = form_error('telephone_no');
                    $data['company_error_msg'] = form_error('company');
                    $data['address_error_msg'] = form_error('address');
                    $data['city_error_msg'] = form_error('city');
                    $data['zipcode_error_msg'] = form_error('zipcode');
                    $data['list_unit_error_msg'] = form_error('list_unit');
                    $data['list_volume_error_msg'] = form_error('list_volume');
                    $data['selected_revenue_error_msg'] = form_error('selected_revenue');
                    
                }
            }
            $con = array('id' => $id);
            $data['agent_info'] = $this->agent_model->get_rows($con);
            
        }
        else
        {
            redirect(base_url().'agents');
        }

        $this->admintemplate->show("order/agent", "edit-agent", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/agent/edit-agent', $data);
        // $this->load->view('order/layout/footer', $data);
    }
}
