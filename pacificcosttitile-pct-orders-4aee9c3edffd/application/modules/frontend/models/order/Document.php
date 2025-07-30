<?php
class Document extends CI_Model 
{
	function __construct() {
        // Set table name
        $this->table = 'pct_order_documents';
    }

    public function delete($data, $condition)
    {
        $table = $this->table;
        if(!empty($data))
        {   
            $this->db->select('*')
            ->from('pct_order_documents');
            $this->db->where($condition);
            // $this->db->where('is_pre_listing_report_doc', 1);
            $query = $this->db->get();
            if ($query->num_rows() > 0)  {
                // Delete data
                return $this->db->delete($table, $condition);
            }
        }
        return false;
    }
    public function update($data, $condition) 
    {
        $table = $this->table;

        if(!empty($data))
        {          
            
            $data['updated'] = date("Y-m-d H:i:s");

            // Update data
            $update = $this->db->update($table, $data, $condition);
            
            // Return the status
            return $update?true:false;
        }
        return false;
    }

    public function insert($data) 
    {
         $table = $this->table;
        if(!empty($data)){

            
            if(!isset($data['created'])) {
                $data['created'] = date("Y-m-d H:i:s");
            }

            // Insert data
            $insert = $this->db->insert($table, $data);
            
            // Return the status
            return $insert?$this->db->insert_id():false;
        }
        return false;
    }

    
    public function countCplDocument($orderId)
    {
        $this->db->select('*')
            ->from('pct_order_documents');
        $this->db->where('is_cpl_doc', 1);
        $this->db->where('order_id', $orderId);
        $query = $this->db->get();
        if ($query->num_rows() > 0)  {
            return $query->num_rows()+1;
        } else {
            return 1;
        }         
    }

    public function countProposedInsuredDocument($orderId)
    {
        $this->db->select('*')
            ->from('pct_order_documents');
        $this->db->where('is_proposed_insured_doc', 1);
        $this->db->where('order_id', $orderId);
        $query = $this->db->get();
        if ($query->num_rows() > 0)  {
            return $query->num_rows()+1;
        } else {
            return 1;
        }         
    }

    public function countBorrowerDocument($orderId)
    {
        $this->db->select('*')
            ->from('pct_order_documents');
        $this->db->where('is_borrower_doc', 1);
        $this->db->where('order_id', $orderId);
        $query = $this->db->get();
        if ($query->num_rows() > 0)  {
            return $query->num_rows()+1;
        } else {
            return 1;
        }         
    }
}
