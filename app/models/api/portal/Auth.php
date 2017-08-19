<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library('Func');
		$this->load->library('Packet');
		$this->load->library('Socket');
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete');
	}

	public function index (){
		$token 	= $this->input->get('token');
		$optr	= $this->input->get('optr');
		$uname	= $this->input->get('uname');
		$upass	= $this->input->get('upass');
		$url 	= urlencode($this->input->get['url']);

		try{
			if(!$token || !$optr){
				throw new AppException ('Portal auth without optr', "optr=".$optr);
			}

			$res = $this->db->query("select * from token where token = ?", array($token));
			if(!$res || $res->num_rows() == 0){
				throw new AppException('Portal auth error','取令牌信息发生错误|'.$token);
			}
			$tokeninfo = $res->row_array();

			if($tokeninfo['valid']==0){
				throw new AppException('Portal auth error','令牌不可用|'.$token.'|'.$tokeninfo['valid'].'|'.$tokeninfo['token']);
			}
			if($tokeninfo['validatetime'] < time()){
				throw new AppException('Portal auth error','令牌不可用有效期过|'.$token.'|'.$tokeninfo['valid'].'|'.$tokeninfo['token']);
			}
			$res = $this->db->query("select * from user where uid = ?", array($tokeninfo['uid']));
			if(!$res || $res->num_rows() == 0){
				throw new AppException('Portal auth error','取用户信息发生错误|'.$token);
			}

			$res = $this->db->query ("select * from ap where apid = ?", array($tokeninfo['apid']));
			if($res->num_rows()==0) {
				throw new AppException('Portal auth error','令牌信息发生错误');
			}
			$ap = $res->row_array();

			# connect bas device
			$socket = $this->socket->createsocket("udp://{$ap['wlanip']}:2000");
			$this->packet->init(0x02, $ap['modal'], $ap['key'], $tokeninfo['ip']);
			
			#看看有没有用
			$timeout = 2;
			
			# challenge request
			if($ap["modal"] == 'c'){
				$challengeretry = 1;
				do{
					$requestpacket = $this->packet->createpacket(0x01);
					$responsepacket = $this->socket->interactpacket($requestpacket, $timeout);
					if($responsepacket === false){
						$requestpacket = $this->packet->createpacket(0x05, "", "", 0x01);
						$this->socket->sendpacket($requestpacket);
						throw new AppException("远程通信challenge请求超时", "wlanip=".$ap['wanip']);
					}
					if(!($responsepacket = $this->packet->checkresponsepacket($responsepacket, 0x02))){
						throw new AppException("远程通信challenge校验错误", "wlanip=".$ap['wanip']);
					}
					switch ($responsepacket[15]){
						case 0x00:
							$challengeretry = 5;
							break;
						case 0x01:
							throw new AppException("远程通信challenge请求被拒绝", "errocode = 0x01");
						case 0x02:
							throw new Exception("终端连接已经建立，您已经可以联网");
						case 0x03:
							usleep(300);   // 睡眠300 毫秒
							$challengeretry++;
							break;
						case 0x04:
							$globalerormsg = "远程通信challenge请求失败";
							throw new AppException($globalerormsg, "errocode = 0x04");
					}
					
				} while($challengeretry < 3);
			}
			
			#auth 
			$authretry = 1;
			do{
				$requestpacket = $this->packet->createpacket(0x03, $username, $password);
				$responsepacket = $this->socket->interactpacket($requestpacket, $timeout);
				if($responsepacket === false) {
					$requestpacket = $this->packet->createpacket(0x05, "", "", 0x01);
					$this->socket->sendpacket($requestpacket);
					throw new AppException("远程通信auth请求超时", "wlanip=".$ap['wanip']);
				}
				if(!($responsepacket = $this->packet->checkresponsepacket($responsepacket, 0x04))){
					throw new AppException("远程通信auth响应错误", "wlanip=".$ap['wanip']);	
				}
				switch ($responsepacket[15]){
					case 0x00:
						$authretry = 5;
						break;
					case 0x01:
						throw new AppException("远程通信auth请求被拒绝", "errocode = 0x01");
					case 0x02:
						throw new Exception("终端连接已经建立，您已经可以联网");
					case 0x03:
						usleep(300);
						$authretry++;
						break;
					case 0x04:
						throw new AppException('远程通信auth请求失败', "errocode = 0x04");
				}
				
			} while($authretry < 3);
			
			# 确认认证成功报文
			$requestpacket = $this->packet->createpacket(0x07);
			$this->socket->sendpacket($requestpacket);

			$res = $this->db->query("select * from usedlog where uid = ?", array($tokeninfo['uid']));
			if($res->num_rows() == 0){
				$query = "insert into usedlog (`uid`,apid,starttime, hearttime,ip,mac) value (?,?,?,?,?,?)";
				$res = $this->db->query($query, array($tokeninfo['uid'], $tokeninfo['apid'], time(), time(), $tokeninfo['ip'], $tokeninfo['mac']));
				if($this->db->affected_rows() == 0){
					throw new AppException('Portal auth error','用户上线信息配置失败|'.$token);
				}
			}
			$query = "update user set valid = '1' where uid = ? and apid = ?";
			$this->db->query($query, array($tokeninfo['uid'], $tokeninfo['apid']));
			
			$redirect = "/c/api/portal/portal?apid=".$ap['id']."&url=".$url;
			redirect($redirtoken, 'auto', 302);

		}catch(AppException $aex){
			$aex->log_message ();
			echo $aex->getOldMessage();
		}catch(Exception $ex){
			$aex = new AppException('Portal auth error','处理发生未知错误');
			$aex->log_message ();
		}
	}
}
?>