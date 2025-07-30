<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class IncidentReports extends MX_Controller 
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
        $data['employee_info'] = $this->common->get_hr_user(array('id' => $userdata['id']));
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $data['name'] = $userdata['name'];
		$data['title'] = 'HR-Center Incident Reports';
        $this->template->show("hr", "incident_reports", $data);
	}

    public function  getIncidentReports()
    {
        $userdata = $this->session->userdata('hr_user');
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
			$incidentReportsList = $this->common->getIncidentReports($params);
			$json_data['draw'] = intval( $params['draw'] );
		} else {
			$params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
			$incidentReportsList = $this->common->getIncidentReports($params);
		}
		
		if (isset($incidentReportsList['data']) && !empty($incidentReportsList['data'])) {
			$i = $params['start'] + 1;
			foreach ($incidentReportsList['data'] as $incidentReport)  {
				$nestedData = array();
				$nestedData[] = $i;
                $nestedData[] =  date("m/d/Y", strtotime($incidentReport['incident_date']));
				$nestedData[] = $incidentReport['first_name']." ".$incidentReport['last_name'];
                $nestedData[] = $incidentReport['incident_reason'];
                $nestedData[] = $incidentReport['num_of_incidents'];
                $nestedData[] = $incidentReport['actions'];
				$update_date = '-';
                
                $status = '<span class="badge-new badge-new-info">Pending</span>';
                if (!empty($incidentReport['approved_by_user_id']) ) {
                    if ($incidentReport['status'] == 'approved') {
                        $status = '<span class="badge-new badge-new-success">Approved</span>';
                    } else {
                        $status = '<span class="badge-new badge-new-danger">Denied</span>';
                    }
					if(strtotime($incidentReport['approved_date'])) {
						$update_date = $this->common->convertTimezone($incidentReport['approved_date'],'m/d/Y');
					}
					elseif(strtotime($incidentReport['updated_at'])) {
						$update_date = $this->common->convertTimezone($incidentReport['updated_at'],'m/d/Y');
					}
                }
                $nestedData[] = $status;
                
                if (!empty($incidentReport['approved_by_user_id'])) {
                    $nestedData[] = $incidentReport['branch_manager_first_name']." ".$incidentReport['branch_manager_last_name'];
                } else {
                    $nestedData[] = ''  ;
                }
				$nestedData[] = $update_date;

                // $nestedData[] = !empty($incidentReport['approved_date']) ? date("m/d/Y", strtotime($incidentReport['approved_date'])) : '';
				$data[] = $nestedData; 
				$i++; 
			}
		}

		$json_data['recordsTotal'] = intval( $incidentReportsList['recordsTotal'] );
		$json_data['recordsFiltered'] = intval( $incidentReportsList['recordsFiltered'] );
		$json_data['data'] = $data;
		echo json_encode($json_data);
    }

    public function saveIncidentReports()
    {
        $userdata = $this->session->userdata('hr_user');
        $errors = array();
        $success = array();
       
        $timeCardsData = array(
            'user_id' => $userdata['id'],
            'incident_date' => date("Y-m-d", strtotime($this->input->post('incident_date'))),
            'employee_number' => $this->input->post('employee_number'),
            'incident_reason' => $this->input->post('incident_reason'),
            'incident_detail' => $this->input->post('incident_detail'),
            'actions' => implode(",", $this->input->post('actions')),
            'num_of_incidents' => implode(",", $this->input->post('num_of_incidents'))
        );
        $id = $this->hr->insert($timeCardsData, 'pct_hr_incident_reports');

        $incident_date = date("F d, Y", strtotime($this->input->post('incident_date')));
        $message = 'Incident Report request of '.$incident_date.' has submitted by '.$userdata['name'];
        $this->load->model('hr/users_model');
        $userInfo = $this->users_model->get($userdata['id']);
        $branchUserInfo = $this->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
        $notificationData = array(
            'sent_user_id' => $branchUserInfo->id,
            'message' => $message,
            'type' => 'submitted'
        );
        $this->hr->insert($notificationData, 'pct_hr_notifications');
        $this->common->sendNotification($message, 'submitted', $branchUserInfo->id, 1);

        $superadminInfo = $this->users_model->get_by('user_type_id', 1);
        $notificationData['sent_user_id'] = $superadminInfo->id;
        $this->hr->insert($notificationData, 'pct_hr_notifications');
        $this->common->sendNotification($message, 'submitted', $superadminInfo->id, 1);
        
        if(!empty($id)) {
            $success[] = "Incident Report saved successfully.";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        
        $data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url().'hr/incident-reports');
    }

}
