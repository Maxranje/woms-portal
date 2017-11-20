<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Corpmanage extends CI_Model {
	
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

	public function get_all_corp_page (){
		$this->load_view('adc/cooperater_all');
	}

	public function get_corp_grant_page (){
		$this->load_view('adc/corpgrant');
	}
	public function get_add_corp_page (){
		$this->load_view ('adc/cooperater_add');
	}
	public function get_edit_corp_page (){
		$cid = $this->input->post('id');
		if(!$cid){
			throw new Exception("请求失败, 错误码: 2714");
		}
		$res = $this->db->query ("select * from cooperater where cid = ?", array($cid));
		if($res->num_rows() == 0){
			throw new Exception("请求失败, 错误码：2715");
		}
		$this->data['corp'] = $res->row_array();
		$this->load_view ('adc/cooperater_edit', $this->data);
	}

	public function get_ap_grant_list (){
		$this->load_view ('adc/apgrant');
	}
	
    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
	public function getcorplist () {
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			if($sc) {
				$query = "select count(*) as total from cooperater where nickname like ? or cpun like ?";
				$res = $this->db->query($query , array('%'.$sc.'%','%'.$sc.'%'));
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql = "select * from cooperater where nickname like ? or cpun like ? order by createtime limit ?,?";
				$res = $this->db->query($sql , array('%'.$sc.'%','%'.$sc.'%', $page, $rows));
			} else {
				$res = $this->db->query("select count(*) as total from cooperater ");
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql ="select * from cooperater order by createtime limit ?,?";				
				$res = $this->db->query($sql , array($page, $rows));				
			}
			$result = $res->result_array();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$array = array();
				$array['id'] = $row['cid'];
				$array['cpun'] = $row['cpun'];
				$array['nickname'] = $row['nickname'];
				$array['industry'] = $row['industry'];
				$array['name_manager'] = $row['name_manager'];
				$array['phone'] = $row['phone'];
				$this->data['rows'][] = $array;
			}
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();
	}

	public function getcorpgrantlist (){
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			if($sc) {
				$query = "select count(*) as total from cooperater c where c.nickname like ? or c.cpun like ?";
				$res = $this->db->query($query , array('%'.$sc.'%','%'.$sc.'%'));
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql = "select c.*, (select count(a.apid) from ap a where a.cid = c.cid) count from cooperater c where c.nickname like ? or c.cpun like ? order by c.createtime limit ?,?";
				$res = $this->db->query($sql , array('%'.$sc.'%', '%'.$sc.'%', $page, $rows));
			} else {
				$res = $this->db->query("select count(*) total from cooperater c");
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql ="select c.*, (select count(a.apid) from ap a where a.cid = c.cid) count from cooperater c order by c.createtime limit ?,?";				
				$res = $this->db->query($sql , array($page, $rows));				
			}
			$result = $res->result_array();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$array = array();
				$array['id'] = $row['cid'];
				$array['cpun'] = $row['cpun'];
				$array['nickname'] = $row['nickname'];
				$array['industry'] = $row['industry'];
				$array['name_manager'] = $row['name_manager'];
				$array['phone'] = $row['phone'];
				$array['aps']	= $row['count'];
				$array['apcountgrant']	= $row['apcountgrant'];
				$this->data['rows'][] = $array;
			}
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();
	}

	public function corpgrant (){
		try{
			$cid = $this->input->post('id'); 
			$count = $this->input->post('count');
			if(!$cid || !$count ){
				throw new Exception("请求无效, 请确认是否填写规范的授权数量");
			}

			$count = intval($count);
			// 比对系统授权参数
			$query = "select apcountgrant from adc where adcid = ?";
			$res = $this->db->query ($query, array($_SESSION['adcid']));
			$adc = $res->row_array();

			$res = $this->db->query ("select sum(apcountgrant) apcountgrant from cooperater");
			$corp = $res->row_array();	

			$res = $this->db->query ("select apcountgrant from cooperater where cid = ?",array($cid));
			$cc = $res->row_array();				

			$numbers = intval($corp['apcountgrant']) - intval($cc['apcountgrant']) + $count;

			if($adc['apcountgrant'] <  $numbers){
				throw new Exception("接入点授权达到系统上限, 系统Licence最大创建接入点上限为".$adc['apcountgrant']);
			}

			$query = "update cooperater set apcountgrant = ? where cid = ?";			
			$res = $this->db->query($query , array($count, $cid));
			$this->data['reson'] = "授权商家可创建接入点数量成功";
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}

	public function apgrant (){
		try{
			$apid = $this->input->post('id'); 
			$count = $this->input->post('count');
			if(!$apid || !$count ){
				throw new Exception("请求无效, 请确认是否填写规范的授权数量");
			}
			$res = $this->db->query("select apname from ap where apid = ?", array($apid));
			if ($res->num_rows() == 0){
				throw new Exception("请求无效, 该接入点不存在, 请确认");
			}
			$ap = $res->row_array();
			$count = intval($count);
			$res = $this->db->query("update apconfig set usecountgrant = ? where apid = ?", array($count, $apid));
			$this->data['reson'] = "授权接入点{$ap['apname']}用户数成功";
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}	

	public function changepw (){
		try{
			$password = $this->input->post('password'); 
			$cid = $this->input->post('cid');
			if(!$cid || !$password){
				throw new Exception("请求无效, 操作失败");
			}

			$query = "select * from cooperater where cid = ?";
			$res = $this->db->query ($query, array($cid));
			if($res->num_rows == 0){
				throw new Exception("该商户不存在， 无法修改密码");
			}	
			$password = substr(md5($password), 0 ,16);
			$query = "update cooperater set cppw = ? where cid = ?";
			$res = $this->db->query($query , array($password, $cid));
			$this->data['reson'] = "修改密码成功";
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();			
	}

	public function corpremove (){
		try{
			$this->data['reson'] = "";
			$cid = $this->input->post('id'); 
			if(!$cid ){
				throw new Exception("请求无效, 操作失败");
			}

			$this->db->trans_start();

			$res = $this->db->query ("select cid from cooperater where cid = ?", array($cid));
			if($res->num_rows() == 0){
				$this->data['query'] = $this->db->last_query();
				throw new Exception("该商户不存在， 无法删除");
			}	

			$res = $this->db->query("delete from cooperater where cid = ?" , array($cid));

			$res = $this->db->query("delete a,ac from ap a, apconfig ac where a.cid = ? and ac.apid= a.apid" , array($cid));
			$this->data['reson'] .= "删除商户所有接入点成功</br>";
			
			$res = $this->db->query("delete ul, u from usedlog ul, user u where u.cid = ? and u.uid = ul.uid" , array($cid));
			$this->data['reson'] .= "删除商户认证信息成功</br>";

			$res = $this->db->query("delete from smstemplate where cid = ?" , array($cid));
			$this->data['reson'] .= "删除商户短信认证模版成功</br>";

			$res = $this->db->query("delete from template where cid = ?" , array($cid));
			$this->data['reson'] .= "删除商户认证模版成功</br>";

			$res = $this->db->query("delete from whitelist where cid = ?" , array($cid));
			$this->data['reson'] .= "删除商户白名单成功</br>";

			$res = $this->db->query("delete from blacklist where cid = ?" , array($cid));
			$this->data['reson'] .= "删除商户黑名单成功</br>";

			$res = $this->db->query("delete from cooperater where cid = ?" , array($cid));
			$this->data['reson'] .= "删除商户基本信息成功</br>";

			$this->db->trans_commit();
		}
		catch (Exception $ec) {
			$this->db->trans_rollback();
			$this->data["state"] = "failed";
			$this->data["reson"] .= $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();			
	}


	public function addcorp (){
		try {
			$nickname = $this->input->post('nickname');
			$cpun = $this->input->post('cpun');
			$cppw = $this->input->post('cppw');
			$industry = $this->input->post('industry');
			$manage = $this->input->post('name_manager');
			$phone = $this->input->post('phone');
			$email = $this->input->post('email');
			$qq = $this->input->post('qq');

			if(!$nickname || !$industry || !$cpun || !$cppw || !$manage || !$phone){
				throw new Exception ('请求无效, 操作失败');
			}

			$res = $this->db->query("select cid from cooperater where cpun = ?", array($cpun));
			if(!$res || $res->num_rows() != 0){
				throw new Exception ('商户信息已经存在, 操作失败');
			}

			$res = $this->db->query("select * from sysconfig where id = 1");
			if(!$res || $res->num_rows() == 0){
				throw new Exception ('无法获取系统全局参数, 请预先配置');
			}
			$conf = $res->row_array();

			$this->db->trans_start();
			$sql = "insert into cooperater (nickname, cpun, cppw, industry,name_manager, phone,email,qq, createtime, usecountgrant, apcountgrant) 
				value (?,?,?,?,?,?,?,?,?,?,?)";
			$res = $this->db->query ($sql, array($nickname,$cpun,$cppw,$industry,$manage,$phone,$email, $qq, time(), $conf['allow_users'],$conf['allow_aps']));
			if(!$res || $this->db->affected_rows() == 0) {
				throw new Exception ('更新用户个人资料数据失败, 请重试');
			}
			$this->data['reson'] = "更新用户个人资料数据成功";
			$this->db->trans_commit();
		}
		catch(Exception $tt) {
			$this->db->trans_rollback();
			$this->data['state'] = "failed";
			$this->data['reson'] = $tt->getMessage(); 
		}
		echo json_encode($this->data);
		exit();
	}	

	public function editcorp (){
		try {
			$nickname = $this->input->post('nickname');
			$cpun = $this->input->post('cpun');
			$cppw = $this->input->post('cppw');
			$industry = $this->input->post('industry');
			$manage = $this->input->post('name_manager');
			$phone = $this->input->post('phone');
			$email = $this->input->post('email');
			$qq = $this->input->post('qq');
			$cid = $this->input->post('cid');

			if(!$nickname || !$industry || !$cpun || !$cppw || !$manage || !$phone || !$cid){
				throw new Exception ('请求失败, 错误码为：2716');
			}

			$res = $this->db->query("select cid from cooperater where cpun = ?", array($cpun));
			if(!$res || $res->num_rows() == 0){
				throw new Exception ('请求失败, 错误码：2717');
			}
			$cppw = substr(md5($cppw), 0, 16);
			$this->db->trans_start();
			$sql = "update cooperater set nickname=?, cpun=?, cppw=?, industry=?,name_manager=?, phone=?,email=?,qq=? where cid = ?";
			$res = $this->db->query ($sql, array($nickname,$cpun,$cppw,$industry,$manage,$phone,$email, $qq, $cid));
			if(!$res) {
				throw new Exception ('更新用户个人资料数据失败, 请重试');
			}
			$this->data['reson'] = "更新用户个人资料数据成功";
			$this->db->trans_commit();
		}
		catch(Exception $tt) {
			$this->db->trans_rollback();
			$this->data['state'] = "failed";
			$this->data['reson'] = $tt->getMessage(); 
		}
		echo json_encode($this->data);
		exit();
	}		

	public function getapgrantlist () {
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			if($sc) {
				$query = "select count(*) total from (ap a, apconfig ac) left join cooperater c on a.cid = c.cid where a.apid = ac.apid and a.apname like ? ";
				$res = $this->db->query($query , array('%'.$sc.'%'));
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql = "select c.cpun, c.nickname, a.apid, a.apname, a.devtype, ac.usecountgrant from (ap a, apconfig ac) left join cooperater c on a.cid = c.cid where a.apid = ac.apid and a.apname like ? order by c.createtime limit ?,?";
				$res = $this->db->query($sql , array('%'.$sc.'%', $page, $rows));
			} else {
				$query = "select count(*) total from (ap a, apconfig ac) left join cooperater c on a.cid = c.cid where a.apid = ac.apid ";
				$res = $this->db->query($query);
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql ="select c.cpun, c.nickname, a.apid, a.apname, a.devtype, ac.usecountgrant from (ap a, apconfig ac) left join cooperater c on a.cid = c.cid where a.apid = ac.apid order by c.createtime limit ?,?";				
				$res = $this->db->query($sql , array($page, $rows));				
			}
			$result = $res->result_array();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$array = array();
				$array['id'] = $row['apid'];
				$array['cpun'] = $row['cpun'];
				$array['nickname'] = $row['nickname'];
				$array['apname'] = $row['apname'];
				$array['devtype'] = $row['devtype'] == "w" ? "网关类": "路由器";
				$array['usecountgrant']	= $row['usecountgrant'];
				$this->data['rows'][] = $array;
			}
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();
	}

	public function checklicence (){
		try{
			$licence = $this->input->post('licence');
			$secret = $this->input->post('secret');
			if(!$licence || !$secret){
				throw new Exception("输入Licence为空");
			}
			if(!isset($_SESSION['secret']) || $_SESSION['secret'] != $secret){
				throw new Exception("请求非法");
			}
			list($state, $reson) = $this->func->checklicence2($licence);
            if($state == "failed"){
            	throw new Exception($reson);
            }
            redirect ("/adc/login");
		}catch (Exception $ec) {
            $data['reson'] = $ec->getMessage();
            $data['access'] = "adc";
            $data['secret'] = $_SESSION['secret'];
            $this->load_view("errors/licence", $data);
		}
	}

}
?>