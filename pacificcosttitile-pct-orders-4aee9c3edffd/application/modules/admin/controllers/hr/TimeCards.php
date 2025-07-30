<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timecards extends MX_Controller {

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
        $data['title'] = 'HR-Center Time Cards';
        $data['page_title'] = 'Time Cards';
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
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js>v=tc_0.1') );
        $this->admintemplate->show("hr", "time_cards", $data);
    }

    public function getTimeCards()
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
				$nestedData[] = $timeCard['total_hours'];
                $status = '<span class="badge badge-info">Pending</span>';
                if (!empty($timeCard['approved_by_user_id'])) {
                    if ($timeCard['status'] == 'approved') {
                        $status = '<span class="badge badge-success">Approved</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Denied</span><span role="button" class="icon" data-toggle="popover" title="Denied Reason" data-content="'.$denied_reason.'">
						<i class="fas fa-info"></i>
					</span>';
                    }
                }
                $nestedData[] = $status;
                if (!empty($timeCard['approved_by_user_id'])) {
                    $nestedData[] = $timeCard['branch_manager_first_name']." ".$timeCard['branch_manager_last_name'];
                } else {
                    $nestedData[] = ''  ;
                }

                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    if($userdata['id'] != $timeCard['user_id']) {
                        if (!empty($timeCard['approved_by_user_id'])) {
                            if ($timeCard['status'] == 'approved') {
                                $nestedData[] = '
                                        <button type="button" class="btn btn-danger btn-icon-split btn-sm timecard-action-btn" data-req-id="'.$timeCard["id"].'">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-ban"></i>
                                            </span>
                                            <span class="text">Deny</span>
                                        </button>';
                            } else {
                                $nestedData[] = '<a href="" onclick="return approve_deny_popup(1, '.$timeCard["id"].');" class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Approve</span>
                                        </a>
                                        '; 
                            }
                        } else {
                            $nestedData[] = '<div style="display:inline-flex;">
                                            <a href="" onclick="return approve_deny_popup(1, '.$timeCard["id"].');" class="btn btn-success btn-icon-split btn-sm">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                                <span class="text">Approve</span>
                                            </a>
											<button type="button" class="ml-2 btn btn-danger btn-icon-split btn-sm timecard-action-btn" data-req-id="'.$timeCard["id"].'">
												<span class="icon text-white-50">
													<i class="fas fa-ban"></i>
												</span>
												<span class="text">Deny</span>
											</button>
                                        </div>'; 
                        }
                    } else {
                        $nestedData[] = ''  ;
                    }
                }
				$data[] = $nestedData; 
				$i++; 
			}
		}

		$json_data['recordsTotal'] = intval( $timeCardsList['recordsTotal'] );
		$json_data['recordsFiltered'] = intval( $timeCardsList['recordsFiltered'] );
		$json_data['data'] = $data;
		echo json_encode($json_data);
    }

    public function addTimeCard()
    {
        $userdata = $this->session->userdata('hr_admin');
        $data['title'] = 'HR-Center Time Card';
        $data['page_title'] = 'Add New Time Card';
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
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/custom.js?v=tc_0.2') );
        $this->admintemplate->show("hr", "add_time_card", $data);
    }

	public function saveTimeCards()
    {
        $userdata = $this->session->userdata('hr_admin');
        $this->load->model('hr/users_model');
        $userInfo = $this->users_model->get($this->input->post('select_employee'));
        $ids = array();
        $exception_dates = $this->input->post('exception_date');
        $reg_hours = $this->input->post('reg_hours');
        $ot_hours = $this->input->post('ot_hours');
        $double_ot = $this->input->post('double_ot');
        $total_hours = $this->input->post('total_hours');
        $comment = $this->input->post('comment');
        $i = 0;

        foreach($exception_dates as $exception_date) {
			$last_id = 0;
            $timeCardsData = array(
                'user_id' =>  $this->input->post('select_employee'),
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
            $notificationData = array(
                'sent_user_id' => $this->input->post('select_employee'),
                'message' => $message,
                'type' =>  'submitted'
            );
            $this->hr->insert($notificationData, 'pct_hr_notifications');
            $this->common->sendNotification($message, 'submitted', $this->input->post('select_employee'), 0);

			//Send Mail to User
			$this->common->mailNotification($this->input->post('select_employee'),'time_card',$last_id);

            $message = 'Timecard request of '.$exceptionDate.' has submitted for employee '.$userInfo->first_name." ".$userInfo->last_name.' by '.$userdata['name'];
            $notificationData['message'] = $message;
            if ($userdata['user_type_id'] == 4) {
                $superadminInfo = $this->users_model->get_by('user_type_id', 1);
                $notificationData['sent_user_id'] = $superadminInfo->id;
                $this->hr->insert($notificationData, 'pct_hr_notifications');
                $this->common->sendNotification($message, 'submitted', $superadminInfo->id, 1);

				//Send Mail to Admin
				$this->common->mailNotification($superadminInfo->id,'time_card',$last_id);
            } else {
                $branchUserInfo = $this->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
                $notificationData['sent_user_id'] = $branchUserInfo->id;
                $this->hr->insert($notificationData, 'pct_hr_notifications');
                $this->common->sendNotification($message, 'submitted', $branchUserInfo->id, 1);

				//Send Mail to Manager
				$this->common->mailNotification($branchUserInfo->id,'time_card',$last_id);
            }
            $i++;
        }
        if(!empty($ids)) {
            $success = "Time Cards saved successfully.";
        } else {
            $errors = "Something went wrong. Please try again.";
        }
        
        $data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url().'hr/admin/time-cards');
    }

    public function deleteTimeCard()
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
