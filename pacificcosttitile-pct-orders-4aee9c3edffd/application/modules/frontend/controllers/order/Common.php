<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class Common extends MX_Controller
{

    private $version = '03';
    private $proposed_js_version = '01';
    private $prelim_orders_js_version = '01';
    private $prelim_order_js_version = '02';
    private $upload_doc_orders_js_version = '01';
    private $upload_document_for_order = '01';
    private $notes_order_js = '01';
    private $policy_orders_js_version = '01';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->version = strtotime(date('Y-m-d'));
        $this->load->library('order/EscrowDashboardTemplate');
        $this->load->library('order/salesDashboardTemplate');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('order/template');
        $this->load->model('order/orderRecording');
        $this->load->library('order/order');
        $this->load->model('order/apiLogs');
        $this->load->model('order/reviewPrelimData');
        $this->load->model('order/titleOfficer');
        $this->load->model('order/home_model');
        $this->load->model('order/fees_model');
        $this->load->library('order/resware');
        $this->load->library('order/common');
    }

    public function prelimFiles()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/prelim_orders.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order", "review_files", $data);
    }

    public function review_file()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $userdata = $this->session->userdata('user');
        $prelimDocument = array();
        $linked_doc = array();
        $this->load->library('order/resware');
        $this->load->model('order/document');
        $fileId = $this->uri->segment(2);
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $orderDetails = $this->order->get_order_details($fileId);
        $prelimDocument = $this->order->get_prelim_document($orderDetails['order_id']);
        if ((isset($userdata['is_sales_rep']) && !empty($userdata['is_sales_rep'])) || (isset($userdata['is_title_officer']) && !empty($userdata['is_title_officer']))) {
            $linked_doc = $this->order->get_order_linked_documents($fileId, 1);
        } else {
            $linked_doc = $this->order->get_order_linked_documents($fileId);
        }
        $uploaded_docs = $this->order->get_order_uploaded_documents($fileId);

        if (isset($orderDetails['file_number']) && !empty($orderDetails['file_number'])) {
            $condition = array('file_number' => $orderDetails['file_number']);
            $summaryData['is_visited'] = 1;

            $update = $this->reviewPrelimData->update($summaryData, $condition);
        }

        $data['error'] = array();
        $data['success'] = array();

        if ($this->session->userdata('errors')) {
            $data['error'] = $this->session->userdata('errors');
            $this->session->unset_userdata('error');
        }

        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }

        $data['linked_doc'] = $linked_doc;
        $data['uploaded_docs'] = $uploaded_docs;
        $data['prelimDocument'] = $prelimDocument;
        $data['orderDetails'] = $orderDetails;
        $data['is_sales_rep'] = isset($userdata['is_sales_rep']) && !empty($userdata['is_sales_rep']) ? 1 : 0;
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/prelim_order.js?v=' . $this->version));
        $this->salesdashboardtemplate->addCss(base_url('assets/css/theme.css?v=' . $this->version));
        $this->salesdashboardtemplate->addCss(base_url('assets/frontend/css/view-review-file.css?v=' . $this->version));
        $this->salesdashboardtemplate->show("order", "view_review_file", $data);
        // $this->template->addJS( base_url('assets/frontend/js/order/prelim_order.js?v='.$this->version));
        // $this->template->show("order", "view_review_file", $data);
    }

    public function summary()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $fileId = $this->input->post('fileId');
        $orderDetails = $this->order->get_order_details($fileId);
        $policy_type = '';
        if (isset($orderDetails['product_type']) && !empty($orderDetails['product_type'])) {
            if (strpos($orderDetails['product_type'], 'Loan:') !== false) {
                $policy_type = 'ALTA 2012 Short Form Residential Loan Policy';
            } else {
                $policy_type = 'ALTA 2006 Extended Loan Policy CA';
            }
        }

        $file_number = isset($orderDetails['file_number']) && !empty($orderDetails['file_number']) ? $orderDetails['file_number'] : '';
        $address = isset($orderDetails['full_address']) && !empty($orderDetails['full_address']) ? $orderDetails['full_address'] : '';
        $property_type = isset($orderDetails['property_type']) && !empty($orderDetails['property_type']) ? $orderDetails['property_type'] : '';

        $condition = array(
            'where' => array(
                'file_number' => $file_number,
            ),
        );

        $prelim_details = $this->reviewPrelimData->get_rows($condition);

        $data = json_decode($prelim_details['resware_json'], true);

        if (isset($data) && !empty($data)) {
            $parcelID = isset($data['ParcelID']) && !empty($data['ParcelID']) ? $data['ParcelID'] : '';
            $vesting = isset($data['Vesting']) && !empty($data['Vesting']) ? $data['Vesting'] : '';
            $generated_date = isset($data['CommitmentEffectiveDate']) && !empty($data['CommitmentEffectiveDate']) ? date('Y-m-d H:i:s', strtotime($data['CommitmentEffectiveDate'])) : '';
            $summaryData = array(
                'file_number' => $file_number,
                'vesting' => $vesting,
                'generated_date' => $generated_date,
                'is_updated' => $prelim_details['is_updated'],
                'lien' => isset($prelim_details['lien']) && !empty($prelim_details['lien']) ? $prelim_details['lien'] : '',
                'tax' => isset($prelim_details['tax']) && !empty($prelim_details['tax']) ? $prelim_details['tax'] : '',
                'easement' => isset($prelim_details['easement']) && !empty($prelim_details['easement']) ? $prelim_details['easement'] : '',
                'requirements' => isset($prelim_details['requirements']) && !empty($prelim_details['requirements']) ? $prelim_details['requirements'] : '',
                'restrictions' => isset($prelim_details['restrictions']) && !empty($prelim_details['restrictions']) ? $prelim_details['restrictions'] : '',
                'resware_json' => $prelim_details['resware_json'],
                'parcel_id' => $parcelID,
                'policy_type' => $policy_type,
            );
            $prelim_details = $summaryData;
        }

        $data['prelim_details'] = array();
        if (isset($prelim_details) && !empty($prelim_details)) {
            $data['prelim_details'] = $prelim_details;
        }
        $data['prelim_details']['address'] = $address;
        $data['prelim_details']['property_type'] = $property_type;
        $results = $this->load->view('order/review_file_summary', $data, true);
        echo json_encode($results, true);
    }

    public function load_doc()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('user');
        $resware_document_id = $this->input->post('resware_document_id');
        $order_id = $this->input->post('order_id');
        $document_id = $this->input->post('document_id');
        $documentDetail = $this->order->get_document_detail($resware_document_id, $order_id, $document_id);
        $is_sync = $this->input->post('is_sync');

        if ($userdata['is_title_officer'] == 1 || $userdata['is_sales_rep'] == 1 || $userdata['is_master'] == 1) {
            $user_data['admin_api'] = 1;
        } else {
            $user_data = array();
        }

        if ($is_sync == 0) {
            $endPoint = 'documents/' . $resware_document_id . '?format=json';
            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $order_id, 0);
            $resultDocument = $this->resware->make_request('GET', $endPoint, '', $user_data);
            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocument, $order_id, $logid);
            $resDocument = json_decode($resultDocument, true);
            if (isset($resDocument['Document']) && !empty($resDocument['Document'])) {
                $documentContent = base64_decode($resDocument['Document']['DocumentBody'], true);
                if (!is_dir('uploads/documents')) {
                    mkdir('./uploads/documents', 0777, true);
                }
                file_put_contents('./uploads/documents/' . $documentDetail['document_name'], $documentContent);
                $this->order->uploadDocumentOnAwsS3($documentDetail['document_name'], 'documents');
                $this->document->update(array('is_sync' => 1), array('api_document_id' => $resware_document_id));
            }
        }

        $data['api_document_id'] = $resware_document_id;
        $data['order_id'] = $order_id;
        $data['document_name'] = $documentDetail['document_name'];
        if ($documentDetail['is_grant_doc'] == 1) {
            if (env('AWS_ENABLE_FLAG') == 1) {
                $data['url'] = env('AWS_PATH') . "grant-deed/" . $documentDetail['document_name'];
            } else {
                $data['url'] = base_url() . 'uploads/grant-deed/' . $documentDetail['document_name'];
            }
        } else if ($documentDetail['is_proposed_insured_doc'] == 1) {
            if (env('AWS_ENABLE_FLAG') == 1) {
                $data['url'] = env('AWS_PATH') . "proposed-insured/" . $documentDetail['document_name'];
            } else {
                $data['url'] = base_url() . 'uploads/proposed-insured/' . $documentDetail['document_name'];
            }
        } else {
            if (env('AWS_ENABLE_FLAG') == 1) {
                $data['url'] = env('AWS_PATH') . "documents/" . $documentDetail['document_name'];
            } else {
                $data['url'] = base_url() . 'uploads/documents/' . $documentDetail['document_name'];
            }
        }
        if ($documentDetail['is_prelim_document'] == 1) {
            $data['prelim_flag'] = 1;
            $data['doc_document_name'] = str_replace("pdf", "docx", $documentDetail['document_name']);
        } else {
            $data['prelim_flag'] = 0;
            $data['doc_document_name'] = '';
        }

        $results = $this->load->view('order/review_file_load_doc', $data, true);
        echo json_encode($results, true);
    }

    public function logout()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $this->session->sess_destroy();
        $this->session->unset_userdata('user');
        redirect(base_url() . 'order');
    }

    public function legal_vesting()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $fileId = $this->input->post('fileId');
        $orderDetails = $this->order->get_order_details($fileId);

        $file_number = isset($orderDetails['file_number']) && !empty($orderDetails['file_number']) ? $orderDetails['file_number'] : '';
        if (env('AWS_ENABLE_FLAG') == 1) {
            $file_path = env('AWS_PATH') . "legal-vesting/" . $file_number . '.pdf';
        } else {
            $file_path = FCPATH . 'uploads/legal-vesting/' . $file_number . '.pdf';
        }

        $file_url = '';

        if (file_exists($file_path)) {
            if (env('AWS_ENABLE_FLAG') == 1) {
                $file_url = env('AWS_PATH') . "legal-vesting/" . $file_number . '.pdf';
            } else {
                $file_url = base_url() . 'uploads/legal-vesting/' . $file_number . '.pdf';
            }
        } else {
            $this->load->model('order/titlePointData');
            $file_id = isset($orderDetails['file_id']) && !empty($orderDetails['file_id']) ? $orderDetails['file_id'] : '';

            $condition = array(
                'where' => array(
                    'file_id' => $file_id,
                ),
            );
            $titlePointDetails = $this->titlePointData->gettitlePointDetails($condition);

            $serviceId = isset($titlePointDetails[0]['cs4_service_id']) && !empty($titlePointDetails[0]['cs4_service_id']) ? $titlePointDetails[0]['cs4_service_id'] : '';
        }

        $data['file_url'] = $file_url;
        $data['file_number'] = $file_number;
        $data['serviceId'] = $serviceId;

        $results = $this->load->view('order/review_file_legal_vesting', $data, true);
        echo json_encode($results, true);
    }

    public function plat_map()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $fileId = $this->input->post('fileId');
        $orderDetails = $this->order->get_order_details($fileId);

        $file_number = isset($orderDetails['file_number']) && !empty($orderDetails['file_number']) ? $orderDetails['file_number'] : '';
        if (env('AWS_ENABLE_FLAG') == 1) {
            $file_path = env('AWS_PATH') . "plat-map/" . $file_number . '.pdf';
        } else {
            $file_path = FCPATH . 'uploads/plat-map/' . $file_number . '.pdf';
        }

        $file_url = '';

        if (file_exists($file_path)) {
            if (env('AWS_ENABLE_FLAG') == 1) {
                $file_url = env('AWS_PATH') . "plat-map/" . $file_number . '.pdf';
            } else {
                $file_url = base_url() . 'uploads/plat-map/' . $file_number . '.pdf';
            }
        } else {
            $address = isset($orderDetails['address']) && !empty($orderDetails['address']) ? $orderDetails['address'] : '';

            $locale = isset($orderDetails['property_city']) && !empty($orderDetails['property_city']) ? $orderDetails['property_city'] : '';

            $propertyState = isset($orderDetails['property_state']) && !empty($orderDetails['property_state']) ? $orderDetails['property_state'] : '';

            $PropertyZip = isset($orderDetails['property_zip']) && !empty($orderDetails['property_zip']) ? $orderDetails['property_zip'] : '';

            if (($locale)) {
                if (!empty($propertyState)) {
                    $locale .= ', ' . $propertyState;
                } else {
                    $locale .= ', CA';
                }
            }

            $data['address'] = $address;
            $data['locale'] = $locale;
            $data['zip'] = $PropertyZip;
        }

        $data['file_url'] = $file_url;
        $data['file_number'] = $file_number;

        $results = $this->load->view('order/review_file_plat_map', $data, true);
        echo json_encode($results, true);
    }

    public function download_document()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $userdata = $this->session->userdata('user');
        $resware_document_id = $this->input->post('resware_document_id');
        $order_id = $this->input->post('order_id');
        $document_name = $this->input->post('document_name');
        $prelimSyncFlag = 0;
        if (env('AWS_ENABLE_FLAG') == 1) {
            $contents = file_get_contents(env('AWS_PATH') . "documents/" . $document_name);
            if (empty($contents)) {
                $prelim_doc_name = str_replace("docx", "pdf", $document_name);
                $pdfContents = file_get_contents($prelim_doc_name);
                if (empty($pdfContents)) {
                    $user_data = array(
                        'admin_api' => 1,
                    );
                    $endPoint = 'documents/' . $resware_document_id . '?format=json';
                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
                    $resultDocument = $this->resware->make_request('GET', $endPoint, '', $user_data);
                    $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocument, 0, $logid);
                    $resDocument = json_decode($resultDocument, true);
                    if (isset($resDocument['Document']) && !empty($resDocument['Document'])) {
                        $pdfContents = base64_decode($resDocument['Document']['DocumentBody'], true);
                    }
                    $prelimSyncFlag = 1;
                }
                if (!is_dir('uploads/documents')) {
                    mkdir(FCPATH . '/uploads/documents', 0777, true);
                }
                file_put_contents(FCPATH . '/uploads/documents/' . $prelim_doc_name, $pdfContents);
                $source_pdf = FCPATH . '/uploads/documents/' . $prelim_doc_name;
                $wordsApi = new \Aspose\Words\WordsApi(getenv('PDF_TO_DOC_CLIENT_ID'), getenv('PDF_TO_DOC_SECRET_KEY'));
                $format = "docx";
                $file = $source_pdf;
                $doc_file_name = str_replace('pdf', 'docx', $document_name);
                $dest_doc = FCPATH . '/uploads/documents/' . $doc_file_name;
                $request = new Aspose\Words\Model\Requests\ConvertDocumentRequest($file, $format, null);
                $result = $wordsApi->ConvertDocument($request);
                copy($result->getPathName(), $dest_doc);
                /** To download docx file */
                // $contents = file_get_contents(FCPATH.'/uploads/documents/'.$doc_file_name);
                /** End */
                $contents = file_get_contents(FCPATH . '/uploads/documents/' . $document_name);
                // $contents = file_get_contents(base_url().'uploads/documents/'.$document_name);
                $this->order->uploadPrelimDocxDocToResware($doc_file_name, $order_id, base64_encode($pdfContents), $this->input->post('fileId'));
                $this->order->uploadDocumentOnAwsS3($doc_file_name, 'documents');
                if ($prelimSyncFlag == 1) {
                    $this->order->uploadDocumentOnAwsS3($prelim_doc_name, 'documents');
                }
            }
        } else {
            $contents = file_get_contents(base_url() . 'uploads/documents/' . $document_name);
        }

        $binaryData = base64_encode($contents);
        echo $binaryData;
    }

    public function upload_document()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $userdata = $this->session->userdata('user');
        $resware_document_id = $this->input->post('resware_document_id');
        $order_id = $this->input->post('order_id');
        $document_name = $this->input->post('document_name');
        $prelimSyncFlag = 0;
        if (env('AWS_ENABLE_FLAG') == 1) {
            $contents = file_get_contents(env('AWS_PATH') . "documents/" . $document_name);
            if (empty($contents)) {
                $prelim_doc_name = str_replace("docx", "pdf", $document_name);
                $pdfContents = file_get_contents($prelim_doc_name);
                if (empty($pdfContents)) {
                    $user_data = array(
                        'admin_api' => 1,
                    );
                    $endPoint = 'documents/' . $resware_document_id . '?format=json';
                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
                    $resultDocument = $this->resware->make_request('GET', $endPoint, '', $user_data);
                    $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocument, 0, $logid);
                    $resDocument = json_decode($resultDocument, true);
                    if (isset($resDocument['Document']) && !empty($resDocument['Document'])) {
                        $pdfContents = base64_decode($resDocument['Document']['DocumentBody'], true);
                    }
                    $prelimSyncFlag = 1;
                }
                if (!is_dir('uploads/documents')) {
                    mkdir(FCPATH . '/uploads/documents', 0777, true);
                }
                file_put_contents(FCPATH . '/uploads/documents/' . $prelim_doc_name, $pdfContents);
                $source_pdf = FCPATH . '/uploads/documents/' . $prelim_doc_name;
                $wordsApi = new \Aspose\Words\WordsApi(getenv('PDF_TO_DOC_CLIENT_ID'), getenv('PDF_TO_DOC_SECRET_KEY'));
                $format = "docx";
                $file = $source_pdf;
                $doc_file_name = str_replace('pdf', 'docx', $document_name);
                $dest_doc = FCPATH . '/uploads/documents/' . $document_name;
                $request = new Aspose\Words\Model\Requests\ConvertDocumentRequest($file, $format, null);
                $result = $wordsApi->ConvertDocument($request);
                copy($result->getPathName(), $dest_doc);
                $contents = file_get_contents(base_url() . 'uploads/documents/' . $document_name);
                $this->order->uploadPrelimDocxDocToResware($doc_file_name, $order_id, base64_encode($pdfContents), $this->input->post('fileId'));
                $this->order->uploadDocumentOnAwsS3($doc_file_name, 'documents');
                if ($prelimSyncFlag == 1) {
                    $this->order->uploadDocumentOnAwsS3($prelim_doc_name, 'documents');
                }
            }
        } else {
            $contents = file_get_contents(base_url() . 'uploads/documents/' . $document_name);
        }
        $res = array('status' => 'success', 'msg' => "Prelim document uploaded successfully on Resware side.");
        echo json_encode($res);exit;
    }

    public function generate_plat_map()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $response = array();
        $imagedata = isset($_POST['imagedata']) && !empty($_POST['imagedata']) ? $_POST['imagedata'] : '';
        $file_number = isset($_POST['file_number']) && !empty($_POST['file_number']) ? $_POST['file_number'] : '';
        if ($imagedata) {
            if (!is_dir('uploads/plat-map')) {
                mkdir('./uploads/plat-map', 0777, true);
            }
            $path = './uploads/plat-map/' . $file_number . '.png';

            file_put_contents($path, base64_decode($imagedata, true));
            if (env('AWS_ENABLE_FLAG') == 1) {
                $plat_map_url = env('AWS_PATH') . "plat-map/" . $file_number . '.png';
            } else {
                $plat_map_url = base_url() . 'uploads/plat-map/' . $file_number . '.png';
            }

            $this->order->uploadDocumentOnAwsS3($file_number . '.png', 'plat-map');
            $response = array('status' => 'success', 'plat_map_url' => $plat_map_url);
        } else {
            $response = array('status' => 'error');
        }
        echo json_encode($response);exit;
    }

    public function getSearchResults()
    {
        $userdata = $this->session->userdata('user');
        ini_set('max_execution_time', 300);
        $request = $_GET['requrl'];
        $api_key = env('BLACK_KNIGHT_KEY');
        $request .= '&key=' . $api_key;
        $query_string = parse_url($request, PHP_URL_QUERY);
        parse_str($query_string, $requestParams);
        $getsortedresults = isset($_GET['getsortedresults']) ? $_GET['getsortedresults'] : 'false';
        $opts = array(
            'http' => array(
                'header' => "User-Agent:MyAgent/1.0\r\n",
            ),
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $this->load->model('order/apiLogs');
        $logid = $this->apiLogs->syncLogs($userdata['id'], 'black knight', 'address_search', $request, $requestParams, array(), 0, 0);
        $file = file_get_contents($request, false, $context);
        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);

        $this->apiLogs->syncLogs($userdata['id'], 'black knight', 'address_search', $request, array(), $result, 0, $logid);
        echo trim($file);
    }

    public function updatePrelimAction()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $userdata = $this->session->userdata('user');
        $this->load->model('order/note');
        $this->load->model('order/document');
        $fileId = $this->uri->segment(2);
        $endPoint = 'files/' . $fileId . '/actions';
        $user_data['admin_api'] = 1;
        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
        $res = $this->resware->make_request('GET', $endPoint, array(), $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'get_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, array(), $res, 0, $logid);
        $result = json_decode($res, true);
        $error = '';
        $success = '';

        $config['upload_path'] = './uploads/prelim-upload-doc/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 12000;
        $this->load->library('upload', $config);
        if (!is_dir('/uploads/prelim-upload-doc')) {
            mkdir('./uploads/prelim-upload-doc', 0777, true);
        }

        if (!empty($_FILES['file_upload']['name'])) {
            if (!$this->upload->do_upload('file_upload')) {
                $errorMsg = $this->upload->display_errors();
                $this->session->set_userdata('error', $errorMsg);
                $file_upload_error_msg = 1;
            } else {
                if (isset($result['Actions']) && !empty($result['Actions'])) {
                    $array_keymap = $this->order->array_recursive_search_key_map(126, $result['Actions']);
                    if (!empty($array_keymap)) {
                        $actionData = array(
                            'StartTask' => array(
                                'CoordinatorTypeID' => 19,
                                'DueDate' => '/Date(' . (strtotime(date('Y-m-d H:i:s')) * 1000) . '-0000)/',
                            ),
                        );
                        $endPoint = 'files/' . $fileId . '/actions/' . $result['Actions'][$array_keymap[0]]['FileActionID'];
                        $user_data['admin_api'] = 1;
                        $actionData = json_encode($actionData);
                        $logid = $this->apiLogs->syncLogs(0, 'resware', 'update_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, array(), $fileId, 0);
                        $res = $this->resware->make_request('PUT', $endPoint, $actionData, $user_data);
                        $this->apiLogs->syncLogs(0, 'resware', 'update_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, $res, $fileId, $logid);
                        $result = json_decode($res, true);

                        if (!empty($result['FileActionID'])) {
                            $success = 'Prelim action updated successfully.';
                        } else {
                            $error = 'Something went wrong during update action prelim';
                        }

                    } else {
                        $actionData = array(
                            'ActionType' => array(
                                'ActionTypeID' => 126,
                            ),
                            'Group' => array(
                                'ActionGroupID' => 6,
                            ),
                            'StartTask' => array(
                                'CoordinatorTypeID' => 19,
                                'DueDate' => '/Date(' . (strtotime(date('Y-m-d H:i:s')) * 1000) . '-0000)/',
                            ),
                        );
                        $endPoint = 'files/' . $fileId . '/actions/';
                        $user_data['admin_api'] = 1;
                        $actionData = json_encode($actionData);
                        $logid = $this->apiLogs->syncLogs(0, 'resware', 'add_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, array(), $fileId, 0);
                        $res = $this->resware->make_request('POST', $endPoint, $actionData, $user_data);
                        $this->apiLogs->syncLogs(0, 'resware', 'add_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, $res, $fileId, $logid);
                        $result = json_decode($res, true);

                        if (!empty($result['FileActionID'])) {
                            $success = 'Prelim action updated successfully. <br/>';
                        } else {
                            $errors = 'Something went wrong during update action prelim';
                        }
                    }
                }
                $subject = isset($_POST['note_subject']) && !empty($_POST['note_subject']) ? $_POST['note_subject'] : '';
                $body = isset($_POST['note']) && !empty($_POST['note']) ? $_POST['note'] : '';
                $orderDetails = $this->order->get_order_details($fileId);
                $orderId = isset($orderDetails['order_id']) && !empty($orderDetails['order_id']) ? $orderDetails['order_id'] : '';

                $request = array();
                $endPoint = 'files/' . $fileId . '/notes';
                $request['Subject'] = $subject;
                $request['Body'] = $body;
                $request['FileID'] = $fileId;
                $request['Expedite'] = true;
                $notes_data = json_encode($request);

                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, array(), $orderId, 0);
                $result = $this->resware->make_request('POST', $endPoint, $notes_data, $user_data);
                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, $result, $orderId, $logid);

                if (isset($result) && !empty($result)) {
                    $response = json_decode($result, true);

                    if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                        $message = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';
                        $errors[] = $message;
                    } else {
                        $noteId = isset($response['Note']['NoteID']) && !empty($response['Note']['NoteID']) ? $response['Note']['NoteID'] : '';
                        $notesData = array(
                            'resware_note_id' => $noteId,
                            'subject' => $subject,
                            'note' => $body,
                            'user_id' => $userdata['id'],
                            'order_id' => $orderId,
                            'task_id' => isset($_POST['task_id']) ? $_POST['task_id'] : 0,
                        );
                        $id = $this->note->insert($notesData);
                        if ($noteId && $id) {
                            $success .= 'Note created successfully.';
                        } else {
                            $errors .= 'Something went wrong. Please try again.';
                        }
                    }
                }

                $data = $this->upload->data();
                $contents = file_get_contents($data['full_path']);
                $binaryData = base64_encode($contents);
                $document_name = date('YmdHis') . "_" . $data['file_name'];
                rename(FCPATH . "/uploads/prelim-upload-doc/" . $data['file_name'], FCPATH . "/uploads/prelim-upload-doc/" . $document_name);
                $documentData = array(
                    'document_name' => $document_name,
                    'original_document_name' => $data['file_name'],
                    'document_type_id' => 1032,
                    'document_size' => ($data['file_size'] * 1000),
                    'user_id' => $userdata['id'],
                    'order_id' => $orderId,
                    'task_id' => 0,
                    'description' => 'Prelim Upload Document',
                    'is_sync' => 1,
                    'is_prelim_document' => 0,
                );

                $this->order->uploadDocumentOnAwsS3($document_name, 'prelim-upload-doc');
                $documentId = $this->document->insert($documentData);

                $endPoint = 'files/' . $fileId . '/documents';
                $documentApiData = array(
                    'DocumentName' => $data['file_name'],
                    'DocumentType' => array(
                        'DocumentTypeID' => 1032,
                    ),
                    'Description' => 'Prelim Upload Document',
                    'InternalOnly' => false,
                    'DocumentBody' => $binaryData,
                );
                $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);
                if ($userdata['is_title_officer'] == 1 || $userdata['is_master'] == 1) {
                    $user_data['admin_api'] = 1;
                }

                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderId, 0);
                $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderId, $logid);
                $res = json_decode($result);
                if (!empty($res->Document->DocumentID)) {
                    $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));
                    $success .= "Document uploaded successfully";
                } else {
                    $errors .= " Something went wrong.Please try again";
                }

                $orderDetails = $this->order->get_order_details($fileId);

                /* Start add resware api logs */
                $reswareLogData = array(
                    'request_type' => 'upload_prelim_document_to_resware',
                    'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                    'request' => $document_api_data,
                    'response' => $result,
                    'status' => 'success',
                    'created_at' => date("Y-m-d H:i:s"),
                );
                $this->db->insert('pct_resware_log', $reswareLogData);
                /* End add resware api logs */

                $data = array(
                    "error" => $errors,
                    "success" => $success,
                );
                $this->session->set_userdata($data);
            }
        }
        redirect(base_url() . 'review-file/' . $fileId);
    }

    public function get_partners()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $fileId = $this->input->post('fileId');
        if ($fileId) {
            $userdata = $this->session->userdata('user');
            $orderDetails = $this->order->get_order_details($fileId);
            $endPoint = 'files/' . $fileId . '/partners';
            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $orderDetails['order_id'], 0);

            if ($userdata['is_title_officer'] == 1 || $userdata['is_sales_rep'] == 1 || $userdata['is_master'] == 1) {
                $user_data['admin_api'] = 1;
            } else {
                $user_data = array();
            }

            $result = $this->resware->make_request('GET', $endPoint, '', $user_data);
            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), $result, $orderDetails['order_id'], $logid);
            $partners = json_decode($result, true);
            $partners = isset($partners['Partners']) && !empty($partners['Partners']) ? $partners['Partners'] : '';
            $res = array('status' => 'success', 'partners' => $partners);
        } else {
            $res = array('status' => 'error', 'msg' => "Please select file.");
        }
        echo json_encode($res);
    }

    public function uploadDocOrders()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/upload_doc_orders.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order/common", "upload_doc_orders", $data);
    }

    public function getOrdersUploadDoc()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
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
            $order_lists = $this->order->get_orders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_orders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $order['file_number'];
                $nestedData[] = $order['full_address'];
                $nestedData[] = "<a href='" . base_url() . "upload-documents/" . $order['file_id'] . "'>
									<button type='submit' class='btn btn-info btn-icon-split'>
										<span class='icon text-white-50'>
											<i class='fas fa-file'></i>
										</span>
										<span class='text'>Attach Files</span>
									</button>
								</a>";
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function policyOrders()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/policy.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order/common", "policy_orders", $data);
    }

    public function getOrdersPolicy()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
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
            $order_lists = $this->order->get_orders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_orders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $order['file_number'];
                $nestedData[] = $order['full_address'];
                $nestedData[] = "<a href='" . base_url() . "policy-order/" . $order['file_id'] . "'>
									<button type='submit' class='btn btn-info btn-icon-split'>
										<span class='icon text-white-50'>
											<i class='fas fa-file'></i>
										</span>
										<span class='text'>Get Policy</span>
									</button>
								</a>";
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function policy()
    {
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $fileId = $this->uri->segment(2);
        $data['title'] = 'Get Policy | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $orderDetails = $this->order->get_order_details($fileId, 1);
        $data['file_number'] = $orderDetails['file_number'];
        $data['full_address'] = $orderDetails['full_address'];
        $data['file_id'] = $orderDetails['file_id'];
        $data['order_id'] = $orderDetails['order_id'];
        $data['created'] = !empty($orderDetails['opened_date']) ? date("m/d/Y", strtotime($orderDetails['opened_date'])) : '';
        $user_data['admin_api'] = 1;
        $endPoint = 'files/' . $fileId . '/documents';

        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_resware_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $orderDetails['order_id'], 0);
        $result = $this->resware->make_request('GET', $endPoint, '', $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'get_resware_document', env('RESWARE_ORDER_API') . $endPoint, array(), $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result, true);

        $policyDocuments = array();
        $i = 0;
        foreach ($res['Documents'] as $document) {
            if ($document['DocumentType']['DocumentTypeID'] == 103) {
                $policyDocuments[$i]['no'] = $i + 1;
                $policyDocuments[$i]['api_document_id'] = $document['DocumentID'];
                $policyDocuments[$i]['document_name'] = $document['DocumentName'];
                $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $document['CreateDate']))) / 1000);
                $created_date = date('m/d/Y', $time);
                $policyDocuments[$i]['created_at'] = $created_date;
                $i++;
            }
        }

        $data['policyDocuments'] = $policyDocuments;
        $this->salesdashboardtemplate->show("order/common", "policy_package", $data);
    }

    public function upload_documents()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $this->load->model('order/document');
        $data['errors'] = array();
        $data['success'] = array();
        $userdata = $this->session->userdata('user');
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $fileId = $this->uri->segment(2);
        $data['orderDetails'] = $this->order->get_order_details($fileId);
        $data['documentTypes'] = $this->order->get_document_types();

        if ($userdata['is_title_officer'] == 1) {
            $documents = $this->order->get_user_documents($data['orderDetails']['order_id']);
            $user_data = array();
            $user_data = array(
                'admin_api' => 1,
            );
            $user_data['from_mail'] = 1;
            $endPoint = 'files/' . $fileId . '/documents';
            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_documents', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $data['orderDetails']['order_id'], 0);
            $resultDocuments = $this->resware->make_request('GET', $endPoint, '', $user_data);
            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_documents', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocuments, $data['orderDetails']['order_id'], $logid);
            $resDocuments = json_decode($resultDocuments, true);
            $apiDocumentIds = array_column($documents, 'api_document_id');

            if (!empty($resDocuments['Documents'])) {
                foreach ($resDocuments['Documents'] as $resDocument) {
                    $ext = end(explode('.', $resDocument['DocumentName']));
                    $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $resDocument['CreateDate']))) / 1000);
                    $created_date = date('Y-m-d H:i:s', $time);
                    $document_name = date('YmdHis') . "_" . $resDocument['DocumentName'];
                    if (strtolower($ext) == 'doc' || strtolower($ext) == 'docx') {
                        $document_name = str_replace($ext, 'pdf', $document_name);
                    }
                    if (in_array($resDocument['DocumentID'], $apiDocumentIds)) {
                        $documentData = array(
                            'original_document_name' => $resDocument['DocumentName'],
                            'document_type_id' => $resDocument['DocumentType']['DocumentTypeID'],
                            'document_size' => $resDocument['Size'],
                            'order_id' => $data['orderDetails']['order_id'],
                            'description' => $resDocument['DocumentName'],
                            'created' => $created_date,
                        );
                        $condition = array(
                            'api_document_id' => $resDocument['DocumentID'],
                        );
                        $this->document->update($documentData, $condition);
                    } else {
                        $documentData = array(
                            'document_name' => $document_name,
                            'original_document_name' => $resDocument['DocumentName'],
                            'document_type_id' => $resDocument['DocumentType']['DocumentTypeID'],
                            'api_document_id' => $resDocument['DocumentID'],
                            'document_size' => $resDocument['Size'],
                            'user_id' => 0,
                            'order_id' => $data['orderDetails']['order_id'],
                            'description' => $resDocument['DocumentName'],
                            'created' => $created_date,
                            'is_sync' => 0,
                            'is_prelim_document' => 0,
                        );
                        $this->document->insert($documentData);
                    }
                }
            }
        } else {
            if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                $prod_type = $data['orderDetails']['prod_type'];
                $this->load->model('admin/escrow/tasks_model');
                $data['tasks'] = $this->tasks_model->get_many_by("(status = 1 and parent_task_id = 0 and (prod_type = 'both' or prod_type = '$prod_type') )");
            }
        }
        // $this->template->addJS( base_url('assets/frontend/js/order/upload_document_for_order.js?v='.$this->version));
        // $this->template->show("order/common", "upload_documents", $data);
        $this->escrowdashboardtemplate->addJS(base_url('assets/frontend/js/order/upload_document_for_order.js?v=' . $this->version));
        $this->escrowdashboardtemplate->show("order/common", "upload_documents", $data);
    }

    public function getOrderDocuments()
    {
        $userdata = $this->session->userdata('user');
        $params = array();
        $data = array();
        $params['order_id'] = $this->input->post('order_id');
        $params['file_id'] = $this->input->post('file_id');
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $documents_lists = $this->order->getOrderdocuments($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $documents_lists = $this->order->getOrderdocuments($params);
        }

        if (isset($documents_lists['data']) && !empty($documents_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($documents_lists['data'] as $document) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $document['original_document_name'];
                if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                    if ($document['is_uploaded_by_borrower'] == 1) {
                        $nestedData[] = 'Yes';
                    } else {
                        $nestedData[] = 'No';
                    }
                }
                $nestedData[] = date('m/d/Y', strtotime($document['created']));
                $apiDocumentId = $document['api_document_id'];
                $documentName = $document['document_name'];
                $documentUrl = env('AWS_PATH') . "documents/" . $documentName;
                $nestedData[] = '<div style="display:inline-flex;"><a href="javascript:void(0)" onclick="downloadDocumentFromAws(' . "'" . $documentUrl . "'" . ', ' . "'" . $apiDocumentId . "'" . ');" class="btn btn-success btn-icon-split btn-sm"><span class="icon text-white-50"><i class="fas fa-download"></i></span><span class="text">Download</span></a></div>';
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($documents_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($documents_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function files_upload()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $this->load->model('order/document');
        $this->load->model('order/apiLogs');
        $this->load->library('order/resware');
        $errors = array();
        $success = array();
        $config['upload_path'] = './uploads/documents/';
        $config['allowed_types'] = 'doc|docx|gif|msg|pdf|tif|tiff|xls|xlsx|xml';
        $config['max_size'] = 12000;
        $userdata = $this->session->userdata('user');
        $this->load->library('upload', $config);
        $fileId = $this->input->post('file_id');
        $orderId = $this->input->post('order_id');

        if (!is_dir('uploads/documents')) {
            mkdir('./uploads/documents', 0777, true);
        }

        for ($i = 1; $i <= 4; $i++) {
            if (!empty($_FILES['document_' . $i]['name'])) {
                if (!$this->upload->do_upload('document_' . $i)) {
                    $errors[$i] = "Document #" . $i . ": " . $this->upload->display_errors();
                } else {
                    $data = $this->upload->data();
                    $contents = file_get_contents($data['full_path']);
                    $binaryData = base64_encode($contents);
                    $document_name = date('YmdHis') . "_" . $data['file_name'];
                    rename(FCPATH . "/uploads/documents/" . $data['file_name'], FCPATH . "/uploads/documents/" . $document_name);

                    $documentData = array(
                        'document_name' => $document_name,
                        'original_document_name' => $data['file_name'],
                        'document_type_id' => $this->input->post('document_type_' . $i),
                        'document_size' => ($data['file_size'] * 1000),
                        'user_id' => $userdata['id'],
                        'order_id' => $orderId,
                        'task_id' => $this->input->post('task_id_' . $i) ? $this->input->post('task_id_' . $i) : 0,
                        'description' => $this->input->post('description_' . $i),
                        'is_sync' => 1,
                        'is_prelim_document' => 0,
                    );

                    $this->order->uploadDocumentOnAwsS3($document_name, 'documents');
                    $documentId = $this->document->insert($documentData);
                    if (($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) && $documentId) {
                        $success[$i] = "Document #" . $i . ": uploaded successfully";
                    } else {
                        $endPoint = 'files/' . $fileId . '/documents';
                        $documentApiData = array(
                            'DocumentName' => $data['file_name'],
                            'DocumentType' => array(
                                'DocumentTypeID' => $this->input->post('document_type_' . $i),
                            ),
                            'Description' => $this->input->post('description_' . $i),
                            'InternalOnly' => false,
                            'DocumentBody' => $binaryData,
                        );
                        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);
                        if ($userdata['is_title_officer'] == 1 || $userdata['is_master'] == 1) {
                            $user_data['admin_api'] = 1;
                        }

                        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderId, 0);
                        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
                        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderId, $logid);
                        $res = json_decode($result);
                        if (!empty($res->Document->DocumentID)) {
                            $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));
                            $success[$i] = "Document #" . $i . ": uploaded successfully";
                        } else {
                            $errors[$i] = "Document #" . $i . ": Something went wrong.Please try again";
                        }
                    }
                }
            }
        }
        $orderDetails = $this->order->get_order_details($fileId);
        if (!empty($userdata) && $userdata['id'] == $orderDetails['title_officer']) {
            $message = 'Documents uploaded for order number #' . $orderDetails['file_number'];
            $notificationData = array(
                'sent_user_id' => $orderDetails['customer_id'],
                'message' => $message,
                'is_admin' => 0,
                'type' => 'created',
            );
            $this->home_model->insert($notificationData, 'pct_order_notifications');
            $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
        } else if (!empty($userdata) && $userdata['id'] == $orderDetails['customer_id']) {
            $message = 'Documents uploaded for order number #' . $orderDetails['file_number'];
            $notificationData = array(
                'sent_user_id' => $orderDetails['title_officer'],
                'message' => $message,
                'is_admin' => 0,
                'type' => 'created',
            );
            $this->home_model->insert($notificationData, 'pct_order_notifications');
            $this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
        }
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'upload-documents/' . $fileId);
    }

    public function cpl()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/cpl.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order", "cpl", $data);
        // $this->template->show("order", "cpl", $data);
    }

    public function get_orders_cpl()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
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
            $order_lists = $this->order->get_orders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_orders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $order['file_number'];
                $nestedData[] = $order['full_address'];
                // $nestedData[] = !empty($order['document_created_date']) ? date("m/d/Y", strtotime($order['document_created_date'])) : '';
                $nestedData[] = !empty($order['document_created_date']) ? convertTimezone($order['document_created_date'], 'm/d/Y') : '';
                if (!empty($order['cpl_document_name'])) {
                    $file_id = $order['file_id'];
                    $documentName = $order['cpl_document_name'];
                    if (env('AWS_ENABLE_FLAG') == 1) {
                        $documentUrl = env('AWS_PATH') . "documents/" . $documentName;
                        $nestedData[] = "<div style='display:flex;justify-content: space-around;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"cpl"' . ");' title='Download' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-download'></i></span><span class='text'>Download</span></a>
						<a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);' class='btn btn-primary btn-icon-split'><span class='icon text-white-50'><i class='fas fa-edit'></i></span><span class='text'>Edit</span></a></div>";
                    } else {
                        $documentUrl = FCPATH . 'uploads/documents/' . $documentName;
                        $nestedData[] = "<div style='display:flex;justify-content: space-around;'><a href='$documentUrl' download title='Download' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-download'></i></span><span class='text'>Download</span></a>
						<a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);' class='btn btn-primary btn-icon-split'><span class='icon text-white-50'><i class='fas fa-edit'></i></span><span class='text'>Edit</span></a></div>";
                    }

                } else if (!empty($order['westcor_file_id'])) {
                    $file_id = $order['file_id'];
                    $westcorFileId = $order['westcor_file_id'];
                    $westcorOrderId = $order['westcor_order_id'];
                    $nestedData[] = "<div style='display:flex;justify-content: space-around;'><a onclick='download_for_pdf($westcorFileId, $westcorOrderId);' href='javascript:void(0);' title='Download' title='Download' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-download'></i></span><span class='text'>Download</span></a><a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);' class='btn btn-primary btn-icon-split'><span class='icon text-white-50'><i class='fas fa-edit'></i></span><span class='text'>Edit</span></a></div>";
                } else {
                    $file_id = $order['file_id'];
                    $nestedData[] = "<div style='display:flex;justify-content: space-around;'><form onclick='return lender_pop_up(0, $file_id);' action='" . base_url() . "create-cpl/" . $order['file_id'] . "' method='POST'><a href='javascript:void(0);'  title='Generate' type='submit' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-seedling'></i></span><span class='text'>Generate</span></a></form><a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);' class='btn btn-primary btn-icon-split'><span class='icon text-white-50'><i class='fas fa-edit'></i></span><span class='text'>Edit</span></a></div>";
                }
                $data[] = $nestedData;
                $i++;
            }
        }

        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function create_cpl()
    {
        $userdata = $this->session->userdata('user');
        $this->load->library('order/westcor');
        $fileId = $this->uri->segment(2);
        $orderDetails = $this->order->get_order_details($fileId);
        $data = $this->westcor->generateCplDocument($fileId, $orderDetails);
        $this->session->set_userdata($data);
        $this->session->unset_userdata('lender_details');
        if (!empty($userdata['id'])) {
            redirect(base_url() . 'cpl-dashboard');
        } else {
            redirect(base_url() . 'generate-cpl/' . $orderDetails['random_number']);
        }
    }

    public function addLenderOnOrder()
    {
        $this->load->model('order/home_model');
        $this->load->library('order/resware');
        $userdata = $this->session->userdata('user');
        if (empty($userdata)) {
            $userdata['id'] = 0;
        }
        $file_id = $this->input->post('file_id');
        $LenderId = $this->input->post('LenderId');
        $loan_number = $this->input->post('loan_number');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $vesting = $this->input->post('vesting');
        $new_existing_lender = $this->input->post('new_existing_lender');
        $borrowers_vesting = $this->input->post('borrowers_vesting');
        $name = explode(" ", $this->input->post('LenderName'));
        $editFlag = $this->input->post('editFlag');
        $orderDetails = $this->order->get_order_details($file_id);
        $cplApi = $this->input->post('cpl_api');
        if ($cplApi == 'doma') {
            $errors[] = "Please contact your title team in order to get your CPL processed.";
            $data = array(
                "errors" => $errors,
            );
            $this->session->set_flashdata($data);
            redirect(base_url() . 'cpl-dashboard');
        }
        $lender_details = array(
            'first_name' => $name[0],
            'last_name' => !empty($name[1]) ? $name[1] : '',
            'lender_fullname' => $this->input->post('LenderName'),
            'state' => !empty($this->input->post('LenderState')) ? $this->input->post('LenderState') : "",
            'company_name' => !empty($this->input->post('LenderCompany')) ? $this->input->post('LenderCompany') : "",
            'street_address' => !empty($this->input->post('LenderAddress')) ? $this->input->post('LenderAddress') : "",
            'city' => !empty($this->input->post('LenderCity')) ? $this->input->post('LenderCity') : "",
            'zip_code' => !empty($this->input->post('LenderZipcode')) ? $this->input->post('LenderZipcode') : "",
            'assignment_clause' => !empty($this->input->post('assignment_clause')) ? $this->input->post('assignment_clause') : "",
        );

        $this->session->set_userdata('lender_details', $lender_details);
        unset($lender_details['lender_fullname']);
        $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
        if ($new_existing_lender == 'add_lender') {
            $lender_details['partner_id'] = $this->input->post('partner_id');
            $lender_details['is_added_lender_by_cpl_proposed'] = 1;
            $lender_details['is_escrow'] = 0;
            $lender_details['status'] = 0;
            $LenderId = $this->home_model->insert($lender_details, 'customer_basic_details');
        } else {
            $condition = array(
                'id' => $LenderId,
            );
            $this->home_model->update($lender_details, $condition, 'customer_basic_details');
        }

        $partners = array();
        $lenderUserDetails = $this->home_model->get_user(array('id' => $LenderId));
        $secondaryEmp[] = array('UserID' => $lenderUserDetails['resware_user_id']);
        $secondaryPartners = array(
            'SecondaryEmployees' => $secondaryEmp,
            'PartnerTypeID' => 3,
            'PartnerID' => $lenderUserDetails['partner_id'],
            'PartnerType' => array(
                'PartnerTypeID' => 3,
            ),
        );
        $endPoint = 'files/' . $file_id . '/partners';
        $partnerUserData = array(
            'admin_api' => 1,
        );

        // if(!empty($lenderUserDetails['resware_user_id'])) {
        //     if ($orderUser['is_escrow'] == 1) {
        //         if(empty($orderDetails['escrow_lender_id'])) {
        //             $partners[] = $secondaryPartners;
        //         } else if (!empty($orderDetails['escrow_lender_id']) && $orderDetails['escrow_lender_id'] != $LenderId) {
        //             $partners[] = $secondaryPartners;
        //             $removeLenderUserDetails = $this->home_model->get_user(array('id' => $orderDetails['escrow_lender_id']));
        //             $removeSecondaryEmp[] = array('UserID'=> $removeLenderUserDetails['resware_user_id']);
        //             $removeSecondaryPartners = array(
        //                 'SecondaryEmployees'=> $removeSecondaryEmp,
        //                 'PartnerTypeID' => 3,
        //                 'PartnerID' => $removeLenderUserDetails['partner_id'],
        //                 'PartnerType' => array(
        //                     'PartnerTypeID' => 3
        //                 )
        //             );
        //             $removePartners[] = $removeSecondaryPartners;
        //             $removePartnerData = json_encode(array('Partners' => $removePartners));
        //             $removeLogid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner', env('RESWARE_ORDER_API').$endPoint, $removePartnerData, array(), 0, 0);
        //             $resultRemovePartner = $this->resware->make_request('DELETE', $endPoint, $removePartnerData, $partnerUserData);
        //             $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner', env('RESWARE_ORDER_API').$endPoint, $removePartnerData, $resultRemovePartner, 0, $removeLogid);
        //         }
        //     } else {
        //         if(empty($orderDetails['cpl_lender_id'])) {
        //             $partners[] = $secondaryPartners;
        //         } else if (!empty($orderDetails['cpl_lender_id']) && $orderDetails['cpl_lender_id'] != $LenderId) {
        //             $partners[] = $secondaryPartners;
        //             $removeLenderUserDetails = $this->home_model->get_user(array('id' => $orderDetails['cpl_lender_id']));
        //             $removeSecondaryEmp[] = array('UserID'=> $removeLenderUserDetails['resware_user_id']);
        //             $removeSecondaryPartners = array(
        //                 'SecondaryEmployees'=> $removeSecondaryEmp,
        //                 'PartnerTypeID' => 3,
        //                 'PartnerID' => $removeLenderUserDetails['partner_id'],
        //                 'PartnerType' => array(
        //                     'PartnerTypeID' => 3
        //                 )
        //             );
        //             $removePartners[] = $removeSecondaryPartners;
        //             $removePartnerData = json_encode(array('Partners' => $removePartners));
        //             $removeLogid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner', env('RESWARE_ORDER_API').$endPoint, $removePartnerData, array(), 0, 0);
        //             $resultRemovePartner = $this->resware->make_request('DELETE', $endPoint, $removePartnerData, $partnerUserData);
        //             $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner', env('RESWARE_ORDER_API').$endPoint, $removePartnerData, $resultRemovePartner, 0, $removeLogid);
        //         }
        //     }

        //     if(!empty($partners)) {
        //         $partnerData = json_encode(array('Partners' => $partners));
        //         $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'add_partner', env('RESWARE_ORDER_API').$endPoint, $partnerData, array(), 0, 0);
        //         $resultPartner = $this->resware->make_request('POST', $endPoint, $partnerData, $partnerUserData);
        //         $this->apiLogs->syncLogs($userdata['id'], 'resware', 'add_partner', env('RESWARE_ORDER_API').$endPoint, $partnerData, $resultPartner, 0, $logid);
        //     }
        // }

        $propertyDetails = array(
            'cpl_lender_id' => $LenderId,
            'borrowers_vesting' => trim($borrowers_vesting),
            'cpl_proposed_property_address' => $this->input->post('property_address'),
            'cpl_proposed_property_city' => $this->input->post('property_city'),
            'cpl_proposed_property_state' => $this->input->post('property_state'),
            'cpl_proposed_property_zip' => $this->input->post('property_zipcode'),
        );
        $this->home_model->update(array('loan_number' => $loan_number), array('id' => $orderDetails['transaction_id']), 'transaction_details');
        $this->home_model->update(array('fnf_agent_id' => $this->input->post('branch')), array('id' => $orderDetails['order_id']), 'order_details');
        $this->home_model->update($propertyDetails, array('id' => $orderDetails['property_id']), 'property_details');

        $this->home_model->update(array('is_regenerate_cpl' => $editFlag), array('id' => $orderDetails['order_id']), 'order_details');
        if ($cplApi == 'fnf') {
            redirect(base_url() . "create-cpl-for-fnf/" . $file_id);
        } else if ($cplApi == 'westcor') {
            redirect(base_url() . "create-cpl/" . $file_id);
        } else if ($cplApi == 'doma') {
            redirect(base_url() . "create-cpl-for-doma/" . $file_id);
        } else {
            redirect(base_url() . "create-cpl-for-natic/" . $file_id);
        }
    }

    public function getOrderDetailsCpl()
    {
        $this->load->library('order/fnf');
        $this->load->library('order/natic');
        // $this->load->library('order/doma');
        $this->load->model('order/home_model');
        $this->load->library('order/resware');
        $fileId = $this->input->post('fileId');
        $requestFrom = $this->input->post('requestFrom');
        $userdata = $this->session->userdata('user');
        if (empty($userdata)) {
            $userdata['id'] = 0;
        }
        $orderDetails = $this->order->get_order_details($fileId);
        $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));

        if ($orderUser['is_escrow'] == 1) {
            if (!empty($orderDetails['cpl_lender_id'])) {
                $lenderDetails = $this->home_model->get_user(array('id' => $orderDetails['cpl_lender_id']));
                $orderDetails['lender_first_name'] = $lenderDetails['first_name'] ? $lenderDetails['first_name'] : '';
                $orderDetails['lender_last_name'] = $lenderDetails['last_name'] ? $lenderDetails['last_name'] : '';
                $orderDetails['lender_email'] = $lenderDetails['email_address'] ? $lenderDetails['email_address'] : '';
                $orderDetails['lender_state'] = $lenderDetails['state'] ? $lenderDetails['state'] : '';
                $orderDetails['lender_company_name'] = $lenderDetails['company_name'] ? $lenderDetails['company_name'] : '';
                $orderDetails['lender_address'] = $lenderDetails['street_address'] ? $lenderDetails['street_address'] : '';
                $orderDetails['lender_city'] = $lenderDetails['city'] ? $lenderDetails['city'] : '';
                $orderDetails['lender_zipcode'] = $lenderDetails['zip_code'] ? $lenderDetails['zip_code'] : '';
                $orderDetails['lender_assignment_clause'] = $lenderDetails['assignment_clause'] ? $lenderDetails['assignment_clause'] : '';
                $orderDetails['lender_id'] = $lenderDetails['id'] ? $lenderDetails['id'] : '';
            } else {
                $orderDetails['lender_first_name'] = $orderDetails['lender_first_name'] ? $orderDetails['lender_first_name'] : '';
                $orderDetails['lender_last_name'] = $orderDetails['lender_last_name'] ? $orderDetails['lender_last_name'] : '';
                $orderDetails['lender_email'] = $orderDetails['lender_email'] ? $orderDetails['lender_email'] : '';
                $orderDetails['lender_state'] = $orderDetails['lender_state'] ? $orderDetails['lender_state'] : '';
                $orderDetails['lender_company_name'] = $orderDetails['lender_company_name'] ? $orderDetails['lender_company_name'] : '';
                $orderDetails['lender_address'] = $orderDetails['lender_address'] ? $orderDetails['lender_address'] : '';
                $orderDetails['lender_city'] = $orderDetails['lender_city'] ? $orderDetails['lender_city'] : '';
                $orderDetails['lender_zipcode'] = $orderDetails['lender_zipcode'] ? $orderDetails['lender_zipcode'] : '';
                $orderDetails['lender_assignment_clause'] = $orderDetails['lender_assignment_clause'] ? $orderDetails['lender_assignment_clause'] : '';
                $orderDetails['lender_id'] = $orderDetails['lender_id'] ? $orderDetails['lender_id'] : '';
            }
        } else {
            if (!empty($orderDetails['cpl_lender_id'])) {
                $lenderDetails = $this->home_model->get_user(array('id' => $orderDetails['cpl_lender_id']));
                $orderDetails['lender_first_name'] = $lenderDetails['first_name'] ? $lenderDetails['first_name'] : '';
                $orderDetails['lender_last_name'] = $lenderDetails['last_name'] ? $lenderDetails['last_name'] : '';
                $orderDetails['lender_email'] = $lenderDetails['email_address'] ? $lenderDetails['email_address'] : '';
                $orderDetails['lender_state'] = $lenderDetails['state'] ? $lenderDetails['state'] : '';
                $orderDetails['lender_company_name'] = $lenderDetails['company_name'] ? $lenderDetails['company_name'] : '';
                $orderDetails['lender_address'] = $lenderDetails['street_address'] ? $lenderDetails['street_address'] : '';
                $orderDetails['lender_city'] = $lenderDetails['city'] ? $lenderDetails['city'] : '';
                $orderDetails['lender_zipcode'] = $lenderDetails['zip_code'] ? $lenderDetails['zip_code'] : '';
                $orderDetails['lender_assignment_clause'] = $lenderDetails['assignment_clause'] ? $lenderDetails['assignment_clause'] : '';
                $orderDetails['lender_id'] = $lenderDetails['id'] ? $lenderDetails['id'] : '';
            } else {
                if ($orderUser['is_primary_mortgage_user'] == 1) {
                    $orderDetails['lender_first_name'] = '';
                    $orderDetails['lender_last_name'] = '';
                    $orderDetails['lender_email'] = '';
                    $orderDetails['lender_state'] = '';
                    $orderDetails['lender_company_name'] = '';
                    $orderDetails['lender_address'] = '';
                    $orderDetails['lender_city'] = '';
                    $orderDetails['lender_zipcode'] = '';
                    $orderDetails['lender_assignment_clause'] = '';
                    $orderDetails['lender_id'] = '';
                } else {
                    $orderDetails['lender_first_name'] = $orderUser['first_name'] ? $orderUser['first_name'] : '';
                    $orderDetails['lender_last_name'] = $orderUser['last_name'] ? $orderUser['last_name'] : '';
                    $orderDetails['lender_email'] = $orderUser['email_address'] ? $orderUser['email_address'] : '';
                    $orderDetails['lender_state'] = $orderUser['state'] ? $orderUser['state'] : '';
                    $orderDetails['lender_company_name'] = $orderUser['company_name'] ? $orderUser['company_name'] : '';
                    $orderDetails['lender_address'] = $orderUser['street_address'] ? $orderUser['street_address'] : '';
                    $orderDetails['lender_city'] = $orderUser['city'] ? $orderUser['city'] : '';
                    $orderDetails['lender_zipcode'] = $orderUser['zip_code'] ? $orderUser['zip_code'] : '';
                    $orderDetails['lender_assignment_clause'] = $orderUser['assignment_clause'] ? $orderUser['assignment_clause'] : '';
                    $orderDetails['lender_id'] = $orderUser['id'] ? $orderUser['id'] : '';
                }
            }
            $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
        }

        if (empty($orderDetails['lender_first_name']) && empty($orderDetails['lender_last_name'])) {
            $orderDetails['lender_name'] = '';
        } else if (empty($orderDetails['lender_first_name']) && !empty($orderDetails['lender_last_name'])) {
            $orderDetails['lender_name'] = $orderDetails['lender_last_name'];
        } else if (!empty($orderDetails['lender_first_name']) && empty($orderDetails['lender_last_name'])) {
            $orderDetails['lender_name'] = $orderDetails['lender_first_name'];
        } else if (!empty($orderDetails['lender_first_name']) && !empty($orderDetails['lender_last_name'])) {
            $orderDetails['lender_name'] = $orderDetails['lender_first_name'] . " " . $orderDetails['lender_last_name'];
        }

        if ($orderDetails['sales_amount'] > 0) {
            if (!empty($orderDetails['borrower'])) {
                $orderDetails['primary_owner_name'] = $orderDetails['borrower'];
            } else {
                $orderDetails['primary_owner_name'] = '';
            }

            if (!empty($orderDetails['secondary_borrower'])) {
                $orderDetails['secondary_owner_name'] = $orderDetails['secondary_borrower'];
            } else {
                $orderDetails['secondary_owner_name'] = '';
            }
        } else {
            if (!empty($orderDetails['primary_owner'])) {
                $orderDetails['primary_owner_name'] = $orderDetails['primary_owner'];
            } else {
                $orderDetails['primary_owner_name'] = '';
            }

            if (!empty($orderDetails['secondary_owner'])) {
                $orderDetails['secondary_owner_name'] = $orderDetails['secondary_owner'];
            } else {
                $orderDetails['secondary_owner_name'] = '';
            }
        }

        $endPoint = 'files/' . $fileId . '/partners';
        $user_data = array();
        if (!empty($userdata['id']) && (empty($requestFrom) || $requestFrom != 'generic-form')) {
            if ($userdata['is_title_officer'] == 1 || $userdata['is_master'] == 1) {
                $user_data['admin_api'] = 1;
            } else {
                $user_data = array();
            }
        } else {
            $user_data['admin_api'] = 1;
        }

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, $user_data, array(), $orderDetails['order_id'], 0);
        $resultPartners = $this->resware->make_request('GET', $endPoint, '', $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), $resultPartners, $orderDetails['order_id'], $logid);
        $resPartners = json_decode($resultPartners, true);
        if (!empty($resPartners)) {
            $key = array_search(7, array_column($resPartners['Partners'], 'PartnerTypeID'));
            $underWriter = 'westcor';
            if (str_contains($resPartners['Partners'][$key]['PartnerName'], 'Doma Title Insurance')) {
                $cplApi = 'doma';
                $orderDetails['cpl_api'] = 'doma';
                $branchesData = $this->natic->getDomaBranches();
                // $branchesData = false;
                if ($branchesData === false) {
                    $branchesData = $this->natic->getBranchesFromApi($cplApi);
                }
                // echo "<pre>";
                // print_r($branchesData);die;
                $orderDetails['agents_data'] = $branchesData;
                $underWriter = 'north_american';
            } elseif (($resPartners['Partners'][$key]['PartnerName'] == 'North American Title Insurance Company')) {
                $orderDetails['cpl_api'] = 'natic';
                $branchesData = $this->natic->getBranches();
                // $branchesData = false;
                if ($branchesData === false) {
                    $branchesData = $this->natic->getBranchesFromApi();
                }
                $orderDetails['agents_data'] = $branchesData;
                $underWriter = 'north_american';
            } elseif ($resPartners['Partners'][$key]['PartnerName'] == 'Westcor Land Title Insurance Company') {
                $orderDetails['cpl_api'] = 'westcor';
                $this->load->library('order/westcor');
                $branchesData = $this->westcor->getBranches();
                if ($branchesData === false) {
                    $branchesData = $this->westcor->getBranchesFromApi();
                }
                $orderDetails['agents_data'] = $branchesData;
                $underWriter = 'westcor';
            } else if ($resPartners['Partners'][$key]['PartnerName'] == 'Commonwealth Land Title Insurance Company') {
                $orderDetails['cpl_api'] = 'fnf';
                $agentsData = $this->fnf->getAgents();
                if ($agentsData === false) {
                    $orderDetails['email'] = $orderUser['email_address'];
                    $agentsData = $this->fnf->getAgentsFromApi($orderDetails);
                }
                $orderDetails['agents_data'] = $agentsData;
                $underWriter = 'commonwealth';
            } else {
                $orderDetails['cpl_api'] = 'westcor';
                $this->load->library('order/westcor');
                $branchesData = $this->westcor->getBranches();
                if ($branchesData === false) {
                    $branchesData = $this->westcor->getBranchesFromApi();
                }
                $orderDetails['agents_data'] = $branchesData;
            }
            $underwriter_data = [
                'underwriter' => $underWriter,
            ];
            $update_condition = [
                'file_id' => $fileId,
            ];
            $this->order->update($underwriter_data, $update_condition);
        }
        if (!empty($orderDetails['borrowers_vesting'])) {
            $orderDetails['borrowers_vesting'] = $orderDetails['borrowers_vesting'];
        } else {
            if (!empty($orderDetails['primary_owner_name'])) {
                $orderDetails['borrowers_vesting'] = $orderDetails['primary_owner_name'];
            }

            if (!empty($orderDetails['secondary_owner_name'])) {
                $orderDetails['borrowers_vesting'] .= " " . $orderDetails['secondary_owner_name'];
            }

            if (!empty($orderDetails['vesting'])) {
                $orderDetails['borrowers_vesting'] .= " " . $orderDetails['vesting'];
            }
        }
        if (!empty($orderDetails['cpl_proposed_property_address'])) {
            $orderDetails['property_address'] = $orderDetails['cpl_proposed_property_address'];
            $orderDetails['property_city'] = $orderDetails['cpl_proposed_property_city'];
            $orderDetails['property_state'] = $orderDetails['cpl_proposed_property_state'];
            $orderDetails['property_zipcode'] = $orderDetails['cpl_proposed_property_zip'];
            $orderDetails['unit_number'] = '';
        } else {
            $orderDetails['property_address'] = $orderDetails['address'];
            $orderDetails['property_city'] = $orderDetails['property_city'];
            $orderDetails['property_state'] = $orderDetails['property_state'];
            $orderDetails['property_zipcode'] = $orderDetails['property_zip'];
        }
        $orderDetails['loan_amount'] = $orderDetails['loan_amount'] ? $orderDetails['loan_amount'] : '';
        $orderDetails['loan_number'] = $orderDetails['loan_number'] ? $orderDetails['loan_number'] : '';
        $response = array('status' => 'success', 'orderDetails' => $orderDetails);
        echo json_encode($response);exit;
    }

    public function createCPlForFnf()
    {
        $this->load->library('order/fnf');
        $this->load->model('order/home_model');
        $this->load->model('order/document');
        $errors = array();
        $success = array();
        $userdata = $this->session->userdata('user');
        if (empty($userdata)) {
            $userdata['id'] = 0;
        }
        $fileId = $this->uri->segment(2);
        $orderDetails = $this->order->get_order_details($fileId);
        $vendorTokenData = $this->fnf->get_vendor_token();

        if ($vendorTokenData === false) {
            $vendorTokenData = $this->fnf->generateVendorToken($orderDetails);
        }
        $userTokenData = $this->fnf->get_user_token();

        if ($userTokenData === false) {
            $userTokenData = $this->fnf->generateUserToken($orderDetails);
            if (!$userTokenData) {
                $errors[] = "Authentication failed, Please try again.";
                $data = array(
                    "errors" => $errors,
                    "success" => $success,
                );
                $this->session->set_userdata($data);
                redirect(base_url() . 'cpl-dashboard');
            }
        }
        $oldOrderFlag = 0;

        if (!empty($orderDetails['created'])) {
            $date = new DateTime($orderDetails['created']);
            $date2 = new DateTime('2021-01-29 00:00:00');
            $diff = $date2->getTimestamp() - $date->getTimestamp();
            if ($diff > 0) {
                $oldOrderFlag = 1;
            }
        }

        if (!empty($orderDetails['fnf_document_id']) && $oldOrderFlag == 0) {
            $editCplResponse = $this->fnf->editCpl($orderDetails, $vendorTokenData, $userTokenData);
            if ($editCplResponse['success']) {
                $cplCount = $this->document->countCplDocument($orderDetails['order_id']);
                $document_name = "fnf_" . $cplCount . "_" . $fileId . ".pdf";
                if (!is_dir('uploads/documents')) {
                    mkdir('./uploads/documents', 0777, true);
                }
                file_put_contents('./uploads/documents/' . $document_name, base64_decode($editCplResponse['response']['a:Content']));
                $this->home_model->update(array('cpl_document_name' => $document_name, 'fnf_document_id' => $editCplResponse['response']['a:DocumentId']), array('file_id' => $fileId), 'order_details');
                $success[] = "CPL document edited successfully for file number - " . $orderDetails['file_number'];
                $this->order->uploadCPLDocumentToResware($document_name, $orderDetails, $editCplResponse['response']['a:Content']);
                $this->order->uploadDocumentOnAwsS3($document_name, 'documents');
                if (!empty($userdata) && $userdata['id'] == $orderDetails['title_officer']) {
                    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['customer_id'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
                } else if (!empty($userdata) && $userdata['id'] == $orderDetails['customer_id']) {
                    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['title_officer'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
                } else {
                    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['title_officer'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['customer_id'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
                }
            } else {
                $errors[] = $editCplResponse['error'] . "<br> We are aware of the Error generated by our CPL form and that our Customer service team will be contacting them shortly.";
                $cplErrorData = array(
                    'order_id' => $orderDetails['order_id'],
                    'file_number' => $orderDetails['file_number'],
                    'cpl_page' => $userdata['id'] > 0 ? 'Dashboard' : 'Generic Or Mail page',
                    'error' => $editCplResponse['error'],
                    'customer_id' => $orderDetails['customer_id'],
                    'property_address' => $orderDetails['full_address'],
                );
                $this->order->storeCplError($cplErrorData);
            }
            $data = array(
                "errors" => $errors,
                "success" => $success,
            );
        }

        $getCPLFormNameResponse = $this->fnf->getCPLForm($orderDetails, $vendorTokenData, $userTokenData);
        if ($getCPLFormNameResponse['success']) {
            $key = array_search('Lender', array_column($getCPLFormNameResponse['response'], 'a:RecipientType'));
            //$orderDetails['formname'] = $getCPLFormNameResponse['response'][$key]['a:FormName'];
            $orderDetails['formname'] = 'Standard CPL_' . $orderDetails['property_state'];
            $generateCplResponse = $this->fnf->generateCpl($orderDetails, $vendorTokenData, $userTokenData);

            if ($generateCplResponse['success']) {
                $cplCount = $this->document->countCplDocument($orderDetails['order_id']);
                $document_name = "fnf_" . $cplCount . "_" . $fileId . ".pdf";
                if (!is_dir('uploads/documents')) {
                    mkdir('./uploads/documents', 0777, true);
                }
                file_put_contents('./uploads/documents/' . $document_name, base64_decode($generateCplResponse['response']['a:Content']));
                $this->home_model->update(array('cpl_document_name' => $document_name, 'fnf_document_id' => $generateCplResponse['response']['a:DocumentId']), array('file_id' => $fileId), 'order_details');
                $success[] = "Generated CPL request successfully for file number - " . $orderDetails['file_number'];
                $this->order->uploadCPLDocumentToResware($document_name, $orderDetails, $generateCplResponse['response']['a:Content']);
                $this->order->uploadDocumentOnAwsS3($document_name, 'documents');
                if (!empty($userdata) && $userdata['id'] == $orderDetails['title_officer']) {
                    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['customer_id'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
                } else if (!empty($userdata) && $userdata['id'] == $orderDetails['customer_id']) {
                    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['title_officer'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
                } else {
                    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['title_officer'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['customer_id'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
                }
            } else {
                $errors[] = $generateCplResponse['error'] . "<br> We are aware of the Error generated by our CPL form and that our Customer service team will be contacting them shortly.";
                $cplErrorData = array(
                    'order_id' => $orderDetails['order_id'],
                    'file_number' => $orderDetails['file_number'],
                    'cpl_page' => $userdata['id'] > 0 ? 'Dashboard' : 'Generic Or Mail page',
                    'error' => $generateCplResponse['error'],
                    'customer_id' => $orderDetails['customer_id'],
                    'property_address' => $orderDetails['full_address'],
                );
                $this->order->storeCplError($cplErrorData);
            }
            $data = array(
                "errors" => $errors,
                "success" => $success,
            );
        } else {
            $errors[] = $getCPLFormNameResponse['error'] . "<br> We are aware of the Error generated by our CPL form and that our Customer service team will be contacting them shortly.";
            $cplErrorData = array(
                'order_id' => $orderDetails['order_id'],
                'file_number' => $orderDetails['file_number'],
                'cpl_page' => $userdata['id'] > 0 ? 'Dashboard' : 'Generic Or Mail page',
                'error' => $getCPLFormNameResponse['error'],
                'customer_id' => $orderDetails['customer_id'],
                'property_address' => $orderDetails['full_address'],
            );
            $this->order->storeCplError($cplErrorData);
            $data = array(
                "errors" => $errors,
                "success" => $success,
            );
        }
        $this->session->unset_userdata('lender_details');
        $this->session->set_userdata($data);
        if (!empty($userdata['id'])) {
            redirect(base_url() . 'cpl-dashboard');
        } else {
            redirect(base_url() . 'generate-cpl/' . $orderDetails['random_number']);
        }
    }

    public function createCPlForNatic()
    {
        $this->load->library('order/natic');
        $this->load->model('order/home_model');
        $this->load->model('order/document');
        $errors = array();
        $success = array();
        $userdata = $this->session->userdata('user');
        if (empty($userdata)) {
            $userdata['id'] = 0;
        }
        $fileId = $this->uri->segment(2);
        $currentRoute = $this->uri->segment(1);
        $cplApi = ($currentRoute === 'create-cpl-for-doma') ? 'doma' : 'natic';

        $orderDetails = $this->order->get_order_details($fileId);
        $responseArr = $this->natic->getDocumentContentForCpl($fileId, $orderDetails, $cplApi);
        if ($responseArr['success']) {
            $cplCount = $this->document->countCplDocument($orderDetails['order_id']);
            $document_name = $cplApi . "_" . $cplCount . "_" . $fileId . ".pdf";
            if (!is_dir('uploads/documents')) {
                mkdir('./uploads/documents', 0777, true);
            }
            file_put_contents('./uploads/documents/' . $document_name, base64_decode($responseArr['content']));
            $this->home_model->update(array('cpl_document_name' => $document_name), array('file_id' => $fileId), 'order_details');
            $success[] = "Generated CPL request successfully for file number - " . $orderDetails['file_number'];
            $this->order->uploadCPLDocumentToResware($document_name, $orderDetails, $responseArr['content']);
            $this->order->uploadDocumentOnAwsS3($document_name, 'documents');
            if (!empty($userdata) && $userdata['id'] == $orderDetails['title_officer']) {
                $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                $notificationData = array(
                    'sent_user_id' => $orderDetails['customer_id'],
                    'message' => $message,
                    'is_admin' => 0,
                    'type' => 'created',
                );
                $this->home_model->insert($notificationData, 'pct_order_notifications');
                $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
            } else if (!empty($userdata) && $userdata['id'] == $orderDetails['customer_id']) {
                $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                $notificationData = array(
                    'sent_user_id' => $orderDetails['title_officer'],
                    'message' => $message,
                    'is_admin' => 0,
                    'type' => 'created',
                );
                $this->home_model->insert($notificationData, 'pct_order_notifications');
                $this->order->sendNotification($message, 'assigned', $orderDetails['title_officer'], 0);
            } else {
                $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                $notificationData = array(
                    'sent_user_id' => $orderDetails['title_officer'],
                    'message' => $message,
                    'is_admin' => 0,
                    'type' => 'created',
                );
                $this->home_model->insert($notificationData, 'pct_order_notifications');
                $this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
                $notificationData = array(
                    'sent_user_id' => $orderDetails['customer_id'],
                    'message' => $message,
                    'is_admin' => 0,
                    'type' => 'created',
                );
                $this->home_model->insert($notificationData, 'pct_order_notifications');
                $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
            }
        } else {
            $errors[] = $responseArr['error'] . "<br> We are aware of the Error generated by our CPL form and that our Customer service team will be contacting them shortly.";
            $cplErrorData = array(
                'order_id' => $orderDetails['order_id'],
                'file_number' => $orderDetails['file_number'],
                'cpl_page' => $userdata['id'] > 0 ? 'Dashboard' : 'Generic Or Mail page',
                'error' => $responseArr['error'],
                'customer_id' => $orderDetails['customer_id'],
                'property_address' => $orderDetails['full_address'],
            );
            $this->order->storeCplError($cplErrorData);
        }
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        $this->session->unset_userdata('lender_details');
        if (!empty($userdata['id'])) {
            redirect(base_url() . 'cpl-dashboard');
        } else {
            redirect(base_url() . 'generate-cpl/' . $orderDetails['random_number']);
        }
    }

    /*public function createCPlForDoma()
    {
    $this->load->library('order/doma');
    $this->load->model('order/home_model');
    $this->load->model('order/document');
    $errors = array();
    $success = array();
    $userdata = $this->session->userdata('user');
    if (empty($userdata)) {
    $userdata['id'] = 0;
    }
    $fileId = $this->uri->segment(2);
    $orderDetails = $this->order->get_order_details($fileId);
    $responseArr = $this->doma->getDocumentContentForCpl($fileId, $orderDetails);
    if ($responseArr['success']) {
    $cplCount = $this->document->countCplDocument($orderDetails['order_id']);
    $document_name = "doma_" . $cplCount . "_" . $fileId . ".pdf";
    if (!is_dir('uploads/documents')) {
    mkdir('./uploads/documents', 0777, true);
    }
    file_put_contents('./uploads/documents/' . $document_name, base64_decode($responseArr['content']));
    $this->home_model->update(array('cpl_document_name' => $document_name), array('file_id' => $fileId), 'order_details');
    $success[] = "Generated CPL request successfully for file number - " . $orderDetails['file_number'];
    $this->order->uploadCPLDocumentToResware($document_name, $orderDetails, $responseArr['content']);
    $this->order->uploadDocumentOnAwsS3($document_name, 'documents');
    if (!empty($userdata) && $userdata['id'] == $orderDetails['title_officer']) {
    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
    $notificationData = array(
    'sent_user_id' => $orderDetails['customer_id'],
    'message' => $message,
    'is_admin' => 0,
    'type' => 'created',
    );
    $this->home_model->insert($notificationData, 'pct_order_notifications');
    $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
    } else if (!empty($userdata) && $userdata['id'] == $orderDetails['customer_id']) {
    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
    $notificationData = array(
    'sent_user_id' => $orderDetails['title_officer'],
    'message' => $message,
    'is_admin' => 0,
    'type' => 'created',
    );
    $this->home_model->insert($notificationData, 'pct_order_notifications');
    $this->order->sendNotification($message, 'assigned', $orderDetails['title_officer'], 0);
    } else {
    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
    $notificationData = array(
    'sent_user_id' => $orderDetails['title_officer'],
    'message' => $message,
    'is_admin' => 0,
    'type' => 'created',
    );
    $this->home_model->insert($notificationData, 'pct_order_notifications');
    $this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
    $notificationData = array(
    'sent_user_id' => $orderDetails['customer_id'],
    'message' => $message,
    'is_admin' => 0,
    'type' => 'created',
    );
    $this->home_model->insert($notificationData, 'pct_order_notifications');
    $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
    }
    } else {
    $errors[] = $responseArr['error'] . "<br> We are aware of the Error generated by our CPL form and that our Customer service team will be contacting them shortly.";
    $cplErrorData = array(
    'order_id' => $orderDetails['order_id'],
    'file_number' => $orderDetails['file_number'],
    'cpl_page' => $userdata['id'] > 0 ? 'Dashboard' : 'Generic Or Mail page',
    'error' => $responseArr['error'],
    'customer_id' => $orderDetails['customer_id'],
    'property_address' => $orderDetails['full_address'],
    );
    $this->order->storeCplError($cplErrorData);
    }
    $data = array(
    "errors" => $errors,
    "success" => $success,
    );
    $this->session->set_userdata($data);
    $this->session->unset_userdata('lender_details');
    if (!empty($userdata['id'])) {
    redirect(base_url() . 'cpl-dashboard');
    } else {
    redirect(base_url() . 'generate-cpl/' . $orderDetails['random_number']);
    }
    }*/

    public function getDetailsByName()
    {
        $searchTerm = isset($_POST['term']) && !empty($_POST['term']) ? $_POST['term'] : '';
        $is_master_search = isset($_POST['is_master_search']) && !empty($_POST['is_master_search']) ? $_POST['is_master_search'] : 0;
        $condition = array(
            'company_name' => $searchTerm,
        );

        if (isset($_POST['is_escrow'])) {
            $isEscrow = $_POST['is_escrow'];
            $condition['is_escrow'] = $isEscrow;
        }

        $condition['where']['is_sales_rep'] = 0;
        $is_from_order_form = $this->input->post('is_from_order_form');
        $condition['is_from_order_form'] = isset($is_from_order_form) && !empty($is_from_order_form) ? $is_from_order_form : 0;
        $userDetails = $this->home_model->get_customers($condition, $is_master_search);
        $userInfo = array();

        if (isset($userDetails) && !empty($userDetails)) {
            foreach ($userDetails as $key => $value) {
                $data['id'] = isset($value['id']) && !empty($value['id']) ? $value['id'] : '';
                $data['value'] = isset($value['value']) && !empty($value['value']) ? $value['value'] : '';
                $data['partner_id'] = isset($value['partner_id']) && !empty($value['partner_id']) ? $value['partner_id'] : '';
                $data['name'] = isset($value['full_name']) && !empty($value['full_name']) ? $value['full_name'] : '';
                $data['fname'] = isset($value['first_name']) && !empty($value['first_name']) ? $value['first_name'] : '';
                $data['lname'] = isset($value['last_name']) && !empty($value['last_name']) ? $value['last_name'] : '';
                $data['email_address'] = isset($value['email_address']) && !empty($value['email_address']) ? $value['email_address'] : '';
                $data['telephone_no'] = isset($value['telephone_no']) && !empty($value['telephone_no']) ? $value['telephone_no'] : '';
                $data['company'] = isset($value['company_name']) && !empty($value['company_name']) ? $value['company_name'] : '';
                $data['address'] = isset($value['street_address']) && !empty($value['street_address']) ? $value['street_address'] : '';
                $data['city'] = isset($value['city']) && !empty($value['city']) ? $value['city'] : '';
                $data['state'] = isset($value['state']) && !empty($value['state']) ? $value['state'] : '';
                $data['zip_code'] = isset($value['zip_code']) && !empty($value['zip_code']) ? $value['zip_code'] : '';
                $data['is_escrow'] = isset($value['is_escrow']) && !empty($value['is_escrow']) ? $value['is_escrow'] : '';
                $data['assignment_clause'] = isset($value['assignment_clause']) && !empty($value['assignment_clause']) ? $value['assignment_clause'] : '';
                $data['is_primary_mortgage_user'] = isset($value['is_primary_mortgage_user']) && !empty($value['is_primary_mortgage_user']) ? $value['is_primary_mortgage_user'] : '';
                $data['title_officer_id'] = isset($value['title_officer_id']) && !empty($value['title_officer_id']) ? $value['title_officer_id'] : '';
                $data['sales_rep_id'] = isset($value['sales_rep_id']) && !empty($value['sales_rep_id']) ? $value['sales_rep_id'] : '';
                // array_push($userInfo, $data);
                $userInfo[] = $data;
            }
        }
        echo json_encode($userInfo);
    }

    public function downloadAwsDocument()
    {
        $userdata = $this->session->userdata('user');
        $url = $this->input->post('url');
        $user_data['admin_api'] = 1;
        $api_document_id = $this->input->post('api_document_id');
        $binaryData = base64_encode(file_get_contents($url));
        $this->load->model('order/document');
        if (empty($binaryData) && !empty($api_document_id)) {
            $endPoint = 'documents/' . $api_document_id . '?format=json';
            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
            $resultDocument = $this->resware->make_request('GET', $endPoint, '', $user_data);
            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocument, 0, $logid);
            $resDocument = json_decode($resultDocument, true);
            if (isset($resDocument['Document']) && !empty($resDocument['Document'])) {
                $binaryData = $resDocument['Document']['DocumentBody'];
                $documentContent = base64_decode($resDocument['Document']['DocumentBody'], true);
                $document_name = str_replace(env('AWS_PATH') . "documents/", '', $url);
                if (!is_dir('uploads/documents')) {
                    mkdir('./uploads/documents', 0777, true);
                }
                file_put_contents('./uploads/documents/' . $document_name, $documentContent);
                $this->order->uploadDocumentOnAwsS3($document_name, 'documents');
                $this->document->update(array('is_sync' => 1), array('api_document_id' => $api_document_id));
            }
        }
        echo $binaryData;exit;
    }

    public function generate_proposed_insured()
    {
        $fileId = isset($_POST['fileId']) && !empty($_POST['fileId']) ? $_POST['fileId'] : '';
        $data['fileId'] = $fileId;
        $orderDetails = $this->order->get_order_details($fileId);
        $orderId = isset($orderDetails['order_id']) && !empty($orderDetails['order_id']) ? $orderDetails['order_id'] : '';
        $data['orderId'] = $orderId;
        $transaction_id = isset($orderDetails['transaction_id']) && !empty($orderDetails['transaction_id']) ? $orderDetails['transaction_id'] : '';
        $data['transaction_id'] = $transaction_id;
        $vesting = isset($orderDetails['vesting']) && !empty($orderDetails['vesting']) ? $orderDetails['vesting'] : '';
        $data['vesting'] = $vesting;
        $property_id = isset($orderDetails['property_id']) && !empty($orderDetails['property_id']) ? $orderDetails['property_id'] : '';
        $data['property_id'] = $property_id;
        $customer_id = isset($orderDetails['customer_id']) && !empty($orderDetails['customer_id']) ? $orderDetails['customer_id'] : '';
        $this->load->model('order/home_model');
        $customer_data = $this->home_model->get_user(array('id' => $customer_id));
        $data['company'] = isset($customer_data['company_name']) && !empty($customer_data['company_name']) ? $customer_data['company_name'] : '';
        $address = array();
        $street_address = isset($customer_data['street_address']) && !empty($customer_data['street_address']) ? $customer_data['street_address'] : '';
        if ($street_address) {
            $address[] = $street_address;
        }
        $city = isset($customer_data['city']) && !empty($customer_data['city']) ? $customer_data['city'] : '';
        if ($city) {
            $address[] = $city;
        }

        $zip_code = isset($customer_data['zip_code']) && !empty($customer_data['zip_code']) ? $customer_data['zip_code'] : '';
        if ($zip_code) {
            $address[] = $zip_code;
        }
        $data['address'] = implode(', ', $address);
        $data['order_number'] = isset($orderDetails['file_number']) && !empty($orderDetails['file_number']) ? $orderDetails['file_number'] : '';
        $data['property_address'] = isset($orderDetails['full_address']) && !empty($orderDetails['full_address']) ? $orderDetails['full_address'] : '';
        $data['sales_amount'] = isset($orderDetails['sales_amount']) && !empty($orderDetails['sales_amount']) ? $orderDetails['sales_amount'] : '';
        $data['loan_amount'] = isset($orderDetails['loan_amount']) && !empty($orderDetails['loan_amount']) ? $orderDetails['loan_amount'] : '';
        $data['loan_number'] = isset($orderDetails['loan_number']) && !empty($orderDetails['loan_number']) ? $orderDetails['loan_number'] : '';
        $data['title_officer'] = isset($orderDetails['title_officer']) && !empty($orderDetails['title_officer']) ? $orderDetails['title_officer'] : '';

        if ($orderDetails['sales_amount'] > 0) {
            if (!empty($orderDetails['borrower'])) {
                $orderDetails['primary_owner_name'] = $orderDetails['borrower'];
            } else {
                $orderDetails['primary_owner_name'] = '';
            }

            if (!empty($orderDetails['secondary_borrower'])) {
                $orderDetails['secondary_owner_name'] = $orderDetails['secondary_borrower'];
            } else {
                $orderDetails['secondary_owner_name'] = '';
            }
        } else {
            if (!empty($orderDetails['primary_owner'])) {
                $orderDetails['primary_owner_name'] = $orderDetails['primary_owner'];
            } else {
                $orderDetails['primary_owner_name'] = '';
            }

            if (!empty($orderDetails['secondary_owner'])) {
                $orderDetails['secondary_owner_name'] = $orderDetails['secondary_owner'];
            } else {
                $orderDetails['secondary_owner_name'] = '';
            }
        }

        if (!empty($orderDetails['borrowers_vesting'])) {
            $orderDetails['borrowers_vesting'] = $orderDetails['borrowers_vesting'];
        } else {
            if (!empty($orderDetails['primary_owner_name'])) {
                $orderDetails['borrowers_vesting'] = $orderDetails['primary_owner_name'];
            }

            if (!empty($orderDetails['secondary_owner_name'])) {
                $orderDetails['borrowers_vesting'] .= " " . $orderDetails['secondary_owner_name'];
            }

            if (!empty($orderDetails['vesting'])) {
                $orderDetails['borrowers_vesting'] .= " " . $orderDetails['vesting'];
            }
        }
        $data['borrowers_vesting'] = $orderDetails['borrowers_vesting'];

        /* property address */
        if (!empty($orderDetails['cpl_proposed_property_address'])) {
            $data['street_address'] = isset($orderDetails['cpl_proposed_property_address']) && !empty($orderDetails['cpl_proposed_property_address']) ? $orderDetails['cpl_proposed_property_address'] : '';
        } else {
            $data['street_address'] = isset($orderDetails['address']) && !empty($orderDetails['address']) ? $orderDetails['address'] : '';
        }

        if (!empty($orderDetails['cpl_proposed_property_city'])) {
            $data['property_city'] = isset($orderDetails['cpl_proposed_property_city']) && !empty($orderDetails['cpl_proposed_property_city']) ? $orderDetails['cpl_proposed_property_city'] : '';
        } else {
            $data['property_city'] = isset($orderDetails['property_city']) && !empty($orderDetails['property_city']) ? $orderDetails['property_city'] : '';
        }

        if (!empty($orderDetails['cpl_proposed_property_state'])) {
            $data['property_state'] = isset($orderDetails['cpl_proposed_property_state']) && !empty($orderDetails['cpl_proposed_property_state']) ? $orderDetails['cpl_proposed_property_state'] : '';
        } else {
            $data['property_state'] = isset($orderDetails['property_state']) && !empty($orderDetails['property_state']) ? $orderDetails['property_state'] : '';
        }

        if (!empty($orderDetails['cpl_proposed_property_zip'])) {
            $data['property_zip'] = isset($orderDetails['cpl_proposed_property_zip']) && !empty($orderDetails['cpl_proposed_property_zip']) ? $orderDetails['cpl_proposed_property_zip'] : '';
        } else {
            $data['property_zip'] = isset($orderDetails['property_zip']) && !empty($orderDetails['property_zip']) ? $orderDetails['property_zip'] : '';
        }
        /* property address */

        if (!empty($orderDetails['supplemental_report_date']) && $orderDetails['supplemental_report_date'] != '0000-00-00') {
            $s_report_date = date("m/d/Y", strtotime($orderDetails['supplemental_report_date']));
        }

        $data['supplemental_report_date'] = isset($s_report_date) && !empty($s_report_date) ? $s_report_date : '';

        if (!empty($orderDetails['preliminary_report_date']) && $orderDetails['preliminary_report_date'] != '0000-00-00') {
            $p_report_date = date("m/d/Y", strtotime($orderDetails['preliminary_report_date']));
        }
        $data['preliminary_report_date'] = isset($p_report_date) && !empty($p_report_date) ? $p_report_date : '';

        $data['is_escrow'] = $customer_data['is_escrow'];
        $data['proposed_branch_id'] = $orderDetails['proposed_branch_id'];

        if ($customer_data['is_escrow'] == 1) {
            if (!empty($orderDetails['cpl_lender_id'])) {
                $lenderDetails = $this->home_model->get_user(array('id' => $orderDetails['cpl_lender_id']));
                $data['lender_first_name'] = $lenderDetails['first_name'] ? $lenderDetails['first_name'] : '';
                $data['lender_last_name'] = $lenderDetails['last_name'] ? $lenderDetails['last_name'] : '';
                $data['lender_email'] = $lenderDetails['email_address'] ? $lenderDetails['email_address'] : '';
                $data['lender_state'] = $lenderDetails['state'] ? $lenderDetails['state'] : '';
                $data['lender_company_name'] = $lenderDetails['company_name'] ? $lenderDetails['company_name'] : '';
                $data['lender_address'] = $lenderDetails['street_address'] ? $lenderDetails['street_address'] : '';
                $data['lender_city'] = $lenderDetails['city'] ? $lenderDetails['city'] : '';
                $data['lender_zipcode'] = $lenderDetails['zip_code'] ? $lenderDetails['zip_code'] : '';
                $data['lender_assignment_clause'] = $lenderDetails['assignment_clause'] ? $lenderDetails['assignment_clause'] : '';
                $data['lender_id'] = $lenderDetails['id'] ? $lenderDetails['id'] : '';
                // $data['escrow_lender_id'] = '';
            } else {
                // $data['escrow_lender_id'] = $orderDetails['escrow_lender_id'] ? $orderDetails['escrow_lender_id'] : '';
                $data['lender_first_name'] = $orderDetails['lender_first_name'] ? $orderDetails['lender_first_name'] : '';
                $data['lender_last_name'] = $orderDetails['lender_last_name'] ? $orderDetails['lender_last_name'] : '';
                $data['lender_email'] = $orderDetails['lender_email'] ? $orderDetails['lender_email'] : '';
                $data['lender_state'] = $orderDetails['lender_state'] ? $orderDetails['lender_state'] : '';
                $data['lender_company_name'] = $orderDetails['lender_company_name'] ? $orderDetails['lender_company_name'] : '';
                $data['lender_address'] = $orderDetails['lender_address'] ? $orderDetails['lender_address'] : '';
                $data['lender_city'] = $orderDetails['lender_city'] ? $orderDetails['lender_city'] : '';
                $data['lender_zipcode'] = $orderDetails['lender_zipcode'] ? $orderDetails['lender_zipcode'] : '';
                $data['lender_assignment_clause'] = $orderDetails['lender_assignment_clause'] ? $orderDetails['lender_assignment_clause'] : '';
                $data['lender_id'] = $orderDetails['lender_id'] ? $orderDetails['lender_id'] : '';
            }
        } else {
            if (!empty($orderDetails['cpl_lender_id'])) {
                $lenderDetails = $this->home_model->get_user(array('id' => $orderDetails['cpl_lender_id']));

                $data['cpl_lender_id'] = $orderDetails['cpl_lender_id'];
                $data['lender_first_name'] = $lenderDetails['first_name'] ? $lenderDetails['first_name'] : '';
                $data['lender_last_name'] = $lenderDetails['last_name'] ? $lenderDetails['last_name'] : '';
                $data['lender_email'] = $lenderDetails['email_address'] ? $lenderDetails['email_address'] : '';
                $data['lender_state'] = $lenderDetails['state'] ? $lenderDetails['state'] : '';
                $data['lender_company_name'] = $lenderDetails['company_name'] ? $lenderDetails['company_name'] : '';
                $data['lender_address'] = $lenderDetails['street_address'] ? $lenderDetails['street_address'] : '';
                $data['lender_city'] = $lenderDetails['city'] ? $lenderDetails['city'] : '';
                $data['lender_zipcode'] = $lenderDetails['zip_code'] ? $lenderDetails['zip_code'] : '';
                $data['lender_assignment_clause'] = $lenderDetails['assignment_clause'] ? $lenderDetails['assignment_clause'] : '';
                $data['lender_id'] = $lenderDetails['id'] ? $lenderDetails['id'] : '';
            } else {
                // $data['cpl_lender_id'] = $customer_data['id'];
                $data['lender_first_name'] = $customer_data['first_name'] ? $customer_data['first_name'] : '';
                $data['lender_last_name'] = $customer_data['last_name'] ? $customer_data['last_name'] : '';
                $data['lender_email'] = $customer_data['email_address'] ? $customer_data['email_address'] : '';
                $data['lender_state'] = $customer_data['state'] ? $customer_data['state'] : '';
                $data['lender_company_name'] = $customer_data['company_name'] ? $customer_data['company_name'] : '';
                $data['lender_address'] = $customer_data['street_address'] ? $customer_data['street_address'] : '';
                $data['lender_city'] = $customer_data['city'] ? $customer_data['city'] : '';
                $data['lender_zipcode'] = $customer_data['zip_code'] ? $customer_data['zip_code'] : '';
                $data['lender_assignment_clause'] = $customer_data['assignment_clause'] ? $customer_data['assignment_clause'] : '';
                $data['lender_id'] = $customer_data['id'] ? $customer_data['id'] : '';
            }
            $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
        }

        if (empty($data['lender_first_name']) && empty($data['lender_last_name'])) {
            $data['lender_name'] = '';
        } else if (empty($data['lender_first_name']) && !empty($data['lender_last_name'])) {
            $data['lender_name'] = $data['lender_last_name'];
        } else if (!empty($data['lender_first_name']) && empty($data['lender_last_name'])) {
            $data['lender_name'] = $data['lender_first_name'];
        } else if (!empty($data['lender_first_name']) && !empty($data['lender_last_name'])) {
            $data['lender_name'] = $data['lender_first_name'] . " " . $data['lender_last_name'];
        }

        $response = array('status' => 'success', 'orderDetails' => $data);
        echo json_encode($response);exit;
    }

    public function add_order_details()
    {
        $userdata = $this->session->userdata('user');
        if (empty($userdata)) {
            $userdata['id'] = 0;
        }
        $orderId = $this->input->post('orderId');
        $this->load->library('order/resware');

        if ($orderId) {
            $this->load->model('order/home_model');
            $TitleOfficer = $this->input->post('TitleOfficer');
            $loan_amount = $this->input->post('loan_amount');
            $loan_number = $this->input->post('loan_number');
            $name = explode(" ", $this->input->post('LenderName'));
            $new_existing_lender = $this->input->post('new_existing_lender');
            $LenderId = $this->input->post('LenderId');
            $transaction_id = $this->input->post('transaction_id');
            $property_id = $this->input->post('property_id');
            $property_address = $this->input->post('property_address');
            $property_city = $this->input->post('property_city');
            $property_state = $this->input->post('property_state');
            $property_zipcode = $this->input->post('property_zipcode');
            $borrowers_vesting = $this->input->post('borrowers_vesting');
            $branch = $this->input->post('branch');
            $fileId = $this->input->post('fileId');
            $s_report_date = $this->input->post('s_report_date');
            $p_report_date = $this->input->post('p_report_date');
            $s_report_date = date("Y-m-d", strtotime($s_report_date));
            $p_report_date = date("Y-m-d", strtotime($p_report_date));
            $orderDetails = $this->order->get_order_details($fileId);
            $lender_details = array(
                'first_name' => $name[0],
                'last_name' => !empty($name[1]) ? $name[1] : '',
                'state' => !empty($this->input->post('LenderState')) ? $this->input->post('LenderState') : "",
                'email_address' => !empty($this->input->post('LenderEmailAddress')) ? $this->input->post('LenderEmailAddress') : "",
                'company_name' => !empty($this->input->post('LenderCompany')) ? $this->input->post('LenderCompany') : "",
                'street_address' => !empty($this->input->post('LenderAddress')) ? $this->input->post('LenderAddress') : "",
                'city' => !empty($this->input->post('LenderCity')) ? $this->input->post('LenderCity') : "",
                'zip_code' => !empty($this->input->post('LenderZipcode')) ? $this->input->post('LenderZipcode') : "",
                'assignment_clause' => !empty($this->input->post('assignment_clause')) ? $this->input->post('assignment_clause') : "",
            );

            if ($new_existing_lender == 'add_lender') {
                $lender_details['partner_id'] = $this->input->post('partner_id');
                $lender_details['state'] = empty($this->input->post('state')) ? $this->input->post('state') : 'CA';
                $lender_details['is_added_lender_by_cpl_proposed'] = 1;
                $lender_details['is_escrow'] = 0;
                $lender_details['status'] = 0;
                $LenderId = $this->home_model->insert($lender_details, 'customer_basic_details');
            } else {
                $condition = array(
                    'id' => $LenderId,
                );
                $this->home_model->update($lender_details, $condition, 'customer_basic_details');
            }
            $this->home_model->update(array('proposed_branch_id' => $branch), array('file_id' => $fileId), 'order_details');

            if (empty($lender_details['first_name']) && empty($lender_details['lender_last_name'])) {
                $lender_details['lender_name'] = '';
            } else if (empty($lender_details['first_name']) && !empty($lender_details['last_name'])) {
                $lender_details['lender_name'] = $lender_details['last_name'];
            } else if (!empty($lender_details['first_name']) && empty($lender_details['last_name'])) {
                $lender_details['lender_name'] = $lender_details['first_name'];
            } else if (!empty($lender_details['first_name']) && !empty($lender_details['last_name'])) {
                $lender_details['lender_name'] = $lender_details['first_name'] . " " . $lender_details['last_name'];
            }
            $lender_address = array();
            $street_address = $this->input->post('LenderAddress');

            if ($street_address) {
                $lender_address[] = $street_address;
            }
            $city = $this->input->post('LenderCity');

            if ($city) {
                $lender_address[] = $city;
            }
            $lendeUser = $this->home_model->get_user(array('id' => $LenderId));
            $state = isset($lendeUser['state']) && !empty($lendeUser['state']) ? $lendeUser['state'] : '';

            if ($state) {
                $lender_address[] = $state;
            }
            $zip_code = $this->input->post('LenderZipcode');

            if ($zip_code) {
                $lender_address[] = $zip_code;
            }
            $pdfData['lender'] = array(
                'lender_name' => $lender_details['lender_name'],
                'address' => implode(', ', $lender_address),
                'company_name' => $lender_details['company_name'],
                'assignment_clause' => $lender_details['assignment_clause'],
            );
            $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
            $propertyDetails = array('cpl_lender_id' => $LenderId, 'cpl_proposed_property_address' => trim($property_address), 'cpl_proposed_property_city' => trim($property_city), 'cpl_proposed_property_state' => trim($property_state), 'cpl_proposed_property_zip' => trim($property_zipcode), 'borrowers_vesting' => trim($borrowers_vesting));
            $property_update_flag = $this->home_model->update($propertyDetails, array('id' => $orderDetails['property_id']), 'property_details');
            $transaction_update_flag = $this->home_model->update(array('loan_amount' => $loan_amount, 'loan_number' => $loan_number, 'title_officer' => $TitleOfficer, 'preliminary_report_date' => $p_report_date, 'supplemental_report_date' => $s_report_date), array('id' => $orderDetails['transaction_id']), 'transaction_details');

            if ($property_update_flag || $transaction_update_flag) {
                $orderDetails = $this->order->get_order_details($fileId);
                $pdfData['company'] = isset($orderUser['company_name']) && !empty($orderUser['company_name']) ? $orderUser['company_name'] : '';
                $address = array();
                $street_address = isset($orderUser['street_address']) && !empty($orderUser['street_address']) ? $orderUser['street_address'] : '';

                if ($street_address) {
                    $address[] = $street_address;
                }
                $city = isset($orderUser['city']) && !empty($orderUser['city']) ? $orderUser['city'] : '';

                if ($city) {
                    $address[] = $city;
                }
                $zip_code = isset($orderUser['zip_code']) && !empty($orderUser['zip_code']) ? $orderUser['zip_code'] : '';

                if ($zip_code) {
                    $address[] = $zip_code;
                }
                $pdfData['address'] = implode(', ', $address);
                $pdfData['order_number'] = isset($orderDetails['file_number']) && !empty($orderDetails['file_number']) ? $orderDetails['file_number'] : '';
                $new_property_address = array();

                if (isset($property_address) && !empty($property_address)) {
                    $new_property_address[] = $property_address;
                }

                if (isset($property_city) && !empty($property_city)) {
                    $new_property_address[] = $property_city;
                }

                if (isset($property_state) && !empty($property_state)) {
                    $new_property_address[] = $property_state;
                }

                if (isset($property_zipcode) && !empty($property_zipcode)) {
                    $new_property_address[] = $property_zipcode;
                }
                $pdfData['property_address'] = implode(', ', $new_property_address);
                $pdfData['sales_amount'] = isset($orderDetails['sales_amount']) && !empty($orderDetails['sales_amount']) ? $orderDetails['sales_amount'] : '';
                $pdfData['loan_amount'] = isset($orderDetails['loan_amount']) && !empty($orderDetails['loan_amount']) ? $orderDetails['loan_amount'] : '';
                $pdfData['loan_number'] = isset($orderDetails['loan_number']) && !empty($orderDetails['loan_number']) ? $orderDetails['loan_number'] : '';

                if (isset($orderDetails['title_officer']) && !empty($orderDetails['title_officer'])) {
                    if (preg_match('/\\d/', $orderDetails['title_officer']) > 0) {
                        $condition = array(
                            'id' => $orderDetails['title_officer'],
                            'status' => 1,
                        );
                        $titleOfficerDetails = $this->titleOfficer->getTitleOfficerDetails($condition);
                    }
                    $pdfData['title_officer'] = isset($titleOfficerDetails['name']) && !empty($titleOfficerDetails['name']) ? $titleOfficerDetails['name'] : '';
                    $pdfData['title_officer_email'] = isset($titleOfficerDetails['email_address']) && !empty($titleOfficerDetails['email_address']) ? $titleOfficerDetails['email_address'] : '';
                    $pdfData['title_officer_phone'] = isset($titleOfficerDetails['telephone_no']) && !empty($titleOfficerDetails['telephone_no']) ? $titleOfficerDetails['telephone_no'] : '';
                }
                $pdfData['vesting'] = isset($borrowers_vesting) && !empty($borrowers_vesting) ? $borrowers_vesting : '';
                $pdfData['supplemental_report_date'] = isset($orderDetails['supplemental_report_date']) && !empty($orderDetails['supplemental_report_date']) ? date("m/d/Y h:i:s A", strtotime($orderDetails['supplemental_report_date'])) : '';
                $pdfData['preliminary_report_date'] = isset($orderDetails['preliminary_report_date']) && !empty($orderDetails['preliminary_report_date']) ? date("m/d/Y h:i:s A", strtotime($orderDetails['preliminary_report_date'])) : '';
                $pdfData['underwriter'] = '';
                $endPoint = 'files/' . $fileId . '/partners';
                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $orderDetails['order_id'], 0);

                if (!empty($userdata['id'])) {
                    if ($userdata['is_title_officer'] == 1 || $userdata['is_master'] == 1) {
                        $user_data['admin_api'] = 1;
                    } else {
                        $user_data = array();
                    }
                } else {
                    $user_data['admin_api'] = 1;
                }

                $resultPartners = $this->resware->make_request('GET', $endPoint, '', $user_data);
                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), $resultPartners, $orderDetails['order_id'], $logid);
                $resPartners = json_decode($resultPartners, true);

                if (!empty($resPartners)) {
                    $key = array_search(7, array_column($resPartners['Partners'], 'PartnerTypeID'));
                    if (str_contains($resPartners['Partners'][$key]['PartnerName'], 'Doma Title Insurance') || $resPartners['Partners'][$key]['PartnerName'] == 'North American Title Insurance Company' || $resPartners['Partners'][$key]['PartnerName'] == 'Westcor Land Title Insurance Company' || $resPartners['Partners'][$key]['PartnerName'] == 'Commonwealth Land Title Insurance Company') {
                        $pdfData['underwriter'] = $resPartners['Partners'][$key]['PartnerName'];
                    } else {
                        $pdfData['underwriter'] = 'Westcor Land Title Insurance Company';
                    }
                }
                $pdfData['proposed_branch_id'] = $orderDetails['proposed_branch_id'];

                if (!empty($orderDetails['proposed_branch_id'])) {
                    $branchDetails = $this->order->getProposedBranchDetail($orderDetails['proposed_branch_id']);
                    $pdfData['branch_address'] = $branchDetails['address'];
                    $pdfData['branch_city'] = $branchDetails['city'];
                    $pdfData['branch_state'] = $branchDetails['state'];
                    $pdfData['branch_zip'] = $branchDetails['zip'];
                }

                $html = $this->load->view('order/proposed_insured_pdf', $pdfData, true);
                $this->load->library('m_pdf');
                $this->m_pdf->pdf->WriteHTML($html);
                $this->load->model('order/document');
                $proposedDocumentCount = $this->document->countProposedInsuredDocument($orderDetails['order_id']);
                $document_name = "proposed_" . $proposedDocumentCount . "_" . $fileId . ".pdf";

                if (!is_dir('uploads/proposed-insured')) {
                    mkdir('./uploads/proposed-insured', 0777, true);
                }

                $pdfFilePath = './uploads/proposed-insured/' . $document_name;
                $this->m_pdf->pdf->Output($pdfFilePath, 'F');
                $contents = file_get_contents($pdfFilePath);
                $binaryData = base64_encode($contents);
                $this->home_model->update(array('proposed_insured_document_name' => $document_name), array('file_id' => $fileId), 'order_details');

                $this->order->uploadProposedDocumentToResware($document_name, $orderDetails, $binaryData);
                $this->order->uploadDocumentOnAwsS3($document_name, 'proposed-insured');
                if (!empty($userdata) && $userdata['id'] == $orderDetails['title_officer']) {
                    $message = 'Proposed Insured document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['customer_id'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
                } else if (!empty($userdata) && $userdata['id'] == $orderDetails['customer_id']) {
                    $message = 'Proposed Insured document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['title_officer'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
                } else {
                    $message = 'Proposed Insured document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['title_officer'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['customer_id'],
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
                }
                /*$fileSize = filesize('./uploads/proposed-insured/'.$document_name);
                $documentData = array(
                'document_name' => $document_name,
                'original_document_name' => $document_name,
                'document_type_id' => 1031,
                'document_size' => $fileSize,
                'user_id' => $userdata['id'],
                'order_id' => $orderDetails['order_id'],
                'description' => 'Proposed Insured Document',
                'is_sync' => 0,
                'is_prelim_document' => 0,
                'is_proposed_insured_doc' => 1
                );
                $documentId = $this->document->insert($documentData);*/
                $data = array('status' => 'success', 'data' => $binaryData);
            } else {
                $data = array('status' => 'error');
            }
        } else {
            $data = array('status' => 'error');
        }
        echo json_encode($data);exit;
    }

    public function proposed_insured()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $condition = array(
            'where' => array(
                'status' => 1,
            ),
        );

        $data['titleOfficer'] = $this->titleOfficer->getTitleOfficerDetails($condition);
        $data['proposedBranches'] = $this->order->getProposedBranches();
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        // $this->template->addJS( base_url('assets/frontend/js/order/proposed.js?v='.$this->version));
        // $this->template->show("order", "proposed_insured", $data);
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/proposed.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order", "proposed_insured", $data);
    }

    public function get_proposed_orders()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
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
            $order_lists = $this->order->get_orders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_orders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $order['file_number'];
                $nestedData[] = $order['full_address'];
                // $nestedData[] = !empty($order['proposed_document_created_date']) ? date("m/d/Y", strtotime($order['proposed_document_created_date'])) : '';
                $nestedData[] = !empty($order['proposed_document_created_date']) ? convertTimezone($order['proposed_document_created_date'], 'm/d/Y') : '';
                if (!empty($order['proposed_insured_document_name'])) {
                    $file_id = $order['file_id'];
                    $documentName = $order['proposed_insured_document_name'];
                    if (env('AWS_ENABLE_FLAG') == 1) {
                        $documentUrl = env('AWS_PATH') . "proposed-insured/" . $documentName;
                        $action = "<a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"proposed_insured"' . ");'><i class='fas fa-download' aria-hidden='true'></i></a>";
                        $action = '<div style="display:flex;justify-content: space-around;" ><a href="' . $documentUrl . '" onclick="downloadDocumentFromAws(' . $documentUrl . ',' . 'proposed_insured' . ');" type="button" title="Download" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-download"></i></span><span class="text">Download</span></a>';
                    } else {
                        $documentUrl = FCPATH . 'uploads/proposed-insured/' . $documentName;
                        // $action = '<a href="'.$documentUrl.'" download><i class="fas fa-download" aria-hidden="true"></i></a>';
                        $action = '<div style="display:flex;justify-content: space-around;" ><a href="' . $documentUrl . '" download type="button" title="Download" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-download"></i></span><span class="text">Download</span></a>';
                    }
                } else {
                    $action = '<div style="display:flex;justify-content: space-around;" ><a href="javascript:void(0);" onclick="generateProposedInsured(' . $order['file_id'] . ');" type="button" title="Generate" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-seedling"></i></span><span class="text">Generate</span></a>';
                }
                $action .= '<a href="javascript:void(0);" onclick="editInformation(' . $order['file_id'] . ');" class="btn btn-primary btn-icon-split" ><span class="icon text-white-50"><i class="fas fa-edit"></i></span><span class="text">Edit</span></a></div>';

                $nestedData[] = $action;

                $data[] = $nestedData;
                $i++;
            }
        }

        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function get_orders_prelim()
    {
        if (empty($this->session->userdata('user'))) {
            redirect(base_url() . 'order');
        }
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
            $order_lists = $this->order->get_orders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_orders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {

                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $order['file_number'];
                $nestedData[] = $order['full_address'];

                if ($order['prelim_summary_id'] != 0) {
                    $class = isset($order['is_visited']) && !empty($order['is_visited']) ? 'secondary' : 'success';
                    $nestedData[] = "<a href='" . base_url() . "review-file/" . $order['file_id'] . "'>
							<button type='submit' class='btn btn-$class btn-icon-split'>
								<span class='icon text-white-50'>
									<i class='fas fa-file'></i>
								</span>
								<span class='text'>Review File</span>
							</button>
						</a>";
                } else {
                    $nestedData[] = "<a href='javascript:void(0)'>
						<button type='submit' class='btn btn-info btn-icon-split'>
							<span class='icon text-white-50'>
								<i class='fas fa-tasks'></i>
							</span>
							<span class='text'>Not Ready</span>
						</button></a>";
                }

                $data[] = $nestedData;
                $i++;
            }
        }

        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function markAsRead()
    {
        $userdata = $this->session->userdata('user');
        $condition = array(
            'sent_user_id' => $userdata['id'],
        );
        $data = array(
            'is_read' => 1,
        );
        $this->home_model->update($data, $condition, 'pct_order_notifications');
        $response = array(
            'success' => 'true',
            'message' => 'Notifcation marked as read.',
        );
        echo json_encode($response);
    }

    public function get_notes($fileId)
    {
        //echo $fileId;exit;
        $userdata = $this->session->userdata('user');
        $this->load->model('admin/escrow/tasks_model');
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $orderDetails = $this->order->get_order_details($fileId);
        $orderId = isset($orderDetails['order_id']) && !empty($orderDetails['order_id']) ? $orderDetails['order_id'] : '';
        $data['orderDetails'] = $orderDetails;
        $prod_type = $orderDetails['prod_type'];

        if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
            $data['tasks'] = $this->tasks_model->get_many_by("(status = 1 and parent_task_id = 0 and (prod_type = 'both' or prod_type = '$prod_type') )");
            $data['notes'] = $this->order->get_order_notes($orderId, $userdata['id']);
        } else {
            $data['tasks'] = array();
            $data['notes'] = $this->order->get_order_notes($orderId);
        }

        // $this->template->addJS( base_url('assets/frontend/js/order/notes_js.js?v='.$this->version) );
        $this->escrowdashboardtemplate->addJS(base_url('assets/frontend/js/order/notes_js.js?v=' . $this->version));
        $this->escrowdashboardtemplate->show("order/common", "get_notes", $data);
        // $this->template->show("order/common", "get_notes", $data);
    }

    public function create_note()
    {
        $fileId = isset($_POST['fileId']) && !empty($_POST['fileId']) ? $_POST['fileId'] : '';
        $errors = array();
        $success = array();
        $this->load->model('order/note');
        if (isset($fileId) && !empty($fileId)) {
            $userdata = $this->session->userdata('user');
            $subject = isset($_POST['subject']) && !empty($_POST['subject']) ? $_POST['subject'] : '';
            $body = isset($_POST['body']) && !empty($_POST['body']) ? $_POST['body'] : '';
            $orderDetails = $this->order->get_order_details($fileId);
            $orderId = isset($orderDetails['order_id']) && !empty($orderDetails['order_id']) ? $orderDetails['order_id'] : '';
            $this->load->library('order/resware');
            $request = array();
            $endPoint = 'files/' . $fileId . '/notes';
            $request['Subject'] = $subject;
            $request['Body'] = $body;
            $request['FileID'] = $fileId;
            $notes_data = json_encode($request);
            $user_data = array();

            if ($userdata['is_title_officer'] == 1 || $userdata['is_master'] == 1 || $userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                $user_data['admin_api'] = 1;
            }

            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, array(), $orderId, 0);
            $result = $this->resware->make_request('POST', $endPoint, $notes_data, $user_data);
            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, $result, $orderId, $logid);

            if (isset($result) && !empty($result)) {
                $response = json_decode($result, true);

                if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                    $message = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';
                    $errors[] = $message;
                } else {
                    $noteId = isset($response['Note']['NoteID']) && !empty($response['Note']['NoteID']) ? $response['Note']['NoteID'] : '';
                    $notesData = array(
                        'resware_note_id' => $noteId,
                        'subject' => $subject,
                        'note' => $body,
                        'user_id' => $userdata['id'],
                        'order_id' => $orderId,
                        'task_id' => isset($_POST['task_id']) ? $_POST['task_id'] : 0,
                    );
                    $id = $this->note->insert($notesData);
                    if ($noteId && $id) {
                        $success[] = 'Note created successfully.';
                    } else {
                        $errors[] = 'Something went wrong. Please try again.';
                    }
                }
            }

            $data = array(
                "errors" => $errors,
                "success" => $success,
            );
            $this->session->set_userdata($data);
            redirect(base_url() . 'get-notes/' . $fileId);
        }
    }

    public function update_commisssion_calculation()
    {
        //Get SalesRep whose order close on current month

        $for_month = date('m');
        $for_year = date('Y');
        $table = 'transaction_details';
        $this->db->select('sales_representative');
        $this->db->from($table);
        $this->db->join('order_details', 'order_details.transaction_id = transaction_details.id');
        $this->db->join('customer_basic_details', 'customer_basic_details.id = transaction_details.sales_representative');
        $this->db->where('MONTH(sent_to_accounting_date)', $for_month);
        $this->db->where('YEAR(sent_to_accounting_date)', $for_year);
        $this->db->group_by('sales_representative');
        $query = $this->db->get();
        $result = $query->result();

        $this->load->model('admin/order/customer_basic_details_model');

        foreach ($result as $record) {
            $sales_rep_id = $record->sales_representative;
            $stored_pocedure = "CALL calculate_commission(?)";
            $this->customer_basic_details_model->call_sp($stored_pocedure, array('id' => $sales_rep_id));
        }

    }

    public function update_commisssion_calculation_dup($for_month = 0, $for_year = 0)
    {
        //Get SalesRep whose order close on current month
        if (!($for_month >= 1 && $for_month <= 12)) {
            $for_month = date('m');
        } elseif (!($for_month >= 2022)) {
            $for_year = date('Y');
        }
        $table = 'transaction_details';
        $this->db->select('sales_representative');
        $this->db->from($table);
        $this->db->join('order_details', 'order_details.transaction_id = transaction_details.id');
        $this->db->join('customer_basic_details', 'customer_basic_details.id = transaction_details.sales_representative');
        $this->db->where('MONTH(sent_to_accounting_date)', $for_month);
        $this->db->where('YEAR(sent_to_accounting_date)', $for_year);
        $this->db->group_by('sales_representative');
        $query = $this->db->get();
        $result = $query->result();

        $this->load->model('admin/order/customer_basic_details_model');

        foreach ($result as $record) {
            $sales_rep_id = $record->sales_representative;
            $stored_pocedure = 'CALL calculate_commission_common(?,?,?)';
            $this->customer_basic_details_model->call_sp($stored_pocedure, array('id' => $sales_rep_id, 'for_year' => $for_year, 'for_month' => $for_month));
        }

    }

    public function surveysResult()
    {
        $data = array();
        $data['title'] = 'PCT Order: Surveys';
        
        $this->load->library('order/survey');
        $this->load->model('order/apiLogs');
        $endPoint = 'surveys';
        // $userdata['email'] = $userdata['email_address'];
        // $logid = $this->apiLogs->syncLogs($userdata['id'], 'survey', 'get_survey', env('SURVEYMONKEY_API_URL') . $endPoint, array(), array(), 0, 0);
        $result = $this->survey->make_request('GET', $endPoint, array(), $userdata);
        // $this->apiLogs->syncLogs($v['id'], 'survey', 'get_survey', env('SURVEYMONKEY_API_URL') . $endPoint, array(), $result, 0, $logid);
        $survey = [];
        $titleOfficerList = [];

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            // echo "<pre>";
            // print_r($response);die;
            if (isset($response['data']) && !empty($response['data'])) {
                foreach ($response['data'] as $key => $value) {
                    $arr = [];
                    $arr['id'] = $value['id'];
                    $arr['title'] = $value['title'];
                    $arr['nickname'] = $value['nickname'];
                    $arr['href'] = $value['href'];
                    $titleOfficerList[] = $arr;
                    // break;
                }
            }
        }
        $ratingData = [];
        if (!empty($titleOfficerList)) {
            // $titleOffSurveyId = "417131721"; 
            $titleOffSurveyId = $titleOfficerList['0']['id']; 
            $endPoint = 'surveys/' . $titleOffSurveyId . '/responses/bulk';
            $result = $this->survey->make_request('GET', $endPoint, array(), $userdata);
            if (isset($result) && !empty($result)) {
                $response = json_decode($result, true);
                $questionAverages = [];
                $textComment = [];
                $ratingArray = [];
                // echo "<pre>";
                if (isset($response['data'])) {
                    foreach ($response['data'] as $res) {
                        $ratingArr = [];
                        $ratingArr['sales_rep'] = '-';
                        if (isset($res['custom_variables']) && !empty($res['custom_variables'])) {
                            $orderId = $res['custom_variables']['order_id'];
                            $salesRepDetails = $this->order->getSalesRepForOrder($orderId);
                            // echo "<pre>";
                            // print_r($salesRepDetails);die;
                            $ratingArr['sales_rep'] = $salesRepDetails['first_name'] . ' ' . $salesRepDetails['last_name'];
                        }
                        $ratingData['titleOfficer'] = $titleOfficerList['0']['title'];
                        foreach ($res['pages'] as $page) {
                            foreach ($page['questions'] as $key => $question) {
                                $questionId = $question['id'];
                                foreach ($question['answers'] as $answer) {
                                    if (isset($answer['choice_metadata']['weight'])) {
                                        $ratingArr[$questionId] = (int)$answer['choice_metadata']['weight'];
                                        // $ratingArr['Q'.($key+1)] = (int)$answer['choice_metadata']['weight'];
                                        $questionAverages[$questionId][] = (int)$answer['choice_metadata']['weight'];
                                    }
                                    if (isset($answer['text']) && !empty($answer['text'])) {
                                        $ratingArr['comment'] = $answer['text'];
                                        $textComment[] = $answer['text'];
                                    }
                                }
                            }
                        }
                        $ratingArray[] = $ratingArr;
                    }
                    // Calculate average for each question
                    $finalAverages = [];
                    $i = 1;
                    foreach ($questionAverages as $questionId => $weights) {
                        $finalAverages['Q'.$i] = number_format(array_sum($weights) / count($weights), 2);
                        $i++;
                    }
                    $ratingData['rating'] = $ratingArray;
                    // print_r($page);die;
                    $ratingData['avg'] = $finalAverages;
                    $ratingData['textComment'] = $textComment;
                    $survey['survey_cards'] = $this->surveyReportCards($ratingData);
                    $survey['survey_rating_details'] = $this->surveyReportRating($ratingData);
                    $survey['title_officer_list'] = $titleOfficerList;
                }
            }
        }
        
        // echo "<pre>";
        // print_r($ratingData);
        // print_r($survey);die;
        // $data['survey'] = $survey;
        $this->salesdashboardtemplate->addCSS(base_url('assets/frontend/css/smart-forms.css?v=' . $this->version));
        $this->salesdashboardtemplate->addCss(base_url('assets/frontend/css/sales-dashboard.css?v=' . $this->version));
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/sales_dashboard.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order/common", "survey_result", $survey);
    }

    public function getSurveyDetails() {
        $titleOffSurveyId = $this->input->post('title_officer_survey_id');
        $titleOffSurveyName = $this->input->post('title_officer_survey_name');
        if (!empty($titleOffSurveyId)) {
            $this->load->library('order/survey');
            $this->load->model('order/apiLogs');
            $endPoint = 'surveys/' . $titleOffSurveyId . '/responses/bulk';
            $result = $this->survey->make_request('GET', $endPoint, array(), $userdata);
            if (isset($result) && !empty($result)) {
                $response = json_decode($result, true);
                $questionAverages = [];
                $textComment = [];
                $ratingArray = [];
                if (isset($response['data'])) {
                    foreach ($response['data'] as $res) {
                        $ratingArr = [];
                        $ratingArr['sales_rep'] = '-';
                        if (isset($res['custom_variables']) && !empty($res['custom_variables'])) {
                            $orderId = $res['custom_variables']['order_id'];
                            $salesRepDetails = $this->order->getSalesRepForOrder($orderId);
                            // echo "<pre>";
                            // print_r($salesRepDetails);die;
                            $ratingArr['sales_rep'] = $salesRepDetails['first_name'] . ' ' . $salesRepDetails['last_name'];
                        }
                        $ratingData['titleOfficer'] = $titleOffSurveyName;
                        foreach ($res['pages'] as $page) {
                            foreach ($page['questions'] as $key => $question) {
                                $questionId = $question['id'];
                                foreach ($question['answers'] as $answer) {
                                    if (isset($answer['choice_metadata']['weight'])) {
                                        // $ratingArr['Q'.($key+1)] = (int)$answer['choice_metadata']['weight'];
                                        $ratingArr[$questionId] = (int)$answer['choice_metadata']['weight'];
                                        $questionAverages[$questionId][] = (int)$answer['choice_metadata']['weight'];
                                    }
                                    if (isset($answer['text']) && !empty($answer['text'])) {
                                        $ratingArr['comment'] = $answer['text'];
                                        $textComment[] = $answer['text'];
                                    }
                                }
                            }
                        }
                        $ratingArray[] = $ratingArr;
                    }
                    // Calculate average for each question
                    $finalAverages = [];
                    $i = 1;
                    foreach ($questionAverages as $questionId => $weights) {
                        $finalAverages['Q'.$i] = number_format(array_sum($weights) / count($weights), 2);
                        $i++;
                    }
                    $ratingData['rating'] = $ratingArray;
                    // print_r($page);die;
                    $ratingData['avg'] = $finalAverages;
                    $ratingData['textComment'] = $textComment;
                    $survey['survey_cards'] = $this->surveyReportCards($ratingData);
                    $survey['survey_rating_details'] = $this->surveyReportRating($ratingData);
                    // $survey['title_officer_list'] = $titleOfficerList;
                }
            }
        }
        if (!empty($survey)){
            $res = array('status' => 'success', 'survey' => $survey);
        } else {
            $res = array('status' => 'error', 'msg' => "Please select file.");
        }
        echo json_encode($res);exit;
    }

    public function surveyReportCards($data) {
        // echo "<pre>";
        // print_r($this->salesdashboardtemplate->show("order/common/survey", "survey_report_cards", ['value' => $data]));die;
        // $results = $this->load->view('order/review_file_summary', $data, true);
        return $this->load->view('order/common/survey/survey_report_cards', $data, true);
        // echo $this->salesdashboardtemplate->show("order/common/survey", "survey_report_cards", ['value' => $data]);
    }

    public function surveyReportRating($data) {
        return $this->load->view('order/common/survey/survey_report_rating_details', $data, true);
    }
}
