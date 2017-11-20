<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Apmanage extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete');
	}

    /***
        *******************************************
        *          show info page                 *
        *******************************************
    ***/

    public function get_ap_list_page (){
    	if(isset($_SESSION['failed_reson'])){
    		$this->data['failed_reson'] = $_SESSION['failed_reson'];
    		unset($_SESSION['failed_reson']);
    	}
    	$this->load_view('aplist', $this->data);
    }

    public function get_ap_info_edit_page (){
    	$id = $this->input->get('apid');
    	if(!$id || intval($id) == 0){
    		throw new Exception("请求失败，请求参数错误");
    	}
    	$res = $this->db->query ('select * from ap where apid = ?' , array($id));
    	if(!$res || $res->num_rows() == 0){
    		throw new Exception ('请求失败，无法获取接入点相关信息');
    	}
    	$ap = $res->row_array ();
    	$this->load_view("apinfoedit", $ap);
    }

	public function get_ap_authtype_page (){
		if(!isset($_SESSION['apid']) || empty($_SESSION['apid'])){
			throw new Exception("Error Processing Request", 1);
		}
		$apid = $_SESSION['apid'];
		$sql = "select a.*, ac.* from ap a, apconfig ac where a.apid = ? and a.apid = ac.apid";
		$res = $this->db->query($sql, array($apid));
		if(!$res || $res->num_rows()==0){
			throw new Exception("apid is error");
		}
		$ap = $res->row_array ();
		$this->data['ap'] = $ap;
		$this->data['apid'] = $apid;

		# 短信模版内容
		$sql = "select * from smstemplate where cid = ? and valid='1'";
		$res = $this->db->query ($sql, array($_SESSION['cid']));
		$result = $res->result_array();
		$this->data['sms'] = $result;
		$this->load_view('apauthtype', $this->data);
	}

	public function get_ap_group_page()
	{
		$pages = $this->input->post('pages'); 
		$sc = $this->input->post('sc'); 
		$pages = !$pages ? 0 : intval($pages);
		$nums = $pages * 50;
		if(!$sc)
		{
			$sql = "select * from apgroup where cid = ? and id != 1 order by addtime limit ?, 50";
			$res = $this->db->query($sql , array($_SESSION['cid'], $nums));
		}
		else
		{
			$sql = "select * from apgroup where cid = ? and groupname like ? and id!=1 order by addtime limit ?, 50";
			$res = $this->db->query($sql , array($_SESSION['cid'], '%'.$sc.'%', $nums));				
		}
		$data =	$res->result_array();
		$this->data['pages'] = $pages +1;
		$this->data['data'] = $data;
		$this->data['nums'] = count($data);
		$this->load_view('grouplist', $this->data);
	}

	public function get_add_ap_page (){
		$query = "select apcountgrant from cooperater where cid = ?";
		$res = $this->db->query($query, array($_SESSION['cid']));
		$apgrant = $res->row_array();

		$query = "select count(*) ap_counts from ap where cid = ?";
		$res = $this->db->query ($query, array($_SESSION['cid']));
		$ap_counts = $res->row_array();

		if(intval($apgrant['apcountgrant']) <= intval($ap_counts['ap_counts'])){
			$_SESSION['failed_reson'] = "接入点达到上限, 请联系管理员获得更多接入点创建权限";
			redirect ('/corp/aplist');
		}
		$this->load_view("addap");
	}

	public function get_ap_config_page (){
		$id = $this->input->post('id');
		if(!$id){
			throw new Exception("Error Processing Request", 1);
		}
		$_SESSION['apid'] = $id;
		$this->load_view('apauthconf');
	}

	public function get_ap_tmp_page (){
		if(!isset($_SESSION['apid']) || empty($_SESSION['apid'])){
			throw new Exception("Error Processing Request", 1);
		}
		$apid = $_SESSION['apid'];
		$id = $this->input->post('id');
		if($id){
			$query = "update apconfig set templateid = ? where apid = ?";
			$this->db->query($query, array($id, $apid));
		}		
		$sql = "select templateid from apconfig ac where apid = ?";
		$res = $this->db->query($sql, array($apid));
		$result = $res->row_array ();
		$this->data['tid'] = $result['templateid'];

		$sql = "select * from template where cid = ? UNION select * from template where cid = 0";
		$res = $this->db->query($sql, array($_SESSION['cid']));
		$result = $res->result_array ();
		$this->data['tmp'] = $result;
		$this->load_view('apauthtmp', $this->data);
	}

	public function get_ap_assist_page (){
		if(!isset($_SESSION['apid']) || empty($_SESSION['apid'])){
			throw new Exception("Error Processing Request", 1);
		}
		$sql = "select opentime, openable from apconfig where apid = ?";
		$res = $this->db->query($sql, array($_SESSION['apid']));
		$apconfig = $res->row_array ();
		$this->data['openable'] = $apconfig['openable'];
		$this->data['opentime'] = $apconfig['opentime'];
		$this->data['apid'] = $_SESSION['apid'];
		$this->load_view('apauthassist', $this->data);		
	}



    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
	public function getaplist () {
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			if(!$sc) {
				$sql = "select count(*) as total from ap a, apconfig ac where cid = ? and a.apid = ac.apid";	
				$res = $this->db->query($sql, array($_SESSION['cid']));
				$result = $res->row_array();
				$total = $result['total'];

				$sql = "select a.*, ac.* ,(select count(u.uid) from user u where u.apid = a.apid and u.state = '1'and u.valid='1') olcount, (select count(u.apid) from user u where u.apid = a.apid) allcount from ap a, apconfig ac where cid = ? and a.apid = ac.apid order by a.createtime desc limit ?, ?";
				$res = $this->db->query($sql , array($_SESSION['cid'], $page, $rows));
			} else {
				$sql = "select count(*) as total from ap a, apconfig ac where cid = ? and a.apid = ac.apid and a.apname like ?";	
				$res = $this->db->query($sql, array($_SESSION['cid'], '%'.$sc.'%'));
				$result = $res->row_array();
				$total = $result['total'];

				$sql = "select a.*, ac.* ,(select count(u.uid) from user u where u.apid = a.apid and u.state = '1' and u.valid='1') olcount,(select count(u.apid) from user u where u.apid = a.apid) allcount from ap a, apconfig ac where cid = ? and a.apid = ac.apid and a.apname like ? order by a.createtime desc limit ?, ?";				
				$res = $this->db->query($sql , array($_SESSION['cid'], '%'.$sc.'%', $page, $rows));				
			}
			$result = $res->result_array();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$ap = array();
				$ap['id'] = $row['apid'];
				$ap['apname'] = $row['apname'];
				$ap['usedgrant'] = $row['usecountgrant'];
				$ap['usedcount'] = $row['allcount'];
				$ap['ollist'] = $row['olcount'];
				$ap['hearttime'] = ($row['hearttime'] == 0) ? '未同步' : date('Y-m-d H:i:s', $row['hearttime']);
				if($row['protocol'] == "p"){
					$ap['hearttime'] = date('Y-m-d H:i:s', $row['createtime']);
				}
				$ap['protocol'] = $row['protocol'] == 'w' ? 'WIFIDOG' : ($row['protocol'] == 'p' ? 'PORTAL2.0' :'PORTAL2.0 RC' );
				if(time() - 180 > $row['hearttime']) {
					$ap['state'] = "连接超时";
				}else {
					$ap['state'] = "nomal";
				}
				$this->data['rows'][] = $ap;
			}
			$this->data['total'] = $total;
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();
	}



	public function addap() {
		try {
			$apname = $this->input->post('apname');
			$protocol = $this->input->post('protocol');
			$modal = $this->input->post('chaporpap');
			$key = $this->input->post('key');
			$ssid = $this->input->post('ssid');
			$nasid = $this->input->post('nasid');
			$lic = $this->input->post('machcode');
			$adapter = $this->input->post('machcode-radio');
			$group = $this->input->post('group');     
			$remark = $this->input->post('remark');  
			$wanip = $this->input->post('wanip');
			$location = $this->input->post('location');     
			$devtype = $this->input->post('devtype');

			if (!$apname || !$protocol) {
				throw new Exception ("参数不正确, 请重新尝试");
			}			
			
			if($protocol == 'p' || $protocol =="pr") {
				if($protocol == 'p' && !$wanip) {
					throw new Exception ("标准Portal参数不正确, 请重新尝试");
				}
				if(!$modal || !$key) {
					throw new Exception ("Portal参数不正确, 请重新尝试");
				}
				if($modal != 'p' && $modal != "c") {
					throw new Exception ("CHAP OR PAP 参数不正确, 请重新尝试");
				}
			}
			$location = !$location ? "北京" : $location;
			$this->db->trans_start();

			# 商家 ap 添加上限检测
			$query = "select * from cooperater where cid = ?";
			$res = $this->db->query($query, array($_SESSION['cid']));
			$apgrant = $res->row_array();

			$query = "select count(*) ap_counts from ap where cid = ?";
			$res = $this->db->query ($query, array($_SESSION['cid']));
			$ap_counts = $res->row_array();

			if(intval($apgrant['apcountgrant']) <= intval($ap_counts['ap_counts'])){
				throw new Exception ("AP创建已经达到上限");
			}	

			# 商户ap重名否
			$res = $this->db->query("select apid from ap where apname = ?", array($apname));
			if ($res->num_rows() > 0){
				throw new Exception ("热点名称已经存在");
			}

			# 添加AP的基本信息
			if($protocol == 'w'){
				$query = "insert into ap (cid, devtype, protocol, apname, lic, adapter, remark, createtime, gid, location) values (?,?,?,?,?,?,?,?,?,?)";
				$res = $this->db->query($query, array($_SESSION['cid'], $devtype, $protocol, $apname, $lic, $adapter, $remark, time(), $group, $location));
			}else{
				$query = 'insert into ap (cid,devtype,protocol,apname,wanip,remark,createtime,gid,`key`, modal,ssid, nasid, location) values (?,?,?,?,?,?,?,?,?,?,?,?,?)';
				$res = $this->db->query($query, array($_SESSION['cid'], $devtype, $protocol, $apname, $wanip, $remark, time(), $group, $key, $modal, $ssid, $nasid, $location));
			}
			if(!$res || $this->db->affected_rows() == 0){   
				throw new Exception ("添加热点失败");
			}

			# 添加ap的配置信息
			$apid = $this->db->insert_id();
			$res = $this->db->query("select redirect from sysconfig");
			$result = $res->row_array();
			$url = $result['redirect'];
			$apgrant = intval($apgrant['usecountgrant']);

			$query = 'insert into apconfig (`apid`, `customurl`, `usecountgrant`, `openable`, `templateid`) values(?,?,?,?,?)';
			$res = $this->db->query($query, array($apid, $url, $apgrant, '0', 1));
			if(!$res || $this->db->affected_rows() == 0){   
				throw new Exception ("添加配置参数热点失败");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "热点添加成功";
		} catch (Exception $ec) {   
			$this->db->trans_rollback();
			$this->data['reson']=$ec->getMessage();
			$this->data['state']="failed";
		}
		echo json_encode($this->data);
		exit();
	}

	public function apedit (){
		try {
			$apid = $this->input->post('apid');
			$apname = $this->input->post('apname');
			$protocol = $this->input->post('protocol');
			$modal = $this->input->post('chaporpap');
			$key = $this->input->post('key');
			$ssid = $this->input->post('ssid');
			$nasid = $this->input->post('nasid');
			$lic = $this->input->post('machcode');
			$adapter = $this->input->post('machcode-radio');
			$group = $this->input->post('group');     
			$remark = $this->input->post('remark');  
			$wanip = $this->input->post('wanip');  
			$location = $this->input->post('location');           
			$devtype = $this->input->post('devtype');

			if (!$apname || !$protocol || !$apid) {
				throw new Exception ("参数不正确, 请重新尝试");
			}			
			if(intval($apid) == 0){
				throw new Exception ("参数不正确, 请重新尝试");
			}
			if($protocol == 'p' || $protocol =="pr") {
				if($protocol == 'p' && !$wanip) {
					throw new Exception ("标准Portal参数不正确, 请重新尝试");
				}
				if(!$modal || !$key) {
					throw new Exception ("Portal参数不正确, 请重新尝试");
				}
				if($modal != 'p' && $modal != "c") {
					throw new Exception ("CHAP OR PAP 参数不正确, 请重新尝试");
				}
			}
			$location = !$location ? "北京" : $location;
			$this->db->trans_start();
			# 添加AP的基本信息
			if($protocol == 'w'){
				$query = "update ap set devtype=?, protocol=?, lic=?, adapter=?, remark=?, gid=?, location=? where apid=? and cid=?";
				$res = $this->db->query($query, array($devtype, $protocol, $lic, $adapter, $remark, $group, $location, $apid, $_SESSION['cid']));
			}else{
				$query = 'update ap set devtype=?, protocol=?, wanip =?,remark=?,gid=?,`key`=?, modal=?,ssid=?, nasid=?, location=? where apid=? and cid=?';
				$res = $this->db->query($query, array($devtype, $protocol, $wanip, $remark, $group, $key, $modal, $ssid, $nasid, $location, $apid, $_SESSION['cid']));
			}
			if(!$res){   
				throw new Exception ("接入点基础信息修改失败");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "接入点基础信息修改成功";
		} catch (Exception $ec) {   
			$this->db->trans_rollback();
			$this->data['reson']=$ec->getMessage();
			$this->data['state']="failed";
		}
		echo json_encode($this->data);
		exit();
	}	

	public function apremove(){
		try{
			$id = $this->input->post('id');
			if(!$id){
				throw new Exception ('请求删除失败, 请求参数不正确');
			}
			$this->db->trans_start();
			$this->data['reson'] = "";
			$res = $this->db->query("delete from apconfig where apid = ?", array($id));
			$this->data['reson'] .= '<p class="text-danger">- 删除接入点配置成功 ！！</p>';
			$res = $this->db->query ("delete from authuser where apid = ? and cid = ?",array($id, $_SESSION['cid']) );
			$this->data['reson'] .= '<p class="text-danger">- 删除认证账户信息成功 ！！</p>';
			$res = $this->db->query ("delete from bindmac where apid = ?",array($id) );
			$this->data['reson'] .= '<p class="text-danger">- 删除绑定MAC信息成功 ！！</p>';
			$res = $this->db->query ("delete from blacklist where apid = ? and cid = ?",array($id, $_SESSION['cid']) );
			$this->data['reson'] .= '<p class="text-danger">- 删除黑名单条目信息成功 ！！</p>';			
			$res = $this->db->query ("delete from whitelist where apid = ? and cid = ?",array($id, $_SESSION['cid']) );
			$this->data['reson'] .= '<p class="text-danger">- 删除白名单条目信息成功 ！！</p>';									
			$res = $this->db->query("delete from ap where apid = ? and cid = ?", array($id, $_SESSION['cid']));
			$this->db->trans_commit();
			$this->data['reson'] .="<p class='text-success'>- 删除接入点成功 ！！</p>";
		}
		catch (Exception $ae){
			$this->db->trans_rollback();
			$this->data['state'] = "failed";
			$this->data['reson'] .= "<p>- ".$ae->getMessage()."</p>";
		}
		echo json_encode($this->data);
		exit();		
	}

	public function apconfig() {
		try {
			$authtype = $this->input->post('authtype');
			$wxlogin = $this->input->post('wxlogin');
			$showstatuspage = $this->input->post('showstatuspage');
			$showad = $this->input->post('showad');
			$customurl = $this->input->post('customurl');
			$bindmac = $this->input->post('bindmac');
			$smsid = $this->input->post('smsid');
			$smskey = $this->input->post('smskey');
			$smssecret = $this->input->post('smssecret');
			$smstemplate = $this->input->post('smstemplate');
			$authserver = $this->input->post('authserver');
			$apid = $this->input->post('apid');     

			if (!$apid) {
				throw new Exception("请求失败, 未知接入点配置, 错误码: 406");
			}	
			if ($apid != $_SESSION['apid']){
				throw new Exception("请求失败, 未知接入点配置, 错误码: 407");
			}

			if ($authserver == 'l'){
				$res = $this->db->query ("select lid from ldap where cid = ?", array($_SESSION['cid']));
				if($res->num_rows() == 0){
					throw new Exception("请求失败, LDAP服务器未配置, 请先添加LDAP服务器, 错误码: 4099");
				}
			}

			$wxlogin = !$wxlogin ? '0' : '1';
			$showad = !$showad ? '0' : '1';
			$bindvalidatetime = 9999999999;
			if($bindmac == '1d'){
				$bindvalidatetime = strtotime("+1 day");
			}else if($bindmac == '1w'){
				$bindvalidatetime = strtotime("+1 week");
			}else if($bindmac == '1m'){
				$bindvalidatetime = strtotime("+1 month");
			}else if($bindmac == '6m'){
				$bindvalidatetime = strtotime("+6 month");
			}

			if($authtype == '2'){
				if (!$smskey || !$smssecret || !$smstemplate){
					throw new Exception("选择短信验证方式必须配置短信通道KEY , SECRET及通知模版ID");
				}
			}

			if(!$customurl){
				$res = $this->db->query("select redirect from sysconfig");
				$result = $res->row_array();
				$customurl = $result['redirect'];
			}

			$this->db->trans_start();
			$query = "update apconfig set authtype=?, wxloginable=?, customurl=?, showstatuspage=?, showad=?,  bindmac=?, bindvalidatetime=?, smstid=?,smskey=?, smssecret=?, authserver=?, smstemplate=? where apid = ?";
			$res = $this->db->query($query, array( $authtype, $wxlogin, $customurl, $showstatuspage, $showad, $bindmac,$bindvalidatetime, $smsid, $smskey, $smssecret, $authserver,$smstemplate,$apid));
			if(!$res){   
				throw new Exception ("配置热点基本信息失败, 请重试");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "配置热点基本信息成功";
		} catch (Exception $ec) {   
			$this->db->trans_rollback();
			$this->data['reson']=$ec->getMessage();
			$this->data['state']="failed";
		}
		echo json_encode($this->data);
		exit();
	}

	public function selectaptmp (){
		$id = $this->input->post('id');
		if(!$id){
			throw new Exception ('参数不正确');
		}

		$this->data['reson'] = "配置接入点认证模版成功";		
	}

	
	public function assitconfig() {
		try {
			$t1 = $this->input->post('t1');
			$t2 = $this->input->post('t2');
			$t3 = $this->input->post('t3');
			$t4 = $this->input->post('t4');
			$openable = $this->input->post('openable');
			$apid = $this->input->post('apid');
			if (!$apid || ($apid != $_SESSION['apid'])){
				throw new Exception("参数不正确, 请重新尝试");
			}	
			if($openable == 0){
				$opentime = "";
			}else{
				$opentime = mktime($t1,$t3, 0,0,0,0)."-".mktime($t2,$t4, 0,0,0,0);
			}
			$this->db->trans_start();
			$query = "update apconfig set openable=?, opentime=? where apid = ?";
			$res = $this->db->query($query, array( $openable, $opentime, $apid));
			if(!$res){   
				throw new Exception ("配置热点基本信息失败, 请重试");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "配置热点基本信息成功";
		} catch (Exception $ec) {   
			$this->db->trans_rollback();
			$this->data['reson']=$ec->getMessage();
			$this->data['state']="failed";
		}
		echo json_encode($this->data);
		exit();
	}

}
?>