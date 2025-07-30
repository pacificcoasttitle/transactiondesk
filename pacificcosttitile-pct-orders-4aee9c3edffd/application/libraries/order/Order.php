<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Order
{
    public static $CI;

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->CI->load->library('email');
        $this->CI->load->library('session');
        self::$CI = $this->CI;
    }

    public function get_recent_orders()
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('order_details.file_number, order_details.file_id, property_details.full_address,order_details.id, order_details.westcor_order_id, order_details.westcor_file_id, order_details.westcor_cpl_id, order_details.created_at, property_details.primary_owner,order_details.borrower_invited,order_details.resware_status')
            ->from('order_details')
            ->join('property_details', 'order_details.property_id = property_details.id');

        if ($userdata['is_master'] == 0) {
            $this->CI->db->group_start()
                ->where('order_details.customer_id', $userdata['id'])
                ->or_where('property_details.escrow_lender_id', $userdata['id'])
                ->group_end();
        }
        $this->CI->db->order_by("order_details.created_at", "desc");
        $this->CI->db->limit(10);
        $query = $this->CI->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get_orders($params)
    {
        $this->CI->load->model('order/home_model');
        $lp_alerts = $this->CI->home_model->get_lp_alert_delete();
        $userdata = $this->CI->session->userdata('user');
        $email = $userdata['email'];
        $status = isset($params['status']) && !empty($params['status']) ? $params['status'] : '';
        $month = isset($params['month']) && !empty($params['month']) ? $params['month'] : '';
        $salesFlag = isset($params['salesFlag']) && !empty($params['salesFlag']) ? $params['salesFlag'] : '';
        $salesUser = isset($params['salesUser']) && !empty($params['salesUser']) ? $params['salesUser'] : '';
        $salesRepManagerFlag = isset($params['sales_rep_manager_flag']) && !empty($params['sales_rep_manager_flag']) ? $params['sales_rep_manager_flag'] : '';
        $is_pay_off = isset($params['is_pay_off']) && !empty($params['is_pay_off']) ? $params['is_pay_off'] : '';
        $yearFlag = isset($params['yearFlag']) && !empty($params['yearFlag']) ? $params['yearFlag'] : '';
        $order_type = isset($params['order_type']) && !empty($params['order_type']) ? $params['order_type'] : '';
        $dashboard_order_by = isset($params['dashboard_order_by']) && !empty($params['dashboard_order_by']) ? $params['dashboard_order_by'] : '';
        $result = $this->getUserFromPartners();
        $select = 'order_details.random_number,order_details.lp_report_status,order_details.lp_file_number,order_details.prelim_summary_id, order_details.created_at as opened_date, order_details.file_number, order_details.file_id,property_details.full_address,order_details.id, order_details.westcor_order_id, order_details.westcor_file_id, order_details.westcor_cpl_id, property_details.escrow_lender_id, order_details.is_regenerate_cpl, order_details.cpl_document_name,
            order_details.created_at, order_details.resware_status, order_details.proposed_insured_document_name, order_details.is_payoff_generated, pct_order_prelim_summary.is_updated,pct_order_prelim_summary.is_visited,pct_order_prelim_summary.generated_date, pct_order_documents.created as document_created_date, p.created as proposed_document_created_date,  property_details.primary_owner';

        if ($userdata['is_sales_rep_manager'] == 1) {
            $salesUsers = $this->CI->home_model->get_user(array('id' => $salesUser));
            if (!empty($salesUsers['sales_rep_users'])) {
                $salesUser = explode(',', $salesUsers['sales_rep_users']);
                if (!in_array($userdata['id'], $salesUser)) {
                    $salesUser[] = $userdata['id'];
                }
                if (!$salesRepManagerFlag) {
                    $this->CI->db->select('id');
                    $this->CI->db->from('customer_basic_details');
                    $this->CI->db->where('is_sales_rep', 1);
                    $this->CI->db->where('is_sales_rep_manager', 1);
                    $this->CI->db->where('id !=', $userdata['id']);
                    $this->CI->db->where('status', 1);
                    $salesMangers = $this->CI->db->get()->result_array();
                    if (!empty($salesMangers)) {
                        $salesMangers = array_column($salesMangers, 'id');
                        $salesUser = array_diff($salesUser, $salesMangers);
                    }
                }
            }
        }
        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            if (isset($keyword) && !empty($keyword) && $salesFlag == 1) {
                $this->CI->db->group_start()->like('property_details.full_address', $keyword);
                if ($order_type == 'resware_orders' || $order_type == 'open') {
                    $this->CI->db->or_like('order_details.file_number', $keyword);
                } else {
                    $this->CI->db->or_like('order_details.lp_file_number', $keyword);
                }
                $this->CI->db->or_like('order_details.created_at', date("Y-m-d", strtotime($keyword)));
                $this->CI->db->or_like('order_details.resware_status', $keyword)->group_end();
            } else {
                $this->CI->db->group_start()->like('property_details.full_address', $keyword);
                $this->CI->db->or_like('order_details.file_number', $keyword)->group_end();
            }

            if (isset($status) && !empty($status)) {
                if ($status == 'open') {
                    $this->CI->db->where('((order_details.resware_status != "closed" and  order_details.resware_status != "cancelled") OR order_details.resware_status IS NULL)');
                } else {
                    $this->CI->db->where('order_details.resware_status', $status);
                }
            }

            if (isset($month) && !empty($month)) {
                if ($status == 'open') {
                    $this->CI->db->where('MONTH(order_details.created_at)', $month);
                    $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
                } else {
                    $this->CI->db->where('MONTH(order_details.resware_closed_status_date)', $month);
                    $this->CI->db->where('YEAR(order_details.resware_closed_status_date)', date('Y'));
                }
            }

            if (isset($yearFlag) && !empty($yearFlag)) {
                $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
            }

            if (isset($is_pay_off) && !empty($is_pay_off)) {
                $select .= ', customer_basic_details.first_name, customer_basic_details.last_name';
            }

            if ($userdata['is_sales_rep_manager'] == 1) {
                $select .= ', sales_users.first_name as sales_first_name, sales_users.last_name as sales_last_name';
            }

            // if (!in_array($email, ['info@retech.company', 'awu@pct.com', 'teammeza@pct.com', 'unit66@pct.com', 'unit33@pct.com', 'jjean@pct.com', 'unit88@pct.com', 'sgrimaldo@pct.com', 'Mpilatti@pct.com'])) {
            if ($userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0) {
                $this->CI->db->group_start()->where('order_details.file_number is not null');
                $this->CI->db->where('order_details.file_number !=', 0)->group_end();
            } else if (isset($order_type) && !empty($order_type) && $order_type != 'open') {
                if ($order_type == 'resware_orders') {
                    //$this->CI->db->where('order_details.lp_file_number is null');
                    $this->CI->db->group_start()->where('order_details.file_number is not null');
                    $this->CI->db->where('order_details.file_number !=', 0)->group_end();
                } else if ($order_type == 'lp_orders') {
                    $this->CI->db->where('order_details.lp_file_number is not null');
                    // $this->CI->db->where('order_details.file_number', 0);
                    if (!empty($lp_alerts)) {
                        $this->CI->db->where("order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY)", null);
                        // $this->CI->db->where("order_details.created_at BETWEEN CURDATE() - INTERVAL ". $lp_alerts['days'] ." DAY AND CURDATE()", null);
                    }
                }
            } else {
                // $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.file_number = 0)";
                $lpOrderCondition = " OR (order_details.lp_file_number is not null)";
                if (!empty($lp_alerts)) {
                    // $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.file_number = 0 AND order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY))";
                    $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY))";
                }
                $whereClause = "((order_details.file_number IS NOT NULL AND order_details.file_number != 0) " . $lpOrderCondition . ")";
                $this->CI->db->where($whereClause);
            }

            $this->CI->db->select($select)
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('pct_order_documents', 'pct_order_documents.document_name = order_details.cpl_document_name', 'left')
                ->join('pct_order_documents as p', 'p.document_name = order_details.proposed_insured_document_name', 'left')
                ->join('pct_order_prelim_summary', 'order_details.prelim_summary_id = pct_order_prelim_summary.id', 'left');

            if (isset($is_pay_off) && !empty($is_pay_off)) {
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                $this->CI->db->join('customer_basic_details', 'customer_basic_details.id = transaction_details.title_officer', 'left');
                $this->CI->db->where('order_details.is_payoff_order', 1);
            }

            if ($userdata['is_master'] == 0 && $userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0 && $userdata['is_payoff_user'] == 0) {
                $this->CI->db->group_start()
                    ->where('order_details.customer_id', $userdata['id'])
                    ->or_where('property_details.escrow_lender_id', $userdata['id'])
                    ->group_end();
            }
            if ($userdata['is_master'] == 0 && $userdata['is_sales_rep'] == 1) {
                if ($userdata['is_sales_rep_manager'] == 1) {
                    $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                    $this->CI->db->join('customer_basic_details as sales_users', 'sales_users.id = transaction_details.sales_representative', 'inner');
                    if ($salesUser != 'all') {
                        $this->CI->db->where_in('transaction_details.sales_representative', $salesUser);
                    }
                } else {
                    $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                    $this->CI->db->where('transaction_details.sales_representative', $userdata['id']);
                }
            }

            if ($userdata['is_master'] == 0 && $userdata['is_title_officer'] == 1) {
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                $this->CI->db->where('transaction_details.title_officer', $userdata['id']);
            }

            if ($userdata['is_master'] == 1 && !empty($userdata['partner_companies'])) {
                if (!empty($result)) {
                    $this->CI->db->group_start()
                        ->where_in('order_details.customer_id', explode(',', $result['ids']))
                        ->or_where_in('property_details.escrow_lender_id', explode(',', $result['ids']))
                        ->group_end();
                }
            }

            $total_records = $this->CI->db->count_all_results();

            if (isset($keyword) && !empty($keyword) && $salesFlag == 1) {
                $this->CI->db->group_start()->like('property_details.full_address', $keyword);
                if ($order_type == 'resware_orders' || $order_type == 'open') {
                    $this->CI->db->or_like('order_details.file_number', $keyword);
                } else {
                    $this->CI->db->or_like('order_details.lp_file_number', $keyword);
                }
                $this->CI->db->or_like('order_details.created_at', date("Y-m-d", strtotime($keyword)));
                $this->CI->db->or_like('order_details.resware_status', $keyword)->group_end();
            } else {
                $this->CI->db->group_start()->like('property_details.full_address', $keyword);
                $this->CI->db->or_like('order_details.file_number', $keyword)->group_end();
            }

            if (isset($status) && !empty($status)) {
                if ($status == 'open') {
                    $this->CI->db->where('((order_details.resware_status != "closed" and  order_details.resware_status != "cancelled") OR order_details.resware_status IS NULL)');
                } else {
                    $this->CI->db->where('order_details.resware_status', $status);
                }
            }

            if (isset($month) && !empty($month)) {
                if ($status == 'open') {
                    $this->CI->db->where('MONTH(order_details.created_at)', $month);
                    $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
                } else {
                    $this->CI->db->where('MONTH(order_details.resware_closed_status_date)', $month);
                    $this->CI->db->where('YEAR(order_details.resware_closed_status_date)', date('Y'));
                }
            }

            if (isset($yearFlag) && !empty($yearFlag)) {
                $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
            }

            // if (!in_array($email, ['info@retech.company', 'awu@pct.com', 'teammeza@pct.com', 'unit66@pct.com', 'unit33@pct.com', 'jjean@pct.com', 'unit88@pct.com', 'sgrimaldo@pct.com', 'Mpilatti@pct.com'])) {
            if ($userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0) {
                $this->CI->db->group_start()->where('order_details.file_number is not null');
                $this->CI->db->where('order_details.file_number !=', 0)->group_end();
            } else if (isset($order_type) && !empty($order_type) && $order_type != 'open') {
                if ($order_type == 'resware_orders') {
                    //$this->CI->db->where('order_details.lp_file_number is null');
                    $this->CI->db->group_start()->where('order_details.file_number is not null');
                    $this->CI->db->where('order_details.file_number !=', 0)->group_end();
                } else if ($order_type == 'lp_orders') {
                    $this->CI->db->where('order_details.lp_file_number is not null');
                    // $this->CI->db->where('order_details.file_number', 0);
                    if (!empty($lp_alerts)) {
                        $this->CI->db->where("order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY)", null);
                        // $this->CI->db->where("order_details.created_at BETWEEN CURDATE() - INTERVAL ". $lp_alerts['days'] ." DAY AND CURDATE()", null);
                    }
                }
            } else {
                // $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.file_number = 0)";
                $lpOrderCondition = " OR (order_details.lp_file_number is not null)";
                if (!empty($lp_alerts)) {
                    // $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.file_number = 0 AND order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY))";
                    $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY))";
                }
                $whereClause = "((order_details.file_number IS NOT NULL AND order_details.file_number != 0) " . $lpOrderCondition . ")";
                $this->CI->db->where($whereClause);
            }

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
            $orders_lists = array();

            $this->CI->db->select($select)
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('pct_order_documents', 'pct_order_documents.document_name = order_details.cpl_document_name', 'left')
                ->join('pct_order_documents as p', 'p.document_name = order_details.proposed_insured_document_name', 'left')
                ->join('pct_order_prelim_summary', 'order_details.prelim_summary_id = pct_order_prelim_summary.id', 'left');

            if (isset($is_pay_off) && !empty($is_pay_off)) {
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                $this->CI->db->join('customer_basic_details', 'customer_basic_details.id = transaction_details.title_officer', 'left');
                $this->CI->db->where('order_details.is_payoff_order', 1);
            }

            if ($userdata['is_master'] == 0 && $userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0 && $userdata['is_payoff_user'] == 0) {
                $this->CI->db->group_start()
                    ->where('order_details.customer_id', $userdata['id'])
                    ->or_where('property_details.escrow_lender_id', $userdata['id'])
                    ->group_end();
            }
            if ($userdata['is_master'] == 0 && $userdata['is_sales_rep'] == 1) {
                if ($userdata['is_sales_rep_manager'] == 1) {
                    $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                    $this->CI->db->join('customer_basic_details as sales_users', 'sales_users.id = transaction_details.sales_representative', 'inner');
                    if ($salesUser != 'all') {
                        $this->CI->db->where_in('transaction_details.sales_representative', $salesUser);
                    }
                } else {
                    $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                    $this->CI->db->where('transaction_details.sales_representative', $userdata['id']);
                }
            }
            if ($userdata['is_master'] == 0 && $userdata['is_title_officer'] == 1) {
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                $this->CI->db->where('transaction_details.title_officer', $userdata['id']);
            }
            if ($userdata['is_master'] == 1 && !empty($userdata['partner_companies'])) {
                if (!empty($result)) {
                    $this->CI->db->group_start()
                        ->where_in('order_details.customer_id', explode(',', $result['ids']))
                        ->or_where_in('property_details.escrow_lender_id', explode(',', $result['ids']))
                        ->group_end();
                }
            }

            if (!empty($dashboard_order_by)) {
                $this->CI->db->order_by('order_details.prelim_summary_id desc');
                $this->CI->db->order_by("order_details.id", "desc");
            } else {
                $this->CI->db->order_by("order_details.id", "desc");
            }

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

            $query = $this->CI->db->get();
            if ($query->num_rows() > 0) {
                $orders_lists = $query->result_array();
            }
            // print_r($this->CI->db->last_query());die;

        } else {
            if (isset($status) && !empty($status)) {
                if ($status == 'open') {
                    $this->CI->db->where('((order_details.resware_status != "closed" and  order_details.resware_status != "cancelled") OR order_details.resware_status IS NULL)');
                } else {
                    $this->CI->db->where('order_details.resware_status', $status);
                }
            }

            if (isset($month) && !empty($month)) {
                if ($status == 'open') {
                    $this->CI->db->where('MONTH(order_details.created_at)', $month);
                    $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
                } else {
                    $this->CI->db->where('MONTH(order_details.resware_closed_status_date)', $month);
                    $this->CI->db->where('YEAR(order_details.resware_closed_status_date)', date('Y'));
                }
            }

            if (isset($yearFlag) && !empty($yearFlag)) {
                $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
            }

            if (isset($is_pay_off) && !empty($is_pay_off)) {
                $select .= ', customer_basic_details.first_name, customer_basic_details.last_name';
            }

            if ($userdata['is_sales_rep_manager'] == 1) {
                $select .= ', sales_users.first_name as sales_first_name, sales_users.last_name as sales_last_name';
            }

            // if (!in_array($email, ['info@retech.company', 'awu@pct.com', 'teammeza@pct.com', 'unit66@pct.com', 'unit33@pct.com', 'jjean@pct.com', 'unit88@pct.com', 'sgrimaldo@pct.com', 'Mpilatti@pct.com'])) {
            if ($userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0) {
                $this->CI->db->group_start()->where('order_details.file_number is not null');
                $this->CI->db->where('order_details.file_number !=', 0)->group_end();
            } else if (isset($order_type) && !empty($order_type) && $order_type != 'open') {
                if ($order_type == 'resware_orders') {
                    //$this->CI->db->where('order_details.lp_file_number is null');
                    $this->CI->db->group_start()->where('order_details.file_number is not null');
                    $this->CI->db->where('order_details.file_number !=', 0)->group_end();
                } else if ($order_type == 'lp_orders') {
                    $this->CI->db->where('order_details.lp_file_number is not null');
                    // $this->CI->db->where('order_details.file_number', 0);
                    if (!empty($lp_alerts)) {
                        $this->CI->db->where("order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY)", null);
                        // $this->CI->db->where("order_details.created_at BETWEEN CURDATE() - INTERVAL ". $lp_alerts['days'] ." DAY AND CURDATE()", null);
                    }
                }
            } else {
                // $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.file_number = 0)";
                $lpOrderCondition = " OR (order_details.lp_file_number is not null)";
                if (!empty($lp_alerts)) {
                    // $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.file_number = 0 AND order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY))";
                    $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY))";
                }
                $whereClause = "((order_details.file_number IS NOT NULL AND order_details.file_number != 0) " . $lpOrderCondition . ")";
                $this->CI->db->where($whereClause);
            }

            $this->CI->db->select($select)
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('pct_order_documents', 'pct_order_documents.document_name = order_details.cpl_document_name', 'left')
                ->join('pct_order_documents as p', 'p.document_name = order_details.proposed_insured_document_name', 'left')
                ->join('pct_order_prelim_summary', 'order_details.prelim_summary_id = pct_order_prelim_summary.id', 'left');

            if (isset($is_pay_off) && !empty($is_pay_off)) {
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                $this->CI->db->join('customer_basic_details', 'customer_basic_details.id = transaction_details.title_officer', 'left');
                $this->CI->db->where('order_details.is_payoff_order', 1);
            }

            if ($userdata['is_master'] == 0 && $userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0 && $userdata['is_payoff_user'] == 0) {
                $this->CI->db->group_start()
                    ->where('order_details.customer_id', $userdata['id'])
                    ->or_where('property_details.escrow_lender_id', $userdata['id'])
                    ->group_end();
            }
            if ($userdata['is_master'] == 0 && $userdata['is_sales_rep'] == 1) {
                if ($userdata['is_sales_rep_manager'] == 1) {
                    $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                    $this->CI->db->join('customer_basic_details as sales_users', 'sales_users.id = transaction_details.sales_representative', 'inner');
                    if ($salesUser != 'all') {
                        $this->CI->db->where_in('transaction_details.sales_representative', $salesUser);
                    }
                } else {
                    $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                    $this->CI->db->where('transaction_details.sales_representative', $userdata['id']);
                }
            }
            if ($userdata['is_master'] == 0 && $userdata['is_title_officer'] == 1) {
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                $this->CI->db->where('transaction_details.title_officer', $userdata['id']);
            }
            if ($userdata['is_master'] == 1 && !empty($userdata['partner_companies'])) {
                if (!empty($result)) {
                    $this->CI->db->group_start()
                        ->where_in('order_details.customer_id', explode(',', $result['ids']))
                        ->or_where_in('property_details.escrow_lender_id', explode(',', $result['ids']))
                        ->group_end();
                }
            }

            /*if ($userdata['is_master'] == 0) {
            $this->CI->db->where('order_details.customer_id', $userdata['id']);
            }*/

            $total_records = $this->CI->db->count_all_results();

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
            $orders_lists = array();

            if (isset($status) && !empty($status)) {
                if ($status == 'open') {
                    $this->CI->db->where('((order_details.resware_status != "closed" and  order_details.resware_status != "cancelled") OR order_details.resware_status IS NULL)');
                } else {
                    $this->CI->db->where('order_details.resware_status', $status);
                }
            }

            if (isset($month) && !empty($month)) {
                if ($status == 'open') {
                    $this->CI->db->where('MONTH(order_details.created_at)', $month);
                    $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
                } else {
                    $this->CI->db->where('MONTH(order_details.resware_closed_status_date)', $month);
                    $this->CI->db->where('YEAR(order_details.resware_closed_status_date)', date('Y'));
                }
            }

            if (isset($yearFlag) && !empty($yearFlag)) {
                $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
            }

            // if (!in_array($email, ['info@retech.company', 'awu@pct.com', 'teammeza@pct.com', 'unit66@pct.com', 'unit33@pct.com', 'jjean@pct.com', 'unit88@pct.com', 'sgrimaldo@pct.com', 'Mpilatti@pct.com'])) {
            if ($userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0) {
                $this->CI->db->group_start()->where('order_details.file_number is not null');
                $this->CI->db->where('order_details.file_number !=', 0)->group_end();
            } else if (isset($order_type) && !empty($order_type) && $order_type != 'open') {
                if ($order_type == 'resware_orders') {
                    //$this->CI->db->where('order_details.lp_file_number is null');
                    $this->CI->db->group_start()->where('order_details.file_number is not null');
                    $this->CI->db->where('order_details.file_number !=', 0)->group_end();
                } else if ($order_type == 'lp_orders') {
                    $this->CI->db->where('order_details.lp_file_number is not null');
                    // $this->CI->db->where('order_details.file_number', 0);
                    if (!empty($lp_alerts)) {
                        $this->CI->db->where("order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY)", null);
                        // $this->CI->db->where("order_details.created_at BETWEEN CURDATE() - INTERVAL ". $lp_alerts['days'] ." DAY AND CURDATE()", null);
                    }
                    // $this->CI->db->where('order_details.created_at <= DATE_ADD(NOW(),INTERVAL ' . $lp_alerts['days'] . ' DAYS)', null);

                }
            } else {
                // $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.file_number = 0)";
                $lpOrderCondition = " OR (order_details.lp_file_number is not null)";
                if (!empty($lp_alerts)) {
                    // $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.file_number = 0 AND order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY))";
                    $lpOrderCondition = " OR (order_details.lp_file_number is not null AND order_details.created_at >= DATE_ADD(NOW(),INTERVAL -" . $lp_alerts['days'] . " DAY))";
                }
                $whereClause = "((order_details.file_number IS NOT NULL AND order_details.file_number != 0) " . $lpOrderCondition . ")";
                // print_r($whereClause);die;
                $this->CI->db->where($whereClause);
            }

            $this->CI->db->select($select)
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('pct_order_documents', 'pct_order_documents.document_name = order_details.cpl_document_name', 'left')
                ->join('pct_order_documents as p', 'p.document_name = order_details.proposed_insured_document_name', 'left')
                ->join('pct_order_prelim_summary', 'order_details.prelim_summary_id = pct_order_prelim_summary.id', 'left');

            if (isset($is_pay_off) && !empty($is_pay_off)) {
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                $this->CI->db->join('customer_basic_details', 'customer_basic_details.id = transaction_details.title_officer', 'left');
                $this->CI->db->where('order_details.is_payoff_order', 1);
            }

            if ($userdata['is_master'] == 0 && $userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0 && $userdata['is_payoff_user'] == 0) {
                $this->CI->db->group_start()
                    ->where('order_details.customer_id', $userdata['id'])
                    ->or_where('property_details.escrow_lender_id', $userdata['id'])
                    ->group_end();
            }
            if ($userdata['is_master'] == 0 && $userdata['is_sales_rep'] == 1) {
                if ($userdata['is_sales_rep_manager'] == 1) {
                    $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                    $this->CI->db->join('customer_basic_details as sales_users', 'sales_users.id = transaction_details.sales_representative', 'inner');
                    if ($salesUser != 'all') {
                        $this->CI->db->where_in('transaction_details.sales_representative', $salesUser);
                    }
                } else {
                    $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                    $this->CI->db->where('transaction_details.sales_representative', $userdata['id']);
                }
            }
            if ($userdata['is_master'] == 0 && $userdata['is_title_officer'] == 1) {
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
                $this->CI->db->where('transaction_details.title_officer', $userdata['id']);
            }
            if ($userdata['is_master'] == 1 && !empty($userdata['partner_companies'])) {
                if (!empty($result)) {
                    $this->CI->db->group_start()
                        ->where_in('order_details.customer_id', explode(',', $result['ids']))
                        ->or_where_in('property_details.escrow_lender_id', explode(',', $result['ids']))
                        ->group_end();
                }
            }

            if (!empty($dashboard_order_by)) {
                $this->CI->db->order_by('order_details.prelim_summary_id desc');
                $this->CI->db->order_by("order_details.id", "desc");
            } else {
                $this->CI->db->order_by("order_details.id", "desc");
            }

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

            $query = $this->CI->db->get();
            // print_r($this->CI->db->last_query());die;
            if ($query->num_rows() > 0) {
                $orders_lists = $query->result_array();
            }
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_records,
            'data' => $orders_lists,
        );
    }

    public function get_document_types()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_documents_types');
        $query = $this->CI->db->get();
        return $query->result_array();
    }

    public function getUserFromPartners()
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('GROUP_CONCAT(id) as ids');
        $this->CI->db->from('customer_basic_details');
        $this->CI->db->where_in('partner_id', explode(',', $userdata['partner_companies']));
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function get_order_details($fileId, $from_mail = 0)
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('
            order_details.lp_file_number,
            order_details.file_number,
            order_details.customer_id,
            order_details.id as order_id,
            order_details.file_id,
            order_details.random_number,
            order_details.westcor_order_id,
            order_details.westcor_cpl_id,
            order_details.westcor_buyer_id,
            order_details.westcor_seller_id,
            order_details.westcor_secondary_buyer_id,
            order_details.westcor_secondary_seller_id,
            order_details.westcor_lender_id,
            order_details.westcor_file_id,
            order_details.is_regenerate_cpl,
            order_details.created_at as opened_date,
            order_details.fnf_agent_id,
            order_details.fnf_document_id,
            order_details.cpl_document_name,
            order_details.proposed_insured_document_name,
            order_details.verification_code,
            order_details.verification_code_for_seller,
            order_details.code_created_at,
            order_details.code_created_at_for_seller,
            order_details.borrower_mobile_number,
            order_details.borrower_mobile_number_for_seller,
            order_details.proposed_branch_id,
            order_details.escrow_officer_id,
            order_details.premium,
            order_details.is_create_order_on_safewire,
            order_details.safewire_action_link,
            order_details.prod_type,
            order_details.resware_status,
            order_details.borrower_email,
            property_details.id as property_id,
            property_details.address,
            property_details.full_address,
            property_details.property_type,
            property_details.city as property_city,
            property_details.state as property_state,
            property_details.zip as property_zip,
            property_details.county,
            property_details.westcor_property_id,
            property_details.legal_description,
            property_details.primary_owner,
            property_details.secondary_owner,
            property_details.escrow_lender_id,
            property_details.cpl_lender_id,
            property_details.buyer_agent_id,
            property_details.listing_agent_id,
            property_details.borrowers_vesting,
            property_details.cpl_proposed_property_address,
            property_details.cpl_proposed_property_city,
            property_details.cpl_proposed_property_state,
            property_details.cpl_proposed_property_zip,
            property_details.unit_number,
            property_details.apn,
            transaction_details.id as transaction_id,
            transaction_details.sales_representative,
            transaction_details.title_officer,
            transaction_details.sales_amount,
            transaction_details.loan_amount,
            transaction_details.loan_number,
            transaction_details.transaction_type,
            transaction_details.purchase_type,
            transaction_details.supplemental_report_date,
            transaction_details.preliminary_report_date,
            transaction_details.borrower,
            transaction_details.secondary_borrower,
            transaction_details.vesting,
            transaction_details.escrow_number,
            transaction_details.additional_email,
            customer_basic_details.id as lender_id,
            customer_basic_details.partner_id as lender_partner_id,
            customer_basic_details.street_address as lender_address,
            customer_basic_details.city as lender_city,
            customer_basic_details.state as lender_state,
            customer_basic_details.zip_code as lender_zipcode,
            customer_basic_details.company_name as lender_company_name,
            customer_basic_details.first_name as lender_first_name,
            customer_basic_details.last_name as lender_last_name,
            customer_basic_details.email_address as lender_email,
            customer_basic_details.assignment_clause as lender_assignment_clause,
            customer_basic_details.is_escrow,
            customer_basic_details.telephone_no as lender_telephone_no,
            cbd.first_name as cust_first_name,
            cbd.last_name as cust_last_name,
            cbd.company_name as cust_company_name,
            cbd.street_address as cust_address,
            cbd.city as cust_city,
            cbd.state as cust_state,
            cbd.zip_code as cust_zipcode,
            cbd.is_escrow as is_client_escrow,
            salerep.first_name as salerep_first_name,
            salerep.last_name as salerep_last_name,
            salerep.is_mail_notification as salerep_is_mail_notification,
            salerep.email_address as salerep_email_address,
            titleofficer.first_name as titleofficer_first_name,
            titleofficer.last_name as titleofficer_last_name,
            titleofficer.email_address as title_officer_email,
            titleofficer.id as title_officer_id,
            agents.name as agent_name,
            agents.address as agent_address,
            agents.email_address as agent_email_address,
            agents.city as agent_city,
            agents.zipcode as agent_zipcode,
            agents.telephone_no as agent_telephone_no,
            agents.company as agent_company,
            pct_order_fnf_agents.agent_number,
            pct_order_fnf_agents.underwriter_code,
            pct_order_fnf_agents.underwriter,
            pct_order_product_types.product_type,
            pct_order_product_types.transaction_type,
            pct_order_documents.created,
            p.created as proposed_document_created_date')
            ->from('order_details')
            ->join('property_details', 'order_details.property_id = property_details.id')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
            ->join('customer_basic_details', 'property_details.escrow_lender_id = customer_basic_details.id', 'left')
            ->join('customer_basic_details as cbd', 'order_details.customer_id = cbd.id', 'left')
            ->join('customer_basic_details as titleofficer', 'transaction_details.title_officer = titleofficer.id', 'left')
            ->join('customer_basic_details as salerep', 'transaction_details.sales_representative = salerep.id', 'left')
            ->join('pct_order_documents', 'pct_order_documents.document_name = order_details.cpl_document_name', 'left')
            ->join('pct_order_documents as p', 'p.document_name = order_details.proposed_insured_document_name', 'left')
            ->join('agents', 'property_details.buyer_agent_id = agents.id', 'left')
            ->join('pct_order_fnf_agents', 'order_details.fnf_agent_id = pct_order_fnf_agents.id', 'left')
            ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');
        $this->CI->db->where('file_id', $fileId);

        if (isset($userdata) && $userdata['is_master'] == 0 && $from_mail == 0 && $userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0 && $userdata['is_payoff_user'] == 0 && $userdata['is_escrow_officer'] == 0 && $userdata['is_escrow_assistant'] == 0) {
            $this->CI->db->group_start()
                ->where('order_details.customer_id', $userdata['id'])
                ->or_where('property_details.escrow_lender_id', $userdata['id'])
                ->group_end();
        }
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function is_user()
    {
        $userdata = $this->CI->session->userdata('user');
        if (!empty($userdata['id'])) {
            if ($userdata['is_title_officer'] == 1) {
                redirect(base_url() . 'title-officer-dashboard');
            } else if ($userdata['is_sales_rep'] == 1) {
                redirect(base_url() . 'sales-dashboard/' . $userdata['id']);
            } else if ($userdata['is_special_lender'] == 1) {
                redirect(base_url() . 'special-lender-dashboard');
            }
        } else {
            redirect(base_url() . 'order/login');
        }
    }

    public function get_order_documents($fileId, $from_mail = 0)
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('order_details.file_number,
                order_details.file_id,
                order_details.id as order_id,
                pct_order_documents.document_name,
                pct_order_documents.document_name,
                pct_order_documents.original_document_name,
                pct_order_documents.is_sync,
                pct_order_documents.is_prelim_document,
                pct_order_documents.api_document_id,
                pct_order_documents.index_number,
                pct_order_documents.is_linked_doc')
            ->from('order_details')
            ->join('property_details', 'order_details.property_id = property_details.id')
            ->join('pct_order_documents', 'order_details.id = pct_order_documents.order_id');
        $this->CI->db->where('order_details.file_id', $fileId);
        if ($userdata['is_master'] == 0 && $from_mail == 0) {
            $this->CI->db->group_start()
                ->where('order_details.customer_id', $userdata['id'])
                ->or_where('property_details.escrow_lender_id', $userdata['id'])
                ->group_end();
        }
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get_order_linked_documents($fileId, $from_mail = 0)
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('order_details.file_number,
                order_details.file_id,
                order_details.id as order_id,
                pct_order_documents.document_name,
                pct_order_documents.id,
                pct_order_documents.original_document_name,
                pct_order_documents.is_sync,
                pct_order_documents.is_prelim_document,
                pct_order_documents.api_document_id,
                pct_order_documents.index_number,
                pct_order_documents.is_linked_doc')
            ->from('order_details')
            ->join('property_details', 'order_details.property_id = property_details.id')
            ->join('pct_order_documents', 'order_details.id = pct_order_documents.order_id');

        $this->CI->db->where('order_details.file_id', $fileId);
        $this->CI->db->where('pct_order_documents.is_linked_doc', 1);
        $this->CI->db->order_by('pct_order_documents.index_number', 'asc');
        if ($userdata['is_master'] == 0 && $from_mail == 0) {
            $this->CI->db->group_start()
                ->where('order_details.customer_id', $userdata['id'])
                ->or_where('property_details.escrow_lender_id', $userdata['id'])
                ->group_end();
        }
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function update($data, $condition = array())
    {
        $table = 'order_details';

        if (!empty($data)) {
            $data['updated_at'] = date("Y-m-d H:i:s");

            // Update data
            $update = $this->CI->db->update($table, $data, $condition);
            // Return the status
            return $update ? true : false;
        }
        return false;
    }

    public function get_linked_documents($order_id)
    {
        $this->CI->db->select('*')
            ->from('pct_order_documents');

        $this->CI->db->where('order_id', $order_id);
        $this->CI->db->where('is_linked_doc', 1);
        $this->CI->db->order_by('index_number', 'asc');
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get_user_documents($order_id)
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('*')
            ->from('pct_order_documents');

        if ($userdata['is_title_officer'] == 0) {
            $this->CI->db->where('user_id', $userdata['id']);
        }

        $this->CI->db->where('order_id', $order_id);
        $this->CI->db->order_by('id', 'desc');
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getOrderdocuments($params)
    {
        $userdata = $this->CI->session->userdata('user');

        $this->CI->db->select('*')
            ->from('pct_order_documents');

        if ($userdata['is_title_officer'] == 0) {
            if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                $this->CI->db->group_start()
                    ->where('user_id', $userdata['id'])
                    ->or_where('is_uploaded_by_borrower', 1)
                    ->group_end();
            } else {
                $this->CI->db->where('user_id', $userdata['id']);
            }
        }
        $this->CI->db->where('order_id', $params['order_id']);
        $total_records = $this->CI->db->count_all_results();

        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $document_lists = array();

        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->like('original_document_name', $keyword);
            }

            if ($userdata['is_title_officer'] == 0) {
                if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                    $this->CI->db->group_start()
                        ->where('user_id', $userdata['id'])
                        ->or_where('is_uploaded_by_borrower', 1)
                        ->group_end();
                } else {
                    $this->CI->db->where('user_id', $userdata['id']);
                }
            }

            $this->CI->db->where('order_id', $params['order_id']);
            $this->CI->db->select('*')
                ->from('pct_order_documents');
            $total_records = $this->CI->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->like('original_document_name', $keyword);
            }

            if ($userdata['is_title_officer'] == 0) {
                if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                    $this->CI->db->group_start()
                        ->where('user_id', $userdata['id'])
                        ->or_where('is_uploaded_by_borrower', 1)
                        ->group_end();
                } else {
                    $this->CI->db->where('user_id', $userdata['id']);
                }
            }

            $this->CI->db->where('order_id', $params['order_id']);
            $this->CI->db->select('*')
                ->from('pct_order_documents');

            $this->CI->db->order_by('id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

            $query = $this->CI->db->get();
            if ($query->num_rows() > 0) {
                $document_lists = $query->result_array();
            }
        } else {
            if ($userdata['is_title_officer'] == 0) {
                if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                    $this->CI->db->group_start()
                        ->where('user_id', $userdata['id'])
                        ->or_where('is_uploaded_by_borrower', 1)
                        ->group_end();
                } else {
                    $this->CI->db->where('user_id', $userdata['id']);
                }
            }

            $this->CI->db->where('order_id', $params['order_id']);
            $this->CI->db->select('*')
                ->from('pct_order_documents');

            $this->CI->db->order_by('id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

            $query = $this->CI->db->get();
            if ($query->num_rows() > 0) {
                $document_lists = $query->result_array();
            }
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_records,
            'data' => $document_lists,
        );
    }

    public function get_prelim_document($order_id)
    {
        $this->CI->db->select('*')
            ->from('pct_order_documents');

        $this->CI->db->where('order_id', $order_id);
        $this->CI->db->where('is_prelim_document', 1);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function get_document_detail($api_document_id, $order_id, $document_id)
    {
        $this->CI->db->select('*')
            ->from('pct_order_documents');

        $this->CI->db->where('api_document_id', $api_document_id);
        $this->CI->db->where('order_id', $order_id);
        $this->CI->db->where('id', $document_id);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function get_rows($params = array())
    {
        $table = 'order_details';

        $this->CI->db->select('*');
        $this->CI->db->from($table);

        if (array_key_exists("where", $params)) {
            foreach ($params['where'] as $key => $val) {
                $this->CI->db->where($key, $val);
            }
        }

        if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
            $result = $this->CI->db->count_all_results();
        } else {
            if (array_key_exists("id", $params)) {
                $this->CI->db->where('id', $params['id']);
                $query = $this->CI->db->get();
                $result = $query->row_array();
            } else {
                $this->CI->db->order_by('id', 'asc');
                if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->CI->db->limit($params['limit'], $params['start']);
                } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->CI->db->limit($params['limit']);
                }

                $query = $this->CI->db->get();
                $result = ($query->num_rows() > 0) ? $query->row_array() : false;
            }
        }

        // Return fetched data
        return $result;
    }

    public function randomPassword()
    {
        $len = 8;
        $sets = array();
        $sets[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $sets[] = 'abcdefghijkmnopqrstuvwxyz';
        $sets[] = '0123456789';
        // $sets[]  = '~!@#$%^&*(){}[],./?';
        $password = '';

        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }

        while (strlen($password) < $len) {
            $randomSet = $sets[array_rand($sets)];
            $password .= $randomSet[array_rand(str_split($randomSet))];
        }
        return str_shuffle($password);
    }

    public function checkDuplicateOrder($apn)
    {
        $this->CI->db->select('*')
            ->from('property_details');

        $this->CI->db->where('apn', $apn);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            $count = $query->num_rows();
            if ($count == 1) {
                $propertyData = $query->row_array();
                if ($propertyData['allow_duplication'] == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                $propertyData = $query->result_array();
                $key = array_search(1, array_column($propertyData, 'allow_duplication'));
                if ($key !== false) {
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public function get_special_lenders_orders($params)
    {
        $userdata = $this->CI->session->userdata('user');
        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                    ->like("property_details.full_address", $keyword)
                    ->or_like('order_details.file_number', $keyword)
                    ->group_end();
            }
            $this->CI->db->select('order_details.file_number,
                order_details.file_id,
                property_details.full_address,
                order_details.id,
                customer_basic_details.company_name,
                pct_order_sales_rep.name,
                order_details.created_at,
                property_details.primary_owner')
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('pct_order_sales_rep', 'pct_order_sales_rep.id = transaction_details.sales_representative', 'left');
            $this->CI->db->where('property_details.escrow_lender_id', $userdata['id']);

            $total_records = $this->CI->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                    ->like("property_details.full_address", $keyword)
                    ->or_like('order_details.file_number', $keyword)
                    ->or_like('order_details.file_number', $keyword)
                    ->group_end();
            }
            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
            $orders_lists = array();

            $this->CI->db->select('order_details.file_number,
                order_details.file_id,
                property_details.full_address,
                order_details.id,
                customer_basic_details.company_name,
                pct_order_sales_rep.name,
                order_details.created_at,
                property_details.primary_owner')
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('pct_order_sales_rep', 'pct_order_sales_rep.id = transaction_details.sales_representative', 'left');
            $this->CI->db->where('property_details.escrow_lender_id', $userdata['id']);
            $this->CI->db->order_by("order_details.id", "desc");

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

            $query = $this->CI->db->get();

            if ($query->num_rows() > 0) {
                $orders_lists = $query->result_array();
            }
        } else {
            $this->CI->db->select('order_details.file_number,
                order_details.file_id,
                property_details.full_address,
                order_details.id,
                customer_basic_details.company_name,
                pct_order_sales_rep.name,
                order_details.created_at,
                property_details.primary_owner')
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('pct_order_sales_rep', 'pct_order_sales_rep.id = transaction_details.sales_representative', 'left');
            $this->CI->db->where('property_details.escrow_lender_id', $userdata['id']);

            $total_records = $this->CI->db->count_all_results();

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
            $orders_lists = array();

            $this->CI->db->select('order_details.file_number,
                order_details.file_id,
                property_details.full_address,
                order_details.id,
                customer_basic_details.company_name,
                pct_order_sales_rep.name,
                order_details.created_at,
                property_details.primary_owner')
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('pct_order_sales_rep', 'pct_order_sales_rep.id = transaction_details.sales_representative', 'left');
            $this->CI->db->where('property_details.escrow_lender_id', $userdata['id']);
            $this->CI->db->order_by("order_details.id", "desc");

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

            $query = $this->CI->db->get();

            if ($query->num_rows() > 0) {
                $orders_lists = $query->result_array();
            }
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_records,
            'data' => $orders_lists,
        );
    }

    public function checkCompanyExist($partner_company_id)
    {
        $this->CI->db->select('*')
            ->from('pct_order_partner_company_info');

        $this->CI->db->where('partner_id', $partner_company_id);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_order_uploaded_documents($fileId)
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('order_details.file_number,
                order_details.file_id,
                order_details.id as order_id,
                pct_order_documents.id,
                pct_order_documents.document_name,
                pct_order_documents.original_document_name,
                pct_order_documents.is_sync,
                pct_order_documents.is_prelim_document,
                pct_order_documents.api_document_id,
                pct_order_documents.index_number,
                pct_order_documents.is_linked_doc')
            ->from('order_details')
            ->join('pct_order_documents', 'order_details.id = pct_order_documents.order_id');

        $this->CI->db->where('order_details.file_id', $fileId);
        $this->CI->db->group_start()
            ->where('pct_order_documents.is_grant_doc', 1)
            ->or_where('pct_order_documents.is_cpl_doc', 1)
            ->or_where('pct_order_documents.is_proposed_insured_doc', 1)
            ->group_end();

        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get_resware_admin_credential()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_resware_admin_credential');
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function get_order($params)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('order_details');

        if (array_key_exists("where", $params)) {
            foreach ($params['where'] as $key => $val) {
                $this->CI->db->where($key, $val);
            }
        }

        if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
            $result = $this->CI->db->count_all_results();
        } else {
            if (array_key_exists("id", $params)) {
                $this->CI->db->where('id', $params['id']);
                $query = $this->CI->db->get();
                $result = $query->row_array();
            } else {
                $this->CI->db->order_by('id', 'asc');
                if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->CI->db->limit($params['limit'], $params['start']);
                } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->CI->db->limit($params['limit']);
                }

                $query = $this->CI->db->get();
                $result = ($query->num_rows() > 0) ? $query->result_array() : false;
            }
        }

        // Return fetched data
        return $result;
    }

    public function get_borrower_info($orderId, $buyerFlag)
    {
        $this->CI->db->select('*')
            ->from('pct_order_borrower_info');

        $this->CI->db->where('order_id', $orderId);
        $this->CI->db->where('is_buyer', $buyerFlag);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get_borrower_residence_info($orderId, $buyerFlag)
    {
        $this->CI->db->select('*')
            ->from('pct_order_borrower_residence_info');

        $this->CI->db->where('order_id', $orderId);
        $this->CI->db->where('is_buyer', $buyerFlag);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get_borrower_employment_info($orderId, $buyerFlag)
    {
        $this->CI->db->select('*')
            ->from('pct_order_borrower_employment_info');

        $this->CI->db->where('order_id', $orderId);
        $this->CI->db->where('is_buyer', $buyerFlag);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function storeCplError($data)
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->load->model('order/home_model');

        if (!empty($userdata['id'])) {
            $user_id = $userdata['id'];
        } else {
            $user_id = 0;
        }

        $errorLogsdata = array(
            'user_id' => $user_id,
            'order_id' => $data['order_id'],
            'file_number' => $data['file_number'],
            'cpl_page' => $data['cpl_page'],
            'error' => $data['error'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $this->CI->db->insert('pct_order_cpl_api_logs', $errorLogsdata);
        $customer_id = $data['customer_id'];
        $subject = 'CPL Document Not Generated';
        $property = $data['property_address'];
        $condition = array(
            'id' => $customer_id,
        );
        $customerDetails = $this->CI->home_model->get_customers($condition);

        if (!empty($customerDetails)) {
            $first_name = isset($customerDetails['first_name']) && !empty($customerDetails['first_name']) ? $customerDetails['first_name'] : '';
            $last_name = isset($customerDetails['last_name']) && !empty($customerDetails['last_name']) ? $customerDetails['last_name'] : '';
            $telephone_no = isset($customerDetails['telephone_no']) && !empty($customerDetails['telephone_no']) ? $customerDetails['telephone_no'] : '';
            $email_address = isset($customerDetails['email_address']) && !empty($customerDetails['email_address']) ? $customerDetails['email_address'] : '';
            $company_name = isset($customerDetails['company_name']) && !empty($customerDetails['company_name']) ? $customerDetails['company_name'] : '';
            $street_address = isset($customerDetails['street_address']) && !empty($customerDetails['street_address']) ? $customerDetails['street_address'] : '';
            $city = isset($customerDetails['city']) && !empty($customerDetails['city']) ? $customerDetails['city'] : '';
            $zipcode = isset($customerDetails['zip_code']) && !empty($customerDetails['zip_code']) ? $customerDetails['zip_code'] : '';

            $message = '<h3>User Details:</h3><p>Name: ' . $first_name . ' ' . $last_name . '</p><p>Telephone: ' . $telephone_no . '</p><p>Email Address: ' . $email_address . '</p><p>Company Name: ' . $company_name . '</p><p>Street Address: ' . $street_address . '</p><p>City: ' . $city . '</p><p>Zipcode: ' . $zipcode . '</p><p>Property Address: ' . $property . '</p>';

            $from_name = 'Pacific Coast Title Company';
            $from_mail = env('FROM_EMAIL');
            $subject = 'Notification for ' . $subject;
            $to = env('ADMIN_EMAIL');
            $this->CI->load->helper('sendemail');
            $mail_result = send_email($from_mail, $from_name, $to, $subject, $message);
        }
        return true;
    }

    public function getProposedBranches()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_proposed_branches');
        $query = $this->CI->db->get();
        return $query->result_array();
    }

    public function getProposedBranchDetail($branchId)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_proposed_branches');
        $this->CI->db->where('id', $branchId);
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function syncSafewireDocuments($orderUrl, $wireUrl, $orderDetails)
    {
        $this->CI->load->model('order/apiLogs');
        $logid = $this->CI->apiLogs->syncLogs(0, 'safewire', 'get_order_detail_pdf', $orderUrl, array(), array(), $orderDetails['order_id'], 0);
        $ch = curl_init($orderUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Api-Key: ' . env('SAFEWIRE_API_KEY'),
                'Content-Type: application/json',
            )
        );
        $result = curl_exec($ch);

        if (!is_dir('uploads/order_safewire_documents')) {
            mkdir('./uploads/order_safewire_documents', 0777, true);
        }
        file_put_contents('./uploads/order_safewire_documents/' . $orderDetails['file_id'] . '.pdf', $result);

        $binaryOrderData = base64_encode($result);
        $this->sendSafewireDocumentToResware($orderDetails['file_id'] . '.pdf', $orderDetails, $binaryOrderData, 1);
        $this->uploadDocumentOnAwsS3($orderDetails['file_id'] . '.pdf', 'order_safewire_documents');

        $logid = $this->CI->apiLogs->syncLogs(0, 'safewire', 'get_wire_detail_pdf', $wireUrl, array(), array(), $orderDetails['order_id'], 0);
        $ch = curl_init($wireUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Api-Key: ' . env('SAFEWIRE_API_KEY'),
                'Content-Type: application/json',
            )
        );
        $error_msg = curl_error($ch);
        $resultWire = curl_exec($ch);

        if (!is_dir('uploads/wire_safewire_documents')) {
            mkdir('./uploads/wire_safewire_documents', 0777, true);
        }
        file_put_contents('./uploads/wire_safewire_documents/' . $orderDetails['file_id'] . '.pdf', $resultWire);
        $binaryWireOrderData = base64_encode($resultWire);
        $this->sendSafewireDocumentToResware($orderDetails['file_id'] . '.pdf', $orderDetails, $binaryWireOrderData, 0);
        $this->uploadDocumentOnAwsS3($orderDetails['file_id'] . '.pdf', 'wire_safewire_documents');
        $this->CI->apiLogs->syncLogs(0, 'safewire', 'get_wire_detail_pdf', $wireUrl, array(), $resultWire, $orderDetails['order_id'], $logid);
        return json_decode($result, true);
    }

    public function sendSafewireDocumentToResware($document_name, $orderDetails, $binaryData, $orderFlag = 0)
    {
        $this->CI->load->model('order/apiLogs');
        $this->CI->load->library('order/resware');

        if ($orderFlag == 1) {
            $fileSize = filesize('./uploads/order_safewire_documents/' . $document_name);
        } else {
            $fileSize = filesize('./uploads/wire_safewire_documents/' . $document_name);
        }

        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => 0,
            'order_id' => $orderDetails['order_id'],
            'description' => 'Safewire Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_cpl_doc' => 0,
            'is_safewire_doc' => 1,
            'created' => date("Y-m-d H:i:s"),
        );
        $this->CI->db->insert('pct_order_documents', $documentData);
        $documentId = $this->CI->db->insert_id();

        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1037,
            ),
            'Description' => 'Safewire Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $userData = array(
            'admin_api' => 1,
        );

        $logid = $this->CI->apiLogs->syncLogs(0, 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->CI->resware->make_request('POST', $endPoint, $document_api_data, $userData);
        $this->CI->apiLogs->syncLogs(0, 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);

        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'send_safewire_to_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => 'success',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->CI->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */

        $data = array();
        $data['updated'] = date("Y-m-d H:i:s");
        $data['api_document_id'] = $res->Document->DocumentID;
        $this->CI->db->update('pct_order_documents', $data, array('id' => $documentId));
    }

    public function array_recursive_search_key_map($needle, $haystack)
    {
        foreach ($haystack as $first_level_key => $value) {
            if ($needle === $value) {
                return array($first_level_key);
            } elseif (is_array($value)) {
                $callback = $this->array_recursive_search_key_map($needle, $value);
                if ($callback) {
                    return array_merge(array($first_level_key), $callback);
                }
            }
        }
        return false;
    }

    public function uploadDocumentOnAwsS3($fileName, $folder = '', $csv = 0)
    {
        $bucket = env('AWS_BUCKET');
        if (!empty($folder)) {
            $keyname = $folder . "/" . basename($fileName);
            $filepath = "uploads/" . $folder . "/" . $fileName;
        } else {
            if ($csv == 1) {
                $keyname = "csv/" . basename($fileName);
            } else {
                $keyname = basename($fileName);
            }
            $filepath = "uploads/" . $fileName;
        }

        try {
            $s3Client = new Aws\S3\S3Client([
                'region' => env('AWS_REGION'),
                'version' => '2006-03-01',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);

            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $keyname,
                'SourceFile' => $filepath,
            ]);
        } catch (Aws\Exception\AwsException $e) {
            //return $e->getMessage() . "\n";
            return false;
        }
        if (!empty($result['ObjectURL'])) {
            chmod($filepath, 0644);
            gc_collect_cycles();
            unlink($filepath);
            return true;
        } else {
            return false;
        }
    }

    public function fileExistOrNotOnS3($key)
    {
        try {
            $s3Client = new Aws\S3\S3Client([
                'region' => env('AWS_REGION'),
                'version' => '2006-03-01',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);
            $result = $s3Client->doesObjectExist(env('AWS_BUCKET'), $key);
        } catch (Aws\Exception\AwsException $e) {
            return false;
        }
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function getOrdersForUser($user_id, $year = 0, $month = 0)
    {
        if ($year == 0) {
            $year = date('Y');
        }
        if ($month == 0) {
            $month = date('m');
        }

        $this->CI->db->select(' premium, escrow_amount,underwriter,prod_type,transaction_details.sales_amount,transaction_details.loan_amount')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('MONTH(order_details.sent_to_accounting_date)', $month);
        $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', $year);
        $this->CI->db->where('transaction_details.sales_representative', $user_id);
        $this->CI->db->where('order_details.underwriter != ', null);

        $query = $this->CI->db->get();
        return $query->result_array();

    }
    public function getOpenOrdersCountForRefiProducts($month, $userId, $year = 0, $escrow_flag = 0, $dashboard_flag = 0)
    {
        $this->CI->db->select('count(*) as refi_count, sum(premium) as total_premium_for_refi_open_orders, sum(escrow_amount) as total_escrow_amount_for_refi_open_orders')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('order_details.prod_type', 'loan');

        if ($dashboard_flag == 1) {
            $startDate = date('Y-m-01 00:00:00', strtotime('-3 months', strtotime(date('Y-m-d'))));
            $endDate = date('Y-m-d 23:59:59');
            $this->CI->db->where('order_details.created_at BETWEEN "' . $startDate . '" and "' . $endDate . '"');

        } else {
            $this->CI->db->where('MONTH(order_details.created_at)', $month);

            if ($year == 0) {
                $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
            } else {
                $this->CI->db->where('YEAR(order_details.created_at)', $year);
            }

        }

        if (is_array($userId)) {
            if (!empty($userId)) {
                $this->CI->db->where_in('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        } else {
            if ($userId != 'all') {
                $this->CI->db->where('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        }

        if ($escrow_flag == 1) {
            $this->CI->db->where('order_details.escrow_amount > 0');
        }

        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getOpenOrdersCountForSaleProducts($month, $userId, $year = 0, $escrow_flag = 0, $dashboard_flag = 0)
    {
        $this->CI->db->select('count(*) as sale_count, sum(premium) as total_premium_for_sale_open_orders, sum(escrow_amount) as total_escrow_amount_for_sale_open_orders')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('order_details.prod_type', 'sale');

        if ($dashboard_flag == 1) {
            $startDate = date('Y-m-01 00:00:00', strtotime('-3 months', strtotime(date('Y-m-d'))));
            $endDate = date('Y-m-d 23:59:59');
            $this->CI->db->where('order_details.created_at BETWEEN "' . $startDate . '" and "' . $endDate . '"');

        } else {

            $this->CI->db->where('MONTH(order_details.created_at)', $month);
            if ($year == 0) {
                $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
            } else {
                $this->CI->db->where('YEAR(order_details.created_at)', $year);
            }
        }

        if (is_array($userId)) {
            if (!empty($userId)) {
                $this->CI->db->where_in('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        } else {
            if ($userId != 'all') {
                $this->CI->db->where('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        }

        if ($escrow_flag == 1) {
            $this->CI->db->where('order_details.escrow_amount > 0');
        }

        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getClosedOrdersCountForRefiProducts($month, $userId, $year = 0, $escrow_flag = 0, $dashboard_flag = 0)
    {
        // $this->CI->db->select('count(*) as refi_count, sum(premium) as total_premium_for_refi_close_orders, sum(escrow_amount) as total_escrow_amount_for_refi_close_orders, '.$fieds_sum_str)
        $this->CI->db->select('count(*) as refi_count, sum(premium) as total_premium_for_refi_close_orders, sum(escrow_amount) as total_escrow_amount_for_refi_close_orders')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('order_details.prod_type', 'loan');
        if ($dashboard_flag == 1) {
            $startDate = date('Y-m-01 00:00:00', strtotime('-3 months', strtotime(date('Y-m-d'))));
            $endDate = date('Y-m-d 23:59:59');
            $this->CI->db->where('order_details.sent_to_accounting_date BETWEEN "' . $startDate . '" and "' . $endDate . '"');

        } else {
            $this->CI->db->where('MONTH(order_details.sent_to_accounting_date)', $month);
            if ($year == 0) {
                $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', date('Y'));
            } else {
                $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', $year);
            }

        }

        if (is_array($userId)) {
            if (!empty($userId)) {
                $this->CI->db->where_in('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        } else {
            if ($userId != 'all') {
                $this->CI->db->where('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        }

        if ($escrow_flag == 1) {
            $this->CI->db->where('order_details.escrow_amount > 0');
        }

        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getClosedOrdersCountForSaleProducts($month, $userId, $year = 0, $escrow_flag = 0, $dashboard_flag = 0)
    {
        // $this->CI->db->select('count(*) as sale_count, sum(premium) as total_premium_for_sale_close_orders, sum(escrow_amount) as total_escrow_amount_for_sale_close_orders , '.$fieds_sum_str)
        $this->CI->db->select('count(*) as sale_count, sum(premium) as total_premium_for_sale_close_orders, sum(escrow_amount) as total_escrow_amount_for_sale_close_orders')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('order_details.prod_type', 'sale');

        if ($dashboard_flag == 1) {
            $startDate = date('Y-m-01 00:00:00', strtotime('-3 months', strtotime(date('Y-m-d'))));
            $endDate = date('Y-m-d 23:59:59');
            $this->CI->db->where('order_details.sent_to_accounting_date BETWEEN "' . $startDate . '" and "' . $endDate . '"');

        } else {

            $this->CI->db->where('MONTH(order_details.sent_to_accounting_date)', $month);

            if ($year == 0) {
                $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', date('Y'));
            } else {
                $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', $year);
            }
        }

        if (is_array($userId)) {
            if (!empty($userId)) {
                $this->CI->db->where_in('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        } else {
            if ($userId != 'all') {
                $this->CI->db->where('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        }

        if ($escrow_flag == 1) {
            $this->CI->db->where('order_details.escrow_amount > 0');
        }

        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getOpenLPOrdersCountForRefiProducts($startDate, $endDate, $userId, $escrow_flag = 0)
    {
        $this->CI->db->select('count(*) as refi_count, sum(premium) as total_premium_for_refi_open_orders, sum(escrow_amount) as total_escrow_amount_for_refi_open_orders')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('order_details.prod_type', 'loan');
        $this->CI->db->where('order_details.resware_status', 'open');
        // $this->CI->db->where('MONTH(order_details.created_at)', $month);
        $this->CI->db->where('order_details.lp_file_number is not null');
        $this->CI->db->where('order_details.created_at BETWEEN "' . $startDate . '" and "' . $endDate . '"');
        // if ($year == 0) {
        //     $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
        // } else {
        //     $this->CI->db->where('YEAR(order_details.created_at)', $year);
        // }

        if (is_array($userId)) {
            if (!empty($userId)) {
                $this->CI->db->where_in('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        } else {
            if ($userId != 'all') {
                $this->CI->db->where('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        }

        // if ($escrow_flag == 1) {
        //     $this->CI->db->where('order_details.escrow_amount > 0');
        // }

        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getOpenLPOrdersCountForSaleProducts($startDate, $endDate, $userId, $escrow_flag = 0)
    {
        $this->CI->db->select('count(*) as sale_count, sum(premium) as total_premium_for_sale_open_orders, sum(escrow_amount) as total_escrow_amount_for_sale_open_orders')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('order_details.prod_type', 'sale');
        $this->CI->db->where('order_details.resware_status', 'open');
        // $this->CI->db->where('MONTH(order_details.created_at)', $month);
        $this->CI->db->where('order_details.lp_file_number is not null');
        $this->CI->db->where('order_details.created_at BETWEEN "' . $startDate . '" and "' . $endDate . '"');

        // if ($year == 0) {
        //     $this->CI->db->where('YEAR(order_details.created_at)', date('Y'));
        // } else {
        //     $this->CI->db->where('YEAR(order_details.created_at)', $year);
        // }

        if (is_array($userId)) {
            if (!empty($userId)) {
                $this->CI->db->where_in('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        } else {
            if ($userId != 'all') {
                $this->CI->db->where('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        }

        // if ($escrow_flag == 1) {
        //     $this->CI->db->where('order_details.escrow_amount > 0');
        // }

        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getClosedLPOrdersCountForRefiProducts($startDate, $endDate, $userId, $escrow_flag = 0)
    {

        // $this->CI->db->select('count(*) as refi_count, sum(premium) as total_premium_for_refi_close_orders, sum(escrow_amount) as total_escrow_amount_for_refi_close_orders, '.$fieds_sum_str)
        $this->CI->db->select('count(*) as refi_count, sum(premium) as total_premium_for_refi_close_orders, sum(escrow_amount) as total_escrow_amount_for_refi_close_orders')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('order_details.prod_type', 'loan');
        $this->CI->db->where('order_details.resware_status', 'closed');
        // $this->CI->db->where('MONTH(order_details.sent_to_accounting_date)', $month);
        $this->CI->db->where('order_details.lp_file_number is not null');
        $this->CI->db->where('order_details.created_at BETWEEN "' . $startDate . '" and "' . $endDate . '"');

        // if ($year == 0) {
        //     $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', date('Y'));
        // } else {
        //     $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', $year);
        // }

        if (is_array($userId)) {
            if (!empty($userId)) {
                $this->CI->db->where_in('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        } else {
            if ($userId != 'all') {
                $this->CI->db->where('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        }

        // if ($escrow_flag == 1) {
        //     $this->CI->db->where('order_details.escrow_amount > 0');
        // }

        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getClosedLPOrdersCountForSaleProducts($startDate, $endDate, $userId, $escrow_flag = 0)
    {
        // $this->CI->db->select('count(*) as sale_count, sum(premium) as total_premium_for_sale_close_orders, sum(escrow_amount) as total_escrow_amount_for_sale_close_orders , '.$fieds_sum_str)
        $this->CI->db->select('count(*) as sale_count, sum(premium) as total_premium_for_sale_close_orders, sum(escrow_amount) as total_escrow_amount_for_sale_close_orders')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('order_details.prod_type', 'sale');
        $this->CI->db->where('order_details.resware_status', 'closed');
        // $this->CI->db->where('MONTH(order_details.sent_to_accounting_date)', $month);
        $this->CI->db->where('order_details.lp_file_number is not null');
        $this->CI->db->where('order_details.created_at BETWEEN "' . $startDate . '" and "' . $endDate . '"');

        // if ($year == 0) {

        if (is_array($userId)) {
            if (!empty($userId)) {
                $this->CI->db->where_in('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        } else {
            if ($userId != 'all') {
                $this->CI->db->where('transaction_details.sales_representative', $userId);
            } else {
                $this->CI->db->where('transaction_details.sales_representative is not null');
            }
        }

        // if ($escrow_flag == 1) {
        //     $this->CI->db->where('order_details.escrow_amount > 0');
        // }

        $query = $this->CI->db->get();
        $result = $query->row_array();
        return $result;
    }

    public function getSalesRep($params = array())
    {
        $table = 'customer_basic_details';
        $this->CI->db->select('*');
        $this->CI->db->from($table);

        if (array_key_exists("where", $params)) {
            foreach ($params['where'] as $key => $val) {
                $this->CI->db->where($key, $val);
            }
        }

        if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
            $result = $this->CI->db->count_all_results();
        } else {
            if (array_key_exists("id", $params)) {
                $this->CI->db->where('id', $params['id']);
                $query = $this->CI->db->get();
                $result = $query->row_array();
            } else {
                $this->CI->db->order_by('id', 'asc');
                if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->CI->db->limit($params['limit'], $params['start']);
                } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->CI->db->limit($params['limit']);
                }
                $query = $this->CI->db->get();
                $result = ($query->num_rows() > 0) ? $query->result_array() : false;
            }
        }
        return $result;
    }

    public function countWokingsDaysLeftOfMonth()
    {
        $count = 0;
        $counter = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        while (date("n", $counter) == date('m')) {
            if (in_array(date("w", $counter), array(0, 6)) == false) {
                $count++;
            }
            $counter = strtotime("+1 day", $counter);
        }
        $this->CI->db->select('*');
        $this->CI->db->from('pct_holidays');
        $this->CI->db->where('holiday_date >', date('Y-m-d'));
        $this->CI->db->where('holiday_date <=', date("Y-m-t", strtotime(date('Y-m-d'))));
        $query = $this->CI->db->get();
        $result = $query->result_array();
        foreach ($result as $res) {
            $weekendFlag = (date('N', strtotime($res['holiday_date'])) >= 6);
            if ($weekendFlag != 1) {
                $count--;
            }
        }
        return $count;
    }

    public function countWorkedDaysOfMonth()
    {
        $count = 0;
        $counter = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        while (date("n", $counter) == date('m')) {
            if (in_array(date("w", $counter), array(0, 6)) == false) {
                $count++;
            }
            $counter = strtotime("-1 day", $counter);
        }
        $this->CI->db->select('*');
        $this->CI->db->from('pct_holidays');
        $this->CI->db->where('holiday_date >=', date('Y-m-01'));
        $this->CI->db->where('holiday_date <', date('Y-m-d'));
        $query = $this->CI->db->get();
        $result = $query->result_array();
        foreach ($result as $res) {
            $weekendFlag = (date('N', strtotime($res['holiday_date'])) >= 6);
            if ($weekendFlag != 1) {
                $count--;
            }
        }
        return $count;
    }

    public function countWorkedDaysOfFourMonth()
    {
        $startDate = date('Y-m-01', strtotime('-3 months', strtotime(date('Y-m-d'))));
        $month = date('m', strtotime($startDate));
        $year = date('Y', strtotime($startDate));
        $endDate = date('Y-m-d');
        $count = 0;
        $endCounter = mktime(0, 0, 0, $month, 1, $year);
        $counter = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        while ($counter >= $endCounter) {
            if (in_array(date("w", $counter), array(0, 6)) == false) {
                $count++;
            }
            $counter = strtotime("-1 day", $counter);
        }
        $this->CI->db->select('*');
        $this->CI->db->from('pct_holidays');
        $this->CI->db->where('holiday_date >=', $startDate);
        $this->CI->db->where('holiday_date <', $endDate);
        $query = $this->CI->db->get();
        $result = $query->result_array();
        foreach ($result as $res) {
            $weekendFlag = (date('N', strtotime($res['holiday_date'])) >= 6);
            if ($weekendFlag != 1) {
                $count--;
            }
        }
        // print_r($count);die;
        return $count;
    }

    public function get_order_notes($orderId, $user_id = 0)
    {
        $this->CI->db->select('pct_order_notes.*, pct_escrow_tasks.name')
            ->from('pct_order_notes')
            ->join('pct_escrow_tasks', 'pct_order_notes.task_id = pct_escrow_tasks.id', 'left');
        $this->CI->db->where('order_id', $orderId);
        if (!empty($user_id)) {
            $this->CI->db->where('user_id', $user_id);
        }
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function uploadCPLDocumentToResware($document_name, $orderDetails, $binaryData)
    {
        $this->CI->load->model('order/document');
        $this->CI->load->library('order/resware');
        $this->CI->load->model('order/apiLogs');
        $userdata = $this->CI->session->userdata('user');
        if (empty($userdata)) {
            $userdata['id'] = 0;
        }
        $fileSize = filesize('./uploads/documents/' . $document_name);
        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1051,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => $orderDetails['order_id'],
            'description' => 'CPL Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_cpl_doc' => 1,
        );
        $documentId = $this->CI->document->insert($documentData);
        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1051,
            ),
            'Description' => 'CPL Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $user_data = array();
        if (!empty($userdata['id'])) {
            if ($userdata['is_title_officer'] == 1 || $userdata['is_master'] == 1) {
                $user_data['admin_api'] = 1;
            } else {
                $user_data = array();
            }
        } else {
            $user_data['admin_api'] = 1;
        }

        $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->CI->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->CI->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);

        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'upload_cpl_document_to_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => 'success',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->CI->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */

        $this->CI->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));

        /*$from_name = 'Pacific Coast Title Company';
    $from_mail = env('FROM_EMAIL');
    $order_message_body = 'Please check attachment for CPL document.';
    $message = $order_message_body;
    $subject = 'CPL Document';
    $to = $orderDetails['lender_email'];
    $cc = array();
    if (!empty($orderDetails['sales_representative'])) {
    $this->CI->db->select('*')
    ->from('pct_order_sales_rep');
    $this->CI->db->where('id', $orderDetails['sales_representative']);
    $query = $this->CI->db->get();
    $salesResult = $query->row_array();
    if (!empty($salesResult)) {
    $cc = array($salesResult['email_address']);
    }
    }
    $bcc = array();
    $file = array(base_url().'uploads/documents/'.$document_name);
    $this->CI->load->helper('sendemail');
    $mail_result = send_email($from_mail,$from_name, $to, $subject, $message,$file,$cc,$bcc);*/
    }

    public function uploadProposedDocumentToResware($document_name, $orderDetails, $binaryData)
    {
        $this->CI->load->model('order/document');
        $this->CI->load->library('order/resware');
        $this->CI->load->model('order/apiLogs');
        $userdata = $this->CI->session->userdata('user');
        if (empty($userdata)) {
            $userdata['id'] = 0;
        }
        $fileSize = filesize('./uploads/proposed-insured/' . $document_name);
        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => $orderDetails['order_id'],
            'description' => 'Proposed Insured Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_proposed_insured_doc' => 1,
        );
        $documentId = $this->CI->document->insert($documentData);
        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1037,
            ),
            'Description' => 'Proposed Insured Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $user_data = array();
        if (!empty($userdata['id'])) {
            if ($userdata['is_title_officer'] == 1 || $userdata['is_master'] == 1) {
                $user_data['admin_api'] = 1;
            } else {
                $user_data = array();
            }
        } else {
            $user_data['admin_api'] = 1;
        }
        $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->CI->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->CI->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);

        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'upload_proposed_document_to_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => 'success',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->CI->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */

        $this->CI->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));
    }

    public function uploadPrelimDocxDocToResware($document_name, $order_id, $binaryData, $file_id)
    {
        $this->CI->load->model('order/document');
        $this->CI->load->library('order/resware');
        $this->CI->load->model('order/apiLogs');
        $userdata = $this->CI->session->userdata('user');
        if (empty($userdata)) {
            $userdata['id'] = 0;
        }
        $fileSize = filesize('./uploads/documents/' . $document_name);
        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1032,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => $order_id,
            'description' => 'Prelim Word Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_proposed_insured_doc' => 1,
        );
        $documentId = $this->CI->document->insert($documentData);
        $endPoint = 'files/' . $file_id . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1032,
            ),
            'Description' => 'Prelim Word Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $user_data = array();
        if (!empty($userdata['id'])) {
            if ($userdata['is_title_officer'] == 1 || $userdata['is_master'] == 1) {
                $user_data['admin_api'] = 1;
            } else {
                $user_data = array();
            }
        } else {
            $user_data['admin_api'] = 1;
        }
        $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $order_id, 0);
        $result = $this->CI->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->CI->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $order_id, $logid);
        $res = json_decode($result);

        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'upload_prelimdocx_document_to_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => 'success',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->CI->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */

        $this->CI->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));
    }

    public function get_sales_users($sales_rep_users = array())
    {
        $this->CI->db->select('*');
        $this->CI->db->from('customer_basic_details');
        $this->CI->db->where('is_sales_rep', 1);
        $this->CI->db->where('status', 1);
        if (!empty($sales_rep_users)) {
            $this->CI->db->where_in('id', $sales_rep_users);
        }
        $this->CI->db->order_by('first_name', 'asc');
        $query = $this->CI->db->get();
        return $query->result_array();
    }

    public function getOpenOrdersCountForLastMonthOfPreviousYear($userId)
    {
        $previousYear = (string) (date('Y') - 1);
        $this->CI->db->select('count(*) as total_count')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('MONTH(order_details.created_at)', '12');
        $this->CI->db->where('YEAR(order_details.created_at)', $previousYear);
        if ($userId != 'all') {
            $this->CI->db->where('transaction_details.sales_representative', $userId);
        } else {
            $this->CI->db->where('transaction_details.sales_representative is not null');
        }
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getCountBasedOnCurrentDayForPreviousMonthForPreviousYear($userId)
    {
        $firstDate = date("Y", strtotime("-1 year")) . '-12-01';
        $lastDate = date("Y", strtotime("-1 year")) . '-12-%d';
        $this->CI->db->select('count(*) as total_count')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where("(order_details.created_at BETWEEN  DATE_FORMAT(NOW() , '$firstDate') AND DATE_FORMAT(NOW() + INTERVAL 1 DAY , '$lastDate'))");
        if ($userId != 'all') {
            $this->CI->db->where('transaction_details.sales_representative', $userId);
        } else {
            $this->CI->db->where('transaction_details.sales_representative is not null');
        }
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getCountBasedOnCurrentDayForPreviousMonth($userId)
    {
        $firstDate = '%Y-' . date("m", strtotime("-1 month")) . '-01';
        $lastDate = '%Y-' . date("m", strtotime("-1 month")) . '-%d';
        $this->CI->db->select('count(*) as total_count')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where("(order_details.created_at BETWEEN  DATE_FORMAT(NOW() , '$firstDate') AND DATE_FORMAT(NOW() + INTERVAL 1 DAY , '$lastDate'))");
        if ($userId != 'all') {
            $this->CI->db->where('transaction_details.sales_representative', $userId);
        } else {
            $this->CI->db->where('transaction_details.sales_representative is not null');
        }
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getUsersInfo($emailAddresses)
    {
        $this->CI->db->select('*');
        $this->CI->db->where_in('email_address', $emailAddresses);
        $query = $this->CI->db->get('customer_basic_details');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function sendNotification($message, $type, $sent_to_user, $is_sent_admin = 0)
    {
        if ($is_sent_admin == 1) {
            $channel = 'admin-channel-' . $sent_to_user;
            $event = 'admin-event-' . $sent_to_user;
        }

        if (!empty($sent_to_user) && $is_sent_admin == 0) {
            $channel = 'user-channel-' . $sent_to_user;
            $event = 'user-event-' . $sent_to_user;
        }

        $options = array(
            'cluster' => env("PUSHER_CLUSTER"),
            'useTLS' => true,
        );

        $pusher = new Pusher\Pusher(
            env("PUSHER_KEY"),
            env("PUSHER_SECRET"),
            env("PUSHER_APP_ID"),
            $options
        );

        $data['message'] = $message;
        $data['date'] = date("F d, Y");
        $data['type'] = $type;
        //$pusher->trigger($channel, $event, $data);
    }

    public function getEscrowOfficerInfoFromOrder($email)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_partner_company_info');
        $this->CI->db->where('email', $email);
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getEscrowOfficerInfoFromOrderForAssistant($email)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_hr_users');
        $this->CI->db->where('email', $email);
        $this->CI->db->where('department_id', 4);
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get();
        $assistantUserInfo = $query->row_array();

        $this->CI->db->select('*');
        $this->CI->db->from('pct_hr_users');
        $this->CI->db->where('branch_id', $assistantUserInfo['branch_id']);
        $this->CI->db->where('(position_id = 9 or position_id = 22 or position_id = 23)');
        $this->CI->db->where('department_id', 4);
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get();
        $escrowUsers = $query->result_array();
        $escrowEmails = array_column($escrowUsers, 'email');

        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_partner_company_info');
        $this->CI->db->where_in('email', $escrowEmails);
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get();
        return $query->result_array();
    }

    public function getEscrowOrders($params)
    {
        $userdata = $this->CI->session->userdata('user');
        $orders_lists = array();
        $orderBy = '';
        if ($params['orderColumn'] != 0) {
            if ($params['orderColumn'] == 1) {
                $orderBy = 'order_details.file_number';
            } else if ($params['orderColumn'] == 2) {
                $orderBy = 'property_details.full_address';
            } else if ($params['orderColumn'] == 3) {
                $orderBy = 'pct_order_product_types.product_type';
            } else if ($params['orderColumn'] == 4) {
                $orderBy = 'order_details.created_at';
            }
        }

        if ($userdata['is_escrow_officer'] == 1) {
            $escrowOfficerInfo = $this->getEscrowOfficerInfoFromOrder($userdata['email']);
        }

        if ($userdata['is_escrow_assistant'] == 1) {
            $escrowOfficersInfo = $this->getEscrowOfficerInfoFromOrderForAssistant($userdata['email']);
        }

        $this->CI->db->from('order_details')
            ->join('property_details', 'order_details.property_id = property_details.id')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
            ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');
        $this->CI->db->where('(transaction_details.purchase_type = 2 or transaction_details.purchase_type = 3 or transaction_details.purchase_type = 4 or transaction_details.purchase_type = 5 or transaction_details.purchase_type = 36)');

        if ($userdata['is_escrow_officer'] == 1) {
            if (!empty($escrowOfficerInfo)) {
                $this->CI->db->where('order_details.escrow_officer_id', $escrowOfficerInfo['partner_id']);
            } else {
                return array(
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => $orders_lists,
                );
            }
        }

        if ($userdata['is_escrow_assistant'] == 1) {
            if (!empty($escrowOfficersInfo)) {
                $escrowUserIds = array_column($escrowOfficersInfo, 'partner_id');
                $this->CI->db->where_in('order_details.escrow_officer_id', $escrowUserIds);
            } else {
                return array(
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => $orders_lists,
                );
            }
        }

        $total_records = $this->CI->db->count_all_results();
        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

        $select = 'order_details.prelim_summary_id, order_details.created_at as opened_date, order_details.file_number, order_details.file_id,property_details.full_address,order_details.id, order_details.westcor_order_id, order_details.westcor_file_id, order_details.westcor_cpl_id, property_details.escrow_lender_id, order_details.is_regenerate_cpl, order_details.cpl_document_name,
            order_details.created_at, order_details.resware_status, order_details.proposed_insured_document_name, order_details.is_payoff_generated,property_details.primary_owner, pct_order_product_types.product_type,order_details.prod_type';

        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
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
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');

            $this->CI->db->where('(transaction_details.purchase_type = 2 or transaction_details.purchase_type = 3 or transaction_details.purchase_type = 4 or transaction_details.purchase_type = 5 or transaction_details.purchase_type = 36)');

            if ($userdata['is_escrow_officer'] == 1) {
                if (!empty($escrowOfficerInfo)) {
                    $this->CI->db->where('order_details.escrow_officer_id', $escrowOfficerInfo['partner_id']);
                }
            }

            if ($userdata['is_escrow_assistant'] == 1) {
                if (!empty($escrowOfficersInfo)) {
                    $escrowUserIds = array_column($escrowOfficersInfo, 'partner_id');
                    $this->CI->db->where_in('order_details.escrow_officer_id', $escrowUserIds);
                }
            }

            $filter_total_records = $this->CI->db->count_all_results();

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
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');

            $this->CI->db->where('(transaction_details.purchase_type = 2 or transaction_details.purchase_type = 3 or transaction_details.purchase_type = 4 or transaction_details.purchase_type = 5 or transaction_details.purchase_type = 36)');

            if ($userdata['is_escrow_officer'] == 1) {
                if (!empty($escrowOfficerInfo)) {
                    $this->CI->db->where('order_details.escrow_officer_id', $escrowOfficerInfo['partner_id']);
                }
            }

            if ($userdata['is_escrow_assistant'] == 1) {
                if (!empty($escrowOfficersInfo)) {
                    $escrowUserIds = array_column($escrowOfficersInfo, 'partner_id');
                    $this->CI->db->where_in('order_details.escrow_officer_id', $escrowUserIds);
                }
            }
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
            if ($query->num_rows() > 0) {
                $orders_lists = $query->result_array();
            }
        } else {

            $filter_total_records = $total_records;
            $this->CI->db->select($select)
                ->from('order_details')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');

            $this->CI->db->where('(transaction_details.purchase_type = 2 or transaction_details.purchase_type = 3 or transaction_details.purchase_type = 4 or transaction_details.purchase_type = 5 or transaction_details.purchase_type = 36)');
            if ($userdata['is_escrow_officer'] == 1) {
                if (!empty($escrowOfficerInfo)) {
                    $this->CI->db->where('order_details.escrow_officer_id', $escrowOfficerInfo['partner_id']);
                }
            }

            if ($userdata['is_escrow_assistant'] == 1) {
                if (!empty($escrowOfficersInfo)) {
                    $escrowUserIds = array_column($escrowOfficersInfo, 'partner_id');
                    $this->CI->db->where_in('order_details.escrow_officer_id', $escrowUserIds);
                }
            }
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
            if ($query->num_rows() > 0) {
                $orders_lists = $query->result_array();
            }
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $orders_lists,
        );
    }

    public function getBorrowerDocuments($order_id)
    {
        $this->CI->db->select('*')
            ->from('pct_order_documents');

        $this->CI->db->where('order_id', $order_id);
        $this->CI->db->where('is_uploaded_by_borrower', 1);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getDocumentsDetails($document_id)
    {
        $this->CI->db->select('*')
            ->from('pct_order_documents');

        $this->CI->db->where('id', $document_id);
        $this->CI->db->where('is_uploaded_by_borrower', 1);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function importOrder($file_number)
    {
        $this->CI->load->model('order/apiLogs');
        $this->CI->load->library('order/resware');
        $this->CI->load->model('order/home_model');
        $data = json_encode(array('FileNumber' => $file_number));
        $userData = array(
            'admin_api' => 1,
        );
        $logid = $this->CI->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, array(), 0, 0);
        $res = $this->CI->resware->make_request('POST', 'files/search', $data, $userData);
        $this->CI->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, $res, 0, $logid);
        $result = json_decode($res, true);

        if (isset($result['Files']) && !empty($result['Files'])) {
            foreach ($result['Files'] as $res) {
                if (count($result['Files']) > 1 && strtolower($res['Status']['Name']) == 'cancelled') {
                    continue;
                }
                $partner_fname = $res['Partners'][0]['PrimaryEmployee']['FirstName'];
                $partner_lname = $res['Partners'][0]['PrimaryEmployee']['LastName'];
                $partner_name = $res['Partners'][0]['PartnerName'];
                $condition = array(
                    'first_name' => $partner_fname,
                    'last_name' => $partner_lname,
                    'company_name' => $partner_name,
                    'is_pass' => $partner_name,
                );
                $user_details = $this->CI->home_model->get_user_by_name($condition);
                $customerId = 0;

                if (isset($user_details) && !empty($user_details)) {
                    $customerId = $user_details['id'];
                }

                $FullProperty = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'] . ", " . $res['Properties'][0]['City'] . ", " . $res['Properties'][0]['State'] . ", " . $res['Properties'][0]['Zip'];
                $address = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'];
                $locale = $res['Properties'][0]['City'];

                if (($locale)) {
                    if (!empty($res['Properties'][0]['State'])) {
                        $locale .= ', ' . $res['Properties'][0]['State'];
                    } else {
                        $locale .= ', CA';
                    }
                }

                $property_details = $this->getSearchResult($address, $locale);
                $property_type = isset($property_details['property_type']) && !empty($property_details['property_type']) ? $property_details['property_type'] : '';
                $LegalDescription = isset($property_details['legaldescription']) && !empty($property_details['legaldescription']) ? $property_details['legaldescription'] : '';
                $apn = isset($property_details['apn']) && !empty($property_details['apn']) ? $property_details['apn'] : '';
                $propertyData = array(
                    'customer_id' => $customerId,
                    'buyer_agent_id' => 0,
                    'listing_agent_id' => 0,
                    'escrow_lender_id' => 0,
                    'parcel_id' => $res['Properties'][0]['ParcelID'],
                    'address' => removeMultipleSpace($address),
                    'city' => $res['Properties'][0]['City'],
                    'state' => $res['Properties'][0]['State'],
                    'zip' => $res['Properties'][0]['Zip'],
                    'property_type' => $property_type,
                    'full_address' => removeMultipleSpace($FullProperty),
                    'apn' => $apn,
                    'county' => $res['Properties'][0]['County'],
                    'legal_description' => $LegalDescription,
                    'status' => 1,
                );

                $transactionData = array(
                    'customer_id' => $customerId,
                    'sales_amount' => !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
                    'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
                    'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
                    'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
                    'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
                    'sales_representative' => $salesRepId,
                    'title_officer' => $titleOfficerId,
                    'status' => 1,
                );

                $primary_owner = ($res['Buyers'][0]['Primary']['First'] && $res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';
                $primary_owner .= ($res['Buyers'][0]['Primary']['Middle'] && $res['Buyers'][0]['Primary']['Middle']) ? " " . $res['Buyers'][0]['Primary']['Middle'] : '';
                $primary_owner .= ($res['Buyers'][0]['Primary']['Last'] && $res['Buyers'][0]['Primary']['Last']) ? " " . $res['Buyers'][0]['Primary']['Last'] : '';
                $secondary_owner = ($res['Buyers'][0]['Secondary']['First'] && $res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
                $secondary_owner .= ($res['Buyers'][0]['Secondary']['Middle'] && $res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
                $secondary_owner .= ($res['Buyers'][0]['Secondary']['Last'] && $res['Buyers'][0]['Secondary']['Last']) ? " " . $res['Buyers'][0]['Secondary']['Last'] : '';
                $ProductTypeTxt = $res['TransactionProductType']['ProductType'];

                $transactionData['borrower'] = $primary_owner;
                $transactionData['secondary_borrower'] = $secondary_owner;
                $propertyData['primary_owner'] = isset($property_details['primary_owner']) && !empty($property_details['primary_owner']) ? $property_details['primary_owner'] : '';
                $propertyData['secondary_owner'] = isset($property_details['secondary_owner']) && !empty($property_details['secondary_owner']) ? $property_details['secondary_owner'] : '';

                $propertyId = $this->CI->home_model->insert($propertyData, 'property_details');
                $transactionId = $this->CI->home_model->insert($transactionData, 'transaction_details');
                $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['OpenedDate']))) / 1000);
                $created_date = date('Y-m-d H:i:s', $time);
                $randomString = $this->randomPassword();
                $randomString = md5($randomString);

                $completed_date = null;
                if (empty($closedDate)) {
                    if (!empty($res['Dates']['FileCompletedDate'])) {
                        $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['FileCompletedDate']))) / 1000);
                        $completed_date = date('Y-m-d H:i:s', $time);
                    }
                }

                $orderData = array(
                    'customer_id' => $customerId,
                    'file_id' => $res['FileID'],
                    'file_number' => $res['FileNumber'],
                    'property_id' => $propertyId,
                    'transaction_id' => $transactionId,
                    'created_at' => $created_date,
                    'status' => 1,
                    'is_imported' => 1,
                    'is_payoff_order' => 1,
                    'random_number' => $randomString,
                    'resware_closed_status_date' => $completed_date,
                    'resware_status' => strtolower($res['Status']['Name']),
                    'sent_to_accounting_date' => $completed_date,
                );
                $this->CI->home_model->insert($orderData, 'order_details');
                return $this->CI->db->insert_id();
            }
        }
    }

    public function getSearchResult($address, $locale)
    {
        $data = new stdClass();
        $data->Address = $address;
        $data->LastLine = (string) $locale;
        $data->ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
        $data->OwnerName = '';
        $data->key = env('BLACK_KNIGHT_KEY');
        $data->ReportType = '187';

        $request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';

        $requestUrl = $request . http_build_query($data);

        $getsortedresults = isset($_GET['getsortedresults']) ? $_GET['getsortedresults'] : 'false';

        $opts = array(
            'http' => array(
                'header' => "User-Agent:MyAgent/1.0\r\n",
            ),
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($requestUrl, false, $context);
        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);
        $property_info = array();
        if (isset($result['Status']) && !empty($result['Status']) && $result['Status'] == 'OK') {
            $reportUrl = (isset($result['ReportURL']) && !empty($result['ReportURL'])) ? $result['ReportURL'] : '';

            if ($reportUrl) {
                $rdata = new stdClass();
                $rdata->key = env('BLACK_KNIGHT_KEY');
                $requestUrl = $reportUrl . http_build_query($rdata);
                $reportFile = file_get_contents($requestUrl, false, $context);
                $reportData = simplexml_load_string($reportFile);
                $response = json_encode($reportData);
                $details = json_decode($response, true);

                $property_info['property_type'] = isset($details['PropertyProfile']['PropertyCharacteristics']['UseCode']) && !empty($details['PropertyProfile']['PropertyCharacteristics']['UseCode']) ? $details['PropertyProfile']['PropertyCharacteristics']['UseCode'] : '';
                $property_info['legaldescription'] = isset($details['PropertyProfile']['LegalDescriptionInfo']['LegalBriefDescription']) && !empty($details['PropertyProfile']['LegalDescriptionInfo']['LegalBriefDescription']) ? $details['PropertyProfile']['LegalDescriptionInfo']['LegalBriefDescription'] : '';
                $property_info['apn'] = isset($details['PropertyProfile']['APN']) && !empty($details['PropertyProfile']['APN']) ? $details['PropertyProfile']['APN'] : '';

                $property_info['unit_no'] = isset($details['PropertyProfile']['SiteUnit']) && !empty($details['PropertyProfile']['SiteUnit']) ? $details['PropertyProfile']['SiteUnit'] : '';

                $property_info['fips'] = isset($details['SubjectValueInfo']['FIPS']) && !empty($details['SubjectValueInfo']['FIPS']) ? $details['SubjectValueInfo']['FIPS'] : '';

                $primaryOwner = isset($details['PropertyProfile']['PrimaryOwnerName']) && !empty($details['PropertyProfile']['PrimaryOwnerName']) ? $details['PropertyProfile']['PrimaryOwnerName'] : '';
                $secondaryOwner = isset($details['PropertyProfile']['SecondaryOwnerName']) && !empty($details['PropertyProfile']['SecondaryOwnerName']) ? $details['PropertyProfile']['SecondaryOwnerName'] : '';
                $property_info['primary_owner'] = $primaryOwner;
                $property_info['secondary_owner'] = $secondaryOwner;
            }
        }

        return $property_info;
    }

    public function sendDailyProductionReport($adminFlag = 0)
    {
        $this->CI->load->model('order/apiLogs');
        $this->CI->db->select('*');
        $this->CI->db->from('customer_basic_details');
        $this->CI->db->where('is_sales_rep', 1);
        $this->CI->db->where('is_sales_rep_manager', 1);
        $this->CI->db->where('status', 1);
        $this->CI->db->order_by('first_name', 'asc');
        $query = $this->CI->db->get();
        $salesMangers = $query->result_array();

        if (!empty($salesMangers)) {
            $data['max_resales_open_orders'] = 0;
            $data['max_resales_open_orders_sales_name'] = '';
            $data['max_resales_close_orders'] = 0;
            $data['max_resales_close_orders_sales_name'] = '';
            $data['max_refi_open_orders'] = 0;
            $data['max_refi_open_orders_sales_name'] = '';
            $data['max_refi_close_orders'] = 0;
            $data['max_refi_close_orders_sales_name'] = '';
            $data['total_sum_premium'] = 0;

            foreach ($salesMangers as $salesManger) {
                $data = array();
                $total_premium = 0;
                $salesUsers = array();
                if (!empty($salesManger['sales_rep_users'])) {
                    $salesRepUsers = explode(',', $salesManger['sales_rep_users']);
                    if (!in_array($salesManger['id'], $salesRepUsers)) {
                        $salesRepUsers[] = $salesManger['id'];
                    }
                    $salesUsers = $this->CI->order->get_sales_users($salesRepUsers);
                } else {
                    $salesUsers = $this->CI->order->get_sales_users();
                }
                $i = 0;
                if (date('d') == '01') {
                    $month = date('m', strtotime(date('Y-m') . " -1 month"));
                } else {
                    $month = date('m');
                }
                if (!empty($salesUsers)) {
                    foreach ($salesUsers as $salesrep) {
                        $data['salesHistory'][$i]['sales_rep'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        $openRefiResult = $this->CI->order->getOpenOrdersCountForRefiProducts($month, $salesrep['id']);
                        $refi_open_count = !empty($openRefiResult['refi_count']) ? $openRefiResult['refi_count'] : 0;
                        $openSaleResult = $this->CI->order->getOpenOrdersCountForSaleProducts($month, $salesrep['id']);
                        $sale_open_count = !empty($openSaleResult['sale_count']) ? $openSaleResult['sale_count'] : 0;
                        $data['salesHistory'][$i]['refi_open_count'] = $refi_open_count;
                        $data['salesHistory'][$i]['sale_open_count'] = $sale_open_count;
                        $data['salesHistory'][$i]['total_open_count'] = $sale_open_count + $refi_open_count;

                        $closeRefiResult = $this->CI->order->getClosedOrdersCountForRefiProducts($month, $salesrep['id']);
                        $refi_close_count = !empty($closeRefiResult['refi_count']) ? $closeRefiResult['refi_count'] : 0;
                        $closeSaleResult = $this->CI->order->getClosedOrdersCountForSaleProducts($month, $salesrep['id']);
                        $sale_close_count = !empty($closeSaleResult['sale_count']) ? $closeSaleResult['sale_count'] : 0;
                        $data['salesHistory'][$i]['refi_close_count'] = $refi_close_count;
                        $data['salesHistory'][$i]['sale_close_count'] = $sale_close_count;
                        $data['salesHistory'][$i]['total_close_count'] = $refi_close_count + $sale_close_count;

                        $openOrderRefiTotalPremium = !empty($openRefiResult['total_premium_for_refi_open_orders']) ? $openRefiResult['total_premium_for_refi_open_orders'] : 0;
                        $closeOrderRefiTotalPremium = !empty($closeRefiResult['total_premium_for_refi_close_orders']) ? $closeRefiResult['total_premium_for_refi_close_orders'] : 0;
                        //$refi_total_premium = $openOrderRefiTotalPremium + $closeOrderRefiTotalPremium;
                        $refi_total_premium = $closeOrderRefiTotalPremium;
                        $openOrderSaleTotalPremium = !empty($openSaleResult['total_premium_for_sale_open_orders']) ? $openSaleResult['total_premium_for_sale_open_orders'] : 0;
                        $closeOrderSaleTotalPremium = !empty($closeSaleResult['total_premium_for_sale_close_orders']) ? $closeSaleResult['total_premium_for_sale_close_orders'] : 0;
                        //$sale_total_premium = $openOrderSaleTotalPremium + $closeOrderSaleTotalPremium;
                        $sale_total_premium = $closeOrderSaleTotalPremium;
                        $total_premium = $sale_total_premium + $refi_total_premium;
                        $data['salesHistory'][$i]['total_premium'] = number_format($total_premium);

                        if ($i == 0) {
                            $data['max_resales_open_orders'] = $sale_open_count;
                            $data['max_resales_open_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                            $data['max_resales_close_orders'] = $sale_close_count;
                            $data['max_resales_close_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                            $data['max_refi_open_orders'] = $refi_open_count;
                            $data['max_refi_open_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                            $data['max_refi_close_orders'] = $refi_close_count;
                            $data['max_refi_close_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        } else {
                            if ($data['max_resales_open_orders'] < $sale_open_count) {
                                $data['max_resales_open_orders'] = $sale_open_count;
                                $data['max_resales_open_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                            }
                            if ($data['max_resales_close_orders'] < $sale_close_count) {
                                $data['max_resales_close_orders'] = $sale_close_count;
                                $data['max_resales_close_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                            }
                            if ($data['max_refi_open_orders'] < $refi_open_count) {
                                $data['max_refi_open_orders'] = $refi_open_count;
                                $data['max_refi_open_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                            }
                            if ($data['max_refi_close_orders'] < $refi_close_count) {
                                $data['max_refi_close_orders'] = $refi_close_count;
                                $data['max_refi_close_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                            }
                        }
                        $data['total_sum_premium'] += $total_premium;
                        $i++;
                    }
                    $data['yesterday_month'] = date('F', strtotime("-1 days"));
                    $data['yesterday_date'] = date('m/d/Y', strtotime("-1 days"));
                    $data['start_date'] = date('m/01/Y', strtotime("-1 days"));

                    $data['total_sum_premium'] = number_format($data['total_sum_premium']);
                    $data['sales_name'] = $salesManger['first_name'] . " " . $salesManger['last_name'];
                    //print_r($data);exit;

                    if ($adminFlag == 1) {
                        $message = $this->CI->load->view('frontend/emails/daily_production.php', $data, true);
                    } else {
                        $message = $this->CI->load->view('emails/daily_production.php', $data, true);
                    }

                    $from_name = 'Pacific Coast Title Company';
                    $from_mail = env('FROM_EMAIL');
                    $subject = 'Daily Production';
                    $to = $salesManger['email_address'];
                    $cc = array('ghernandez@pct.com', 'aleida@pct.com', 'rudy@pct.com', 'haguilar@pct.com');

                    /** Get CC for daily email receiver */
                    $this->CI->db->select('email')->from('pct_daily_email_receiver_list')->where('status', 1);
                    if (strtolower($to) == 'ntorquato@pct.com') {
                        $this->CI->db->where_in('branch', ['both', 'orange']);
                    }

                    if (strtolower($to) == 'teammeza@pct.com') {
                        $this->CI->db->where_in('branch', ['both', 'glendale']);
                    }
                    $query = $this->CI->db->get();
                    $cc = array_column($query->result_array(), 'email');
                    $mailParams = array(
                        'from_mail' => $from_mail,
                        'from_name' => $from_name,
                        'to' => $to,
                        'subject' => $subject,
                        'message' => json_encode($data),
                        'cc' => $cc,
                    );
                    //$to = 'ghernandez@pct.com';
                    //$cc = array();
                    $this->CI->load->helper('sendemail');
                    $logid = $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array(), 0, 0);
                    $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
                    $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array('status' => $escrow_mail_result), 0, $logid);
                }
            }
            return true;
        }
    }

    public function sendLPReports($adminFlag = 0)
    {
        $this->CI->load->model('order/apiLogs');
        $this->CI->db->select('*');
        $this->CI->db->from('customer_basic_details');
        $this->CI->db->where('is_sales_rep', 1);
        $this->CI->db->where('is_sales_rep_manager', 1);
        $this->CI->db->where('status', 1);
        $this->CI->db->order_by('first_name', 'asc');
        $query = $this->CI->db->get();
        $salesMangers = $query->result_array();
        $startDate = date('Y-m-d 00:00:00', strtotime('-15 days', strtotime(date('Y-m-d'))));
        $endDate = date('Y-m-d 23:59:59', strtotime('-1 days', strtotime(date('Y-m-d'))));

        if (!empty($salesMangers)) {
            $data['max_resales_open_orders'] = 0;
            $data['max_resales_open_orders_sales_name'] = '';
            $data['max_resales_close_orders'] = 0;
            $data['max_resales_close_orders_sales_name'] = '';
            $data['max_refi_open_orders'] = 0;
            $data['max_refi_open_orders_sales_name'] = '';
            $data['max_refi_close_orders'] = 0;
            $data['max_refi_close_orders_sales_name'] = '';
            $data['total_sum_premium'] = 0;
            foreach ($salesMangers as $salesManger) {
                $data = array();
                $total_premium = 0;
                $salesUsers = array();
                if (!empty($salesManger['sales_rep_users'])) {
                    $salesRepUsers = explode(',', $salesManger['sales_rep_users']);
                    if (!in_array($salesManger['id'], $salesRepUsers)) {
                        $salesRepUsers[] = $salesManger['id'];
                    }
                    $salesUsers = $this->CI->order->get_sales_users($salesRepUsers);
                } else {
                    $salesUsers = $this->CI->order->get_sales_users();
                }

                $this->CI->db->select('*');
                $this->CI->db->from('order_details');
                $this->CI->db->where('order_details.lp_file_number is not null');
                $this->CI->db->where('order_details.created_at BETWEEN "' . $startDate . '" and "' . $endDate . '"');
                $this->CI->db->where_in('transaction_details.sales_representative', $salesRepUsers);
                $this->CI->db->join('property_details', 'order_details.property_id = property_details.id', 'inner');
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id', 'inner');
                $query = $this->CI->db->get();
                $result = $query->result_array();

                $data['totalReswareCount'] = 0;
                $data['totalApprovedCount'] = 0;
                $data['totalReswareCountPer'] = 0.00;
                $data['totalApprovedCountPer'] = 0.00;
                $data['totalCount'] = $totalCount;
                if (!empty($result)) {
                    $totalCount = count($result);
                    $reswareOrders = array_filter($result, function ($res) {return ($res['file_number'] != 0);});
                    $approvedOrders = array_filter($result, function ($res) {return ($res['lp_report_status'] == 'approved');});
                    $data['totalReswareCount'] = count($reswareOrders);
                    $data['totalApprovedCount'] = count($approvedOrders);
                    $data['totalReswareCountPer'] = number_format((count($reswareOrders) * 100) / $totalCount, 2) . '%';
                    $data['totalApprovedCountPer'] = number_format((count($approvedOrders) * 100) / $totalCount, 2) . '%';
                    $data['totalCount'] = $totalCount;
                }

                $i = 0;
                if (!empty($salesUsers)) {
                    foreach ($salesUsers as $salesrep) {
                        $data['salesHistory'][$i]['sales_rep'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        $resultForSalesRep = $this->CI->order->getLPOrdersForSalesRep($startDate, $endDate, $salesrep['id']);
                        $data['salesHistory'][$i]['salesrep_id'] = $salesrep['id'];

                        if (!empty($resultForSalesRep)) {
                            $totalCount = count($resultForSalesRep);
                            $reswareOrders = array_filter($resultForSalesRep, function ($res) {return ($res['file_number'] != 0);});
                            $approvedOrders = array_filter($resultForSalesRep, function ($res) {return ($res['lp_report_status'] == 'approved');});
                            $data['salesHistory'][$i]['lp_open_count'] = $totalCount;
                            $data['salesHistory'][$i]['lp_approved_count'] = count($approvedOrders);
                            $data['salesHistory'][$i]['lp_converted_rate'] = number_format((count($approvedOrders) * 100) / $totalCount, 2) . '%';
                        } else {
                            $data['salesHistory'][$i]['lp_open_count'] = 0;
                            $data['salesHistory'][$i]['lp_approved_count'] = 0;
                            $data['salesHistory'][$i]['lp_converted_rate'] = '0.00%';
                        }

                        // $openRefiResult = $this->CI->order->getOpenLPOrdersCountForRefiProducts($startDate, $endDate, $salesrep['id']);

                        // $refi_open_count = !empty($openRefiResult['refi_count']) ? $openRefiResult['refi_count'] : 0;
                        // $openSaleResult = $this->CI->order->getOpenLPOrdersCountForSaleProducts($startDate, $endDate, $salesrep['id']);
                        // // print_r($openSaleResult);die;
                        // $sale_open_count = !empty($openSaleResult['sale_count']) ? $openSaleResult['sale_count'] : 0;
                        // $data['salesHistory'][$i]['salesrep_id'] = $salesrep['id'];
                        // $data['salesHistory'][$i]['refi_open_count'] = $refi_open_count;
                        // $data['salesHistory'][$i]['sale_open_count'] = $sale_open_count;
                        // $data['salesHistory'][$i]['total_open_count'] = $sale_open_count + $refi_open_count;

                        // $closeRefiResult = $this->CI->order->getClosedLPOrdersCountForRefiProducts($startDate, $endDate, $salesrep['id']);
                        // $refi_close_count = !empty($closeRefiResult['refi_count']) ? $closeRefiResult['refi_count'] : 0;
                        // $closeSaleResult = $this->CI->order->getClosedLPOrdersCountForSaleProducts($startDate, $endDate, $salesrep['id']);
                        // $sale_close_count = !empty($closeSaleResult['sale_count']) ? $closeSaleResult['sale_count'] : 0;
                        // $data['salesHistory'][$i]['refi_close_count'] = $refi_close_count;
                        // $data['salesHistory'][$i]['sale_close_count'] = $sale_close_count;
                        // $data['salesHistory'][$i]['total_close_count'] = $refi_close_count + $sale_close_count;

                        // $openOrderRefiTotalPremium = !empty($openRefiResult['total_premium_for_refi_open_orders']) ? $openRefiResult['total_premium_for_refi_open_orders'] : 0;
                        // $closeOrderRefiTotalPremium = !empty($closeRefiResult['total_premium_for_refi_close_orders']) ? $closeRefiResult['total_premium_for_refi_close_orders'] : 0;
                        // //$refi_total_premium = $openOrderRefiTotalPremium + $closeOrderRefiTotalPremium;
                        // $refi_total_premium = $closeOrderRefiTotalPremium;
                        // $openOrderSaleTotalPremium = !empty($openSaleResult['total_premium_for_sale_open_orders']) ? $openSaleResult['total_premium_for_sale_open_orders'] : 0;
                        // $closeOrderSaleTotalPremium = !empty($closeSaleResult['total_premium_for_sale_close_orders']) ? $closeSaleResult['total_premium_for_sale_close_orders'] : 0;
                        // //$sale_total_premium = $openOrderSaleTotalPremium + $closeOrderSaleTotalPremium;
                        // $sale_total_premium = $closeOrderSaleTotalPremium;
                        // $total_premium = $sale_total_premium + $refi_total_premium;
                        // $data['salesHistory'][$i]['total_premium'] = number_format($total_premium);

                        // if ($i == 0) {
                        //     $data['max_resales_open_orders'] = $sale_open_count;
                        //     $data['max_resales_open_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        //     $data['max_resales_close_orders'] = $sale_close_count;
                        //     $data['max_resales_close_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        //     $data['max_refi_open_orders'] = $refi_open_count;
                        //     $data['max_refi_open_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        //     $data['max_refi_close_orders'] = $refi_close_count;
                        //     $data['max_refi_close_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        // } else {
                        //     if ($data['max_resales_open_orders'] < $sale_open_count) {
                        //         $data['max_resales_open_orders'] = $sale_open_count;
                        //         $data['max_resales_open_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        //     }
                        //     if ($data['max_resales_close_orders'] < $sale_close_count) {
                        //         $data['max_resales_close_orders'] = $sale_close_count;
                        //         $data['max_resales_close_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        //     }
                        //     if ($data['max_refi_open_orders'] < $refi_open_count) {
                        //         $data['max_refi_open_orders'] = $refi_open_count;
                        //         $data['max_refi_open_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        //     }
                        //     if ($data['max_refi_close_orders'] < $refi_close_count) {
                        //         $data['max_refi_close_orders'] = $refi_close_count;
                        //         $data['max_refi_close_orders_sales_name'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                        //     }
                        // }
                        // $data['total_sum_premium'] += $total_premium;

                        $i++;
                    }
                    $data['start_date'] = date('Y-m-d', strtotime($startDate));
                    $data['end_date'] = date('Y-m-d', strtotime($endDate));

                    $data['total_sum_premium'] = number_format($data['total_sum_premium']);
                    $data['sales_name'] = $salesManger['first_name'] . " " . $salesManger['last_name'];

                    if ($adminFlag == 1) {
                        $message = $this->CI->load->view('frontend/emails/lp_report.php', $data, true);
                    } else {
                        $message = $this->CI->load->view('emails/lp_report.php', $data, true);
                    }

                    $from_name = 'Pacific Coast Title Company';
                    $from_mail = env('FROM_EMAIL');
                    $subject = 'LP Report';
                    $to = $salesManger['email_address'];
                    $cc = array('ghernandez@pct.com', 'aleida@pct.com', 'rudy@pct.com', 'haguilar@pct.com');
                    $to = 'ghernandez@pct.com';
                    //$cc = array('piyush.j@crestinfosystems.com');
                    //$to = 'hitesh.p@crestinfosystems.com';
                    $mailParams = array(
                        'from_mail' => $from_mail,
                        'from_name' => $from_name,
                        'to' => $to,
                        'subject' => $subject,
                        'message' => json_encode($data),
                        'cc' => $cc,
                    );
                    //$cc = array();
                    $this->CI->load->helper('sendemail');
                    $logid = $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array(), 0, 0);
                    $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
                    $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array('status' => $escrow_mail_result), 0, $logid);
                }
            }
            return true;
        }
    }

    public function getDocumetTypes()
    {
        $this->CI->db->select('*')
            ->from('pct_lp_document_types');

        $this->CI->db->where('is_display', 1);
        //$this->CI->db->where('is_notice', 0);
        //$this->CI->db->group_by('doc_type');
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getAllSubCategory()
    {
        $this->CI->db->select('doc_type')
            ->from('pct_lp_document_types');

        $this->CI->db->where('subtype_flag', 1);
        //$this->CI->db->where('is_notice', 0);
        //$this->CI->db->group_by('doc_type');
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getNoticeDocumetTypes()
    {
        $this->CI->db->select('*')
            ->from('pct_lp_document_types');

        $this->CI->db->where('is_display', 1);
        // $this->CI->db->where('is_notice', 1);
        $this->CI->db->group_by('doc_type');
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    /**
     * Generate LP Report for LP Order
     */
    public function createLpReport($fileNumber, $regerateGeoDoc = false, $regenerate = false)
    {
        $document_name = 'pre_listing_report_' . $fileNumber . '.pdf';
        // if ($this->fileExistOrNotOnS3('pre-listing-doc/'.$document_name) && !$regenerate) {
        //     return;
        // }

        $this->CI->load->model('order/titlePointData');
        $this->CI->load->model('order/home_model');
        $this->CI->load->library('order/titlepoint');

        $userdata = $this->CI->session->userdata('user');
        if (empty($userdata)) {
            $userdata = $this->CI->session->userdata('admin');
        }

        $condition = array(
            'where' => array(
                'file_number' => $fileNumber,
            ),
        );
        $titlePointDetails = $this->CI->titlePointData->gettitlePointDetails($condition);
        $file_id = $titlePointDetails[0]['file_id'];
        $orderDetails = $this->get_order_details($file_id);
        // echo "<pre>";
        // print_r($orderDetails);die;
        $postData['file_number'] = $fileNumber;
        $postData['order_id'] = $orderDetails['order_id'];
        $postData['state'] = $orderDetails['property_state'];
        $postData['county'] = $orderDetails['county'];
        $postData['property'] = $orderDetails['address'];
        $postData['apn'] = $orderDetails['apn'];
        $postData['unit_number'] = $orderDetails['unit_number'];

        if (!$regerateGeoDoc) {
            $this->CI->titlepoint->generateGeoDoc($postData);
        }

        $this->checkGrantDoc($fileNumber, $regenerate);
        // die;
        $titlePointDetails = $this->CI->titlePointData->gettitlePointDetails($condition);
        $orderDetails['opened_date'] = convertTimezone($orderDetails['opened_date']);
        /************** Plat map url integration Start ************** */

        $plat_map_url = '';
        if ($this->fileExistOrNotOnS3('plat-map/' . $fileNumber . '.png')) {
            $plat_map_url = env('AWS_PATH') . "plat-map/" . $fileNumber . '.png';

        } else {
            $address = isset($orderDetails['address']) && !empty($orderDetails['address']) ? $orderDetails['address'] : '';
            $locale = isset($orderDetails['property_city']) && !empty($orderDetails['property_city']) ? $orderDetails['property_city'] : '';
            $propertyState = isset($orderDetails['property_state']) && !empty($orderDetails['property_state']) ? $orderDetails['property_state'] : '';
            $PropertyZip = isset($orderDetails['property_zip']) && !empty($orderDetails['property_zip']) ? $orderDetails['property_zip'] : '';
            if (($locale)) {
                if (!empty($propertyState)) {
                    $locale .= ', ' . $propertyState;
                } else {
                    $locale .= ', CA';
                }
            }
            $stdcls = new stdClass();
            $stdcls->Address = $address;
            $stdcls->LastLine = (string) $locale;
            $stdcls->ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
            $stdcls->OwnerName = '';
            $stdcls->key = env('BLACK_KNIGHT_KEY');
            $stdcls->ReportType = '111';
            $request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';

            $requestUrl = $request . http_build_query($stdcls);
            $query_string = parse_url($request, PHP_URL_QUERY);
            parse_str($query_string, $requestParams);
            $getsortedresults = 'false';

            $opts = array(
                'http' => array(
                    'header' => "User-Agent:MyAgent/1.0\r\n",
                ),
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );

            $context = stream_context_create($opts);
            $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'black knight plat map', 'address_search', $requestUrl, $requestParams, array(), $orderDetails['order_id'], 0);

            $file = file_get_contents($requestUrl, false, $context);
            $xmlData = simplexml_load_string($file);
            $response = json_encode($xmlData);
            $result = json_decode($response, true);
            // echo "<pre>";
            $this->CI->apiLogs->syncLogs($userdata['id'], 'black knight', 'address_search', $requestUrl, $requestParams, $result, $orderDetails['order_id'], $logid);
            // $property_info = array();
            if (isset($result['Status']) && !empty($result['Status']) && $result['Status'] == 'OK') {
                $reportUrl = (isset($result['ReportURL']) && !empty($result['ReportURL'])) ? $result['ReportURL'] : '';
                // $reportUrl = "https://api.sitexdata.com/111/A9848F79-B03E-5199-87B5-9D7A01B8A111.asmx/GetXMLWithFilter?reportInfo=dKagb-JSbWexO13idLleU6jTy7YyY-gG4_QVbq5sHOWWHuYyviAIfxaZ3rNiK_YGA8I26ioLlWrZUPvQNXjgra6PdYeNsVlNxhIMu3hnlAnSGmSKaTfcYg2&filter=<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>";
                // print_r($reportUrl);die;

                if ($reportUrl) {
                    $rdata = new stdClass();
                    $rdata->key = env('BLACK_KNIGHT_KEY');
                    $requestUrl = $reportUrl . http_build_query($rdata);
                    $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'black knight plat map - 2 - request', 'address_search', $requestUrl, $requestParams, array(), $orderDetails['order_id'], 0);
                    $reportFile = file_get_contents($requestUrl, false, $context);
                    $reportData = simplexml_load_string($reportFile);
                    $response = json_encode($reportData);
                    $this->CI->apiLogs->syncLogs($userdata['id'], 'black knight plat map - 2 - response', 'address_search', $requestUrl, $requestParams, $response, $orderDetails['order_id'], $logid);
                    $details = json_decode($response, true);
                    if (isset($details['PlatMap']) && !empty($details['PlatMap']['Content'])) {
                        $imagedata = isset($details['PlatMap']['Content']) && !empty($details['PlatMap']['Content']) ? $details['PlatMap']['Content'] : '';
                        if ($imagedata) {
                            if (!is_dir('uploads/plat-map')) {
                                mkdir('./uploads/plat-map', 0777, true);
                            }
                            $path = './uploads/plat-map/' . $fileNumber . '.png';

                            file_put_contents($path, base64_decode($imagedata, true));
                            if (env('AWS_ENABLE_FLAG') == 1) {
                                $plat_map_url = env('AWS_PATH') . "plat-map/" . $fileNumber . '.png';
                            } else {
                                $plat_map_url = base_url() . 'uploads/plat-map/' . $fileNumber . '.png';
                            }
                            $this->uploadDocumentOnAwsS3($fileNumber . '.png', 'plat-map');
                        }
                    }
                }
            }
        }

        /************** Plat map url integration end ************** */

        // $orderDetails = $this->get_order_details($file_id);
        // $_POST['primary_owner'] = $orderDetails['primary_owner'];
        // $_POST['secondary_owner'] = $orderDetails['secondary_owner'];

        // $postData['file_number'] = $fileNumber;
        // $postData['order_id'] = $orderDetails['order_id'];
        // $postData['state'] = $orderDetails['property_state'];
        // $postData['county'] = $orderDetails['county'];
        // $postData['property'] = $orderDetails['address'];
        // if (!$regerateGeoDoc) {
        //     $this->CI->titlepoint->generateGeoDoc($postData);
        // }

        $orderId = $_POST['order_id'];
        $geoFileName = $fileNumber . '.pdf'; //$titlePointDetails[0]['geo_file_message'];
        // if ($this->fileExistOrNotOnS3('pre-listing-doc/'.$geoFileName)) {
        /** Generate Pre listing report document */
        // $fileNumber = "LP-00000013";
        $tax_file_url = '';

        $tax_file_url = env('AWS_PATH') . "tax/" . $fileNumber . '.pdf';
        $taxFileUrl = empty($tax_file_url) ? '#' : $tax_file_url;
        $condition = array(
            'where' => array(
                'file_number' => $fileNumber,
            ),
        );
        // $titlePointDetails = $this->titlePointData->gettitlePointDetails($condition);
        // $file_id = $titlePointDetails[0]['file_id'];

        $titlePointInstrumentDetails = $this->CI->titlePointData->getInstrumentDetails($fileNumber);
        $vestingInstrumentDetails = $this->CI->titlePointData->getSelectedVestingInstrumentDetails($fileNumber);
        // $vestingAllInstrumentDetails = $this->CI->titlePointData->getVestingInstrumentDetails($fileNumber);
        // $temp = array_unique(array_column($vestingAllInstrumentDetails, 'document_type'));
        // $vestingInstrumentDetails = array_intersect_key($vestingAllInstrumentDetails, $temp);
        // $sectionGList = $this->CI->home_model->getSectionWiseLPDocumentList('G');
        // $sectionHList = $this->CI->home_model->getSectionWiseLPDocumentList('H');
        // $sectionIList = $this->CI->home_model->getSectionWiseLPDocumentList('I');
        // $sectionJList = $this->CI->home_model->getSectionWiseLPDocumentList('J');
        // $sectionGList = array_map(function ($value) {
        //     return $value['doc_type'];
        // }, $sectionGList);
        // $sectionHList = array_map(function ($value) {
        //     return $value['doc_type'];
        // }, $sectionHList);
        // $sectionIList = array_map(function ($value) {
        //     return $value['doc_type'];
        // }, $sectionIList);
        // $sectionJList = array_map(function ($value) {
        //     return $value['doc_type'];
        // }, $sectionJList);

        $sectionGRecord = array_filter($titlePointInstrumentDetails, function ($v) {
            // return (in_array($v['document_type'], $sectionGList) || in_array($v['document_sub_type'], $sectionGList));
            return ($v['display_in_section'] == 'G');
        });
        $sectionHRecord = array_filter($titlePointInstrumentDetails, function ($v) {
            // return (in_array($v['document_type'], $sectionHList) || in_array($v['document_sub_type'], $sectionHList));
            return ($v['display_in_section'] == 'H');
        });
        $sectionIRecord = array_filter($titlePointInstrumentDetails, function ($v) {
            // return (in_array($v['document_type'], $sectionIList) || in_array($v['document_sub_type'], $sectionIList));
            return ($v['display_in_section'] == 'I');
        });
        $sectionJRecord = array_filter($titlePointInstrumentDetails, function ($v) {
            // return (in_array($v['document_type'], $sectionJList) || in_array($v['document_sub_type'], $sectionJList));
            return ($v['display_in_section'] == 'J');
        });

        $sectionGRecord = array_map(function ($arr) {
            return $arr + ['section' => 'G'];
        }, $sectionGRecord);
        if (empty($sectionGRecord)) {
            $sectionGRecord = [['message' => 'There is No Foreclosure activity found', 'section' => 'G']];
        }
        // echo "<pre>";
        // print_r($sectionGRecord);
        // die;
        $sectionHRecord = array_map(function ($arr) {
            return $arr + ['section' => 'H'];
        }, $sectionHRecord);
        if (empty($sectionHRecord)) {
            $sectionHRecord = [['message' => 'There is No Foreclosure activity found', 'section' => 'H']];
        }
        $sectionIRecord = array_map(function ($arr) {
            return $arr + ['section' => 'I'];
        }, $sectionIRecord);
        if (empty($sectionIRecord)) {
            $sectionIRecord = [['message' => 'There is No Liens, Notices, and Violations found', 'section' => 'I']];
        }
        $sectionJRecord = array_map(function ($arr) {
            return $arr + ['section' => 'J'];
        }, $sectionJRecord);

        $allSectionRecord = array_merge($sectionGRecord, $sectionHRecord, $sectionIRecord);

        // $titlePointInstrumentDetails = array_chunk($titlePointInstrumentDetails, 25);
        // $orderDetails = $this->get_order_details($file_id);
        // $openDeedTrust = array_filter($titlePointInstrumentDetails, function($v) { return ($v['document_type'] == 'TDD') || ($v['document_type'] == 'TDA'); });
        // $itemsForReview = array_filter($titlePointInstrumentDetails, function($v) { return ($v['is_notice'] == 0) && ($v['is_display'] == 1) && ($v['document_type'] != 'TDD') && ($v['document_type'] != 'TDA'); });
        // $foreclosure = array_filter($titlePointInstrumentDetails, function($v) { return ($v['is_notice'] == 1) && ($v['is_display'] == 1) && ($v['document_type'] != 'TDD') && ($v['document_type'] != 'TDA'); });

        // $instrumentRecordDetails['orderDetails'] = $orderDetails;
        // $instrumentRecordDetails['titlePointDetails'] = $titlePointDetails;
        if (!$regenerate) {
            $titlePointDetails[0]['cs4_instrument_no'] = '';
        }
        $instrumentRecordDetails['orderDetails'] = $orderDetails;
        $instrumentRecordDetails['titlePointDetails'] = $titlePointDetails;
        $instrumentRecordDetails['sectionGRecord'] = array_values($sectionGRecord);
        $instrumentRecordDetails['sectionHRecord'] = array_values($sectionHRecord);
        $instrumentRecordDetails['sectionIRecord'] = array_values($sectionIRecord);
        $instrumentRecordDetails['sectionJRecord'] = array_values($sectionJRecord);
        $instrumentRecordDetails['allSectionRecord'] = array_values($allSectionRecord);
        // $instrumentRecordDetails['itemsForReview'] = array_values($itemsForReview);
        // $instrumentRecordDetails['foreclosure'] = array_values($foreclosure);
        // $instrumentRecordDetails['openDeedTrust'] = array_values($openDeedTrust);
        // $instrumentRecordDetails['titlePointInstrumentDetails'] = $titlePointInstrumentDetails;
        $instrumentRecordDetails['is_plat_map_exist'] = !empty($plat_map_url) ? 1 : 0;
        $instrumentRecordDetails['taxFileUrl'] = $taxFileUrl;
        $instrumentRecordDetails['vestingInstrumentDetails'] = $vestingInstrumentDetails;

        $tax_file_path = FCPATH . 'uploads/tax/' . $fileNumber . '.pdf';
        //if (file_exists($tax_file_path)) {
        $tax_file_url = env('AWS_PATH') . "tax/" . $fileNumber . '.pdf';
        //}

        $taxFileUrl = empty($tax_file_url) ? '#' : $tax_file_url;
        $instrumentRecordDetails['taxFileUrl'] = $taxFileUrl;
        $html = $this->CI->load->view('report/instrument_report', $instrumentRecordDetails, true);

        $this->CI->load->library('snappy_pdf');
        // $this->snappy_pdf->pdf->setOption('page-size', 'Letter');
        $this->CI->snappy_pdf->pdf->setOption('zoom', '1.15');

        if (!is_dir('uploads/pre-listing-doc')) {
            mkdir('./uploads/pre-listing-doc', 0777, true);
        }
        $pdfFilePath = FCPATH . '/uploads/pre-listing-doc/' . $document_name;
        $pdfFilePath = str_replace('\\', '/', $pdfFilePath);
        $this->CI->snappy_pdf->pdf->generateFromHtml($html, $pdfFilePath);
        // die;
        $this->uploadDocumentOnAwsS3($document_name, 'pre-listing-doc');
        $this->insertRecord($document_name, $file_id, $orderDetails);
        /*** Upload Pre listing doc to resware */
        //$this->uploadPreListingDocsToResware($geoFileName, $file_id, $orderDetails);
        // }
    }

    /**
     * Insert pre listing document record in document table
     */
    public function insertRecord($document_name, $fileId, $orderDetails)
    {
        $this->CI->load->model('order/document');
        // $this->load->library('order/resware');
        $userdata = $this->CI->session->userdata('user');
        if (empty($userdata)) {
            $userdata = $this->CI->session->userdata('admin');
        }
        $fileSize = filesize(env('AWS_PATH') . "pre-listing-doc/" . $document_name);
        // $contents = file_get_contents(env('AWS_PATH')."pre-listing-doc/".$document_name);
        // $binaryData   = base64_encode($contents);

        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => !empty($orderDetails['order_id']) ? $orderDetails['order_id'] : 0,
            'description' => 'Pre Listing Report Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_pre_listing_doc' => 0,
            'is_pre_listing_report_doc' => 1,
        );
        $condition = array('is_pre_listing_report_doc' => 1, 'order_id' => $orderDetails['order_id']);
        $this->CI->document->delete($documentData, $condition);
        $this->CI->document->insert($documentData);
    }

    /**
     * Check and generated new grant deed document for latest Instrument number
     */
    public function checkGrantDoc($fileNumber, $regenerate)
    {
        $this->CI->load->model('order/titlePointDocumentRecords');
        $this->CI->load->library('order/titlepoint');
        $titlePointInstrumentDetails = $this->CI->titlePointData->getLatestGrantDeedInstrumentDetails($fileNumber);
        if (!empty($titlePointInstrumentDetails)) {
            $titlePointInstrumentDetails = $titlePointInstrumentDetails[0];

            $recordedDate = $titlePointInstrumentDetails['recorded_date'];
            $grantDeedRecordedDate = $titlePointInstrumentDetails['cs4_recorded_date'];
            $grantDeedInstuNum = $titlePointInstrumentDetails['cs4_instrument_no'];
            $latestInstuNum = $titlePointInstrumentDetails['instrument'];
            $titlePointId = $titlePointInstrumentDetails['title_point_id'];
            $fileId = $titlePointInstrumentDetails['file_id'];
            $fileNumber = $titlePointInstrumentDetails['file_number'];
            $fips = $titlePointInstrumentDetails['fips'];

            if (!empty($grantDeedInstuNum) && strtotime($grantDeedRecordedDate) > strtotime($recordedDate)) {
                $insertData = array(
                    'title_point_id' => $titlePointId,
                    'instrument' => $grantDeedInstuNum,
                    'recorded_date' => date("Y-m-d", strtotime($grantDeedRecordedDate)),
                    'type' => 'REC',
                    'sub_type' => 'ALL',
                    'order_number' => $titlePointInstrumentDetails['order_number'],
                    'document_name' => 'Grant Deed',
                    'document_type' => 'DEG',
                    'document_sub_type' => $titlePointInstrumentDetails['document_sub_type'],
                    'parties' => null,
                    'coupling' => 0,
                    'remarks' => null,
                    'color_coding' => "80FF80",
                    'loan_amount' => $titlePointInstrumentDetails['loan_amount'],
                    'amount' => 0,
                    'is_display' => 1,
                    'is_ves_display' => 1,
                    'is_csinstrument_record' => 1,
                );
                $this->CI->titlePointDocumentRecords->insert($insertData);
            } else {
                if (!empty($latestInstuNum)) {
                    if (isset($recordedDate) && !empty($recordedDate)) {
                        $time = strtotime($recordedDate);
                        $year = date('Y', $time);
                    }

                    $newInstuNum = $year . '-' . $latestInstuNum;
                    $condition = array(
                        'id' => $titlePointId,
                    );
                    $tpData = array(
                        'cs4_instrument_no' => $newInstuNum,
                        'cs4_recorded_date' => $recordedDate,
                    );

                    $orderCondition = array(
                        'where' => array(
                            'file_id' => $fileId,
                        ),
                    );

                    $orderDetails = $this->get_rows($orderCondition);
                    $orderId = $orderDetails['id'];
                    $this->CI->titlepoint->generateGrantDeed($newInstuNum, $recordedDate, $fips, $fileNumber, $orderId);
                    $this->CI->titlePointData->update($tpData, $condition);
                    if ($regenerate == true) {
                        $updateData = array('is_ves_display' => 0);
                        $condition = array('title_point_id' => $titlePointInstrumentDetails['title_point_id']);
                        $this->CI->titlePointDocumentRecords->update($updateData, $condition);

                        $updateData = array('is_ves_display' => 1);
                        $condition = array('instrument' => $latestInstuNum);
                        $this->CI->titlePointDocumentRecords->update($updateData, $condition);
                    }
                }
            }
            // $count = substr_count($grantDeedInstuNum, $latestInstuNum);
            // if(!isset($count) || empty($count)) {
            // if (!empty($latestInstuNum)) {
            // if(isset($recordedDate) && !empty($recordedDate))
            // {
            //     $time = strtotime($recordedDate);
            //     $year = date('Y',$time);
            // }
            // $newInstuNum = $year.'-'.$latestInstuNum;
            // $condition = array(
            //     'id' => $titlePointId
            // );
            // $tpData = array(
            //     'cs4_instrument_no' => $newInstuNum,
            //     'cs4_recorded_date' => $recordedDate,
            // );

            // $orderCondition = array(
            //     'where' => array(
            //         'file_id' => $fileId,
            //     )
            // );

            // $orderDetails = $this->get_rows($orderCondition);
            // $orderId = $orderDetails['id'];
            // $this->CI->titlepoint->generateGrantDeed($newInstuNum,$recordedDate,$fips,$fileNumber,$orderId);
            // $this->CI->titlePointData->update($tpData,$condition);
            // if ($regenerate == true) {
            //     $updateData = array('is_ves_display' => 0);
            //     $condition = array('title_point_id' => $titlePointInstrumentDetails['title_point_id']);
            //     $this->CI->titlePointDocumentRecords->update($updateData,$condition);

            //     $updateData = array('is_ves_display' => 1);
            //     $condition = array('instrument' => $latestInstuNum);
            //     $this->CI->titlePointDocumentRecords->update($updateData,$condition);
            // }

            // }

        }
    }

    /**
     * Curl request integration method
     */
    public function curl_post($end_point, $requestParams)
    {

        foreach ($requestParams as $key => $value) {
            $post_array_string .= $key . '=' . $value . '&';
        }
        $ch = curl_init($end_point);
        // curl_setopt($ch, CURLOPT_USERPWD, "$user:$passcode");
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('OCS-APIRequest: true'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array_string);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $res = curl_exec($ch);
        curl_close($ch);
        $xmlData = simplexml_load_string($res);
        return json_encode($xmlData);
        // $result = json_decode($response,TRUE);
        // return $result;
    }

    public function sendOrderEmail($fileNumber)
    {
        $this->CI->load->model('order/apiLogs');
        $this->CI->load->model('order/home_model');
        $this->CI->load->model('order/agent_model');
        $userdata = $this->CI->session->userdata('user');
        $condition = array(
            'where' => array(
                'file_number' => $fileNumber,
            ),
        );
        $titlePointDetails = $this->CI->titlePointData->gettitlePointDetails($condition);
        $file_id = $titlePointDetails[0]['file_id'];
        $orderDetails = $this->CI->order->get_order_details($file_id);
        $cond = array(
            'id' => $orderDetails['customer_id'],
        );
        $customerDetails = $this->CI->home_model->get_customers($cond);
        $configData = $this->getConfigData();
        $titlePointShutOff = $configData['title_point_shut_off']['is_enable'];
        $timezone = -8;
        $isLpOrder = false;
        $orderNumber = $orderDetails['file_number'];
        if (empty($orderDetails['file_number']) && !empty($orderDetails['lp_file_number'])) {
            $isLpOrder = true;
            $orderNumber = $orderDetails['lp_file_number'];
        }
        // $orderNumber = $orderDetails['file_number'] ? $orderDetails['file_number'] : $orderDetails['lp_file_number'];

        $data = array(
            'orderNumber' => $orderNumber,
            'orderId' => $file_id,
            'OpenName' => $customerDetails['first_name'] . ' ' . $customerDetails['last_name'],
            'Opentelephone' => $customerDetails['telephone_no'],
            'OpenEmail' => $customerDetails['email_address'],
            'CompanyName' => $customerDetails['company_name'],
            'StreetAddress' => $customerDetails['street_address'],
            'City' => $customerDetails['city'],
            'Zipcode' => $customerDetails['zip_code'],
            'openAt' => gmdate("m-d-Y h:i A", strtotime($orderDetails['opened_date']) + 3600 * ($timezone + date("I"))),
            'PropertyAddress' => $orderDetails['address'],
            'FullProperty' => $orderDetails['full_address'],
            'APN' => $orderDetails['apn'],
            'County' => $orderDetails['county'],
            'LegalDescription' => $orderDetails['legal_description'],
            'PrimaryOwner' => $orderDetails['primary_owner'],
            'SecondaryOwner' => $orderDetails['secondary_owner'],
            'SalesRep' => $orderDetails['salerep_first_name'] . ' ' . $orderDetails['salerep_last_name'],
            'TitleOfficer' => $orderDetails['titleofficer_first_name'] . ' ' . $orderDetails['titleofficer_last_name'],
            'ProductType' => $orderDetails['product_type'],
            'SalesAmount' => $orderDetails['sales_amount'],
            'LoanAmount' => $orderDetails['loan_amount'],
            'LoanNumber' => $orderDetails['loan_number'],
            'EscrowNumber' => $orderDetails['escrow_number'],
            'randomString' => $this->CI->order->randomPassword(),
            'titlePointDetails' => $titlePointDetails[0],
            'titlePointShutOff' => $titlePointShutOff,
        );

        $buyerDetails = $listingDetails = $parties_email = array();
        $parties_email = explode(',', $orderDetails['additional_email']);
        if (isset($orderDetails['lender_id']) && !empty($orderDetails['lender_id'])) {
            $parties_email[] = $orderDetails['lender_email'];
            $data['lender_details'] = array(
                'name' => $orderDetails['lender_first_name'],
                'email' => $orderDetails['lender_email'],
                'telephone' => $orderDetails['lender_telephone_no'],
                'company' => $orderDetails['lender_company_name'],
            );
        }

        if (isset($orderDetails['buyer_agent_id']) && !empty($orderDetails['buyer_agent_id'])) {
            $buyerDetails = $this->CI->agent_model->get_agents(array('id' => $orderDetails['buyer_agent_id']));
            if (!empty($buyerDetails)) {
                $parties_email[] = $buyerDetails['email_address'];
                $data['buyers_agent'] = array(
                    'name' => $buyerDetails['name'],
                    'email' => $buyerDetails['email_address'],
                    'telephone' => $buyerDetails['telephone_no'],
                    'company' => $buyerDetails['company'],
                );
            }

        }

        if (isset($orderDetails['listing_agent_id']) && !empty($orderDetails['listing_agent_id'])) {
            $listingDetails = $this->CI->agent_model->get_agents(array('id' => $orderDetails['listing_agent_id']));
            if (!empty($listingDetails)) {
                $parties_email[] = $listingDetails['email_address'];
                $data['listing_agent'] = array(
                    'name' => $listingDetails['name'],
                    'email' => $listingDetails['email_address'],
                    'telephone' => $listingDetails['telephone_no'],
                    'company' => $listingDetails['company'],
                );
            }
        }

        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $order_message_body = $this->CI->load->view('emails/order.php', $data, true);
        $message = $order_message_body;
        $addInSubject = '';
        if (str_contains(strtolower($orderDetails['property_type']), 'vacant land')) {
            $addInSubject = ' - APN: ' . $orderDetails['apn'];
        }
        $subject = $orderNumber . ' - PCT Title Order Placed' . $addInSubject;
        $email_notification = $this->CI->session->userdata('email_notification');
        $this->CI->session->unset_userdata('email_notification');
        if ($orderDetails["salerep_is_mail_notification"] == 1) {
            $parties_email[] = isset($orderDetails["salerep_email_address"]) && !empty($orderDetails["salerep_email_address"]) ? $orderDetails["salerep_email_address"] : '';
        }

        if ((isset($userdata['is_master']) && !empty($userdata['is_master'])) && (empty($email_notification))) {
            $to = env('OPEN_ORDER_ADMIN_EMAIL');
        } else {
            $to = $customerDetails['email_address'];
            $parties_email[] = env('OPEN_ORDER_ADMIN_EMAIL');
        }

        //$parties_email[] = 'hitesh.p@crestinfosystems.com';
        // $parties_email[] = 'rudy@pct.com';
        // $parties_email[] = 'evelasquez@pct.com';
        $parties_email[] = 'openorders@pct.com';
        $file = array();
        $lvfilename = $deedfilename = $taxfilename = '';

        /**
         * Comment From Jerry on 26th July, 2024:
         * When the LP is created we can send out a confirmation email to all parties but it should not include any attachments.
         * */
        if ((empty($titlePointShutOff) || $titlePointShutOff == 0) && !$isLpOrder) {
            $lvfilename = $orderNumber . '.pdf';
            $deedfilename = $orderNumber . '.pdf';
            $taxfilename = $orderNumber . '.pdf';
            $file[] = env('AWS_PATH') . "legal-vesting/" . $lvfilename;
            $file[] = env('AWS_PATH') . "grant-deed/" . $deedfilename;
            $file[] = env('AWS_PATH') . "tax/" . $taxfilename;
        }

        //$parties_email[] = env('ORDER_ADMIN_EMAIL');
        if (isset($orderDetails["title_officer_email"]) && !empty($orderDetails["title_officer_email"])) {
            $parties_email[] = $orderDetails["title_officer_email"];
        }
        $cc = isset($parties_email) && !empty($parties_email) ? $parties_email : array();
        $this->CI->load->helper('sendemail');

        // $cc = array('piyush.j@crestinfosystems.net');$to='hitesh.p@crestinfosystems.com';

        $mailParams = array(
            'from_mail' => $from_mail,
            'from_name' => $from_name,
            'to' => $to,
            'subject' => $subject,
            'message' => json_encode($data),
            'file' => json_encode($file),
            'cc' => json_encode($cc),
        );
        $lvDocStatus = strtolower($titlePointDetails[0]['lv_file_status']);
        $taxDataStatus = strtolower($titlePointDetails[0]['tax_data_status']);
        $taxDocStatus = strtolower($titlePointDetails[0]['tax_file_status']);
        $emailSentFlag = strtolower($titlePointDetails[0]['email_sent_status']);
        $this->CI->apiLogs->syncLogs($userdata['id'], 'email-check_mail_library', 'email-check', '', ['$emailSentFlag' => $emailSentFlag, '$taxDocStatus' => $taxDocStatus, '$taxDataStatus' => $taxDataStatus, '$lvDocStatus' => $lvDocStatus], array(), $orderDetails['order_id'], 0);

        if ($emailSentFlag != 1 &&
            ((($lvDocStatus == 'success' || $lvDocStatus == 'failed' || $lvDocStatus == 'exception') &&
                ($taxDocStatus == 'success' || $taxDocStatus == 'failed' || $taxDocStatus == 'exception')) || $titlePointShutOff == 1)) {
            if (isset($orderDetails['lp_file_number']) && !empty($orderDetails['lp_file_number'])) {

                $parties_email[] = 'rudy@pct.com';
                $parties_email[] = 'evelasquez@pct.com';
                $mailParams['cc'] = json_encode($cc);
                $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_LP_order_mail_library', '', $mailParams, array(), $orderDetails['order_id'], 0);
                try {
                    // $to = 'hitesh.p@crestinfosystems.com';
                    // $cc = ['piyush.j@crestinfosystems.net'];
                    $cc = $parties_email;
                    // $cc[] = 'piyush.j@crestinfosystems.net';
                    $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, $file, $cc, array());

                    /** Notify CS */
                    // array('ghernandez@pct.com', 'aleida@pct.com', 'rudy@pct.com', 'haguilar@pct.com');
                    $to = ['openorders@pct.com', 'cs@pct.com'];
                    if ($taxDataStatus != 'success') {
                        // $subject = $subject . ' But Tax details not found';
                        //send_email($from_mail, $from_name, $to, $subject, $message, $file, array(), array());
                    }
                    /** End Notify CS */
                } catch (Exception $e) {
                }
                $this->CI->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_LP_order_mail_library', '', $mailParams, array('status' => $mail_result), $orderDetails['order_id'], $logid);
            } else {
                $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_resware_order_mail_library', '', $mailParams, array(), $orderDetails['order_id'], 0);
                try {
                    // $to = 'hitesh.p@crestinfosystems.com';
                    // $cc = ['piyush.j@crestinfosystems.net'];
                    // $cc[] = 'piyush.j@crestinfosystems.net';
                    $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, $file, $cc, array());
                    /** Notify CS */
                    $to = ['openorders@pct.com', 'cs@pct.com'];
                    if ($taxDataStatus != 'success') {
                        $subject = $subject . ' But Tax details not found';
                        //send_email($from_mail, $from_name, $to, $subject, $message, $file, array(), array());
                    }
                    /** End Notify CS */
                } catch (Exception $e) {
                }
                $this->CI->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_resware_order_mail_library', '', $mailParams, array('status' => $mail_result), $orderDetails['order_id'], $logid);
            }
            $tpData = array(
                'email_sent_status' => ($mail_result) ? 1 : 0,
            );

            $condition = array(
                'file_number' => $fileNumber,
            );
            $this->CI->titlePointData->update($tpData, $condition);
        }
    }

    public function getConfigData()
    {
        // $this->CI->db->select('is_enable');
        // $this->CI->db->from('pct_configs');
        // $query = $this->CI->db->get();
        // return $query->row();
        $this->CI->db->select('is_enable, slug');
        $this->CI->db->from('pct_configs');
        $this->CI->db->where('slug !=', 'sales_rep_status_flag');
        $query = $this->CI->db->get();
        $data = array();

        foreach ($query->result_array() as $row) {
            $data[$row['slug']] = $row;
        }

        return $data;
    }

    public function getSalesConfigData()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_configs');
        $this->CI->db->where('slug', 'sales_rep_status_flag');
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function logAdminActivity($activity)
    {
        $userdata = $this->CI->session->userdata('admin');
        $data = array(
            'user_id' => $userdata['id'] ?? 1,
            'message' => $activity,
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->CI->db->insert('pct_admin_activity_logs', $data);
    }

    public function sendSummaryMail($sales_rep_id = 0)
    {
        $this->CI->load->library('order/resware');
        $this->CI->load->model('order/apiLogs');
        $month = sprintf('%02d', date('m', strtotime(date('Y-m') . " -1 month")));
        $year = date('Y', strtotime(date('Y-m') . " -1 month"));

        // $userdata = $this->CI->session->userdata('user');
        // if ($userdata['is_title_officer'] == 1 || $userdata['is_sales_rep'] == 1 || $userdata['is_master'] == 1) {
        $user_data['admin_api'] = 1;
        // } else {
        //     $user_data = array();
        // }

        $this->CI->db->select('order_details.file_id,
            order_details.file_number,
            order_details.customer_id,
            order_details.id as order_id,
            order_details.resware_status,
            property_details.full_address,
            customer_basic_details.email_address as sales_email,
            CONCAT_WS(" ", customer_basic_details.first_name, customer_basic_details.last_name) as sales_name,
            CONCAT_WS(" ", user_details.first_name, user_details.last_name) as name,
            user_details.email_address,
            user_details.company_name,
            user_details.is_escrow,
            user_details.partner_id,
            pci.partner_type_id,
            pci.partner_name,
            transaction_details.sales_representative');
        $this->CI->db->from('order_details');
        $this->CI->db->where('MONTH(order_details.sent_to_accounting_date)', $month);
        $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', $year);
        if ($sales_rep_id == 0) {
            $this->CI->db->where('transaction_details.sales_representative != ""');
        } else {
            $this->CI->db->where('transaction_details.sales_representative', $sales_rep_id);
        }
        $this->CI->db->where('customer_basic_details.email_address != ""');
        $this->CI->db->join('property_details', 'order_details.property_id = property_details.id', 'inner');
        $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id', 'inner');
        $this->CI->db->join('customer_basic_details', 'customer_basic_details.id = transaction_details.sales_representative', 'inner');
        $this->CI->db->join('customer_basic_details as user_details', 'user_details.id = order_details.customer_id', 'inner');
        $this->CI->db->join('pct_order_partner_company_info as pci', 'pci.partner_id = user_details.partner_id', 'left');
        $this->CI->db->order_by('transaction_details.sales_representative asc, order_details.customer_id asc');
        $query = $this->CI->db->get();
        $result = $query->result_array();
        // echo "<pre>";
        // print_r($result);
        // die;
        if (!empty($result)) {
            $checkFlag = 0;
            $data = array();
            $i = 0;
            $userName = '';
            $companyName = '';
            foreach ($result as $res) {
                if ($checkFlag == 0) {
                    $sales_rep_user_id = $res['sales_representative'];
                    $sales_rep_email = $res['sales_email'];
                    $order_user_id = $res['customer_id'];
                    $userName = $res['name'];
                    $companyName = $res['company_name'];
                    $checkFlag = 1;
                    $j = 0;
                }

                // $month = sprintf('%02d',date('m') - 2);
                $month = sprintf('%02d', date('m', strtotime(date('Y-m') . " -2 month")));
                $year = date('Y', strtotime(date('Y-m') . " -2 month"));

                $this->CI->db->select('COUNT(user_details.id) as order_count,user_details.`id`, user_details.company_name, CONCAT_WS(" ", user_details.first_name, user_details.last_name) as name, user_details.is_escrow');
                $this->CI->db->from('order_details');
                $this->CI->db->where('MONTH(order_details.sent_to_accounting_date)', $month);
                $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', $year);
                $this->CI->db->where('transaction_details.sales_representative', $sales_rep_user_id);
                $this->CI->db->where('customer_basic_details.email_address != ""');
                $this->CI->db->join('property_details', 'order_details.property_id = property_details.id', 'inner');
                $this->CI->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id', 'inner');
                $this->CI->db->join('customer_basic_details', 'customer_basic_details.id = transaction_details.sales_representative', 'inner');
                $this->CI->db->join('customer_basic_details as user_details', 'user_details.id = order_details.customer_id', 'inner');
                $this->CI->db->group_by('user_details.id');
                $query = $this->CI->db->get();
                $resultMax = $query->result_array();

                $lenderName = '';
                $escrowName = '';
                $lenderCount = 0;
                $escrowCount = 0;

                foreach ($resultMax as $resMax) {
                    if ($resMax['is_escrow'] == '0') {
                        if ($resMax['order_count'] > $lenderCount) {
                            $lenderName = $resMax['name'] . " - " . $resMax['company_name'];
                            $lenderCount = $resMax['order_count'];
                        }
                    } else {
                        if ($resMax['order_count'] > $escrowCount) {
                            $escrowName = $resMax['name'] . " - " . $resMax['company_name'];
                            $escrowCount = $resMax['order_count'];
                        }
                    }
                }

                if ($res['sales_representative'] == $sales_rep_user_id) {
                    if ($res['customer_id'] == $order_user_id) {
                        $j++;
                        $userName = $res['name'];
                        $companyName = $res['company_name'];
                        $sales_name = $res['sales_name'];
                    } else {
                        $j = 1;
                        $i++;
                        $userName = $res['name'];
                        $companyName = $res['company_name'];
                        $order_user_id = $res['customer_id'];
                        $sales_name = $res['sales_name'];
                    }

                    $data['sales_name'] = $sales_name;
                    $data['escrowName'] = $escrowName;
                    $data['lenderName'] = $lenderName;
                    $currentMonth = date('F');
                    $data['currentMonth'] = Date('F', strtotime($currentMonth . " last month"));

                    if ($res['partner_type_id']) {
                        $partner_type_id = explode(',', $res['partner_type_id']);
                        if (in_array(14, $partner_type_id) || in_array(15, $partner_type_id)) {
                            $endPoint = 'files/' . $res['file_id'] . '/partners';
                            $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $res['file_id'], 0);
                            $result = $this->CI->resware->make_request('GET', $endPoint, '', $user_data);
                            $this->CI->apiLogs->syncLogs(0, 'resware', 'get_partners_from_client_summary', env('RESWARE_ORDER_API') . $endPoint, array(), $result, $res['file_id'], $logid);
                            $partners = json_decode($result, true);
                            // print_r($partners);die;
                            if (isset($partners['Partners']) && !empty($partners['Partners'])) {
                                $partnersArr = $partners['Partners'];
                                $key = array_search($res['partner_id'], array_column($partnersArr, 'PartnerID'));
                                if ($partnersArr[$key]['PartnerTypeID'] == 14 || $partnersArr[$key]['PartnerTypeID'] == 15) {
                                    $data['summary_info'][$i]['company_name'] = $companyName . ' - ' . $partnersArr[$key]['PartnerType']['PartnerTypeName'];
                                } else {
                                    continue;
                                }
                            } else {
                                continue;
                            }
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }

                    // if ($res['is_escrow'] == "0") {
                    $data['summary_info'][$i]['name'] = $userName;
                    // $data['summary_info'][$i]['company_name'] = $companyName;
                    $data['summary_info'][$i]['count'] = $j;
                    // }

                } else {
                    if ($sales_rep_id != 0) {
                        $message = $this->CI->load->view('frontend/emails/summary_new.php', $data, true);
                    } else {
                        $message = $this->CI->load->view('emails/summary_new.php', $data, true);
                    }
                    $from_name = 'Pacific Coast Title Company';
                    $from_mail = env('FROM_EMAIL');
                    $subject = 'Client Summary';
                    $to = $sales_rep_email;
                    $cc = array('ghernandez@pct.com');
                    $mailParams = array(
                        'from_mail' => $from_mail,
                        'from_name' => $from_name,
                        'to' => $to,
                        'subject' => $subject,
                        'message' => json_encode($data),
                        'cc' => $cc,
                    );
                    $to = 'ghernandez@pct.com';
                    //$to = array('hitesh.p@crestinfosystems.com');
                    $cc = array('piyush.j@crestinfosystems.net');
                    $this->CI->load->helper('sendemail');
                    $logid = $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'summary_mail_to_sales_rep', '', $mailParams, array(), 0, 0);
                    $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
                    $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'summary_mail_to_sales_rep', '', $mailParams, array('status' => $escrow_mail_result), 0, $logid);
                    $data = array();
                    $i = 0;
                    $j = 1;
                    $sales_rep_email = $res['sales_email'];
                    $sales_rep_user_id = $res['sales_representative'];
                    $order_user_id = $res['customer_id'];
                    $userName = $res['name'];
                    $companyName = $res['company_name'];
                    $sales_name = $res['sales_name'];
                    // if ($res['is_escrow'] == "0") {
                    $data['summary_info'][$i]['name'] = $userName;
                    $data['summary_info'][$i]['count'] = $j;
                    $data['summary_info'][$i]['company_name'] = $companyName;
                    // }
                    $data['sales_name'] = $sales_name;
                    $data['escrowName'] = $escrowName;
                    $data['lenderName'] = $lenderName;
                }
            }
            // echo "<pre>";
            // print_r($data);die;
            if (!empty($data)) {
                if ($sales_rep_id != 0) {
                    $message = $this->CI->load->view('frontend/emails/summary_new.php', $data, true);
                } else {
                    $message = $this->CI->load->view('emails/summary_new.php', $data, true);
                }
                $from_name = 'Pacific Coast Title Company';
                $from_mail = env('FROM_EMAIL');
                $subject = 'Client Summary';
                $to = $sales_rep_email;
                $cc = array('ghernandez@pct.com');
                $this->CI->load->helper('sendemail');
                $mailParams = array(
                    'from_mail' => $from_mail,
                    'from_name' => $from_name,
                    'to' => $to,
                    'subject' => $subject,
                    'message' => json_encode($data),
                    'cc' => $cc,
                );
                $to = 'ghernandez@pct.com';
                // $to = 'piyush.j@crestinfosystems.net';
                //$to = array('hitesh.p@crestinfosystems.com');
                $cc = array('piyush.j@crestinfosystems.net');

                $logid = $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'summary_mail_to_sales_rep', '', $mailParams, array(), 0, 0);
                $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
                $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'summary_mail_to_sales_rep', '', $mailParams, array('status' => $escrow_mail_result), 0, $logid);
            }
        }
    }

    public function getLPOrdersForSalesRep($startDate, $endDate, $userId)
    {
        $this->CI->db->select('*')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->CI->db->where('order_details.lp_file_number is not null');
        $this->CI->db->where('order_details.created_at BETWEEN "' . $startDate . '" and "' . $endDate . '"');
        $this->CI->db->where_in('transaction_details.sales_representative', $userId);
        $query = $this->CI->db->get();
        return $query->result_array();
    }

    public function sendNonOpenersEmail($sales_rep_id)
    {
        $this->CI->load->model('order/home_model');
        $this->CI->load->model('order/apiLogs');
        $salesUser = $this->CI->home_model->get_user(array('id' => $sales_rep_id));
        $salesRepUsers = array();
        if (!empty($salesUser['sales_rep_users'])) {
            $salesRepUsers = explode(',', $salesUser['sales_rep_users']);
            if (!in_array($sales_rep_id, $salesRepUsers)) {
                $salesRepUsers[] = $userdata['id'];
            }
        }
        $startDate = date('Y-m-d 00:00:00', strtotime('-90 days', strtotime(date('Y-m-d'))));
        //$endDate = date('Y-m-d 23:59:59', strtotime('-7 days', strtotime(date('Y-m-d'))));
        $this->CI->db->select('customer_basic_details.email_address, user_details.company_name, user_details.id as user_id, CONCAT_WS(" ", user_details.first_name, user_details.last_name) as name, order_details.resware_status, order_details.created_at, CONCAT_WS(" ", customer_basic_details.first_name, customer_basic_details.last_name) as sales_rep_name')
            ->from('order_details')
            ->join('customer_basic_details as user_details', 'user_details.id = order_details.customer_id', 'inner')

            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
            ->join('customer_basic_details', 'customer_basic_details.id = transaction_details.sales_representative', 'inner');
        //$this->CI->db->where('order_details.lp_file_number is not null');
        $this->CI->db->where("order_details.created_at <= '$startDate'");
        if (!empty($salesRepUsers)) {
            // $this->CI->db->where_in('transaction_details.sales_representative', $salesRepUsers);
            $this->CI->db->where('transaction_details.sales_representative', $sales_rep_id);
        } else {
            $this->CI->db->where('transaction_details.sales_representative', $sales_rep_id);
        }
        $this->CI->db->where('order_details.`is_imported` = 0');
        $this->CI->db->where('order_details.`file_number` != 0');
        $this->CI->db->order_by('order_details.id asc');
        $query = $this->CI->db->get();
        //echo $this->CI->db->last_query();exit;
        $result = $query->result_array();

        $users = array();
        $i = 0;
        if (!empty($result)) {
            foreach ($result as $res) {
                if ($res['resware_status'] == 'open') {
                    if (!empty($res['name'])) {
                        $key = array_search($res['user_id'], array_column($users, 'id'));
                        if ($key === false) {
                            $users[$i]['id'] = $res['user_id'];
                            $users[$i]['name'] = $res['name'];
                            $users[$i]['company_name'] = $res['company_name'];
                            $users[$i]['last_deal_opened'] = date("m/d/Y", strtotime($res['created_at']));
                            $sales_rep_email = $res['email_address'];
                            $sales_rep_name = $res['sales_rep_name'];
                            $i++;
                        } else {
                            $users[$key]['last_deal_opened'] = date("m/d/Y", strtotime($res['created_at']));
                        }
                    }
                } else {
                    if ($res['resware_status'] != 'cancelled') {
                        if (!empty($res)) {
                            $key = array_search($res['user_id'], array_column($users, 'id'));
                            if (strlen($key) > 0) {
                                unset($users[$key]);
                            }
                        }
                    }
                }
            }
        }
        if (!empty($users)) {
            $data['users'] = $users;
            $data['sales_rep_name'] = $sales_rep_name;
            if ($sales_rep_id != 0) {
                $message = $this->CI->load->view('frontend/emails/non_openers.php', $data, true);
            } else {
                $message = $this->CI->load->view('emails/non_openers.php', $data, true);
            }
            $from_name = 'Pacific Coast Title Company';
            $from_mail = env('FROM_EMAIL');
            $subject = 'Non Openers';
            $to = $sales_rep_email;
            $cc = array('ghernandez@pct.com');
            $this->CI->load->helper('sendemail');
            $mailParams = array(
                'from_mail' => $from_mail,
                'from_name' => $from_name,
                'to' => $to,
                'subject' => $subject,
                'message' => json_encode($data),
                'cc' => $cc,
            );
            $to = 'ghernandez@pct.com';
            // $to = 'piyush.j@crestinfosystems.net';
            //$to = array('hitesh.p@crestinfosystems.com');
            $cc = array('piyush.j@crestinfosystems.net');

            $logid = $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'non_openers_mail_to_sales_rep', '', $mailParams, array(), 0, 0);
            $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
            $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'non_openers_mail_to_sales_rep', '', $mailParams, array('status' => $escrow_mail_result), 0, $logid);
        }
        return true;
    }

    public function getRevenueData($month, $userId)
    {
        $userdata = $this->CI->session->userdata('user');
        if (empty($userId)) {
            $userId = $userdata['id'];
        }
        $this->CI->db->select('order_details.file_number, order_details.file_id, property_details.full_address,order_details.id, order_details.prod_type, order_details.premium')
            ->from('order_details')
            ->join('property_details', 'order_details.property_id = property_details.id')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');

        $this->CI->db->where('MONTH(order_details.sent_to_accounting_date)', $month);
        $this->CI->db->where('YEAR(order_details.sent_to_accounting_date)', date('Y'));

        $this->CI->db->where('transaction_details.sales_representative', $userId);
        $query = $this->CI->db->get();
        return $query->result_array();
    }

    public function get_transactees($params)
    {
        // $userdata = $this->CI->session->userdata('user');
        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = trim($params['searchvalue']);

            if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
                    ->like("pct_vendors.transctee_name", $keyword)
                    ->or_like('pct_vendors.file_number', $keyword)
                    ->or_like('pct_vendors.account_number', $keyword)
                    ->or_like('pct_vendors.aba', $keyword)
                    ->or_like('pct_vendors.bank_name', $keyword)
                    ->or_like('pct_vendors.notes', $keyword)
                    ->or_like('pct_vendors.admin_notes', $keyword)
                    ->group_end();
            }

            $this->CI->db->from('pct_vendors');
            $this->CI->db->where('is_approved', 1);
            $filter_total_records = $this->CI->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->CI->db->group_start()
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
            $venders_lists = array();

            $this->CI->db->select('
                    pct_vendors.id,
                    pct_vendors.transctee_name,
                    pct_vendors.file_number,
                    pct_vendors.account_number,
                    pct_vendors.aba,
                    pct_vendors.bank_name,
                    pct_vendors.submitted,
                    pct_vendors.notes,
                    pct_vendors.admin_notes,
                    pct_vendors.approved_by,
                    pct_vendors.created_by,
                    pct_vendors.is_approved,
                    pct_vendors.approved_date,
                    admin.first_name,
                    admin.last_name,
                ')
                ->from('pct_vendors')
                ->join('admin', 'admin.id = pct_vendors.approved_by', 'left')
                ->where('is_approved', 1)
                ->order_by('pct_vendors.transctee_name', 'asc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

            $query = $this->CI->db->get();

            if ($query->num_rows() > 0) {
                $venders_lists = $query->result_array();
            }
        } else {

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

            $this->CI->db->from('pct_vendors');
            $this->CI->db->where('is_approved', 1);
            $filter_total_records = $this->CI->db->count_all_results();

            $venders_lists = array();

            $this->CI->db->select('
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
                ')
                ->from('pct_vendors')
                ->join('admin', 'admin.id = pct_vendors.approved_by', 'left')
                ->where('is_approved', 1)
                ->order_by('pct_vendors.transctee_name', 'asc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->CI->db->limit($limit, $offset);
            }

            $query = $this->CI->db->get();

            if ($query->num_rows() > 0) {
                $venders_lists = $query->result_array();
            }
        }
        // print_r($this->CI->db->last_query());die;
        return array(
            'recordsTotal' => $filter_total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $venders_lists,
        );
    }

    public function sendEmail($to, $cc, $subject, $data, $message, $logName)
    {
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');

        $mailParams = array(
            'from_mail' => $from_mail,
            'from_name' => $from_name,
            'to' => $to,
            'subject' => $subject,
            'message' => json_encode($data),
            'cc' => $cc,
        );

        $this->CI->load->helper('sendemail');
        $logid = $this->CI->apiLogs->syncLogs(0, 'sendgrid', $logName, '', $mailParams, array(), 0, 0);
        $email_send_status = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
        $this->CI->apiLogs->syncLogs(0, 'sendgrid', $logName, '', $mailParams, array('status' => $email_send_status), 0, $logid);
    }

    public function sendSurveySampleEmail($data) {
        $this->CI->load->model('order/apiLogs');
        $this->CI->load->helper('sendemail');
        $message = $this->CI->load->view('frontend/emails/surveymonkey_email.php', $data, true);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        // $subject = 'Thank You!';
        // $to = $data['escrow_officer_email'];
        $to = array($data['email_address']);
        $cc = array('piyush.j@crestinfosystems.com');
        // print_r($message);die;

        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $subject = "We'd Love Your Feedback";
        // $to = $escrow_email_address;
        // $cc = array('piyush.j@crestinfosystems.com', $sales_email);
        // $cc = array('piyush.j@crestinfosystems.com');
        $mailParams = array(
            'from_mail' => $from_mail,
            'from_name' => $from_name,
            'to' => $to,
            'subject' => $subject,
            'message' => json_encode($data),
            'cc' => $cc,
        );
        // $to = ['piyush.j@crestinfosystems.net', 'ghernandez@pct.com'];
        // $cc = array();
        $this->CI->load->helper('sendemail');
        $logid = $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'survay_sample_email_sent_mail', '', $mailParams, array(), $data['order_id'], 0);
        $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
        // print_r($mail_result);die;
        $this->CI->apiLogs->syncLogs(0, 'sendgrid', 'survay_sample_email_sent_mail', '', $mailParams, array('status' => $mail_result), $data['orderId'], $logid);
        return $mail_result;
    }

    public function getSalesRepForOrder($orderId) {
        $this->CI->db->select('order_details.file_number, customer_basic_details.first_name, customer_basic_details.last_name')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
            ->join('customer_basic_details', 'customer_basic_details.id = transaction_details.sales_representative and customer_basic_details.is_sales_rep = 1');
        $this->CI->db->where('order_details.id', $orderId);

        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function surveyReportCards($data) {
        // echo "<pre>";
        // print_r($this->salesdashboardtemplate->show("order/common/survey", "survey_report_cards", ['value' => $data]));die;
        // $results = $this->load->view('order/review_file_summary', $data, true);
        return $this->CI->load->view('frontend/order/common/survey/survey_report_cards', $data, true);
        // echo $this->salesdashboardtemplate->show("order/common/survey", "survey_report_cards", ['value' => $data]);
    }

    public function surveyReportRating($data) {
        return $this->CI->load->view('frontend/order/common/survey/survey_report_rating_details', $data, true);
    }
}
