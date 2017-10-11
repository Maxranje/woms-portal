<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Func {
	public function __construct (){
		$this->ci = & get_instance ();
		$this->db = $this->ci->db;
	}

	public function addtoken($uid, $apid, $tokentype, $ip, $mac){
		$token = substr( md5(mt_rand(111111, 999999)), 0, 16).($this->creatRandomStr(16));

		$query ="insert into token (`uid`, `apid`,`token`, `useragent`, `createtime`, `validatetime`, `tokentype`, `ip`,`mac`, `valid`) values (?,?,?,?,?,?,?,?,?,?)";
		$res = $this->db->query($query, array($uid, $apid, $token, $_SERVER['HTTP_USER_AGENT'], time(),time()+900, $tokentype, $ip, $mac, '1'));
		if(!$res || $this->db->affected_rows() == 0){
			throw new AppException("Token failed to generate");
		}else{
			return $token;
		}
	}

	public function creatRandomStr ($num) {
		$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$stt= '';
		for ($i=0; $i <16 ; $i++) { 
			$stt .= $str[mt_rand(0, 51)];
		}
		return $stt;
	}


	function getBytesbyString($str){
		$bytes = array();
		for($i=0; $i<strlen($str); $i++){
			$bytes[] = ord($str[$i]);
		}
		return $bytes;
	}

	function randomSerialno(){
		$serialno = array();
		$serialno[] = rand(10, 127);
		$serialno[] = rand(10, 127);
		return $serialno;
	}

	function bytes2str($bytes){
		$str = '';
		foreach($bytes as $b){
			$str .= chr($b);
		}
		return $str;
	}


	public function iptobyte($ip){
		$iparray = explode (".", $ip);
		return array(intval($iparray[0]), intval($iparray[1]), intval($iparray[2]), intval($iparray[3]));
	}

	function getAuthenticator(){
		$array = array();
		for($i = 0; $i < 16; $i++){
			$array[] = 0x00;
		}
		return $array;
	}

	function isSessionAvail(){
		return (isset($_SESSION["username"]) && !empty($_SESSION["username"])) ? true : false; 
	}

	function preg_match_parameter($str){
		$_match = "/[!%#$=+|\.\\\^@]/";
		return (preg_match($_match, $str) == 1) ? true : false ;
	}
	

	function adduserlog($conn, $cid, $basid, $ip, $mac){
		$query = "insert into portal_userlog (cid, basid, ip, mac, createtime, validate) values ({$cid}, {$basid}, '".$ip."', '".$mac."', ".time().", '1')";
		$res = $conn -> query ($query);
		return $res;
	}

	function getmixedinformation($uname, $passwd, $type){
		$key = "maxranje";
		$str =  json_encode(array("username" => $uname, "password" => $passwd));
		return authcode($str, 'ENCODE', $key);
	}

	#Discuz!经典代码
	function authcode($string, $operation = 'DECODE', $key = 'maxranje', $expiry = 0) {   
	    // 暂时指定密钥， 也可随机生成
	    $ckey_length = 4;   
	    $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);   
	    $keya = md5(substr($key, 0, 16));   
	    $keyb = md5(substr($key, 16, 16));   
	    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';   
	    $cryptkey = $keya.md5($keya.$keyc);   
	    $key_length = strlen($cryptkey); 
	    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;   
	    $string_length = strlen($string);   
	    $result = '';   
	    $box = range(0, 255);   
	    $rndkey = array();   
	    for($i = 0; $i <= 255; $i++) {   
	        $rndkey[$i] = ord($cryptkey[$i % $key_length]);   
	    }   
	    for($j = $i = 0; $i < 256; $i++) {   
	        $j = ($j + $box[$i] + $rndkey[$i]) % 256;   
	        $tmp = $box[$i];   
	        $box[$i] = $box[$j];   
	        $box[$j] = $tmp;   
	    }   
	    for($a = $j = $i = 0; $i < $string_length; $i++) {   
	        $a = ($a + 1) % 256;   
	        $j = ($j + $box[$a]) % 256;   
	        $tmp = $box[$a];   
	        $box[$a] = $box[$j];   
	        $box[$j] = $tmp;   
	        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));   
	    }   
	    if($operation == 'DECODE') {   
	        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&  
				substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {   
	            return substr($result, 26);   
	        } else {   
	            return '';   
	        }   
	    } else { 
	        return $keyc.str_replace('=', '', base64_encode($result));   
	    }   
	} 

	public function getToken ($num) {
		return substr(md5(mt_rand(1111111, 9999999)), 0, intval($num));
	}
	public function getOptr ($uname, $upass) {
		return md5($uname." maxranje ".$upass);
	}	

	
	/**
	 * 用户登录信息录入
	 * @return [type] [description]
	 */
	public function adduser ($cid, $apid, $acctype, $authid, $uname, $upass,$hearttime, $ip, $mac, $wanip){
		if($authid != 0){
			$query = "update authuser set logincount = logincount+1 where id = ?";
			$this->db->query ($query , array($authid));
		}
		// mm user filter
		
		$query = "insert into user (cid,apid,acctype,authid,uname,upass,createtime,hearttime,ip,mac,wanip,useragent) values (?,?,?,?,?,?,?,?,?,?,?,?)";
		$res = $this->db->query ($query, array($cid, $apid, $acctype, $authid, $uname, $upass, time(),$hearttime,$ip, $mac, $wanip,$_SERVER['HTTP_USER_AGENT']));
		if(!$res || $this->db->affected_rows() == 0){
			throw new Exception ('新建登录用户信息失败, 用户类型'.$acctype);
		}
		return $this->db->insert_id();
	}
		// if($authid != 0){
		// 	$query = "update authuser set logincount = logincount+1 where id = ?";
		// 	$this->db->query ($query , array($authid));
		// }
		// // mm user filter
		// $this->mredis->init();
		// $uid = $this->mredis->getuserid($mac);
		// if(!$uid) {
		// 	$query = "insert into user (cid,apid,acctype,authid,uname,upass,createtime,hearttime,ip,mac,wanip,useragent) values (?,?,?,?,?,?,?,?,?,?,?,?)";
		// 	$res = $this->db->query ($query, array($cid, $apid, $acctype, $authid, $uname, $upass, time(),$hearttime,$ip, $mac, $wanip,$_SERVER['HTTP_USER_AGENT']));
		// 	if(!$res || $this->db->affected_rows() == 0){
		// 		throw new Exception ('新建登录用户信息失败, 用户类型'.$acctype);
		// 	}
		// 	$uid = $this->db->insert_id();
		// 	$this->mredis->setuserid ($mac, $uid);
		// }
		// $userinfo = json_encode(array($cid, $apid, $acctype, $authid, $uname, $upass, time(), $hearttime, $ip, $mac, $wanip, $_SERVER['HTTP_USER_AGENT'] ));
		// $this->mredis->setuserinfo ($uid, $userinfo);		
		
		// return $uid;
	
	

	public function sec2time ($time){
		$time = intval($time);
		$response = "";
		# 天
		if(floor($time / 86400 ) > 0){
			$response.= floor($time / 86400 )."天 "; 
			$time = $time % 86400;
		}
		# 小时
		if(floor($time / 3600 ) > 0){
			$response.= floor($time / 3600 )."小时 "; 
			$time = $time % 3600;
		}
		# 分钟
		if(floor($time / 60 ) > 0){
			$response.= floor($time / 60 )."分 "; 
			$time = $time % 60;
		}
		if($time > 0){
			$response.= $time."秒";
		}
		if (empty($response)){
			return "未连接或未使用";
		} else {
			return $response;
		}
	}

	public function ismobile() {
		if(isset($_SERVER['HTTP_X_WAP_PROFILE'])){
			return true;
		}

		if(isset($_SERVER['HTTP_VIA'])){
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger'); 
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return true;
			} 
		} 
		if (isset ($_SERVER['HTTP_ACCEPT'])) { 
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
				return true;
			} 
		} 
		return false;		
	}


	public function easybyte($byte){
		$response = "";

		if (floor($byte/ (1024*1024*1024)) > 0){
			$response .= floor($byte/ (1024*1024*1024))." GB ";
			$byte = $byte % (1024*1024*1024);
		}

		if (floor($byte/ (1024*1024)) > 0){
			$response .= floor($byte/ (1024*1024))." MB ";
			$byte = $byte % (1024*1024);
		}

		if (floor($byte/ (1024)) > 0){
			$response .= floor($byte/ (1024))." KB ";
			$byte = $byte % (1024);
		}

		if ($response == ""){
			$response = $byte . " B";
		}
		return $response;
	}


	public function ldap ($username, $password, $cid){
		try{
			$res = $this->db->query("select * from ldap where cid = ?", array($cid));
			if($res->num_rows() == 0){
				throw new Exception ('LDAP服务尚未配置, 请联系管理员');
			}
			$ldap = $res->row_array();
			$connect = ldap_connect($ldap['ldaphost'], $ldap['ldapport']) ;
			if($connect === false){
				throw new Exception ('链接ldap服务器失败');
			}
			ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
			$u = $username;
			$username .= "@corp.homelink.com.cn";
			$state = ldap_bind($connect, $username, $password);	
			if($state){
				return array("success", "success");
			}else{
				ldap_bind($connect, $ldap['ldapusername'], $ldap['ldapuserpass']);
				$result = ldap_search($connect, "OU=链家网,DC=corp,DC=homelink,DC=com,DC=cn", $ldap['attr2'].'='.$u , array($ldap['attr1']));
				$entry = ldap_get_entries($connect, $result);
				if($entry['count'] == 0){
					$result = ldap_search($connect, 'ou=北京链家,DC=corp,DC=homelink,DC=com,DC=cn', $ldap['attr2'].'='.$u , array($ldap['attr1']));
					$entry = ldap_get_entries($connect, $result);
					if($entry['count'] == 0){
						throw new Exception("用户名密码不正确");
					}
				}
				$uname = strtolower($ldap['attr1']);
				$uname = $entry[0][$uname][0];
				$uname = $uname."@corp.homelink.com.cn";
				$bind = ldap_bind($connect, $uname, $p);
				if($bind){
					return array("success", "success");
				}else{
					throw new Exception("用户名密码不正确");
				}			
			}
		}catch (Exception $ec){
			if(isset($connect)){
				ldap_close($connect);
			}
			return array('failed', $ec->getMessage());
		}
		return array("success", "success");
	}


}