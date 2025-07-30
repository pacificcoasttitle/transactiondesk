<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Survey
{
    public static $CI;
    
    public function __construct($params = array())
    {
        $this->CI =& get_instance();                        
        $this->CI->load->database();
        $this->CI->load->library('email');
        $this->CI->load->library('session');
        self::$CI = $this->CI;
    }
    
    public function make_request($http_method, $endpoint, $body_params='', $data = array())
    {
        $login = env('RESWARE_ORDER_API');
        $password = env('RESWARE_ORDER_API');
        $bearerToken = env('SURVEYMONKEY_AUTH_TOKEN');
	    $ch = curl_init(env('SURVEYMONKEY_API_URL').$endpoint);                                    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);                        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body_params);                   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $bearerToken,
            'Content-Length: ' . strlen($body_params))
        );
        $error_msg = curl_error($ch);
        $result = curl_exec($ch);
        return $result;
    }
    
}
