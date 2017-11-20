<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Socket{
	
	public $socket ;

	public function __construct (){
		$this->ci = & get_instance ();
		$this->func = $this->ci->func;
	}
	
	public function createsocket($serverhost){
		if(($this->socket = stream_socket_client($serverhost, $errno, $errstr)) === false){
			throw new AppException("远程通信失败, 无法连接BAS服务器", "errno={$errno}, errstr={$errstr}");
		}
		return $this->socket;
	}

	public function sendpacket($packet){
		fwrite($this->socket, $packet);
	}
	
	public function interactpacket($requestpacket, $timeout){
		if($this->socket){
			fwrite($this->socket, $requestpacket);
			stream_set_timeout($this->socket,  1);
			$responsepacket=fread($this->socket, 1024);
			$result  =  stream_get_meta_data ($this->socket);
			if($result["timed_out"] || empty($responsepacket)){
				return false;
			}
			return $responsepacket;
		}
	}
}




?>