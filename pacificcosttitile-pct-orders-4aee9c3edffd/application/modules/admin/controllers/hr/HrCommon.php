<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class HrCommon extends MX_Controller 
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
        $this->load->model('hr/hr'); 
        $this->load->library('hr/common');
	}
	
	function approveDenyRequest()
	{
		$request_type = $this->input->post('request_type');
        $request_id = $this->input->post('request_id');
        $status = $this->input->post('status');
        $this->common->approveDenyRequest($request_type, $request_id, $status);
        if ($request_type == 'time_card') {
            redirect(base_url().'hr/admin/time-cards');
        } else if ($request_type == 'incident_report') {
            redirect(base_url().'hr/admin/incident-reports');
        } else if ($request_type == 'vacation_request') {
            redirect(base_url().'hr/admin/vacation-requests');
        } else if ($request_type == 'time_sheet_status') {
            redirect(base_url().'hr/admin/time-sheets');
        }
		
	}
    
    public function markAsRead()
    {
        $userdata = $this->session->userdata('hr_admin');
        $condition = array(
            'sent_user_id' => $userdata['id']
        );
        $data = array(
            'is_read' => 1
        );
        $this->hr->update($data, $condition, 'pct_hr_notifications');
        $response = array(
            'success' => 'true',
            'message'  => 'Notifcation marked as read.',
        );
        echo json_encode($response);
    }

	function randomizeTimeClockIn() {
		$today_date = $this->common->convertTimezone(date('Y-m-d H:i:s'),'Y-m-d','America/Los_Angeles');

		$this->load->model('hr/branches_model');
		$this->load->model('hr/users_model');
		$this->load->model('frontend/hr/pct_hr_employee_time_tracking_model');

		$branch_names = [
			'10 PCT Glendale Escr',
			'12 PCT Glendale Titl'
		];
		// $branch_names = ['IT'];
		$dept_records = $this->branches_model->get_many_by('name',$branch_names);
		$dept_ids = array_column($dept_records,'id');
		if(!(count($dept_ids))) {
			die('No department found');
		}
		$user_records = $this->users_model->get_many_by('branch_id',$dept_ids);
		$user_ids = array_column($user_records,'id');

		foreach($user_ids as $user_id) {
			
			// Check if record already exist
			$where_arr = [
				'employee_id'=>$user_id,
				'DATE(time_in)'=>$today_date,
			];

			$record_exist = $this->pct_hr_employee_time_tracking_model->get_by($where_arr);
			if(!($record_exist)) {

				$random_start = strtotime($today_date.' '.'08:30:00');
				$random_end = strtotime($today_date.' '.'08:40:00');
				$start_time = rand($random_start,$random_end);

				$insert_tracking_tmp = [
					'employee_id'=>$user_id,
					'time_in'=>date("Y-m-d H:i:s",$start_time),
					'is_break'=>0
				];
				
				$this->pct_hr_employee_time_tracking_model->insert($insert_tracking_tmp);

			}
		}
	}
	function randomizeTimeClockOut() {

		// $today_date = $this->common->convertTimezone(date('Y-m-d H:i:s'),'Y-m-d','America/Los_Angeles');

		// $this->load->model('hr/branches_model');
		// $this->load->model('hr/users_model');
		// $this->load->model('frontend/hr/pct_hr_employee_time_tracking_model');

		// $branch_names = [
		// 	'10 PCT Glendale Escr',
		// 	'12 PCT Glendale Titl'
		// ];
		// // $branch_names = ['IT'];
		// $dept_records = $this->branches_model->get_many_by('name',$branch_names);
		// $dept_ids = array_column($dept_records,'id');
		// if(!(count($dept_ids))) {
		// 	die('No department found');
		// }
		// $user_records = $this->users_model->get_many_by('branch_id',$dept_ids);
		// $user_ids = array_column($user_records,'id');

		// foreach($user_ids as $user_id) {
			
		// 	// Check if record already exist
		// 	$where_arr = [
		// 		'employee_id'=>$user_id,
		// 		'DATE(time_in)'=>$today_date,
		// 	];

		// 	$record_exist = $this->pct_hr_employee_time_tracking_model->order_by('time_in','DESC')->get_by($where_arr);
		// 	if($record_exist && empty($record_exist->time_out)) {

				
		// 		$end_time = strtotime($today_date.' '.'17:30:00');

		// 		$update_tracking_tmp = [
		// 			'time_out'=>date("Y-m-d H:i:s",$end_time)
		// 		];
				
		// 		$this->pct_hr_employee_time_tracking_model->update($record_exist->id,$update_tracking_tmp);

		// 	}
		// }

		$this->load->model('frontend/hr/pct_hr_employee_time_tracking_model');
		$this->load->model('hr/users_model','employee');
		$where['time_out'] = NULL;
		$data = $this->pct_hr_employee_time_tracking_model->get_many_by($where);
		foreach($data as $record) {
			$start_time = $record->time_in;
			$start_date = date('Y-m-d',strtotime($start_time));
			$end_time = $start_date.' 17:30:00'; // PST 5:30 pm
			$current_time = $this->common->convertTimezone(date('Y-m-d H:i:s'),'Y-m-d H:i:s','America/Los_Angeles');
			if((strtotime($start_time) < strtotime($end_time)) && (strtotime($end_time) <= strtotime($current_time))) {
				$update_data = array();
				$update_data['time_out'] = $end_time;
				$update_data['is_auto'] = 1;
				$this->pct_hr_employee_time_tracking_model->update($record->id,$update_data);

				//Send mail notification to user (Temp Account Dept)
				$employee_obj = $this->employee->with('department')->get($record->employee_id);
				if($employee_obj && !empty($employee_obj->department) && stripos($employee_obj->department->name,'account')!== false) {
					
					$this->common->mailNotification($record->employee_id,'day_end',$record->id);
				}


			}
		}
	}


	function randomizeTimeSheet() {
		$this->load->model('hr/branches_model');
		$this->load->model('hr/users_model');
		$this->load->model('frontend/hr/pct_hr_employee_time_tracking_model');
		$branch_names = [
			'10 PCT Glendale Escr',
			'12 PCT Glendale Titl'
		];
		// $branch_names = ['IT'];
		$dept_records = $this->branches_model->get_many_by('name',$branch_names);
		$dept_ids = array_column($dept_records,'id');
		if(!(count($dept_ids))) {
			die('No department found');
		}
		$user_records = $this->users_model->get_many_by('branch_id',$dept_ids);
		$user_ids = array_column($user_records,'id');
		
		$start_date = strtotime('2022-04-01');
		$end_date = strtotime('2022-04-17');
		$current_date = $start_date;
		while($current_date <= $end_date) {
			$record_date = date('Y-m-d',$current_date );
			foreach($user_ids as $user_id) {

				// Check if record already exist
				$where_arr = [
					'employee_id'=>$user_id,
					'DATE(time_in)'=>$record_date,
				];

				$record_exist = $this->pct_hr_employee_time_tracking_model->get_by($where_arr);
				if(!($record_exist)) {
					
					//8:30am-:8:40am
					$random_start = strtotime($record_date.' '.'08:30:00');
					$random_end = strtotime($record_date.' '.'08:40:00');
					$random_time = rand($random_start,$random_end);
					$random_end = strtotime($record_date.' '.'17:30:00');
		
					
		
					
		
					$insert_tracking_tmp = [
						'employee_id'=>$user_id,
						'time_in'=>date("Y-m-d H:i:s",$random_time),
						'time_out'=>date("Y-m-d H:i:s",$random_end),
						'is_break'=>0
					];
					
					$this->pct_hr_employee_time_tracking_model->insert($insert_tracking_tmp);
				}
				
			}

			$current_date = strtotime("+1 day",$current_date);


		}





		// $users = 		
	}
}
