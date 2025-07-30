<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller {

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

    private $dashboard_js_version = '06';
	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('form_validation');
        $this->load->library('hr/adminTemplate');
        $this->load->library('hr/common');
        $this->load->model('hr/timecards_model');
        $this->load->model('hr/vacation_request_model');
        $this->load->model('hr/report_incident_model');
        $this->load->model('hr/training_status_model');
		$this->load->model('hr/users_model');
		$this->load->library('order/order');
        $this->common->is_hr_admin();
    }

    public function index()
    {		
		$userdata = $this->session->userdata('hr_admin');
        $data['title'] = 'HR-Center Admin Dashboard';
        $data['page_title'] = 'Dashboard';
	
        if ($userdata['user_type_id'] == '4' || $userdata['department_id'] == '4') {
			$usersForBranchManager = $this->common->getUsersForBranchManager($userdata['id']);
			$usersIds = array_column($usersForBranchManager, 'id');
			$user_ids = implode(',' , $usersIds);
			$data['pending_timecard_count'] = !empty($user_ids) ? $this->timecards_model->count_by("approved_date is NULL and user_id in ($user_ids)") : 0;
			$data['pending_vacation_request_count'] =!empty($user_ids) ? $this->vacation_request_model->count_by("approved_date is NULL and user_id in ($user_ids)") : 0;
			$data['pending_report_incident_count'] = !empty($user_ids) ? $this->report_incident_model->count_by("approved_date is NULL and user_id in ($user_ids)") : 0;
			$userid = $userdata['id'];
			$data['pending_training_count'] = $this->training_status_model->count_by("is_complete= 0 and user_id = $userid");
		} else {
			$data['pending_timecard_count'] = $this->timecards_model->count_by('approved_date', null);
			$data['pending_vacation_request_count'] = $this->vacation_request_model->count_by('approved_date', null);
			$data['pending_report_incident_count'] = $this->report_incident_model->count_by('approved_date', null);
			$data['pending_training_count'] = $this->training_status_model->count_by('is_complete', 0);
		}

        $this->admintemplate->addCSS( base_url('assets/libs/calendar/main.css'));
        $this->admintemplate->addJS( base_url('assets/libs/calendar/main.js'));
        $this->admintemplate->addJS( base_url('assets/backend/hr/js/dashboard.js?v=dashboard_'.$this->dashboard_js_version) );
        $this->admintemplate->show("hr", "dashboard", $data);
    }

    public function getVacationDataForCalendar()
    {
        $start = date('Y-m-d', strtotime($this->input->post('start')));
        $end = date('Y-m-d', strtotime($this->input->post('end')));
        $vacationData = $this->common->getVacationDataForCalendar($start, $end);
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

	public function getDashboardCount()
	{
		$userdata = $this->session->userdata('hr_admin');
		$data['month'] = $month = !empty($this->input->post('month')) ? $this->input->post('month') : date('m');
		$data['user_id'] = $user_id = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : 0;
		$data['manager_id'] = $manager_id = !empty($this->input->post('manager_id')) ? $this->input->post('manager_id') : 0;
		$data['users'] = array();
		$data['managers'] = array();
		$usersEmails = array();
		$usersIds = array();

		if ($userdata['department_id'] == '4') {
			$workedDays = $this->order->countWorkedDaysOfMonth();
			$workingDaysRemaining = $this->order->countWokingsDaysLeftOfMonth();
			$openRefiResult = $this->order->getOpenOrdersCountForRefiProducts($month, $usersIds, 0, 1);
			$data['refi_open_count'] = !empty($openRefiResult['refi_count']) ? $openRefiResult['refi_count'] : 0;
			$openSaleResult = $this->order->getOpenOrdersCountForSaleProducts($month, $usersIds, 0, 1);
			$data['sale_open_count'] = !empty($openSaleResult['sale_count']) ? $openSaleResult['sale_count'] : 0;
			$data['total_open_count'] = $data['sale_open_count'] + $data['refi_open_count'];

			if ($data['total_open_count'] > 0) {
				$numOfOpenOrderPerWorkedDays = $data['total_open_count']/$workedDays;
				$data['projected_open_count'] = (round($numOfOpenOrderPerWorkedDays*$workingDaysRemaining))+ $data['total_open_count'];
			} else {
				$numOfOpenOrderPerWorkedDays = 0;
				$data['projected_open_count'] = 0;
			}
			$data['projected_open_count'] = 0;
			$closeRefiResult = $this->order->getClosedOrdersCountForRefiProducts($month, $usersIds, 0, 1);
			$data['refi_close_count'] = !empty($closeRefiResult['refi_count']) ? $closeRefiResult['refi_count'] : 0;
			$closeSaleResult = $this->order->getClosedOrdersCountForSaleProducts($month, $usersIds, 0, 1);
			$data['sale_close_count'] =  !empty($closeSaleResult['sale_count']) ? $closeSaleResult['sale_count'] : 0;
			$data['total_close_count'] = $data['refi_close_count'] + $data['sale_close_count'];

			if ($data['total_close_count'] > 0) {
				$numOfCloseOrderPerWorkedDays = $data['total_close_count']/$workedDays;
				$data['projected_close_count'] = (round($numOfCloseOrderPerWorkedDays*$workingDaysRemaining))+ $data['total_close_count'];
			} else {
				$numOfCloseOrderPerWorkedDays = 0;
				$data['projected_close_count'] = 0;
			}
			$data['projected_close_count'] = 0;
			$closeOrderRefiTotalPremium =  !empty($closeRefiResult['total_escrow_amount_for_refi_close_orders']) ? $closeRefiResult['total_escrow_amount_for_refi_close_orders'] : 0;
			$data['refi_total_premium'] = $closeOrderRefiTotalPremium;
			$closeOrderSaleTotalPremium =  !empty($closeSaleResult['total_escrow_amount_for_sale_close_orders']) ? $closeSaleResult['total_escrow_amount_for_sale_close_orders'] : 0;
			$data['sale_total_premium'] = $closeOrderSaleTotalPremium;
			$data['total_premium'] = $data['sale_total_premium'] + $data['refi_total_premium'];

			if ($data['total_premium'] > 0) {
				$premiumWorkedDays = $data['total_premium']/$workedDays;
				$data['projected_revenue'] = (round($premiumWorkedDays*$workingDaysRemaining))+ $data['total_premium'];
			} else {
				$premiumWorkedDays = 0;
				$data['projected_revenue'] = 0;
			}
			$data['projected_revenue'] = 0;
			$totalCount = $data['sale_close_count'] + $data['refi_close_count'] + $data['sale_open_count'] + $data['refi_open_count'];

			if($totalCount > 0) { 
				$data['refi_close_order_percetage'] = round(($data['refi_close_count']*100)/$totalCount);
				$data['sale_close_order_percetage'] = round(($data['sale_close_count']*100)/$totalCount);
				$data['close_order_percetage'] = $data['refi_close_order_percetage'] + $data['sale_close_order_percetage'];
			} else {
				$data['refi_close_order_percetage'] = 0;
				$data['sale_close_order_percetage'] = 0;
				$data['close_order_percetage'] = 0;
			}
		} else {
			if ($userdata['user_type_id'] == '4') {
				$users = $this->common->getUsersForBranchManager($userdata['id']);
				$data['users'] = $users;
			} else {
				$data['managers'] = $this->users_model->get_many_by('user_type_id', '4');
				if (!empty($manager_id) && $manager_id != 'all_managers') {
					$users = $this->common->getUsersForBranchManager($manager_id);
				} else {
					$users = json_decode(json_encode($this->users_model->get_many_by('(user_type_id != 1 and user_type_id != 2)')), true);
				}
				$data['users'] = $users;
			}
	
			if (!empty($users)) {
				if (!empty($user_id) && $user_id != 'all_users') {
					$key = array_search($user_id, array_column($users, 'id'));
					if (isset($key) && strlen($key) > 0) {
						if (str_contains($users[$key]['pct_order_email'], ',')) {
							$usersEmails = explode(',', $users[$key]['pct_order_email']);
						} else {
							$usersEmails[] = $users[$key]['pct_order_email'];
						}
					} else {
						$usersEmails = array_column($users, 'pct_order_email');	
						$usersEmails = array_filter($usersEmails, function($value) {
							return strstr($value, ',') === false;
						});
						$usersEmails[] = $userdata['email'];
					}
				} else {
					$usersEmails = array_column($users, 'pct_order_email');	
					$usersEmails = array_filter($usersEmails, function($value) {
						return strstr($value, ',') === false;
					});
					$usersEmails[] = $userdata['email'];
				}
				$pctOrderUserInfo = $this->order->getUsersInfo($usersEmails);
				if (!empty($pctOrderUserInfo)) {
					$usersIds = array_column($pctOrderUserInfo, 'id');	
					$workedDays = $this->order->countWorkedDaysOfMonth();
					$workingDaysRemaining = $this->order->countWokingsDaysLeftOfMonth();
					$openRefiResult = $this->order->getOpenOrdersCountForRefiProducts($month, $usersIds);
					$data['refi_open_count'] = !empty($openRefiResult['refi_count']) ? $openRefiResult['refi_count'] : 0;
					$openSaleResult = $this->order->getOpenOrdersCountForSaleProducts($month, $usersIds);
					$data['sale_open_count'] = !empty($openSaleResult['sale_count']) ? $openSaleResult['sale_count'] : 0;
					$data['total_open_count'] = $data['sale_open_count'] + $data['refi_open_count'];
	
					if ($data['total_open_count'] > 0) {
						$numOfOpenOrderPerWorkedDays = $data['total_open_count']/$workedDays;
						$data['projected_open_count'] = (round($numOfOpenOrderPerWorkedDays*$workingDaysRemaining))+ $data['total_open_count'];
					} else {
						$numOfOpenOrderPerWorkedDays = 0;
						$data['projected_open_count'] = 0;
					}
					$data['projected_open_count'] = 0;
					$closeRefiResult = $this->order->getClosedOrdersCountForRefiProducts($month, $usersIds);
					$data['refi_close_count'] = !empty($closeRefiResult['refi_count']) ? $closeRefiResult['refi_count'] : 0;
					$closeSaleResult = $this->order->getClosedOrdersCountForSaleProducts($month, $usersIds);
					$data['sale_close_count'] =  !empty($closeSaleResult['sale_count']) ? $closeSaleResult['sale_count'] : 0;
					$data['total_close_count'] = $data['refi_close_count'] + $data['sale_close_count'];
	
					if ($data['total_close_count'] > 0) {
						$numOfCloseOrderPerWorkedDays = $data['total_close_count']/$workedDays;
						$data['projected_close_count'] = (round($numOfCloseOrderPerWorkedDays*$workingDaysRemaining))+ $data['total_close_count'];
					} else {
						$numOfCloseOrderPerWorkedDays = 0;
						$data['projected_close_count'] = 0;
					}
					$data['projected_close_count'] = 0;
					$closeOrderRefiTotalPremium =  !empty($closeRefiResult['total_premium_for_refi_close_orders']) ? $closeRefiResult['total_premium_for_refi_close_orders'] : 0;
					$data['refi_total_premium'] = $closeOrderRefiTotalPremium;
					$closeOrderSaleTotalPremium =  !empty($closeSaleResult['total_premium_for_sale_close_orders']) ? $closeSaleResult['total_premium_for_sale_close_orders'] : 0;
					$data['sale_total_premium'] = $closeOrderSaleTotalPremium;
					$data['total_premium'] = $data['sale_total_premium'] + $data['refi_total_premium'];
	
					if ($data['total_premium'] > 0) {
						$premiumWorkedDays = $data['total_premium']/$workedDays;
						$data['projected_revenue'] = (round($premiumWorkedDays*$workingDaysRemaining))+ $data['total_premium'];
					} else {
						$premiumWorkedDays = 0;
						$data['projected_revenue'] = 0;
					}
					$data['projected_revenue'] = 0;
					$totalCount = $data['sale_close_count'] + $data['refi_close_count'] + $data['sale_open_count'] + $data['refi_open_count'];
	
					if($totalCount > 0) { 
						$data['refi_close_order_percetage'] = round(($data['refi_close_count']*100)/$totalCount);
						$data['sale_close_order_percetage'] = round(($data['sale_close_count']*100)/$totalCount);
						$data['close_order_percetage'] = $data['refi_close_order_percetage'] + $data['sale_close_order_percetage'];
					} else {
						$data['refi_close_order_percetage'] = 0;
						$data['sale_close_order_percetage'] = 0;
						$data['close_order_percetage'] = 0;
					}
				} else {
					$data['refi_open_count'] = 0;
					$data['sale_open_count'] =  0;
					$data['total_open_count'] = 0;
					$data['projected_open_count'] = 0;
					$data['refi_close_count'] = 0;
					$data['sale_close_count'] =  0;
					$data['total_close_count'] = 0;
					$data['projected_close_count'] = 0;
					$data['projected_revenue'] = 0;
					$data['refi_close_order_percetage'] = 0;
					$data['sale_close_order_percetage'] = 0;
					$data['close_order_percetage'] = 0;
				}
			} 
		}
		$results = $this->load->view('hr/dashboard_count', $data, TRUE);
		echo json_encode($results, true);
	}

    public function logout()
    {
        $this->session->unset_userdata('hr_admin');
        redirect(base_url().'hr/admin');
    }
}
