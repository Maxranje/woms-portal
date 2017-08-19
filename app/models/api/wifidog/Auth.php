<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library('Func');
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete');
	}

	public function index (){
		$gw_id		= $this->input->get('gw_id');
		$stage		= $this->input->get('stage');
		$clientip	= $this->input->get('ip');
		$clientmac 	= $this->input->get('mac');
		$token 		= $this->input->get('token');
		$incoming	= $this->input->get('incoming');
		$outgoing	= $this->input->get('outgoing');
		$wanip='';

		if(!$stage || !$token){
			echo 'Auth: -1';
			exit(0);
		}
		$t = time();

		if($clientmac){
			$clientmac = strtoupper($clientmac);
		}
				
		try{
			$res = $this->db->query("select * from token where token = ?", array($token));
			if(!$res || $res->num_rows() == 0){
				throw new AppException('Auth: 0','取令牌信息发生错误|'.$token);
			}
			$tokeninfo = $res->row_array();
			$this->db->trans_start();
			
			if($stage=='login'){
				if($tokeninfo['valid']==0){
					throw new AppException('Auth: -1','令牌不可用|'.$token.'|'.$tokeninfo['valid'].'|'.$tokeninfo['token']);
				}
				if($tokeninfo['validatetime'] < time()){
					throw new AppException('Auth: -1','令牌不可用有效期过|'.$token.'|'.$tokeninfo['valid'].'|'.$tokeninfo['token']);
				}
				$res = $this->db->query("select * from user where uid = ?", array($tokeninfo['uid']));
				if(!$res || $res->num_rows() == 0){
					throw new AppException('Auth: -1','取用户信息发生错误|'.$token);
				}
				$res = $this->db->query("select * from usedlog where uid = ?", array($tokeninfo['uid']));
				if($res->num_rows() == 0){
					$query = "insert into usedlog (`uid`,apid,starttime, hearttime,ip,mac) value (?,?,?,?,?,?)";
					$res = $this->db->query($query, array($tokeninfo['uid'], $tokeninfo['apid'], time(), time(), $clientip, $clientmac));
					if($this->db->affected_rows() == 0){
						throw new AppException('Auth: -1','用户上线信息配置失败|'.$this->db->last_query());
					}
				}
				$query = "update user set valid = '1' where uid = ? and apid = ?";
				$res = $this->db->query($query, array($tokeninfo['uid'], $tokeninfo['apid']));
				$this->db->query ('update token set valid = "0" where id = ?', array($tokeninfo['id']));

				# bind mac
				$query = "select ac.bindmac, ac.bindvalidatetime, a.cid, b.valid, b.bid from (ap a, apconfig ac) left join bindmac b on b.apid = a.apid and b.mac = ? where a.apid = ac.apid and a.apid = ?";
				$res = $this->db->query ($query, array($clientmac, $tokeninfo['apid']));
				$result = $res->row_array();
				if($result['bindmac'] != '0' && empty($result['bid']) ){
					$query = "insert into bindmac (apid, uid, mac, ip, validatetime, createtime, valid) value (?,?,?,?,?,?,?)";
					$res = $this->db->query ($query, array($tokeninfo['apid'], $tokeninfo['uid'], $clientmac, $clientip, $result['bindvalidatetime'], time(), '1'));						
				} else if($result['bindmac'] != '0' && !empty($result['bid']) && $result['valid'] == '0'){
					$query = "update bindmac set validatetime=?, valid = '1' where apid = ? and mac =?";
					$res = $this->db->query ($query, array($result['bindvalidatetime'], $tokeninfo['apid'], $clientmac));						
				}
			}elseif($stage == 'logout'){
				
				$query = "update user set state = '0' where uid = ? and apid = ?";
				$res = $this->db->query ($query, array($tokeninfo['uid'], $tokeninfo['apid']));
				if(!$res || $this->db->affected_rows () == 0){
					throw new AppException('Auth: -1','用户下线信息配置失败');
				}
				
				$query = "update usedlog set endtime = ?, totaltime = (?-starttime)+totaltime where uid = ? and apid = ?";
				$res = $this->db->query ($query, array(time(), time(), $tokeninfo['uid'], $tokeninfo['apid']));
				if(!$res || $this->db->affected_rows() == 0){
					throw new AppException('Auth: -1','用户下线状态信息配置失败');
				}
				$_SESSION = array();
				session_destroy();

			}elseif($stage == 'counters'){

				$res = $this->db->query("select * from user where uid = ? and state='1' and valid='1'", array($tokeninfo['uid']));
				if(!$res || $res->num_rows() == 0){
					throw new AppException('Auth: 0','取用户信息发生错误|'.$token);
				}
				$user = $res->row_array();
				$res = $this->db->query("select * from usedlog where uid = ?", array($tokeninfo['uid']));
				if($res->num_rows() == 0){
					throw new AppException('Auth: 0','取用户session信息失败|'.$token);
				}
				$usersession = $res->row_array();
				
				if($user['tobeoffline'] == '1'){
					$query = "update user set state = '0', valid='0', tobeoffline='0' where uid = ?";
					$this->db->query ($query, array($tokeninfo['uid']));
					$this->db->trans_complete();
					echo 'Auth:0';
					exit();
				}
				$query = "update user set hearttime = ? where uid = ?";
				$res = $this->db->query ($query, array(time(), $tokeninfo['uid']));
				if (!$res || $this->db->affected_rows() == 0){
					throw new AppException('Auth: -1','更新用户心跳失败'.$tokeninfo['uid']);
				}
				$totaltime = time() - $user['createtime'];
				$query ="update usedlog set allbyte_down = allbyte_down+?, allbyte_up=allbyte_up+?,usedbyte_down=?,usedbyte_up=?, hearttime=?, totaltime=? where uid=?";
				$res = $this->db->query ($query, array($incoming,$outgoing,$incoming, $outgoing, time(), $totaltime,$tokeninfo['uid']));
				if (!$res || $this->db->affected_rows() == 0){
					throw new AppException('Auth: -1','更新用户会话流量统计失败'.$tokeninfo['uid']);
				}
			}
			
			$this->db->trans_complete();
			echo 'Auth: 1';
			exit(0);
		}catch(AppException $aex){
			$this->db->trans_rollback();
			$aex->log_message ();
			echo $aex->getOldMessage();
		}catch(Exception $ex){
			$this->db->trans_rollback();
			$aex = new AppException('Auth: -1','处理发生未知错误');
			$aex->log_message ();
			echo 'Auth: -1';
		}
	}
}
?>