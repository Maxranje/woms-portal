<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Clear extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete');
	}

	public function index (){
		$gw_id		= $this->input->get('gw_id');
		$user_list  = $this->input->get('user_list');

		if(!$gw_id) {
			echo 'Copy: -1';
			exit(0);
		}

		try{
			$query="select apid from apconfig where a.apname = ?";
			$res = $this->db->query($query, array($gw_id));
			if(!$res || $res->num_rows() == 0){
				throw new AppException("清理僵尸数据失败", "设备请求热点名称不正确, gw_id=".$gw_id);
			}
			$ap = $res->row_array();
			$userarray = explode("-", $user_list);
			$query = "select uid, mac from user where apid = ? and state ='1'";
			$res = $this->db->query ($query, array($ap['apid']));
			if (!$res || $res->num_rows() == 0){
				throw new AppException("清理僵尸数据失败", "热点没有用户在线, apid = ".$ap['apid']);
			}
			$result = $res->result_array();
			$uid_array = array();
			foreach ($result as $row) {
				if(!in_array($row['mac'], $userarray)){
					$uid_array[] = $row['uid'];
				}
			}

			if(!empty($uid_array)){
				$query = "update user set state='0', tobeoffline='0',valid='0' where uid in (?)";
				$res = $this->db->query ($query, array( implode(",", $uid_array)));
				if(!$res || $this->db->affected_rows() == 0){
					throw new AppException("清理僵尸数据失败", "更新僵尸数据状态失败".implode(",", $uid_array));
				}
			}
			echo "Copy: 1";
			exit();
				
		}catch(AppException $aex){
			$aex->log_message();
			echo "Copy: 0";
			exit();
		}
	}
}
?>