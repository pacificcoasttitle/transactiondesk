<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IncidentReports extends MX_Controller {

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
        $data['title'] = 'HR-Center Report Incident';
        $data['page_title'] = 'Report Incident';
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
		$userdata = $this->session->userdata('hr_admin');
		$show_action = false;
		if($userdata['user_type_id'] == 1 || $userdata['user_type_id'] == 2 ) {
			$show_action = true;
		}
		$data['show_action'] = $show_action;
		$this->admintemplate->addCSS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.css'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/jquery.dataTables.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.js'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js') );
        $this->admintemplate->show("hr", "incident_reports", $data);
    }

    public function  getIncidentReports()
    {
        $params = array();  $data = array();
        $params['is_frontend'] = 0;
        $userdata = $this->session->userdata('hr_admin');
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

                $status = '<span class="badge badge-info">Pending</span>';
                if (!empty($incidentReport['approved_by_user_id'])) {
                    if ($incidentReport['status'] == 'approved') {
                        $status = '<span class="badge badge-success">Approved</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Denied</span>';
                    }
                }
                $nestedData[] = $status;

                if (!empty($incidentReport['approved_by_user_id'])) {
                    $nestedData[] = $incidentReport['branch_manager_first_name']." ".$incidentReport['branch_manager_last_name'];
                } else {
                    $nestedData[] = ''  ;
                }

                if(isset($_POST['draw']) && !empty($_POST['draw'])) {
                    if ($userdata['id'] != $incidentReport['user_id'] &&  ($userdata['user_type_id'] == 1 || $userdata['user_type_id'] == 2)) {
                        if (!empty($incidentReport['approved_by_user_id'])) {
                            if ($incidentReport['status'] == 'approved') {
                                $nestedData[] = '
                                        <a href="#" onclick="return approve_deny_popup(0, '.$incidentReport["id"].');" class="btn btn-danger btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-ban"></i>
                                            </span>
                                            <span class="text">Deny</span>
                                        </a>';
                            } else {
                                $nestedData[] = '<a href="" onclick="return approve_deny_popup(1, '.$incidentReport["id"].');" class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Approve</span>
                                        </a>
                                        '; 
                            }
                        } else {
                            $nestedData[] = '<div style="display:inline-flex;">
                                                <a href="" onclick="return approve_deny_popup(1, '.$incidentReport["id"].');" class="btn btn-success btn-icon-split btn-sm">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                    <span class="text">Approve</span>
                                                </a>
                                                <a style="margin-left: 5px;" href="#" onclick="return approve_deny_popup(0, '.$incidentReport["id"].');" class="btn btn-danger btn-icon-split btn-sm">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-ban"></i>
                                                    </span>
                                                    <span class="text">Deny</span>
                                                </a>
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
		$json_data['recordsTotal'] = intval( $incidentReportsList['recordsTotal'] );
		$json_data['recordsFiltered'] = intval( $incidentReportsList['recordsFiltered'] );
		$json_data['data'] = $data;
		echo json_encode($json_data);
    }

    public function deleteIncidentReport()
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

	public function addIncident()
	{
        $userdata = $this->session->userdata('hr_admin');
        $this->load->model('hr/users_model');
		$data['title'] = 'HR-Center Report Incident';
        $data['page_title'] = 'Report new incident';
		$this->load->model('hr/users_model');
        if ($userdata['user_type_id'] == 4) {
            $userInfo = $this->users_model->get($userdata['id']);
            if ($userdata['department_id'] == 4) {
                $users = $this->users_model->with('position')->get_many_by('department_id', 4);
            } else {
                $users = $this->users_model->with('position')->get_many_by('branch_id', $userInfo->branch_id);
            }
        } else {
            $users = $this->users_model->with('position')->get_all();
        }
		$data['employees'] = $users;
		$this->admintemplate->addCSS( base_url('assets/frontend/hr/css/smart-forms.css?v=0.1') );
        $this->admintemplate->addCSS( base_url('css/smart-addons.css') );
		$this->admintemplate->addJS( base_url('assets/frontend/hr/js/jquery-ui-custom.min.js') );
		$this->admintemplate->addJS( base_url('assets/frontend/js/jquery.steps.min.js') );
		$this->admintemplate->addJS( base_url('assets/frontend/js/jquery.validate.min.js') );
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=inc_0.2') );
        $this->admintemplate->show("hr", "add_incident_report", $data);
	}

	public function saveIncidentReports()
    {
        $userdata = $this->session->userdata('hr_admin');
        $this->load->model('hr/users_model');
        $userInfo = $this->users_model->get($this->input->post('select_employee'));
        $incidentData = array(
            'user_id' => $this->input->post('select_employee'),
            'incident_date' => date("Y-m-d", strtotime($this->input->post('incident_date'))),
            //'employee_number' => $this->input->post('employee_number'),
            'incident_reason' => $this->input->post('incident_reason'),
            'incident_detail' => $this->input->post('incident_detail'),
            'actions' => implode(",", $this->input->post('actions')),
            'num_of_incidents' => implode(",", $this->input->post('num_of_incidents'))
        );
        $id = $this->hr->insert($incidentData, 'pct_hr_incident_reports');
        $incident_date = date("F d, Y", strtotime($this->input->post('incident_date')));
        $message = 'Incident Report request of '.$incident_date.' has submitted by '.$userdata['name'];
        $notificationData = array(
            'sent_user_id' => $this->input->post('select_employee'),
            'message' => $message,
            'type' =>  'submitted'
        );
        $this->hr->insert($notificationData, 'pct_hr_notifications');
        $this->common->sendNotification($message, 'submitted', $this->input->post('select_employee'), 0);

		//Send Mail to User
		$this->common->mailNotification($this->input->post('select_employee'),'incident_report',$id);

        $message = 'Incident Report request of '.$incident_date.' has submitted for employee '.$userInfo->first_name." ".$userInfo->last_name.' by '.$userdata['name'];
        $notificationData['message'] = $message;
        if ($userdata['user_type_id'] == 4) {
            $superadminInfo = $this->users_model->get_by('user_type_id', 1);
            $notificationData['sent_user_id'] = $superadminInfo->id;
            $this->hr->insert($notificationData, 'pct_hr_notifications');
            $this->common->sendNotification($message, 'submitted', $superadminInfo->id, 1);

			//Send Mail to Super Admin
			$this->common->mailNotification($superadminInfo->id,'incident_report',$id);

        } else {
            $branchUserInfo = $this->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
            $notificationData['sent_user_id'] = $branchUserInfo->id;
            $this->hr->insert($notificationData, 'pct_hr_notifications');
            $this->common->sendNotification($message, 'submitted', $branchUserInfo->id, 1);

			//Send Mail to Manager
			$this->common->mailNotification($branchUserInfo->id,'incident_report',$id);
        }
        
        if(!empty($id)) {
            $success = "Incident Report saved successfully.";
        } else {
            $errors = "Something went wrong. Please try again.";
        }
        
        $data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url().'hr/admin/incident-reports');
    }
}
