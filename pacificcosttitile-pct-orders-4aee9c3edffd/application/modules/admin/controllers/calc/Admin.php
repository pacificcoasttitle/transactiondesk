<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function __construct() 
	{ 
        parent::__construct();
        
        // Load form validation library
        $this->load->library('form_validation','session');
        
        // Load file helper
        $this->load->helper(
            array('file', 'url')
        );

        // Load model
        $this->load->model('calc/admin_model'); 
        $this->load->model('frontend/calc/welcome_model'); 
    }

    public function title_rates()
    {
        $this->is_admin();
        
        $data = array();
        
        $data['title'] = 'Rate Calculator: Title Rates';
        
        $table= 'pctc_title_rates';
        $con = array(
            'where' => array(
                'status' => 1
            )
        );
        $data['rates'] = $this->admin_model->getTitleRatesRows($con,$table);

        $this->load->view('calc/header', $data);
        $this->load->view('calc/title_rates', $data);
        $this->load->view('calc/footer', $data);
    }

	public function escrow_refinance()
	{
		$data = array();
        
        // Get messages from the session
        if($this->session->userdata('success_msg')){
            $data['success_msg'] = $this->session->userdata('success_msg');
            $this->session->unset_userdata('success_msg');
        }

        if($this->session->userdata('error_msg')){
            $data['error_msg'] = $this->session->userdata('error_msg');
            $this->session->unset_userdata('error_msg');
        }
        $data['rates'] = $this->admin_model->getRows();

		$this->load->view('front/header', $data);
		$this->load->view('admin/upload_form', $data);
		$this->load->view('front/footer', $data);
	}

	public function import()
	{
        $data = array();
        $ratesData = array();
        
        // If import request is submitted
        if($this->input->post('importSubmit'))
        {
            // Form field validation rules
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');
            
            // Validate submitted form data
            if($this->form_validation->run() == true){
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;
                
                // If file uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    // Load CSV reader library
                    $this->load->library('CSVReader');
                    
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);

                    // Insert/update CSV data into database
                    if(!empty($csvData)){
                        foreach($csvData as $row){ 

                        	$rowCount++;

                            // Prepare data for DB insertion
                            $ratesData = array(
                                'min_range' => str_replace('$','', $row['Min Range']),
                                'max_range' => str_replace('$','', $row['Max Range']),
                                'escrow_rate' => str_replace('$','', $row['Rates']),
                            );

                            $con = array(
                                'where' => array(
                                    'min_range' => $row['Min Range'],
                                    'max_range' => $row['Max Range']
                                ),
                                'returnType' => 'count'
                            );
                            $prevCount = $this->admin_model->getRows($con);
                          
                            if($prevCount > 0){
                                // Update member data
                                $condition = array('min_range' => $row['Min Range'], 'max_range' => $row['Max Range']);

                                $update = $this->admin_model->update($ratesData, $condition);
                                
                                if($update){
                                    $updateCount++;
                                }
                            }else{
                                // Insert member data
                                $insert = $this->admin_model->insert($ratesData);
                                
                                if($insert){
                                    $insertCount++;
                                }
                            }
                        }
                        
                        // Status message with imported data count
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Rates imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
                        $this->session->set_userdata('success_msg', $successMsg);
                    }
                }else{
                    $this->session->set_userdata('error_msg', 'Error on file upload, please try again.');
                }
            }else{
                $this->session->set_userdata('error_msg', 'Invalid file, please select only CSV file.');
            }
        }
        
        redirect('admin/escrow_refinance');
    }

    public function file_check($str)
    {
        $allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ""){
            $mime = get_mime_by_extension($_FILES['file']['name']);
            $fileAr = explode('.', $_FILES['file']['name']);
            $ext = end($fileAr);
            if(($ext == 'csv') && in_array($mime, $allowed_mime_types)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only CSV file to upload.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please select a CSV file to upload.');
            return false;
        }
    }

    public function import_title_rates()
    {
        $this->is_admin();
    	$data = array();
        $data['title'] = 'Rate Calculator: Import Title Rates';

        $ratesData = array();

        if($this->input->post('importSubmit'))
        {
            // Form field validation rules
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');
            
            // Validate submitted form data
            if($this->form_validation->run() == true){
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;
                
                // If file uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    // Load CSV reader library
                    $this->load->library('CSVReader');
                    
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
                  
                    // Insert/update CSV data into database
                    if(!empty($csvData)){
                        foreach($csvData as $row){ 

                        	$rowCount++;

                            // Prepare data for DB insertion
                            
                            $ratesData = array(
                                'min_range' => str_replace('$','', $row['Min Range']),
                                'max_range' => str_replace('$','', $row['Max Range']),
                                'owner_rate' => str_replace('$','', $row['Owner Rate']),
                                'home_owner_rate' => str_replace('$','', $row['Home Owner Rate']),
                                'con_loan_rate' =>  str_replace('$','', $row['Con Loan Rate']),
                                'resi_loan_rate' =>  str_replace('$','', $row['Resi Loan Rate']),
                                'con_full_loan_rate' =>  str_replace('$','', $row['Con Full Loan Rate']) ,
                                'region'=>'California',
                                'status' => 1
                            );
                            // Prepare data for DB insertion

                            // Check whether record already exists in the database
                            $con = array(
                                'where' => array(
                                    'min_range' => $row['Min Range'],
                                    'max_range' => $row['Max Range']
                                ),
                                'returnType' => 'count'
                            );
                            $table= 'pctc_title_rates';
                            $prevCount = $this->admin_model->getTitleRatesRows($con,$table);

                            if($prevCount > 0){
                                // Update member data
                                $condition = array('min_range' => $row['Min Range'], 'max_range' => $row['Max Range']);
                                $update = $this->admin_model->update($ratesData, $condition,$table);
                                
                                if($update){
                                    $updateCount++;
                                }
                            }else{
                                // Insert member data
                                $insert = $this->admin_model->insert($ratesData,$table);
                                
                                if($insert){
                                    $insertCount++;
                                }
                            }
                        }
                        
                        // Status message with imported data count
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Rates imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
                        $data['success_msg']= $successMsg;
                    }
                }else{
                    $data['error_msg'] = 'Error on file upload, please try again.';
                }
            }else{
                $data['file_error_msg'] = form_error('file');
            }
        }
        $this->load->view('calc/header', $data);
        $this->load->view('calc/import_title_rates', $data);
        $this->load->view('calc/footer', $data);
    }

    public function refinance_rates()
    {
        $this->is_admin();
        $data = array();

        $data['title'] = 'Rate Calculator: Refinance Rates';
        $con = array(
            'where' => array(
                'status' => 1
            )
        );
        $data['rates'] = $this->admin_model->getRows($con,'pctc_escrow_refinance');

        $this->load->view('calc/header', $data);
        $this->load->view('calc/refinance_rates', $data);
        $this->load->view('calc/footer', $data);
    }

    public function add_refinance_rates()
    {
        $this->is_admin();

        $data = array();
        
        $data['title'] = 'Rate Calculator: Add Refinance Rates';
        if(isset($_POST) && !empty($_POST))
        {
            // Validations
            $this->form_validation->set_rules('county', 'County', 'required',array('required'=> 'Please select county'));
            $this->form_validation->set_rules('min_price', 'Min Price', 'required',array('required'=> 'Please enter min price'));
            // $this->form_validation->set_rules('max_price', 'Max Price', 'required',array('required'=> 'Please enter max price'));
            $this->form_validation->set_rules('rate', 'Rate', 'required',array('required'=> 'Please enter rate'));

            if($this->form_validation->run() == true)
            {
                $rateData = array(
                    'county' => $_POST['county'],
                    'min_range' => $_POST['min_price'],
                    'max_range' =>  isset($_POST['max_price']) && !empty($_POST['max_price']) ? $_POST['max_price'] : NULL,
                    'escrow_rate' => $_POST['rate'],
                    'status' => 1,
                );

                $insert = $this->admin_model->insert($rateData,'pctc_escrow_refinance');
                    
                if($insert){
                    $data['success_msg'] = 'Rates added successfully.';
                }
                else
                {
                    $data['error_msg'] = 'Error occurred while adding rates.';
                }
            }
            else
            {
                $data['county_error_msg'] = form_error('county');
                $data['min_price_error_msg'] = form_error('min_price');
                // $data['max_price_error_msg'] = form_error('max_price');
                $data['rate_error_msg'] = form_error('rate');
            }
        }
        $data['county_list'] = $this->welcome_model->get_county_list();
        
        $this->load->view('calc/header', $data);
        $this->load->view('calc/add_refinance_rates', $data);
        $this->load->view('calc/footer', $data);
    }

    public function dashboard()
    {
        $data['title'] = "Dashboard";

        /* $adminId = $data['admin_id'] = $this->session->userdata('adminid');
        $adminId = $this->session->userdata('adminid');
        if($adminId){*/
            $data['user_name'] = "test";
            $this->load->view('calc/header',$data);
            $this->load->view('calc/dashboard');
            $this->load->view('calc/footer');
        /*}else{
            redirect('admin/index');
        }*/
    }

    public function resale_rates()
    {
        $this->is_admin();
        $data = array();

        $data['title'] = 'Rate Calculator: Resale Rates';
        $con = array(
            'where' => array(
                'status' => 1
            )
        );
        $data['rates'] = $this->admin_model->getResaleRows($con,'pctc_escrow_resale');

        $this->load->view('calc/header', $data);
        $this->load->view('calc/resale_rates', $data);
        $this->load->view('calc/footer', $data);
    }

    public function add_resale_rates()
    {
        $this->is_admin();
        $data = array();
        
        $data['title'] = 'Rate Calculator: Add Resale Rates';
        if(isset($_POST) && !empty($_POST))
        {
            // Validations
            $this->form_validation->set_rules('county', 'County', 'required',array('required'=> 'Please select county'));
            $this->form_validation->set_rules('min_price', 'Min Price', 'required',array('required'=> 'Please enter min price'));
            /*$this->form_validation->set_rules('max_price', 'Max Price', 'required',array('required'=> 'Please enter max price'));
            $this->form_validation->set_rules('base_amount', 'Base Amount', 'required',array('required'=> 'Please enter base amount'));
            $this->form_validation->set_rules('per_thousand_price', 'Per Thousand Price', 'required',array('required'=> 'Please enter per thousand price'));
            $this->form_validation->set_rules('base_rate', 'Base Rate', 'required',array('required'=> 'Please enter base rate'));
            $this->form_validation->set_rules('minimum_rate', 'Minimum Rate', 'required',array('required'=> 'Please enter minimum rate'));*/

            if($this->form_validation->run() == true)
            {
                $rateData = array(
                    'county' => $_POST['county'],
                    'min_range' => $_POST['min_price'],
                    'max_range' =>  isset($_POST['max_price']) && !empty($_POST['max_price']) ? $_POST['max_price'] : NULL,
                    'base_amount' => isset($_POST['base_amount']) && !empty($_POST['base_amount']) ? $_POST['base_amount'] : NULL,
                    'per_thousand_price' => isset($_POST['per_thousand_price']) && !empty($_POST['per_thousand_price']) ? $_POST['per_thousand_price'] : NULL ,
                    'base_rate' => isset($_POST['base_rate']) && !empty($_POST['base_rate']) ? $_POST['base_rate'] : NULL ,
                    'minimum_rate' => isset($_POST['minimum_rate']) && !empty($_POST['minimum_rate']) ? $_POST['minimum_rate'] : NULL,
                    'status' => 1,
                );

                $insert = $this->admin_model->insert($rateData,'pctc_escrow_resale');
                    
                if($insert){
                    $data['success_msg'] = 'Rates added successfully.';
                }
                else
                {
                    $data['error_msg'] = 'Error occurred while adding rates.';
                }
            }
            else
            {
                $data['county_error_msg'] = form_error('county');
                $data['min_price_error_msg'] = form_error('min_price');
                //$data['max_price_error_msg'] = form_error('max_price');
                // $data['base_amount_error_msg'] = form_error('base_amount');
                // $data['per_thousand_price_error_msg'] = form_error('per_thousand_price');
                /*$data['base_rate_error_msg'] = form_error('base_rate');
                $data['minimum_rate_error_msg'] = form_error('minimum_rate');*/
            }
        }
        $data['county_list'] = $this->welcome_model->get_county_list();
        
        $this->load->view('calc/header', $data);
        $this->load->view('calc/add_resale_rates', $data);
        $this->load->view('calc/footer', $data);
    }

    public function fees()
    {
        $this->is_admin();
        $data = array();

        $data['title'] = 'Rate Calculator: Fees';
        $con = array(
            'where' => array(
                'status' => 1
            )
        );

        $data['fees'] = $this->admin_model->getFees($con);

        $this->load->view('calc/header', $data);
        $this->load->view('calc/fees', $data);
        $this->load->view('calc/footer', $data);   
    }

    public function add_fees()
    {
        $this->is_admin();
        $data = array();

        $data['title'] = 'Rate Calculator: Add Fees';

        $feesData = array();

        // If import request is submitted
        if($this->input->post())
        {
            // Validations
            $this->form_validation->set_rules('section', 'Section', 'required',array('required'=> 'Select section name'));
            $this->form_validation->set_rules('fee_name', 'Fee name', 'required',array('required'=> 'Enter fee name'));
            $this->form_validation->set_rules('fee_value', 'Fee value', 'required',array('required'=> 'Enter Fee value'));
            $this->form_validation->set_rules('txn_type', 'Transaction Type', 'required',array('required'=> 'Select transaction type'));

            if($this->form_validation->run() == true)
            {
                $id = isset($_POST['fee_id']) && !empty($_POST['fee_id']) ? $_POST['fee_id'] : '';


                // Prepare data for DB insertion
                $feesData = array(
                    'parent_name' => $_POST['section'],
                    'transaction_type' => $_POST['txn_type'],
                    'name' =>  $_POST['fee_name'],
                    'value' => $_POST['fee_value'],
                    'status' => 1,
                );

                if($id)
                {
                    $condition = array('id' => $id);

                    $update = $this->admin_model->update($feesData, $condition,'pctc_fees');

                    if($update)
                    {
                        $successMsg = 'Fees updated successfully.';
                        $this->session->set_userdata('success_msg', $successMsg);
                    }
                }
                else
                {                  
                    // Insert member data
                    $insert = $this->admin_model->insert($feesData,'pctc_fees');
                    
                    if($insert){
                        $data['success_msg'] = 'Fees added successfully.';
                    }
                    else
                    {
                        $data['error_msg'] = 'Fees not added.';
                    } 
                }
                
            }
            else
            {

                $data['section_error_msg'] = form_error('section');
                $data['txn_type_error_msg'] = form_error('txn_type');
                $data['name_error_msg'] = form_error('fee_name');
                $data['value_error_msg'] = form_error('fee_value');
            }

                                       
        }
        $this->load->view('calc/header', $data);
        $this->load->view('calc/add_fees', $data);
        $this->load->view('calc/footer', $data);
        
        // redirect('index.php?admin/fees');
    }


    public function delete_fees()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        $data = array();
        if($id)
        {
            $feesData = array('status' => 0);

            $condition = array('id' => $id);

            $update = $this->admin_model->update($feesData, $condition,'pctc_fees');

            if($update)
            {
                $successMsg = 'Fees deleted successfully.';
                $data = array('status'=>'success', 'message'=>$successMsg);
            }
        }

        echo json_encode($data); exit;
    }

    public function edit_resale_rates()
    {
        $this->is_admin();

        $data = array();
        
        $data['title'] = 'Rate Calculator: Edit Resale Rates';

        $id = $this->uri->segment('4');
        
        if(isset($id) && !empty($id))
        {
            if(isset($_POST) && !empty($_POST))
            {
                // Validations
                $this->form_validation->set_rules('county', 'County', 'required',array('required'=> 'Please select county'));
                $this->form_validation->set_rules('min_price', 'Min Price', 'required',array('required'=> 'Please enter min price'));
                

                if($this->form_validation->run() == true)
                {
                    $rateData = array(
                        'county' => $_POST['county'],
                        'min_range' => $_POST['min_price'],
                        'max_range' =>  isset($_POST['max_price']) && !empty($_POST['max_price']) ? $_POST['max_price'] : NULL,
                        'base_amount' => isset($_POST['base_amount']) && !empty($_POST['base_amount']) ? $_POST['base_amount'] : NULL,
                        'per_thousand_price' => isset($_POST['per_thousand_price']) && !empty($_POST['per_thousand_price']) ? $_POST['per_thousand_price'] : NULL ,
                        'base_rate' => isset($_POST['base_rate']) && !empty($_POST['base_rate']) ? $_POST['base_rate'] : NULL ,
                        'minimum_rate' => isset($_POST['minimum_rate']) && !empty($_POST['minimum_rate']) ? $_POST['minimum_rate'] : NULL,
                        'status' => 1,
                    );

                    $condition = array('escrow_resale_id_pk' => $id);

                    $update = $this->admin_model->update($rateData,$condition,'pctc_escrow_resale');
                        
                    if($update){
                        $data['success_msg'] = 'Rates updated successfully.';
                    }
                    else
                    {
                        $data['error_msg'] = 'Error occurred while updating rates.';
                    }
                }
                else
                {
                    $data['county_error_msg'] = form_error('county');
                    $data['min_price_error_msg'] = form_error('min_price');
                }
            }
            $con = array('id' => $id);
            $rate_info = $this->admin_model->getResaleRows($con,'pctc_escrow_resale');
        }
        else
        {
	    redirect('admin/resale_rates');
        }
        $data['rate_info'] = $rate_info;
        $data['county_list'] = $this->welcome_model->get_county_list();

        $this->load->view('calc/header', $data);
        $this->load->view('calc/edit_resale_rates', $data);
        $this->load->view('calc/footer', $data);
    }    

    public function delete_resale_rates()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        $data = array();
        if($id)
        {
            $ratesData = array('status' => 0);

            $condition = array('escrow_resale_id_pk' => $id);

            $update = $this->admin_model->update($ratesData, $condition,'pctc_escrow_resale');

            if($update)
            {
                $successMsg = 'Rates deleted successfully.';
                $data = array('status'=>'success', 'message'=>$successMsg);
            }
        }

        echo json_encode($data); exit;
    }
    
    public function edit_refinance_rates()
    {
        $this->is_admin();
        
        $data = array();
        
        $data['title'] = 'Rate Calculator: Edit Refinance Rates';

        $id = $this->uri->segment('4');
        
        if(isset($id) && !empty($id))
        {
            if(isset($_POST) && !empty($_POST))
            {
                // Validations
                $this->form_validation->set_rules('county', 'County', 'required',array('required'=> 'Please select county'));
                $this->form_validation->set_rules('min_price', 'Min Price', 'required',array('required'=> 'Please enter min price'));
                // $this->form_validation->set_rules('max_price', 'Max Price', 'required',array('required'=> 'Please enter max price'));
                $this->form_validation->set_rules('rate', 'Rate', 'required',array('required'=> 'Please enter rate'));

                if($this->form_validation->run() == true)
                {
                    $rateData = array(
                        'county' => $_POST['county'],
                        'min_range' => $_POST['min_price'],
                        'max_range' =>  isset($_POST['max_price']) && !empty($_POST['max_price']) ? $_POST['max_price'] : NULL,
                        'escrow_rate' => $_POST['rate'],
                        'status' => 1,
                    );

                    $condition = array('escrow_ref_id_pk' => $id);

                    $update = $this->admin_model->update($rateData,$condition,'pctc_escrow_refinance');
                        
                    if($update){
                        $data['success_msg'] = 'Rates updated successfully.';
                    }
                    else
                    {
                        $data['error_msg'] = 'Error occurred while updating rates.';
                    }
                }
                else
                {
                    $data['county_error_msg'] = form_error('county');
                    $data['min_price_error_msg'] = form_error('min_price');
                    // $data['max_price_error_msg'] = form_error('max_price');
                    $data['rate_error_msg'] = form_error('rate');
                }
            }
            $con = array('id' => $id);
            $rate_info = $this->admin_model->getRows($con,'pctc_escrow_refinance');
        }
        else
        {
	    redirect('admin/refinance_rates');
        }
        $data['rate_info'] = $rate_info;
        $data['county_list'] = $this->welcome_model->get_county_list();

        $this->load->view('calc/header', $data);
        $this->load->view('calc/edit_refinance_rates', $data);
        $this->load->view('calc/footer', $data);
    }

    public function delete_refinance_rates()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        $data = array();
        if($id)
        {
            $ratesData = array('status' => 0);

            $condition = array('escrow_ref_id_pk' => $id);

            $update = $this->admin_model->update($ratesData, $condition,'pctc_escrow_refinance');

            if($update)
            {
                $successMsg = 'Rates deleted successfully.';
                $data = array('status'=>'success', 'message'=>$successMsg);
            }
        }

        echo json_encode($data); exit;
    }

    public function edit_fees()
    {
        $this->is_admin();

        $data = array();
        
        $data['title'] = 'Rate Calculator: Edit Fees';

        $id = $this->uri->segment('4');
        
        if(isset($id) && !empty($id))
        {
            if(isset($_POST) && !empty($_POST))
            {
                // Validations
                $this->form_validation->set_rules('section', 'Section', 'required',array('required'=> 'Select section name'));
                $this->form_validation->set_rules('fee_name', 'Fee name', 'required',array('required'=> 'Enter fee name'));
                $this->form_validation->set_rules('fee_value', 'Fee value', 'required',array('required'=> 'Enter Fee value'));
                $this->form_validation->set_rules('txn_type', 'Transaction Type', 'required',array('required'=> 'Select transaction type'));

                if($this->form_validation->run() == true)
                {
                    $feesData = array(
                        'parent_name' => $_POST['section'],
                        'transaction_type' => $_POST['txn_type'],
                        'name' =>  $_POST['fee_name'],
                        'value' => $_POST['fee_value'],
                        'status' => 1,
                    );

                    $condition = array('id' => $id);

                    $update = $this->admin_model->update($feesData,$condition,'pctc_fees');
                        
                    if($update){
                        $data['success_msg'] = 'Fees updated successfully.';
                    }
                    else
                    {
                        $data['error_msg'] = 'Error occurred while updating fees.';
                    }
                }
                else
                {
                    $data['section_error_msg'] = form_error('section');
                    $data['txn_type_error_msg'] = form_error('txn_type');
                    $data['name_error_msg'] = form_error('fee_name');
                    $data['value_error_msg'] = form_error('fee_value');
                }
            }
            $con = array('id' => $id);
            $fees_info = $this->admin_model->getFees($con);
        }
        else
        {
            redirect('admin/fees');
        }
        $data['fees_info'] = $fees_info;

        $this->load->view('calc/header', $data);
        $this->load->view('calc/edit_fees', $data);
        $this->load->view('calc/footer', $data);
    }

    public function edit_title_rates()
    {
        $this->is_admin();

        $data = array();
        
        $data['title'] = 'Rate Calculator: Edit Title Rate';

        $id = $this->uri->segment('4');
        
        if(isset($id) && !empty($id))
        {

            if(isset($_POST) && !empty($_POST))
            {
                // Validations
                $this->form_validation->set_rules('min_price', 'Min Price', 'required',array('required'=> 'Please enter min price'));
                $this->form_validation->set_rules('max_price', 'Max Price', 'required',array('required'=> 'Please enter max price'));
                $this->form_validation->set_rules('owner_rate', 'Owner Rate', 'required',array('required'=> 'Please enter owner rate'));
                $this->form_validation->set_rules('home_owner_rate', 'Home Owner Rate', 'required',array('required'=> 'Please enter home owner rate'));
                $this->form_validation->set_rules('con_loan_rate', 'Con Loan Rate', 'required',array('required'=> 'Please enter con loan rate'));
                $this->form_validation->set_rules('resi_loan_rate', 'Resi Loan Rate', 'required',array('required'=> 'Please enter resi loan rate'));
                $this->form_validation->set_rules('con_full_loan_rate', 'Con Full Loan Rate', 'required',array('required'=> 'Please enter con full loan rate'));

                if($this->form_validation->run() == true)
                {
                    $ratesData = array(
                        'min_range' => $_POST['min_price'],
                        'max_range' =>  isset($_POST['max_price']) && !empty($_POST['max_price']) ? $_POST['max_price'] : NULL,
                        'owner_rate' => isset($_POST['owner_rate']) && !empty($_POST['owner_rate']) ? $_POST['owner_rate'] : NULL,
                        'home_owner_rate' => isset($_POST['home_owner_rate']) && !empty($_POST['home_owner_rate']) ? $_POST['home_owner_rate'] : NULL ,
                        'con_loan_rate' => isset($_POST['con_loan_rate']) && !empty($_POST['con_loan_rate']) ? $_POST['con_loan_rate'] : NULL ,
                        'resi_loan_rate' => isset($_POST['resi_loan_rate']) && !empty($_POST['resi_loan_rate']) ? $_POST['resi_loan_rate'] : NULL,
                        'con_full_loan_rate' => isset($_POST['con_full_loan_rate']) && !empty($_POST['con_full_loan_rate']) ? $_POST['con_full_loan_rate'] : NULL,
                        'region'=>'California',
                        'status' => 1
                    );

                    $condition = array('title_rate_id_pk' => $id);

                    $update = $this->admin_model->update($ratesData,$condition,'pctc_title_rates');
                        
                    if($update){
                        $data['success_msg'] = 'Rates updated successfully.';
                    }
                    else
                    {
                        $data['error_msg'] = 'Error occurred while updating rates.';
                    }
                }
                else
                {
                    $data['min_price_error_msg'] = form_error('min_price');
                    $data['max_price_error_msg'] = form_error('max_price');
                    $data['owner_rate_error_msg'] = form_error('owner_rate');
                    $data['home_owner_rate_error_msg'] = form_error('home_owner_rate');
                    $data['con_loan_rate_error_msg'] = form_error('con_loan_rate');
                    $data['resi_loan_rate_error_msg'] = form_error('resi_loan_rate');
                    $data['con_full_loan_rate_error_msg'] = form_error('con_full_loan_rate');
                }
            }
            $con = array('id' => $id);
            $rate_info = $this->admin_model->getTitleRatesRows($con,'pctc_title_rates');
        }
        else
        {
            redirect('calculator/admin/title_rates');
        }
        $data['rate_info'] = $rate_info;

        $this->load->view('calc/header', $data);
        $this->load->view('calc/edit_title_rates', $data);
        $this->load->view('calc/footer', $data);
    }

    public function delete_title_rates()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        $data = array();
        if($id)
        {
            $ratesData = array('status' => 0);

            $condition = array('title_rate_id_pk' => $id);

            $update = $this->admin_model->update($ratesData, $condition,'pctc_title_rates');

            if($update)
            {
                $successMsg = 'Rates deleted successfully.';
                $data = array('status'=>'success', 'message'=>$successMsg);
            }
        }

        echo json_encode($data); exit;
    }

    public function is_admin()
    {
        if ($this->session->userdata('adminid')) {
            
        } else {
            redirect("calculator/admin_login");
        }
    }

    public function admin_dashboard()
    {
        $this->is_admin();
        $data['title'] = 'Rate Calculator: Dashboard';
        $data['users'] = $this->welcome_model->get_all_users();
        $data['roles'] = $this->welcome_model->get_all_roles();
        $this->load->view('calc/header', $data);
        $this->load->view('calc/admin_dashboard', $data);
        $this->load->view('calc/footer', $data);
    }
    
    public function reset_user_pass($user_id)
    {
        $this->is_admin();
        $this->welcome_model->reset_user_pass($user_id, $_POST['pass']);
        $user_detail = $this->welcome_model->get_user_detail($user_id);
        $this->email->from('info@PCT247.com', 'PCT Password reset');
        $this->email->to($user_detail->email);
        $this->email->subject('Welcome to PCT');
        $this->email->message('Hi ' . "\n" . "\n" . 'You have requested for new password on PCT.' . "\n" . "\n" . 'Here is your New login details' . "\n" . 'your email id : ' . $user_detail->email . "\n" . 'Password : ' . $_POST['pass'] . "\n" . "\n" . 'PCT Team' . "\n" . 'http://www.pacificcoasttitle.com' . "\n" . "\n" . "\n" . 'This is an automatically generated email. Please do not reply');
        $this->email->send();
        $this->session->set_flashdata('msg', "user account password been reset.");
        redirect("calculator/admin_dashboard");
    }
    
    public function admin_dashboard_submit()
    {
        if ($this->session->userdata('adminid')) {
            if ($this->input->is_ajax_request()) {
                $this->welcome_model->update_user_role();
                // $this->session->set_flashdata('msg', 'User Membership updated.');
                // redirect('?welcome/admin_dashboard','refresh');
                print_r("1");
            } else {
                redirect("calculator/admin_dashboard");
            }
        } else {
            print_r("login_fail");
        }
        
    }
    
    public function admin_login_submit()
    {
        if (isset($_POST['email'])) {
            $email    = $this->input->post('email');
            $password = $this->input->post('password');
            $admin    = $this->welcome_model->get_admin_user($email, $password);
            //print_r($user);
            if ($admin) {
                $adminSessionStore = array(
                    "adminid" => $admin->id,
                    "adminname" => $admin->user_name,
                    "adminemail" => $admin->email_id
                );
                
                $this->session->set_userdata($adminSessionStore);
                // $this->session->set_userdata('adminid', $admin->id);
                // $this->session->set_userdata('adminname', $admin->user_name);
                // $this->session->set_userdata('adminemail', $admin->email_id);
                if ($this->input->is_ajax_request()) {
                    print_r("1,admin");
                    die;
                } else {
                    redirect('calculator/admin_dashboard');
                }
                
            } else {
                if ($this->input->is_ajax_request()) {
                    print_r("0");
                    die;
                } else {
                    $data['msg'] = "Incorrect email or password";
                }
            }
        }
    }

    public function admin_logout()
    {
        $this->session->unset_userdata('adminid');
        $this->session->unset_userdata('adminname');
        $this->session->unset_userdata('adminemail');
        session_destroy();
        redirect("calculator/admin_login");
    }
}