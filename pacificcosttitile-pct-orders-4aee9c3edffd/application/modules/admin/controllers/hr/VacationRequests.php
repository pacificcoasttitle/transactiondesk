<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VacationRequests extends MX_Controller {

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
        $data['title'] = 'HR-Center Vacation Requests';
        $data['page_title'] = 'Vacation Requests';
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
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=vr_1') );
        $this->admintemplate->show("hr", "vacation_requests", $data);
    }

    public function  getVacationRequests()
    {
        $userdata = $this->session->userdata('hr_admin');
        $params = array();  $data = array();
        $params['is_frontend'] = 0;
		if (isset($_POST['draw']) && !empty($_POST['draw'])) {
			$params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
			$params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
			$params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
			$params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
			$params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
			$params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
			$pageno = ($params['start'] / $params['length'])+1;
			$vacationRequestsList = $this->common->getVacationRequests($params);
			$json_data['draw'] = intval( $params['draw'] );
		} else {
			$params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
			$vacationRequestsList = $this->common->getVacationRequests($params);
		}
		
		if (isset($vacationRequestsList['data']) && !empty($vacationRequestsList['data'])) {
			$i = $params['start'] + 1;
			foreach ($vacationRequestsList['data'] as $vacationRequestList)  {
                $denied_reason = '-';
				if(!empty($vacationRequestList['denied_reason'])) {
					$denied_reason = $vacationRequestList['denied_reason'];
				}
				$nestedData = array();
				$nestedData[] = $i;
				$nestedData[] = $vacationRequestList['first_name']." ".$vacationRequestList['last_name'];
                $nestedData[] = date("m/d/Y", strtotime($vacationRequestList['from_date']));
                $nestedData[] = date("m/d/Y", strtotime($vacationRequestList['to_date']));
                $nestedData[] = $vacationRequestList['is_salary_deduction'] == 1 ? 'Yes' : 'No';
                $nestedData[] = $vacationRequestList['is_time_charged_vacation'] == 1 ? 'Yes' : 'No';

                $status = '<span class="badge badge-info">Pending</span>';
                if (!empty($vacationRequestList['approved_by_user_id'])) {
                    if ($vacationRequestList['status'] == 'approved') {
                        $status = '<span class="badge badge-success">Approved</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Denied</span><span role="button" class="icon" data-toggle="popover" title="Denied Reason" data-content="'.$denied_reason.'">
						            <i class="fas fa-info"></i>
					            </span>';
                    }
                }
                $nestedData[] = $status;

                if (!empty($vacationRequestList['approved_by_user_id'])) {
                    $nestedData[] = $vacationRequestList['branch_manager_first_name']." ".$vacationRequestList['branch_manager_last_name'];
                } else {
                    $nestedData[] = ''  ;
                }

                if(isset($_POST['draw']) && !empty($_POST['draw'])) {
                    if($userdata['id'] != $vacationRequestList['user_id']) {
                        if (!empty($vacationRequestList['approved_by_user_id'])) {
                            if ($vacationRequestList['status'] == 'approved') {
                                $nestedData[] = '
                                         <button type="button" class="btn btn-danger btn-icon-split btn-sm vacation-request-action-btn" data-req-id="'.$vacationRequestList["id"].'">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-ban"></i>
                                            </span>
                                            <span class="text">Deny</span>
                                        </button>';
                            } else {
                                $nestedData[] = '<a href="" onclick="return approve_deny_popup(1, '.$vacationRequestList["id"].');" class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Approve</span>
                                        </a>
                                        '; 
                            }
                        } else {
                            $nestedData[] = '<div style="display:inline-flex;">
                                                <a href="" onclick="return approve_deny_popup(1, '.$vacationRequestList["id"].');" class="btn btn-success btn-icon-split btn-sm">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                    <span class="text">Approve</span>
                                                </a>
                                                <button type="button" class="ml-2 btn btn-danger btn-icon-split btn-sm vacation-request-action-btn" data-req-id="'.$vacationRequestList["id"].'">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-ban"></i>
                                                    </span>
                                                    <span class="text">Deny</span>
                                                </button>
                                            </div>'; 
                        }
                    } else {
                        $nestedData[] = ''; 
                    }
                }
				$data[] = $nestedData; 
				$i++; 
			}
		}
		$json_data['recordsTotal'] = intval( $vacationRequestsList['recordsTotal'] );
		$json_data['recordsFiltered'] = intval( $vacationRequestsList['recordsFiltered'] );
		$json_data['data'] = $data;
		echo json_encode($json_data);
    }

    public function addVacationRequest()
    {
        $userdata = $this->session->userdata('hr_admin');
        $data['title'] = 'HR-Center Vacation Request';
        $data['page_title'] = 'Report new Vacation Request';
		$this->load->model('hr/users_model');
        if ($userdata['user_type_id'] == 4) {
            $userInfo = $this->users_model->get($userdata['id']);
            if ($userdata['department_id'] == 4) {
                $users = $this->users_model->get_many_by('department_id', 4);
            } else {
                $users = $this->users_model->get_many_by('branch_id', $userInfo->branch_id);
            }
        } else {
            $users = $this->users_model->get_all();
        }
		$data['employees'] = $users;
		$this->admintemplate->addCSS( base_url('assets/frontend/hr/css/smart-forms.css?v=0.1') );
		$this->admintemplate->addJS( base_url('assets/frontend/js/jquery.steps.min.js') );
		$this->admintemplate->addJS( base_url('assets/frontend/js/jquery-cloneya.min.js') );
		$this->admintemplate->addJS( base_url('assets/frontend/js/parsley.min.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=vac_0.1') );
        $this->admintemplate->show("hr", "add_vacation_request", $data);
    }

    public function saveVacationRequests()
    {
        $userdata = $this->session->userdata('hr_admin');
        $this->load->model('hr/users_model');
        $userInfo = $this->users_model->get($this->input->post('select_employee'));
        $ids = array();
        $from_dates = $this->input->post('from_dates');
        $to_dates = $this->input->post('to_dates');
        $comments = $this->input->post('comments');
        $is_salary_deductions = $this->input->post('is_salary_deductions');
        $is_time_charged_vacations = $this->input->post('is_time_charged_vacations');
        $i = 0;

        foreach($from_dates as $from_date) {
            $vacationRequestsData = array(
                'user_id' => $this->input->post('select_employee'),
                'from_date' => date("Y-m-d", strtotime($from_date)),
                'to_date' => date("Y-m-d", strtotime($to_dates[$i])),
                'comment' => $comments[$i],
                'is_salary_deduction' => $is_salary_deductions[$i] == 'on' ? 1 : 0,
                'is_time_charged_vacation' => $is_time_charged_vacations[$i] == 'on' ? 1 : 0
            );
            $ids[] = $last_id = $this->hr->insert($vacationRequestsData, 'pct_hr_vacation_requests');
            $from_date = date("F d, Y", strtotime($from_date));
            $to_date = date("F d, Y", strtotime($to_dates[$i]));
            $message = 'Vacation request from '.$from_date.' to '.$to_date.' has submitted by '.$userdata['name'];
            $notificationData = array(
                'sent_user_id' => $this->input->post('select_employee'),
                'message' => $message,
                'type' =>  'submitted'
            );
            $this->hr->insert($notificationData, 'pct_hr_notifications');
            $this->common->sendNotification($message, 'submitted', $this->input->post('select_employee'), 0);

			//Send Mail to User
			$this->common->mailNotification($this->input->post('select_employee'),'vacation_request',$last_id);

            $message = 'Vacation request from '.$from_date.' to '.$to_date.' has submitted for employee '.$userInfo->first_name." ".$userInfo->last_name.' by '.$userdata['name'];
            $notificationData['message'] = $message;
            if ($userdata['user_type_id'] == 4) {
                $superadminInfo = $this->users_model->get_by('user_type_id', 1);
                $notificationData['sent_user_id'] = $superadminInfo->id;
                $this->hr->insert($notificationData, 'pct_hr_notifications');
                $this->common->sendNotification($message, 'submitted', $superadminInfo->id, 1);

				//Send Mail to Admin
				$this->common->mailNotification($superadminInfo->id,'vacation_request',$last_id);
            } else {
                $branchUserInfo = $this->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
                $notificationData['sent_user_id'] = $branchUserInfo->id;
                $this->hr->insert($notificationData, 'pct_hr_notifications');
                $this->common->sendNotification($message, 'submitted', $branchUserInfo->id, 1);

				//Send Mail to Manager
				$this->common->mailNotification($branchUserInfo->id,'vacation_request',$last_id);
            }
            $i++;
        }
        if(!empty($ids)) {
            $success = "Vacation Requests saved successfully.";
        } else {
            $errors = "Something went wrong. Please try again.";
        }
        
        $data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url().'hr/admin/vacation-requests');
    }


    public function deleteVacationRequest()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $userData = array('status' => 0);
            $condition = array('id' => $id);
            $update = $this->hr->update($userData, $condition, 'pct_hr_users');
            if ($update) {
                $successMsg = 'User deleted successfully.';
                $response = array('status'=>'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'User ID is required.';
            $response = array('status' => 'error','message'=>$msg);
        }
        echo json_encode($response);
    }
}
