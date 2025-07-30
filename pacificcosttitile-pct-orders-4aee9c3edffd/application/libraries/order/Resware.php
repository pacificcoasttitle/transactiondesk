<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Resware
{
    public static $CI;

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->CI->load->library('email');
        $this->CI->load->library('session');
        self::$CI = $this->CI;
    }

    public function make_request($http_method, $endpoint, $body_params = '', $data = array())
    {
        $userdata = $this->CI->session->userdata('user');
        if (isset($data['admin_api']) && $data['admin_api'] == 1) {
            $this->CI->load->library('order/order');
            $credResult = $this->CI->order->get_resware_admin_credential();
            $login = $credResult['username'];
            $password = $credResult['password'];
        } else if ((isset($userdata['is_master']) && !empty($userdata['is_master'])) || isset($data['from_mail']) && !empty($data['from_mail'])) {
            $login = isset($data['email']) && !empty($data['email']) ? $data['email'] : '';
            $password = isset($data['password']) && !empty($data['password']) ? $data['password'] : '';
        } else {
            $login = $userdata['email'];
            $password = $userdata['random_password'];
        }

        $ch = curl_init(env('RESWARE_ORDER_API') . $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body_params))
        );
        $error_msg = curl_error($ch);
        $result = curl_exec($ch);
        return $result;
    }

}
