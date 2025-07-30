<?php
class Fees_model extends CI_Model
{
    public function __construct()
    {
        // Set table name
        $this->table = 'pct_order_fees';
    }

    public function update($data, $condition = array())
    {
        $table = $this->table;

        if (!empty($data)) {

            $data['updated_at'] = date("Y-m-d H:i:s");

            // Update data
            $update = $this->db->update($table, $data, $condition);

            // Return the status
            return $update ? true : false;
        }
        return false;
    }

    public function insert($data = array())
    {
        $table = $this->table;
        if (!empty($data)) {

            $data['created_at'] = date("Y-m-d H:i:s");

            // Insert data
            $insert = $this->db->insert($table, $data);

            // Return the status
            return $insert ? $this->db->insert_id() : false;
        }
        return false;
    }

    public function getFees($params)
    {
        $this->db->where('status', 1);
        $this->db->from($this->table);
        $total_records = $this->db->count_all_results();

        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

        $fees_lists = array();
        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->db->like('name', $keyword);
            }
            $this->db->where('status', 1);

            $this->db->from($this->table);
            $filter_total_records = $this->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->db->like('name', $keyword);
            }

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('id', 'desc');

            $this->db->where('status', 1);

            $query = $this->db->get($this->table);

            if ($query->num_rows() > 0) {
                $fees_lists = $query->result_array();
            }
        } else {
            $this->db->where('status', 1);

            $this->db->from($this->table);

            $filter_total_records = $this->db->count_all_results();

            $this->db->where('status', 1);

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('id', 'desc');
            $query = $this->db->get($this->table);

            if ($query->num_rows() > 0) {
                $fees_lists = $query->result_array();
            }
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $fees_lists,
        );
    }

    public function get_rows($params = array())
    {
        $table = $this->table;
        $product_type = isset($params['product_type']) && !empty($params['product_type']) ? $params['product_type'] : '';

        $this->db->select('pct_order_fees.*, pct_order_fees_types.name as fee_type');
        $this->db->from($table);

        if (array_key_exists("where", $params)) {
            foreach ($params['where'] as $key => $val) {
                $this->db->where($key, $val);
            }
        }

        if (!empty($product_type)) {
            if ($product_type == 'Loan:  Title and Escrow' || $product_type == 'Loan:  Escrow Only (Outside Title)' || $product_type == 'Sale:  Title and Escrow' || $product_type == 'Sale: Escrow Only (Outside Title)') {

            } else {
                $this->db->where($key, $val);
                $this->db->where("pct_order_fees_types.name !=", 'Escrow');
            }
        }
        if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
            $this->db->join('pct_order_fees_types', 'pct_order_fees.fee_type_id = pct_order_fees_types.id');
            $result = $this->db->count_all_results();
        } else {
            if (array_key_exists("id", $params)) {
                $this->db->where('id', $params['id']);
                $this->db->join('pct_order_fees_types', 'pct_order_fees.fee_type_id = pct_order_fees_types.id');
                $query = $this->db->get();
                $result = $query->row_array();
            } else {
                $this->db->order_by('pct_order_fees.id', 'desc');
                if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit'], $params['start']);
                } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit']);
                }
                $this->db->join('pct_order_fees_types', 'pct_order_fees.fee_type_id = pct_order_fees_types.id');
                $query = $this->db->get();
                $result = ($query->num_rows() > 0) ? $query->result_array() : false;
            }
        }

        // Return fetched data
        return $result;
    }

    public function getFeesEstimation($type, $excludeType)
    {
        $result = array();
        $table = $this->table;
        $this->db->from($table);
        $this->db->select('pct_order_fees.id, pct_order_fees.transaction_type, pct_order_fees.name, pct_order_fees.value, pct_order_fees.status as fees_status, ft.name as fees_type_name');
        $this->db->join('pct_order_fees_types as ft', 'ft.id = pct_order_fees.fee_type_id');
        $this->db->where('pct_order_fees.transaction_type', $type);
        $this->db->where('pct_order_fees.status', 1);
        $this->db->where_not_in('ft.name', $excludeType);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
        }

        $resultArray = [];
        if (!empty($result)) {
            // Group elements by 'fees_type_name'
            foreach ($result as $item) {
                $key = $item['fees_type_name'];
                $resultArray[$key][] = $item;

                if (!isset($resultArray[$key]['Total'])) {
                    $resultArray[$key]['Total'] = 0;
                }

                $resultArray[$key]['Total'] += $item['value'];
            }
        }
        return $resultArray;
    }

    public function getRecordingFees($transactionType, $titleOfficerId)
    {
        $recordingAdditionalFeesTotal = 0;
        $recordingAdditionalFees = $this->get_additional_fees($transactionType, 'recording', $titleOfficerId);

        if (isset($recordingAdditionalFees) && !empty($recordingAdditionalFees)) {
            foreach ($recordingAdditionalFees as $record_key => $record_value) {
                $recordingAdditionalFeesTotal += $record_value['value'];
            }
        }
        return array('recordingFeesTotal' => $recordingAdditionalFeesTotal, 'recordingFees' => $recordingAdditionalFees);
    }

    public function get_additional_fees($txn_type, $rate_type, $titleOfficerId)
    {
        $subquery = $this->db->select('pct_order_fees.name')
            ->from('pct_order_fees')
            ->join('pct_order_fees_types as ft', 'ft.id = pct_order_fees.fee_type_id')
            ->where('ft.name =', $rate_type)
            ->where('transaction_type', $txn_type)
            ->where('title_officer', $titleOfficerId)
            ->get_compiled_select();
        // print_r($subquery);die;
        $this->db->from('pct_order_fees');
        $this->db->select('pct_order_fees.id, pct_order_fees.transaction_type, pct_order_fees.name, pct_order_fees.value, pct_order_fees.status as fees_status, ft.name as fees_type_name');
        $this->db->join('pct_order_fees_types as ft', 'ft.id = pct_order_fees.fee_type_id');
        $this->db->where('pct_order_fees.status', 1);
        $this->db->where('pct_order_fees.transaction_type', $txn_type);
        $this->db->where('ft.name =', $rate_type);

        if ($titleOfficerId) {

            $this->db->group_start()
                ->where('title_officer', $titleOfficerId)
                ->or_group_start() // Second condition starts
                ->where('title_officer', 0)
                ->where("pct_order_fees.name NOT IN ($subquery)")
                ->group_end() // Close the second condition
                ->group_end();
        }
        // Close the entire OR group
        $query = $this->db->get();
        if ($rate_type == 'Others') {
            // echo "<pre>";
            // print_r($query->result_array());
            // print_r($this->db->last_query());die;
        }
        return $query->result_array();
    }

    public function getOtherAdditionalFees($transactionType, $titleOfficerId)
    {
        $otherFeesTotal = 0;
        $otherFees = array();
        $otherFees = $this->get_additional_fees($transactionType, 'Others', $titleOfficerId);
        if (count($otherFees)) {
            $otherFeesVal = array_column($otherFees, 'value');
            $otherFeesTotal = array_sum($otherFeesVal);
        }
        return array('otherFeesTotal' => $otherFeesTotal, 'otherFees' => $otherFees);
    }
}
