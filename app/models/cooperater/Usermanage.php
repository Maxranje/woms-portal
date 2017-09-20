<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Usermanage extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('Func');
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete');
	}

    /***
        *******************************************
        *          show info page                 *
        *******************************************
    ***/
	public function get_stattistics_page (){
		$id = $this->input->post('id');
		if ($id){
			$_SESSION['rediect_id'] = $id;
			$this->data['apid'] = $id;
		}
		$res = $this->db->query("select apid, apname from ap where cid = ?", array($_SESSION['cid']));
		$result = $res->result_array ();
		$this->data['ap'] = $result;
		$this->load_view('authstatistics', $this->data);
	}


    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
	public function getuserlist (){
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$protocol = $this->input->post('protocol');
			$state = $this->input->post('state');
			$type = $this->input->post('type');
			$apid = $this->input->post('apid');

			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;

			if(isset($_SESSION['rediect_id']) && !empty($_SESSION['rediect_id'])){
				$apid = $_SESSION['rediect_id'];
				$state = 'on';
				unset($_SESSION['rediect_id']);
			}

			$query = "select a.protocol,a.apname, u.*, ul.* from user u, usedlog ul, ap a where a.apid = u.apid and u.uid = ul.uid and u.cid = ?";
			$array = array($_SESSION['cid']);
			if($sc){
				$array[] = '%'.$sc.'%';
				$query .= " and u.uname like ?";
			}
			if($protocol && $protocol != "all"){
				$array[] = $protocol;
				$query .= " and a.protocol=?";
			}
			if($type && $type != "all"){
				$array[] = $type;
				$query .= " and u.acctype=?";
			}
			if($apid && $apid != "all"){
				$array[] = $apid;
				$query .= " and a.apid=?";
			}
			if($state && $state != "all"){
				$state = $state == "on" ? "1" : "0";
				$array[] = $state;
				$query .= " and state=?";
			}
			$query .= " order by a.createtime desc limit ?, ?";
			$array[] = $page;
			$array[] = $rows;
			$res = $this->db->query ($query, $array);
			$result = $res->result_array();
			$total = $res->num_rows();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$ap = array();
				$ap['id'] = $row['uid'];
				$ap['apname'] = $row['apname'];
				$ap['uname'] = $row['uname'];
				$ap['state'] = $row['state'];
				$ap['type'] = $row['acctype']=='c'?"帐号认证":($row['acctype']=='m'?'MAC白名单认证':($row['acctype']=='w'?"微信认证":($row['acctype']=='n'?"一键认证":"短信认证")));
				$ap['starttime'] = date('Y-m-d H:i:s', $row['starttime']);
				$ap['ip'] = $row['ip'];
				$ap['mac'] = $row['mac'];
				$ap['totaltime'] = $this->func->sec2time($row['totaltime']);
				$ap['tobeoffline'] = $row['tobeoffline'];
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

	public function offline (){
		try{
			$id = $this->input->post('id'); 
			$query = "update user set tobeoffline = '1' where uid = ? and cid = ? and state = '1'";
			$res = $this->db->query ($query, array($id, $_SESSION['cid']));
			if(!$res || $this->db->affected_rows() == 0){
				throw new Exception("强制下线未成功或没有强制下线设备");
			}
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}

	public function alloffline (){
		try{
			$query = "update user set tobeoffline = '1' where cid = ? and state = '1'";
			$res = $this->db->query ($query, array($_SESSION['cid']));
			if(!$res || $this->db->affected_rows() == 0){
				throw new Exception("强制下线未成功或没有强制下线设备");
			}
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}

	public function showinfo (){
		try{
			$id = $this->input->post('id'); 
			$query = "select u.*, ul.* from usedlog ul, user u where u.uid = ? and u.uid = ul.uid and cid = ?";
			$res = $this->db->query ($query, array($id, $_SESSION['cid']));
			if(!$res || $res->num_rows() == 0){
				throw new Exception("查询失败");
			}
			$info = $res->row_array();
			$this->data['info']['name'] = $info['uname'];
			$this->data['info']['type'] = $info['acctype']=='c'?"帐号认证":($info['acctype']=='m'?'MAC白名单认证':($info['acctype']=='w'?"微信认证":($info['acctype']=='n'?"一键认证":"短信认证")));
			$this->data['info']['allup'] 	= $this->func->easybyte ($info['allbyte_up']);
			$this->data['info']['alldown'] 	=  $this->func->easybyte ($info['allbyte_down']);
			$this->data['info']['userup']   =  $this->func->easybyte ($info['usedbyte_down']);
			$this->data['info']['userdown'] =  $this->func->easybyte ($info['usedbyte_up']);
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}
}
?>