<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Dashboard extends MX_Controller 
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
	
	function index()
	{
		$userdata = $this->session->userdata('hr_user');
		$this->load->model('hr/pct_hr_employee_time_tracking_model');
		$clock_event = $this->pct_hr_employee_time_tracking_model->get_clock_event($userdata['id']);
		$get_today_working = $this->pct_hr_employee_time_tracking_model->get_today_working($userdata['id']);
		$get_last_time = $this->pct_hr_employee_time_tracking_model->get_last_time($userdata['id']);
		$data['clock_event'] = $clock_event;
		$data['time_tracking'] = $get_today_working + $get_last_time;
		$data['title'] = 'HR-Center Employee Dashboard';
		// var_dump($get_today_working);die;
		$this->template->show("hr/employee", "dashboard", $data);
	}

	function logout()
	{
		$userdata = $this->session->userdata('hr_user');
		if(!empty($userdata['id'])) {
			$this->load->model('hr/pct_hr_employee_time_tracking_model');
			$clock_event = $this->pct_hr_employee_time_tracking_model->get_clock_event($userdata['id']);
			if($clock_event == 'OUT') {
				$this->pct_hr_employee_time_tracking_model->track_time($userdata['id'],$clock_event);
			}
		}
		$this->session->sess_destroy();
		$this->session->unset_userdata('hr_user');
		redirect(base_url().'hr');
	}
}
