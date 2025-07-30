<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommissionRange extends MX_Controller {
	
	public $commission_headers = ['min_value','max_value','premium'];
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
		$this->load->model('order/commission_range_model');
		$this->load->model('order/underwriter_tier_model');
        // $this->common->is_admin();
		$this->common->is_super_admin();
    }

    public function index($product_type_name = 'all',$underwriter_tire = 0)
    {
        $data = array();
        
        $data['title'] = 'PCT Order: Commission Range.';
		
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');

		$data['product_types'] = PRODUCT_TYPE;
		$product_types = PRODUCT_TYPE;
		foreach ($product_types as $product_type) {
			$data['underwriter_tiers'][$product_type] = $this->underwriter_tier_model->order_by('underwriter')->get_many_by('product_type',$product_type);
			
		}
		$data['filter_product'] = $product_type_name;
		$data['filter_underwriter'] = $underwriter_tire;

		if($underwriter_tire > 0) {
			$data['commission_details'] = $this->commission_range_model->with('underwriter_tier_obj')->get_many_by('underwriter_tier',$underwriter_tire);
		}
		elseif(in_array($product_type_name,$product_types)) {
			$data['commission_details'] = $this->commission_range_model->with('underwriter_tier_obj')->get_many_by('product_type',$product_type_name);
		}
		else {
			$data['commission_details'] = $this->commission_range_model->with('underwriter_tier_obj')->get_all();
		}
		$this->admintemplate->show("order/sales", "commission_range", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/commission_range', $data);
        // $this->load->view('order/layout/footer', $data);
    }

	

	public function add_commission_range()
    {
        $data = array();
        $data['title'] = 'PCT Order: Add Commission Range';

        if ($this->input->post()) {
            
            $this->form_validation->set_rules('product_type', 'Product Type', 'trim|required');
            $this->form_validation->set_rules('premium', 'Premium', 'trim|required|numeric');
            $this->form_validation->set_rules('revenue_range_min', 'Minimum Revenue Range', 'trim|required|numeric');
            $this->form_validation->set_rules('revenue_range_max', 'Maximum Revenue Range', 'trim|required|numeric');

			if(!empty($this->input->post('product_type'))) {
				$this->form_validation->set_rules('underwriter_tier['.$this->input->post('product_type').']', 'Underwriter Tier', 'trim|required');
			}
              
           
            if ($this->form_validation->run() == true) {
                
                    $commissionData = array(
						'product_type' =>$this->input->post('product_type') ,
						'underwriter_tier' =>$this->input->post('underwriter_tier['.$this->input->post('product_type').']') ,
						'premium' => !empty($this->input->post('premium')) ? $this->input->post('premium') : 0,
						'min_revenue' => !empty($this->input->post('revenue_range_min')) ? $this->input->post('revenue_range_min') : 0,
						'max_revenue' => !empty($this->input->post('revenue_range_max')) ? $this->input->post('revenue_range_max') : 0,
                    );
					
                    $insert = $this->commission_range_model->insert($commissionData);
                    
                    if ($insert) {
                        $flash_data['success'] = 'Commission Range added successfully.';
						$this->common->updateCommisssionCalculation();
                    } else {
                        $flash_data['error'] = 'Commission Range not added.';
                    }
					
					$this->session->set_flashdata($flash_data);
					redirect(base_url('order/admin/commission-range'));
                
                
            }                                       
        }
		$product_types = PRODUCT_TYPE;
		foreach ($product_types as $product_type) {
			$data['underwriter_tiers'][$product_type] = $this->underwriter_tier_model->order_by('underwriter')->get_many_by('product_type',$product_type);
			
		}
		// $data['underwriter_tiers_sale'] = $this->underwriter_tier_model->order_by('underwriter')->get_all();
		
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');
		$data['product_types'] = PRODUCT_TYPE;
		$this->admintemplate->show("order/sales", "add_commission_range", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/add_commission_range', $data);
        // $this->load->view('order/layout/footer', $data);
    }



	public function import_commission_range()
    {
        $data = array();
        $data['title'] = 'PCT Order: Import Commission Range';

        if ($this->input->post()) {
			if (empty($_FILES['file']['name'])){
				$this->form_validation->set_rules('file', 'CSV file', 'required');
			}
			else {
				$allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
				
				$mime = get_mime_by_extension($_FILES['file']['name']);
				$fileAr = explode('.', $_FILES['file']['name']);
				$ext = end($fileAr);
				if(($ext == 'csv') && in_array($mime, $allowed_mime_types)){

				}else{
					$this->form_validation->set_rules('file', 'CSV file', 'required',
								array('required' => 'Please select only CSV file to upload.')
						);
				}
			
			}
            
            $this->form_validation->set_rules('product_type', 'Product Type', 'trim|required');
            

			if(!empty($this->input->post('product_type'))) {
				$this->form_validation->set_rules('underwriter_tier['.$this->input->post('product_type').']', 'Underwriter Tier', 'trim|required');
			} 
           
            if ($this->form_validation->run() == true) {
                if(is_uploaded_file($_FILES['file']['tmp_name']))
                {
                    // Load CSV reader library
                    $this->load->library('CSVReader');
                    
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
                    // Insert/update CSV data into database
					$insert = false;
                    if(!empty($csvData))
                    {
						//Delete existing records
						$check_condition = [
							'product_type' =>$this->input->post('product_type') ,
							'underwriter_tier' =>$this->input->post('underwriter_tier['.$this->input->post('product_type').']') ,
						];
						$this->commission_range_model->delete_by($check_condition);

                        foreach($csvData as $row)
                        {
                            if(isset($row['min_value']) && (isset($row['max_value'])) &&  !(empty($row['premium'])))
                            {
								 
								$commissionData = array(
									'product_type' =>$this->input->post('product_type') ,
									'underwriter_tier' =>$this->input->post('underwriter_tier['.$this->input->post('product_type').']') ,
									'premium' => !empty($row['premium']) ? $row['premium'] : 0,
									'min_revenue' => !empty($row['min_value']) ? $row['min_value'] : 0,
									'max_revenue' => !empty($row['max_value']) ? $row['max_value'] : 0,
								);
								
								$insert = $this->commission_range_model->insert($commissionData);
								

							}
						}
					}
				}  
                    
				if ($insert) {
					$flash_data['success'] = 'Commission Range imported successfully.';
					$this->common->updateCommisssionCalculation();
				} else {
					$flash_data['error'] = 'Commission Range not added.';
				}
				
				$this->session->set_flashdata($flash_data);
				redirect(base_url('order/admin/commission-range'));
                
                
            }                                    
        }
		$product_types = PRODUCT_TYPE;
		foreach ($product_types as $product_type) {
			$data['underwriter_tiers'][$product_type] = $this->underwriter_tier_model->order_by('underwriter')->get_many_by('product_type',$product_type);
			
		}

		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');
		$data['product_types'] = PRODUCT_TYPE;
		$this->admintemplate->show("order/sales", "import_commission_range", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/import_commission_range', $data);
        // $this->load->view('order/layout/footer', $data);
    }
	public function template_commission_range()
    {
		$filename = 'commission_range.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; ");

		// file creation
		$file = fopen('php://output', 'w');

		$header = $this->commission_headers;
		fputcsv($file, $header);
		fputcsv($file, [0,50000,350,10]);
		fclose($file);
		exit;

	}
	public function export_commission_range()
    {

		$data = array();
        $data['title'] = 'PCT Order: Export Commission Range';

        if ($this->input->post()) {
			
            $this->form_validation->set_rules('product_type', 'Product Type', 'trim|required');

			if(!empty($this->input->post('product_type'))) {
				$this->form_validation->set_rules('underwriter_tier['.$this->input->post('product_type').']', 'Underwriter Tier', 'trim|required');
			} 
           
            if ($this->form_validation->run() == true) {
					$tier_id = $this->input->post('underwriter_tier['.$this->input->post('product_type').']');
					
					$underwriter_tire = $this->underwriter_tier_model->get($tier_id);
					$check_condition = [
						'product_type' =>$this->input->post('product_type') ,
						'underwriter_tier' =>$tier_id,
					];
					$exist_records = $this->commission_range_model->order_by('min_revenue','ASC')->get_many_by($check_condition);
					$filename = $underwriter_tire->product_type.'_'.$underwriter_tire->underwriter.'_'.$underwriter_tire->title.'.csv';
					header("Content-Description: File Transfer");
					header("Content-Disposition: attachment; filename=$filename");
					header("Content-Type: application/csv; ");
			
					// file creation
					$file = fopen('php://output', 'w');
			
					$header = $this->commission_headers;
					fputcsv($file, $header);
			
					foreach ($exist_records as $line){
						fputcsv($file,array($line->min_revenue,$line->max_revenue,$line->premium));
					}
			
					fclose($file);
					exit;
					
				 
                    
				
				redirect(base_url('order/admin/commission-range'));
                
                
            }                                    
        }
		$product_types = PRODUCT_TYPE;
		foreach ($product_types as $product_type) {
			$data['underwriter_tiers'][$product_type] = $this->underwriter_tier_model->order_by('underwriter')->get_many_by('product_type',$product_type);
			
		}

		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');
		$data['product_types'] = PRODUCT_TYPE;
		$this->admintemplate->show("order/sales", "export_commission_range", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/export_commission_range', $data);
        // $this->load->view('order/layout/footer', $data);

	}
    
    public function edit_commission_range($id)
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Commission Range';
        
		$record = $this->commission_range_model->get($id);
        
        if (!empty($record)) {
			if ($this->input->post()) {
            
            
			$this->form_validation->set_rules('product_type', 'Product Type', 'trim|required');
            // $this->form_validation->set_rules('underwriter_tier', 'Underwriter Tier', 'trim|required');
			$this->form_validation->set_rules('premium', 'Premium', 'trim|required|numeric');
            $this->form_validation->set_rules('revenue_range_min', 'Minimum Revenue Range', 'trim|required|numeric');
            $this->form_validation->set_rules('revenue_range_max', 'Maximum Revenue Range', 'trim|required|numeric');

			if(!empty($this->input->post('product_type'))) {
				$this->form_validation->set_rules('underwriter_tier['.$this->input->post('product_type').']', 'Underwriter Tier', 'trim|required');
			}
				  
			   
				if ($this->form_validation->run() == true) {
					
						$commissionData = array(
							'product_type' =>$this->input->post('product_type') ,
							'underwriter_tier' =>$this->input->post('underwriter_tier['.$this->input->post('product_type').']') ,
							'premium' => !empty($this->input->post('premium')) ? $this->input->post('premium') : 0,
							'min_revenue' => !empty($this->input->post('revenue_range_min')) ? $this->input->post('revenue_range_min') : 0,
							'max_revenue' => !empty($this->input->post('revenue_range_max')) ? $this->input->post('revenue_range_max') : 0,
						);
						
						$update = $this->commission_range_model->update($id,$commissionData);
						
						if ($update) {
							//Check data before call function
							
							$this->common->updateCommisssionCalculation();
							
							$flash_data['success'] = 'Commission Range updated successfully.';
						} else {
							$flash_data['error'] = 'Commission Range not updated.';
						}
						
						$this->session->set_flashdata($flash_data);
						redirect(base_url('order/admin/edit-commission-range/'.$id));
					
					
				}                                       
			}

        } else {
            redirect('order/admin/commission-range');
        }
		$data['underwriter_tiers'] = $this->underwriter_tier_model->order_by('underwriter')->get_all();
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');
		$product_types = PRODUCT_TYPE;
		foreach ($product_types as $product_type) {
			$data['underwriter_tiers'][$product_type] = $this->underwriter_tier_model->order_by('underwriter')->get_many_by('product_type',$product_type);
			
		}
        $data['record'] = $record;
		$data['product_types'] = PRODUCT_TYPE;
		
		$this->admintemplate->show("order/sales", "edit_commission_range", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/edit_commission_range', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function delete_commission_range($id)
    {
		$status = false;
		if($this->input->post('action') == 'delete') {
			$delete_status = $this->commission_range_model->delete($id);
			if ($delete_status) {
				$flash_data['success'] = 'Commission Range deleted successfully.';
				$this->common->updateCommisssionCalculation();
				$status = true;
			} else {
				$flash_data['error'] = 'Commission Range not deleted.';
			}
			
			$this->session->set_flashdata($flash_data);

		}
		echo json_encode(['status'=>$status]);
    }

	public function index_underwriter_tier()
    {
        $data = array();
        
        $data['title'] = 'PCT Order: Underwriter Tier.';

		$data['underwriter_tier_details'] = $this->underwriter_tier_model->get_all();
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');

		$this->admintemplate->show("order/sales", "underwriter_tiers", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/underwriter_tiers', $data);
        // $this->load->view('order/layout/footer', $data);
    }

	public function add_underwriter_tier()
    {

        $data = array();
        $data['title'] = 'PCT Order: Add Underwriter Tier';

        if ($this->input->post()) {
            
            $this->form_validation->set_rules('underwriter_type', 'Underwriter', 'trim|required');
            $this->form_validation->set_rules('product_type', 'Product Type', 'trim|required');
            $this->form_validation->set_rules('title', 'Tier Title', 'trim|required');
			$this->form_validation->set_rules('commission', 'Commission %', 'trim|required|numeric');
              
           
            if ($this->form_validation->run() == true) {
                
                    $underwriterData = array(
						'product_type' =>$this->input->post('product_type') ,
						'underwriter' =>$this->input->post('underwriter_type') ,
						'title' =>$this->input->post('title') ,
						'commission' => !empty($this->input->post('commission'))?$this->input->post('commission'):0 ,
						'description' =>!empty($this->input->post('description'))?$this->input->post('description'):null ,
                    );
					
                    $insert = $this->underwriter_tier_model->insert($underwriterData);
                    
                    if ($insert) {
                        $flash_data['success'] = 'Underwriter Tier added successfully.';
						$this->common->updateCommisssionCalculation();
                    } else {
                        $flash_data['error'] = 'Underwriter Tier not added.';
                    }
					
					$this->session->set_flashdata($flash_data);
					redirect(base_url('order/admin/underwriter-tier'));
					
                
                
            }                                       
        }
		$data['underwriter_types'] = UNDERWRITERS;
		$data['product_types'] = PRODUCT_TYPE;
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');
		$this->admintemplate->show("order/sales", "add_underwriter_tier", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/add_underwriter_tier', $data);
        // $this->load->view('order/layout/footer', $data);
    }
    
    public function edit_underwriter_tier($id)
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Underwriter Tier';
        
		$record = $this->underwriter_tier_model->get($id);
        
        if (!empty($record)) {
			if ($this->input->post()) {
            
				$this->form_validation->set_rules('underwriter_type', 'Underwriter', 'trim|required');
				$this->form_validation->set_rules('product_type', 'Product Type', 'trim|required');
            	$this->form_validation->set_rules('title', 'Tier Title', 'trim|required');  
				$this->form_validation->set_rules('commission', 'Commission %', 'trim|required|numeric');
			   
				if ($this->form_validation->run() == true) {
					
						$underwriterData = array(
							'product_type' =>$this->input->post('product_type') ,
							'underwriter' =>$this->input->post('underwriter_type') ,
							'title' =>$this->input->post('title') ,
							'commission' => !empty($this->input->post('commission'))?$this->input->post('commission'):0 ,
							'description' =>!empty($this->input->post('description'))?$this->input->post('description'):null ,
						);
						
						$update = $this->underwriter_tier_model->update($id,$underwriterData);
						
						if ($update) {
							//Check data before call function
							if($record->product_type != $underwriterData['product_type'] || $record->underwriter != $underwriterData['underwriter'] || $record->commission != $underwriterData['commission']) {
								$this->common->updateCommisssionCalculation();
							}
							$flash_data['success'] = 'Underwriter Tier updated successfully.';
						} else {
							$flash_data['error'] = 'Underwriter Tier not updated.';
						}
						
						$this->session->set_flashdata($flash_data);
						redirect(base_url('order/admin/edit-underwriter-tier/'.$id));
				}                                       
			}

        } else {
            redirect('order/admin/underwriter-tier');
        }
		$data['underwriter_types'] = UNDERWRITERS;
		$data['product_types'] = PRODUCT_TYPE;
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');
        $data['record'] = $record;
		$this->admintemplate->show("order/sales", "edit_underwriter_tier", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/edit_underwriter_tier', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function delete_underwriter_tier($id)
    {
		$status = false;
		if($this->input->post('action') == 'delete') {
			$delete_status = $this->underwriter_tier_model->delete($id);
			if ($delete_status) {
				$flash_data['success'] = 'Underwriter Tier deleted successfully.';
				$this->common->updateCommisssionCalculation();
				$status = true;
			} else {
				$flash_data['error'] = 'Underwriter Tier not deleted.';
			}
			
			$this->session->set_flashdata($flash_data);

		}
		echo json_encode(['status'=>$status]);
    }


	public function commission_files($year = 0,$month =0,$sales_rep=0)
    {
		$this->load->model('order/user_monthly_commission_model');
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            if (!is_dir('uploads/file_document')) {
                mkdir('./uploads/file_document', 0777, TRUE);
            }
            $error = '';
            if (!empty($_FILES['file']['name'])) {
                $document_name = 'commission_'.time();
                $config['upload_path'] = './uploads/file_document/';
                $config['allowed_types'] = 'pdf';   
                $config['max_size'] = 12000;
                // $userdata = $this->session->userdata('user');
                $config['file_name'] = $document_name;
                $this->load->library('upload', $config);
                if (! $this->upload->do_upload('file')) {
                    $error = $this->upload->display_errors();
                } else { 
                    $data = $this->upload->data();
                    $document_name = $data['file_name'];

					$this->load->library('order/order');
					$this->order->uploadDocumentOnAwsS3($document_name, 'file_document');
					//Check for existing record
					$check_condition = [
						'user_id' => $this->input->post('sales_rep'),
                        'commission_month' => $this->input->post('commission_month'),
                        'commission_year' => $this->input->post('commission_year'),
					];

					$is_exist = $this->user_monthly_commission_model->get_by($check_condition);
					if($is_exist) {
						$fileData = array(
							'pdf_name' => $this->input->post('name'),
							'commisssion_pdf' => $document_name
						);
						$inserted = $this->user_monthly_commission_model->update($is_exist->id,$fileData);
					}
					else {

						$fileData = array(
							'user_id' => $this->input->post('sales_rep'),
							'pdf_name' => $this->input->post('name'),
							'commission_month' => $this->input->post('commission_month'),
							'commission_year' => $this->input->post('commission_year'),
							'commisssion_pdf' => $document_name
						);
	
						
						$inserted = $this->user_monthly_commission_model->insert($fileData);
					}
                    
                    if($inserted) {
						
						$this->session->set_flashdata('success','File uploaded.');

						//Send notification
						$user_id = $this->input->post('sales_rep');
						$month_i = $this->input->post('commission_month');
						$dt = DateTime::createFromFormat('!m', $month_i);
						$month_i = $dt->format('F') ;
						$year_i = $this->input->post('commission_year');
						$message = 'Commission File is uploaded for '.$month_i.' '.$year_i;
						$notificationData = array(
							'sent_user_id' => $user_id,
							'message' => $message,
							'is_admin' => 0,
							'type' =>  'created'
						);
						$this->load->model('order/home_model');
						$this->home_model->insert($notificationData, 'pct_order_notifications');
						$this->order->sendNotification($message, 'created', $user_id, 0);
						//Send notification
                        redirect('order/admin/commission-files');
                    }
                } 
            } else {
                $error = 'Please slect file to uplaod';
            }
            if($error == '') {
                $error = 'Something went wrong. Please try again';
            }
            $this->session->set_flashdata('error',$error);
            redirect('order/admin/commission-files');
        }
        $data = array();
        $data['title'] = 'PCT Order: Commission Files';
        
		
		
		if($year == 0) {
			$year = date('Y');
		}
		if($month == 0) {
			$month = date('m');
		}
		$data['filter_month'] = $month;
		$data['filter_year'] = $year;
		$where_array = [
			'commission_month' => $month,
			'commission_year' => $year,
			'commisssion_pdf !='=> null,
		];
		if($sales_rep > 0) {
			$where_array['user_id'] = $sales_rep;
		}
		
		
		$data['commission_files'] = $this->user_monthly_commission_model->with('sales_rep_obj')->get_many_by($where_array);

		$this->load->model('order/customer_basic_details_model');
        $data['sales_reps'] = $this->customer_basic_details_model->get_many_by(['status'=>1,'is_sales_rep'=>1]);
		$this->admintemplate->show("order/sales", "commssion_files", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/commssion_files', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function delete_commission_file($id)
    {
		$status = false;
		if($this->input->post('action') == 'delete') {
			$this->load->model('order/user_monthly_commission_model');
			$fileData = array(
				'pdf_name' => null,
				'commisssion_pdf' => null
			);
			$delete_status = $this->user_monthly_commission_model->update($id,$fileData);
			if ($delete_status) {
				$flash_data['success'] = 'Commission File deleted successfully.';
				$status = true;
			} else {
				$flash_data['error'] = 'Commission File not deleted.';
			}
			
			$this->session->set_flashdata($flash_data);

		}
		echo json_encode(['status'=>$status]);

        
    }

	public function commission_config() 
	{
		$this->load->model('order/config_settings_model');
		$data = array();
        
        $data['title'] = 'PCT Order: Commisison config.';
		
		$data['config_details'] = $this->config_settings_model->get_many_by('slug','escrow_commission');
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');

		$this->admintemplate->show("order/config", "index", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/config/index', $data);
        // $this->load->view('order/layout/footer', $data);


	}

	public function edit_commission_config($id)
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Comission Configuartion';
		$this->load->model('order/config_settings_model');
        
		$record = $this->config_settings_model->get($id);
        
        if (!empty($record)) {
			if ($this->input->post()) {
            
            	$this->form_validation->set_rules('title', 'Tier Title', 'trim|required');  
				$this->form_validation->set_rules('commission', 'Commission %', 'trim|required|numeric');
			   
				if ($this->form_validation->run() == true) {
					
						$configData = array(
							'title' =>$this->input->post('title') ,
							'value' => !empty($this->input->post('commission'))?$this->input->post('commission'):0 ,
						);
						
						$update = $this->config_settings_model->update($id,$configData);
						
						if ($update) {
							//Check data before call function
							if($record->value != $configData['value'] ) {
								$this->common->updateCommisssionCalculation();
							}
							$flash_data['success'] = 'Commission value successfully.';
						} else {
							$flash_data['error'] = 'Commission value not updated.';
						}
						
						$this->session->set_flashdata($flash_data);
						redirect(base_url('order/admin/commission-config'));
				}                                       
			}

        } else {
            redirect('order/admin/commission-config');
        }
		
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');
        $data['record'] = $record;
		$this->admintemplate->show("order/config", "edit_config", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/config/edit_config', $data);
        // $this->load->view('order/layout/footer', $data);
    }

	public function sales_rep_commission($sales_rep_id) {
		
		$data['salesUsers'] = array();
		$data['title'] = 'Sales Production History | Pacific Coast Title Company';
		$commissionHistory = array();
		$current_year = date('Y');
		$this->load->model('admin/order/user_monthly_commission_model');
		for ($iM = 1; $iM <= (int)date('m'); $iM++) {
			$dateObj   = DateTime::createFromFormat('!m', $iM);
			$monthName = $dateObj->format('F'); 
			$commissionHistory[$iM-1]['month'] = $monthName;
			$commissionHistory[$iM-1]['month_num'] = $iM;
			$get_month_conditon = [
				'user_id'=>$sales_rep_id,
				'commission_year'=>$current_year,
				'commission_month'=>$iM,
			];
			$commisson_data = $this->user_monthly_commission_model->get_by($get_month_conditon);
			if($iM == date('m') && (!($commisson_data) || empty($commisson_data->commission))) {
				//Call procedure
				$stored_pocedure = "CALL calculate_commission(?)";
				$this->user_monthly_commission_model->call_sp($stored_pocedure,array('id'=>$sales_rep_id));
				$commisson_data = $this->user_monthly_commission_model->get_by($get_month_conditon);
			}
			$commissionHistory[$iM-1]['commission_data'] = $commisson_data;

			

		}
		$data['commissionHistory'] = $commissionHistory;
		// echo '<pre>';var_dump($data);die;

        $this->admintemplate->show("order/sales", "commission_list", $data);

		// $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/commission_list', $data);
        // $this->load->view('order/layout/footer', $data);
	}
	public function index_bonus()
    {
        $data = array();
        
        $data['title'] = 'PCT Order: Bonus Range.';
		
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');

		$this->load->model('admin/order/commission_bonus_model');
		
		$data['bonus_details'] = $this->commission_bonus_model->order_by('min_range','ASC')->get_all();
		
		$this->admintemplate->show("order/sales", "commission_bonus", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/commission_bonus', $data);
        // $this->load->view('order/layout/footer', $data);
    }

	public function add_bonus()
    {
        $data = array();
        $data['title'] = 'PCT Order: Add Bonus';

        if ($this->input->post()) {
            
            
			$this->form_validation->set_rules('revenue_range_min', 'From Revenue Range', 'trim|required|numeric');
            $this->form_validation->set_rules('bonus_amount', 'Bous Amount', 'trim|required|numeric');

			if(!empty($this->input->post('product_type'))) {
				$this->form_validation->set_rules('underwriter_tier['.$this->input->post('product_type').']', 'Underwriter Tier', 'trim|required');
			}
              
           
            if ($this->form_validation->run() == true) {
                
                    $commissionData = array(
						'min_range' => !empty($this->input->post('revenue_range_min')) ? $this->input->post('revenue_range_min') : 0,
						'bonus_amount' => !empty($this->input->post('bonus_amount')) ? $this->input->post('bonus_amount') : 0,
						
                    );
					$this->load->model('admin/order/commission_bonus_model');
					
                    $insert = $this->commission_bonus_model->insert($commissionData);
                    
                    if ($insert) {
                        $flash_data['success'] = 'Bonus added successfully.';
						$this->common->updateCommisssionCalculation();
                    } else {
                        $flash_data['error'] = 'Bonus not added.';
                    }
					
					$this->session->set_flashdata($flash_data);
					redirect(base_url('order/admin/commission-bonus'));
            }                                       
        }
		
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');
		$this->admintemplate->show("order/sales", "add_commission_bonus", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/add_commission_bonus', $data);
        // $this->load->view('order/layout/footer', $data);
    }


    public function edit_bonus($id)
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Bonus';

		$this->load->model('admin/order/commission_bonus_model');
        
		$record = $this->commission_bonus_model->get($id);
        
        if (!empty($record)) {
			if ($this->input->post()) {
            
            
				$this->form_validation->set_rules('revenue_range_min', 'From Revenue Range', 'trim|required|numeric');
				$this->form_validation->set_rules('bonus_amount', 'Bous Amount', 'trim|required|numeric');

			   
				if ($this->form_validation->run() == true) {
					
					$commissionData = array(
						'min_range' => !empty($this->input->post('revenue_range_min')) ? $this->input->post('revenue_range_min') : 0,
						'bonus_amount' => !empty($this->input->post('bonus_amount')) ? $this->input->post('bonus_amount') : 0,
						
                    );
						
						$update = $this->commission_bonus_model->update($id,$commissionData);
						
						if ($update) {
							//Check data before call function
							
							$this->common->updateCommisssionCalculation();
							
							$flash_data['success'] = 'Bonus updated successfully.';
						} else {
							$flash_data['error'] = 'Bonus not updated.';
						}
						
						$this->session->set_flashdata($flash_data);
						redirect(base_url('order/admin/edit-commission-bonus/'.$id));
					
					
				}                                       
			}

        } else {
            redirect('order/admin/commission-bonus');
        }
		
		$data['success_msg'] = $this->session->flashdata('success');
		$data['error_msg'] = $this->session->flashdata('error');
		
        $data['record'] = $record;
		
		$this->admintemplate->show("order/sales", "edit_commission_bonus", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/sales/edit_commission_bonus', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function delete_bonus($id)
    {
		$status = false;
		if($this->input->post('action') == 'delete') {
			$this->load->model('admin/order/commission_bonus_model');
			$delete_status = $this->commission_bonus_model->delete($id);
			if ($delete_status) {
				$flash_data['success'] = 'Bonus deleted successfully.';
				$this->common->updateCommisssionCalculation();
				$status = true;
			} else {
				$flash_data['error'] = 'Bonus not deleted.';
			}
			
			$this->session->set_flashdata($flash_data);

		}
		echo json_encode(['status'=>$status]);
    }

	

}
