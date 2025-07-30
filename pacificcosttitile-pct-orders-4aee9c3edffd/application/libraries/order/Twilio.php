<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Twilio
{
	/**
     * @var string
     */
    protected $sid;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var bool
     */
    protected $sslVerify;

    /**
     * @var \Twilio\Rest\Client
     */
     protected $twilio;

    /**
     * @param string $token
     * @param string $from
     * @param string $sid
     * @param bool $sslVerify
     */
    public function __construct($sslVerify = true)
    {
        $this->sid = env('TWILIO_SID');
        $this->token = env('TWILIO_TOKEN');
        // $this->from = (env('TWILIO_MODE') == 'production') ? env('TWILIO_FROM') : env('TWILIO_TEST_FROM');
        $this->sslVerify = $sslVerify;
    }

    /**
     * @return \Twilio\Rest\Client
     */
    public function getTwilio()
    {
        if ($this->twilio) {
            return $this->twilio;
        }

        return $this->twilio = new Twilio\Rest\Client($this->sid, $this->token);
    }

    public function message($to, $message, $mediaUrls = null, array $params = [])
    {
        $params['body'] = $message;

        if (!isset($params['from'])) {
            $params['from'] = $this->from;
        }

        if (!empty($mediaUrls)) {
            $params['mediaUrl'] = $mediaUrls;
        }

        return $this->getTwilio()->messages->create($to, $params);
    }
}