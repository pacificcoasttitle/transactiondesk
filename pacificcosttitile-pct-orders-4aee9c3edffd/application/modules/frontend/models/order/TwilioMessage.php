<?php
class TwilioMessage extends CI_Model 
{
	function __construct() {
        // Set table name
        $this->table = 'pct_order_twilio_message_records';
    }

    public function update($data, $condition) 
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

    public function insert($data) 
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
}