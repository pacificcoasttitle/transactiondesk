<?php
class Label_model extends CI_Model 
{
	function __construct() 
    {
        $this->table = 'pct_labels';
    }

    public function insert($data = array(),$table = '') 
    {
    	if ($table == '') {
        	$table = $this->table;
    	}
        
        if(!empty($data)) {
            $insert = $this->db->insert($table, $data);
            return $insert ? $this->db->insert_id() : false;
        }
        return false;
    }

    public function getData($condition=null)
    {
    	$table = $this->table;
        $this->db->select($table.'.*,customer_basic_details.first_name ,customer_basic_details.last_name');
        $this->db->from($table);

        if ($condition && is_array($condition)) {
	        foreach($condition as $key => $val){
	            $this->db->where($key, $val);
	        }
        }
        $this->db->join('customer_basic_details', "customer_basic_details.id = $table.sales_rep_id");
        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        if(!empty($result)) { 
            return $result;
        } else {
            return array();
        }
    }

    public function getSalesRepData($condition=null, $added_by = 0)
    {
    	$table = 'customer_basic_details';
        $this->db->select($table.'.*,count('.$this->table.'.id) as report_count');
        $this->db->from($table);
        if($condition && is_array($condition)) {

	        foreach($condition as $key => $val){
	            $this->db->where($key, $val);
	        }
        }
        $this->db->join($this->table, "$table.id = {$this->table}.sales_rep_id AND added_by = $added_by ",'LEFT');
        $this->db->group_by("$table.id");
        $this->db->order_by("$table.first_name");
        $query = $this->db->get();
        $result = $query->result_array();
        if(!empty($result)) { 
            return $result;
        } else {
            return array();
        }
    }

    public function update($data, $condition = array(), $table='') 
    {
        if(empty($table)) {
            $table = $this->table;
        }
        
        if(!empty($data)) {          
            $update = $this->db->update($table, $data, $condition);
            return $update?true:false;
        }
        return false;
    }

    public function delete_records($condition = array(), $table='') 
    {
    	if(empty($table)) {
            $table = $this->table;
        }

        $this->db->delete($table, $condition); 
    }

}
