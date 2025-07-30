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
        $this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('hr/template');
        $this->load->library('hr/common');
        $this->load->model('hr/hr'); 
		$current_url = current_url();
		if (!(strpos($current_url, 'acknowledge-memo') !== false)) {
			$this->common->is_user();
		}
    }

    public function index()
    {
        $userdata = $this->session->userdata('hr_user');
		$data['title'] = 'HR-Center Memos';
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
        $this->template->show("hr", "memos", $data);
    }

    public function getMemos()
    {
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
                $nestedData[] = $value['first_name']." ".$value['last_name'];
                $nestedData[] = date("m/d/Y", strtotime($value['created_at'])); 
				$ack_date = '-';
				if ($value['is_read'] == '1') {
                    $status = '<span class="badge-new badge-new-success">Acknowledge</span>';
					if(strtotime($value['acknowledge_at'])) {
						$ack_date = $this->common->convertTimezone($value['acknowledge_at']);
					}
					
                } else {
                    $status = '<span class="badge-new badge-new-info">Pending</span>';
                }
                $nestedData[] = $status;
                $nestedData[] = $ack_date;
                $memoId = $value['id'];
                $nestedData[] = "<div class=''>
                        <button onclick='return showMemoInfo($memoId);' class='btn btn-grad-2a generate button-color' type='submit'>View</button>
					</div>";
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
        $userdata = $this->session->userdata('hr_user');
        $memoId = $this->input->post('memoId');
        $memoInfo = $this->common->getMemoInfo($memoId);
        $memoInfo['to'] = $userdata['name']; 
        $memoInfo['date'] = date("m/d/Y", strtotime($memoInfo['date'])); 
        $response = array('status'=>'success', 'memoInfo' => $memoInfo);
		echo json_encode($response); exit; 
    }

    public function acceptMemo()
    {
        $userdata = $this->session->userdata('hr_user');
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
        $success[] =  $subject." memo accepted successfully.";
        $memo_date = date("F d, Y", strtotime($memoInfo['date']));
        $message = $subject.' Memo request of '.$memo_date.' accepted by '.$userdata['name'];
    
        $this->load->model('hr/users_model');
        $userInfo = $this->users_model->get($userdata['id']);
        $branchUserInfo = $this->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
        $notificationData = array(
            'sent_user_id' => $branchUserInfo->id,
            'message' => $message,
            'type' => 'accepted'
        );
        $this->hr->insert($notificationData, 'pct_hr_notifications');
        $this->common->sendNotification($message, 'accepted', $branchUserInfo->id, 1);

        $superadminInfo = $this->users_model->get_by('user_type_id', 1);
        $notificationData['sent_user_id'] = $superadminInfo->id;
        $this->hr->insert($notificationData, 'pct_hr_notifications');
        $this->common->sendNotification($message, 'accepted', $superadminInfo->id, 1);

        $data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url().'hr/memos');
    }

	public function acknowledgeMemo($memo_cipher,$user_cipher)
    {
		$this->load->library('encryption');
		$memoId = $this->encryption->decrypt($memo_cipher);
		$userId = $this->encryption->decrypt($user_cipher);
        $memoInfo = $this->common->getAssignedMemoInfo($memoId);
		$userInfo = $this->hr->getUserInfo($userId);
		if($memoInfo && $userInfo) {
			$subject =  $memoInfo['subject'];
			$errors = array();
			$success = array();
			$data = array(
				'is_read' => 1,
			);
			$condition = array(
				'id' => $memoId,
				'user_id' => $userId
			);
			$this->hr->update($data, $condition, 'pct_hr_assigned_memo_users'); 
			$success[] =  $subject." memo accepted successfully.";
			$memo_date = date("F d, Y", strtotime($memoInfo['date']));
			$message = $subject.' Memo request of '.$memo_date.' accepted by '.$userInfo['first_name'].' '.$userInfo['last_name'];

            $this->load->model('hr/users_model');
            $userInfo = $this->users_model->get($userId);
            $branchUserInfo = $this->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
            $notificationData = array(
                'sent_user_id' => $branchUserInfo->id,
                'message' => $message,
                'type' => 'accepted'
            );
            $this->hr->insert($notificationData, 'pct_hr_notifications');
            $this->common->sendNotification($message, 'accepted', $branchUserInfo->id, 1);

            $superadminInfo = $this->users_model->get_by('user_type_id', 1);
            $notificationData['sent_user_id'] = $superadminInfo->id;
            $this->hr->insert($notificationData, 'pct_hr_notifications');
            $this->common->sendNotification($message, 'accepted', $superadminInfo->id, 1);
			$data = array(
				"errors" =>  $errors,
				"success" => $success
			);
			$this->session->set_userdata($data);
			redirect(base_url().'hr/memos');
		}
		else {
			echo "Invalid Request";
		}
    }
}
