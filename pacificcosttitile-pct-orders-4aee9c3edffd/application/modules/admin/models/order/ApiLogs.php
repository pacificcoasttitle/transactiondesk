<?php
class ApiLogs extends CI_Model 
{
	function __construct() {
        // Set table name
        $this->table = 'pct_order_api_logs';
    }

    public function syncLogs($user_id, $api_type, $request_type, $request_url, $request_data, $response_data, $order_id = 0, $logId = 0) 
    {
        if(is_array($request_data)) {
            $request_data = json_encode($request_data, true);
        }
        if ($logId == 0) {
            $data = array(
                'user_id' => $user_id,
                'order_id' => $order_id ? $order_id : 0,
                'api_type' => $api_type,
                'request_type' => $request_type,
                'request_data' => !empty($request_data) ? $request_data : '',
                'request_url' => $request_url,
                'created' => date('Y-m-d H:i:s')
            );
            if (getenv('API_LOGS_ENABLE') == 1) {
                $this->db->insert($this->table, $data);
                return $this->db->insert_id();
            } else {
                return 1;
            }
        } else {
            if (is_array($response_data)) {
                $response_data = json_encode($response_data, true);
            }
            $data = array(
                'response_data' => !empty($response_data) ? $response_data : '',
                'updated' => date('Y-m-d H:i:s'),
            );
            if (getenv('API_LOGS_ENABLE') == 1) {
               $this->db->update($this->table, $data, array('id' => $logId));
            }
        }
    }
}
