<?php
class Payoff_model extends CI_Model
{

    public function __construct()
    {
        $this->table = 'customer_basic_details';
    }

    public function get_payoff_users($params)
    {
        $this->db->where('is_payoff_user', 1);
        $this->db->from('customer_basic_details');
        $total_records = $this->db->count_all_results();
        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

        $payoff_user_lists = array();
        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            $filter_total_records = 0;
            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start();
                $this->db->where("CONCAT_WS(' ',first_name,last_name) LIKE '%" . $keyword . "%'", null, false);
                $this->db->or_like('email_address', $keyword);
                $this->db->or_like('company_name', $keyword);
                $this->db->group_end();
            }

            $this->db->where('status', 1);
            $this->db->where('is_payoff_user', 1);
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get('customer_basic_details');
            if ($query->num_rows() > 0) {
                $payoff_user_lists = $query->result_array();
                $filter_total_records = count($payoff_user_lists);
            }
        } else {
            $filter_total_records = 0; //$this->db->count_all_results();

            $this->db->where('is_payoff_user', 1);
            // $this->db->where('status', 1);
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $query = $this->db->get('customer_basic_details');

            if ($query->num_rows() > 0) {
                $payoff_user_lists = $query->result_array();
                $filter_total_records = count($payoff_user_lists);
            }
        }
        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $payoff_user_lists,
        );
    }

    public function getPayoffUsers($params = array())
    {
        $table = $this->table;
        $this->db->select('*');
        $this->db->from($table);
        // $this->db->where('status', 1);
        $this->db->where('is_payoff_user', 1);

        if (array_key_exists("where", $params)) {
            foreach ($params['where'] as $key => $val) {
                $this->db->where($key, $val);
            }
        }

        if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
            $result = $this->db->count_all_results();
        } else {
            if (array_key_exists("id", $params)) {
                $this->db->where('id', $params['id']);
                $query = $this->db->get();
                $result = $query->row_array();
            } else {
                $this->db->order_by('id', 'asc');
                if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit'], $params['start']);
                } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit']);
                }
                $query = $this->db->get();
                $result = ($query->num_rows() > 0) ? $query->result_array() : false;
            }
        }
        return $result;
    }

    public function insert($data = array())
    {
        $table = $this->table;
        if (!empty($data)) {
            $data['created_at'] = date("Y-m-d H:i:s");
            $insert = $this->db->insert($table, $data);
            return $insert ? $this->db->insert_id() : false;
        }
        return false;
    }

    public function update($data, $condition = array())
    {
        $table = $this->table;
        if (!empty($data)) {
            $data['updated_at'] = date("Y-m-d H:i:s");
            $update = $this->db->update($table, $data, $condition);
            return $update ? true : false;
        }
        return false;
    }

    public function delete($condition = array())
    {
        $table = $this->table;
        $update = $this->db->delete($table, $condition);
        return $update ? true : false;
    }

}
