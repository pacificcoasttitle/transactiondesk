<?php
class Agent_model extends CI_Model 
{
	function __construct() {
        // Set table name
        $this->table = 'agents';
    }

    public function get_agents($params = array())
    {
    	$table = $this->table;

        $this->db->select('*');
        $this->db->from($table);
        
        if(array_key_exists("where", $params)){
            foreach($params['where'] as $key => $val){
                $this->db->where($key, $val);
            }
        }
        
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
            $result = $this->db->count_all_results();
        }else{
            if(array_key_exists("id", $params)){
                $this->db->where('id', $params['id']);
                $query = $this->db->get();
                $result = $query->row_array();
            }elseif(array_key_exists("name", $params))
            {
            	$this->db->select('CONCAT(name," - ",email_address) AS value');
                $this->db->like('name', $params['name']);
                $query = $this->db->get();
                $result = $query->result_array();
            }else{
                $this->db->order_by('id', 'asc');
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit'],$params['start']);
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit']);
                }
                
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
        }
        
        // Return fetched data
        return $result;
    }

    public function update($data, $condition = array(), $table='') 
    {
        if(empty($table)) {
            $table = $this->table;
        }
        
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
        if (!empty($data)) {
            if(!isset($data['created_at'])) {
                $data['created_at'] = date("Y-m-d H:i:s");
            }
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }
        return false;
    }
}