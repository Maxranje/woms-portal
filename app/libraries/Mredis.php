<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mredis {

	public function __construct (){
		$this->ci = & get_instance ();
	}

	public function init (){
		$this->redis = new redis();
		$this->redis->connect('127.0.0.1', 6379);
	}

	public function dev_ping ($apid, $info){
		$this->redis->hset("devping", $apid, $info);
	}

	# 返回
	public function getuserinfo ($uid){
		return $this->redis->hget('authuser', $uid);
	}
	public function setuserinfo ($uid, $info){
		return $this->redis->hset('authuser', $uid, $info);
	}

	public function getuserid ($mac) {
		return $this->redis->hget('usermapping', $mac);
	}

	public function setuserid ($mac, $id){
		$this->redis->hset('usermapping', $mac, $id);
	}

}