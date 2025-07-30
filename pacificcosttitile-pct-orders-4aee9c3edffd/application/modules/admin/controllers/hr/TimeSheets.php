<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimeSheets extends MX_Controller {

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

    private $custom_js_version = '01';
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
        $this->load->model('hr/branches_model');
        $this->common->is_hr_admin();
    }

	public function index() {
		$data['title'] = 'HR-Center Time Sheets';
        $data['page_title'] = 'Time Sheets';
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
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js>v=ts_0.1') );
        $this->admintemplate->show("hr", "time_sheets", $data);
	}
	public function getTimesheets() {
		$userdata = $this->session->userdata('hr_admin');

		$this->load->model('frontend/hr/pct_hr_user_timesheet_status_model');
		$time_sheet_data = array();
		$usersIds = array();
        if(!empty($userdata)) {
            if ($userdata['user_type_id'] == 4) {
                $usersForBranchManager = $this->common->getUsersForBranchManager($userdata['id']);
                if(!empty($usersForBranchManager)) {
                    $usersIds = array_column($usersForBranchManager, 'id');
                } else {
                    $usersIds[] = $userdata['id'];
                }
				$time_sheet_data = $this->pct_hr_user_timesheet_status_model->order_by('id','desc')->with('user')->with('updated_by_user')->get_many_by('user_id',$usersIds);
            } 
			else {
				$time_sheet_data = $this->pct_hr_user_timesheet_status_model->order_by('id','desc')->with('user')->with('updated_by_user')->get_all();
			}
        }

		
		$data = array();
		foreach($time_sheet_data as $key=>$time_sheet_record) {
			$pay_period_start_time_stamp = strtotime($time_sheet_record->start_date);
			$pay_period_ends_time_stamp = strtotime("+13 day",$pay_period_start_time_stamp);
			$pay_period_monday_time_stamp = strtotime("+1 day",$pay_period_ends_time_stamp);
			$updated_by = '';
			if(!empty($time_sheet_record->updated_by_user)) {
				$updated_by = $time_sheet_record->updated_by_user->first_name.' '.$time_sheet_record->updated_by_user->last_name;
			}
			$denied_reason = $time_sheet_record->denied_reason;
			$status = '<span class="badge badge-info">Submitted</span>';
			
			$pay_range_link = base_url("hr/admin/view-time-sheet/".$pay_period_start_time_stamp."/".$time_sheet_record->user_id);
			$action_btn = '<div style="display:inline-flex;">';
			$action_btn .= '<a target="_blank" class="btn btn-secondary btn-icon-split btn-sm" href = "'.$pay_range_link.'"><span class="icon text-white-50">
			<i class="fas fa-eye"></i>
		</span>
		<span class="text">View Timesheet</span></a>';

			if($time_sheet_record->status == 'approved') {
				$action_btn .= '<button type="button" class="ml-2 btn btn-danger btn-icon-split btn-sm timesheet-action-btn" data-req-id="'.$time_sheet_record->id.'">
				<span class="icon text-white-50">
					<i class="fas fa-ban"></i>
				</span>
				<span class="text">Deny</span>
			</button>';
				$status = '<span class="badge badge-success">Approved</span>';
			}
			elseif($time_sheet_record->status == 'denied') {
				$action_btn .= '<a href="" onclick="return approve_deny_popup(1, '.$time_sheet_record->id.');" class=" ml-2 btn btn-success btn-icon-split btn-sm">
				<span class="icon text-white-50">
					<i class="fas fa-check"></i>
				</span>
				<span class="text">Approve</span>
			</a>';
				$status = '<span class="badge badge-danger">Denied</span><span role="button" class="icon" data-toggle="popover" title="Denied Reason" data-content="'.$denied_reason.'">
				<i class="fas fa-info"></i>
			</span>';
			}
			else {

				$action_btn .= '
				<a href="" onclick="return approve_deny_popup(1, '.$time_sheet_record->id.');" class=" ml-2 btn btn-success btn-icon-split btn-sm">
					<span class="icon text-white-50">
						<i class="fas fa-check"></i>
					</span>
					<span class="text">Approve</span>
				</a>
				<button type="button" class="ml-2 btn btn-danger btn-icon-split btn-sm timesheet-action-btn" data-req-id="'.$time_sheet_record->id.'">
					<span class="icon text-white-50">
						<i class="fas fa-ban"></i>
					</span>
					<span class="text">Deny</span>
				</button>';
			}


			$action_btn .= '</div>';
			$data[] = [
				($key + 1),
				$time_sheet_record->user->first_name.' '.$time_sheet_record->user->last_name,
				date('m/d/Y',$pay_period_start_time_stamp),
				date('m/d/Y',$pay_period_ends_time_stamp),
				date('m/d/Y',$pay_period_monday_time_stamp),
				$status,
				$updated_by,
				$action_btn

			];
		}
		$json_data['recordsTotal'] = intval( count($data));
		$json_data['recordsFiltered'] = intval( count($data));
		$json_data['data'] = $data;
		echo json_encode($json_data);


	}
    public function viewOtHours()
    {
        $data['title'] = 'HR-Center Employee OT Hours';
        $data['page_title'] = 'Employee OT Hours';
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
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=ot_'.$this->custom_js_version) );
        $this->admintemplate->show("hr", "ot_hours", $data);
    }

    public function getOtHours()
    {
        $this->load->model('frontend/hr/pct_hr_employee_time_tracking_model');
		$this->load->model('frontend/hr/pct_hr_employee_allowed_ot_model');
		$ot_data_all = $this->pct_hr_employee_time_tracking_model->get_ot_hours();
		$ot_data = array();
		$tmp_data = array();
		foreach($ot_data_all as $ot_data_record) {
			$record_date = date('Y_m_d',strtotime($ot_data_record->time_in));
			$record_date_key = $record_date.'__'.$ot_data_record->employee_id;
			$timestamp_in = strtotime($ot_data_record->time_in);
			$timestamp_out = strtotime($ot_data_record->time_out);
			if(empty($tmp_data[$record_date_key])) {
				$tmp_data[$record_date_key] = array();
				$tmp_data[$record_date_key]['employee'] = $ot_data_record->user->first_name.' '.$ot_data_record->user->last_name;
				$tmp_data[$record_date_key]['employee_id'] = $ot_data_record->employee_id;
				$tmp_data[$record_date_key]['record_date'] = strtotime($ot_data_record->time_in);
				$tmp_data[$record_date_key]['total_hours'] = $timestamp_out - $timestamp_in;
				
				$tmp_data[$record_date_key]['break_hours'] = 0;
			}
			else {
				$tmp_data[$record_date_key]['total_hours'] += ($timestamp_out - $timestamp_in);
				if($tmp_data[$record_date_key]['is_break']) {
					$tmp_data[$record_date_key]['break_hours'] += $timestamp_in - $tmp_data[$record_date_key]['last_time_out'];
				}
			}
			$tmp_data[$record_date_key]['last_time_out'] = $timestamp_out;
			$tmp_data[$record_date_key]['is_break'] = $ot_data_record->is_break;
		}

		krsort($tmp_data);

		$ot_recorded_data = $this->pct_hr_employee_allowed_ot_model->get_all();
		$tmp_ot_recorded_data = array();
		foreach($ot_recorded_data as $ot_recorded_record) {
			$record_date = date('Y_m_d',strtotime($ot_recorded_record->ot_date));
			$record_date_key = $record_date.'__'.$ot_recorded_record->employee_id;
			$tmp_ot_recorded_data[$record_date_key] = [
				'is_approved'=>$ot_recorded_record->is_approved
			];
		}
		$int_i = 1;
		foreach($tmp_data as $tmp_key=>$tmp_record) {
			if($tmp_record['break_hours'] > 3600) {
				$tmp_record['break_hours'] = 3600;
			}
			$total_hours = $tmp_record['break_hours'] + $tmp_record['total_hours'];
			if(($total_hours) > (9*60*60)) {
				$ot_seconds =  ($total_hours) - (9*60*60);
				$tmp_record['ot_hours'] = sprintf('%02d:%02d', ($ot_seconds/3600),($ot_seconds/60%60));
				$action = '';
				if(isset($tmp_ot_recorded_data[$tmp_key])){
					if($tmp_ot_recorded_data[$tmp_key]['is_approved']) {
						$action = '<h5><span class="badge badge-success">Approved</span></h5>';
					} else {
						$action = '<h5><span class="badge badge-danger">Rejected</span></h5>';
					}
				} else {
					$action ='<div style="display:inline-flex;" >
						<button class="btn btn-success btn-icon-split btn-sm ot-action-btn" data-user="'.$tmp_record['employee_id'].'" data-ot-date = "'.$tmp_record["record_date"].'" data-is-approved="1">
							<span class="icon text-white-50">
								<i class="fas fa-check"></i>
							</span>
							<span class="text">Approve</span>
						</button>
						<button style="margin-left: 5px;" class="btn btn-danger btn-icon-split btn-sm ot-action-btn" data-user="'.$tmp_record['employee_id'].'" data-ot-date = "'.$tmp_record["record_date"].'" data-is-approved="0">
							<span class="icon text-white-50">
								<i class="fas fa-ban"></i>
							</span>
							<span class="text">Reject</span>
						</button>
					</div>';
				}
				$ot_data[] = [
					$int_i++,
					$tmp_record['employee'],
					date('m/d/Y',$tmp_record['record_date']),
					sprintf('%02d:%02d', ($total_hours/3600),($total_hours/60%60)),
					$tmp_record['ot_hours'],
					$action,

				];
			}
		}
        $json_data['recordsTotal'] = intval( count($ot_data) );
        $json_data['recordsFiltered'] = intval( count($ot_data) );
        $json_data['data'] = $ot_data;
	    echo json_encode($json_data);


    }

	public function addOtRequest()
    {
		$this->form_validation->set_rules('employee_id', 'Employee', 'required');
		$this->form_validation->set_rules('ot_date', 'Date', 'required');
		$this->form_validation->set_rules('status', 'Action', 'required');
		$added_by =$this->session->userdata('hr_admin')['id'];
        $inserted_id = 0;
        if ($this->form_validation->run() == true) {
			$ot_data_insert = [
				'employee_id'=>$this->input->post('employee_id'),
				'ot_date'=>date('Y-m-d',$this->input->post('ot_date')),
				'allowed_by'=>$added_by,
				'is_approved'=>$this->input->post('status'),

			];
			$this->load->model('frontend/hr/pct_hr_employee_allowed_ot_model');
			$inserted_id = $this->pct_hr_employee_allowed_ot_model->insert($ot_data_insert);
		}

		if($inserted_id) {
            $success = "OT Hours request changed successfully.";
        } else {
            $errors = "Something went wrong. Please try again.";
        }

		$data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url('hr/admin/ot-hours'));
	}

	

	function viewTimeSheet($pay_period_start,$emp_id) {
		
		// $current_date = $this->common->convertTimezone(date('Y-03-d H:i:s'),'Y-m-d H:i:s','America/Los_Angeles');
		$this->load->model('hr/users_model');
		$userdata = $this->users_model->get($emp_id);
		if(empty($userdata)) {
			$data = array(
				"errors" =>  'Invalid Request',
				"success" => ''
			);
			$this->session->set_userdata($data);
			redirect(base_url('hr/admin/users'));
		}
		//check branch manager user 
		$logged_in_userdata = $this->session->userdata('hr_admin');
        $usersIds = array();
        if(!empty($logged_in_userdata)) {
            if ($logged_in_userdata['user_type_id'] == 4) {
                $usersForBranchManager = $this->common->getUsersForBranchManager($logged_in_userdata['id']);
                if(!empty($usersForBranchManager)) {
                    $usersIds = array_column($usersForBranchManager, 'id');
                } else {
                    return array(
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => array()
                    );
                }
				if(!(in_array($emp_id,$usersIds))) {
					$data = array(
						"errors" =>  'Invalid Request',
						"success" => ''
					);
					$this->session->set_userdata($data);
					redirect(base_url('hr/admin/users'));
				}
            } 
        }
		$userdata = (array)$userdata;
		$userdata['name'] = $userdata['first_name'].' '.$userdata['last_name'];
		
		$first_date = date('Y-m-d', $pay_period_start);
		$last_date = date('Y-m-d',strtotime("+13 day",$pay_period_start));


		$data = array();
		$data['user'] = $userdata;
		$data['first_date'] = $first_date;
		$data['last_date'] = $last_date;
		
		$this->load->model('frontend/hr/pct_hr_employee_time_tracking_model');
		$tracking_data = $this->pct_hr_employee_time_tracking_model->get_time_sheet($first_date,$last_date,$userdata['id']);

		/* Get Approved Ot hours Start */
		$this->load->model('frontend/hr/pct_hr_employee_allowed_ot_model');
		$ot_where = [
			'ot_date >='=>$first_date,
			'ot_date <='=>$last_date,
			'employee_id' => $userdata['id'],
			'is_approved' => 1
		];
		$ot_allowed_data = $this->pct_hr_employee_allowed_ot_model->get_many_by($ot_where);
		$data['ot_approved_dates'] = array_column($ot_allowed_data,'ot_date');
		/* Get Approved Ot hours Start */

		/* Get Time card Request End */
		$this->load->model('hr/timecards_model');
		$timecard_where = [
			'exception_date >='=>$first_date,
			'exception_date <='=>$last_date,
			'user_id' => $userdata['id'],
			'status' => 'approved',
			'approved_by_user_id != ' => NULL
		];
		$timecard_exception_data = $this->timecards_model->get_many_by($timecard_where);
		$data['timecard_exception_data'] = $timecard_exception_data;
		/* Get Time card Request End*/
		$time_sheet_array_tmp = $time_sheet_array = array();
		foreach($tracking_data as $tracking_record) {
			
			$record_date =  date('Y-m-d',strtotime($tracking_record->time_in));
			if(!isset($time_sheet_array_tmp[$record_date])) {
				$time_sheet_array_tmp[$record_date] = array();
			}
				$time_sheet_array_tmp[$record_date][] =array(
					'time_in' => strtotime($tracking_record->time_in),
					'time_out' => strtotime($tracking_record->time_out),
					'is_break' => $tracking_record->is_break,
				);
		}
		$process_date = strtotime($first_date);
		while($process_date <= strtotime($last_date))
		{
			$record_date =  date('Y-m-d',$process_date);

			if(!isset($time_sheet_array_tmp[$record_date])) {
				$random_time = $random_lunch_start = $random_lunch_end = $random_end_time= "-";
				$random_reg_hours = 0;

				$time_sheet_array[$record_date] = [
					'start_time' => $random_time,
					'lunch_start' => $random_lunch_start,
					'lunch_end' => $random_lunch_end,
					'end_time' => $random_end_time,
					'reg_hours'=>$random_reg_hours,
					'ot_hours'=>'0',
					'double_ot_hours'=>'0',
					'lunch_hours'=>'0',
					'unpaid_hours'=>'0',
				];
			}
			else {
				$total_hours = 0;
				$lunch_hours = 0;
				$unpaid_hours = 0;

				if(count($time_sheet_array_tmp[$record_date]) == 1) {
					
					$time_sheet_array[$record_date]['start_time'] = $time_sheet_array_tmp[$record_date][0]['time_in'];
					$time_sheet_array[$record_date]['end_time'] = $time_sheet_array_tmp[$record_date][0]['time_out'];
					$total_hours = $time_sheet_array_tmp[$record_date][0]['time_out'] - $time_sheet_array_tmp[$record_date][0]['time_in'];

				}
				else {

					$was_break = 0;
					$total_hours = $lunch_hours = $unpaid_hours = $last_tracked_time = $hours_counted = 0;
					
					foreach($time_sheet_array_tmp[$record_date] as $key=>$track_time) {
						$hours_counted = 0;
						if($key==0) {
							$time_sheet_array[$record_date]['start_time'] = $track_time['time_in'];
							if($track_time['is_break'] == 1  && $key<count($time_sheet_array_tmp) && empty($time_sheet_array[$record_date]['lunch_start'])) {
								
								$time_sheet_array[$record_date]['lunch_start'] = $track_time['time_out'];
								$was_break = 1;
							}
						}
						elseif($was_break) {
							$time_sheet_array[$record_date]['lunch_end'] = $track_time['time_in'];
							$was_break = 0;
							$lunch_hours += $track_time['time_in'] - $last_tracked_time;
							$hours_counted = 1;
						}
						elseif($track_time['is_break'] == 1  && $key<count($time_sheet_array_tmp) && empty($time_sheet_array[$record_date]['lunch_start'])) {
							$time_sheet_array[$record_date]['lunch_start'] = $track_time['time_out'];
							$was_break = 1;
							
						}
						if(($key+1) == count($time_sheet_array_tmp[$record_date])) {
							$time_sheet_array[$record_date]['end_time'] = $track_time['time_out'];
							// $working_hours += $track_time['time_out'] - $track_time['time_in'];

						}
						if($hours_counted == 0 && $key>0) {
							$unpaid_hours += $track_time['time_in'] - $last_tracked_time;
						}
						$last_tracked_time = $track_time['time_out'];
						$total_hours += $track_time['time_out'] - $track_time['time_in'];

					}

				}
				if(empty($time_sheet_array[$record_date]['lunch_start'])) {
					$time_sheet_array[$record_date]['lunch_start'] = '-';
					$time_sheet_array[$record_date]['lunch_end'] = '-';
				}
				$time_sheet_array[$record_date]['ot_hours'] = '0';
				$time_sheet_array[$record_date]['double_ot_hours'] = '0';
				$time_sheet_array[$record_date]['reg_hours'] = $total_hours;
				$time_sheet_array[$record_date]['lunch_hours'] = $lunch_hours;
				$time_sheet_array[$record_date]['unpaid_hours'] = $unpaid_hours;
				
			}
			$process_date = strtotime("+1 day", $process_date);
		}
		$data['time_sheet_array'] = $time_sheet_array;
		$this->load->view('frontend/hr/time_sheet',$data);
	}
}
