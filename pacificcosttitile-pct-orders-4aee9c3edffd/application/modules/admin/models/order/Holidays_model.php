<?php
class Holidays_model extends CI_Model
{
    public function __construct()
    {
        $this->table = 'pct_holidays';
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

    public function getHolidays($params)
    {
        $this->db->from($this->table);
        $total_records = $this->db->count_all_results();
        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $holidays_lists = array();

        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];
            if (isset($keyword) && !empty($keyword)) {
                $this->db->like('name', $keyword);
            }

            $this->db->from($this->table);
            $filter_total_records = $this->db->count_all_results();
            if (isset($keyword) && !empty($keyword)) {
                $this->db->like('name', $keyword);
            }

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $this->db->order_by('id', 'desc');
            // $this->db->where('status', 1);
            $query = $this->db->get($this->table);

            if ($query->num_rows() > 0) {
                $holidays_lists = $query->result_array();
            }
        } else {
            $this->db->from($this->table);
            $filter_total_records = $this->db->count_all_results();

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('id', 'desc');
            $query = $this->db->get($this->table);

            if ($query->num_rows() > 0) {
                $holidays_lists = $query->result_array();
            }
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $holidays_lists,
        );
    }

    public function get_rows($params = array())
    {
        $table = $this->table;

        $this->db->select('*');
        $this->db->from($table);

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

        // Return fetched data
        return $result;
    }

}
