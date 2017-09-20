<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Splash_mo extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library('func');
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete', 'tmp'=>'');
	}

	public function index (){
		
		$wlanacname 	= $this->input->get("wlanacname");
		$wlanacip 		= $this->input->get("wlanacip");
		$nasid 			= $this->input->get("nasid");
		$ssid 			= $this->input->get("ssid");
		$clientip 		= $this->input->get("wlanuserip");
		$clientmac 		= $this->input->get("usermac");
		$url 			= urlencode ($this->input->get("userurl"));
		#$wlanusername 	= $this->input->get("wlanusername");
		$vlanid 		= $this->input->get("vlanid");
		try{
			if(!$clientip || !$wlanacname || !$wlanacip){
				throw new Exception("系统默认参数不正确， 请确定是否是连接规定设备");
			}

			$query = "select a.*, ac.* from ap a, apconfig ac where a.apid = ac.apid and a.apname = ? and a.wanip = ?";
			$res = $this->db->query ($query, array($wlanacname, $wlanacip));
			if(!$res){
				throw new AppException('System abnormal, please contact administrator');
			}
			if($res->num_rows() == 0){
				throw new AppException('Access point does not exist');
			}			
			$ap = $res->row_array();
			
			# Check whether AP users exceed the standard
			$query = "select uid from user where apid = ? and state = '1' and valid='1'";
			$res = $this->db->query($query, array($ap['apid']));
			if($ap['usecountgrant'] <= $res->num_rows()) {
				throw new AppException ('接入点在线人数以达到上限');
			}

			# Determine whether or not opentime is in
			if ($ap['openable'] == "1") {
				$opentime = explode("-", $ap['opentime']);
				
				$a = explode(":", date('H:i', $opentime[0]));
				$start = mktime($a[0], $a[1], 0 ,0,0,0);

				$a = explode(":", date('H:i', $opentime[1]));
				$end = mktime($a[0], $a[1], 0 ,0,0,0);					

				$a = explode(":", date('H:i', time()));
				$now = mktime($a[0], $a[1], 0 ,0,0,0);	

				if($start> $now || $now > $end){
					$dd = date("H:i", $opentime[0]).":".date("H:i", $opentime[1]);
					throw new Exception("认证系统尚未开启, 开启时间为:".$dd);
				}
			}

			if ($clientmac){
				$clientmac = strtoupper ($clientmac) ;
			}

			$_SESSION['url'] = $url;
			// check mac white list
			$query = "select * from whitelist where content = ? and (apid = ? or apid = 0) and type = 'm'";
			$res = $this->db->query ($query, array( $clientmac , $ap['apid']));
			if ($res && $res->num_rows() > 0){
				$uid = $this->func->adduser ($ap['cid'], $ap['apid'], 'm', 0, $clientmac,'',time(), $clientip, $clientmac, $wanip);
				$token = $this->func->addtoken ($uid, $ap['apid'], 'm', $clientip, $clientmac);
				$optr = $this->func->getOptr($clientmac, "wl");
				$redirtoken = "/c/api/portal/auth?token=".$token."&url=".$url."&optr=".$optr."&uname=".$clientmac."&upass=wl";
				redirect($redirtoken, 'auto', 302);
			}				

			// check mac black list
			$query = "select * from blacklist where content = ? and (apid = ? or apid = 0) and type = 'm'";
			$res = $this->db->query ($query, array( $clientmac, $ap['apid']));
			if ($res->num_rows() > 0){
				$result = $res->row_array();
				if($result['validtime'] > time()){
					throw new Exception("你的mac地址已被拉入黑名单, 请联系管理员开通");
				}
			}

			// Determines whether to bind Mac and within the valid time
			if($ap['bindmac'] == '1'){
				$query = "select * from bindmac where apid = ? and mac = ? and valid = '1' ";
				$res = $this->db->query ($query, array($ap['apid'], $clientmac));
				if ($res->num_rows() > 0) {
					$result = $res->row_array();
					if($result['validatetime'] > time()){
						$this->db->query ("update user set state = '1' and heartime=? where uid = ?", array(time(),$result['uid']));
						$token = $this->func->addtoken ($result['uid'], $ap['apid'], 'm', $clientip, $clientmac);
						$optr = $this->func->getOptr($clientmac, "bm");
						$redirtoken = "/c/api/portal/auth?token=".$token."&url=".$url."&optr=".$optr."&uname=".$clientmac."&upass=bm";
						redirect($redirtoken, 'auto', 302);						
					} else {
						$query = "update bindmac set state ='0' where apid = ? and mac = ?";
						$this->db->query ($query, array($apid, $clientmac));
					}
				}
			}

			# WeChat scan code authentication
			$authtype = null;
			$authcode = null;
			if (isset($_GET[$url]) && !empty($url)){
				if (strpos($url, "wxcode") != false){
					$wxcode = explode ('wxcode=', urldecode($url));
					if($wxcode[1]){
						$wxcode = explode ('&', $wxcode);
						$authtype = 'w';
						$authcode = $wxcode[0];
					}
				}
			}

			if($authcode && $authtype == 'w'){
				$sql = "select t.*, wf.* from wxtoken t, wxfuns wf where t.cid = ? and t.type=? and t.token =? and t.openid = wf.openid";
				$res = $this->db->query ($sql, array($_SESSION['cid'], $authtype, $authcode));
				if(!$res || $res->num_rows() == 0){
					throw new AppException ('微信认证参数不正确', "token=".$authcode);
				}
				$wxlogincode = $res->row_array ();
				if($wxlogincode['status'] == '0'){
					throw new AppException ('微信认证无效');
				}
				if($wxauthcode['expiretime'] != 0 && $wxauthcode['expiretime'] < time()){
					throw new AppException ('微信认证有效期已过');
				}
				$wx = $res->row_array ();
				
				$uid = $this->func->adduser ($ap['cid'], $ap['apid'], 'w',0, $wx['openid'],'',time(), $clientip, $clientmac, $wanip);
				$token = $this->func->addtoken ($uid, $ap['apid'], 'w', $clientip, $clientmac);	
				$optr = $this->func->getOptr($wx['openid'], "wx");
				$redirtoken = "/c/api/portal/auth?token=".$token."&url=".$url."&optr=".$optr."&uname=".$wx['openid']."&upass=wx";				redirect($redirtoken, 'auto', 302);
			}

			
			// get auth template
			$res = $this->db->query ("select * from template where id = ? and state = '1'", array($ap['templateid']));
			if (!$res || $res->num_rows () == 0){
				throw new AppException ('没有可用的模版, 无法展示内容进行认证');
			}
			$template = $res->row_array ();
			if (isset($template['title']) || empty($template['title'])){
				$template['title'] = "欢迎使用无线认证";
			}
			$this->data['tmp'] = $template; 
			if($this->func->ismobile()){
				$this->data['tmp_page'] = "template_mobile";
			}else{
				$this->data['tmp_page'] = "template_pc";
			}

			if ($ap['authtype'] == '0'){
				$this->data['authtype'] = 'noauth';
			} else if($ap['authtype'] == '1') {
				$this->data['authtype'] = 'authuser';
			} else if($ap['authtype'] == '2') {
				$this->data['authtype'] = 'authphone';
			}

			// no auth with ad
			if ($ap['authtype'] == 3){
				$uid = $this->func->adduser ($ap['cid'], $ap['apid'], 'n',0, 'auth_systemuser', 'auth_systempass',time(), $clientip, $clientmac, wanip);
				$token = $this->func->addtoken ($uid, $ap['apid'], 'n', $clientip, $clientmac);	
				$optr = $this->func->getOptr('auth_systemuser', "auth_systempass");
				$redirtoken = "/c/api/portal/auth?token=".$token."&url=".$url."&optr=".$optr."&uname=auth_systemuser&upass=auth_systempass";
				redirect($redirtoken);				
			}

			// auth
			$acctype = $this->input->post('acctype');
			if ($acctype){ 
				if($acctype == 'login'){ // noauth
					# sysaccount auth
					$uid = $this->func->adduser ($ap['cid'], $ap['apid'], 'n',0, 'auth_systemuser', 'auth_systempass',time(), $clientip, $clientmac, wanip);
					$token = $this->func->addtoken ($uid, $ap['apid'], 'n', $clientip, $clientmac);
					$optr = $this->func->getOptr('auth_systemuser', "auth_systempass");
					$redirtoken = "/c/api/portal/auth?token=".$token."&url=".$url."&optr=".$optr."&uname=auth_systemuser&upass=auth_systempass";
					redirect($redirtoken);	
				}
				
				// username and password
				if($acctype == 'userlogin'){
					$authname = $this->input->post('authname');
					$authpass = $this->input->post('authpass');
					if (!$authname || !$authpass){
						throw new Exception ('用户名或密码输入错误, 请重新认证');
					}
					$query = "select * from authuser where uname = ? and upasswd =? and ( apid = ? or apid = 0 ) ";
					$res = $this->db->query($query, array($authname, $authpass, $ap['apid']));
					if(!$res || $res->num_rows () == 0){
						throw new Exception ('用户名或密码输入错误, 请重新认证');
					}
					$authuser = $res->row_array ();		
					if($authuser['state'] == "0"){
						throw new Exception ('该账户处于无效状态，请使用其他帐号登录');
					}
					if($authuser['expiredate'] <= time()){
						throw new Exception ('该账户已过有效期，请使用其他帐号登录');
					}	
					if($authuser['mutilogin'] == '1'){
						$query = "select count(*) count from user where apid =? and uid = ? and state = '1' group by uid";
						$res = $this->db->query ($query, array($ap['apid'], $authuser['id']));
						$result = $res->row_array ();	
						if(($result['count'] >= intval($authuser['mutilcount'])) ){
							throw new Exception ('该账户多人登录以达到上限， 请重新更换账户登录');	
						}
					}

					$this->db->trans_start();
					$uid = $this->func->adduser ($ap['cid'], $ap['apid'], 'c', $authuser['id'], $authname, $authpass,time(), $clientip, $clientmac, $wanip);
					$token = $this->func->addtoken ($uid, $ap['apid'], 'c', $clientip, $clientmac);	
					$this->db->trans_commit();
					$optr = $this->func->getOptr('authname', "authpass");
					$redirtoken = "/c/api/portal/auth?token=".$token."&url=".$url."&optr=".$optr."&uname=".$authname."&upass=".$authpass;
					redirect($redirtoken);	
				}
				
				// phone sms code
				if ($acctype == 'mobilevalidatelogin'){
					$authname = $this->input->post('authname');
					$authpass = $this->input->post('authpass');
					if (!$authname || !$authpass){
						throw new Exception ('手机号或验证码输入错误, 请重新认证');
					}	
					$query = "select * from smsauthlog where verifycode = ? and mobile =? and apid = ? and status = '1'";
					$res = $this->db->query($query, array($authpass, $authname, $ap['apid']));
					if(!$res || $res->num_rows () == 0){
						throw new Exception ('验证码不正确');
					}
					$smsauthlog = $res->row_array ();
					if($smsauthlog['expiretime'] < time()) {
						throw new Exception ('验证码过期');
					}
					$uid = $this->func->adduser ($ap['cid'], $ap['apid'], 's',0, $authname,$authpass,time(), $clientip, $clientmac, wanip);
					$token = $this->func->addtoken ($uid, $ap['apid'], 's', $clientip, $clientmac);	
					$optr = $this->func->getOptr('authname', "authpass");
					$redirtoken = "/c/api/portal/auth?token=".$token."&url=".$url."&optr=".$optr."&uname=".$authname."&upass=".$authpass;
					redirect($redirtoken);							
				}

				// get smsauth code 
				if($acctype == 'mobileauthcode'){
					try{
						$this->data = array();
						$phone = $this->input->post("phone");
						if(!$phone || intval($phone) == 0){
							throw new Exception("手机号输入不正确, 请重新输入");
						}
						if(strlen($phone) != 11) {
							throw new Exception("手机号必须是11位有效号码, 请重新输入");
						}

						$query = "select * from blacklist where content = ? and (apid = ? or apid = 0) and type = 'p'";
						$res = $this->db->query ($query, array($phone, $ap['apid']));
						if ($res->num_rows() > 0){
							$result = $res->row_array();
							if($result['validtime'] > time()){
								throw new Exception("你的手机号已被拉入黑名单, 请联系管理员开通");
							}
						}							

						$query = "select * from smstemplate where smsid = ? and cid = ? and valid = '1'";
						$res = $this->db->query($query, array($ap['smstid'], $_SESSION['cid']));
						if(!$res || $res->num_rows () == 0){
							throw new Exception('短信模版不存在或未审核通过, 请联系管理员');
						}
						$smstemplate= $res->row_array ();
						$smscode = mt_rand(111111, 999999);

						$this->load->library ('Wysms');
						$this->wysms->init($ap['smskey'], $ap['smssecret'] ,$ap['smstemplate']);
						$flag = $this->wysms->sendsms ($phone, $smscode);
						if($flag != 200){
							throw new Exception ('获得验证码失败, 错误码:'.$flag);
						}
						$query = "insert into smsauthlog (apid, stid, verifycode, mobile, ip, mac, successcode,sendtime,expiretime, status) value (?,?,?,?,?,?,?,?,?,?)";
						$status = $flag == 200 ? '1' : '0';
						$res = $this->db->query($query, array($ap['apid'],$ap['smstid'],$smscode, $phone, $clientip, $clientmac, $flag, time(), time()+7200, $status));
						if(!$res || $this->db->affected_rows() == 0){
							throw new Exception("新建短信验证信息失败");
						}
						$this->data['state'] = "success";
						$this->data['reson'] = "短信已发送, 请验收";
					}catch (Exception $ec){
						$this->data['state'] = "failed";
						$this->data['reson'] = $ec->getMessage();
					}
					echo json_encode($this->data);
					exit();
				}				
			}			

		}
		catch (AppException $ec) {
			$this->data['reson'] = $ec->getOldMessage();
			$this->data['tmp_page'] = "errors/auth_error";
		}
		catch (Exception $e) {
			$this->db->trans_rollback();
			if(empty($this->data['tmp_page'])){
				$this->data['tmp_page'] = "errors/auth_error";
			}			
			$this->data['state'] = "authfailed";
			$this->data['reson'] = $e->getMessage ();
		}
		$this->load_view($this->data['tmp_page'], $this->data);
	}
}
?>