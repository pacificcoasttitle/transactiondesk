<?php
class Transactee_model extends CI_Model
{

    public function __construct()
    {
        $this->table = 'pct_vendors';
    }

    public function get_transactees($params)
    {
        $venders_lists = array();
        $payoffUserId = isset($params['user_id']) && !empty($params['user_id']) ? $params['user_id'] : '';

        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = trim($params['searchvalue']);

            if (isset($payoffUserId) && !empty($payoffUserId)) {
                $this->db->where('pct_vendors.created_by', 'user');
                $this->db->where('pct_vendors.created_by_id', $payoffUserId);
            }
            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like("pct_vendors.transctee_name", $keyword)
                    ->or_like('pct_vendors.file_number', $keyword)
                    ->or_like('pct_vendors.account_number', $keyword)
                    ->or_like('pct_vendors.aba', $keyword)
                    ->or_like('pct_vendors.bank_name', $keyword)
                    ->or_like('pct_vendors.notes', $keyword)
                    ->or_like('pct_vendors.admin_notes', $keyword)
                    ->group_end();
            }

            $this->db->from('pct_vendors');
            $filter_total_records = $this->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like("pct_vendors.transctee_name", $keyword)
                    ->or_like('pct_vendors.file_number', $keyword)
                    ->or_like('pct_vendors.account_number', $keyword)
                    ->or_like('pct_vendors.aba', $keyword)
                    ->or_like('pct_vendors.bank_name', $keyword)
                    ->or_like('pct_vendors.notes', $keyword)
                    ->or_like('pct_vendors.admin_notes', $keyword)
                    ->group_end();
            }

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

            if (isset($payoffUserId) && !empty($payoffUserId)) {
                $this->db->where('pct_vendors.created_by', 'user');
                $this->db->where('pct_vendors.created_by_id', $payoffUserId);
            }

            $this->db->select('
                    pct_vendors.id,
                    pct_vendors.transctee_name,
                    pct_vendors.file_number,
                    pct_vendors.account_number,
                    pct_vendors.aba,
                    pct_vendors.bank_name,
                    pct_vendors.submitted,
                    pct_vendors.notes,
                    pct_vendors.admin_notes,
                    pct_vendors.created_by,
                    pct_vendors.approved_by,
                    pct_vendors.is_approved,
                    pct_vendors.approved_date,
                    admin.first_name,
                    admin.last_name,
                    a.first_name as a_first_name,
                    a.last_name as a_last_name,
                    c.first_name as c_first_name,
                    c.last_name as c_last_name,
                ')
                ->from('pct_vendors')
                ->join('admin', 'admin.id = pct_vendors.approved_by', 'left')
                ->join('admin as a', 'a.id = pct_vendors.created_by_id', 'left')
                ->join('customer_basic_details as c', 'c.id = pct_vendors.created_by_id', 'left')
                ->order_by('pct_vendors.transctee_name', 'asc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $venders_lists = $query->result_array();
            }
        } else {

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

            if (isset($payoffUserId) && !empty($payoffUserId)) {
                $this->db->where('pct_vendors.created_by', 'user');
                $this->db->where('pct_vendors.created_by_id', $payoffUserId);
            }

            $this->db->from('pct_vendors');
            $filter_total_records = $this->db->count_all_results();

            if (isset($payoffUserId) && !empty($payoffUserId)) {
                $this->db->where('pct_vendors.created_by', 'user');
                $this->db->where('pct_vendors.created_by_id', $payoffUserId);
            }

            $this->db->select('
                    pct_vendors.id,
                    pct_vendors.transctee_name,
                    pct_vendors.file_number,
                    pct_vendors.account_number,
                    pct_vendors.aba,
                    pct_vendors.bank_name,
                    pct_vendors.submitted,
                    pct_vendors.notes,
                    pct_vendors.admin_notes,
                    pct_vendors.created_by,
                    pct_vendors.approved_by,
                    pct_vendors.is_approved,
                    pct_vendors.approved_date,
                    admin.first_name,
                    admin.last_name,
                    a.first_name as a_first_name,
                    a.last_name as a_last_name,
                    c.first_name as c_first_name,
                    c.last_name as c_last_name,
                ')
                ->from('pct_vendors')
                ->join('admin', 'admin.id = pct_vendors.approved_by', 'left')
                ->join('admin as a', 'a.id = pct_vendors.created_by_id', 'left')
                ->join('customer_basic_details as c', 'c.id = pct_vendors.created_by_id', 'left')
                ->order_by('pct_vendors.transctee_name', 'asc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $venders_lists = $query->result_array();
            }
        }
        // print_r($this->db->last_query());die;
        return array(
            'recordsTotal' => $filter_total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $venders_lists,
        );
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

    public function delete($condition = array())
    {
        $table = $this->table;
        if (!empty($condition)) {
            // $data['id'] = date("Y-m-d H:i:s");
            $deleted = $this->db->delete($table, $condition);
            return $deleted ? true : false;
        }
        return false;
    }

    public function getDetails($val, $params = '')
    {
        $table = $this->table;
        if (!empty($val)) {
            $this->db->select('*');
            $this->db->from($table);
            if (!empty($params)) {
                $this->db->where($params, $val);
            } else {
                $this->db->where('id', $val);
            }
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
}
