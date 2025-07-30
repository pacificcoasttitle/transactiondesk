<?php
class TitlePointData extends CI_Model 
{
	function __construct() {
        // Set table name
        $this->table = 'pct_order_title_point_data';
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

    public function gettitlePointDetails($params)
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
            }
            else if(array_key_exists("session_id", $params)){
                $this->db->where('session_id', $params['session_id']);
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

    public function getInstrumentDetails($fileNumber)
    {
        $table = $this->table;

        $this->db->select('pct_title_point_document_records.*')
            ->from($table)
            ->join('pct_title_point_document_records', 'pct_order_title_point_data.id = pct_title_point_document_records.title_point_id', 'left');
        $this->db->where('pct_order_title_point_data.file_number', $fileNumber);
        $this->db->where('pct_title_point_document_records.is_display', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getVestingInstrumentDetails($fileNumber)
    {
        $table = $this->table;

        $this->db->select('pct_title_point_document_records.*, pct_order_title_point_data.fips')
            ->from($table)
            ->join('pct_title_point_document_records', 'pct_order_title_point_data.id = pct_title_point_document_records.title_point_id');
        $this->db->where('pct_order_title_point_data.file_number', $fileNumber);
        $this->db->where_in('pct_title_point_document_records.document_type', ['AFD','AFF','DCD','DCR','DDU','DEE','DEF','DED','DEJ','DEQ','DEW','DEX','DQT','JTY','ORS','SWD','TDR','CFS','CMP','DTH','FCL','TSC','QUI']);
        $this->db->order_by('pct_title_point_document_records.id',"desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSelectedVestingInstrumentDetails($fileNumber)
    {
        $table = $this->table;

        $this->db->select('pct_title_point_document_records.*, pct_order_title_point_data.fips')
            ->from($table)
            ->join('pct_title_point_document_records', 'pct_order_title_point_data.id = pct_title_point_document_records.title_point_id');
        $this->db->where('pct_order_title_point_data.file_number', $fileNumber);
        $this->db->where('pct_title_point_document_records.is_ves_display', 1);
        $this->db->order_by('pct_title_point_document_records.id',"desc");
        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        return $query->result_array();
    }

    public function getLatestGrantDeedInstrumentDetails($fileNumber)
    {
        /** Get selected ves doc type */
        $this->db->select('doc_type')->from('pct_lp_document_types');
        $this->db->where('is_ves', 1);
        $query = $this->db->get();
        $result = $query->result_array();
		$isVesDocType = array_map (function($value){
			return $value['doc_type'];
		} , $result);

        /** End Get selected ves doc type */
        
        $table = $this->table;

        $this->db->select('pct_title_point_document_records.*, pct_order_title_point_data.cs4_instrument_no, pct_order_title_point_data.cs4_recorded_date, pct_order_title_point_data.fips, pct_order_title_point_data.file_id, pct_order_title_point_data.file_number')
            ->from($table)
            ->join('pct_title_point_document_records', 'pct_order_title_point_data.id = pct_title_point_document_records.title_point_id', 'left');
        $this->db->where('pct_order_title_point_data.file_number', $fileNumber);
        $this->db->where_in('pct_title_point_document_records.document_type', $isVesDocType)->order_by("recorded_date desc, instrument desc")->limit(1);
        // $this->db->where('pct_title_point_document_records.document_name', 'Grant Deed')->order_by('id',"desc")->limit(1);
        $query = $this->db->get();
        return $query->result_array();
    }
}

?>