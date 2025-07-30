<?php
class OrderRecording extends CI_Model 
{
	function __construct() {
        // Set table name
        $this->table = 'pct_order_recordings';
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

    public function insert($data = array(),$table='') 
    {
        // $table = $this->table;
        if(!empty($data)){

            $data['created_at'] = date("Y-m-d H:i:s");

            // Insert data
            $insert = $this->db->insert($table, $data);
            
            // Return the status
            return $insert?$this->db->insert_id():false;
        }
        return false;
    }

    public function get_recordings($params)
    {
        $table = $this->table;
    	$this->db->from($table);
		$total_records =  $this->db->count_all_results();
		$limit = isset($params['length']) && !empty($params['length']) ? $params['length'] : '';
        $offset = isset($params['start']) && !empty($params['start']) ? $params['start'] : '';
        $recording_lists = array();
        $this->db->from($table);
        $filter_total_records =  $this->db->count_all_results();

        if ((isset($limit) && !empty($limit)) || (isset($offset) && !empty($offset))) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get($table);

        if ($query->num_rows() > 0)  {
            $recording_lists = $query->result_array();
        } 
    
    	return array(
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filter_total_records,
            'data' => $recording_lists
        );
    }

}