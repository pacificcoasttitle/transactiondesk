<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class Escrow extends MX_Controller
{
    // private $escrow_js_version = '03';
    private $version;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->version = strtotime(date('Y-m-d'));
        $this->load->library('order/EscrowDashboardTemplate');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('order/template');
        $this->load->library('order/order');
        $this->load->model('order/apiLogs');
        $this->load->model('order/home_model');
        $this->load->library('order/resware');
        $this->load->library('order/common');
        $this->common->is_escrow_user();
    }

    public function index()
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

        $userdata = $this->session->userdata('user');
        $name = isset($userdata['name']) && !empty($userdata['name']) ? $userdata['name'] : '';
        $data['name'] = $name;
        $data['user_email'] = $userdata['email'];
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        // $this->template->addJS( base_url('assets/frontend/js/order/escrow.js?v='.$this->version));

        $this->escrowdashboardtemplate->addJS(base_url('assets/frontend/js/order/escrow.js?v=' . $this->version));
        // $this->escrowdashboardtemplate->addCss( base_url('assets/frontend/css/sales-dashboard.css?v='.$this->sales_dashboard_js_version) );

        $this->escrowdashboardtemplate->show("order/escrow", "dashboard", $data);
        // $this->template->show("order/escrow", "dashboard", $data);
    }

    public function get_escrow_orders()
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
            $order_lists = $this->order->getEscrowOrders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->getEscrowOrders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;

            $this->load->model('admin/escrow/tasks_model');
            $this->load->model('admin/escrow/order_completed_tasks_model');

            foreach ($order_lists['data'] as $order) {
                $prod_type = $order['prod_type'];
                $total_tasks = $this->tasks_model->count_by("(status = 1 and (prod_type = 'both' or prod_type = '$prod_type') )");
                $total_task_completed = $this->order_completed_tasks_model->count_by('order_id', $order['id']);
                $task_complete_ratio = floor((100 * $total_task_completed) / $total_tasks);

                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $order['file_number'];
                $nestedData[] = $order['full_address'];
                $nestedData[] = $order['product_type'];
                $nestedData[] = date("m/d/Y", strtotime($order['created_at']));
                $nestedData[] = '<div class="percentage">' . $task_complete_ratio . '%</div>';
                $editUrl = base_url() . 'order/escrow/order-tasks/' . $order['id'];
                $nestedData[] = '<div style="display: flex;">
                                    <a href="' . base_url() . 'order/escrow/order-tasks/' . $order['id'] . '" title="Tasks" >
                                    <i class="fas fa-tasks" aria-hidden="true"></i>
                                    </a>
                                    <a style="margin-left:5px;" href="' . base_url() . 'get-notes/' . $order['file_id'] . '" title="Notes">
                                        <i class="fas fa-sticky-note"></i>
                                    </a>
                                    <a style="margin-left:5px;" href="' . base_url() . 'upload-documents/' . $order['file_id'] . '" title="Documents">
                                        <i class="fas fa-file"></i>
                                    </a>
                                </div>';
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function orderTasks($id)
    {
        $userdata = $this->session->userdata('user');
        $data['title'] = 'Escrow Order Tasks';
        $data['page_title'] = 'Order Tasks';
        $this->load->model('admin/escrow/tasks_model');
        $this->load->model('admin/escrow/order_model');
        $this->load->model('admin/escrow/order_completed_tasks_model');
        $this->load->model('admin/escrow/escrow_user_model');
        $this->load->model('admin/hr/escrow_instruction_model');
        $this->load->model('admin/hr/escrow_instruction_value_model');

        $data['escrow_instruction_list'] = $this->escrow_instruction_model->get_all();
        $data['escrow_instruction_value_list'] = $this->escrow_instruction_value_model->get_all();
        $this->load->model('admin/hr/users_model');

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

        $orderInfo = $this->order_model->get($id);
        $orderDetails = $this->order->get_order_details($orderInfo->file_id);
        $data['orderDetails'] = $orderDetails;
        $prod_type = $orderInfo->prod_type;
        if ($prod_type == 'loan') {
            $tasks = json_decode(json_encode($this->tasks_model->order_by('loan_position', 'asc')->get_many_by("(status = 1 and (prod_type = 'both' or prod_type = '$prod_type') )")), true);
        } else if ($prod_type == 'sale') {
            $tasks = json_decode(json_encode($this->tasks_model->order_by('sale_position', 'asc')->get_many_by("(status = 1 and (prod_type = 'both' or prod_type = '$prod_type') )")), true);
        }
        $completedTasksInfo = $this->order_completed_tasks_model->get_many_by("(order_id = $id)");
        if (!empty($completedTasksInfo)) {
            $completedTasks = json_decode(json_encode($completedTasksInfo), true);
            $completedTaskIds = array_column($completedTasks, 'task_id');
        } else {
            $completedTasks = array();
            $completedTaskIds = array();
        }
        if ($this->input->post()) {
            $completed_task_name = array();
            $incompleted_task_name = array();
            $orders_tasks_add = array();
            $task_done = $this->input->post('task_done');
            $message = '';

            foreach ($task_done as $task_id) {
                if (!(in_array($task_id, $completedTaskIds))) {
                    $order_tasks = array();
                    $order_tasks['task_id'] = $task_id;
                    $order_tasks['order_id'] = $id;
                    $order_tasks['completed_by'] = $userdata['id'];
                    $orders_tasks_add[] = $order_tasks;
                    $taskKey = array_search($task_id, array_column($tasks, 'id'));
                    $completed_task_name[] = $tasks[$taskKey]['name'];
                }
            }

            if (count($orders_tasks_add)) {
                $this->order_completed_tasks_model->insert_many($orders_tasks_add);
            }

            $delete_id_array = array();
            $incompleteTaskKey = '';
            if (!empty($completedTasks)) {
                foreach ($completedTasks as $task) {
                    if (!(in_array($task['task_id'], $task_done))) {
                        $delete_id_array[] = $task['id'];
                        $incompleteTaskKey = array_search($task['task_id'], array_column($tasks, 'id'));
                        $incompleted_task_name[] = $tasks[$incompleteTaskKey]['name'];
                    }
                }
            }

            if (count($delete_id_array)) {
                $this->order_completed_tasks_model->delete_many($delete_id_array);
            }

            if (!empty($completed_task_name)) {
                if (count($completed_task_name) > 1) {
                    $task_names = implode(', ', $completed_task_name);
                    $message .= $task_names . ' tasks of order #' . $orderInfo->file_number . ' have been marked as complete by ' . $userdata['name'] . '.<br>';
                } else {
                    $message .= $completed_task_name[0] . ' task of order #' . $orderInfo->file_number . ' has been marked as complete by ' . $userdata['name'] . '.<br>';
                }
            }

            if (!empty($incompleted_task_name)) {
                if (count($incompleted_task_name) > 1) {
                    $incompleted_task_names = implode(', ', $incompleted_task_name);
                    $message .= '<br>' . $incompleted_task_names . ' tasks of order #' . $orderInfo->file_number . ' have been marked as incomplete by ' . $userdata['name'] . '.';
                } else {
                    $message .= '<br>' . $incompleted_task_name[0] . ' task of order #' . $orderInfo->file_number . ' has been marked as incomplete by ' . $userdata['name'] . '.';
                }
            }

            if (!empty($orderInfo->escrow_officer_id)) {
                $escrowInfoFromOrder = $this->common->getEscrowOfficerInfoBasedOnIdFromOrder($orderInfo->escrow_officer_id);
                if ($userdata['is_escrow_officer'] == 1) {
                    $escrowInfo = $this->users_model->get_by(array('email' => $escrowInfoFromOrder['email_address']));
                    $assistantUsersInfo = json_decode(json_encode($this->escrow_user_model->get_many_by(array('branch_id' => $escrowInfo->branch_id, 'position_id' => 15))), true);
                    $assistantUserEmails = array_column($assistantUsersInfo, 'email');
                    $assistantOrderUsersInfo = $this->common->getAssistantUsers($assistantUserEmails);

                    foreach ($assistantOrderUsersInfo as $assistant) {
                        $notificationData = array(
                            'sent_user_id' => $assistant['id'],
                            'message' => $message,
                            'type' => 'completed',
                        );
                        $this->home_model->insert($notificationData, 'pct_order_notifications');
                        $this->order->sendNotification($message, 'completed', $assistant['id'], 0);
                    }
                } else {
                    $notificationData = array(
                        'sent_user_id' => $escrowInfoFromOrder['id'],
                        'message' => $message,
                        'type' => 'completed',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'completed', $escrowInfoFromOrder['id'], 0);
                }

                $managersInfo = $this->users_model->get_many_by(array('user_type_id' => 4, 'department_id' => 4));
                foreach ($managersInfo as $manager) {
                    $notificationData = array(
                        'sent_user_id' => $manager->id,
                        'message' => $message,
                        'type' => 'completed',
                    );
                    $this->home_model->insert($notificationData, 'pct_hr_notifications');
                    $this->order->sendNotification($message, 'completed', $manager->id, 1);
                }
            }
            $successMsg[] = 'Order task list updated for file number ' . $orderInfo->file_number;
            $this->session->set_userdata('success', $successMsg);
            redirect(base_url() . 'order/escrow/order-tasks/' . $id);
        }
        $data['tasks'] = $tasks;
        $data['completedTaskIds'] = $completedTaskIds;
        $data['order_task_notes'] = $this->order->get_order_notes($id);
        $data['borrowerDocuments'] = $this->order->getBorrowerDocuments($data['orderDetails']['order_id']);
        // $this->template->addCss( base_url('assets/frontend/css/escrow_tasks.css?v=05') );
        // $this->template->addJS( base_url('assets/plugins/ckeditor/ckeditor.js') );
        // $this->template->addJS( base_url('assets/frontend/js/escrow_tasks.js?v=09') );
        // $this->template->addJS( base_url('assets/frontend/js/jquery-cloneya.min.js') );
        $this->escrowdashboardtemplate->addCss(base_url('assets/frontend/css/escrow_tasks.css?v=' . $this->sales_dashboard_js_version));
        $this->escrowdashboardtemplate->addJS(base_url('assets/plugins/ckeditor/ckeditor.js?v=' . $this->version));
        $this->escrowdashboardtemplate->addJS(base_url('assets/frontend/js/escrow_tasks.js?v=' . $this->version));
        $this->escrowdashboardtemplate->addJS(base_url('assets/frontend/js/jquery-cloneya.min.js?v=' . $this->version));

        $this->escrowdashboardtemplate->show("order/escrow", "order_tasks", $data);
        // $this->template->show("order/escrow", "order_tasks", $data);
    }

    public function uploadBorrowerDocumentResware($document_id)
    {
        $userdata = $this->session->userdata('user');
        $this->load->model('order/document');
        $this->load->model('admin/escrow/tasks_model');
        $this->load->model('admin/escrow/order_model');
        $documentDetails = $this->order->getDocumentsDetails($document_id);
        $orderInfo = $this->order_model->get($documentDetails['order_id']);
        $endPoint = 'files/' . $orderInfo->file_id . '/documents';
        $documentApiData = array(
            'DocumentName' => $documentDetails['original_document_name'],
            'DocumentType' => array(
                'DocumentTypeID' => $documentDetails['document_type_id'],
            ),
            'Description' => 'Borrower Document',
            'InternalOnly' => false,
            'DocumentBody' => base64_encode(file_get_contents(env('AWS_PATH') . 'borrower/' . $documentDetails['document_name'])),
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $documentDetails['order_id'], 0);
        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $documentDetails['order_id'], $logid);
        $res = json_decode($result);

        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'upload_borrower_document_to_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => 'success',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */

        $taskInfo = $this->tasks_model->get($documentDetails['task_id']);
        if (!empty($res->Document->DocumentID)) {
            $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $document_id));
            $success[] = "Document " . $documentDetails['original_document_name'] . " of " . $taskInfo->name . " task uploaded successfully on Resware side.";
        } else {
            $errors[] = "Document " . $documentDetails['original_document_name'] . " of " . $taskInfo->name . " task didn't upload on Resware side due to some error. Please try again.";
        }
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'order/escrow/order-tasks/' . $documentDetails['order_id']);
    }

    public function create_note()
    {
        $user_data = array();
        $this->load->model('admin/escrow/tasks_model');
        $this->load->library('order/resware');

        $num_of_notes = isset($_POST['num_of_notes']) ? $_POST['num_of_notes'] : '';
        $userdata = $this->session->userdata('user');
        $subject = isset($_POST['subject']) && !empty($_POST['subject']) ? $_POST['subject'] : '';
        $note = isset($_POST['note']) && !empty($_POST['note']) ? $_POST['note'] : '';
        $order_id = isset($_POST['order_id']) && !empty($_POST['order_id']) ? $_POST['order_id'] : '';
        $file_id = isset($_POST['file_id']) && !empty($_POST['file_id']) ? $_POST['file_id'] : '';

        $request = array();
        $endPoint = 'files/' . $file_id . '/notes';
        $request['Subject'] = $subject;
        $request['Body'] = $note;
        $request['FileID'] = $file_id;
        $notes_data = json_encode($request);
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, array(), $order_id, 0);
        $result = $this->resware->make_request('POST', $endPoint, $notes_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, $result, $order_id, $logid);

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                $message = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';
                $response = array('status' => 'error', 'message' => $message, 'num_of_notes' => $num_of_notes);
            } else {
                $noteId = isset($response['Note']['NoteID']) && !empty($response['Note']['NoteID']) ? $response['Note']['NoteID'] : '';
                $notesData = array(
                    'resware_note_id' => $noteId,
                    'subject' => $subject,
                    'note' => $note,
                    'user_id' => $userdata['id'],
                    'order_id' => $order_id,
                    'task_id' => $_POST['task_id'],
                );
                $id = $this->home_model->insert($notesData, 'pct_order_notes');
                $taskInfo = $this->tasks_model->get($_POST['task_id']);
                if ($id) {
                    $success[] = 'Note created successfully for task ' . $taskInfo->name;
                    $response = array('status' => 'success');
                } else {
                    $error = 'Something went wrong. Please try again.';
                    $response = array('status' => 'error', 'message' => $error, 'num_of_notes' => $num_of_notes);
                }
            }
        }
        $data['success'] = $success;
        $data = array(
            "errors" => array(),
            "success" => $success,
        );
        $this->session->set_userdata($data);
        echo json_encode($response);
    }

    public function addBorrowerOnOrder()
    {
        $userdata = $this->session->userdata('user');
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $borrower_email = $this->input->post('borrower_email');
        $package_type = $this->input->post('package_type');
        $orderDetails = $this->order->get_order_details($file_id);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $errors = array();
        $success = array();

        $this->home_model->update(array('borrower_email' => $borrower_email), array('file_id' => $file_id), 'order_details');

        if ($package_type == 'seller') {
            $form_url = base_url() . 'borrower-seller-form/' . $orderDetails['random_number'];
            $subject = $orderDetails['file_number'] . ' - Seller: Required Info Needed';
        } else {
            $form_url = base_url() . 'borrower-buyer-form/' . $orderDetails['random_number'];
            $subject = $orderDetails['file_number'] . ' - Buyer: Required Info Needed';
        }

        $email_data = array(
            'file_number' => $orderDetails['file_number'],
            'property_address' => $orderDetails['full_address'],
            'random_number' => $orderDetails['random_number'],
            'borrrower' => $orderDetails['primary_owner'],
            'form_url' => $form_url,
            'escrow_officer' => $userdata['name'],
            'is_seller_flag' => $package_type == 'seller' ? 1 : 0,
        );

        if ($package_type == 'seller') {
            $borrower_message_body = $this->load->view('emails/borrower_seller.php', $email_data, true);
        } else {
            $borrower_message_body = $this->load->view('emails/borrower_buyer.php', $email_data, true);
        }
        $message_body = $borrower_message_body;

        $mailParams = array(
            'from_mail' => $from_mail,
            'from_name' => $from_name,
            'subject' => $subject,
            'message' => json_encode($email_data),
        );

        if (!empty($borrower_email)) {
            $to = $borrower_email;
            $mailParams['to'] = $to;
            $this->load->helper('sendemail');
            $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_borrower', '', $mailParams, array(), $order_id, 0);
            $borrower_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
            $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_borrower', '', $mailParams, array('status' => $borrower_mail_result), $order_id, $logid);
        }

        if ($orderDetails['resware_status'] == 'closed') {
            $param = $file_id;
            $command = "php " . FCPATH . "index.php frontend/order/cron sendDataToHomeDocs $param";
            if (substr(php_uname(), 0, 7) == "Windows") {
                pclose(popen("start /B " . $command, "r"));
            } else {
                exec($command . " > /dev/null &");
            }
        }

        $success[] = "Mail sent succesfully to borrower";
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'order/escrow/order-tasks/' . $orderDetails['order_id']);
    }

    public function addBorrowerOnOrderForPayoff()
    {
        $userdata = $this->session->userdata('user');
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $borrower_email = $this->input->post('borrower_email');
        $orderDetails = $this->order->get_order_details($file_id);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $errors = array();
        $success = array();

        //$this->home_model->update(array('borrower_email' => $borrower_email), array('file_id' => $file_id), 'order_details');
        $form_url = base_url() . 'borrower-document/' . $orderDetails['random_number'];

        $email_data = array(
            'file_number' => $orderDetails['file_number'],
            'property_address' => $orderDetails['full_address'],
            'random_number' => $orderDetails['random_number'],
            'borrrower' => $orderDetails['primary_owner'],
            'form_url' => $form_url,
            'escrow_officer' => $userdata['name'],
        );

        $borrower_message_body = $this->load->view('emails/borrower_buyer_seller.php', $email_data, true);
        $message_body = $borrower_message_body;
        $subject = $orderDetails['file_number'] . ' - Borrower';

        $mailParams = array(
            'from_mail' => $from_mail,
            'from_name' => $from_name,
            'subject' => $subject,
            'message' => json_encode($email_data),
        );

        if (!empty($borrower_email)) {
            $to = $borrower_email;
            $mailParams['to'] = $to;
            $this->load->helper('sendemail');
            $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_borrower_payoff', '', $mailParams, array(), $order_id, 0);
            $borrower_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
            $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_borrower_payoff', '', $mailParams, array('status' => $borrower_mail_result), $order_id, $logid);
        }

        $success[] = "Mail sent succesfully to borrower for order payoff";
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'order/escrow/order-tasks/' . $orderDetails['order_id']);
    }

    public function addLenderOnOrder()
    {
        $userdata = $this->session->userdata('user');
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $lender_email = $this->input->post('lender_email');
        $orderDetails = $this->order->get_order_details($file_id);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $errors = array();
        $success = array();

        //$this->home_model->update(array('borrower_email' => $borrower_email), array('file_id' => $file_id), 'order_details');
        $form_url = base_url() . 'borrower-document/' . $orderDetails['random_number'];

        $email_data = array(
            'file_number' => $orderDetails['file_number'],
            'property_address' => $orderDetails['full_address'],
            'random_number' => $orderDetails['random_number'],
            'borrrower' => $orderDetails['primary_owner'],
            'form_url' => $form_url,
            'escrow_officer' => $userdata['name'],
        );

        $borrower_message_body = $this->load->view('emails/borrower_buyer_seller.php', $email_data, true);
        $message_body = $borrower_message_body;
        $subject = $orderDetails['file_number'] . ' - Lender';

        $mailParams = array(
            'from_mail' => $from_mail,
            'from_name' => $from_name,
            'subject' => $subject,
            'message' => json_encode($email_data),
        );

        if (!empty($lender_email)) {
            $to = $lender_email;
            $mailParams['to'] = $to;
            $this->load->helper('sendemail');
            $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_lender', '', $mailParams, array(), $order_id, 0);
            $borrower_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
            $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_lender', '', $mailParams, array('status' => $borrower_mail_result), $order_id, $logid);
        }

        $success[] = "Mail sent succesfully to lender.";
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'order/escrow/order-tasks/' . $orderDetails['order_id']);
    }

    public function taskDocuments()
    {
        $this->load->model('admin/escrow/tasks_model');
        $userdata = $this->session->userdata('user');
        $this->load->model('order/document');
        $success = array();
        $errors = array();
        $documentNames = array();
        $config['upload_path'] = './uploads/borrower/';
        $config['allowed_types'] = 'doc|docx|gif|msg|pdf|tif|tiff|xls|xlsx|xml';
        $config['max_size'] = 18000;
        $this->load->library('upload', $config);

        if (!is_dir('uploads/borrower')) {
            mkdir('./uploads/borrower', 0777, true);
        }

        if (!empty($_FILES['files']['name'])) {
            $taskInfo = $this->tasks_model->get($this->input->post('task_id'));
            foreach ($_FILES['files']['name'] as $key => $file) {
                $_FILES['file']['name'] = $_FILES['files']['name'][$key];
                $_FILES['file']['type'] = $_FILES['files']['type'][$key];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$key];
                $_FILES['file']['error'] = $_FILES['files']['error'][$key];
                $_FILES['file']['size'] = $_FILES['files']['size'][$key];

                if (!$this->upload->do_upload('file')) {
                    $errors[] = $this->upload->display_errors();
                } else {
                    $data = $this->upload->data();
                    $document_name = date('YmdHis') . "_" . $data['file_name'];
                    rename(FCPATH . "/uploads/borrower/" . $data['file_name'], FCPATH . "/uploads/borrower/" . $document_name);
                    $this->order->uploadDocumentOnAwsS3($document_name, 'borrower');

                    $documentData = array(
                        'document_name' => $document_name,
                        'original_document_name' => $data['file_name'],
                        'document_type_id' => 1041,
                        'document_size' => ($data['file_size'] * 1000),
                        'user_id' => $userdata['id'],
                        'order_id' => $this->input->post('order_id'),
                        'task_id' => $this->input->post('task_id'),
                        'description' => 'Borrower Document',
                        'is_sync' => 1,
                        'is_uploaded_by_borrower' => 1,
                    );
                    $this->document->insert($documentData);
                    $success[] = $data['file_name'] . " uploaded successfully for " . $taskInfo->name . " task";
                    $documentNames[] = $data['file_name'];
                }
            }
        }

        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        $response = array('status' => 'success');
        echo json_encode($response);
    }

    public function addBuyerOnOrder()
    {
        $userdata = $this->session->userdata('user');
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $buyer_emails = $this->input->post('buyer_emails');
        $buyer_first_names = $this->input->post('buyer_first_names');
        $buyer_last_names = $this->input->post('buyer_last_names');
        $is_main_buyer = $this->input->post('is_main_buyer');
        $orderDetails = $this->order->get_order_details($file_id);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $errors = array();
        $success = array();
        $i = 0;

        $this->db->delete('pct_order_borrower_buyer_info', array('order_id' => $order_id));

        foreach ($buyer_emails as $buyerEmail) {
            $is_main_buyer_flag = ((str_replace("is_main_buyer", "", $is_main_buyer)) == $i) ? 1 : 0;
            $buyer_email = $buyerEmail;
            $buyerInfo = array(
                'order_id' => $order_id,
                'first_name' => $buyer_first_names[$i],
                'last_name' => $buyer_last_names[$i],
                'email' => $buyerEmail,
                'is_main_buyer' => $is_main_buyer_flag,
            );
            $this->home_model->insert($buyerInfo, 'pct_order_borrower_buyer_info');

            $form_url = base_url() . 'buyer-info/' . $orderDetails['random_number'];
            $email_data = array(
                'name' => $buyer_first_names[$i] . " " . $buyer_last_names[$i],
                'buyer_first_names' => $buyer_first_names,
                'buyer_last_names' => $buyer_last_names,
                'file_number' => $orderDetails['file_number'],
                'property_address' => $orderDetails['full_address'],
                'random_number' => $orderDetails['random_number'],
                'borrrower' => $orderDetails['primary_owner'],
                'form_url' => $form_url,
                'escrow_officer' => $userdata['name'],
            );

            $borrower_message_body = $this->load->view('emails/welcome_buyer.php', $email_data, true);
            $message_body = $borrower_message_body;
            $subject = $orderDetails['file_number'] . ' - ' . $orderDetails['full_address'] . ' - Welcome to Escrow';

            $mailParams = array(
                'from_mail' => $from_mail,
                'from_name' => $from_name,
                'subject' => $subject,
                'message' => json_encode($email_data),
            );

            if (!empty($buyer_email)) {
                $to = $buyer_email;
                $mailParams['to'] = $to;
                $this->load->helper('sendemail');
                $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_buyer', '', $mailParams, array(), $order_id, 0);
                $buyer_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
                $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_buyer', '', $mailParams, array('status' => $buyer_mail_result), $order_id, $logid);
            }
            $i++;
        }

        $request = array();
        $endPoint = 'files/' . $file_id . '/notes';
        $request['Subject'] = 'Buyer Welcome Email';
        $request['Body'] = 'Buyers welcome email sent to successfully to ' . implode(', ', $buyer_emails) . " users.";
        $request['FileID'] = $file_id;
        $notes_data = json_encode($request);
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, array(), $order_id, 0);
        $result = $this->resware->make_request('POST', $endPoint, $notes_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, $result, $order_id, $logid);

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                $message = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';

            } else {
                $noteId = isset($response['Note']['NoteID']) && !empty($response['Note']['NoteID']) ? $response['Note']['NoteID'] : '';
                $notesData = array(
                    'resware_note_id' => $noteId,
                    'subject' => $request['Subject'],
                    'note' => $request['Body'],
                    'user_id' => $userdata['id'],
                    'order_id' => $order_id,
                    'task_id' => 4,
                );
                $this->home_model->insert($notesData, 'pct_order_notes');
            }
        }

        $success[] = "Mail sent succesfully to buyers.";
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'order/escrow/order-tasks/' . $orderDetails['order_id']);
    }

    public function addSellerOnOrder()
    {
        $userdata = $this->session->userdata('user');
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $seller_emails = $this->input->post('seller_emails');
        $seller_first_names = $this->input->post('seller_first_names');
        $seller_last_names = $this->input->post('seller_last_names');
        $is_main_seller = $this->input->post('is_main_seller');
        $orderDetails = $this->order->get_order_details($file_id);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $errors = array();
        $success = array();
        $i = 0;

        $this->db->delete('pct_order_borrower_seller_info', array('order_id' => $order_id));

        foreach ($seller_emails as $sellerEmail) {
            $is_main_seller_flag = ((str_replace("is_main_buyer", "", $is_main_seller)) == $i) ? 1 : 0;
            $seller_email = $sellerEmail;
            $sellerInfo = array(
                'order_id' => $order_id,
                'first_name' => $seller_first_names[$i],
                'last_name' => $seller_last_names[$i],
                'email' => $sellerEmail,
                'is_main_seller' => $is_main_seller_flag,
            );
            $this->home_model->insert($sellerInfo, 'pct_order_borrower_seller_info');

            $form_url = base_url() . 'seller-info/' . $orderDetails['random_number'];
            $email_data = array(
                'name' => $seller_first_names[$i] . " " . $seller_last_names[$i],
                'seller_first_names' => $seller_first_names,
                'seller_last_names' => $seller_last_names,
                'file_number' => $orderDetails['file_number'],
                'property_address' => $orderDetails['full_address'],
                'random_number' => $orderDetails['random_number'],
                'borrrower' => $orderDetails['primary_owner'],
                'form_url' => $form_url,
                'escrow_officer' => $userdata['name'],
            );

            $borrower_message_body = $this->load->view('emails/welcome_seller.php', $email_data, true);
            $message_body = $borrower_message_body;
            $subject = $orderDetails['file_number'] . ' - ' . $orderDetails['full_address'] . ' - Welcome to Escrow';

            $mailParams = array(
                'from_mail' => $from_mail,
                'from_name' => $from_name,
                'subject' => $subject,
                'message' => json_encode($email_data),
            );

            if (!empty($seller_email)) {
                $to = $seller_email;
                $mailParams['to'] = $to;
                $this->load->helper('sendemail');
                $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_seller', '', $mailParams, array(), $order_id, 0);
                $seller_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
                $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_seller', '', $mailParams, array('status' => $seller_mail_result), $order_id, $logid);
            }
            $i++;
        }

        $request = array();
        $endPoint = 'files/' . $file_id . '/notes';
        $request['Subject'] = 'Seller Welcome Email';
        $request['Body'] = 'Sellers welcome email sent to successfully to ' . implode(', ', $seller_emails) . " users.";
        $request['FileID'] = $file_id;
        $notes_data = json_encode($request);
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, array(), $order_id, 0);
        $result = $this->resware->make_request('POST', $endPoint, $notes_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, $result, $order_id, $logid);

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                $message = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';

            } else {
                $noteId = isset($response['Note']['NoteID']) && !empty($response['Note']['NoteID']) ? $response['Note']['NoteID'] : '';
                $notesData = array(
                    'resware_note_id' => $noteId,
                    'subject' => $request['Subject'],
                    'note' => $request['Body'],
                    'user_id' => $userdata['id'],
                    'order_id' => $order_id,
                    'task_id' => 4,
                );
                $this->home_model->insert($notesData, 'pct_order_notes');
            }
        }

        $success[] = "Mail sent succesfully to sellers.";
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'order/escrow/order-tasks/' . $orderDetails['order_id']);
    }

    public function addEscrowInsOrder()
    {
        $userdata = $this->session->userdata('user');
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $orderDetails = $this->order->get_order_details($file_id);
        $this->load->model('admin/order/escrow_instruction_model');
        $errors = array();
        $success = array();
        $escrow_instruction_list = $this->escrow_instruction_model->get_all();

        $filename = date('YmdHis') . "_" . $file_id . ".doc";
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=" . basename($filename));
        header("Content-Description: File Transfer");
        @readfile($filename);
        $content = '<html xmlns:v="urn:schemas-microsoft-com:vml" '
            . 'xmlns:o="urn:schemas-microsoft-com:office:office" '
            . 'xmlns:w="urn:schemas-microsoft-com:office:word" '
            . 'xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"= '
            . 'xmlns="http://www.w3.org/TR/REC-html40">'
            . '<head><meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">'
            . '<title></title>'
            . '<!--[if gte mso 9]>'
            . '<xml>'
            . '<w:WordDocument>'
            . '<w:View>Print'
            . '<w:Zoom>100'
            . '<w:DoNotOptimizeForBrowser/>'
            . '</w:WordDocument>'
            . '</xml>'
            . '<![endif]-->'
            . '<style>
                @page
                {
                    font-family: Arial;
                    size:215.9mm 279.4mm;  /* A4 */
                    margin:14.2mm 17.5mm 14.2mm 16mm; /* Margins: 2.5 cm on each side */
                }
                h2 { font-family: Arial; font-size: 22px; text-align:center; }
                p.para {font-family: Arial; font-size: 20px !important; text-align: justify;}
                span.marker {background-color: Yellow}
                </style>'
            . '</head>'
            . '<body>'
            . '<h2>Escrow Instruction</h2><br/>';

        foreach ($escrow_instruction_list as $escrow_ins) {
            $escrow_ins_name = $escrow_ins->name;
            $escrow_ins_select_name = $this->input->post($escrow_ins->id);
            $escrow_ins_select_value = $this->input->post('esw_ins_value_' . $escrow_ins->id);

            $content .= "<p class='para'>$escrow_ins_name: <b>$escrow_ins_select_name</b>$escrow_ins_select_value<br/></p>";
        }
        $content .= '</body></html>';
        if (!is_dir('uploads/escrow_ins')) {
            mkdir('./uploads/escrow_ins', 0777, true);
        }
        $this->load->model('order/document');
        file_put_contents("./uploads/escrow_ins/" . $filename, $content);
        $this->order->uploadDocumentOnAwsS3($filename, 'escrow_ins');

        $documentData = array(
            'document_name' => $filename,
            'original_document_name' => $filename,
            'document_type_id' => 1041,
            'document_size' => 0,
            'user_id' => $userdata['id'],
            'order_id' => $this->input->post('order_id'),
            'task_id' => 5,
            'description' => 'Escrow Instruction Document',
            'is_sync' => 1,
            'is_uploaded_by_borrower' => 1,
        );
        $this->document->insert($documentData);
        $success[] = "Escrow instruction word document generated successfully.<br> " . $filename . " uploaded successfully for Check Escrow Instructions & Packages task";
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'order/escrow/order-tasks/' . $orderDetails['order_id']);
    }

    public function sendRequestDocs()
    {
        $userdata = $this->session->userdata('user');
        $this->load->library('order/order');
        $this->load->model('order/apiLogs');
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $email = $this->input->post('email');
        $orderDetails = $this->order->get_order_details($file_id);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $errors = '';
        $success = '';

        //$this->home_model->update(array('borrower_email' => $borrower_email), array('file_id' => $file_id), 'order_details');
        $form_url = base_url() . 'borrower-document/request_docs/' . $orderDetails['random_number'];

        $email_data = array(
            'file_number' => $orderDetails['file_number'],
            'property_address' => $orderDetails['full_address'],
            'random_number' => $orderDetails['random_number'],
            'borrrower' => $orderDetails['primary_owner'],
            'form_url' => $form_url,
            'escrow_officer' => '',
        );

        $borrower_message_body = $this->load->view('frontend/emails/request_docs.php', $email_data, true);
        $message_body = $borrower_message_body;
        $subject = $orderDetails['file_number'] . ' - Request Docs';

        $mailParams = array(
            'from_mail' => $from_mail,
            'from_name' => $from_name,
            'subject' => $subject,
            'message' => json_encode($email_data),
        );

        if (!empty($email)) {
            $to = $email;
            $mailParams['to'] = $to;
            $this->load->helper('sendemail');
            $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_lender', '', $mailParams, array(), $order_id, 0);
            $borrower_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
            $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_lender', '', $mailParams, array('status' => $borrower_mail_result), $order_id, $logid);
        }

        $success = "Mail sent succesfully to user for request docs.";
        $this->session->set_userdata('success', $success);
        $this->session->set_userdata('errors', $errors);
        redirect(base_url() . 'order/escrow/order-tasks/' . $orderDetails['order_id']);
    }
}
