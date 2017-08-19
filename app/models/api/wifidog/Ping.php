<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ping extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		//$this->load->library('mredis');
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete');
	}
	public function index (){
		$gw_id				= $this->input->get('gw_id');
		$sys_uptime 		= $this->input->get('sys_uptime');
		$sys_memfree 		= $this->input->get('sys_memfree');
		$sys_load 			= $this->input->get('sys_load');
		$wifidog_uptime 	= $this->input->get('wifidog_uptime');
		$mcode 				= $this->input->get('mcode');

		if(!$gw_id || !$sys_uptime || !$sys_memfree) {
			echo 'Auth: -1';
			exit(0);
		}

		try{
			$query="select a.*, ac.* from ap a, apconfig ac where a.apname = ? and a.apid = ac.apid";
			$res = $this->db->query($query, array($gw_id));
			if(!$res || $res->num_rows() == 0){
				throw new AppException("热点ID不正确");
			}
			$ap = $res->row_array();
			$lic=$ap['lic'];
			if(!empty($lic) && $mcode != $lic){
				throw new AppException("机器码不正确");
			}
			$clientip = $_SERVER['REMOTE_ADDR'];
			if($clientip != $ap['wanip'])
			{
				$query="update ap set wanip= ? where apname=?";
				$res = $this->db->query($query, array($clientip, $gw_id));
			}
			$t = time();
			$t = $t-50;
			// $info = implode(" ", array($t, $sys_uptime, $sys_memfree, $sys_load, $wifidog_uptime));
			// $this->mredis->update_ping_info($ap['apid'], $info);

			$query="insert into apstatus (`apid`,`time`,`sys_uptime`,`sys_memfree`,`sys_load`,`wifidog_uptime`) values (?,?,?,?,?,?)";
			$this->db->query($query, array($ap['apid'], $t, $sys_uptime, $sys_memfree, $sys_load, $wifidog_uptime));

			$query = "update ap set hearttime = ? where apid = ?";
			$this->db->query($query, array($t, $ap['apid']));

			echo 'Pong'."\n";
				
		}catch(AppException $aex){
			$aex->log_message();
		}catch(Exception $ex){
			$errmsg='取登录页面发生错误';
			echo $errmsg;
		}
	}
}
?>