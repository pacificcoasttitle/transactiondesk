<?php
class TitlePoint_model extends CI_Model
{
    public function __construct()
    {
        // Set table name
        $this->table = 'pct_order_title_point_data';
    }

    public function gettitlePointDetails($params)
    {
        $table = $this->table;

        $this->db->select('*');
        $this->db->from($table);

        if (array_key_exists("where", $params)) {
            foreach ($params['where'] as $key => $val) {
                $this->db->where($key, $val);
            }
        }
        if (array_key_exists("status", $params)) {
            foreach ($params['status'] as $key => $val) {
                $this->db->where($key . "!=", $val);
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

    public function getLvLogs($params)
    {
        $this->db->where('file_id IS NOT NULL');
        $this->db->from($this->table);
        $total_records = $this->db->count_all_results();
        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $logs_lists = array();

        if ((isset($params['searchvalue']) && !empty($params['searchvalue'])) || (isset($params['dateRange']) && !empty($params['dateRange'])) || (isset($params['lvLog']) && !empty($params['lvLog']))) {
            $keyword = $params['searchvalue'];
            $dateRange = trim($params['dateRange']);
            $lvLog = $params['lvLog'];
            $ymdStartDate = '';
            $ymdEndDate = '';
            if (!empty($dateRange)) {
                $dateRangeArr = explode(' - ', $dateRange);
                $startDate = $dateRangeArr[0];
                $endDate = $dateRangeArr[1];
                $ymdStartDate = date("Y-m-d 00:00:00", strtotime($startDate));
                $ymdEndDate = date("Y-m-d 23:59:59", strtotime($endDate));
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }
            $this->db->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.full_address', $keyword)
                    ->group_end();
            }
            if (!empty($lvLog) && $lvLog == 'success') {
                $this->db->where('tp.lv_file_status', $lvLog);
            } else if (!empty($lvLog) && $lvLog == 'error') {
                $this->db->group_start()
                    ->where('tp.lv_file_status !=', 'success')
                    ->or_where('tp.lv_file_status is null')
                    ->group_end();
            }

            $this->db->where('tp.file_id IS NOT NULL');
            // $this->db->from($this->table);
            $filter_total_records = $this->db->count_all_results();

            $this->db->select('tp.file_number,tp.file_id, tp.lv_file_status, tp.cs4_message, tp.created_at, pd.full_address')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.full_address', $keyword)
                    ->group_end();
            }
            if (isset($dateRange) && !empty($dateRange)) {
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }
            if (!empty($lvLog) && $lvLog == 'success') {
                $this->db->where('tp.lv_file_status', $lvLog);
            } else if (!empty($lvLog) && $lvLog == 'error') {
                $this->db->group_start()
                    ->where('tp.lv_file_status !=', 'success')
                    ->or_where('tp.lv_file_status is null')
                    ->group_end();
            }

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('tp.id', 'desc');
            /*if(array_key_exists("status", $params)){
            foreach($params['status'] as $key => $val){
            $this->db->where($key."!=", $val);
            }
            }*/
            $this->db->where('tp.file_id IS NOT NULL');
            // $this->db->where('cs4_message IS NOT NULL AND cs4_message != ""');
            $query = $this->db->get();
            // echo $this->db->last_query();exit;
            if ($query->num_rows() > 0) {
                $logs_lists = $query->result_array();
            }
        } else {

            /*if(array_key_exists("status", $params)){
            foreach($params['status'] as $key => $val){
            $this->db->where($key."!=", $val);
            }
            }*/
            $this->db->where('file_id IS NOT NULL');
            // $this->db->where('cs4_message IS NOT NULL AND cs4_message != ""');
            $this->db->from($this->table);

            $filter_total_records = $this->db->count_all_results();

            /*if(array_key_exists("status", $params)){
            foreach($params['status'] as $key => $val){
            $this->db->where($key."!=", $val);
            }
            }*/
            $this->db->select('tp.file_number,tp.file_id, tp.lv_file_status, tp.cs4_message, tp.created_at, pd.full_address')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            $this->db->where('tp.file_id IS NOT NULL');
            // $this->db->where('cs4_message IS NOT NULL AND cs4_message != ""');
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('tp.id', 'desc');
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

    public function getPreListingLogs($params)
    {
        $this->db->where('file_id IS NOT NULL');
        $this->db->from($this->table);
        $total_records = $this->db->count_all_results();
        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $logs_lists = array();

        if ((isset($params['searchvalue']) && !empty($params['searchvalue'])) || (isset($params['dateRange']) && !empty($params['dateRange'])) || (isset($params['preListingLog']) && !empty($params['preListingLog']))) {
            $keyword = $params['searchvalue'];
            $dateRange = trim($params['dateRange']);
            $preListingLog = $params['preListingLog'];
            $ymdStartDate = '';
            $ymdEndDate = '';
            if (!empty($dateRange)) {
                $dateRangeArr = explode(' - ', $dateRange);
                $startDate = $dateRangeArr[0];
                $endDate = $dateRangeArr[1];
                $ymdStartDate = date("Y-m-d 00:00:00", strtotime($startDate));
                $ymdEndDate = date("Y-m-d 23:59:59", strtotime($endDate));
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }

            $this->db->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.address', $keyword)
                    ->group_end();
                $this->db->where('tp.geo_file_status IS NOT NULL');
            }
            if (!empty($preListingLog) && $preListingLog == 'success') {
                $this->db->where('tp.geo_file_status', $preListingLog);
            } else if (!empty($preListingLog) && $preListingLog == 'error') {
                $this->db->group_start()
                    ->where('tp.geo_file_status !=', 'success')
                    ->where('tp.geo_file_status IS NOT NULL')
                    ->group_end();
            }

            $this->db->where('tp.file_id IS NOT NULL');
            $this->db->from($this->table);
            $filter_total_records = $this->db->count_all_results();

            $this->db->select('tp.file_number,tp.file_id, tp.geo_file_status, tp.geo_file_message, tp.created_at, pd.full_address')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.address', $keyword)
                    ->group_end();
                $this->db->where('tp.geo_file_status IS NOT NULL');
            }
            if (isset($dateRange) && !empty($dateRange)) {
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }
            if (!empty($preListingLog) && $preListingLog == 'success') {
                $this->db->where('tp.geo_file_status', $preListingLog);
            } else if (!empty($preListingLog) && $preListingLog == 'error') {
                $this->db->group_start()
                    ->where('tp.geo_file_status !=', 'success')
                    ->where('tp.geo_file_status IS NOT NULL')
                    ->group_end();
            }

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $this->db->where('tp.file_id IS NOT NULL');
            $this->db->order_by('tp.id', 'desc');
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $logs_lists = $query->result_array();
            }
        } else {
            $this->db->select('tp.file_number,tp.file_id, tp.geo_file_status, tp.geo_file_message, tp.created_at, pd.full_address')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            $this->db->where('tp.file_id IS NOT NULL');
            $this->db->where('tp.geo_file_status IS NOT NULL');

            $filter_total_records = $this->db->count_all_results();

            $this->db->select('tp.file_number,tp.file_id, tp.geo_file_status, tp.geo_file_message, tp.created_at, pd.full_address')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            $this->db->where('tp.file_id IS NOT NULL');
            $this->db->where('tp.geo_file_status IS NOT NULL');
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('tp.id', 'desc');
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

    public function get_order_details($fileId)
    {

        $this->db->select('order_details.file_number,
            order_details.customer_id,
            order_details.id as order_id,
            order_details.file_id,
            order_details.westcor_order_id,
            order_details.westcor_cpl_id,
            order_details.westcor_buyer_id,
            order_details.westcor_seller_id,
            order_details.westcor_secondary_buyer_id,
            order_details.westcor_secondary_seller_id,
            order_details.westcor_lender_id,
            order_details.is_regenerate_cpl,
            order_details.created_at as opened_date,
            property_details.id as property_id,
            property_details.address,
            property_details.full_address,
            property_details.property_type,
            property_details.apn,
            property_details.city as property_city,
            property_details.state as property_state,
            property_details.zip as property_zip,
            property_details.county,
            property_details.westcor_property_id,
            property_details.legal_description,
            property_details.primary_owner,
            property_details.secondary_owner,
            property_details.escrow_lender_id,
            transaction_details.id as transaction_id,
            transaction_details.sales_amount,
            transaction_details.loan_amount,
            transaction_details.loan_number,
            transaction_details.transaction_type,
            transaction_details.title_officer,
            transaction_details.purchase_type,
            transaction_details.supplemental_report_date,
            transaction_details.preliminary_report_date,
            transaction_details.borrower,
            transaction_details.secondary_borrower,
            customer_basic_details.id as lender_id,
            customer_basic_details.street_address as lender_address,
            customer_basic_details.city as lender_city,
            customer_basic_details.zip_code as lender_zipcode,
            customer_basic_details.company_name as lender_company_name,
            customer_basic_details.first_name as lender_first_name,
            customer_basic_details.last_name as lender_last_name,
            customer_basic_details.email_address as lender_email,
            customer_basic_details.telephone_no as lender_telephone_no')
            ->from('order_details')
            ->join('property_details', 'order_details.property_id = property_details.id')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
            ->join('customer_basic_details', 'property_details.escrow_lender_id = customer_basic_details.id', 'left');
        $this->db->where('file_id', $fileId);
        // $this->db->order_by('order_details.id', 'desc');

        $query = $this->db->get();

        return $query->row_array();
    }

    public function getTaxData($params)
    {
        $this->db->where('file_id IS NOT NULL');

        $this->db->from($this->table);
        $total_records = $this->db->count_all_results();

        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

        $logs_lists = array();
        if ((isset($params['searchvalue']) && !empty($params['searchvalue'])) || (isset($params['dateRange']) && !empty($params['dateRange'])) || (isset($params['taxLog']) && !empty($params['taxLog']))) {

            $keyword = $params['searchvalue'];
            $dateRange = trim($params['dateRange']);
            $taxLog = $params['taxLog'];
            $ymdStartDate = '';
            $ymdEndDate = '';
            $this->db->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            if (!empty($dateRange)) {
                $dateRangeArr = explode(' - ', $dateRange);
                $startDate = $dateRangeArr[0];
                $endDate = $dateRangeArr[1];
                $ymdStartDate = date("Y-m-d 00:00:00", strtotime($startDate));
                $ymdEndDate = date("Y-m-d 23:59:59", strtotime($endDate));
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }
            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.address', $keyword)
                    ->group_end();
            }
            if (!empty($taxLog) && $taxLog == 'success') {
                $this->db->where('tp.tax_data_status', $taxLog);
            } else if (!empty($taxLog) && $taxLog == 'error') {
                $this->db->group_start()
                    ->where('tp.tax_data_status !=', 'success')
                    ->or_where('tp.tax_data_status is null')
                    ->group_end();
            }

            $this->db->where('tp.file_id IS NOT NULL');
            // $this->db->from($this->table);
            $filter_total_records = $this->db->count_all_results();
            $this->db->select('tp.file_number,tp.file_id, tp.tax_data_status, tp.geo_file_message, tp.created_at, pd.full_address, pd.apn, order_details.id as order_id, tp.session_id')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            if (!empty($dateRange)) {
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.address', $keyword)
                    ->group_end();
            }

            if (!empty($taxLog) && $taxLog == 'success') {
                $this->db->where('tp.tax_data_status', $taxLog);
            } else if (!empty($taxLog) && $taxLog == 'error') {
                $this->db->group_start()
                    ->where('tp.tax_data_status !=', 'success')
                    ->or_where('tp.tax_data_status is null')
                    ->group_end();
            }

            $this->db->where('tp.file_id IS NOT NULL');
            $this->db->order_by('tp.id', 'desc');

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $logs_lists = $query->result_array();
            }

        } else {
            $this->db->where('tp.file_id IS NOT NULL');
            // $this->db->where('cs3_message IS NOT NULL AND cs3_message != ""');

            // $this->db->from($this->table);
            $this->db->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');

            $filter_total_records = $this->db->count_all_results();

            $this->db->select('tp.file_number,tp.file_id, tp.tax_data_status, tp.geo_file_message, tp.created_at, pd.full_address, pd.apn, order_details.id as order_id, tp.session_id')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            $this->db->where('tp.file_id IS NOT NULL');
            // $this->db->where('cs3_message IS NOT NULL AND cs3_message != ""');
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('tp.id', 'desc');
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
    public function getTaxLogs($params)
    {
        //print_r($params);exit;
        $this->db->where('file_id IS NOT NULL');
        // $this->db->where('cs3_message IS NOT NULL AND cs3_message != ""');

        $this->db->from($this->table);
        $total_records = $this->db->count_all_results();

        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

        $logs_lists = array();
        if ((isset($params['searchvalue']) && !empty($params['searchvalue'])) || (isset($params['dateRange']) && !empty($params['dateRange'])) || (isset($params['taxLog']) && !empty($params['taxLog']))) {

            $keyword = $params['searchvalue'];
            $dateRange = trim($params['dateRange']);
            $taxLog = $params['taxLog'];
            $ymdStartDate = '';
            $ymdEndDate = '';

            $this->db->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            $this->db->where('tp.file_id IS NOT NULL');
            if (!empty($dateRange)) {
                $dateRangeArr = explode(' - ', $dateRange);
                $startDate = $dateRangeArr[0];
                $endDate = $dateRangeArr[1];
                $ymdStartDate = date("Y-m-d 00:00:00", strtotime($startDate));
                $ymdEndDate = date("Y-m-d 23:59:59", strtotime($endDate));
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }
            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.address', $keyword)
                    ->group_end();
            }
            if (!empty($taxLog) && $taxLog == 'success') {
                $this->db->where('tp.tax_file_status', $taxLog);
            } else if (!empty($taxLog) && $taxLog == 'error') {
                $this->db->group_start()
                    ->where('tp.tax_file_status !=', 'success')
                    ->or_where('tp.tax_file_status is null')
                    ->group_end();
            }

            $this->db->where('tp.file_id IS NOT NULL');
            // $this->db->from();
            $filter_total_records = $this->db->count_all_results();

            $this->db->select('tp.file_number,tp.file_id, tp.tax_file_status, tp.cs3_message, tp.tax_request_id, tp.cs3_service_id, tp.created_at, pd.full_address, pd.apn, order_details.id as order_id, tp.session_id')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            if (!empty($dateRange)) {
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.address', $keyword)
                    ->group_end();
            }

            if (!empty($taxLog) && $taxLog == 'success') {
                $this->db->where('tp.tax_file_status', $taxLog);
            } else if (!empty($taxLog) && $taxLog == 'error') {
                $this->db->group_start()
                    ->where('tp.tax_file_status !=', 'success')
                    ->or_where('tp.tax_file_status is null')
                    ->group_end();
            }

            $this->db->order_by('tp.id', 'desc');

            $this->db->where('tp.file_id IS NOT NULL');

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $logs_lists = $query->result_array();
            }

        } else {
            $this->db->where('tp.file_id IS NOT NULL');
            // $this->db->where('cs3_message IS NOT NULL AND cs3_message != ""');

            // $this->db->from($this->table);
            $this->db->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');

            $filter_total_records = $this->db->count_all_results();

            $this->db->select('tp.file_number,tp.file_id, tp.tax_file_status, tp.cs3_message, tp.tax_request_id, tp.cs3_service_id, tp.created_at, pd.full_address, pd.apn, order_details.id as order_id, tp.session_id')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            $this->db->where('tp.file_id IS NOT NULL');
            // $this->db->where('cs3_message IS NOT NULL AND cs3_message != ""');
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('tp.id', 'desc');
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

    public function getGrantDeedLogs($params)
    {
        $this->db->where('tp.file_id IS NOT NULL');

        $this->db->from($this->table . ' as tp');

        $total_records = $this->db->count_all_results();

        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

        $logs_lists = array();
        if ((isset($params['searchvalue']) && !empty($params['searchvalue'])) || (isset($params['dateRange']) && !empty($params['dateRange'])) || (isset($params['grantLog']) && !empty($params['grantLog']))) {
            $keyword = $params['searchvalue'];
            $dateRange = trim($params['dateRange']);
            $grantLog = $params['grantLog'];
            $ymdStartDate = '';
            $ymdEndDate = '';

            if (!empty($dateRange)) {
                $dateRangeArr = explode(' - ', $dateRange);
                $startDate = $dateRangeArr[0];
                $endDate = $dateRangeArr[1];
                $ymdStartDate = date("Y-m-d 00:00:00", strtotime($startDate));
                $ymdEndDate = date("Y-m-d 23:59:59", strtotime($endDate));
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }

            $this->db->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.address', $keyword)
                    ->group_end();
            }
            if (!empty($grantLog) && $grantLog == 'success') {
                $this->db->where('tp.grant_deed_status', 'ok');
            } else if (!empty($grantLog) && $grantLog == 'error') {
                $this->db->group_start()
                    ->where('tp.grant_deed_status !=', 'ok')
                    ->or_where('tp.grant_deed_status is null')
                    ->group_end();
            }

            $this->db->where('tp.file_id IS NOT NULL');

            $filter_total_records = $this->db->count_all_results();

            $this->db->select('tp.file_number,tp.file_id, tp.grant_deed_type, tp.grant_deed_message, tp.created_at, pd.full_address')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            if (!empty($dateRange)) {
                $this->db->where('tp.created_at >=', $ymdStartDate);
                $this->db->where('tp.created_at <=', $ymdEndDate);
            }

            if (!empty($grantLog) && $grantLog == 'success') {
                $this->db->where('tp.grant_deed_status', 'ok');
            } else if (!empty($grantLog) && $grantLog == 'error') {
                $this->db->group_start()
                    ->where('tp.grant_deed_status !=', 'ok')
                    ->or_where('tp.grant_deed_status is null')
                    ->group_end();
            }
            if (isset($keyword) && !empty($keyword)) {
                // $this->db->like('file_number', $keyword);
                $this->db->group_start()
                    ->like('tp.file_number', $keyword)
                    ->or_like('pd.address', $keyword)
                    ->group_end();
            }

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('tp.id', 'desc');

            $this->db->where('tp.file_id IS NOT NULL');

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $logs_lists = $query->result_array();
            }
        } else {
            $this->db->where('tp.file_id IS NOT NULL');

            $this->db->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');

            $filter_total_records = $this->db->count_all_results();

            $this->db->select('tp.file_number,tp.file_id, tp.grant_deed_type, tp.grant_deed_message, tp.created_at, pd.full_address')
                ->from($this->table . ' as tp')
                ->join('order_details', 'order_details.file_id = tp.file_id')
                ->join('property_details as pd', 'order_details.property_id = pd.id');
            $this->db->where('tp.file_id IS NOT NULL');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('tp.id', 'desc');
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

    public function getLPOrderLogs($params)
    {
        $this->db->where('lp_file_number IS NOT NULL');
        $this->db->from('order_details');
        $total_records = $this->db->count_all_results();
        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';

        if ((isset($params['searchvalue']) && !empty($params['searchvalue'])) || (isset($params['dateRange']) && !empty($params['dateRange']))) {
            $keyword = $params['searchvalue'];
            $dateRange = trim($params['dateRange']);

            if (isset($keyword) && !empty($keyword)) {
                $this->db->like('lp_file_number', $keyword);
            }

            // $this->db->where('file_id IS NOT NULL');
            $this->db->from('order_details');
            $filter_total_records = $this->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->db->like('lp_file_number', $keyword);
            }

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $this->db->order_by('id', 'desc');
            $query = $this->db->get('order_details');

            if ($query->num_rows() > 0) {
                $logs_lists = $query->result_array();
            }
        } else {
            $this->db->where('lp_file_number IS NOT NULL');
            $this->db->from('order_details');
            $filter_total_records = $this->db->count_all_results();

            $this->db->where('lp_file_number IS NOT NULL');
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('id', 'desc');
            $query = $this->db->get('order_details');

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
}
