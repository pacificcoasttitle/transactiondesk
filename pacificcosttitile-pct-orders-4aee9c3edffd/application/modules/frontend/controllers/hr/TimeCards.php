<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class TimeCards extends MX_Controller 
{
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
		$data['pay_period_start'] = PAY_PERIOD_START;
		$data['current_date'] = $this->common->convertTimezone(date('Y-m-d H:i:s'),'Y-m-d','America/Los_Angeles');
		$this->load->model('hr/pct_hr_user_timesheet_status_model');
		$timesheet_status =  $this->pct_hr_user_timesheet_status_model->get_many_by(['user_id'=>$userdata['id']]);
		$data['timesheet_status'] = $timesheet_status;

        $data['name'] = $userdata['name'];
		$data['title'] = 'HR-Center Time Cards';
        $this->template->show("hr", "time_cards", $data);
	}

    public function getTimeCards()
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
			$timeCardsList = $this->common->getTimeCards($params);
			$json_data['draw'] = intval( $params['draw'] );
		} else {
			$params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
			$timeCardsList = $this->common->getTimeCards($params);
		}
		
		if (isset($timeCardsList['data']) && !empty($timeCardsList['data'])) {
			$i = $params['start'] + 1;
			foreach ($timeCardsList['data'] as $timeCard)  {
				$denied_reason = '-';
				if(!empty($timeCard['denied_reason'])) {
					$denied_reason = $timeCard['denied_reason'];
				}
				$nestedData = array();
				$nestedData[] = $i;
				$nestedData[] = $timeCard['first_name']." ".$timeCard['last_name'];
                $nestedData[] = date("m/d/Y", strtotime($timeCard['exception_date']));
                $nestedData[] = $timeCard['reg_hours'];
                $nestedData[] = $timeCard['ot_hours'];
                $nestedData[] = $timeCard['double_ot'];
                $status = '<span class="badge-new badge-new-info">Pending</span>';

                if (!empty($timeCard['approved_by_user_id'])) {
                    if ($timeCard['status'] == 'approved') {
                        $status = '<span class="badge-new badge-new-success">Approved</span>';
                    } else {
                        $status = '<span class="badge-new badge-new-danger">Denied</span><span role="button" class="icon" data-toggle="popover" title="Denied Reason" data-content="'.$denied_reason.'">
						<i class="fa fa-info"></i>
					</span>';
                    }
                }
                $nestedData[] = $status;

                if (!empty($timeCard['approved_by_user_id'])) {
                    $nestedData[] = $timeCard['branch_manager_first_name']." ".$timeCard['branch_manager_last_name'];
                } else {
                    $nestedData[] = ''  ;
                }

                $nestedData[] = !empty($timeCard['approved_date']) ? date("m/d/Y", strtotime($timeCard['approved_date'])) : '';
				$data[] = $nestedData; 
				$i++; 
			}
		}

		$json_data['recordsTotal'] = intval( $timeCardsList['recordsTotal'] );
		$json_data['recordsFiltered'] = intval( $timeCardsList['recordsFiltered'] );
		$json_data['data'] = $data;
		echo json_encode($json_data);
    }

    public function saveTimeCards()
    {
        $userdata = $this->session->userdata('hr_user');
        $errors = array();
        $success = array();
        $ids = array();
        $exception_dates = $this->input->post('exception_date');
        $reg_hours = $this->input->post('reg_hours');
        $ot_hours = $this->input->post('ot_hours');
        $double_ot = $this->input->post('double_ot');
        $total_hours = $this->input->post('total_hours');
        $comment = $this->input->post('comment');
        $i = 0;

        foreach($exception_dates as $exception_date) {
            $timeCardsData = array(
                'user_id' => $userdata['id'],
                'exception_date' => date("Y-m-d", strtotime($exception_date)),
                'reg_hours' => $reg_hours[$i],
                'ot_hours' => $ot_hours[$i],
                'double_ot' => $double_ot[$i],
                'total_hours' => $total_hours[$i],
                'comment' => $comment[$i]
            );
            $ids[] = $last_id = $this->hr->insert($timeCardsData, 'pct_hr_time_cards');
            $exceptionDate = date("F d, Y", strtotime($exception_date));
            $message = 'Timecard request of '.$exceptionDate.' has submitted by '.$userdata['name'];
			//Send Mail to User
			$this->common->mailNotification($userdata['id'],'time_card',$last_id);

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
			$this->common->mailNotification($branchUserInfo->id,'time_card',$last_id);
    
            $superadminInfo = $this->users_model->get_by('user_type_id', 1);
            $notificationData['sent_user_id'] = $superadminInfo->id;
            $this->hr->insert($notificationData, 'pct_hr_notifications');
            $this->common->sendNotification($message, 'submitted', $superadminInfo->id, 1);
			//Send Mail to Admin
			$this->common->mailNotification($superadminInfo->id,'time_card',$last_id);
            $i++;
        }
        if(!empty($ids)) {
            $success[] = "Time Cards saved successfully.";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        
        $data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url().'hr/time-cards');
    }

	public function submitTimesheet() {
		$userdata = $this->session->userdata('hr_user');
		$inserted_id= '';
		if(!empty($userdata['id'])) {
			$this->load->model('hr/pct_hr_user_timesheet_status_model');
			$status_data = [
				'user_id'=>$userdata['id'],
				'start_date' => date('Y-m-d',$this->input->post('start_date')),
			];

			$inserted_id = $this->pct_hr_user_timesheet_status_model->insert($status_data);

		}

		if(!empty($inserted_id)) {
            $success[] = "Time sheet submitted successfully.";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        
        $data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url().'hr/time-cards');
	}

}
