<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome_model extends CI_Model
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_state_list($value = '')
    {
        $this->db->distinct();
        $this->db->select('region');
        $query = $this->db->get('pctc_county_mst');
        return $query->result();
    }
    
    
    public function get_county_list($value = '')
    {
        $this->db->distinct();
        $this->db->select('zone_name,region,zone_id,transaction_type');
        $query = $this->db->get('pctc_county_mst');
        return $query->result();
    }
    
    public function get_county_detail($closing_county)
    {
        $this->db->where('county_id_pk', $closing_county);
        $query = $this->db->get('pctc_county_mst');
        return $query->row();
    }
    
    public function get_city_list($value = '')
    {
        $query = $this->db->get('pctc_county_mst');
        return $query->result();
    }
    
    
    public function generate_quote()
    {
        
        $skip                        = array(
            ",",
            "$"
        );
        $_POST['transaction_amount'] = str_replace($skip, "", $_POST['transaction_amount']);
        $_POST['loanamount']         = str_replace($skip, "", $_POST['loanamount']);
        $_POST['is_lender_policy']         = trim($_POST['is_lender_policy']);

        $row                         = array(
            "quote_name" => "quote",
            "quote_email" => "demo@demo.com",
            "region" => $_POST['region'],
            "zone_name" => $_POST['county'],
            "county_id_fk" => $_POST['city'],
            "is_all_rates" => $_POST['all-fees'],
            "is_title_rate" => $_POST['title-rates'],
            "is_escrow_rate" => $_POST['escrow-rates'],
            "is_endorsement" => $_POST['endorsements'],
            "is_recording" => $_POST['recording-fees'],
            "is_same_county" => $_POST['same_county'],
            "txn_type" => $_POST['transaction_type'],
            "sale_amount" => $_POST['transaction_amount'],
            "loan_amount" => $_POST['loanamount'],
            "quote_date" => date("Y-m-d g:i:s"),
            "policy_type" => $_POST['policy_type'],
            "is_resi_escrow_service" => $_POST['eight'],
            "is_mobile_signin" => $_POST['nine'],
            "is_notary_fee" => $_POST['eleven'],
            "is_recording_service_fee" => $_POST['twelve'],
            "is_new_loan" => $_POST['ten'],
            "no_of_mobile_signin" => $_POST['no_of_mobile_signin'],
            "closing_county" => $_POST['closing_county'],
            "closing_zone" => $_POST['closing_zone'],
            "is_lender_policy" => $_POST['is_lender_policy']
        );
        
        $this->db->insert('pctc_quotes', $row);
        $quote_id = $this->db->insert_id();
        
        foreach ($_POST['endo_sel'] as $key) {
            $row = array(
                'endorse_id_ck' => $key,
                'quote_id_ck' => $quote_id
            );
            $this->db->insert('pctc_quote_endorsements', $row);
        }
        return $quote_id;
    }
    
    public function get_quote_detail($quote_id = '')
    {
        $this->db->select('pctc_quotes.*, pctc_county_mst.county_name');
        $this->db->where('quote_id_pk', $quote_id);
        $this->db->join('pctc_county_mst', 'pctc_county_mst.county_id_pk = pctc_quotes.county_id_fk', 'left');
        $query = $this->db->get('pctc_quotes');
        return $query->row();
    }
    public function get_title_rate_loan($quote_id)
    {
        
        $row = $this->get_quote_detail($quote_id);
        if ($row->txn_type == "Resale") {
            $this->db->where('min_range <=', $row->loan_amount);
            $this->db->where('max_range >=', $row->loan_amount);
            $this->db->where('status =', 1);
            $query = $this->db->get('pctc_title_rates');
            return $query->row();
        }
    }
    
    public function get_title_rate($quote_id)
    {
        
        $row = $this->get_quote_detail($quote_id);
        if ($row->txn_type == "Resale") {
            $this->db->where('status =', 1);
            $this->db->where('min_range <=', $row->sale_amount);
            $this->db->where('max_range >=', $row->sale_amount);
            $query = $this->db->get('pctc_title_rates');
            return $query->row();
        }
        if ($row->txn_type == "Re-Finance") {
            if ($this->session->userdata('mpusertype')) {
                if ($this->session->userdata('mpusertype') == 1) {
                    $this->db->where('status =', 1);
                    $this->db->where('min_range <=', $row->loan_amount);
                    $this->db->where('max_range >=', $row->loan_amount);
                    $query = $this->db->get('pctc_title_rates');
                    return $query->row();
                    
                } else {
                    $this->db->where('min_range <=', $row->loan_amount);
                    $this->db->where('max_range >=', $row->loan_amount);
                    $this->db->where('membership_id_fk', $this->session->userdata('mpusertype'));
                    $query = $this->db->get('pctc_members_escrow');
                    if ($query->num_rows()) {
                        return $query->row();
                    } else {
                        $this->db->where('membership_id_fk', $this->session->userdata('mpusertype'));
                        $this->db->where('max_range IS NULL', null, false);
                        $query = $this->db->get('pctc_members_escrow');
                        return $query->row();
                    }
                }
            } else {
                $this->db->where('status =', 1);
                $this->db->where('min_range <=', $row->loan_amount);
                $this->db->where('max_range >=', $row->loan_amount);
                $query = $this->db->get('pctc_title_rates');
                return $query->row();
            }   
        }
    }
    
    
    public function get_cfpb_title_rate($quote_id)
    {
        $row = $this->get_quote_detail($quote_id);
        $this->db->where('min_range <=', $row->loan_amount);
        $this->db->where('max_range >=', $row->loan_amount);
        $this->db->where('status =', 1);
        $query = $this->db->get('pctc_title_rates');
        return $query->row();
    }
    
    
    public function calculate_escrow_fee_resale($quote_id)
    {
        $row = $this->get_quote_detail($quote_id);
        if ($row->is_same_county == 1) {
            $this->db->where('county_id_fk', $row->county_id_fk);
        } else {
            $this->db->where('county_id_fk', $row->closing_county);
        }
        $this->db->where('min_range <=', $row->sale_amount);
        $this->db->where('max_range >=', $row->sale_amount);
        $query = $this->db->get('pctc_escrow_resale');
        if ($query->num_rows()) {
            return $query->row();
        } else {
            if ($row->is_same_county == 1) {
                $this->db->where('county_id_fk', $row->county_id_fk);
            } else {
                $this->db->where('county_id_fk', $row->closing_county);
            }
            $this->db->where('max_range IS NULL', null, false);
            $query = $this->db->get('pctc_escrow_resale');
            return $query->row();
        }
        
    }
    
    
    public function calculate_escrow_fee_refinance($quote_id)
    {
        $row = $this->get_quote_detail($quote_id);
        
        if ($row->is_same_county == 1) {
            $this->db->where('county_id_fk', $row->county_id_fk);
            
        } else {
            $this->db->where('county_id_fk', $row->closing_county);
        }
        $this->db->where('min_range <=', $row->loan_amount);
        $this->db->where('max_range >=', $row->loan_amount);
        $query = $this->db->get('pctc_escrow_refinance');
        if ($query->num_rows()) {
            return $query->row();
        } else {
            if ($row->is_same_county == 1) {
                $this->db->where('county_id_fk', $row->county_id_fk);
            } else {
                $this->db->where('county_id_fk', $row->closing_county);
            }
            $this->db->where('max_range IS NULL', null, false);
            $query = $this->db->get('pctc_escrow_refinance');
            return $query->row();
        }
        
        
    }
    
    
    public function get_endorsement_fee($quote_id)
    {
        $this->db->select('pctc_quote_endorsements.*,pctc_endorsement_fee.*');
        $this->db->join('pctc_endorsement_fee', 'pctc_endorsement_fee.endorse_fee_id_fk = pctc_quote_endorsements.endorse_id_ck', 'left');
        $this->db->where('quote_id_ck', $quote_id);
        $query = $this->db->get('pctc_quote_endorsements');
        return $query->result();
    }
    
    public function get_endorsement_options($txn_type)
    {
        $this->db->where('txn_type', $txn_type);
        $query = $this->db->get('pctc_endorsement_fee');
        return $query->result();
    }
    
    public function get_login_user($email, $password)
    {
        $this->db->select('pctc_user_mst.*,pctc_membership.membership');
        $this->db->where('email', $email);
        $this->db->where('p_word', md5($password));
        $this->db->join('pctc_membership', 'pctc_membership.membership_id_pk = pctc_user_mst.membership_id_fk', 'left');
        $query = $this->db->get('pctc_user_mst');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    
    public function get_admin_user($email, $password)
    {
        $this->db->select('*');
        $this->db->where('email_id', $email);
        $this->db->where('password', md5($password));
        $query = $this->db->get('admin');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    public function get_all_users()
    {
        $query = $this->db->get('pctc_user_mst');
        return $query->result();
    }
    
    public function get_reps_data()
    {
        $this->db->order_by('rep_key', 'asc');
        $query = $this->db->get('pdb_rep_mst');
        return $query->result();
    }
    
    public function get_all_roles()
    {
        $this->db->select('*');
        $this->db->where('status', 1);
        $query = $this->db->get('pctc_membership');
        return $query->result();
    }
    
    
    public function check_email_exist($email)
    {
        $this->db->select('*');
        $this->db->where('email', $email);
        $query = $this->db->get('pctc_user_mst');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    public function update_user_role($value = '')
    {
        $i1 = array(
            'membership_id_fk' => $_POST['membership_id_fk']
        );
        $this->db->where('user_id_pk', $_POST['user_id_pk']);
        $this->db->update('pctc_user_mst', $i1);
    }
    
    
    public function insert_user($data)
    {
        $this->db->insert('pctc_user_mst', $data);
        return $this->db->insert_id();
    }
    
    
    public function reset_verification($data, $pass)
    {
        $i1 = array(
            'verification' => $pass
        );
        $this->db->where('email', $data);
        $this->db->update('pctc_user_mst', $i1);
        
    }
    
    public function reset_password($data, $pass)
    {
        
        $i1 = array(
            'p_word' => md5($pass)
        );
        $this->db->where('email', $data);
        $this->db->where('verification', $_POST['verification']);
        $this->db->update('pctc_user_mst', $i1);
        return $this->db->affected_rows();
        
    }
    
    
    public function reset_user_pass($user, $pass)
    {
        
        $i1 = array(
            'p_word' => md5($pass)
        );
        $this->db->where('user_id_pk', $user);
        $this->db->update('pctc_user_mst', $i1);
        return $this->db->affected_rows();
        
    }
    
    public function get_user_detail($user)
    {
        $this->db->select('pctc_user_mst.*,pctc_membership.membership');
        $this->db->where('user_id_pk', $user);
        $this->db->join('pctc_membership', 'pctc_membership.membership_id_pk = pctc_user_mst.membership_id_fk', 'left');
        $query = $this->db->get('pctc_user_mst');
        return $query->row();
        
    }
    
    
    public function verify_registration($user_id, $unique)
    {
        $this->db->select('*');
        $this->db->where('user_id_pk', $user_id);
        $this->db->where('verification', $unique);
        $query = $this->db->get("pctc_user_mst");
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return 0;
        }
    }
    
    public function update_verfication($id, $unique)
    {
        $data = array(
            'is_email_confirmed' => "1",
            'is_active' => 1
        );
        $this->db->where('user_id_pk', $id);
        $this->db->where('verification', $unique);
        $this->db->update("pctc_user_mst", $data);
        if ($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }

    public function get_escrow_refinance($county,$loan_amount)
    {
        if (isset($county) && !empty($county)) 
        {
            $this->db->where('county', $county);
        }

        $this->db->where('status =', 1);
        $this->db->where('min_range <=', $loan_amount);
        $this->db->where('max_range >=', $loan_amount);
        $query = $this->db->get('pctc_escrow_refinance');

        if ($query->num_rows()) {
            return $query->row_array();
        }
        else
        {
            if (isset($county) && !empty($county)) 
            {
                $this->db->where('county', $county);
            }

            $this->db->where('status =', 1);
            $this->db->where('min_range <=', $loan_amount);
            $this->db->where('max_range IS NULL', null, false);

            $query = $this->db->get('pctc_escrow_refinance');
            return $query->row_array();
        }
    }

    public function get_additional_fees($txn_type,$rate_type)
    {
        if(isset($txn_type) && !empty($txn_type))
        {
            $this->db->where('transaction_type =', $txn_type);
        }

        if(isset($rate_type) && !empty($rate_type))
        {
            $this->db->where('parent_name =', $rate_type);
        }
        $this->db->where('status =', 1);
        $query = $this->db->get('pctc_fees');
        return $query->result_array();
    }

    public function get_escrow_resale($county, $sale_amount)
    {
       
        if (isset($county) && !empty($county)) 
        {
            $this->db->where('county', $county);
        }

        $this->db->where('min_range <=', $sale_amount);
        $this->db->where('max_range >=', $sale_amount);
        $this->db->where('status =', 1);
        $query = $this->db->get('pctc_escrow_resale');
        
        if ($query->num_rows()) 
        {
            return $query->row_array();
        } 
        else 
        {
            if (isset($county) && !empty($county)) 
            {
                $this->db->where('county', $county);
            }
            $this->db->where('status =', 1);
            $this->db->where('min_range <=', $sale_amount);
            $this->db->where('max_range IS NULL', null, false);

            $query = $this->db->get('pctc_escrow_resale');
            return $query->row_array();
        }
        
    }  
}

/* End of file welcome_model.php */
/* Location: ./application/models/welcome_model.php */