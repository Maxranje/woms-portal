<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AppException extends Exception {

	public function __construct ($errmsg, $assist = "", $cid=0, $apid=0, $writedb=true){
		parent::__construct ($errmsg);
		$this->errmsg = '['.$_SERVER['REMOTE_ADDR'].'] Error on'.$this->getFile().' line:'.$this->getLine().'['.$errmsg.']['.$assist.']';
		$this->apid = $apid ;
		$this->cid = $cid ;
		$this->writedb = $writedb;
	}

	public function log_message (){
		if($this->writedb){
			$CI = CI_Controller::get_instance();
			$CI->load->database();
			$sql = "insert into errmsg (`cid`, `apid`, `errmsg`, `createtime`) values (?,?,?,?)";
			$CI->db->query($sql, array($this->cid, $this->apid, $this->errmsg, time()));		
		}
	}

	public function getNewMessage (){
		return $this->errmsg ;
	}

	public function getOldMessage (){
		return parent::getMessage();
	}
}