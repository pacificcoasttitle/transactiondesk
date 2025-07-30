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

    public function getUserInfo($user_id)
    {
        $this->db->select('pct_hr_users.*, pct_hr_position.name as position,  pct_hr_user_types.name, pct_hr_departments.name as department_name');
        $this->db->from('pct_hr_users')
                 ->join('pct_hr_position', 'pct_hr_position.id = pct_hr_users.position_id')
                 ->join('pct_hr_user_types', 'pct_hr_user_types.id = pct_hr_users.user_type_id')
                 ->join('pct_hr_departments', 'pct_hr_departments.id = pct_hr_users.department_id', 'left');
        $this->db->where('pct_hr_users.status', 1);
        $this->db->where('pct_hr_users.id', $user_id);
        $query = $this->db->get();
        $userInfo = $query->row_array();
        return $userInfo;
    }

    public function getMemos($params)
    {
        $userdata = $this->session->userdata('hr_user');
        $this->db->from('pct_hr_memos')
                 ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by')
                 ->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id');
        $this->db->where('pct_hr_memos.status', 1);
        $this->db->where('pct_hr_assigned_memo_users.user_id', $userdata['id']);
        
        $total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $memos = array();

    	if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
    		$keyword = $params['searchvalue'];

    		if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_memos.subject', $keyword)
                        ->or_like('pct_hr_memos.created_at', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->group_end();
            }
            
            $this->db->from('pct_hr_memos')
                    ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by')
                    ->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id');
            $this->db->where('pct_hr_memos.status', 1);
            $this->db->where('pct_hr_assigned_memo_users.user_id', $userdata['id']);
			$filter_total_records =  $this->db->count_all_results();

			if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                        ->like('pct_hr_memos.subject', $keyword)
                        ->or_like('pct_hr_memos.created_at', date("Y-m-d", strtotime($keyword)))
                        ->or_like('pct_hr_users.first_name', $keyword)
                        ->or_like('pct_hr_users.last_name', $keyword)
                        ->group_end();
            }

            $this->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name, pct_hr_assigned_memo_users.is_read, pct_hr_assigned_memo_users.updated_at as acknowledge_at');
            $this->db->from('pct_hr_memos')
                    ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by')
                    ->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id');
            $this->db->where('pct_hr_memos.status', 1);
            $this->db->where('pct_hr_assigned_memo_users.user_id', $userdata['id']);
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
            $this->db->select('pct_hr_memos.*, pct_hr_users.first_name, pct_hr_users.last_name, pct_hr_assigned_memo_users.is_read, pct_hr_assigned_memo_users.updated_at as acknowledge_at');
            $this->db->from('pct_hr_memos')
                ->join('pct_hr_users', 'pct_hr_users.id = pct_hr_memos.created_by')
                ->join('pct_hr_assigned_memo_users', 'pct_hr_assigned_memo_users.memo_id = pct_hr_memos.id');
            $this->db->where('pct_hr_memos.status', 1);
            $this->db->where('pct_hr_assigned_memo_users.user_id', $userdata['id']);
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

    public function getTrainingMaterials($id) 
    {
        $this->db->select('*');
        $this->db->where('training_id', $id);
        $query = $this->db->get('pct_hr_employee_training_material');
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }

}
