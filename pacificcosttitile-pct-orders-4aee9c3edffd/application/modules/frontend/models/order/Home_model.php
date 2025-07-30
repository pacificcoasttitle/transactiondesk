<?php
class Home_model extends CI_Model
{
    public function __construct()
    {
        // Set table name
        $this->table = 'customer_basic_details';
    }

    public function get_user($params = array())
    {
        $table = $this->table;
        // $this->db->select('*');
        $this->db->select('customer_basic_details.*, pc.title_officer_id, pc.sales_rep_id');
        $this->db->from($table);
        $this->db->join('pct_order_partner_company_info as pc', 'customer_basic_details.partner_id = pc.partner_id', 'left');
        foreach ($params as $key => $val) {
            $this->db->where('customer_basic_details.' . $key, $val);
        }
        $query = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }

    }

    public function get_customers($params = array(), $is_master_search = 0)
    {
        $table = $this->table;

        $this->db->select('customer_basic_details.*');
        $this->db->from($table);

        if (array_key_exists("where", $params)) {
            $this->db->select('pc.title_officer_id, pc.sales_rep_id');
            $this->db->join('pct_order_partner_company_info as pc', 'customer_basic_details.partner_id = pc.partner_id', 'left');
            foreach ($params['where'] as $key => $val) {
                if ($key == 'status') {
                    $this->db->where('customer_basic_details.status', $val);
                } else {
                    $this->db->where($key, $val);
                }
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
                } elseif (array_key_exists("name", $params) && array_key_exists("is_escrow", $params)) {
                    $this->db->select("CONCAT(first_name, ' ',last_name, ' - ',email_address) AS value, CONCAT(first_name, ' ',last_name) AS full_name");
                    $this->db->where('is_escrow', $params['is_escrow']);
                    $this->db->like('first_name', $params['name']);
                    $this->db->where('is_password_updated', 1);
                } elseif (array_key_exists("company_name", $params) && array_key_exists("is_escrow", $params)) {
                    if (isset($params['is_from_order_form']) && !empty($params['is_from_order_form'])) {
                        $this->db->select("CONCAT(first_name, ' ',last_name, ' - ',email_address) AS value, CONCAT(first_name, ' ',last_name) AS full_name");
                    } else {
                        $this->db->select("CONCAT(company_name, ' - ',CONCAT_WS(',', street_address, customer_basic_details.city, customer_basic_details.state, zip_code)) AS value, CONCAT(first_name, ' ',last_name) AS full_name");
                    }

                    $this->db->where('is_escrow', $params['is_escrow']);
                    $this->db->group_start()
                        ->like('company_name', $params['company_name'])
                        ->or_like("first_name", $params['company_name'])
                        ->or_like("email_address", $params['company_name'])
                        ->group_end();
                    $this->db->where('is_password_updated', 1);
                } elseif (array_key_exists("company_name", $params)) {
                    $this->db->select("CONCAT(company_name, ' - ',email_address) AS value");
                    if ($is_master_search == 1) {
                        $this->db->group_start()
                            ->like('company_name', $params['company_name'])
                            ->or_like("email_address", $params['company_name'])
                            ->group_end();
                    } else {
                        $this->db->like('company_name', $params['company_name']);
                    }
                    $this->db->where('is_password_updated', 1);

                }

                $query = $this->db->get();
                $result = ($query->num_rows() > 0) ? $query->result_array() : false;
            }
        }
        // Return fetched data
        return $result;
    }

    public function get_customer_number()
    {
        $query = $this->db->query("SELECT random_num
                        FROM (
                          SELECT FLOOR(1000 + ( RAND( ) *8999 )) AS random_num
                          UNION
                          SELECT FLOOR(1000 + ( RAND( ) *8999 )) AS random_num
                        ) AS customer_basic_details_plus_1
                        WHERE `random_num` NOT IN (SELECT customer_number FROM customer_basic_details)
                        LIMIT 1");

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function update($data, $condition = array(), $table = '')
    {
        if (empty($table)) {
            $table = $this->table;
        }

        if (!empty($data)) {

            $data['updated_at'] = date("Y-m-d H:i:s");

            // Update data
            $update = $this->db->update($table, $data, $condition);

            // Return the status
            return $update ? true : false;
        }
        return false;
    }

    public function insert($data = array(), $table = '')
    {
        // $table = $this->table;
        if (!empty($data)) {

            if (!isset($data['created_at'])) {
                $data['created_at'] = date("Y-m-d H:i:s");
            }

            // Insert data
            $insert = $this->db->insert($table, $data);

            // Return the status
            return $insert ? $this->db->insert_id() : false;
        }
        return false;
    }

    public function get_property_details($id)
    {
        $this->db->select('*');
        $this->db->from('property_details');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

    }

    public function check_product_type($id)
    {

    }

    public function get_product_types($params = array())
    {
        $table = 'pct_order_product_types';

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

    public function get_company_rows($params = array())
    {
        $table = 'pct_order_partner_company_info';
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
        return $result;
    }

    public function getSalesRepDetails($params)
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
                $this->db->order_by('first_name', 'asc');
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

    public function get_user_by_name($params = array())
    {
        $table = $this->table;
        $this->db->select('*');
        $this->db->from($table);

        $this->db->where('company_name', $params['company_name']);
        $this->db->like('first_name', $params['first_name']);
        $this->db->like('last_name', $params['last_name']);
        $this->db->where('is_password_updated', 1);

        $query = $this->db->get();
        $result = ($query->num_rows() > 0) ? $query->row_array() : false;
        // Return fetched data
        return $result;
    }

    public function get_rules_rows($params = array())
    {
        $table = 'pct_order_rules_manager';
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
        return $result;
    }

    public function get_counties_rows($params = array())
    {
        $table = 'pct_order_counties';
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
        return $result;
    }

    public function getEscrowOfficerDetails()
    {
        $this->db->select('*');
        $this->db->from('pct_order_partner_company_info');
        $this->db->like('partner_type_id', '10010');
        $this->db->where('status', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get_records($table, $condition = null, $order = null)
    {
        $this->db->from($table);
        if ($condition) {
            $this->db->where($condition);
        }
        if ($order) {
            foreach ($order as $order_key => $order_val) {
                $this->db->order_by($order_key, $order_val);
            }
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getLastFileNumberForLpOrders()
    {
        $this->db->select('*');
        $this->db->from('order_details');
        $this->db->where('lp_file_number is not null');
        $this->db->order_by('order_details.id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function getSectionWiseLPDocumentList($section)
    {
        $this->db->select('doc_type');
        $this->db->from('pct_lp_document_types');
        $this->db->where('display_in_section', $section);
        $this->db->where('is_display', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_lp_alert_list()
    {
        $lp_alert = array();

        if (isset($params['searchvalue']) && !empty($params['searchvalue'])) {
            $keyword = $params['searchvalue'];

            if (isset($keyword) && !empty($keyword)) {
                $this->db->group_start()
                    ->like("days", $keyword)
                    ->or_like('color_code', $keyword)
                    ->group_end();
            }
            $this->db->where('delete', 0);
            $this->db->order_by('days', 'asc');
            $query = $this->db->get('pct_lp_alert');
            if ($query->num_rows() > 0) {
                $lp_alert = $query->result_array();
            }
        } else {

            $this->db->where('delete', 0);
            $this->db->order_by('days', 'asc');
            $query = $this->db->get('pct_lp_alert');
            if ($query->num_rows() > 0) {
                $lp_alert = $query->result_array();
            }
        }
        return $lp_alert;
    }

    public function get_lp_alert_delete()
    {
        $lp_alert = array();
        $this->db->order_by('days', 'asc');
        $this->db->where('delete', 1);
        $query = $this->db->get('pct_lp_alert');
        if ($query->num_rows() > 0) {
            $lp_alert = $query->row_array();
        }
        return $lp_alert;
    }
}
