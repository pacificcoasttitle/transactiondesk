<?php
class PartnerApiLogs extends CI_Model
{

    public function __construct()
    {
        // Set table name
        $this->table = 'pct_order_partner_api_logs';
    }

    public function get_partner_api_logs($params)
    {
        $sales_rep = isset($params['sales_rep']) && !empty($params['sales_rep']) ? $params['sales_rep'] : '';

        if (isset($sales_rep) && !empty($sales_rep)) {
            $this->db->where('transaction_details.sales_representative', $sales_rep);
        }

        $title_officer = isset($params['title_officer']) && !empty($params['title_officer']) ? $params['title_officer'] : '';

        if (isset($title_officer) && !empty($title_officer)) {
            $this->db->where('transaction_details.title_officer', $title_officer);
        }
        $this->db->select('pct_order_partner_api_logs.*,order_details.underwriter,order_details.cpl_document_name,order_details.file_id,order_details.file_number,transaction_details.id as transaction_id,
            transaction_details.sales_representative,
            CONCAT(customer_basic_details.first_name, " ", customer_basic_details.last_name) as sales_rep_name,
            transaction_details.title_officer,
            CONCAT(to.first_name, " ", to.last_name) as title_officer_name')
            ->from('pct_order_partner_api_logs')
            ->join('order_details', 'order_details.partner_api_log_id = pct_order_partner_api_logs.id')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
            ->join('customer_basic_details', 'transaction_details.sales_representative = customer_basic_details.id', 'left')
            ->join('customer_basic_details to', 'transaction_details.title_officer = to.id', 'left');
        $total_records = $this->db->count_all_results();

        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $logs_lists = array();

        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            $this->db->like('file_number', $keyword);
            if (isset($sales_rep) && !empty($sales_rep)) {
                $this->db->where('transaction_details.sales_representative', $sales_rep);
            }

            if (isset($title_officer) && !empty($title_officer)) {
                $this->db->where('transaction_details.title_officer', $title_officer);
            }

            $this->db->select('pct_order_partner_api_logs.*,order_details.underwriter,order_details.cpl_document_name,order_details.file_id,order_details.file_number,transaction_details.id as transaction_id,
            transaction_details.sales_representative,
            CONCAT(customer_basic_details.first_name, " ", customer_basic_details.last_name) as sales_rep_name,
            transaction_details.title_officer,
            CONCAT(to.first_name, " ", to.last_name) as title_officer_name')
                ->from('pct_order_partner_api_logs')
                ->join('order_details', 'order_details.partner_api_log_id = pct_order_partner_api_logs.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details', 'transaction_details.sales_representative = customer_basic_details.id', 'left')
                ->join('customer_basic_details to', 'transaction_details.title_officer = to.id', 'left');

            $filter_total_records = $this->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->db->like('file_number', $keyword);
            }
            if (isset($sales_rep) && !empty($sales_rep)) {
                $this->db->where('transaction_details.sales_representative', $sales_rep);
            }

            if (isset($title_officer) && !empty($title_officer)) {
                $this->db->where('transaction_details.title_officer', $title_officer);
            }

            $this->db->select('pct_order_partner_api_logs.*,order_details.underwriter,order_details.cpl_document_name,order_details.file_id,order_details.file_number,transaction_details.id as transaction_id,
            transaction_details.sales_representative,
            CONCAT(customer_basic_details.first_name, " ", customer_basic_details.last_name) as sales_rep_name,
            transaction_details.title_officer,
            CONCAT(to.first_name, " ", to.last_name) as title_officer_name')
                ->from('pct_order_partner_api_logs')
                ->join('order_details', 'order_details.partner_api_log_id = pct_order_partner_api_logs.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details', 'transaction_details.sales_representative = customer_basic_details.id', 'left')
                ->join('customer_basic_details to', 'transaction_details.title_officer = to.id', 'left');
            $this->db->order_by("pct_order_partner_api_logs.created_at", "desc");

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $logs_lists = $query->result_array();
            }

        } else {
            if (isset($sales_rep) && !empty($sales_rep)) {
                $this->db->where('transaction_details.sales_representative', $sales_rep);
            }

            if (isset($title_officer) && !empty($title_officer)) {
                $this->db->where('transaction_details.title_officer', $title_officer);
            }

            $this->db->select('pct_order_partner_api_logs.*,order_details.underwriter,order_details.cpl_document_name,order_details.file_id,order_details.file_number,transaction_details.id as transaction_id,
            transaction_details.sales_representative,
            CONCAT(customer_basic_details.first_name, " ", customer_basic_details.last_name) as sales_rep_name,
            transaction_details.title_officer,
            CONCAT(to.first_name, " ", to.last_name) as title_officer_name')
                ->from('pct_order_partner_api_logs')
                ->join('order_details', 'order_details.partner_api_log_id = pct_order_partner_api_logs.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details', 'transaction_details.sales_representative = customer_basic_details.id', 'left')
                ->join('customer_basic_details to', 'transaction_details.title_officer = to.id', 'left');

            $filter_total_records = $this->db->count_all_results();

            if (isset($sales_rep) && !empty($sales_rep)) {
                $this->db->where('transaction_details.sales_representative', $sales_rep);
            }

            if (isset($title_officer) && !empty($title_officer)) {
                $this->db->where('transaction_details.title_officer', $title_officer);
            }

            $this->db->select('pct_order_partner_api_logs.*,order_details.underwriter,order_details.cpl_document_name,order_details.file_id,order_details.file_number,transaction_details.id as transaction_id,
            transaction_details.sales_representative,
            CONCAT(customer_basic_details.first_name, " ", customer_basic_details.last_name) as sales_rep_name,
            transaction_details.title_officer,
            CONCAT(to.first_name, " ", to.last_name) as title_officer_name')
                ->from('pct_order_partner_api_logs')
                ->join('order_details', 'order_details.partner_api_log_id = pct_order_partner_api_logs.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details', 'transaction_details.sales_representative = customer_basic_details.id', 'left')
                ->join('customer_basic_details to', 'transaction_details.title_officer = to.id', 'left');
            $this->db->order_by("pct_order_partner_api_logs.created_at", "desc");

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $logs_lists = $query->result_array();
            }
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $logs_lists,
        );
    }

    public function get_api_logs()
    {
        $query = $this->db->get('pct_order_partner_api_logs');

        if ($query->num_rows() > 0) {
            $data = $query->result_array();
        }

        return $data;
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
}
