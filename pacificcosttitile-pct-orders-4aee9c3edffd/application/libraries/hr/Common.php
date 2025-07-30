<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common 
{
    public static $CI;
    
	public function __construct($params = array())
	{
		$this->CI =& get_instance();                        
		$this->CI->load->database();
        $this->CI->load->library('email');
        $this->CI->load->library('session');
		self::$CI = $this->CI;
    }

    public function is_hr_admin()
    {
        $userdata = $this->CI->session->userdata('hr_admin');
        if (!empty($userdata['id']) && $userdata['is_hr_admin'] == 1) {
            return true;
        } else {
            redirect(base_url().'hr/admin');
        }
    }

    public function randomPassword() 
    {
        $len = 8;
        $sets = array();
        $sets[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $sets[] = 'abcdefghijkmnopqrstuvwxyz';
        $sets[] = '0123456789';
        $password = '';
        
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }
    
        while(strlen($password) < $len) {
            $randomSet = $sets[array_rand($sets)];
            $password .= $randomSet[array_rand(str_split($randomSet))]; 
        }
        return str_shuffle($password);
    }

    public function is_user()
    {
        $userdata = $this->CI->session->userdata('hr_user');
        if (empty($userdata)) {
            redirect(base_url().'hr/login');
        } 
    }

    public function is_manager_user()
    {
        $userdata = $this->CI->session->userdata('hr_user');
        if (!empty($userdata['id']) && $userdata['user_type_id'] == 2) {
            return true;
        } else {
            redirect(base_url().'hr/dashboard');
        }
    }

    public function is_employee_user()
    {
        $userdata = $this->CI->session->userdata('hr_user');
        if (!empty($userdata['id']) && $userdata['user_type_id'] == 1) {
            return true;
        } else {
            redirect(base_url().'hr/dashboard');
        }
    }

	public function is_onboarding_user()
    {
        $userdata = $this->CI->session->userdata('hr_user');
        if(!empty($userdata) && isset($userdata['user_type']) && strtolower(trim($userdata['user_type'])) == 'onboarding laison'){
            return true;
        } else {
            redirect(base_url().'hr/dashboard');
        }
    }

    public function uploadDocumentOnAwsS3($fileName, $folder= '', $csv = 0)
    {
        $bucket = env('AWS_BUCKET');
        if(!empty($folder)) {
            $keyname = $folder."/".basename($fileName);    
            $filepath = "uploads/".$folder."/".$fileName;             
        } else {
            if ($csv == 1) {
                $keyname = "csv/".basename($fileName); 
            } else {
                $keyname = basename($fileName); 
            }
            $filepath = "uploads/".$fileName;  
        }
        
        try {
            $s3Client = new Aws\S3\S3Client([
                'region' => env('AWS_REGION'),
                'version' => '2006-03-01',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY')
                ],
            ]);
            
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $keyname,
                'SourceFile' => $filepath,
            ]);
        } catch (Aws\Exception\AwsException $e) {
            return false;
        }
        if(!empty($result['ObjectURL'])) {
            chmod($filepath, 0644);
            gc_collect_cycles();
            unlink($filepath);
            return true;
        } else {
            return false;
        } 
    }

    public function getAllUsers() 
    {
        $this->CI->db->select('*');
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get('pct_hr_users');
        if ($query->num_rows() > 0)  {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getMemoInfo($id) 
    {
        $this->CI->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name');
        $this->CI->db->from('pct_hr_memos')
            ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by');
        $this->CI->db->where('pct_hr_memos.id', $id);
        $this->CI->db->where('pct_hr_memos.status', 1);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function sendNotification($message, $type, $sent_to_user, $is_sent_admin = 0)
    {
        if ($is_sent_admin == 1) {
            $channel = 'admin-channel-'.$sent_to_user;
            $event = 'admin-event-'.$sent_to_user;
        }

        if ($is_sent_admin == 0) {
            $channel = 'user-channel-'.$sent_to_user;
            $event = 'user-event-'.$sent_to_user;
        }

        $options = array(
            'cluster' => env("PUSHER_CLUSTER"),
            'useTLS' => true
        );

        $pusher = new Pusher\Pusher(
            env("PUSHER_KEY"),
            env("PUSHER_SECRET"),
            env("PUSHER_APP_ID"),
            $options
        );

        $data['message'] = $message ;
        $data['date'] = date("F d, Y");
        $data['type'] = $type;
        $pusher->trigger($channel, $event, $data);
    }

    public function getTimeCardInfo($id) 
    {
        $this->CI->db->select('pct_hr_time_cards.*, pct_hr_users.first_name, pct_hr_users.last_name');
        $this->CI->db->from('pct_hr_time_cards')
            ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_time_cards.user_id');
        $this->CI->db->where('pct_hr_time_cards.id', $id);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getIncidentReport($id) 
    {
        $this->CI->db->select('pct_hr_incident_reports.*, pct_hr_users.first_name, pct_hr_users.last_name');
        $this->CI->db->from('pct_hr_incident_reports')
            ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_incident_reports.user_id');
        $this->CI->db->where('pct_hr_incident_reports.id', $id);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getVacationRequest($id) 
    {
        $this->CI->db->select('pct_hr_vacation_requests.*, pct_hr_users.first_name, pct_hr_users.last_name');
        $this->CI->db->from('pct_hr_vacation_requests')
            ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_vacation_requests.user_id');
        $this->CI->db->where('pct_hr_vacation_requests.id', $id);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

	public function getAssignedMemoInfo($id) 
    {
        $this->CI->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name');
        $this->CI->db->from('pct_hr_assigned_memo_users')
            ->join('pct_hr_memos', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id')
            ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by');
        $this->CI->db->where('pct_hr_assigned_memo_users.id', $id);
        $this->CI->db->where('pct_hr_memos.status', 1);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getVacationDataForCalendar($start, $end, $userIds = array()) 
    {
        $this->CI->db->select('pct_hr_vacation_requests.*, pct_hr_users.first_name, pct_hr_users.last_name');
        $this->CI->db->from('pct_hr_vacation_requests')
            ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_vacation_requests.user_id')
            ->group_start() 
                ->where("pct_hr_vacation_requests.from_date between '$start' and '$end'")
                ->or_where("pct_hr_vacation_requests.to_date between '$start' and '$end'")
                ->or_where("pct_hr_vacation_requests.from_date <= '$start' and pct_hr_vacation_requests.to_date >= '$end'")
            ->group_end();
        $this->CI->db->where("pct_hr_vacation_requests.status != 'denied'");
        if(!empty($userIds)) {
            $this->CI->db->where_in("pct_hr_vacation_requests.user_id", $userIds);
        }
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0)  {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get_hr_user($params = array()) 
    {
        $this->CI->db->select('pct_hr_users.*,pct_hr_user_types.name as user_type');
        $this->CI->db->from('pct_hr_users');
        $this->CI->db->join('pct_hr_user_types','pct_hr_users.user_type_id = pct_hr_user_types.id','left');
        foreach($params as $key => $val){
            $this->CI->db->where('pct_hr_users.'.$key, $val);
        }
        $query = $this->CI->db->get();
        $result = $query->row_array();
        if(!empty($result)) { 
            return $result;
        } else {
            return array();
        }   
    }

    public function getUsersForBranchManager($user_id) 
    {
        $this->CI->load->library('hr/common');
        $userInfo = $this->get_hr_user(array('id' => $user_id));
        $this->CI->db->select('id, email, pct_order_email, first_name, last_name')
            ->from('pct_hr_users');
        $this->CI->db->where('branch_id', $userInfo['branch_id']);
        $this->CI->db->where('user_type_id', 3);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0)  {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getEscrowUsersForEscrowManager() 
    {
        $this->CI->db->select('id, email, pct_order_email, first_name, last_name')
            ->from('pct_hr_users');
        $this->CI->db->where('department_id', 4);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0)  {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getTimeCards($params)
    { 
        if (isset($params['is_frontend']) && $params['is_frontend'] == 1) {
            $userdata = $this->CI->session->userdata('hr_user');
        } else {
            $userdata = $this->CI->session->userdata('hr_admin');
        }
        $usersIds = array();
        if(!empty($userdata)) {
            if ($userdata['user_type_id'] == 4) {
                if ($userdata['department_id'] == 4) {
                    $usersForBranchManager = $this->getEscrowUsersForEscrowManager($userdata['id']);
                } else {
                    $usersForBranchManager = $this->getUsersForBranchManager($userdata['id']);
                }
                if(!empty($usersForBranchManager)) {
                    $usersIds = array_column($usersForBranchManager, 'id');
                } else {
                    $usersIds[] = $userdata['id'];
                }
            } 
        }
        
        $this->CI->db->from('pct_hr_time_cards')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_time_cards.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_time_cards.approved_by_user_id', 'left');

        if(!empty($usersIds)) {
            $this->CI->db->where_in('pct_hr_time_cards.user_id', $usersIds);
        } else {
            if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                $this->CI->db->where_in('pct_hr_time_cards.user_id', $userdata['id']);
            }
        }

        $total_records =  $this->CI->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $timeCardsList = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                        ->like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->or_like('pct_hr_time_cards.exception_date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_time_cards.reg_hours', $keyword)
                        ->or_like('pct_hr_time_cards.ot_hours', $keyword)
                        ->or_like('pct_hr_time_cards.double_ot', $keyword)
                        ->or_like('pct_hr_time_cards.total_hours', $keyword)
                        ->or_like('branch_manager.first_name', $keyword)
                        ->or_like('branch_manager.last_name', $keyword)
                        ->group_end();
            }
            
            $this->CI->db->from('pct_hr_time_cards')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_time_cards.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_time_cards.approved_by_user_id', 'left');

            if(!empty($usersIds)) {
                $this->CI->db->where_in('pct_hr_time_cards.user_id', $usersIds);
            } else {
                if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                    $this->CI->db->where_in('pct_hr_time_cards.user_id', $userdata['id']);
                }
            }
			$filter_total_records =  $this->CI->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                        ->like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->or_like('pct_hr_time_cards.exception_date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_time_cards.reg_hours', $keyword)
                        ->or_like('pct_hr_time_cards.ot_hours', $keyword)
                        ->or_like('pct_hr_time_cards.double_ot', $keyword)
                        ->or_like('pct_hr_time_cards.total_hours', $keyword)
                        ->or_like('branch_manager.first_name', $keyword)
                        ->or_like('branch_manager.last_name', $keyword)
                        ->group_end();
            }

            $this->CI->db->select('pct_hr_time_cards.*, pct_hr_users.first_name,  pct_hr_users.last_name, branch_manager.first_name as branch_manager_first_name, branch_manager.last_name as branch_manager_last_name');
            $this->CI->db->from('pct_hr_time_cards')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_time_cards.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_time_cards.approved_by_user_id', 'left');

            if(!empty($usersIds)) {
                $this->CI->db->where_in('pct_hr_time_cards.user_id', $usersIds);
            } else {
                if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                    $this->CI->db->where_in('pct_hr_time_cards.user_id', $userdata['id']);
                }
            }
            $this->CI->db->order_by('pct_hr_time_cards.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }	

			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) {
	            $timeCardsList = $query->result_array();
	        }
    	} else {    		
            $filter_total_records =  $total_records;
            $this->CI->db->select('pct_hr_time_cards.*, pct_hr_users.first_name,  pct_hr_users.last_name, branch_manager.first_name as branch_manager_first_name, branch_manager.last_name as branch_manager_last_name');
            $this->CI->db->from('pct_hr_time_cards')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_time_cards.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_time_cards.approved_by_user_id', 'left');

            if(!empty($usersIds)) {
                $this->CI->db->where_in('pct_hr_time_cards.user_id', $usersIds);
            } else {
                if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                    $this->CI->db->where_in('pct_hr_time_cards.user_id', $userdata['id']);
                }
            }
            $this->CI->db->order_by('pct_hr_time_cards.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) {
	            $timeCardsList = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $timeCardsList
        );
    }

    public function getIncidentReports($params)
    { 
        if (isset($params['is_frontend']) && $params['is_frontend'] == 1) {
            $userdata = $this->CI->session->userdata('hr_user');
        } else {
            $userdata = $this->CI->session->userdata('hr_admin');
        }
        $usersIds = array();
        if(!empty($userdata)) {
            if ($userdata['user_type_id'] == 4) {
                if ($userdata['department_id'] == 4) {
                    $usersForBranchManager = $this->getEscrowUsersForEscrowManager($userdata['id']);
                } else {
                    $usersForBranchManager = $this->getUsersForBranchManager($userdata['id']);
                }
                if(!empty($usersForBranchManager)) {
                    $usersIds = array_column($usersForBranchManager, 'id');
                } else {
                    $usersIds[] = $userdata['id'];
                }
            } 
        }

        $this->CI->db->from('pct_hr_incident_reports')
            ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_incident_reports.user_id')
            ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_incident_reports.approved_by_user_id', 'left');

        if(!empty($usersIds)) {
            $this->CI->db->where_in('pct_hr_incident_reports.user_id', $usersIds);
        } else {
            if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                $this->CI->db->where_in('pct_hr_incident_reports.user_id', $userdata['id']);
            }
        }

        $total_records =  $this->CI->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $incidentReportsList = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                        ->like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->or_like('pct_hr_incident_reports.employee_number', $keyword)
                        ->or_like('pct_hr_incident_reports.incident_date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_incident_reports.incident_reason', $keyword)
                        ->or_like('pct_hr_incident_reports.actions', $keyword)
                        ->or_like('pct_hr_incident_reports.num_of_incidents', $keyword)
                        ->or_like('branch_manager.first_name', $keyword)
                        ->or_like('branch_manager.last_name', $keyword)
                        ->group_end();
            }
            
            $this->CI->db->from('pct_hr_incident_reports')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_incident_reports.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_incident_reports.approved_by_user_id', 'left');

            if(!empty($usersIds)) {
                $this->CI->db->where_in('pct_hr_incident_reports.user_id', $usersIds);
            } else {
                if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                    $this->CI->db->where_in('pct_hr_incident_reports.user_id', $userdata['id']);
                }
            }
			$filter_total_records =  $this->CI->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                    ->like('pct_hr_users.first_name', $keyword)
                    ->or_like('pct_hr_users.last_name', $keyword)
                    ->or_like('pct_hr_incident_reports.employee_number', $keyword)
                    ->or_like('pct_hr_incident_reports.incident_date', date("Y-m-d", strtotime($keyword)))
                    ->or_like('pct_hr_incident_reports.incident_reason', $keyword)
                    ->or_like('pct_hr_incident_reports.actions', $keyword)
                    ->or_like('pct_hr_incident_reports.num_of_incidents', $keyword)
                    ->or_like('branch_manager.first_name', $keyword)
                    ->or_like('branch_manager.last_name', $keyword)
                    ->group_end();
            }

            $this->CI->db->select('pct_hr_incident_reports.*, pct_hr_users.first_name,  pct_hr_users.last_name, branch_manager.first_name as branch_manager_first_name, branch_manager.last_name as branch_manager_last_name');
            $this->CI->db->from('pct_hr_incident_reports')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_incident_reports.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_incident_reports.approved_by_user_id', 'left');

            if(!empty($usersIds)) {
                $this->CI->db->where_in('pct_hr_incident_reports.user_id', $usersIds);
            } else {
                if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                    $this->CI->db->where_in('pct_hr_incident_reports.user_id', $userdata['id']);
                }
            }
            $this->CI->db->order_by('pct_hr_incident_reports.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }	

			$query = $this->CI->db->get();
           
			if ($query->num_rows() > 0) {
	            $incidentReportsList = $query->result_array();
	        }
    	} else {    		
            $filter_total_records =  $total_records;
            $this->CI->db->select('pct_hr_incident_reports.*, pct_hr_users.first_name,  pct_hr_users.last_name, branch_manager.first_name as branch_manager_first_name, branch_manager.last_name as branch_manager_last_name');
            $this->CI->db->from('pct_hr_incident_reports')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_incident_reports.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_incident_reports.approved_by_user_id', 'left');

            if(!empty($usersIds)) {
                $this->CI->db->where_in('pct_hr_incident_reports.user_id', $usersIds);
            } else {
                if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                    $this->CI->db->where_in('pct_hr_incident_reports.user_id', $userdata['id']);
                }
            }
            $this->CI->db->order_by('pct_hr_incident_reports.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) {
	            $incidentReportsList = $query->result_array();
	        } 
    	}
        
    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $incidentReportsList
        );
    }

    public function getVacationRequests($params)
    { 
        if (isset($params['is_frontend']) && $params['is_frontend'] == 1) {
            $userdata = $this->CI->session->userdata('hr_user');
        } else {
            $userdata = $this->CI->session->userdata('hr_admin');
        }
        $usersIds = array();
        if(!empty($userdata)) {
            if ($userdata['user_type_id'] == 4) {
                if ($userdata['department_id'] == 4) {
                    $usersForBranchManager = $this->getEscrowUsersForEscrowManager($userdata['id']);
                } else {
                    $usersForBranchManager = $this->getUsersForBranchManager($userdata['id']);
                }
                if(!empty($usersForBranchManager)) {
                    $usersIds = array_column($usersForBranchManager, 'id');
                } else {
                    $usersIds[] = $userdata['id'];
                }
            } 
        }

        $this->CI->db->from('pct_hr_vacation_requests')
            ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_vacation_requests.user_id')
            ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_vacation_requests.approved_by_user_id', 'left');

        if(!empty($usersIds)) {
            $this->CI->db->where_in('pct_hr_vacation_requests.user_id', $usersIds);
        } else {
            if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                $this->CI->db->where_in('pct_hr_vacation_requests.user_id', $userdata['id']);
            }
        }
    
        $total_records =  $this->CI->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $vacationRequestsList = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                        ->like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->or_like('pct_hr_vacation_requests.comment', $keyword)
                        ->or_like('pct_hr_vacation_requests.from_date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_vacation_requests.to_date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_vacation_requests.is_salary_deduction', strtolower($keyword) == 'yes' ? 1 : 0)
                        ->or_like('pct_hr_vacation_requests.is_time_charged_vacation', strtolower($keyword) == 'yes' ? 1 : 0)
                        ->or_like('branch_manager.first_name', $keyword)
                        ->or_like('branch_manager.last_name', $keyword)
                        ->group_end();
            }
            
            $this->CI->db->from('pct_hr_vacation_requests')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_vacation_requests.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_vacation_requests.approved_by_user_id', 'left');

            if(!empty($usersIds)) {
                $this->CI->db->where_in('pct_hr_vacation_requests.user_id', $usersIds);
            } else {
                if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                    $this->CI->db->where_in('pct_hr_vacation_requests.user_id', $userdata['id']);
                }
            }

			$filter_total_records =  $this->CI->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                        ->like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->or_like('pct_hr_vacation_requests.comment', $keyword)
                        ->or_like('pct_hr_vacation_requests.from_date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_vacation_requests.to_date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_vacation_requests.is_salary_deduction', strtolower($keyword) == 'yes' ? 1 : 0)
                        ->or_like('pct_hr_vacation_requests.is_time_charged_vacation', strtolower($keyword) == 'yes' ? 1 : 0)
                        ->or_like('branch_manager.first_name', $keyword)
                        ->or_like('branch_manager.last_name', $keyword)
                        ->group_end();
            }

            $this->CI->db->select('pct_hr_vacation_requests.*, pct_hr_users.first_name,  pct_hr_users.last_name, branch_manager.first_name as branch_manager_first_name, branch_manager.last_name as branch_manager_last_name');
            $this->CI->db->from('pct_hr_vacation_requests')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_vacation_requests.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_vacation_requests.approved_by_user_id', 'left');

            if (!empty($usersIds)) {
                $this->CI->db->where_in('pct_hr_vacation_requests.user_id', $usersIds);
            } else {
                if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                    $this->CI->db->where_in('pct_hr_vacation_requests.user_id', $userdata['id']);
                }
            }

            $this->CI->db->order_by('pct_hr_vacation_requests.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }	

			$query = $this->CI->db->get();
           
			if ($query->num_rows() > 0) {
	            $vacationRequestsList = $query->result_array();
	        }
    	} else {    		
            $filter_total_records =  $total_records;
            $this->CI->db->select('pct_hr_vacation_requests.*, pct_hr_users.first_name,  pct_hr_users.last_name, branch_manager.first_name as branch_manager_first_name, branch_manager.last_name as branch_manager_last_name');
            $this->CI->db->from('pct_hr_vacation_requests')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_vacation_requests.user_id')
                ->join('pct_hr_users as branch_manager', 'branch_manager.id = pct_hr_vacation_requests.approved_by_user_id', 'left');

            if (!empty($usersIds)) {
                $this->CI->db->where_in('pct_hr_vacation_requests.user_id', $usersIds);
            } else {
                if ($userdata['user_type_id'] != 1 && $userdata['user_type_id'] != 2)  {
                    $this->CI->db->where_in('pct_hr_vacation_requests.user_id', $userdata['id']);
                }
            }
            $this->CI->db->order_by('pct_hr_vacation_requests.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) {
	            $vacationRequestsList = $query->result_array();
	        } 
    	}
        
    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $vacationRequestsList
        );
    }

    function approveDenyRequest($request_type, $request_id, $status)
	{
		$userdata = $this->CI->session->userdata('hr_admin');
        $condition = array(
            'id' => $request_id
        );
        $type = $status == '1' ? 'approved' : 'denied';
        $this->CI->load->model('hr/users_model');
        if ($userdata['user_type_id'] == 4) {
            $superadminInfo = $this->CI->users_model->get_by('user_type_id', 1);
        }

        if ($request_type == 'time_card') {
            $data = array(
                'status' => $type,
                'approved_date' => date('Y-m-d'),
                'approved_by_user_id' => $userdata['id'],
				'denied_reason'=>NULL
            );
			if($type == 'denied' && !empty($this->CI->input->post('deny_reason'))) {
				$data['denied_reason'] = $this->CI->input->post('deny_reason');
			}
			$this->CI->hr->update($data, $condition, 'pct_hr_time_cards');
            $timeCardInfo = $this->getTimeCardInfo($request_id);
            $exceptionDate = date("F d, Y", strtotime($timeCardInfo['exception_date']));
            $message = 'Timecard request of '.$exceptionDate.' '.$type.' by '.$userdata['name'].' for '.$timeCardInfo['first_name']." ".$timeCardInfo['last_name'];

            if ($userdata['user_type_id'] == 4) {
                $notificationData = array(
                    'sent_user_id' => $timeCardInfo['user_id'],
                    'message' => $message,
                    'type' =>  $type
                );
                $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                $this->sendNotification($message, $type, $timeCardInfo['user_id'], 0);

                $notificationData['sent_user_id'] = $superadminInfo->id;
                $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                $this->sendNotification($message, $type, $superadminInfo->id, 1);
            } else {
                $userInfo = $this->CI->users_model->get($timeCardInfo['user_id']);
                $notificationData = array(
                    'sent_user_id' => $timeCardInfo['user_id'],
                    'message' => $message,
                    'type' =>  $type
                );
                $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                if ($userInfo->user_type_id == 4) {
                    $this->sendNotification($message, $type, $timeCardInfo['user_id'], 1);
                } else {
                    $this->sendNotification($message, $type, $timeCardInfo['user_id'], 0);
                    $branchUserInfo = $this->CI->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
                    $notificationData['sent_user_id'] = $branchUserInfo->id;
                    $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                    $this->sendNotification($message, $type, $branchUserInfo->id, 1);
                } 
            }
            if ($status == 1) {
                $successMsg = 'Timecard request approved successfully.';
            } else {
                $successMsg = 'Timecard request denied successfully.';
            }
            $this->CI->session->set_userdata('success', $successMsg);
        } else if ($request_type == 'incident_report') {
            $data = array(
                'status' => $type,
                'approved_date' => date('Y-m-d'),
                'approved_by_user_id' => $userdata['id']
            );
			$this->CI->hr->update($data, $condition, 'pct_hr_incident_reports'); 
            $incidentReportInfo = $this->getIncidentReport($request_id);
            $incident_date = date("F d, Y", strtotime($incidentReportInfo['incident_date']));
            $message = 'Incident report request of '.$incident_date.' '.$type.' by '.$userdata['name'].' for '.$incidentReportInfo['first_name']." ".$incidentReportInfo['last_name'];

            if ($userdata['user_type_id'] == 4) {
                $notificationData = array(
                    'sent_user_id' => $incidentReportInfo['user_id'],
                    'message' => $message,
                    'type' =>  $type
                );
                $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                $this->sendNotification($message, $type, $incidentReportInfo['user_id'], 0);

                $notificationData['sent_user_id'] = $superadminInfo->id;
                $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                $this->sendNotification($message, $type, $superadminInfo->id, 1);
            } else {
                $userInfo = $this->CI->users_model->get($incidentReportInfo['user_id']);
                $notificationData = array(
                    'sent_user_id' => $incidentReportInfo['user_id'],
                    'message' => $message,
                    'type' =>  $type
                );
                $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                if ($userInfo->user_type_id == 4) {
                    $this->sendNotification($message, $type, $incidentReportInfo['user_id'], 1);
                } else {
                    $this->sendNotification($message, $type, $incidentReportInfo['user_id'], 0);
                    $branchUserInfo = $this->CI->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
                    $notificationData['sent_user_id'] = $branchUserInfo->id;
                    $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                    $this->sendNotification($message, $type, $branchUserInfo->id, 1);
                } 
            }
            if ($status == 1) {
                $successMsg = 'Incident Report request approved successfully.';
            } else {
                $successMsg = 'Incident Report request denied successfully.';
            }
            $this->CI->session->set_userdata('success', $successMsg);
        } else if ($request_type == 'vacation_request') {
            $data = array(
                'status' => $type,
                'approved_date' => date('Y-m-d'),
                'approved_by_user_id' => $userdata['id']
            );
            if($type == 'denied' && !empty($this->CI->input->post('deny_reason'))) {
				$data['denied_reason'] = $this->CI->input->post('deny_reason');
			}
			$this->CI->hr->update($data, $condition, 'pct_hr_vacation_requests'); 
            $vacationRequestInfo = $this->getVacationRequest($request_id);
            $from_date = date("F d, Y", strtotime($vacationRequestInfo['from_date']));
            $to_date = date("F d, Y", strtotime($vacationRequestInfo['to_date']));
            $message = 'Vacation request from '.$from_date.' to '.$to_date.' '.$type.' by '.$userdata['name'].' for '.$vacationRequestInfo['first_name']." ".$vacationRequestInfo['last_name'];

            if ($userdata['user_type_id'] == 4) {
                $notificationData = array(
                    'sent_user_id' => $vacationRequestInfo['user_id'],
                    'message' => $message,
                    'type' =>  $type
                );
                $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                $this->sendNotification($message, $type, $vacationRequestInfo['user_id'], 0);

                $notificationData['sent_user_id'] = $superadminInfo->id;
                $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                $this->sendNotification($message, $type, $superadminInfo->id, 1);
            } else {
                $userInfo = $this->CI->users_model->get($vacationRequestInfo['user_id']);
                $notificationData = array(
                    'sent_user_id' => $vacationRequestInfo['user_id'],
                    'message' => $message,
                    'type' =>  $type
                );
                $this->CI->hr->insert($notificationData, 'pct_hr_notifications');

                if ($userInfo->user_type_id == 4) {
                    $this->sendNotification($message, $type, $vacationRequestInfo['user_id'], 1);
                } else {
                    $this->sendNotification($message, $type, $vacationRequestInfo['user_id'], 0);
                    $branchUserInfo = $this->CI->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
                    $notificationData['sent_user_id'] = $branchUserInfo->id;
                    $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
                    $this->sendNotification($message, $type, $branchUserInfo->id, 1);
                } 
            }
            if ($status == 1) {
                $successMsg = 'Vacation request approved successfully.';
            } else {
                $successMsg = 'Vacation request denied successfully.';
            }
            $this->CI->session->set_userdata('success', $successMsg);
        }
		else if ($request_type == 'time_sheet_status') {
            $data = array(
                'status' => $type,
                'updated_by' => $userdata['id'],
				'denied_reason'=>NULL
            );
			if($type == 'denied' && !empty($this->CI->input->post('deny_reason'))) {
				$data['denied_reason'] = $this->CI->input->post('deny_reason');
			}
			$this->CI->hr->update($data, $condition, 'pct_hr_timeheet_status');
            
			// $timeSheetInfo = $this->getTimeCardInfo($request_id);
            // $exceptionDate = date("F d, Y", strtotime($timeCardInfo['exception_date']));
            // $message = 'Time sheet request of '.$exceptionDate.' '.$type.' by '.$userdata['name'].' for '.$timeCardInfo['first_name']." ".$timeCardInfo['last_name'];

            // if ($userdata['user_type_id'] == 4) {
            //     $notificationData = array(
            //         'sent_user_id' => $timeCardInfo['user_id'],
            //         'message' => $message,
            //         'type' =>  $type
            //     );
            //     $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
            //     $this->sendNotification($message, $type, $timeCardInfo['user_id'], 0);

            //     $notificationData['sent_user_id'] = $superadminInfo->id;
            //     $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
            //     $this->sendNotification($message, $type, $superadminInfo->id, 1);
            // } else {
            //     $userInfo = $this->CI->users_model->get($timeCardInfo['user_id']);
            //     $notificationData = array(
            //         'sent_user_id' => $timeCardInfo['user_id'],
            //         'message' => $message,
            //         'type' =>  $type
            //     );
            //     $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
            //     if ($userInfo->user_type_id == 4) {
            //         $this->sendNotification($message, $type, $timeCardInfo['user_id'], 1);
            //     } else {
            //         $this->sendNotification($message, $type, $timeCardInfo['user_id'], 0);
            //         $branchUserInfo = $this->CI->users_model->get_by(array('user_type_id' => 4, 'branch_id' => $userInfo->branch_id));
            //         $notificationData['sent_user_id'] = $branchUserInfo->id;
            //         $this->CI->hr->insert($notificationData, 'pct_hr_notifications');
            //         $this->sendNotification($message, $type, $branchUserInfo->id, 1);
            //     } 
            // }
            if ($status == 1) {
                $successMsg = 'Timesheet request approved successfully.';
            } else {
                $successMsg = 'Timesheet request denied successfully.';
            }
            $this->CI->session->set_userdata('success', $successMsg);
        }
	}

    public function getTrainings($params)
    { 
        if (isset($params['is_frontend']) && $params['is_frontend'] == 1) {
            $userdata = $this->CI->session->userdata('hr_user');
        } else {
            $userdata = $this->CI->session->userdata('hr_admin');
        }

        $this->CI->db->from('pct_hr_employee_training')
            ->join('pct_hr_user_training_status', 'pct_hr_user_training_status.training_id = pct_hr_employee_training.id', 'inner');

        $this->CI->db->where('pct_hr_employee_training.status', 1);
        $this->CI->db->where('pct_hr_user_training_status.user_id', $userdata['id']);
        $total_records =  $this->CI->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $trainingsList = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                        ->like('pct_hr_employee_training.name', $keyword)
                        ->or_like('pct_hr_employee_training.description', $keyword)
                        ->group_end();
            }
            
            $this->CI->db->from('pct_hr_employee_training')
                ->join('pct_hr_user_training_status', 'pct_hr_user_training_status.training_id = pct_hr_employee_training.id', 'inner');
            $this->CI->db->where('pct_hr_employee_training.status', 1);
            $this->CI->db->where('pct_hr_user_training_status.user_id', $userdata['id']);
			$filter_total_records =  $this->CI->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                    ->like('pct_hr_employee_training.name', $keyword)
                    ->or_like('pct_hr_employee_training.description', $keyword)
                    ->group_end();
            }

            $this->CI->db->select('pct_hr_employee_training.*, pct_hr_user_training_status.is_complete');
            $this->CI->db->from('pct_hr_employee_training')
                ->join('pct_hr_user_training_status', 'pct_hr_user_training_status.training_id = pct_hr_employee_training.id', 'inner');

            $this->CI->db->where('pct_hr_employee_training.status', 1);
            $this->CI->db->where('pct_hr_user_training_status.user_id', $userdata['id']);
            $this->CI->db->order_by('pct_hr_employee_training.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }	

			$query = $this->CI->db->get();
           
			if ($query->num_rows() > 0) {
	            $trainingsList = $query->result_array();
	        }
    	} else {    		
            $this->CI->db->from('pct_hr_employee_training')
                ->join('pct_hr_user_training_status', 'pct_hr_user_training_status.training_id = pct_hr_employee_training.id', 'inner');

            $this->CI->db->where('pct_hr_employee_training.status', 1);
            $this->CI->db->where('pct_hr_user_training_status.user_id', $userdata['id']);
            $filter_total_records =  $this->CI->db->count_all_results();

            $this->CI->db->select('pct_hr_employee_training.*, pct_hr_user_training_status.is_complete');
            $this->CI->db->from('pct_hr_employee_training')
                ->join('pct_hr_user_training_status', 'pct_hr_user_training_status.training_id = pct_hr_employee_training.id', 'inner');

            $this->CI->db->where('pct_hr_employee_training.status', 1);
            $this->CI->db->where('pct_hr_user_training_status.user_id', $userdata['id']);
            $this->CI->db->order_by('pct_hr_employee_training.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }
			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) {
	            $trainingsList = $query->result_array();
	        } 
    	}
        
    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $trainingsList
        );
    }

	public function convertTimezone($dateTime,$format = 'm/d/Y h:i:s A',$to_timezone = '')
	{
		$default_timezone = $to_timezone = 'America/Los_Angeles';
		if(!empty($_COOKIE['user_timezone']) && $to_timezone == '') {
			$to_timezone = $_COOKIE['user_timezone'];
		}
		else if($to_timezone == ''){
			$to_timezone = 'America/Los_Angele';
		}
		$date = new DateTime($dateTime);
		try {
			$date->setTimezone(new DateTimeZone($to_timezone));
		} catch (\Throwable $th) {
			$date->setTimezone(new DateTimeZone($default_timezone));
		}
		return $date->format($format);
	}

    public function getNotifications($params)
    {
        if (isset($params['is_frontend']) && $params['is_frontend'] == 1) {
            $userdata = $this->CI->session->userdata('hr_user');
        } else {
            $userdata = $this->CI->session->userdata('hr_admin');
        }

        $this->CI->db->from('pct_hr_notifications');
        $this->CI->db->where('pct_hr_notifications.sent_user_id', $userdata['id']);
        $total_records =  $this->CI->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $notifications = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                        ->like('pct_hr_notifications.message', $keyword)
                        ->group_end();
            }
            
            $this->CI->db->from('pct_hr_notifications');
            $this->CI->db->where('pct_hr_notifications.sent_user_id', $userdata['id']);
			$filter_total_records =  $this->CI->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                        ->like('pct_hr_notifications.message', $keyword)
                        ->group_end();
            }

            $this->CI->db->select('pct_hr_notifications.*');
            $this->CI->db->from('pct_hr_notifications');
            $this->CI->db->where('pct_hr_notifications.sent_user_id', $userdata['id']);
            $this->CI->db->order_by('pct_hr_notifications.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }	

			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) {
	            $notifications = $query->result_array();
	        }
    	} else {    		
    		$this->CI->db->from('pct_hr_notifications');
            $this->CI->db->where('pct_hr_notifications.sent_user_id', $userdata['id']);
            $filter_total_records =  $this->CI->db->count_all_results();

            $this->CI->db->select('pct_hr_notifications.*');
            $this->CI->db->from('pct_hr_notifications');
            $this->CI->db->where('pct_hr_notifications.sent_user_id', $userdata['id']);
            $this->CI->db->order_by('pct_hr_notifications.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) {
	            $notifications = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $notifications
        );
    }

	public function mailNotification($user_id,$request_type,$request_id) {
		$param = $user_id.' '.$request_type.' '.$request_id;
		$command = "php ".FCPATH."index.php frontend/hr/hrCommon sendMailNotification $param";
		if (substr(php_uname(), 0, 7) == "Windows"){
			pclose(popen("start /B ". $command, "r")); 
		}
		else {
			exec($command . " > /dev/null &");  
		}
	}

    public function getTasks($params)
    {
        $this->CI->db->from('pct_escrow_tasks');
        $this->CI->db->where('pct_escrow_tasks.status', 1);
        $total_records =  $this->CI->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $tasks = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                    ->like('pct_escrow_tasks.name', $keyword)
                    ->or_like('pct_escrow_tasks.prod_type', $keyword)
                    ->group_end();
            }
            
            $this->CI->db->from('pct_escrow_tasks');
            $this->CI->db->where('pct_escrow_tasks.status', 1);
			$filter_total_records =  $this->CI->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                    ->like('pct_escrow_tasks.name', $keyword)
                    ->or_like('pct_escrow_tasks.prod_type', $keyword)
                    ->group_end();
            }

            $this->CI->db->select('pct_escrow_tasks.*');
            $this->CI->db->from('pct_escrow_tasks');
            $this->CI->db->where('pct_escrow_tasks.status', 1);
            $this->CI->db->order_by('pct_escrow_tasks.id', 'asc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }	

			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) {
	            $tasks = $query->result_array();
	        }
    	} else {    		
    		$this->CI->db->from('pct_escrow_tasks');
            $this->CI->db->where('pct_escrow_tasks.status', 1);
            $filter_total_records =  $this->CI->db->count_all_results();

            $this->CI->db->select('pct_escrow_tasks.*');
            $this->CI->db->from('pct_escrow_tasks');
            $this->CI->db->where('pct_escrow_tasks.status', 1);
            $this->CI->db->order_by('pct_escrow_tasks.id', 'asc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) {
	            $tasks = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $tasks
        );
    }

    public function getOrders($params)
    {
        $orders_lists = array();
        $this->CI->db->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details','order_details.transaction_id = transaction_details.id')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1')
                ->join('pct_order_partner_company_info', 'pct_order_partner_company_info.partner_id = order_details.escrow_officer_id', 'left');
        $this->CI->db->where('(transaction_details.purchase_type = 2 or transaction_details.purchase_type = 3 or transaction_details.purchase_type = 4 or transaction_details.purchase_type = 5 or transaction_details.purchase_type = 36)');
        $total_records =  $this->CI->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $orderBy = '';
        if ($params['orderColumn'] != 0) {
            if ($params['orderColumn'] == 1) {
                $orderBy = 'order_details.file_number'; 
            } else if ($params['orderColumn'] == 2) {
                $orderBy = 'property_details.full_address'; 
            } else if ($params['orderColumn'] == 3) {
                $orderBy = 'pct_order_product_types.product_type'; 
            }  else if ($params['orderColumn'] == 4) {
                $orderBy = 'pct_order_partner_company_info.partner_name'; 
            } else if ($params['orderColumn'] == 6) {
                $orderBy = 'order_details.created_at'; 
            } 
        }
        
        $select = 'order_details.prelim_summary_id, order_details.created_at as opened_date, order_details.file_number, order_details.file_id,property_details.full_address,order_details.id, order_details.westcor_order_id, order_details.westcor_file_id, order_details.westcor_cpl_id, property_details.escrow_lender_id, order_details.is_regenerate_cpl, order_details.cpl_document_name,
            order_details.created_at, order_details.resware_status, order_details.proposed_insured_document_name, order_details.is_payoff_generated,property_details.primary_owner, pct_order_product_types.product_type, pct_order_partner_company_info.partner_name, order_details.prod_type';

        if(isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                    ->like('property_details.full_address', $keyword)         
                    ->or_like('order_details.file_number', $keyword) 
                    ->or_like('order_details.created_at', date("Y-m-d", strtotime($keyword)))
                    ->or_like('order_details.resware_status', $keyword)
                ->group_end();
            } 

            $this->CI->db->select($select)
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details','order_details.transaction_id = transaction_details.id')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1')
                ->join('pct_order_partner_company_info', 'pct_order_partner_company_info.partner_id = order_details.escrow_officer_id', 'left');

            $this->CI->db->where('(transaction_details.purchase_type = 2 or transaction_details.purchase_type = 3 or transaction_details.purchase_type = 4 or transaction_details.purchase_type = 5 or transaction_details.purchase_type = 36)');
            $filter_total_records =  $this->CI->db->count_all_results();
            
            if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                    ->like('property_details.full_address', $keyword)         
                    ->or_like('order_details.file_number', $keyword) 
                    ->or_like('order_details.created_at', date("Y-m-d", strtotime($keyword)))
                    ->or_like('order_details.resware_status', $keyword)
                ->group_end();
            } 

            $this->CI->db->select($select)
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details','order_details.transaction_id = transaction_details.id')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1')
                ->join('pct_order_partner_company_info', 'pct_order_partner_company_info.partner_id = order_details.escrow_officer_id', 'left');

            $this->CI->db->where('(transaction_details.purchase_type = 2 or transaction_details.purchase_type = 3 or transaction_details.purchase_type = 4 or transaction_details.purchase_type = 5 or transaction_details.purchase_type = 36)');
            if (!empty($orderBy)) {
                $this->CI->db->order_by($orderBy, $params['orderDir']);
            } else {
                $this->CI->db->order_by("order_details.id", "desc");
            }
            
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

            $query = $this->CI->db->get();
            //echo $this->CI->db->last_query();exit;
            if ($query->num_rows() > 0)  {
                $orders_lists = $query->result_array();
            }
        } else {

            $filter_total_records =  $total_records;
            $this->CI->db->select($select)
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details','order_details.transaction_id = transaction_details.id')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1')
                ->join('pct_order_partner_company_info', 'pct_order_partner_company_info.partner_id = order_details.escrow_officer_id', 'left');

            $this->CI->db->where('(transaction_details.purchase_type = 2 or transaction_details.purchase_type = 3 or transaction_details.purchase_type = 4 or transaction_details.purchase_type = 5 or transaction_details.purchase_type = 36)');
            if (!empty($orderBy)) {
                $this->CI->db->order_by($orderBy, $params['orderDir']);
            } else {
                $this->CI->db->order_by("order_details.id", "desc");
            }
        
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }
            $query = $this->CI->db->get();
            //echo $this->CI->db->last_query();exit;
            if ($query->num_rows() > 0)  {
                $orders_lists = $query->result_array();
            } 
        }
        
    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $orders_lists
        );
    }

    public function getEscrowOfficerInfoBasedOnIdFromOrder($partner_id)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_partner_company_info');
        $this->CI->db->where('partner_id', $partner_id);
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get();    
        return $query->row_array();
    }

    public function getAssistantUsers($emails)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('customer_basic_details');
        $this->CI->db->where_in('email_address', $emails);
        $this->CI->db->where('is_escrow_assistant', 1);
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get();    
        return $query->result_array();
    }
    
}
