<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class VacationRequests extends MX_Controller 
{
    private $vacation_js_version = '01';
	function __construct() 
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
        $this->common->is_user();
	}
	
	public function index()
	{
        $userdata = $this->session->userdata('hr_user');
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
        $data['name'] = $userdata['name'];
		$data['title'] = 'HR-Center Vacation Requests';
        $this->template->addCSS( base_url('assets/libs/calendar/main.css'));
        $this->template->addJS( base_url('assets/libs/calendar/main.js'));
        $this->template->addJS( base_url('assets/frontend/hr/js/vacation.js?v=vacation_'.$this->vacation_js_version) );
        $this->template->show("hr", "vacation_requests", $data);
	}

    public function getVacationRequests()
    {
        $params = array();  $data = array();
        $params['is_frontend'] = 1;
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
				$update_date = '-';

                $status = '<span class="badge-new badge-new-info">Pending</span>';
                if (!empty($vacationRequestList['approved_by_user_id'])) {
                    if ($vacationRequestList['status'] == 'approved') {
                        $status = '<span class="badge-new badge-new-success">Approved</span>';
                    } else {
                        $status = '<span class="badge-new badge-new-danger">Denied</span><span role="button" class="icon" data-toggle="popover" title="Denied Reason" data-content="'.$denied_reason.'">
                                    <i class="fa fa-info"></i>
                                </span>';
                    }
					if(strtotime($vacationRequestList['approved_date'])) {
						$update_date = $this->common->convertTimezone($vacationRequestList['approved_date'],'m/d/Y');
					}
					elseif(strtotime($vacationRequestList['updated_at'])) {
						$update_date = $this->common->convertTimezone($vacationRequestList['updated_at'],'m/d/Y');
					}
                }
                $nestedData[] = $status;
                $nestedData[] = $update_date;

                if (!empty($vacationRequestList['approved_by_user_id'])) {
                    $nestedData[] = $vacationRequestList['branch_manager_first_name']." ".$vacationRequestList['branch_manager_last_name'];
                } else {
                    $nestedData[] = ''  ;
                }

                // $nestedData[] = !empty($vacationRequestList['approved_date']) ? date("m/d/Y", strtotime($vacationRequestList['approved_date'])) : '';
				$data[] = $nestedData; 
				$i++; 
			}
		}
		$json_data['recordsTotal'] = intval( $vacationRequestsList['recordsTotal'] );
		$json_data['recordsFiltered'] = intval( $vacationRequestsList['recordsFiltered'] );
		$json_data['data'] = $data;
		echo json_encode($json_data);
    }

    public function saveVacationRequests()
    {
        $userdata = $this->session->userdata('hr_user');
        $errors = array();
        $success = array();
        $ids = array();
        $from_dates = $this->input->post('from_dates');
        $to_dates = $this->input->post('to_dates');
        $comments = $this->input->post('comments');
        $is_salary_deductions = $this->input->post('is_salary_deductions');
        $is_time_charged_vacations = $this->input->post('is_time_charged_vacations');
        $i = 0;

        foreach($from_dates as $from_date) {
            $vacationRequestsData = array(
                'user_id' => $userdata['id'],
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

			//Send Mail to User
			$this->common->mailNotification($userdata['id'],'vacation_request',$last_id);
			
            $this->load->model('hr/users_model');
            $userInfo = $this->users_model->get($userdata['id']);

            if ($userdata['department_id'] == 4) {
                $branchUserInfo = $this->users_model->get_by(array('user_type_id' => 4, 'department_id' => 4));
            } else {
                $branchUserInfo = $this->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
            }
            
            if (!empty($branchUserInfo)) {
                $notificationData = array(
                    'sent_user_id' => $branchUserInfo->id,
                    'message' => $message,
                    'type' => 'submitted'
                );
                $this->hr->insert($notificationData, 'pct_hr_notifications');
                $this->common->sendNotification($message, 'submitted', $branchUserInfo->id, 1);
            }

			//Send Mail to Manager
			$this->common->mailNotification($branchUserInfo->id,'vacation_request',$last_id);
    
            $superadminInfo = $this->users_model->get_by('user_type_id', 1);
            $notificationData['sent_user_id'] = $superadminInfo->id;
            $this->hr->insert($notificationData, 'pct_hr_notifications');
            $this->common->sendNotification($message, 'submitted', $superadminInfo->id, 1);

			//Send Mail to Admin
			$this->common->mailNotification($superadminInfo->id,'vacation_request',$last_id);

            $i++;
        }
        if(!empty($ids)) {
            $success[] = "Vacation Requests saved successfully.";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        
        $data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url().'hr/vacation-requests');
    }

    public function getVacationDataForCalendarUser()
	{
		$userdata = $this->session->userdata('hr_user');
        $userIds = array();
        if(!empty($userdata)) {
            if ($userdata['user_type_id'] == 2) {
                $usersForBranchManager = $this->common->getUsersForBranchManager($userdata['id']);
                $userIds = array_column($usersForBranchManager, 'id');	
            } else {
                $userIds[] = $userdata['id'];
            }
        }
		$start = date('Y-m-d', strtotime($this->input->post('start')));
		$end = date('Y-m-d', strtotime($this->input->post('end')));
        $vacationData = $this->common->getVacationDataForCalendar($start, $end, $userIds);
        $data = array();
        $i = 0;
        foreach ($vacationData as $vacation) {
            $data[$i]['id'] = $vacation['id'];
            $data[$i]['title'] = $vacation['first_name']." ".$vacation['last_name'];
            $data[$i]['start'] = $vacation['from_date'];
            $data[$i]['end'] = date('Y-m-d', strtotime($vacation['to_date'] . ' +1 day'));
            if (!empty($vacation['approved_by_user_id'])) {
				if ($vacation['status'] == 'approved') {
					$data[$i]['backgroundColor'] = '#28a745';
				} 
			}
            $i++;
        }
        $i++;
        echo json_encode($data); 
	}

}
