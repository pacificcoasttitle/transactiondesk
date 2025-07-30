<?php
class Order_model extends CI_Model
{

    public function __construct()
    {
        // Set table name
        $this->table = 'order_details';
    }

    public function get_orders($params)
    {
        $sales_rep = isset($params['sales_rep']) && !empty($params['sales_rep']) ? $params['sales_rep'] : '';
        $product_type = isset($params['product_type']) && !empty($params['product_type']) ? $params['product_type'] : '';
        $order_type = isset($params['order_type']) && !empty($params['order_type']) ? $params['order_type'] : '';

        if (isset($sales_rep) && !empty($sales_rep)) {
            $this->db->where('transaction_details.sales_representative', $sales_rep);
        }

        if (isset($product_type) && !empty($product_type)) {
            $this->db->like('pct_order_product_types.product_type', $product_type);
        }

        $created_by = isset($params['created_by']) && !empty($params['created_by']) ? $params['created_by'] : '';

        if (isset($created_by) && !empty($created_by)) {
            $this->db->where('order_details.created_by', $created_by);
        }

        if (isset($order_type) && !empty($order_type)) {
            if ($order_type == 'resware_orders') {
                // $this->db->where('order_details.lp_file_number is null');
                $this->db->where('order_details.file_number is not null');
                $this->db->where('order_details.file_number !=', 0);
            } else if ($order_type == 'lp_orders') {
                $this->db->where('order_details.lp_file_number is not null');
                // $this->db->where('order_details.file_number', 0);
            }

        }

        if (isset($params['searchValue']) && !empty($params['searchValue'])) {
            $keyword = $params['searchValue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('property_details.full_address', $keyword)
                    ->or_like('order_details.file_number', $keyword)
                    ->group_end();
            }

            $this->db->select('order_details.file_number, order_details.lp_file_number,order_details.file_id, property_details.allow_duplication, property_details.full_address,property_details.id as property_id,order_details.id,transaction_details.id as transaction_id,transaction_details.sales_representative,transaction_details.purchase_type, CONCAT(cbd.first_name, " ", cbd.last_name) as sales_rep_name,pct_order_product_types.product_type, customer_basic_details.first_name, customer_basic_details.last_name,order_details.created_at, tpd.email_sent_status')
                ->from('order_details')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by', 'left')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details as cbd', 'transaction_details.sales_representative = cbd.id', 'left')
                ->join('pct_order_title_point_data as tpd', 'order_details.file_id = tpd.file_id', 'left')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');
            $total_records = $this->db->count_all_results();

            if (isset($sales_rep) && !empty($sales_rep)) {
                $this->db->where('transaction_details.sales_representative', $sales_rep);
            }

            if (isset($product_type) && !empty($product_type)) {
                $this->db->like('pct_order_product_types.product_type', $product_type);
            }

            $created_by = isset($params['created_by']) && !empty($params['created_by']) ? $params['created_by'] : '';
            if (isset($created_by) && !empty($created_by)) {
                $this->db->where('order_details.created_by', $created_by);
            }

            if (isset($order_type) && !empty($order_type)) {
                if ($order_type == 'resware_orders') {
                    // $this->db->where('order_details.lp_file_number is null');
                    $this->db->where('order_details.file_number is not null');
                    $this->db->where('order_details.file_number !=', 0);
                } else if ($order_type == 'lp_orders') {
                    $this->db->where('order_details.lp_file_number is not null');
                    // $this->db->where('order_details.file_number', 0);
                }

            }

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('property_details.full_address', $keyword)
                    ->or_like('order_details.file_number', $keyword)
                    ->group_end();
            }

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
            $orders_lists = array();

            $this->db->select('order_details.file_number, order_details.lp_file_number, order_details.file_id, property_details.allow_duplication, property_details.id as property_id,property_details.full_address,order_details.id,transaction_details.id as transaction_id,transaction_details.sales_representative,transaction_details.purchase_type,CONCAT(cbd.first_name, " ", cbd.last_name) as sales_rep_name,pct_order_product_types.product_type, customer_basic_details.first_name, customer_basic_details.last_name,order_details.created_at,tpd.email_sent_status')
                ->from('order_details')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by', 'left')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details as cbd', 'transaction_details.sales_representative = cbd.id', 'left')
                ->join('pct_order_title_point_data as tpd', 'order_details.file_id = tpd.file_id', 'left')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');
            $this->db->order_by("order_details.id", "desc");

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();
            // echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {
                $orders_lists = $query->result_array();
            }
        } else {
            $this->db->select('order_details.file_number, order_details.lp_file_number, order_details.file_id, property_details.allow_duplication, property_details.full_address,property_details.id as property_id,order_details.id,transaction_details.id as transaction_id,transaction_details.sales_representative,transaction_details.purchase_type,CONCAT(cbd.first_name, " ", cbd.last_name) as sales_rep_name,pct_order_product_types.product_type, customer_basic_details.first_name, customer_basic_details.last_name,order_details.created_at,tpd.email_sent_status')
                ->from('order_details')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by', 'left')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details as cbd', 'transaction_details.sales_representative = cbd.id', 'left')
                ->join('pct_order_title_point_data as tpd', 'order_details.file_id = tpd.file_id', 'left')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');

            $total_records = $this->db->count_all_results();

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
            $orders_lists = array();

            if (isset($sales_rep) && !empty($sales_rep)) {
                $this->db->where('transaction_details.sales_representative', $sales_rep);
            }

            if (isset($product_type) && !empty($product_type)) {
                $this->db->like('pct_order_product_types.product_type', $product_type);
            }

            $created_by = isset($params['created_by']) && !empty($params['created_by']) ? $params['created_by'] : '';
            if (isset($created_by) && !empty($created_by)) {
                $this->db->where('order_details.created_by', $created_by);
            }

            if (isset($order_type) && !empty($order_type)) {
                if ($order_type == 'resware_orders') {
                    // $this->db->where('order_details.lp_file_number is null');
                    $this->db->where('order_details.file_number is not null');
                    $this->db->where('order_details.file_number !=', 0);
                } else if ($order_type == 'lp_orders') {
                    $this->db->where('order_details.lp_file_number is not null');
                    // $this->db->where('order_details.file_number', 0);
                }

            }

            $this->db->select('order_details.file_number, order_details.lp_file_number, order_details.file_id, property_details.allow_duplication, property_details.full_address,property_details.id as property_id,transaction_details.id as transaction_id,order_details.id,transaction_details.sales_representative,transaction_details.purchase_type,CONCAT(cbd.first_name, " ", cbd.last_name) as sales_rep_name,pct_order_product_types.product_type, customer_basic_details.first_name, customer_basic_details.last_name,order_details.created_at,tpd.email_sent_status')
                ->from('order_details')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by', 'left')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details as cbd', 'transaction_details.sales_representative = cbd.id', 'left')
                ->join('pct_order_title_point_data as tpd', 'order_details.file_id = tpd.file_id', 'left')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');

            $this->db->order_by("order_details.id", "desc");

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();

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

    public function get_order_details($fileId)
    {
        $this->db->select('
            order_details.file_number,
            order_details.lp_file_number,
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
            property_details.apn,
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
            property_details.listing_agent_id,
            property_details.borrowers_vesting,
            property_details.cpl_proposed_property_address,
            property_details.cpl_proposed_property_city,
            property_details.cpl_proposed_property_state,
            property_details.cpl_proposed_property_zip,
            property_details.unit_number,
            transaction_details.id as transaction_id,
            transaction_details.sales_representative,
            CONCAT(salerep.first_name, " ", salerep.last_name) as sales_rep_name,
            salerep.telephone_no as sales_rep_phone,
            transaction_details.title_officer,
            CONCAT(titleofficer.first_name, " ", titleofficer.last_name) as title_officer_name,
            transaction_details.sales_amount,
            transaction_details.loan_amount,
            transaction_details.loan_number,
            transaction_details.escrow_number,
            transaction_details.notes,
            transaction_details.transaction_type,
            transaction_details.purchase_type,
            pct_order_product_types.product_type,
            transaction_details.supplemental_report_date,
            transaction_details.preliminary_report_date,
            transaction_details.borrower,
            CONCAT_WS(",",transaction_details.additional_email,transaction_details.additional_email_1,transaction_details.additional_email_2) as additional_emails,
            transaction_details.additional_email,
            transaction_details.additional_email_1,
            transaction_details.additional_email_2,
            transaction_details.secondary_borrower,
            transaction_details.vesting,
            transaction_details.escrow_number,
            customer_basic_details.company_name as escrow_lender_company_name,
            customer_basic_details.first_name as escrow_lender_first_name,
            customer_basic_details.last_name as escrow_lender_last_name,
            customer_basic_details.email_address as escrow_lender_email,
            customer_basic_details.telephone_no as escrow_lender_telephone_no,
            customer_basic_details.id as lender_id,
            customer_basic_details.resware_user_id as lender_resware_user_id,
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
            cbd.email_address as cust_email_address,
            cbd.company_name as cust_company_name,
            cbd.telephone_no as cust_telephone_no,
            cbd.street_address as cust_street_address,
            cbd.city as cust_city,
            cbd.zip_code as cust_zip_code,
            salerep.first_name as salerep_first_name,
            salerep.last_name as salerep_last_name,
            salerep.is_mail_notification as salerep_is_mail_notification,
            salerep.email_address as salerep_email_address,
            titleofficer.first_name as titleofficer_first_name,
            titleofficer.last_name as titleofficer_last_name,
            property_details.buyer_agent_id,
            agents.name as buyer_agent_name,
            agents.email_address as buyer_agent_email_address,
            agents.company as buyer_agent_company,
            agents.city as buyer_agent_city,
            agents.zipcode as buyer_agent_zipcode,
            agents.telephone_no as buyer_buyer_agent_telephone_no,
            agents.address as buyer_agent_address,
            agents.partner_id as buyer_agent_partner_id,
            pct_order_fnf_agents.agent_number,
            pct_order_fnf_agents.underwriter_code,
            pct_order_fnf_agents.underwriter,
            pct_order_product_types.product_type,
            pct_order_documents.created,
            p.created as proposed_document_created_date,
            a.name as listing_agent_name,
            a.email_address as listing_agent_email_address,
            a.company as listing_agent_company,
            a.partner_id as listing_agent_partner_id,
            a.telephone_no as listing_agent_telephone_no')
            ->from('order_details')
            ->join('property_details', 'order_details.property_id = property_details.id')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
            ->join('customer_basic_details', 'property_details.escrow_lender_id = customer_basic_details.id', 'left')
            ->join('customer_basic_details as cbd', 'order_details.customer_id = cbd.id', 'left')
            ->join('customer_basic_details as titleofficer', 'transaction_details.title_officer = titleofficer.id')
            ->join('customer_basic_details as salerep', 'transaction_details.sales_representative = salerep.id', 'left')
            ->join('pct_order_documents', 'pct_order_documents.document_name = order_details.cpl_document_name', 'left')
            ->join('pct_order_documents as p', 'p.document_name = order_details.proposed_insured_document_name', 'left')
            ->join('agents', 'property_details.buyer_agent_id = agents.id', 'left')
            ->join('agents a', 'property_details.listing_agent_id = a.id', 'left')
            ->join('pct_order_fnf_agents', 'order_details.fnf_agent_id = pct_order_fnf_agents.id', 'left')
            ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');
        $this->db->where('file_id', $fileId);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function get_order_count($filter = null)
    {
        $query = $this->db->select('order_details.prod_type as type,COUNT(order_details.id) AS total')
            ->from('order_details');
        // ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
        // ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');
        // ->where('is_imported=0');
        if ($filter && is_array($filter)) {
            $check_date_field = 'order_details.created_at';
            if (isset($filter['type']) && $filter['type'] == 'closed') {
                $check_date_field = 'order_details.sent_to_accounting_date';
            }
            if (isset($filter['for_month'])) {
                $query->where('MONTH(' . $check_date_field . ')', $filter['for_month']);
            }
            if (isset($filter['for_year'])) {
                $query->where('YEAR(' . $check_date_field . ')', $filter['for_year']);
            }
        }
        $query->group_by('order_details.prod_type');

        $query = $this->db->get();
        // echo $this->db->last_query();die;
        $orders_data = array();
        if ($query->num_rows() > 0) {
            $orders_data = $query->result_array();
        }

        return $orders_data;
    }

    public function get_title_point_count()
    {
        $this->db->group_start()
            ->where('lv_file_status !=', 'success')
            ->or_where('lv_file_status is null')
            ->group_end();

        $this->db->where('file_id IS NOT NULL');
        $this->db->from('pct_order_title_point_data');

        $lv_total_records = $this->db->count_all_results();

        $this->db->group_start()
            ->where('grant_deed_status !=', 'ok')
            ->or_where('grant_deed_status is null')
            ->group_end();

        $this->db->where('file_id IS NOT NULL');
        $this->db->from('pct_order_title_point_data');

        $grant_deed_total_records = $this->db->count_all_results();

        $this->db->group_start()
            ->where('tax_file_status !=', 'success')
            ->or_where('tax_file_status is null')
            ->group_end();

        $this->db->where('file_id IS NOT NULL');
        $this->db->from('pct_order_title_point_data');

        $tax_total_records = $this->db->count_all_results();

        return array(
            'lv_total_records' => $lv_total_records,
            'grant_deed_total_records' => $grant_deed_total_records,
            'tax_total_records' => $tax_total_records,
        );
    }

    public function get_safewire_orders_list($params)
    {
        $this->db->select('order_details.file_number,order_details.file_id, order_details.safewire_order_status,property_details.full_address,order_details.id,order_details.created_at, pct_order_partner_company_info.partner_name')
            ->from('order_details')
            ->join('pct_order_partner_company_info', 'order_details.escrow_officer_id = pct_order_partner_company_info.partner_id')
            ->join('property_details', 'order_details.property_id = property_details.id');
        $this->db->where('order_details.is_create_order_on_safewire = 1');

        $total_records = $this->db->count_all_results();

        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $safewire_orders_lists = array();

        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('order_details.file_number', $keyword)
                    ->or_like('order_details.safewire_order_status', $keyword)
                    ->or_like('property_details.full_address', $keyword)
                    ->or_like('pct_order_partner_company_info.partner_name', $keyword)
                    ->group_end();
            }

            $this->db->select('order_details.file_number,order_details.file_id, order_details.safewire_order_status,property_details.full_address,order_details.id,order_details.created_at, pct_order_partner_company_info.partner_name')
                ->from('order_details')
                ->join('pct_order_partner_company_info', 'order_details.escrow_officer_id = pct_order_partner_company_info.partner_id')
                ->join('property_details', 'order_details.property_id = property_details.id');
            $this->db->where('order_details.is_create_order_on_safewire = 1');
            $filter_total_records = $this->db->count_all_results();

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like('order_details.file_number', $keyword)
                    ->or_like('order_details.safewire_order_status', $keyword)
                    ->or_like('property_details.full_address', $keyword)
                    ->or_like('pct_order_partner_company_info.partner_name', $keyword)
                    ->group_end();
            }

            $this->db->select('order_details.file_number,order_details.file_id, order_details.safewire_order_status,property_details.full_address,order_details.id,order_details.created_at, pct_order_partner_company_info.partner_name')
                ->from('order_details')
                ->join('pct_order_partner_company_info', 'order_details.escrow_officer_id = pct_order_partner_company_info.partner_id')
                ->join('property_details', 'order_details.property_id = property_details.id');
            $this->db->where('order_details.is_create_order_on_safewire = 1');
            $this->db->order_by('order_details.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $safewire_orders_lists = $query->result_array();
            }
        } else {
            $this->db->select('order_details.file_number,order_details.file_id, order_details.safewire_order_status,property_details.full_address,order_details.id,order_details.created_at, pct_order_partner_company_info.partner_name')
                ->from('order_details')
                ->join('pct_order_partner_company_info', 'order_details.escrow_officer_id = pct_order_partner_company_info.partner_id')
                ->join('property_details', 'order_details.property_id = property_details.id');
            $this->db->where('order_details.is_create_order_on_safewire = 1');
            $filter_total_records = $this->db->count_all_results();

            $this->db->select('order_details.file_number,order_details.file_id, order_details.safewire_order_status,property_details.full_address,order_details.id,order_details.created_at, pct_order_partner_company_info.partner_name')
                ->from('order_details')
                ->join('pct_order_partner_company_info', 'order_details.escrow_officer_id = pct_order_partner_company_info.partner_id')
                ->join('property_details', 'order_details.property_id = property_details.id');
            $this->db->where('order_details.is_create_order_on_safewire = 1');
            $this->db->order_by('order_details.id', 'desc');

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $safewire_orders_lists = $query->result_array();
            }
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $safewire_orders_lists,
        );
    }

    public function get_lp_orders($params)
    {
        $sales_rep = isset($params['sales_rep']) && !empty($params['sales_rep']) ? $params['sales_rep'] : '';
        $product_type = isset($params['product_type']) && !empty($params['product_type']) ? $params['product_type'] : '';
        $order_type = isset($params['order_type']) && !empty($params['order_type']) ? $params['order_type'] : '';
        $start_date = isset($params['start_date']) && !empty($params['start_date']) ? $params['start_date'] : '';
        $end_date = isset($params['end_date']) && !empty($params['end_date']) ? $params['end_date'] : '';

        if (isset($sales_rep) && !empty($sales_rep)) {
            $this->db->where('transaction_details.sales_representative', $sales_rep);
        }

        if (isset($product_type) && !empty($product_type)) {
            $this->db->like('pct_order_product_types.product_type', $product_type);
        }

        $created_by = isset($params['created_by']) && !empty($params['created_by']) ? $params['created_by'] : '';

        if (isset($created_by) && !empty($created_by)) {
            $this->db->where('order_details.created_by', $created_by);
        }

        if (isset($order_type) && !empty($order_type)) {
            if ($order_type == 'resware_orders') {
                //$this->db->where('order_details.lp_file_number is null');
                $this->db->where('order_details.file_number is not null');
            } else if ($order_type == 'lp_orders') {
                $this->db->where('order_details.lp_file_number is not null');
                //$this->db->where('order_details.file_number', 0);
            }

        }

        if (!empty($end_date)) {
            $this->db->where('order_details.created_at >=', date('Y-m-d H:i:s', strtotime($start_date)));
            $this->db->where('order_details.created_at <=', date('Y-m-d 23:59:59', strtotime($end_date)));
        }

        if (isset($params['searchValue']) && !empty($params['searchValue'])) {
            $keyword = $params['searchValue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->db->where("(property_details.full_address LIKE '%" . $keyword . "%' OR order_details.lp_file_number LIKE '%" . $keyword . "%')");
            }

            $this->db->select('order_details.file_number, order_details.lp_file_number, order_details.file_id, order_details.ion_fraud_proceed_status, order_details.ion_fraud_required_status, property_details.allow_duplication, property_details.full_address,property_details.id as property_id,order_details.id,transaction_details.sales_representative,transaction_details.purchase_type, CONCAT(cbd.first_name, " ", cbd.last_name) as sales_rep_name,pct_order_product_types.product_type, customer_basic_details.first_name, customer_basic_details.last_name,order_details.created_at,tpd.email_sent_status')
                ->from('order_details')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by', 'left')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details as cbd', 'transaction_details.sales_representative = cbd.id', 'left')
                ->join('pct_order_title_point_data as tpd', 'order_details.file_id = tpd.file_id', 'left')
                ->join('pct_order_documents', 'order_details.id = pct_order_documents.order_id and pct_order_documents.is_pre_listing_report_doc=1', 'left')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');
            $total_records = $this->db->count_all_results();

            if (isset($sales_rep) && !empty($sales_rep)) {
                $this->db->where('transaction_details.sales_representative', $sales_rep);
            }

            if (isset($product_type) && !empty($product_type)) {
                $this->db->like('pct_order_product_types.product_type', $product_type);
            }

            $created_by = isset($params['created_by']) && !empty($params['created_by']) ? $params['created_by'] : '';
            if (isset($created_by) && !empty($created_by)) {
                $this->db->where('order_details.created_by', $created_by);
            }

            if (isset($order_type) && !empty($order_type)) {
                if ($order_type == 'resware_orders') {
                    //$this->db->where('order_details.lp_file_number is null');
                    $this->db->where('order_details.file_number is not null');
                } else if ($order_type == 'lp_orders') {
                    $this->db->where('order_details.lp_file_number is not null');
                    //$this->db->where('order_details.file_number', 0);
                }

            }

            if (!empty($end_date)) {
                $this->db->where('order_details.created_at >=', date('Y-m-d H:i:s', strtotime($start_date)));
                $this->db->where('order_details.created_at <=', date('Y-m-d 23:59:59', strtotime($end_date)));
            }

            if (isset($keyword) && !empty($keyword)) {
                $this->db->where("(property_details.full_address LIKE '%" . $keyword . "%' OR order_details.lp_file_number LIKE '%" . $keyword . "%')");
            }

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
            $orders_lists = array();

            $this->db->select('order_details.lp_report_status, order_details.file_number, order_details.lp_file_number, order_details.file_id, order_details.ion_fraud_proceed_status, order_details.ion_fraud_required_status, property_details.allow_duplication, property_details.id as property_id,property_details.full_address,order_details.id,transaction_details.sales_representative,transaction_details.purchase_type,CONCAT(cbd.first_name, " ", cbd.last_name) as sales_rep_name,pct_order_product_types.product_type, customer_basic_details.first_name, customer_basic_details.last_name,order_details.created_at, pct_order_documents.document_name,tpd.email_sent_status')
                ->from('order_details')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by', 'left')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details as cbd', 'transaction_details.sales_representative = cbd.id', 'left')
                ->join('pct_order_title_point_data as tpd', 'order_details.file_id = tpd.file_id', 'left')
                ->join('pct_order_documents', 'order_details.id = pct_order_documents.order_id and pct_order_documents.is_pre_listing_report_doc=1', 'left')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');
            $this->db->order_by("order_details.id", "desc");

            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $orders_lists = $query->result_array();
            }
        } else {
            $this->db->select('order_details.file_number, order_details.lp_file_number, order_details.file_id, order_details.ion_fraud_proceed_status, order_details.ion_fraud_required_status, property_details.allow_duplication, property_details.full_address,property_details.id as property_id,order_details.id,transaction_details.sales_representative,transaction_details.purchase_type,CONCAT(cbd.first_name, " ", cbd.last_name) as sales_rep_name,pct_order_product_types.product_type, customer_basic_details.first_name, customer_basic_details.last_name,order_details.created_at,tpd.email_sent_status')
                ->from('order_details')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by', 'left')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details as cbd', 'transaction_details.sales_representative = cbd.id', 'left')
                ->join('pct_order_title_point_data as tpd', 'order_details.file_id = tpd.file_id', 'left')
                ->join('pct_order_documents', 'order_details.id = pct_order_documents.order_id and pct_order_documents.is_pre_listing_report_doc=1', 'left')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');

            $total_records = $this->db->count_all_results();

            $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
            $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
            $orders_lists = array();

            if (isset($sales_rep) && !empty($sales_rep)) {
                $this->db->where('transaction_details.sales_representative', $sales_rep);
            }

            if (isset($product_type) && !empty($product_type)) {
                $this->db->like('pct_order_product_types.product_type', $product_type);
            }

            $created_by = isset($params['created_by']) && !empty($params['created_by']) ? $params['created_by'] : '';
            if (isset($created_by) && !empty($created_by)) {
                $this->db->where('order_details.created_by', $created_by);
            }

            if (isset($order_type) && !empty($order_type)) {
                if ($order_type == 'resware_orders') {
                    //$this->db->where('order_details.lp_file_number is null');
                    $this->db->where('order_details.file_number is not null');
                } else if ($order_type == 'lp_orders') {
                    $this->db->where('order_details.lp_file_number is not null');
                    //$this->db->where('order_details.file_number', 0);
                }

            }
            if (!empty($end_date)) {
                $this->db->where('order_details.created_at >=', date('Y-m-d H:i:s', strtotime($start_date)));
                $this->db->where('order_details.created_at <=', date('Y-m-d 23:59:59', strtotime($end_date)));
            }
            $this->db->select('order_details.lp_report_status, order_details.file_number, order_details.lp_file_number, order_details.file_id, order_details.ion_fraud_proceed_status, order_details.ion_fraud_required_status, property_details.allow_duplication, property_details.full_address,property_details.id as property_id,order_details.id,transaction_details.sales_representative,transaction_details.purchase_type,CONCAT(cbd.first_name, " ", cbd.last_name) as sales_rep_name,pct_order_product_types.product_type, customer_basic_details.first_name, customer_basic_details.last_name,order_details.created_at, pct_order_documents.document_name,tpd.email_sent_status')
                ->from('order_details')
                ->join('customer_basic_details', 'customer_basic_details.id = order_details.created_by', 'left')
                ->join('property_details', 'order_details.property_id = property_details.id')
                ->join('transaction_details', 'order_details.transaction_id = transaction_details.id')
                ->join('customer_basic_details as cbd', 'transaction_details.sales_representative = cbd.id', 'left')
                ->join('pct_order_title_point_data as tpd', 'order_details.file_id = tpd.file_id', 'left')
                ->join('pct_order_documents', 'order_details.id = pct_order_documents.order_id and pct_order_documents.is_pre_listing_report_doc=1', 'left')
                ->join('pct_order_product_types', 'transaction_details.purchase_type = pct_order_product_types.product_type_id AND pct_order_product_types.status=1');

            $this->db->order_by("order_details.id", "desc");
            if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $orders_lists = $query->result_array();
            }
        }
        // echo $this->db->last_query();exit;
        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_records,
            'data' => $orders_lists,
        );
    }
}
