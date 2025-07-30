<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class PctCalculator extends MX_Controller {
	private $return_response;

	function __construct() 
    {
		parent::__construct();
		$this->return_response = ['status'=>false,'message'=>'','data'=>array()];
		$this->load->library('order/order');
		$this->load->library('order/resware');
		$this->load->model('order/home_model');
		$this->load->model('order/apiLogs');
		$headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
	if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            $verify_token = $matches[1];
			$env_token = env('PCT_CALC_TOKEN');
			if( ! ($verify_token === $env_token)) {
				$this->return_response['message']='Invalid Token';
				echo json_encode($this->return_response);
				exit;
			}
        }
    }
	else {
			$this->return_response['message']='Please provide Token';
			echo json_encode($this->return_response);
			exit;
		}
	}
	
	function get_file_details($file_number = null) 
	{
		if($file_number == null) {
			$this->return_response['message']='Please provide File Number';
			echo json_encode($this->return_response);
			exit;
		}
		$data = json_encode(array('FileNumber' => $file_number));
		$userData = array(
			'admin_api' => 1
		);
		$result = $this->resware->make_request('POST', 'files/search', $data, $userData);
		if(json_decode($result) && count(json_decode($result)->Files)) {
			
			$condition = array(
				'where' => array(
					'file_number' => $file_number,
				)
			);
			$order = $this->order->get_order($condition);

			if (empty($order)) {
				$orderId =  $this->order->importOrder($file_number);
			}

			$result_data = $result_decoded = array();
			$result_decoded = json_decode($result);
			$orderDetails = $this->order->get_order_details($result_decoded->Files[0]->FileID, 1);
			if (!empty($orderDetails)) {
				$result_data['seller'] = $orderDetails['primary_owner'];
			} else {
				$result_data['seller'] = '';
			}
			$property_data = $result_decoded->Files[0]->Properties[0];
			$buyer_data = $result_decoded->Files[0]->Buyers[0];
			$buyer_name = $buyer_data->Primary;
			$result_data['LoanNumber']=$result_decoded->Files[0]->Loans[0]->LoanNumber;
			$result_data['FileID']=$result_decoded->Files[0]->FileID;
			$result_data['FileNumber']=$result_decoded->Files[0]->FileNumber;
			$result_data['LoanAmount']=$result_decoded->Files[0]->Loans[0]->LoanAmount;
			$result_data['SalesPrice']=$result_decoded->Files[0]->SalesPrice;
			$result_data['City']=$property_data->City;
			$result_data['State']=$property_data->State;
			$result_data['County']=$property_data->County;
			$result_data['borrower']=$result_decoded->Files[0]->Buyers[0]->Primary->First." ".$result_decoded->Files[0]->Buyers[0]->Primary->Last;

			$result_data['FullAddress']=$property_data->StreetNumber;
			$result_data['FullAddress'] .= ! empty($property_data->StreetDirection) ?' '.substr($property_data->StreetDirection, 0, 1) : '';
			$result_data['FullAddress'] .= ' '.$property_data->StreetName;
			$result_data['FullAddress'] .= ' '.$property_data->StreetSuffix;	
			$result_data['FullAddress'] .= ', '.$property_data->City;	
			$result_data['FullAddress'] .= ', '.$property_data->State;	
			$result_data['FullAddress'] .= ' '.$property_data->Zip;	

			$result_data['ProductType']=$result_decoded->Files[0]->TransactionProductType->ProductType;

			//Borrower
			$result_data['Borrower'] = ! empty($buyer_name->First) ? $buyer_name->First : '';
			$result_data['Borrower'] .= ! empty($buyer_name->Middle) ? ' '.$buyer_name->Middle : '';
			$result_data['Borrower'] .= ! empty($buyer_name->Last) ? ' '.$buyer_name->Last : '';
			$result_data['Borrower'] = trim($result_data['Borrower']);

			if(empty($result_data['Borrower'])) {
				$result_data['Borrower'] = ! empty($buyer_name->BusinessName) ? $buyer_name->BusinessName : '';
			}


			//ECD
			$result_data['ECD']='';
			if(!empty($result_decoded->Files[0]->Dates->FileCompletedDate)) {
				$ecd_timestamp = str_replace("-0000)/", "", str_replace("/Date(", "",$result_decoded->Files[0]->Dates->FileCompletedDate));
				$ecd_date = date('m/d/Y', $ecd_timestamp/1000);
				$result_data['ECD'] = $ecd_date;
			}

			$this->return_response['status'] = true;
			$this->return_response['data'] = $result_data;
		}
		echo json_encode($this->return_response);
	}

	function add_calc_details()  {
		$this->load->library('form_validation');
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$_POST = json_decode($stream_clean,TRUE);
		$this->form_validation->set_rules('file_number', 'File Number', 'required|numeric');
		$this->form_validation->set_rules('transaction_type', 'Transaction Type', 'required');
		if($this->form_validation->run() == TRUE) {
			$this->return_response['status'] = true;
		}
		else {
			$this->return_response['message']=$this->form_validation->error_array();
		}
		// var_dump($request_data);
	}

	function send_title_rates_document_to_resware() 
	{
		$data = json_decode(file_get_contents('php://input'), true);
		
		if (empty($data['file_name'])) {
			$this->return_response['message']='Please provide file name';
			echo json_encode($this->return_response);
			exit;
		}

		if (empty($data['file_id'])) {
			$this->return_response['message']='Please provide file id';
			echo json_encode($this->return_response);
			exit;
		}

		$orderDetails = $this->order->get_order_details($data['file_id'], 1);
		//print_r($orderDetails);exit;

		$contents = file_get_contents(env('PCT_CALC_DOC_PATH').$data['file_name']);
		$binaryData   = base64_encode($contents); 
		$document_name = date('YmdHis')."_".$data['file_name'];
		if (!is_dir('uploads/calc_title_rates')) {
			mkdir('./uploads/calc_title_rates', 0777, TRUE);
		}
		file_put_contents(FCPATH.'/uploads/calc_title_rates/'.$document_name, $contents);
		$this->order->uploadDocumentOnAwsS3($document_name, 'calc_title_rates');
		
		$this->home_model->update(array('calc_title_doc_name' => $document_name), array('file_id' => $data['file_id']), 'order_details');
		$endPoint = 'files/'.$data['file_id'].'/documents';
		$documentApiData = array(			
			'DocumentName' => $data['file_name'],
			'DocumentType' => array(
				'DocumentTypeID' => 1044,
			),
			'Description' => 'Calc Title Rates',
			'InternalOnly' => false,
			'DocumentBody' => $binaryData
		);
		$document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);
		$user_data['admin_api'] = 1; 
		$logid = $this->apiLogs->syncLogs(0, 'resware', 'create_document', env('RESWARE_ORDER_API').$endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
		$result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
		$this->apiLogs->syncLogs(0, 'resware', 'create_document', env('RESWARE_ORDER_API').$endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
		$res = json_decode($result);
		if (!empty($res->Document->DocumentID)) {
			$this->return_response['status'] = true;
			$this->return_response['message']='Title Rate document uploaded successfully on Resware side for this file number '.$orderDetails['file_number'];
			$this->return_response['document_name'] = $document_name;
		} else {
			$this->return_response['status'] = false;
			$this->return_response['message']='Something went wrong during upload title rate document on Resware side';
		}
		echo json_encode($this->return_response);
	}
}
