<?php

namespace App\Services\Sms;

class Sms
{
    protected $api_url = 'http://172.16.2.234/api/messages';

    public function __construct()
    {

    }

    public function send($phonenumber, $message)
    {
    	$sms_key = config('services.telvidasms.key');
        $phonenumber = starts_with($phonenumber, '+') ? $phonenumber : '+' . $phonenumber;
        $data = json_encode(['recipient' => $phonenumber, 'content' => $message]);

        $ch = curl_init($this->api_url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8", "Accept: application/json", "X-Auth-Key: $sms_key"));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	    $response = curl_exec($ch);

	    return $response;
    }
}