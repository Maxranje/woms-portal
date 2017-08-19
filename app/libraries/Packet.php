<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packet{

	public function __construct (){
		$this->ci = & get_instance ();
		$this->func = $this->ci->func;
	}

	public function init($ver, $chaporpap, $key, $userip){
		$this->chaporpap 	= $chaporpap == 'c' ? 0x00 : 0x01;	
		$this->ip 			= $this->func->iptobyte($userip);
		$this->reqid 		= array(0x00, 0x00);
		$this->key 			= $key;
	}

	public function createpacket($type, $username="admin", $password="admin", $timeout = ""){
		if($type == 0x01){
			$this->serialno = $this->func->randomSerialno();
			$basicarray =  array_merge(array(0x02, 0x01, $this->chaporpap, 0x00), $this->serialno,  $this->reqid, $this->ip, 
				array(0x00, 0x00), array(0x00, 0x00));
			$keyarray = $this->func->getBytesbyString($this->key);
			$this->authenticator = $this->func->getBytesbyString(md5($this->func->bytes2str(array_merge($basicarray, $this->func->getAuthenticator(), $keyarray)), true));
			$this->packet = $this->func->bytes2str(array_merge($basicarray, $this->authenticator));
			return $this->packet;
		}
		if($type == 0x03){
			$this->serialno = $this->func->randomSerialno();
			$username = $this->func->getBytesbyString($username);
			$password = $this->func->getBytesbyString($password);
			if($this->challenge){
				$password = array_merge(array($this->reqid[1]), $password, $this->challenge);
			}
			$password = $this->func->getBytesbyString(md5($this->func->bytes2str($password)));
			$basicarray =  array_merge(array(0x02, 0x03, $this->chaporpap, 0x00), $this->serialno,  $this->reqid, $this->ip, 
				array(0x00, 0x00), array(0x00, 0x02));
			$keyarray = $this->func->getBytesbyString($this->key);
			$attibute = array_merge(array(0x01, count($username)+2), $username, array(0x02, count($password)+2), $password);
			$this->authenticator = $this->func->getBytesbyString(md5($this->func->bytes2str(array_merge($basicarray, $this->func->getAuthenticator(), $attibute, $keyarray)), true));
			$this->packet = $this->func->bytes2str(array_merge($basicarray, $this->authenticator, $attibute));
			return $this->packet;
		}
		if($type == 0x05){
			$errorcode = $timeout == 0x00 ? 0x00 : 0x01; 
			$serialno = ( $errorcode == 0x00 ) ? $this->func->randomSerialno() : $this->serialno;
			$reqid = ($this->chaporpap == 0x00 && $errorcode == 0x01) ? $this->reqid : array(0x00, 0x00);
			$basicarray = array_merge(array(0x02, 0x05, $this->chaporpap, 0x00), $serialno, $reqid, $this->ip, 
				array(0x00, 0x00), array(0x00, 0x00));
			$keyarray = $this->func->getBytesbyString($this -> key);
			$this -> authenticator = $this->func->getBytesbyString(md5($this->func->bytes2str(array_merge($basicarray, $this->func->getAuthenticator(), $keyarray)), true));
			$this -> packet = $this->func->bytes2str(array_merge($basicarray, $this -> authenticator));
			return $this -> packet;
		}
		if($type == 0x07){
			$basicarray =  array_merge(array(0x02, 0x07, $this -> chaporpap, 0x00), $this->serialno, $this -> reqid, $this->ip, 
				array(0x00, 0x00), array(0x00, 0x00));
			$keyarray = $this->func->getBytesbyString($this -> key);
			$this -> authenticator = $this->func->getBytesbyString(md5($this->func->bytes2str(array_merge($basicarray, $this->func->getAuthenticator(), $keyarray)), true));
			$this -> packet = $this->func->bytes2str(array_merge($basicarray, $this -> authenticator));
			return $this -> packet;
		}
	}
	
	public function checkresponsepacket($packet, $type){
		$packet = unpack("C*", $packet);
		if($packet[2] != $type){
			echo "检测类型是否匹配 <br>";
			return false;
		}
		if(($packet[5] != $this->serialno[0]) || ($packet[6] != $this->serialno[1])){
			echo "检测serialno与发送的是否匹配 <br>";
			return false;
		}
		if($packet[2] == 0x04){
			if(($packet[7] != $this -> reqid[0]) || ($packet[8] != $this -> reqid[1])){
				echo "如果是响应认证包， 检测reqid 是否与发送的匹配 <br>";
				return false;
			}
		}
		
		$authpacket = $packet;
		$responseauth = $this->func->bytes2str(array_splice($authpacket, 16, 16, $this -> authenticator));
		$authenticator = md5($this->func->bytes2str(array_merge($authpacket, $this->func->getBytesbyString($this -> key))), true);
		
		if($authenticator != $responseauth){
			echo "校验字不对  <br>";
			return false;
		}
		
		if($packet[2] == 0x02){
			$this -> reqid = array($packet[7], $packet[8]);
		}
		if($packet[2] == 0x02 && $packet[16] == 0x01){
			$this -> challenge = array_slice($packet, 33, 16);
		}
		return $packet;
	}
}

?>