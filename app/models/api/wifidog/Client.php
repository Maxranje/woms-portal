<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Client extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete');
	}
	public function index (){
		$gw_id				= $this->input->get('gw_id');
		$user_list  		= $this->input->get('user_list');

		if(!$gw_id) {
			echo 'Copy: -1';
			exit(0);
		}

		try{
			$query="select apid from apconfig where a.apname = ?";
			$res = $this->db->query($query, array($gw_id));
			if(!$res || $res->num_rows() == 0){
				throw new AppException("Client request 热点ID不正确");
			}
			$ap = $res->row_array();
			$userarray = explode("-", $user_list);
			if(empty($userarray)){
				echo 'Copy: 1';
				exit();
			}

			$query = "selec"

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