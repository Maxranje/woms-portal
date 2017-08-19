<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Smsmanage extends CI_Model {
	
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

	public function get_sms_content_list (){
		$res = $this->db->query("select nickname, cid from cooperater");
		$result = $res->result_array ();
		$this->data['corp'] = $result;
		$this->load_view('adc/smscontentlist', $this->data);
	}

	public function get_sms_content_grant_list (){
		$this->load_view('adc/smscontentgrant');
	}

	public function get_sms_content_add_list (){
		$this->load_view('adc/smscontentadd');
	}
	
    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
	public function getcontentlist () {
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;

			$sc = $this->input->post('sc');
			$state = $this->input->post('state');
			$corp = $this->input->post('corp');
			$array = array();

			$sql = "select s.*, c.nickname, c.name_manager, c.phone from smstemplate s left join cooperater c on s.cid = c.cid where valid >= 0 " ;
			if($sc){
				$sql .=" and s.content like ?";
				$array[] = '%'.$sc.'%';
			}

			if($state && $state != "all"){
				$sql .= " and s.valid = ?";
				$array[] = intval(($state - 1));
			}

			if($corp && $corp != "all"){
				$sql .= " and c.cid = ?";
				$array[] = $corp;
			}

			$sql .= " order by s.createtime limit ?,?";
			$array[] = $page;
			$array[] = $rows;
			$res = $this->db->query($sql , $array);
			$result = $res->result_array();

			$this->data['rows'] = array();
			foreach ($result as $row) {
				$array = array();
				$array['smsid']			= $row['smsid'];
				$array['cid']			= $row['cid'];
				$array['content']		= $row['content'];
				$array['createtime']	= date("Y-m-d", $row['createtime']);
				$array['valid']			= $row['valid'];
				$array['reson']			= $row['reson'];	
							
				$array['nickname'] 		= $row['nickname'] == "" ? "SYSTEM" : $row['nickname'];
				$array['name_manager'] = $row['name_manager'] == "" ? "SYSTEM" :$row['name_manager'];
				$array['phone'] = $row['phone'];
				$this->data['rows'][] = $array;
			}
			$this->data['total'] = $res->num_rows();
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();
	}


	public function getsmsgrantlist (){
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			if($sc) {
				$sql = "select s.*, c.nickname from smstemplate s, cooperater c where s.cid = c.cid and c.nickname like ? and s.valid = '0' order by s.createtime limit ?,?";
				$res = $this->db->query($sql , array('%'.$sc.'%', $page, $rows));
			} else {
				$sql = "select s.*, c.nickname from smstemplate s, cooperater c where s.cid = c.cid and s.valid = '0' order by s.createtime limit ?,?";				
				$res = $this->db->query($sql , array($page, $rows));				
			}
			$result = $res->result_array();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$array = array();
				$array['id']			= $row['smsid'];
				$array['content']		= $row['content'];
				$array['createtime']	= date("Y-m-d", $row['createtime']);
				$array['valid']			= $row['valid'];
				$array['reson']			= $row['reson'];	
							
				$array['nickname'] 		= $row['nickname'];
				$this->data['rows'][] = $array;
			}
			$this->data['total'] = $res->num_rows();
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();
	}


	public function smsgrant (){
		try{
			$state = $this->input->post('state'); 
			$smsid = $this->input->post('id');
			if(!$state || !$smsid){
				throw new Exception("请求失败，错误码为：S1755");
			}
			if($state != "1" && $state != "2"){
				throw new Exception("请求失败，错误码为：S1756");
			}

			$sql = "update smstemplate set valid = ? where smsid = ?";				
			$res = $this->db->query($sql , array($state, $smsid));
			if($this->db->affected_rows() == 0){
				throw new Exception("更新短信内容模版失败, 错误码：S176".$smsid);
			}
			$this->data['reson'] = "更新成功";
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}


	public function addsmstmp (){
		try{
			$smscontent = $this->input->post('smscontent'); 
			if(!$smscontent){
				throw new Exception("请求失败，错误码为：S1775");
			}

			$sql = "insert into smstemplate (cid, content, createtime, valid) value (?,?,?,?)";				
			$res = $this->db->query($sql , array('0', $smscontent, time(), '1'));
			if($this->db->affected_rows() == 0){
				throw new Exception("添加短信内容模版失败, 错误码：S1778");
			}
			$this->data['reson'] = "添加完成, 请在短信内容查看中查阅， 该模版所有用户可用";
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