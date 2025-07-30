<?php
class SalesReport_model extends CI_Model
{
    public function __construct()
    {
        // Set table name
        $this->table = 'pct_sales_activity_report';
    }

    public function insert($data = array(), $table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }

        if (!empty($data)) {
            // Insert data
            $insert = $this->db->insert($table, $data);

            // Return the status
            return $insert ? $this->db->insert_id() : false;
        }
        return false;
    }

    public function getData($condition = null)
    {
        $table = $this->table;
        $this->db->select($table . '.*,customer_basic_details.first_name ,customer_basic_details.last_name, ,customer_basic_details.email_address');
        $this->db->from($table);
        if ($condition && is_array($condition)) {
            foreach ($condition as $key => $val) {
                $this->db->where($key, $val);
            }
        }
        $this->db->join('customer_basic_details', "customer_basic_details.id = $table.sales_rep");
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function getSalesRepData($condition = null, $added_by = 0)
    {
        $table = 'customer_basic_details';
        $this->db->select($table . '.*,count(' . $this->table . '.id) as report_count');
        $this->db->from($table);
        if ($condition && is_array($condition)) {
            foreach ($condition as $key => $val) {
                $this->db->where($key, $val);
            }
        }
        $this->db->join($this->table, "$table.id = {$this->table}.sales_rep AND added_by = $added_by ", 'LEFT');
        $this->db->group_by("$table.id");
        $this->db->order_by("$table.first_name");
        $query = $this->db->get();
        $result = $query->result_array();
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function update($data, $condition = array(), $table = '')
    {
        if (empty($table)) {
            $table = $this->table;
        }

        if (!empty($data)) {
            // Update data
            $update = $this->db->update($table, $data, $condition);

            // Return the status
            return $update ? true : false;
        }
        return false;
    }

    public function delete_records($condition = array(), $table = '')
    {
        if (empty($table)) {
            $table = $this->table;
        }
        $this->db->delete($table, $condition);
    }

    public function getSalesAllReportData($condition = null)
    {
        $table = $this->table;
        // $this->db->select('id, month, report_url, "County" as report_type, created_at, CONCAT(month, " & ", county) AS option, county');
        $this->db->select('id, month as option, report_url, "County Report" as report_type, created_at, county as area');
        $this->db->from($table);
        if ($condition && is_array($condition)) {
            foreach ($condition as $key => $val) {
                $this->db->where($key, $val);
            }
        }
        // $this->db->join('customer_basic_details', "customer_basic_details.id = $table.sales_rep");
        // $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $table1 = 'pct_sales_snap_shot_report';

        $this->db->select('id, month_option as option, report_url, "Sales Activity" as report_type, created_at, area_name as area');
        $this->db->from($table1);
        if ($condition && is_array($condition)) {
            foreach ($condition as $key => $val) {
                $this->db->where($key, $val);
            }
        }
        $query1 = $this->db->get();
        $result1 = $query1->result_array();

        $table2 = 'pct_sales_rep_report';
        $this->db->select('id, sort_by as option, report_url, "FAR Report" as report_type, created_at, area_name as area');
        $this->db->from($table2);
        if ($condition && is_array($condition)) {
            foreach ($condition as $key => $val) {
                $this->db->where($key, $val);
            }
        }
        $query2 = $this->db->get();
        $result2 = $query2->result_array();

        // Combine data from all tables
        $combined_data = array_merge($result, $result1, $result2);

        // Sort the combined data by the created date field
        usort($combined_data, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        if (!empty($combined_data)) {
            return $combined_data;
        } else {
            return array();
        }
    }
}
