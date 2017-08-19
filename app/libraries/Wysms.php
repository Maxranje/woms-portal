<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wysms {

	public function init ($key, $secret, $tmplateid){
		$this->key = $key;
		$this->secret = $secret;
		$this->tmplateid = $tmplateid;
	}

	public function sendsms ($phone, $code){	
		$str = '0123456789abcdef';
		$nonce = '';
		foreach(range(1, 16) as $i){
			$nonce .= $str[rand(0, 15)];
		}
		$curltime = time();
		$checksum = sha1($this->secret.$nonce.$curltime);

		$data= array(
			'templateid' => intval($this->tmplateid),
			'mobiles' => json_encode(array($phone)),
			'params' => json_encode(array($code))
		);

		$data = http_build_query($data);

		$opts = array (
			'http' => array(
				'method' => 'POST',
				'header' => array(
					'Content-Type:application/x-www-form-urlencoded;charset=utf-8',
					"AppKey:".$this->key,
					"Nonce:$nonce",
					"CurTime:$curltime",
					"CheckSum:$checksum"
				),
				'content' =>  $data
			),
		);

		$context = stream_context_create($opts);
		$flag = file_get_contents("https://api.netease.im/sms/sendtemplate.action", false, $context);
		$json = json_decode($flag, true);
		return $json['code'];
	}	

}
