<?php
class Customer_model extends CI_Model
{

    public function __construct()
    {
        $this->table = 'customer_basic_details';
    }

    public function get_customers($params = array())
    {
        $this->db->where('status', 1);
        $this->db->where('is_master', 0);
        if (isset($params['credentials_check']) && strlen($params['credentials_check']) > 0) {
            if ($params['credentials_check'] == '1') {
                $this->db->where('is_password_updated', 1);
            } else if ($params['credentials_check'] == '0') {
                $this->db->where('(is_password_updated = 0 and random_password != "")');
            } else {
                $this->db->where('random_password is null');
            }
        }
        $this->db->from($this->table);
        $total_records = $this->db->count_all_results();

        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

        $customer_lists = array();
        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->db->like('first_name', $keyword);
                $this->db->or_like('last_name', $keyword);
                $this->db->or_like('email_address', $keyword);
                $this->db->or_like('company_name', $keyword);
                $this->db->or_like('street_address', $keyword);
            }

            if (isset($params['credentials_check']) && strlen($params['credentials_check']) > 0) {
                if ($params['credentials_check'] == '1') {
                    $this->db->where('is_password_updated', 1);
                } else if ($params['credentials_check'] == '0') {
                    $this->db->where('(is_password_updated = 0 and random_password != "")');
                } else {
                    $this->db->where('random_password is null');
                }
            }
            $this->db->where('status', 1);
            $this->db->where('is_master', 0);
            $this->db->from($this->table);
            $filter_total_records = $this->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->db->like('first_name', $keyword);
                $this->db->or_like('last_name', $keyword);
                $this->db->or_like('email_address', $keyword);
                $this->db->or_like('company_name', $keyword);
                $this->db->or_like('street_address', $keyword);
            }

            if (isset($params['credentials_check']) && strlen($params['credentials_check']) > 0) {
                if ($params['credentials_check'] == '1') {
                    $this->db->where('is_password_updated', 1);
                } else if ($params['credentials_check'] == '0') {
                    $this->db->where('(is_password_updated = 0 and random_password != "")');
                } else {
                    $this->db->where('random_password is null');
                }
            }
            $this->db->where('status', 1);
            $this->db->where('is_master', 0);
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('is_password_updated', 'desc');
            $query = $this->db->get($this->table);

            if ($query->num_rows() > 0) {
                $customer_lists = $query->result_array();
            }
        } else {

            $this->db->where('status', 1);
            $this->db->where('is_master', 0);
            if (isset($params['credentials_check']) && strlen($params['credentials_check']) > 0) {
                if ($params['credentials_check'] == '1') {
                    $this->db->where('is_password_updated', 1);
                } else if ($params['credentials_check'] == '0') {
                    $this->db->where('(is_password_updated = 0 and random_password != "")');
                } else {
                    $this->db->where('random_password is null');
                }
            }
            $this->db->from($this->table);

            $filter_total_records = $this->db->count_all_results();

            if (isset($params['credentials_check']) && strlen($params['credentials_check']) > 0) {
                if ($params['credentials_check'] == '1') {
                    $this->db->where('is_password_updated', 1);
                } else if ($params['credentials_check'] == '0') {
                    $this->db->where('(is_password_updated = 0 and random_password != "")');
                } else {
                    $this->db->where('random_password is null');
                }
            }
            $this->db->where('status', 1);
            $this->db->where('is_master', 0);
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('is_password_updated', 'desc');
            $query = $this->db->get($this->table);

            if ($query->num_rows() > 0) {
                $customer_lists = $query->result_array();
            }
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $customer_lists,
        );
    }

    public function update($data, $condition = array())
    {
        if (!empty($data)) {

            $data['updated_at'] = date("Y-m-d H:i:s");

            // Update data
            $update = $this->db->update($this->table, $data, $condition);

            // Return the status
            return $update ? true : false;
        }
        return false;
    }
}
