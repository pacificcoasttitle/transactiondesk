<?php
class Hr extends CI_Model 
{
    public function insert($data = array(), $table) 
    {
        if (!empty($data)) {
        	$data['created_at'] = date("Y-m-d H:i:s");
            $insert = $this->db->insert($table, $data);
            return $insert ? $this->db->insert_id() : false;
        }
        return false;
    }

    public function update($data, $condition = array(), $table) 
    {
        if (!empty($data)) {          
            $data['updated_at'] = date("Y-m-d H:i:s");
            $update = $this->db->update($table, $data, $condition);
            return $update ? true : false;
        }
        return false;
    }

    public function getAdminUsers($params)
    {
        $this->db->from('pct_hr_users')
                ->join('pct_hr_user_types', 'pct_hr_user_types.id = pct_hr_users.user_type_id');
        //$this->db->where('pct_hr_users.status', 1);
        $this->db->where('(pct_hr_users.user_type_id = 1 OR pct_hr_users.user_type_id = 2)');
        $total_records =  $this->db->count_all_results();
        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $adminUsersList =array();
        
        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like("pct_hr_users.first_name", $keyword)
                    ->or_like('pct_hr_users.last_name',$keyword)
                    ->or_like('pct_hr_users.email',$keyword)
                    ->or_like('pct_hr_user_types.name',$keyword)
                    ->group_end();
            }
            
            $this->db->from('pct_hr_users')
                ->join('pct_hr_user_types', 'pct_hr_user_types.id = pct_hr_users.user_type_id');
            //$this->db->where('pct_hr_users.status', 1);
            $this->db->where('(pct_hr_users.user_type_id = 1 OR pct_hr_users.user_type_id = 2)');
            $filter_total_records =  $this->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like("pct_hr_users.first_name", $keyword)
                    ->or_like('pct_hr_users.last_name',$keyword)
                    ->or_like('pct_hr_users.email',$keyword)
                    ->or_like('pct_hr_user_types.name',$keyword)
                    ->group_end();
            }

            $this->db->select('pct_hr_users.*, pct_hr_user_types.name');
            $this->db->from('pct_hr_users')
                    ->join('pct_hr_user_types', 'pct_hr_user_types.id = pct_hr_users.user_type_id');
            //$this->db->where('pct_hr_users.status', 1);
            $this->db->where('(pct_hr_users.user_type_id = 1 OR pct_hr_users.user_type_id = 2)');

            if((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }  
            $this->db->order_by('pct_hr_users.id', 'desc');
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $adminUsersList = $query->result_array();
            }
        } else {            
            $filter_total_records =  $total_records;

            $this->db->select('pct_hr_users.*, pct_hr_user_types.name');
            $this->db->from('pct_hr_users')
                    ->join('pct_hr_user_types', 'pct_hr_user_types.id = pct_hr_users.user_type_id');
            //$this->db->where('pct_hr_users.status', 1);
            $this->db->where('(pct_hr_users.user_type_id = 1 OR pct_hr_users.user_type_id = 2)');

            if((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }  
            
            $this->db->order_by('pct_hr_users.id', 'desc');
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $adminUsersList = $query->result_array();
            }
        }
        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $adminUsersList
        );
    }

    public function getAdminUserInfo($id) 
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $query = $this->db->get('admin');
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getUsers($params)
    {
        $this->load->library('hr/common');
        $userdata = $this->session->userdata('hr_admin');
        $usersIds = array();
        if(!empty($userdata)) {
            if ($userdata['user_type_id'] == 4 && $userdata['department_id'] != 4) {
                $usersForBranchManager = $this->common->getUsersForBranchManager($userdata['id']);
                if(!empty($usersForBranchManager)) {
                    $usersIds = array_column($usersForBranchManager, 'id');
                    if (($key = array_search($userdata['id'], $usersIds)) !== false) {
                        unset($usersIds[$key]);
                    }
                } else {
                    return array(
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => array()
                    );
                }
            } 
        }

        $this->db->from('pct_hr_users')
                 ->join('pct_hr_position', 'pct_hr_position.id = pct_hr_users.position_id')
                 ->join('pct_hr_user_types', 'pct_hr_user_types.id = pct_hr_users.user_type_id')
                 ->join('pct_hr_branches', 'pct_hr_branches.id = pct_hr_users.branch_id')
                 ->join('pct_hr_departments', 'pct_hr_departments.id = pct_hr_users.department_id', 'left');
        $this->db->where('pct_hr_users.status', 1);
        if(!empty($usersIds)) {
            $this->db->where_in('pct_hr_users.id', $usersIds);
        } 
        if ($userdata['department_id'] == '4') {
            $this->db->where('pct_hr_users.department_id', 4);
        }
        $total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $users = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->or_like('pct_hr_users.email', $keyword)
                        ->or_like('pct_hr_users.hire_date', $keyword)
                        ->or_like('pct_hr_position.name', $keyword)
                        ->or_like('pct_hr_user_types.name', $keyword)
                        ->or_like('pct_hr_departments.name', $keyword)
                        ->group_end();
            }
            
            $this->db->from('pct_hr_users')
                 ->join('pct_hr_position', 'pct_hr_position.id = pct_hr_users.position_id')
                 ->join('pct_hr_user_types', 'pct_hr_user_types.id = pct_hr_users.user_type_id')
                 ->join('pct_hr_branches', 'pct_hr_branches.id = pct_hr_users.branch_id')
                 ->join('pct_hr_departments', 'pct_hr_departments.id = pct_hr_users.department_id', 'left');
            $this->db->where('pct_hr_users.status', 1);
            if(!empty($usersIds)) {
                $this->db->where_in('pct_hr_users.id', $usersIds);
            } 
            if ($userdata['department_id'] == '4') {
                $this->db->where('pct_hr_users.department_id', 4);
            }
			$filter_total_records =  $this->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->or_like('pct_hr_users.email', $keyword)
                        ->or_like('pct_hr_users.hire_date', $keyword)
                        ->or_like('pct_hr_position.name', $keyword)
                        ->or_like('pct_hr_user_types.name', $keyword)
                        ->or_like('pct_hr_departments.name', $keyword)
                        ->group_end();
            }

            $this->db->select('pct_hr_users.*, pct_hr_position.name as position, pct_hr_user_types.name, pct_hr_departments.name as department_name, pct_hr_branches.name as branch_name');
            $this->db->from('pct_hr_users')
                    ->join('pct_hr_position', 'pct_hr_position.id = pct_hr_users.position_id')
                    ->join('pct_hr_user_types', 'pct_hr_user_types.id = pct_hr_users.user_type_id')
                    ->join('pct_hr_branches', 'pct_hr_branches.id = pct_hr_users.branch_id')
                    ->join('pct_hr_departments', 'pct_hr_departments.id = pct_hr_users.department_id', 'left');
            $this->db->where('pct_hr_users.status', 1);
            if(!empty($usersIds)) {
                $this->db->where_in('pct_hr_users.id', $usersIds);
            } 
            if ($userdata['department_id'] == '4') {
                $this->db->where('pct_hr_users.department_id', 4);
            }
            $this->db->order_by('pct_hr_users.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }	

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $users = $query->result_array();
	        }
    	} else {    		
            $filter_total_records =  $total_records;
            $this->db->select('pct_hr_users.*, pct_hr_position.name as position,  pct_hr_user_types.name, pct_hr_departments.name as department_name, pct_hr_branches.name as branch_name');
            $this->db->from('pct_hr_users')
                    ->join('pct_hr_position', 'pct_hr_position.id = pct_hr_users.position_id')
                    ->join('pct_hr_user_types', 'pct_hr_user_types.id = pct_hr_users.user_type_id')
                    ->join('pct_hr_branches', 'pct_hr_branches.id = pct_hr_users.branch_id')
                    ->join('pct_hr_departments', 'pct_hr_departments.id = pct_hr_users.department_id', 'left');
            $this->db->where('pct_hr_users.status', 1);
            if(!empty($usersIds)) {
                $this->db->where_in('pct_hr_users.id', $usersIds);
            } 
            if ($userdata['department_id'] == '4') {
                $this->db->where('pct_hr_users.department_id', 4);
            }
            $this->db->order_by('pct_hr_users.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $users = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $users
        );
    }

    public function getHrPositions() 
    {
        $this->db->select('*');
        $this->db->where('status', 1);
        $query = $this->db->get('pct_hr_position');
        return $query->result_array();
    }

    public function getHrDepartments() 
    {
        $this->db->select('*');
        $this->db->where('status', 1);
        $query = $this->db->get('pct_hr_departments');
        return $query->result_array();
    }

    public function getHrUserTypes() 
    {
        $this->db->select('*');
        $this->db->where('status', 1);
        $query = $this->db->get('pct_hr_user_types');
        return $query->result_array();
    }

    public function getUserInfo($id) 
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $query = $this->db->get('pct_hr_users');
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getBranchManagers() 
    {
        $this->db->select('*');
        $this->db->where('user_type_id', 2);
        $this->db->where('status', 1);
        $query = $this->db->get('pct_hr_users');
        if ($query->num_rows() > 0)  {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getUserTypes($params)
    {
        $this->db->from('pct_hr_user_types');
        $this->db->where('pct_hr_user_types.status', 1);
        $total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $userTypes = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_user_types.name', $keyword)
                        ->group_end();
            }
            
            $this->db->from('pct_hr_user_types');
            $this->db->where('pct_hr_user_types.status', 1);
			$filter_total_records =  $this->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_user_types.name', $keyword)
                        ->group_end();
            }

            $this->db->select('pct_hr_user_types.*');
            $this->db->from('pct_hr_user_types');
            $this->db->where('pct_hr_user_types.status', 1);
            $this->db->order_by('pct_hr_user_types.id', 'asc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }	

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $userTypes = $query->result_array();
	        }
    	} else {    		
    		$this->db->from('pct_hr_user_types');
            $this->db->where('pct_hr_user_types.status', 1);
            $filter_total_records =  $this->db->count_all_results();

            $this->db->select('pct_hr_user_types.*');
            $this->db->from('pct_hr_user_types');
            $this->db->where('pct_hr_user_types.status', 1);
            $this->db->order_by('pct_hr_user_types.id', 'asc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $userTypes = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $userTypes
        );
    }

    public function getDepartments($params)
    {
        $this->db->from('pct_hr_departments');
        $this->db->where('pct_hr_departments.status', 1);
        $total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $departments = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_departments.name', $keyword)
                        ->group_end();
            }
            
            $this->db->from('pct_hr_departments');
            $this->db->where('pct_hr_departments.status', 1);
			$filter_total_records =  $this->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_departments.name', $keyword)
                        ->group_end();
            }

            $this->db->select('pct_hr_departments.*');
            $this->db->from('pct_hr_departments');
            $this->db->where('pct_hr_departments.status', 1);
            $this->db->order_by('pct_hr_departments.id', 'asc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }	

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $departments = $query->result_array();
	        }
    	} else {    		
    		$this->db->from('pct_hr_departments');
            $this->db->where('pct_hr_departments.status', 1);
            $filter_total_records =  $this->db->count_all_results();

            $this->db->select('pct_hr_departments.*');
            $this->db->from('pct_hr_departments');
            $this->db->where('pct_hr_departments.status', 1);
            $this->db->order_by('pct_hr_departments.id', 'asc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $departments = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $departments
        );
    }

    public function getUserTypeInfo($id) 
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $query = $this->db->get('pct_hr_user_types');
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getDepartmentInfo($id) 
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $query = $this->db->get('pct_hr_departments');
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getPositions($params)
    {
        $this->db->from('pct_hr_position');
        $this->db->where('pct_hr_position.status', 1);
        $total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $positions = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_position.name', $keyword)
                        ->group_end();
            }
            
            $this->db->from('pct_hr_position');
            $this->db->where('pct_hr_position.status', 1);
			$filter_total_records =  $this->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_position.name', $keyword)
                        ->group_end();
            }

            $this->db->select('pct_hr_position.*');
            $this->db->from('pct_hr_position');
            $this->db->where('pct_hr_position.status', 1);
            $this->db->order_by('pct_hr_position.id', 'asc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }	

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $positions = $query->result_array();
	        }
    	} else {    		
    		$this->db->from('pct_hr_position');
            $this->db->where('pct_hr_position.status', 1);
            $filter_total_records =  $this->db->count_all_results();

            $this->db->select('pct_hr_position.*');
            $this->db->from('pct_hr_position');
            $this->db->where('pct_hr_position.status', 1);
            $this->db->order_by('pct_hr_position.id', 'asc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $positions = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $positions
        );
    }

    public function getPositionInfo($id) 
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $query = $this->db->get('pct_hr_position');
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getMemos($params)
    {
        $userdata = $this->session->userdata('hr_admin');
        $this->db->from('pct_hr_memos')
                 ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by');
        
        if ($userdata['user_type_id'] == 4) {
            $this->db->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id');
            $this->db->where('pct_hr_assigned_memo_users.user_id', $userdata['id']);
        }

        $this->db->where('pct_hr_memos.status', 1);
        $total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $memos = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_memos.subject', $keyword)
                        ->or_like('pct_hr_memos.date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_memos.description', $keyword)
                        ->or_like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->group_end();
            }
            
            $this->db->from('pct_hr_memos')
                    ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by');
            
            if ($userdata['user_type_id'] == 4) {
                $this->db->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id');
                $this->db->where('pct_hr_assigned_memo_users.user_id', $userdata['id']);
            }

            $this->db->where('pct_hr_memos.status', 1);
			$filter_total_records =  $this->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_memos.subject', $keyword)
                        ->or_like('pct_hr_memos.date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_memos.description', $keyword)
                        ->or_like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->group_end();
            }

            if ($userdata['user_type_id'] == 4) {
                $this->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name, pct_hr_assigned_memo_users.is_read');
            } else {
                $this->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name');
            }

            $this->db->from('pct_hr_memos')
                    ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by');
            
            if ($userdata['user_type_id'] == 4) {
                $this->db->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id');
                $this->db->where('pct_hr_assigned_memo_users.user_id', $userdata['id']);
            }

            $this->db->where('pct_hr_memos.status', 1);
            $this->db->order_by('pct_hr_memos.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }	

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $memos = $query->result_array();
	        }
    	} else {    		
            $filter_total_records =  $total_records;
            
            if ($userdata['user_type_id'] == 4) {
                $this->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name, pct_hr_assigned_memo_users.is_read');
            } else {
                $this->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name');
            }

            $this->db->from('pct_hr_memos')
                    ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by');

            if ($userdata['user_type_id'] == 4) {
                $this->db->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id');
                $this->db->where('pct_hr_assigned_memo_users.user_id', $userdata['id']);
            }

            $this->db->where('pct_hr_memos.status', 1);
            $this->db->order_by('pct_hr_memos.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $memos = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $memos
        );
    }

    public function getAssignedMemoInfo($memo_id)
    {
        $this->db->select('Group_concat(user_id) as user_ids')
            ->from('pct_hr_assigned_memo_users');
        $this->db->where('memo_id', $memo_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function getMemosStatus($params)
    {
        $userdata = $this->session->userdata('hr_admin');
        $usersIds = array();
        if ($userdata['user_type_id'] == 4) {
            $usersForBranchManager = $this->common->getUsersForBranchManager($userdata['id']);
            if(!empty($usersForBranchManager)) {
                $usersIds = array_column($usersForBranchManager, 'id');
                if (($key = array_search($userdata['id'], $usersIds)) !== false) {
                    unset($usersIds[$key]);
                }
            } 
        } 

        $this->db->from('pct_hr_memos')
                 ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by')
                 ->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id')
                 ->join('pct_hr_users as asu', 'asu.id = pct_hr_assigned_memo_users.user_id');
        $this->db->where('pct_hr_memos.status', 1);

        if (!empty($usersIds)) {
            $this->db->where_in('pct_hr_assigned_memo_users.user_id', $usersIds);
        }

        $total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $memos = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_memos.subject', $keyword)
                        ->or_like('pct_hr_memos.date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->or_like('asu.first_name', $keyword)
                        ->or_like('asu.last_name', $keyword)
                        ->group_end();
            }
            
            $this->db->from('pct_hr_memos')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by')
                ->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id')
                ->join('pct_hr_users as asu', 'asu.id = pct_hr_assigned_memo_users.user_id');
            $this->db->where('pct_hr_memos.status', 1);
            if(!empty($usersIds)) {
                $this->db->where_in('pct_hr_assigned_memo_users.user_id', $usersIds);
            }
			$filter_total_records =  $this->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_memos.subject', $keyword)
                        ->or_like('pct_hr_memos.date', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->or_like('asu.first_name', $keyword)
                        ->or_like('asu.last_name', $keyword)
                        ->group_end();
            }

            $this->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name, pct_hr_assigned_memo_users.is_read, asu.first_name as assign_first_name, asu.last_name as assign_last_name');
            $this->db->from('pct_hr_memos')
                    ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by')
                    ->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id')
                    ->join('pct_hr_users as asu', 'asu.id = pct_hr_assigned_memo_users.user_id');
            $this->db->where('pct_hr_memos.status', 1);
            if(!empty($usersIds)) {
                $this->db->where_in('pct_hr_assigned_memo_users.user_id', $usersIds);
            }
            $this->db->order_by('pct_hr_memos.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }	

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $memos = $query->result_array();
	        }
    	} else {    		
            $filter_total_records =   $total_records;
            $this->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name, pct_hr_assigned_memo_users.is_read, asu.first_name as assign_first_name, asu.last_name as assign_last_name');
            $this->db->from('pct_hr_memos')
                    ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by')
                    ->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id')
                    ->join('pct_hr_users as asu', 'asu.id = pct_hr_assigned_memo_users.user_id');
            $this->db->where('pct_hr_memos.status', 1);
            if(!empty($usersIds)) {
                $this->db->where_in('pct_hr_assigned_memo_users.user_id', $usersIds);
            }
            $this->db->order_by('pct_hr_memos.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $memos = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $memos
        );
    }

    public function getTrainingStatus($params)
    {
        $userdata = $this->session->userdata('hr_admin');
        $usersIds = array();
        if ($userdata['user_type_id'] == 4) {
            $usersForBranchManager = $this->common->getUsersForBranchManager($userdata['id']);
            if(!empty($usersForBranchManager)) {
                $usersIds = array_column($usersForBranchManager, 'id');
                if (($key = array_search($userdata['id'], $usersIds)) !== false) {
                    unset($usersIds[$key]);
                }
            } 
        } 

        $this->db->from('pct_hr_employee_training')
                 ->join('pct_hr_user_training_status', 'pct_hr_user_training_status.training_id = pct_hr_employee_training.id')
                 ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_user_training_status.user_id');
        $this->db->where('pct_hr_employee_training.status', 1);
        if (!empty($usersIds)) {
            $this->db->where_in('pct_hr_user_training_status.user_id', $usersIds);
        }
        $total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $trainings_status = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_employee_training.name', $keyword)
                        ->or_like('pct_hr_user_training_status.created_at', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->group_end();
            }
            
            $this->db->from('pct_hr_employee_training')
                        ->join('pct_hr_user_training_status', 'pct_hr_user_training_status.training_id = pct_hr_employee_training.id')
                        ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_user_training_status.user_id');
            $this->db->where('pct_hr_employee_training.status', 1);
            if (!empty($usersIds)) {
                $this->db->where_in('pct_hr_user_training_status.user_id', $usersIds);
            }
			$filter_total_records =  $this->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_employee_training.name', $keyword)
                        ->or_like('pct_hr_user_training_status.created_at', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->group_end();
            }

            $this->db->select('pct_hr_employee_training.*, pct_hr_users.first_name, pct_hr_users.last_name, pct_hr_user_training_status.is_complete, pct_hr_user_training_status.created_at');
            $this->db->from('pct_hr_employee_training')
                        ->join('pct_hr_user_training_status', 'pct_hr_user_training_status.training_id = pct_hr_employee_training.id')
                        ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_user_training_status.user_id');
            $this->db->where('pct_hr_employee_training.status', 1);
            if (!empty($usersIds)) {
                $this->db->where_in('pct_hr_user_training_status.user_id', $usersIds);
            }
            $this->db->order_by('pct_hr_employee_training.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }	

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $trainings_status = $query->result_array();
	        }
    	} else {    		
            $filter_total_records =  $total_records;
            $this->db->select('pct_hr_employee_training.*, pct_hr_users.first_name, pct_hr_users.last_name, pct_hr_user_training_status.is_complete, pct_hr_user_training_status.created_at');
            $this->db->from('pct_hr_employee_training')
                        ->join('pct_hr_user_training_status', 'pct_hr_user_training_status.training_id = pct_hr_employee_training.id')
                        ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_user_training_status.user_id');
            $this->db->where('pct_hr_employee_training.status', 1);
            if (!empty($usersIds)) {
                $this->db->where_in('pct_hr_user_training_status.user_id', $usersIds);
            }
            $this->db->order_by('pct_hr_employee_training.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $trainings_status = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $trainings_status
        );
    }

    public function getBranches($params)
    {
        $this->db->from('pct_hr_branches');
        $this->db->where('pct_hr_branches.status', 1);
        $total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $branches = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_branches.name', $keyword)
                        ->group_end();
            }
            
            $this->db->from('pct_hr_branches');
            $this->db->where('pct_hr_branches.status', 1);
			$filter_total_records =  $this->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_branches.name', $keyword)
                        ->group_end();
            }

            $this->db->select('pct_hr_branches.*');
            $this->db->from('pct_hr_branches');
            $this->db->where('pct_hr_branches.status', 1);
            $this->db->order_by('pct_hr_branches.id', 'asc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }	

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $branches = $query->result_array();
	        }
    	} else {    		
    		$this->db->from('pct_hr_branches');
            $this->db->where('pct_hr_branches.status', 1);
            $filter_total_records =  $this->db->count_all_results();

            $this->db->select('pct_hr_branches.*');
            $this->db->from('pct_hr_branches');
            $this->db->where('pct_hr_branches.status', 1);
            $this->db->order_by('pct_hr_branches.id', 'asc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $branches = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $branches
        );
    }

    public function get_escrow_instruction_list($params)
    {
        $this->db->from('pct_order_escrow_instruction_columns_values')
                 ->join('pct_order_escrow_instruction', 'pct_order_escrow_instruction_columns_values.escrow_instruction_id = pct_order_escrow_instruction.id');
        $total_records =  $this->db->count_all_results();
    
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $cpl_document_lists = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_order_escrow_instruction_columns_values.custom_field_value_id', $keyword)
                        ->or_like('pct_order_escrow_instruction_columns_values.custom_field_id', $keyword)
                        ->or_like('pct_order_escrow_instruction_columns_values.name', $keyword)
                        ->or_like('pct_order_escrow_instruction_columns_values.value', $keyword)
                        ->or_like('pct_order_escrow_instruction.name', $keyword)
                        ->group_end();
               
            }

            $this->db->from('pct_order_escrow_instruction_columns_values')
                ->join('pct_order_escrow_instruction', 'pct_order_escrow_instruction_columns_values.escrow_instruction_id = pct_order_escrow_instruction.id');
			$filter_total_records =  $this->db->count_all_results();

			if(isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_order_escrow_instruction_columns_values.custom_field_value_id', $keyword)
                        ->or_like('pct_order_escrow_instruction_columns_values.custom_field_id', $keyword)
                        ->or_like('pct_order_escrow_instruction_columns_values.name', $keyword)
                        ->or_like('pct_order_escrow_instruction_columns_values.value', $keyword)
                        ->or_like('pct_order_escrow_instruction.name', $keyword)
                        ->group_end();
			}

            $this->db->select('pct_order_escrow_instruction_columns_values.id, pct_order_escrow_instruction_columns_values.custom_field_value_id, pct_order_escrow_instruction_columns_values.custom_field_id, pct_order_escrow_instruction_columns_values.name, pct_order_escrow_instruction_columns_values.value, pct_order_escrow_instruction.name as instruction_name');
            $this->db->from('pct_order_escrow_instruction_columns_values')
                ->join('pct_order_escrow_instruction', 'pct_order_escrow_instruction_columns_values.escrow_instruction_id = pct_order_escrow_instruction.id');
            
            $this->db->order_by('pct_order_escrow_instruction_columns_values.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }	
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $cpl_document_lists = $query->result_array();
	        }
    	} else {    		

    		$this->db->from('pct_order_escrow_instruction_columns_values')
                ->join('pct_order_escrow_instruction', 'pct_order_escrow_instruction_columns_values.escrow_instruction_id = pct_order_escrow_instruction.id');
        
            $filter_total_records =  $this->db->count_all_results();

            $this->db->select('pct_order_escrow_instruction_columns_values.id, pct_order_escrow_instruction_columns_values.custom_field_value_id, pct_order_escrow_instruction_columns_values.custom_field_id, pct_order_escrow_instruction_columns_values.name, pct_order_escrow_instruction_columns_values.value, pct_order_escrow_instruction.name as instruction_name');

            $this->db->from('pct_order_escrow_instruction_columns_values')
                ->join('pct_order_escrow_instruction', 'pct_order_escrow_instruction_columns_values.escrow_instruction_id = pct_order_escrow_instruction.id');
            
            $this->db->order_by('pct_order_escrow_instruction_columns_values.id', 'desc');

			if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
	            $cpl_document_lists = $query->result_array();
	        } 
    	}

    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $cpl_document_lists
        );
    }
}
