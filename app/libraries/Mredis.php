<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mredis {

	public function __construct (){
		$this->ci = & get_instance ();
		$this->init();
	}

	public function init (){
		$this->redis = new redis();
		$this->redis->connect('127.0.0.1', 6379);
	}

	public function update_ping_info ($apid, $info){
		$this->redis->hset("ping", $apid, $info);
	}

}