<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memos extends MX_Controller {

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
        $this->load->library('hr/common');
        $this->common->is_hr_admin();
    }

    public function index()
    {
        $data['title'] = 'HR-Center User Types';
        $data['page_title'] = 'User Types';
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
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "memos", $data);
    }

    public function getMemos()
    {
        $userdata = $this->session->userdata('hr_admin');
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $memos = $this->hr->getMemos($params);
            $json_data['draw'] = intval( $params['draw'] );
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $memos = $this->hr->getMemos($params);            
        }

        $data = array(); 
        $count = $params['start'] + 1;
	    if (isset($memos['data']) && !empty($memos['data'])) {
	    	foreach ($memos['data'] as $key => $value)  {
	    		$nestedData=array();
                $nestedData[] = $count;
	            $nestedData[] = $value['subject'];
                $nestedData[] = date("m/d/Y", strtotime($value['date'])); 
                $nestedData[] = $value['first_name']." ".$value['last_name'];
                $nestedData[] = date("m/d/Y", strtotime($value['created_at'])); 
                
                if($userdata['user_type_id'] == 4) {
                    $status = '<span class="badge badge-info">Pending</span>';
                    if ($value['is_read'] == 1) {
                        $status = '<span class="badge badge-success">Accepted</span>';
                    }
                    $memoId = $value['id'];
                    $nestedData[] = $status;
                    if(isset($_POST['draw']) && !empty($_POST['draw'])) { 
                        $nestedData[] = "<div style='display:inline-flex;'>
                                            <a onclick='return showMemoInfo($memoId);' class='btn btn-info btn-icon-split btn-sm'>
                                                <span class='icon text-white-50'>
                                                    <i class='fas fa-eye'></i>
                                                </span>
                                                <span class='text'>View</span>
                                            </a>
                                        </div>";
                    }
                } else {
                    if(isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $editUrl = base_url().'hr/admin/edit-memo/'.$value['id'];
                        $nestedData[] = '<div style="display:inline-flex;">
                                            <a href="'.$editUrl.'" class="btn btn-info btn-icon-split btn-sm">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </span>
                                                <span class="text">Edit</span>
                                            </a>
                                            <a style="margin-left: 5px;" href="#" onclick="deleteMemo('.$value["id"].')" class="btn btn-danger btn-icon-split btn-sm">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                <span class="text">Delete</span>
                                            </a>
                                        </div>';
                    }
                }
                $data[] = $nestedData;    
                $count++;          
	    	}
	    }
        $json_data['recordsTotal'] = intval( $memos['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $memos['recordsFiltered'] );
        $json_data['data'] = $data;
	    echo json_encode($json_data);
    }

    public function addMemo()
    {
        $userdata = $this->session->userdata('hr_admin');
        $data['title'] = 'HR-Center Memos';
        $data['page_title'] = 'Add memo';
        $data['users'] = $this->common->getAllUsers();
        $this->load->model('hr/users_model');

        if ($this->input->post()) {
            $this->form_validation->set_rules('subject', 'Subject', 'required', array('required'=> 'Please Enter Subject'));
            $this->form_validation->set_rules('users[]', 'Users', 'required', array('required'=> 'Please Select atleast one user'));
            $this->form_validation->set_rules('memo_date', 'Memo Date', 'required', array('required'=> 'Please Select Memo Date'));
            $this->form_validation->set_rules('memo_description', 'Memo Description', 'required', array('required'=> 'Please ente Description'));
           
            if ($this->form_validation->run() == true) {
                $memoData = array(
                    'subject' =>  $this->input->post('subject'),
                    'description' => $this->input->post('memo_description'),
                    'date' => date("Y-m-d", strtotime($this->input->post('memo_date'))),
                    'status' => 1,
                    'created_by' => $userdata['id']
                );
                $memoId = $this->hr->insert($memoData, 'pct_hr_memos');
                $users = $this->input->post('users');
                $user_ids = implode(',', $users);
                $usersInfo = $this->users_model->get_many_by("id in ($user_ids)");
                foreach($usersInfo as $user) {
                    $memoAssignedData = array(
                        'user_id' =>  $user->id,
                        'memo_id' =>  $memoId
                    );
                    $memo_assign_id = $this->hr->insert($memoAssignedData, 'pct_hr_assigned_memo_users');
                    $memo_date = date("F d, Y", strtotime($this->input->post('memo_date')));
                    $message = $this->input->post('subject').' Memo request of '.$memo_date.' has assigned to you.';
                    $notificationData = array(
                        'sent_user_id' => $user->id,
                        'message' => $message,
                        'type' =>  'assigned'
                    );
                    $this->hr->insert($notificationData, 'pct_hr_notifications');
        
                    if ($user->user_type_id == 4) {
                        $this->common->sendNotification($message, 'assigned', $user->id, 1);
                    } else {
                        $this->common->sendNotification($message, 'assigned', $user->id, 0);
                    }
                   
					//Send Mail
					$param = $memo_assign_id;
					$command = "php ".FCPATH."index.php frontend/order/cron sendMemoMail $param";
					if (substr(php_uname(), 0, 7) == "Windows"){
						pclose(popen("start /B ". $command, "r")); 
					}
					else {
						exec($command . " > /dev/null &");  
					}
                }
                $successMsg = 'Memo added successfully.';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url().'hr/admin/memos');
            } else {
                $data['subject_error_msg'] = form_error('subject');
                $data['users_error_msg'] = form_error('users[]');
                $data['memo_date_error_msg'] = form_error('memo_date');
                $data['memo_description_error_msg'] = form_error('memo_description');
            }                                       
        }
        $this->admintemplate->addCSS( base_url('assets/css/bootstrap-select.css') );
        $this->admintemplate->addJS( base_url('assets/plugins/ckeditor/ckeditor.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->addJS( base_url('assets/js/bootstrap-select.min.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/memos.js') );
        $this->admintemplate->show("hr", "add_memo", $data);
    }

    public function editMemo()
    {
        $userdata = $this->session->userdata('hr_admin');
        $id = $this->uri->segment(4);
        $data['title'] = 'HR-Center Memos';
        $data['page_title'] = 'Edit memo';
        $data['users'] = $this->common->getAllUsers();
        $data['assignedMemoUsers'] = $this->hr->getAssignedMemoInfo($id);
    
        if(isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('subject', 'Subject', 'required', array('required'=> 'Please Enter Subject'));
                $this->form_validation->set_rules('users[]', 'Users', 'required', array('required'=> 'Please Select atleast one user'));
                $this->form_validation->set_rules('memo_date', 'Memo Date', 'required', array('required'=> 'Please Select Memo Date'));
                $this->form_validation->set_rules('memo_description', 'Memo Description', 'required', array('required'=> 'Please ente Description'));
            
                if ($this->form_validation->run() == true) {
                    $memoData = array(
                        'subject' =>  $this->input->post('subject'),
                        'description' => $this->input->post('memo_description'),
                        'date' => date("Y-m-d", strtotime($this->input->post('memo_date'))),
                        'status' => 1
                    );
                    $condition = array('id' => $id);
                    $this->hr->update($memoData, $condition, 'pct_hr_memos');
                    $users = $this->input->post('users');
                    $this->db->delete('pct_hr_assigned_memo_users', array('memo_id' => $id));
                    foreach($users as $user) {
                        $memoAssignedData = array(
                            'user_id' =>  $user,
                            'memo_id' =>  $id
                        );
                        $this->hr->insert($memoAssignedData, 'pct_hr_assigned_memo_users');
                    }
                    $successMsg = 'Memo edited successfully.';
                    $this->session->set_userdata('success', $successMsg);
                    redirect(base_url().'hr/admin/memos');
                } else {
                    $data['subject_error_msg'] = form_error('subject');
                    $data['users_error_msg'] = form_error('users[]');
                    $data['memo_date_error_msg'] = form_error('memo_date');
                    $data['memo_description_error_msg'] = form_error('memo_description');
                }                                       
            }
            $data['memoInfo'] = $this->common->getMemoInfo($id);
            $data['assignedMemoInfo'] = $this->hr->getAssignedMemoInfo($id);
        } else { 
            redirect(base_url().'hr/admin/memos');
        }
        $this->admintemplate->addCSS( base_url('assets/css/bootstrap-select.css') );
        $this->admintemplate->addJS( base_url('assets/plugins/ckeditor/ckeditor.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->addJS( base_url('assets/js/bootstrap-select.min.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/memos.js') );
        $this->admintemplate->show("hr", "edit_memo", $data);
    }

    public function deleteMemo()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $memoData = array('status' => 0);
            $condition = array('id' => $id);
            $update = $this->hr->update($memoData, $condition, 'pct_hr_memos');
            if ($update) {
                $successMsg = 'Memo deleted successfully.';
                $response = array('status'=>'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Memo ID is required.';
            $response = array('status' => 'error','message'=>$msg);
        }
        echo json_encode($response);
    }

    public function memosStatus()
    {
        $data['title'] = 'HR-Center Memos';
        $data['page_title'] = "Memo's Status";
        $this->admintemplate->addCSS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.css'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/jquery.dataTables.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "memos_status", $data);
    }

    public function getMemosStatus()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $memos = $this->hr->getMemosStatus($params);
            $json_data['draw'] = intval( $params['draw'] );
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $memos = $this->hr->getMemosStatus($params);            
        }

        $data = array(); 
        $count = $params['start'] + 1;
	    if (isset($memos['data']) && !empty($memos['data'])) {
	    	foreach ($memos['data'] as $key => $value)  {
	    		$nestedData=array();
                $nestedData[] = $count;
	            $nestedData[] = $value['subject'];
                $nestedData[] = date("m/d/Y", strtotime($value['date'])); 
                $nestedData[] = $value['first_name']." ".$value['last_name']; 
                $nestedData[] = $value['assign_first_name']." ".$value['assign_last_name']; 
                $status = '<span class="badge badge-info">Pending</span>';
                if ($value['is_read'] == 1) {
                    $status = '<span class="badge badge-success">Accepted</span>';
                }
                $nestedData[] = $status;
	            $data[] = $nestedData;    
                $count++;          
	    	}
	    }
        $json_data['recordsTotal'] = intval( $memos['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $memos['recordsFiltered'] );
        $json_data['data'] = $data;
	    echo json_encode($json_data);
    }

    public function getMemoInfo()
    {
        $userdata = $this->session->userdata('hr_admin');
        $memoId = $this->input->post('memoId');
        $memoInfo = $this->common->getMemoInfo($memoId);
        $memoInfo['to'] = $userdata['name']; 
        $memoInfo['date'] = date("m/d/Y", strtotime($memoInfo['date'])); 
        $response = array('status'=>'success', 'memoInfo' => $memoInfo);
		echo json_encode($response); exit; 
    }

    public function acceptMemo()
    {
        $userdata = $this->session->userdata('hr_admin');
        $memoId = $this->input->post('memoId');
        $subject = $this->input->post('subject');
        $memoInfo = $this->common->getMemoInfo($memoId);
        $errors = array();
        $success = array();
        $data = array(
            'is_read' => 1,
        );
        $condition = array(
            'memo_id' => $memoId,
            'user_id' => $userdata['id']
        );
        $this->hr->update($data, $condition, 'pct_hr_assigned_memo_users'); 
        $successMsg =  $subject." memo accepted successfully.";
        $this->load->model('hr/users_model');
        $superadminInfo = $this->users_model->get_by('user_type_id', 1);
        $memo_date = date("F d, Y", strtotime($memoInfo['date']));
        $message = $subject.' Memo request of '.$memo_date.' accepted by '.$userdata['name'];
        $notificationData = array(
            'sent_user_id' => $superadminInfo->id,
            'message' => $message,
            'type' =>  'accepted'
        );
        $this->hr->insert($notificationData, 'pct_hr_notifications');
        $this->common->sendNotification($message, 'accepted', $superadminInfo->id, 1);
        $this->session->set_userdata('success', $successMsg);
        redirect(base_url().'hr/admin/memos');
    }
}
