<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Authmanage extends CI_Model {
	
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

	public function get_manageval_page (){
		$this->load_view('manageval');
	}

	public function get_add_validate_page(){
		$res = $this->db->query("select apid, apname from ap where cid = ?", array($_SESSION['cid']));
		$this->data['ap']  = $res->result_array();
		$this->load_view('addvalidate', $this->data);
	}

	public function get_edit_validate_page(){
		$id = $this->input->post('id');
		if(!$id ){
			throw new Exception ("参数不正确，无法添加");
		}
		$query = "select * from authuser where id = ? and cid = ?";
		$res = $this->db->query ($query, array($id, $_SESSION['cid']));
		if(!$res || $res->num_rows () == 0){
			throw new Exception ("查询不到具体信息");	
		}
		$result = $res->row_array();
		$this->data['user'] = $result;
		$res = $this->db->query("select apid, apname from ap where cid = ?", array($_SESSION['cid']));
		$this->data['ap']  = $res->result_array();		
		$this->load_view('editvalidate', $this->data);
	}

    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
	public function accountlist(){
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			if(!$sc) {
				$sql = "select (select count(*) from user u where u.uid = au.id) as usedcount, ifnull(a.apname, 'all') as apname, au.* from authuser au left join ap a on a.apid = au.apid where au.cid = ? order by createtime desc limit ?, ?";
				$res = $this->db->query($sql , array($_SESSION['cid'], $page, $rows));
			} else {
				$sql = "select (select count(*) from user u where u.uid = au.id) as usedcount, ifnull(a.apname, 'all') as apname, au.* from authuser au left join ap a on a.apid = au.apid where u.cid = ? and au.uname like ? order by createtime desc limit ?, ?";
				$res = $this->db->query($sql , array($_SESSION['cid'], '%'.$sc.'%', $page, $rows));				
			}
			$result = $res->result_array();
			$this->data['total'] = $res->num_rows();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$account = array();
				$account['apname'] = $row['apname'] == "all"?"所有热点":$row['apname'];
				$account['time'] = date('Y-m-d', $row['expiredate']);
				$account['usedcount'] = $row['logincount'];
				$account['uname'] = $row['uname'];
				$account['upass'] = $row['upasswd'];
				$account['id'] = $row['id'];
				$this->data['rows'][] = $account;
			}
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}



	public function accountremove(){
		try{
			$id = $this->input->post('id');
			if(!$id){
				throw new Exception ('无删除选项');
			}
			$this->db->trans_start();
			$query = "delete from authuser where id = ? and cid = ?";
			$res = $this->db->query($query, array($id, $_SESSION['cid']));
			if(!$res){
				throw new Exception ('删除帐号失败, 系统错误:4003');
			}
			$this->db->trans_commit();
			$this->data['reson']="删除帐号成功";
		}
		catch (Exception $ae){
			$this->db->trans_rollback();
			$this->data['state'] = "failed";
			$this->data['reson'] = $ae->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}	


	public function addaccount(){
		try{
			$uname = $this->input->post('uname');
			$upass = $this->input->post('upass');
			$ap = $this->input->post('ap');
			$validatetime = $this->input->post('validatetime');
			$mutilogin = $this->input->post('mutilogin');
			$mutilcount = $this->input->post('mutilcount');
			$state = $this->input->post('state');


			if(!$uname || !$upass || !$validatetime ){
				throw new Exception ("请求失败, 错误码: A1667");
			}
			$mutilogin = !$mutilogin ? '0' :'1';
			$state = $state == "off" ? '0' :'1';
			if($mutilogin == '0' || !$mutilcount){
				$mutilcount = 0;
			}
			$validatetime = strtotime($validatetime);
			if($validatetime <= time()){
				throw new Exception ("请求失败, 有效时间输入错误, 错误码: A1668");
			}
			$this->db->trans_start();
			
			$sql = "select id from authuser where cid = ? and uname = ? and apid = ?";
			$res = $this->db->query($sql, array($_SESSION['cid'], $uname, $ap));
			if(!$res || $res->num_rows() > 0){
				throw new Exception("用户名存在，重新添加");
			}
			$query = 'insert into authuser (`uname`, `upasswd`, `apid`,`cid`, `expiredate`, `mutilogin`, `mutilcount`, `createtime`, `state`)
			 values (?,?,?,?,?,?,?,?,?) ';
			$res = $this->db->query($query, array($uname,$upass, $ap, $_SESSION['cid'],$validatetime,
			 	$mutilogin, $mutilcount,  time(), $state));
			if(!$res || $this->db->affected_rows() == 0) {   
				throw new Exception ("创建验证信息失败");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "创建账户 ".$uname."成功";

		} catch(Exception $ec) { 
			$this->db->trans_rollback();  			
			$this->data['reson']=$ec->getMessage();
			$this->data['state']="failed";		
		}
		echo json_encode($this->data);
		exit();
	}

	public function accountedit (){
		try{
			$uname = $this->input->post('uname');
			$upasswd = $this->input->post('upasswd');
			$ap = $this->input->post('ap');
			$validatetime = $this->input->post('validatetime');
			$mutilogin = $this->input->post('mutilogin');
			$mutilcount = $this->input->post('mutilcount');
			$state = $this->input->post('state');
			$id = $this->input->post('id');

			if(!$id ){
				throw new Exception ("请求失败, 错误码: A1667");
			}
			if(!$uname || !$upasswd || !$validatetime ){
				$thi->data['id'] = $id;
				$this->load_view('editvalidate', $this->data);
			}

			$mutilogin = !$mutilogin ? '0' :'1';
			$state = !$state ? '0' :'1';
			if($mutilogin == '0' || !$mutilcount){
				$mutilcount = 0;
			}
			$validatetime = strtotime($validatetime);
			if($validatetime <= time()){
				throw new Exception ("请求失败, 有效时间输入错误, 错误码: A1768");
			}

			$this->db->trans_start();

			$query = 'update authuser set uname=?,upasswd=?,apid=?,expiredate=?,mutilogin=?,mutilcount=?, state=? where id=? and cid =?';
			$res = $this->db->query($query, array($uname,$upasswd,$ap, $validatetime,
			 	$mutilogin, $mutilcount, $state, $id, $_SESSION['cid'] ));
			if(!$res) {   
				throw new Exception ("更新auth账户信息失败, 请重新尝试");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "更新auth验证账户 ".$uname." 信息成功";

		} catch(Exception $ec) { 
			$this->db->trans_rollback();  			
			$this->data['reson']=$ec->getMessage();
			$this->data['state']="failed";		
		}
		echo json_encode($this->data);
		exit();
	}
}
?>