<?php
class TitleOfficer extends CI_Model 
{
	function __construct() {
        // Set table name
        $this->table = 'customer_basic_details';
    }

    public function getTitleOfficerDetails($params)
    {
        $table = $this->table;

        $this->db->select('*,CONCAT(first_name, " ", last_name) as name');
        $this->db->from($table);
        $this->db->where('is_title_officer', 1);
        
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
            }
            else
            {
                $this->db->order_by('id', 'asc');
                if(array_key_exists("start",$params) && array_key_exists("limit",$params))
                {
                    $this->db->limit($params['limit'],$params['start']);
                }
                elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params))
                {
                    $this->db->limit($params['limit']);
                }
                $query = $this->db->get();              
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
        }
        // Return fetched data
        return $result;
    }
}