<?php
class RulesManager_model extends CI_Model 
{
	function __construct() {
        // Set table name
        $this->table = 'pct_order_rules_manager';
    }

    public function update($data, $condition = array()) 
    {
    	$table = $this->table;

        if(!empty($data))
        {          
            
            $data['updated_at'] = date("Y-m-d H:i:s");

            // Update data
            $update = $this->db->update($table, $data, $condition);
            
            // Return the status
            return $update?true:false;
        }
        return false;
    }

    public function insert($data = array()) 
    {
    	$table = $this->table;
        if(!empty($data)){

        	$data['created_at'] = date("Y-m-d H:i:s");

            // Insert data
            $insert = $this->db->insert($table, $data);
            
            // Return the status
            return $insert?$this->db->insert_id():false;
        }
        return false;
    }

    public function getRules($params)
    {
        $this->db->from($this->table);
        $total_records =  $this->db->count_all_results();


        $limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        
        
        $rules_lists =array();
        if(isset($params['searchvalue']) && !empty($params['searchvalue']))
        {
            $keyword = $params['searchvalue'];

            if(isset($keyword) && !empty($keyword))
            {
                $this->db->like('value', $keyword);
            }
            
               
            $this->db->from($this->table);
            $filter_total_records =  $this->db->count_all_results();


            if(isset($keyword) && !empty($keyword))
            {
                $this->db->like('value', $keyword);
            }

            if((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset)))
            {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('id', 'desc');
                    
            $query = $this->db->get($this->table);

            if ($query->num_rows() > 0) 
            {
                $rules_lists = $query->result_array();
            }
        }
        else
        {
            
            $this->db->from($this->table);

            $filter_total_records =  $this->db->count_all_results();
            
            if((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset)))
            {
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('id', 'desc');
            $query = $this->db->get($this->table);
            
            if ($query->num_rows() > 0) 
            {
                $rules_lists = $query->result_array();
            } 
        }

        return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $rules_lists
        );
    }

}