<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Sales extends MX_Controller {
	
	private $commission_types = ['global','override','fix'];

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->library('order/order');
        $this->load->model('order/sales_model');
        $this->load->library('order/common');
        $this->common->is_admin();
		// $this->common->is_super_admin();
    }

    public function index()
    {
        $data = array();
        
        $data['title'] = 'PCT Order: Sales Rep.';
        
        $salesStatusData = $this->order->getSalesConfigData();
        $data['sales_rep_status_flag'] = $salesStatusData['value'];
        $this->admintemplate->show("order/sales", "sales", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/sales', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_sales_rep_list()
    {
        $params = array();  $data = array();
        $params['sales_rep_enable'] = $this->input->post('sales_rep_enable');
        if ($this->input->post('sales_rep_enable') == '1' || $this->input->post('sales_rep_enable') == '0') {
            $salesData = array(
                'value' => $this->input->post('sales_rep_enable'),
            );
            $this->db->update('pct_configs', $salesData, array('slug' => 'sales_rep_status_flag'));
        }   
        
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length'])+1;
            $sales_rep_lists = $this->sales_model->get_sales_reps($params);
            $json_data['draw'] = intval( $params['draw'] );
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $sales_rep_lists = $this->sales_model->get_sales_reps($params);
        }
        
        if (isset($sales_rep_lists['data']) && !empty($sales_rep_lists['data'])) {
            foreach ($sales_rep_lists['data'] as $key => $value)  {
               // echo "<pre>"; print_r($value); exit;
                $nestedData=array();
                $nestedData[] = $value['first_name']." ".$value['last_name'];
                // $nestedData[] = $value['name'];
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['telephone_no'];
                $nestedData[] = $value['partner_id'];
                $nestedData[] = ($value['is_sales_rep_manager'] == 1) ? 'Sales Rep Manager' : 'Sales Rep';
                $nestedData[] = ($value['is_mail_notification'] == 1) ? 'On' : 'Off';
                $nestedData[] = ($value['status'] == 1) ? 'Enable' : 'Disable';
                
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editOrderUrl = base_url().'order/admin/edit-sales-rep/'.$value['id'];
                    $action = "<div style='display:flex;justify-content: space-around;' ><a href='".$editOrderUrl."' class='edit-agent'title ='Edit Sales Rep Detail'><i class='fas fa-edit' aria-hidden='true'></i></a>";
                    $action .= "<a href='javascript:void(0);' onclick='deleteSalesRep(".$value['id'].")'  title='Delete Sales Rep'><i class='fas fa-trash' aria-hidden='true'></i></a>";
					if($this->common->if_super_admin()) {
						$action .= "<a href='".base_url('order/admin/sales-rep-commission/'.$value['id'])."'  title='View Commissions'><i class='fas fa-dollar' aria-hidden='true'></i></a>";
					}
                    $action .= " </div>";
                    $nestedData[] = $action;
                }
                $data[] = $nestedData;            
            }
        }

        $json_data['recordsTotal'] = intval( $sales_rep_lists['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $sales_rep_lists['recordsFiltered'] );
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function add_sales_rep()
    {
        $data = array();
        $data['title'] = 'PCT Order: Add Sales Rep.';
        $salesRepData = array();
        $data['salesUsers'] = $this->order->get_sales_users();

		$data['success_msg'] = $this->session->flashdata('success');

        if ($this->input->post()) {
			
			$flash_data = array();
			
            $this->form_validation->set_rules('sales_rep_first_name', 'Sales Rep. First Name', 'required', array('required'=> 'Please Enter Sales Rep. First Name'));
            $this->form_validation->set_rules('sales_rep_last_name', 'Sales Rep. Last Name', 'required', array('required'=> 'Please Enter Sales Rep. Last Name'));
            $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required'=> 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
            $this->form_validation->set_rules('telephone', 'Phone Number', 'required', array('required'=> 'Please Enter Phone Number'));
            $this->form_validation->set_rules('partner_id', 'Partner Id', 'trim|required|numeric', array('required'=> 'Please Enter Partner Id'));
            $this->form_validation->set_rules('partner_type_id', 'Partner Type Id', 'trim|required|numeric', array('required'=> 'Please Enter Partner Type Id'));
            //$this->form_validation->set_rules('sales_rep_no_of_open_orders', 'Sales Rep No of Open Orders', 'trim|required|numeric', array('required'=> 'Please Enter Sales Rep No of Open Orders'));
            //$this->form_validation->set_rules('sales_rep_no_of_close_orders', 'Sales Rep No of Close Orders', 'trim|required|numeric', array('required'=> 'Please Enter Sales Rep No of Close Orders'));
            //$this->form_validation->set_rules('sales_rep_premium', 'Sales Rep Premium', 'trim|required|numeric', array('required'=> 'Sales Rep Premium'));
            
              
            $config['upload_path'] = 'uploads/sales-rep/';
            $config['allowed_types'] = 'jpg|png';
            $config['max_size']  = 12000;
                    
            if ($this->form_validation->run() == true) {
                $fileuri = ''; $status = "success";
                if(is_uploaded_file($_FILES['sales_rep_profile_img']['tmp_name'])) 
                {  
                    if (!is_dir('uploads/sales-rep')) 
                    {
                        mkdir('./uploads/sales-rep', 0777, TRUE);
                    }
                    
                    $new_name = 'sales_rep_'.time().rand(10,100000);
                    $config['file_name'] = $new_name;         
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('sales_rep_profile_img')) {
                        $status = 'error';
                        $msg = $this->upload->display_errors();
                    } else{
                        $data = $this->upload->data();
                        $status = "success";
                        $msg = "Borrower File successfully uploaded";
                        $document_name = 'sales_rep_'.time().rand(10,100000).'.'.$data['image_type'];
                        rename('./uploads/sales-rep/'.$data['file_name'], './uploads/sales-rep/'.$document_name);
                        $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep');
                        $fileuri=  $config['upload_path'].$document_name;
                    }
                }

                $fileUrlThankYou = ''; $statusThank = "success";
                if (is_uploaded_file($_FILES['sales_rep_profile_thank_you_img']['tmp_name'])) { 

                    if (!is_dir('uploads/sales-rep')) {
                        mkdir('./uploads/sales-rep', 0777, TRUE);
                    }
                    $sales_rep_profile_thank_you_img_name = 'sales_rep_thank_you'.time().rand(10,100000);
                    $config['file_name'] = $sales_rep_profile_thank_you_img_name;         
                    $this->load->library('upload', $config);
                    $msgThankyou = '';
                    if (!$this->upload->do_upload('sales_rep_profile_thank_you_img')) {
                        $statusThank = 'error';
                        $msgThankyou = $this->upload->display_errors();
                    } else {
                        $dataThank = $this->upload->data();
                        $statusThank = "success";
                        $msgThankyou = "Thank you File successfully uploaded";
                        $document_name = 'sales_rep_thank_you'.time().rand(10,100000).'.'.$dataThank['image_type'];
                        rename('./uploads/sales-rep/'.$dataThank['file_name'], './uploads/sales-rep/'.$document_name);
                        $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep');
                        $fileUrlThankYou =  $config['upload_path'].$document_name;
                    }
                }

                if($status == "success" && $statusThank == "success")
                {
                    $salesRepData = array(
                        'first_name' => $_POST['sales_rep_first_name'],
                        'last_name' => $_POST['sales_rep_last_name'],
                        'email_address' => $_POST['email_address'],
                        'telephone_no' =>  $_POST['telephone'],
                        'partner_id' => $_POST['partner_id'],
                        'partner_type_id' =>  $_POST['partner_type_id'],
                        'is_mail_notification' =>  isset($_POST['is_mail_notification']) ? 1 : 0,
                        'status' => 1,
                        'is_sales_rep' => 1,
                        'is_sales_rep_manager' => isset($_POST['is_sales_rep_manager']) ? 1 : 0,
                        'sales_rep_profile_img' => $fileuri,
                        'sales_rep_profile_thank_you_img' => $fileUrlThankYou,
                        'sales_rep_no_of_open_orders' => $_POST['sales_rep_no_of_open_orders'],
                        'sales_rep_no_of_close_orders' => $_POST['sales_rep_no_of_close_orders'],
                        'sales_rep_premium' => $_POST['sales_rep_premium'],
                        'is_password_updated' => 1,
                        'sales_rep_users' => implode(",",$this->input->post('sales_rep_users')),
						'commission_draw_value' => $this->input->post('commission_draw') ? $this->input->post('commission_draw') : 0,
						'first_in_threshold' => $this->input->post('commission_first_threshold') ? $this->input->post('commission_first_threshold') : 0,
						'apply_bonus' => $this->input->post('apply_bonus') == "1" ? 1 : 0,
						
                    );

                    $insert = $this->sales_model->insert($salesRepData);
                    
                    if ($insert) {
                        $data['success_msg'] = 'Sales Rep. added successfully.';

						if($this->common->if_super_admin()) {
							$this->load->model('order/underwriter_user_model');
							$this->load->model('order/underwriter_user_threshold_model');

							$commission_array = $this->input->post('commission');
							foreach($commission_array as $product_key=>$product_type_array) {
								foreach($product_type_array as $underwriter_key=>$product_underwriter) {
									foreach($product_underwriter as $product_underwriter_tier_id=>$product_underwriter_tier){

										
										if($product_underwriter_tier['type'] == 'fix') {
											$fix_commission = $product_underwriter_tier['fix_commission'];
											if(!empty($fix_commission)) {
												$underwriter_data =[
													'user_id'=>$insert,
													'fix_commission'=>$fix_commission,
													'underwriter_tier_id'=>$product_underwriter_tier_id
												];
		
												$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
											}
										}
										elseif($product_underwriter_tier['type'] == 'override') {
											
											$underwriter_data =[
												'user_id'=>$insert,
												'allow_threshold'=>1,
												'underwriter_tier_id'=>$product_underwriter_tier_id
											];
											
											$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
											
											$threshold_commissions = $product_underwriter_tier['threshold_commission'];
											$threshold_amount_min = $product_underwriter_tier['threshold_amount_min'];
											$threshold_amount_max = $product_underwriter_tier['threshold_amount_max'];
		
											foreach($threshold_commissions as $commission_key=>$threshold_commission) {
												

												if(!empty($threshold_commission) && isset($threshold_amount_min[$commission_key])  && isset($threshold_amount_max[$commission_key])) {
													
													$underwriter_threshold_data =[
														'underwriter_users_id'=>$underwriter_user_id,
														'threshold_amount_min'=>$threshold_amount_min[$commission_key],
														'threshold_amount_max'=>$threshold_amount_max[$commission_key],
														'threshold_commission'=>$threshold_commission,
													];
		
													$this->underwriter_user_threshold_model->insert($underwriter_threshold_data);
												}
											}
										}
									}
								}
							}

							//Escrow commission
							$escrow_commission_array = $this->input->post('escrow_commission');
							$escrow_commission_type = $escrow_commission_array['type'];
							if($escrow_commission_type == 'fix') {
								$fix_commission = $escrow_commission_array['fix_commission'];
								if(!empty($fix_commission)) {
									$underwriter_data =[
										'user_id'=>$insert,
										'fix_commission'=>$fix_commission,
										'is_escrow'=>1
									];

									$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
								}
							}
							elseif($escrow_commission_type == 'override') {
								
								$underwriter_data =[
									'user_id'=>$insert,
									'allow_threshold'=>1,
									'is_escrow'=>1
								];
								
								$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
								
								$threshold_commissions = $escrow_commission_array['threshold_commission'];
								$threshold_amount_min = $escrow_commission_array['threshold_amount_min'];
								$threshold_amount_max = $escrow_commission_array['threshold_amount_max'];

								foreach($threshold_commissions as $commission_key=>$threshold_commission) {
									

									if($threshold_commission >= 0 && isset($threshold_amount_min[$commission_key])  && isset($threshold_amount_max[$commission_key])) {
										
										$underwriter_threshold_data =[
											'underwriter_users_id'=>$underwriter_user_id,
											'threshold_amount_min'=>$threshold_amount_min[$commission_key],
											'threshold_amount_max'=>$threshold_amount_max[$commission_key],
											'threshold_commission'=>$threshold_commission,
										];

										$this->underwriter_user_threshold_model->insert($underwriter_threshold_data);
									}
								}
							}

							//Escrow commission

							//Sales rep Override commission
							if($this->input->post('commission_sales_rep_override_id') > 0 && count($this->input->post('commission_sales_rep_override_val'))) {
								$this->load->model('order/sales_rep_commission_override_model');
								$override_types= $this->input->post('commission_sales_rep_override_val');
								foreach($override_types as $override_type_key=>$override_type_val) {
									if($override_type_val > 0) {

										$commission_override = [
											'user_id'=>$insert,
											'override_user_id'=>$this->input->post('commission_sales_rep_override_id'),
											'product_type'=>$override_type_key,
											'commission'=>$this->input->post('commission_sales_rep_override_val')
										];
										$this->sales_rep_commission_override_model->insert($commission_override);
									}
								}

							}
							//Sales rep Override commission

						}
                            /** Save user Activity */
                            $activity = 'Sales rep user created :- ' . $_POST['email_address'];
                            $this->order->logAdminActivity($activity);
                            /** End save user activity */
							$flash_data['success'] = 'Sales Rep added successfully.';
							$this->session->set_flashdata($flash_data);
							redirect(base_url('order/admin/add-sales-rep'));
						} else {
							$data['error_msg'] = 'Sales Rep. not added.';
							$this->session->set_flashdata($flash_data);
						}
                }
                else
                {
                    $data['sales_rep_profile_img_error_msg'] = $msg;
                    $data['sales_rep_profile_thank_you_img_error_msg'] = $msgThankyou;
                }
                
            } else {
                $data['first_name_error_msg'] = form_error('sales_rep_first_name');
                $data['last_name_error_msg'] = form_error('sales_rep_last_name');
                $data['email_error_msg'] = form_error('email_address');
                $data['phone_error_msg'] = form_error('telephone');
                $data['partner_id_error_msg'] = form_error('partner_id');
                $data['sales_rep_no_of_open_orders_error_msg'] = form_error('sales_rep_no_of_open_orders');
                $data['sales_rep_no_of_close_orders_error_msg'] = form_error('sales_rep_no_of_close_orders');
                $data['sales_rep_premium_error_msg'] = form_error('sales_rep_premium');
                
            }                                       
        }
		$this->load->model('order/underwriter_tier_model');
		
		
		$underwriter_types = UNDERWRITERS;
		$product_types = PRODUCT_TYPE;
		$data['underwriter_tires'] = array();
		foreach($product_types as $product_type) {
			foreach ($underwriter_types as $key=>$underwriter_type) {
				$data['underwriter_tires'][$product_type][$key]=$this->underwriter_tier_model->get_many_by(['product_type'=>$product_type,'underwriter'=>$key]);
			}
		}

		$data['underwriter_types'] = $underwriter_types;
		$data['product_types'] = PRODUCT_TYPE;
		$data['commission_types'] = $this->commission_types;

		$data['is_super_admin'] =$this->common->if_super_admin();
		
        $this->admintemplate->addJS( base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->addJS( base_url('assets/admin/js/jquery.validate.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/js/add-sales-rep.js?v=1.0'));
        
		$this->admintemplate->show("order/sales", "add_sales_rep", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/add_sales_rep', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function edit_sales_rep()
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Sales Rep.';
        $id = $this->uri->segment('4');
        $data['salesUsers'] = $this->order->get_sales_users();
		$data['success_msg'] = $this->session->flashdata('success');

        
        if (isset($id) && !empty($id)) {
            $con = array('id' => $id);
            $sales_rep_info = $this->sales_model->getSalesRep($con);

			$this->load->model('order/underwriter_user_model');
			$this->load->model('order/sales_rep_commission_override_model');
			$existing_underwriter = $this->underwriter_user_model->with('underwriter_user_threshold_obj')->get_many_by('user_id',$id);
			$existing_commission_override_data = $this->sales_rep_commission_override_model->get_many_by('user_id',$id);
            $existing_commission_override = array();
            $existing_commission_override_user = null;
            foreach($existing_commission_override_data as $existing_commission_override_obj){
                if($existing_commission_override_obj->product_type) {
					$existing_commission_override_user = $existing_commission_override_obj->override_user_id;
					$existing_commission_override[$existing_commission_override_obj->product_type] = $existing_commission_override_obj->commission;
				}
            }

            if (isset($_POST) && !empty($_POST)) {

				$flash_data = array();
               
                $this->form_validation->set_rules('sales_rep_first_name', 'Sales Rep. First Name', 'required', array('required'=> 'Please Enter Sales Rep. First Name'));
                $this->form_validation->set_rules('sales_rep_last_name', 'Sales Rep. Last Name', 'required', array('required'=> 'Please Enter Sales Rep. Last Name'));
                $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required'=> 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
                $this->form_validation->set_rules('telephone', 'Phone Number', 'required', array('required'=> 'Please Enter Phone Number'));
                $this->form_validation->set_rules('partner_id', 'Partner Id', 'trim|required|numeric', array('required'=> 'Please Enter Partner Id'));
                $this->form_validation->set_rules('partner_type_id', 'Partner Type Id', 'trim|required|numeric', array('required'=> 'Please Enter Partner Type Id'));
                //$this->form_validation->set_rules('sales_rep_no_of_open_orders', 'Sales Rep No of Open Orders', 'trim|required|numeric', array('required'=> 'Please Enter Sales Rep No of Open Orders'));
                //$this->form_validation->set_rules('sales_rep_no_of_close_orders', 'Sales Rep No of Close Orders', 'trim|required|numeric', array('required'=> 'Please Enter Sales Rep No of Close Orders'));
                //$this->form_validation->set_rules('sales_rep_premium', 'Sales Rep Premium', 'trim|required|numeric', array('required'=> 'Sales Rep Premium'));
				

                $config['upload_path'] = 'uploads/sales-rep/';
                $config['allowed_types'] = 'jpg|png';
                $config['max_size']  = '2048';
                        
                if($this->form_validation->run() == true) {
                    $fileuri = isset($sales_rep_info['sales_rep_profile_img']) && !empty($sales_rep_info['sales_rep_profile_img']) ? $sales_rep_info['sales_rep_profile_img'] : '';
                    $status = "success";
                    if(is_uploaded_file($_FILES['sales_rep_profile_img']['tmp_name'])) 
                    {  
                        if (!is_dir('uploads/sales-rep')) 
                        {
                            mkdir('./uploads/sales-rep', 0777, TRUE);
                        }
                        
                        $new_name = 'sales_rep_'.time().rand(10,100000);

                        $config['file_name'] = $new_name;         
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('sales_rep_profile_img'))
                        {
                            $status = 'error';
                            $msg = $this->upload->display_errors();
                        }
                        else
                        {
                            $data = $this->upload->data();
                            $status = "success";
                            $msg = "File successfully uploaded";
                            $document_name = 'sales_rep_'.time().rand(10,100000).'.'.$data['image_type'];
                            rename('./uploads/sales-rep/'.$data['file_name'], './uploads/sales-rep/'.$document_name);
                            $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep');
                            $fileuri=  $config['upload_path'].$document_name;
                        }
                    }

                    $fileUrlThankYou = isset($sales_rep_info['sales_rep_profile_thank_you_img']) && !empty($sales_rep_info['sales_rep_profile_thank_you_img']) ? $sales_rep_info['sales_rep_profile_thank_you_img'] : ''; 
                    $statusThank = "success";
                    if(is_uploaded_file($_FILES['sales_rep_profile_thank_you_img']['tmp_name'])) { 
    
                        if (!is_dir('uploads/sales-rep')) {
                            mkdir('./uploads/sales-rep', 0777, TRUE);
                        }
                        
                        $sales_rep_profile_thank_you_img_name = 'sales_rep_thank_you'.time().rand(10,100000);
                        $config['file_name'] = $sales_rep_profile_thank_you_img_name;         
                        $this->load->library('upload', $config);
                        $msgThankyou = '';
                        if (!$this->upload->do_upload('sales_rep_profile_thank_you_img')) {
                            $statusThank = 'error';
                            $msgThankyou = $this->upload->display_errors();
                        } else {
                            $data = $this->upload->data();
                            $statusThank = "success";
                            $msgThankyou = "Thank you File successfully uploaded";
                            $document_name = 'sales_rep_thank_you'.time().rand(10,100000).'.'.$data['image_type'];
                            rename('./uploads/sales-rep/'.$data['file_name'], './uploads/sales-rep/'.$document_name);
                            $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep');
                            $fileUrlThankYou =  $config['upload_path'].$document_name;
                        }
                    }

                    if($status == "success" && $statusThank == "success")
                    {
                       
                        $salesRepData = array(
                            'first_name' => $_POST['sales_rep_first_name'],
                            'last_name' => $_POST['sales_rep_last_name'],
                            'email_address' => $_POST['email_address'],
                            'telephone_no' =>  $_POST['telephone'],
                            'partner_id' => $_POST['partner_id'],
                            'partner_type_id' =>  $_POST['partner_type_id'],
                            'is_mail_notification' =>  isset($_POST['is_mail_notification']) ? 1 : 0,
                            'status' => isset($_POST['status']) ? 0 : 1,
                            'is_sales_rep' => 1,
                            'is_sales_rep_manager' => isset($_POST['is_sales_rep_manager']) ? 1 : 0,
                            'sales_rep_profile_img' => $fileuri,
                            'sales_rep_profile_thank_you_img' => $fileUrlThankYou,
                            'sales_rep_no_of_open_orders' => $_POST['sales_rep_no_of_open_orders'],
                            'sales_rep_no_of_close_orders' => $_POST['sales_rep_no_of_close_orders'],
                            'sales_rep_premium' => $_POST['sales_rep_premium'],
                            'is_password_updated' => 1,
                            'sales_rep_users' => implode(",",$this->input->post('sales_rep_users')),
							'commission_draw_value' => $this->input->post('commission_draw') ? $this->input->post('commission_draw') : 0,
							'first_in_threshold' => $this->input->post('commission_first_threshold') ? $this->input->post('commission_first_threshold') : 0,
							'apply_bonus' => $this->input->post('apply_bonus') == "1" ? 1 : 0,
							
                        );
						// var_dump($salesRepData);die;

                        $condition = array('id' => $id);
                        $update = $this->sales_model->update($salesRepData, $condition);
                            
                        if ($update) {
                            $data['success_msg'] = 'Sales Rep. updated successfully.';

							if($this->common->if_super_admin()) {

								$this->load->model('order/underwriter_user_threshold_model');
	
								//$existing_underwriter;
	
								
								
								$existing_underwriter_array = array();
								$existing_escrow = array();
								$commission_array = $this->input->post('commission');
								foreach($existing_underwriter as $existing_underwriter_obj) {
									if($existing_underwriter_obj->underwriter_tier_id) {
										$existing_underwriter_array[$existing_underwriter_obj->underwriter_tier_id] = $existing_underwriter_obj;
									}
									elseif($existing_underwriter_obj->is_escrow) {
										$existing_escrow = $existing_underwriter_obj;
									}
								}
								foreach($commission_array as $product_key=>$product_type_array) {
									foreach($product_type_array as $underwriter_key=>$product_underwriter) {
										foreach($product_underwriter as $product_underwriter_tier_id=>$product_underwriter_tier){
											$existing_underwriter_val = null;
											if(isset($existing_underwriter_array[$product_underwriter_tier_id])) {
												$existing_underwriter_val = $existing_underwriter_array[$product_underwriter_tier_id];
												$this->underwriter_user_threshold_model->delete_by('underwriter_users_id',$existing_underwriter_val->id);
											}
											if($product_underwriter_tier['type'] == 'global') {
												if($existing_underwriter_val) {
													$this->underwriter_user_model->delete($existing_underwriter_val->id);
													
												}
											}
											elseif($product_underwriter_tier['type'] == 'fix') {
												$fix_commission = $product_underwriter_tier['fix_commission'];
												if(!empty($fix_commission)) {
													if($existing_underwriter_val) {
														$underwriter_user_id = $existing_underwriter_val->id;
														$underwriter_data =[
															'underwriter_tier_id'=>$product_underwriter_tier_id,
															'fix_commission'=>$fix_commission,
															'allow_threshold'=>0,
														];
														$this->underwriter_user_model->update($underwriter_user_id,$underwriter_data);
														
	
													}
													else {
	
														$underwriter_data =[
															'user_id'=>$id,
															'underwriter_tier_id'=>$product_underwriter_tier_id,
															'fix_commission'=>$fix_commission,
														];
			
														$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
														
													}
												}
											}
											elseif($product_underwriter_tier['type'] == 'override') {
	
												if($existing_underwriter_val) {
													$underwriter_user_id = $existing_underwriter_val->id;
													$underwriter_data =[
														'underwriter_tier_id'=>$product_underwriter_tier_id,
														'fix_commission'=>0,
														'allow_threshold'=>1,
													];
													$this->underwriter_user_model->update($underwriter_user_id,$underwriter_data);
													
	
												}
												else {
	
													$underwriter_data =[
														'user_id'=>$id,
														'underwriter_tier_id'=>$product_underwriter_tier_id,
														'allow_threshold'=>1,
													];
													
													$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
												}
												
												$threshold_commissions = $product_underwriter_tier['threshold_commission'];
												$threshold_amount_min = $product_underwriter_tier['threshold_amount_min'];
												$threshold_amount_max = $product_underwriter_tier['threshold_amount_max'];
	
												foreach($threshold_commissions as $commission_key=>$threshold_commission) {
													if(!empty($threshold_commission) && isset($threshold_amount_min[$commission_key])  && isset($threshold_amount_max[$commission_key])) {
														$underwriter_threshold_data =[
															'underwriter_users_id'=>$underwriter_user_id,
															'threshold_amount_min'=>$threshold_amount_min[$commission_key],
															'threshold_amount_max'=>$threshold_amount_max[$commission_key],
															'threshold_commission'=>$threshold_commission,
														];
	
														$this->underwriter_user_threshold_model->insert($underwriter_threshold_data);
													}
												}
	
	
	
	
											}
										}
									}
								}

								//Escrow commission
								$escrow_commission_array = $this->input->post('escrow_commission');
								
								$existing_underwriter_val = null;
								if(!empty($existing_escrow)) {
									$existing_underwriter_val = $existing_escrow;
									$this->underwriter_user_threshold_model->delete_by('underwriter_users_id',$existing_underwriter_val->id);
								}
								if($escrow_commission_array['type'] == 'global') {
									if($existing_underwriter_val) {
										$this->underwriter_user_model->delete($existing_underwriter_val->id);
									}
								}
								elseif($escrow_commission_array['type'] == 'fix') {
									$fix_commission = $escrow_commission_array['fix_commission'];
									if(!empty($fix_commission)) {
										if($existing_underwriter_val) {
											$underwriter_user_id = $existing_underwriter_val->id;
											$underwriter_data =[
												'fix_commission'=>$fix_commission,
												'allow_threshold'=>0,
											];
											$this->underwriter_user_model->update($underwriter_user_id,$underwriter_data);
											

										}
										else {

											$underwriter_data =[
												'user_id'=>$id,
												'fix_commission'=>$fix_commission,
												'is_escrow'=>1
											];

											$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
										}
									}
								}
								elseif($escrow_commission_array['type'] == 'override') {

									if($existing_underwriter_val) {
										$underwriter_user_id = $existing_underwriter_val->id;
										$underwriter_data =[
											'fix_commission'=>0,
											'allow_threshold'=>1,
										];
										$this->underwriter_user_model->update($underwriter_user_id,$underwriter_data);
										

									}
									else {

										$underwriter_data =[
											'user_id'=>$id,
											'allow_threshold'=>1,
											'is_escrow'=>1
										];
										
										$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
									}
									
									$threshold_commissions = $escrow_commission_array['threshold_commission'];
									$threshold_amount_min = $escrow_commission_array['threshold_amount_min'];
									$threshold_amount_max = $escrow_commission_array['threshold_amount_max'];

									foreach($threshold_commissions as $commission_key=>$threshold_commission) {
										if($threshold_commission >= 0 && isset($threshold_amount_min[$commission_key])  && isset($threshold_amount_max[$commission_key])) {
											$underwriter_threshold_data =[
												'underwriter_users_id'=>$underwriter_user_id,
												'threshold_amount_min'=>$threshold_amount_min[$commission_key],
												'threshold_amount_max'=>$threshold_amount_max[$commission_key],
												'threshold_commission'=>$threshold_commission,
											];

											$this->underwriter_user_threshold_model->insert($underwriter_threshold_data);
										}
									}


								}
								//Escrow commission
                                //Sales rep Override commission
                                if($this->input->post('commission_sales_rep_override_id') > 0 && count($this->input->post('commission_sales_rep_override_val'))) {
                                    $this->load->model('order/sales_rep_commission_override_model');
                                    $override_types= $this->input->post('commission_sales_rep_override_val');
                                    foreach($override_types as $override_type_key=>$override_type_val) {
                                        if($override_type_val > 0) {
											if(isset($existing_commission_override[$override_type_key])) {

												$commission_override = [
													'override_user_id'=>$this->input->post('commission_sales_rep_override_id'),
													'commission'=>$override_type_val
												];
												$update_by = [
													'product_type'=>$override_type_key,
													'user_id'=>$id,
												];
												$this->sales_rep_commission_override_model->update_by($update_by,$commission_override);

											}
											else {

												$commission_override = [
													'user_id'=>$id,
													'override_user_id'=>$this->input->post('commission_sales_rep_override_id'),
													'product_type'=>$override_type_key,
													'commission'=>$override_type_val
												];
												$this->sales_rep_commission_override_model->insert($commission_override);
											}
                                        }
										else {
											$delete_by = [
												'product_type'=>$override_type_key,
												'user_id'=>$id,
											];
											$this->sales_rep_commission_override_model->delete_by($delete_by);
											
										}

										
                                    }

									$stored_pocedure = "CALL calculate_commission(?)";
									$this->underwriter_user_model->call_sp($stored_pocedure,array('id'=>$this->input->post('commission_sales_rep_override_id')));

                                }
								else {
									$delete_by = [
										'user_id'=>$id,
									];
									$this->sales_rep_commission_override_model->delete_by($delete_by);
									if($existing_commission_override_user) {
										$stored_pocedure = "CALL calculate_commission(?)";
										$this->underwriter_user_model->call_sp($stored_pocedure,array('id'=>$existing_commission_override_user));
									}
								}
								if($existing_commission_override_user && $this->input->post('commission_sales_rep_override_id')  != $existing_commission_override_user) {
									$stored_pocedure = "CALL calculate_commission(?)";
									$this->underwriter_user_model->call_sp($stored_pocedure,array('id'=>$existing_commission_override_user));
								}
								
                                //Sales rep Override commission
								
								$stored_pocedure = "CALL calculate_commission(?)";
								$this->underwriter_user_model->call_sp($stored_pocedure,array('id'=>$id));
								
								
							}
                            /** Save user Activity */
                            $activity = 'Sales rep user details updated :- ' . $_POST['email_address'];
                            $this->order->logAdminActivity($activity);
                            /** End save user activity */
							$flash_data['success'] = 'Sales Rep Updated successfully.';
							$this->session->set_flashdata($flash_data);

							redirect('order/admin/edit-sales-rep/'.$id);
							
                        } else {
                            $data['error_msg'] = 'Error occurred while updating Sales Rep.';
                        }
                    }
                    else
                    {
                        $data['sales_rep_profile_img_error_msg'] = $msg;
                        $data['sales_rep_profile_thank_you_img_error_msg'] = $msgThankyou;
                    }

                } else {
                    $data['first_name_error_msg'] = form_error('sales_rep_first_name');
                    $data['last_name_error_msg'] = form_error('sales_rep_last_name');
                    $data['email_error_msg'] = form_error('email_address');
                    $data['phone_error_msg'] = form_error('telephone');
                    $data['partner_id_error_msg'] = form_error('partner_id');
                    $data['partner_type_id_error_msg'] = form_error('partner_type_id');
                    $data['sales_rep_no_of_open_orders_error_msg'] = form_error('sales_rep_no_of_open_orders');
                    $data['sales_rep_no_of_close_orders_error_msg'] = form_error('sales_rep_no_of_close_orders');
                    $data['sales_rep_premium_error_msg'] = form_error('sales_rep_premium');
					
                }
            }
            $con = array('id' => $id);
            $sales_rep_info = $this->sales_model->getSalesRep($con);

        } else {
            redirect('order/admin/sales-rep');
        }
        $data['sales_rep_info'] = $sales_rep_info;
		//Get commission range condition
		$this->load->model('order/underwriter_tier_model');
		
		
		$underwriter_types = UNDERWRITERS;
		$product_types = PRODUCT_TYPE;
		$data['underwriter_tires'] = array();
		foreach($product_types as $product_type) {
			foreach ($underwriter_types as $key=>$underwriter_type) {
				$data['underwriter_tires'][$product_type][$key]=$this->underwriter_tier_model->get_many_by(['product_type'=>$product_type,'underwriter'=>$key]);
			}
		}

		$data['underwriter_types'] = $underwriter_types;
		$data['product_types'] = PRODUCT_TYPE;
		$data['commission_types'] = $this->commission_types;
		
		$data['existing_underwriter'] = array();
		$data['escrow_commissions'] = array();
		foreach($existing_underwriter as $existing_underwriter_obj) {
			if($existing_underwriter_obj->underwriter_tier_id) {
				$data['existing_underwriter'][$existing_underwriter_obj->underwriter_tier_id] = $existing_underwriter_obj;
			}
			elseif($existing_underwriter_obj->is_escrow) {
				$data['escrow_commissions'] = $existing_underwriter_obj;
			}
		}
		$data['is_super_admin'] =$this->common->if_super_admin();
		$data['commission_sales_rep_override_id']=$existing_commission_override_user;
		$data['commission_sales_rep_override_val']=$existing_commission_override;

        $this->admintemplate->show("order/sales", "edit_sales_rep", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/edit_sales_rep', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function delete_sales_rep()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $salesRepData = array('status' => 0);
            $condition = array('id' => $id);
            $update = $this->sales_model->update($salesRepData, $condition);
            if($update) {
                /** Save user Activity */
                $salesUser = $this->sales_model->getSalesRep($condition);
                $activity = 'Sales rep user deleted :- ' . $salesUser['email_address'];
                $this->order->logAdminActivity($activity);
                /** End save user activity */
                $successMsg = 'Sales Rep. deleted successfully.';
                $response = array('status' =>'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Sales Rep. ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }
        echo json_encode($response);
    }

    public function sales_rep_profile_img_check($str)
    {
        $allowed_mime_type_arr = array('image/jpeg','image/pjpeg','image/png','image/x-png');
        $mime = get_mime_by_extension($_FILES['sales_rep_profile_img']['name']);
        if(isset($_FILES['sales_rep_profile_img']['name']) && $_FILES['sales_rep_profile_img']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('sales_rep_profile_img_check', 'Please select only jpg/png file.');
                return false;
            }
        }else{
            $this->form_validation->set_message('sales_rep_profile_img_check', 'Please choose a file to upload.');
            return false;
        }
    }

    public function remove_sales_rep()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $con = array('id' => $id);
            $sales_rep_info = $this->sales_model->getSalesRep($con);
            $imgPath = isset($sales_rep_info['sales_rep_profile_img']) && !empty($sales_rep_info['sales_rep_profile_img']) ? $sales_rep_info['sales_rep_profile_img'] : '';
            $salesRepData = array('sales_rep_profile_img' => '');
            $condition = array('id' => $id);
            $update = $this->sales_model->update($salesRepData, $condition);
            if($update) {
                unlink('./'.$imgPath);
                $successMsg = 'Sales Rep. Borrower profile image deleted successfully.';
                $response = array('status' =>'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Sales Rep. ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }
        echo json_encode($response);
    }

    public function remove_sales_rep_thank_you()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $con = array('id' => $id);
            $sales_rep_info = $this->sales_model->getSalesRep($con);
            $imgPath = isset($sales_rep_info['sales_rep_profile_thank_you_img']) && !empty($sales_rep_info['sales_rep_profile_thank_you_img']) ? $sales_rep_info['sales_rep_profile_thank_you_img'] : '';
            $salesRepData = array('sales_rep_profile_thank_you_img' => '');
            $condition = array('id' => $id);
            $update = $this->sales_model->update($salesRepData, $condition);
            if($update) {
                unlink('./'.$imgPath);
                $successMsg = 'Sales Rep. Thank you profile image deleted successfully.';
                $response = array('status' =>'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Sales Rep. ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }
        echo json_encode($response);
    }

    public function export_sales_reps()
    {
        $sales_rep = $this->input->post('sales_rep');
        $salesList = $this->sales_model->get_sales_reps_client($sales_rep);
        if (isset($salesList) && !empty($salesList)) {
            $export_data = array();
            foreach ($salesList as $key => $sales) {
                $export_data[] = array(
                    'person_name' => $sales['first_name'] . ' ' . $sales['last_name'],
                    'person_first_name' => $sales['first_name'],
                    'person_last_name' => $sales['last_name'],
                    'person_phone' => $sales['telephone_no'],
                    'email_address_work' => $sales['email_address'],
                    'email_address_home' => null,
                    'organization_name' => $sales['company_name'],
                    'organization_address' => $sales['street_address'] . ' ' . $sales['city'] . ' ' . $sales['state'] . ' ' . $sales['zip_code'],
                    'email_address_home' => null,
                    'email_address_home' => null,
                    'email_address_home' => null,
                    'email_address_home' => null,
                    'email_address_home' => null
                );
            }
            if (isset($export_data) && !empty($export_data)) {
                if (!is_dir('uploads/orders')) {
                    mkdir('./uploads/orders', 0777, true);
                }

                $outputPath = './uploads/orders/output.csv';
                $output = fopen($outputPath, "w");

                $header = array("Person - Name*", "Person - First name", "Person - Last name", "Person - Phone", "Person - Email (Work)", "Person - Email (Home)", "Organization - Name", "Organization - Address", "Deal - Title", "Deal - Value", "Activity - Subject*", "Activity - Due date", "Note - Content*");
                fputcsv($output, $header);

                foreach ($export_data as $key => $value) {
                    fputcsv($output, $value);
                }

                header('Content-Type: application/json');
                $contents = file_get_contents($outputPath);
                $binaryData = base64_encode($contents);
                unlink($outputPath);
                fclose($output);

                $res = array('status' => 'success', 'data' => $binaryData);
            } else {
                $res = array('status' => 'error', 'data' => 'No data found.');
            }
        } else {
            $res = array('status' => 'error', 'data' => 'No data found.');
        }

        echo json_encode($res);
        exit;
    }

    public function export_sales_rep_client()
    {
        $data = array();
        $data['title'] = 'PCT Order: Export Sales Rep Client.';
        $salesRepData = array();
        $data['salesUsers'] = $this->order->get_sales_users();

		$data['success_msg'] = $this->session->flashdata('success');

        if ($this->input->post()) {
			
			$flash_data = array();
			
            $this->form_validation->set_rules('sales_rep_first_name', 'Sales Rep. First Name', 'required', array('required'=> 'Please Enter Sales Rep. First Name'));
            $this->form_validation->set_rules('sales_rep_last_name', 'Sales Rep. Last Name', 'required', array('required'=> 'Please Enter Sales Rep. Last Name'));
            $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required'=> 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
            $this->form_validation->set_rules('telephone', 'Phone Number', 'required', array('required'=> 'Please Enter Phone Number'));
            $this->form_validation->set_rules('partner_id', 'Partner Id', 'trim|required|numeric', array('required'=> 'Please Enter Partner Id'));
            $this->form_validation->set_rules('partner_type_id', 'Partner Type Id', 'trim|required|numeric', array('required'=> 'Please Enter Partner Type Id'));
            //$this->form_validation->set_rules('sales_rep_no_of_open_orders', 'Sales Rep No of Open Orders', 'trim|required|numeric', array('required'=> 'Please Enter Sales Rep No of Open Orders'));
            //$this->form_validation->set_rules('sales_rep_no_of_close_orders', 'Sales Rep No of Close Orders', 'trim|required|numeric', array('required'=> 'Please Enter Sales Rep No of Close Orders'));
            //$this->form_validation->set_rules('sales_rep_premium', 'Sales Rep Premium', 'trim|required|numeric', array('required'=> 'Sales Rep Premium'));
            
              
            $config['upload_path'] = 'uploads/sales-rep/';
            $config['allowed_types'] = 'jpg|png';
            $config['max_size']  = 12000;
                    
            if ($this->form_validation->run() == true) {
                $fileuri = ''; $status = "success";
                if(is_uploaded_file($_FILES['sales_rep_profile_img']['tmp_name'])) 
                {  
                    if (!is_dir('uploads/sales-rep')) 
                    {
                        mkdir('./uploads/sales-rep', 0777, TRUE);
                    }
                    
                    $new_name = 'sales_rep_'.time().rand(10,100000);
                    $config['file_name'] = $new_name;         
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('sales_rep_profile_img')) {
                        $status = 'error';
                        $msg = $this->upload->display_errors();
                    } else{
                        $data = $this->upload->data();
                        $status = "success";
                        $msg = "Borrower File successfully uploaded";
                        $document_name = 'sales_rep_'.time().rand(10,100000).'.'.$data['image_type'];
                        rename('./uploads/sales-rep/'.$data['file_name'], './uploads/sales-rep/'.$document_name);
                        $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep');
                        $fileuri=  $config['upload_path'].$document_name;
                    }
                }

                $fileUrlThankYou = ''; $statusThank = "success";
                if (is_uploaded_file($_FILES['sales_rep_profile_thank_you_img']['tmp_name'])) { 

                    if (!is_dir('uploads/sales-rep')) {
                        mkdir('./uploads/sales-rep', 0777, TRUE);
                    }
                    $sales_rep_profile_thank_you_img_name = 'sales_rep_thank_you'.time().rand(10,100000);
                    $config['file_name'] = $sales_rep_profile_thank_you_img_name;         
                    $this->load->library('upload', $config);
                    $msgThankyou = '';
                    if (!$this->upload->do_upload('sales_rep_profile_thank_you_img')) {
                        $statusThank = 'error';
                        $msgThankyou = $this->upload->display_errors();
                    } else {
                        $dataThank = $this->upload->data();
                        $statusThank = "success";
                        $msgThankyou = "Thank you File successfully uploaded";
                        $document_name = 'sales_rep_thank_you'.time().rand(10,100000).'.'.$dataThank['image_type'];
                        rename('./uploads/sales-rep/'.$dataThank['file_name'], './uploads/sales-rep/'.$document_name);
                        $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep');
                        $fileUrlThankYou =  $config['upload_path'].$document_name;
                    }
                }

                if($status == "success" && $statusThank == "success")
                {
                    $salesRepData = array(
                        'first_name' => $_POST['sales_rep_first_name'],
                        'last_name' => $_POST['sales_rep_last_name'],
                        'email_address' => $_POST['email_address'],
                        'telephone_no' =>  $_POST['telephone'],
                        'partner_id' => $_POST['partner_id'],
                        'partner_type_id' =>  $_POST['partner_type_id'],
                        'is_mail_notification' =>  isset($_POST['is_mail_notification']) ? 1 : 0,
                        'status' => 1,
                        'is_sales_rep' => 1,
                        'is_sales_rep_manager' => isset($_POST['is_sales_rep_manager']) ? 1 : 0,
                        'sales_rep_profile_img' => $fileuri,
                        'sales_rep_profile_thank_you_img' => $fileUrlThankYou,
                        'sales_rep_no_of_open_orders' => $_POST['sales_rep_no_of_open_orders'],
                        'sales_rep_no_of_close_orders' => $_POST['sales_rep_no_of_close_orders'],
                        'sales_rep_premium' => $_POST['sales_rep_premium'],
                        'is_password_updated' => 1,
                        'sales_rep_users' => implode(",",$this->input->post('sales_rep_users')),
						'commission_draw_value' => $this->input->post('commission_draw') ? $this->input->post('commission_draw') : 0,
						'first_in_threshold' => $this->input->post('commission_first_threshold') ? $this->input->post('commission_first_threshold') : 0,
						'apply_bonus' => $this->input->post('apply_bonus') == "1" ? 1 : 0,
						
                    );

                    $insert = $this->sales_model->insert($salesRepData);
                    
                    if ($insert) {
                        $data['success_msg'] = 'Sales Rep. added successfully.';

						if($this->common->if_super_admin()) {
							$this->load->model('order/underwriter_user_model');
							$this->load->model('order/underwriter_user_threshold_model');

							$commission_array = $this->input->post('commission');
							foreach($commission_array as $product_key=>$product_type_array) {
								foreach($product_type_array as $underwriter_key=>$product_underwriter) {
									foreach($product_underwriter as $product_underwriter_tier_id=>$product_underwriter_tier){

										
										if($product_underwriter_tier['type'] == 'fix') {
											$fix_commission = $product_underwriter_tier['fix_commission'];
											if(!empty($fix_commission)) {
												$underwriter_data =[
													'user_id'=>$insert,
													'fix_commission'=>$fix_commission,
													'underwriter_tier_id'=>$product_underwriter_tier_id
												];
		
												$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
											}
										}
										elseif($product_underwriter_tier['type'] == 'override') {
											
											$underwriter_data =[
												'user_id'=>$insert,
												'allow_threshold'=>1,
												'underwriter_tier_id'=>$product_underwriter_tier_id
											];
											
											$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
											
											$threshold_commissions = $product_underwriter_tier['threshold_commission'];
											$threshold_amount_min = $product_underwriter_tier['threshold_amount_min'];
											$threshold_amount_max = $product_underwriter_tier['threshold_amount_max'];
		
											foreach($threshold_commissions as $commission_key=>$threshold_commission) {
												

												if(!empty($threshold_commission) && isset($threshold_amount_min[$commission_key])  && isset($threshold_amount_max[$commission_key])) {
													
													$underwriter_threshold_data =[
														'underwriter_users_id'=>$underwriter_user_id,
														'threshold_amount_min'=>$threshold_amount_min[$commission_key],
														'threshold_amount_max'=>$threshold_amount_max[$commission_key],
														'threshold_commission'=>$threshold_commission,
													];
		
													$this->underwriter_user_threshold_model->insert($underwriter_threshold_data);
												}
											}
										}
									}
								}
							}

							//Escrow commission
							$escrow_commission_array = $this->input->post('escrow_commission');
							$escrow_commission_type = $escrow_commission_array['type'];
							if($escrow_commission_type == 'fix') {
								$fix_commission = $escrow_commission_array['fix_commission'];
								if(!empty($fix_commission)) {
									$underwriter_data =[
										'user_id'=>$insert,
										'fix_commission'=>$fix_commission,
										'is_escrow'=>1
									];

									$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
								}
							}
							elseif($escrow_commission_type == 'override') {
								
								$underwriter_data =[
									'user_id'=>$insert,
									'allow_threshold'=>1,
									'is_escrow'=>1
								];
								
								$underwriter_user_id = $this->underwriter_user_model->insert($underwriter_data);
								
								$threshold_commissions = $escrow_commission_array['threshold_commission'];
								$threshold_amount_min = $escrow_commission_array['threshold_amount_min'];
								$threshold_amount_max = $escrow_commission_array['threshold_amount_max'];

								foreach($threshold_commissions as $commission_key=>$threshold_commission) {
									

									if($threshold_commission >= 0 && isset($threshold_amount_min[$commission_key])  && isset($threshold_amount_max[$commission_key])) {
										
										$underwriter_threshold_data =[
											'underwriter_users_id'=>$underwriter_user_id,
											'threshold_amount_min'=>$threshold_amount_min[$commission_key],
											'threshold_amount_max'=>$threshold_amount_max[$commission_key],
											'threshold_commission'=>$threshold_commission,
										];

										$this->underwriter_user_threshold_model->insert($underwriter_threshold_data);
									}
								}
							}

							//Escrow commission

							//Sales rep Override commission
							if($this->input->post('commission_sales_rep_override_id') > 0 && count($this->input->post('commission_sales_rep_override_val'))) {
								$this->load->model('order/sales_rep_commission_override_model');
								$override_types= $this->input->post('commission_sales_rep_override_val');
								foreach($override_types as $override_type_key=>$override_type_val) {
									if($override_type_val > 0) {

										$commission_override = [
											'user_id'=>$insert,
											'override_user_id'=>$this->input->post('commission_sales_rep_override_id'),
											'product_type'=>$override_type_key,
											'commission'=>$this->input->post('commission_sales_rep_override_val')
										];
										$this->sales_rep_commission_override_model->insert($commission_override);
									}
								}

							}
							//Sales rep Override commission

						}
                            /** Save user Activity */
                            $activity = 'Sales rep user created :- ' . $_POST['email_address'];
                            $this->order->logAdminActivity($activity);
                            /** End save user activity */
							$flash_data['success'] = 'Sales Rep added successfully.';
							$this->session->set_flashdata($flash_data);
							redirect(base_url('order/admin/add-sales-rep'));
						} else {
							$data['error_msg'] = 'Sales Rep. not added.';
							$this->session->set_flashdata($flash_data);
						}
                }
                else
                {
                    $data['sales_rep_profile_img_error_msg'] = $msg;
                    $data['sales_rep_profile_thank_you_img_error_msg'] = $msgThankyou;
                }
                
            } else {
                $data['first_name_error_msg'] = form_error('sales_rep_first_name');
                $data['last_name_error_msg'] = form_error('sales_rep_last_name');
                $data['email_error_msg'] = form_error('email_address');
                $data['phone_error_msg'] = form_error('telephone');
                $data['partner_id_error_msg'] = form_error('partner_id');
                $data['sales_rep_no_of_open_orders_error_msg'] = form_error('sales_rep_no_of_open_orders');
                $data['sales_rep_no_of_close_orders_error_msg'] = form_error('sales_rep_no_of_close_orders');
                $data['sales_rep_premium_error_msg'] = form_error('sales_rep_premium');
                
            }                                       
        }
		$this->admintemplate->addJS( base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->addJS( base_url('assets/admin/js/jquery.validate.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/js/add-sales-rep.js'));
        
		$this->admintemplate->show("order/sales", "export_sales_rep_client", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/add_sales_rep', $data);
        // $this->load->view('order/layout/footer', $data);
    }
}
