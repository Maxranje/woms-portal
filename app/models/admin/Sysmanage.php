<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sysmanage extends CI_Model {
	
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

	public function get_sys_conf_page (){
		$res = $this->db->query( "select * from sysconfig" );
		$this->data['conf'] = $res->row_array();
		$this->load_view('adc/system_conf', $this->data);
	}

	public function get_sys_notice_page (){
		$res = $this->db->query ('select id, title, addtime from sysnotice where display = "1"');
		$result = $res->result_array ();
		for ($i = 0; $i < count($result); $i++) {
			$result[$i]['addtime'] = date('Y-m-d', $result[$i]['addtime']);
		}
		$this->data['notice'] = $result;
		$this->load_view('adc/sysnotice_list', $this->data);
	}

	public function get_add_sys_notice_page (){
		$this->load_view('adc/sysnotice_add');
	}
	
    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
	public function getnoticelist () {
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			$sc = $this->input->post('sc');

			if($sc) {
				$query = "select count(*) total from sysnotice where display = '1' and title like ?";
				$res = $this->db->query($query , array('%'.$sc.'%'));
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql = "select * from sysnotice where display = '1' and title like ? order by addtime limit ?, ? " ;
				$res = $this->db->query($sql , array('%'.$sc.'%', $page, $rows));
			} else {
				$res = $this->db->query("select count(*) total from sysnotice where display = '1'");
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql = "select * from sysnotice where display = '1' order by addtime limit ?, ? " ;
				$res = $this->db->query($sql , array($page, $rows));				
			}
			$result = $res->result_array();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$array = array();
				$array['id']			= $row['id'];
				$array['title']			= $row['title'];
				$array['content']		= $row['content'];
				$array['createtime']	= date("Y-m-d", $row['addtime']);
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


	public function addsystemnotice (){
		try{
			$title = $this->input->post('title'); 
			$content = $this->input->post('msg');
			if(!$title || !$content){
				throw new Exception ('请求无效, 公告标题或内容为空');
			}
			$query = "insert into sysnotice (type, title, content, addtime, display) value (?,?,?,?,?)";
			$res = $this->db->query ($query, array('c', $title, $content, time(), '1' ));
			if($this->db->affected_rows() == 0){
				throw new Exception ('添加失败，错误码为：S1745');
			}
			$this->data['reson'] = "添加公告成功, 所有商户都会收到公告消息";
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();
	}


	public function removenotice (){
		try{
			$id = $this->input->post('id');
			if(!$id){
				throw new Exception("请求失败，错误码为：S1746");
			}
			$res = $this->db->query ("delete from sysnotice where id = ?", array($id));				
			if($this->db->affected_rows() == 0){
				throw new Exception("删除系统公告失败, 错误码：S1747".$smsid);
			}
			$this->data['reson'] = "删除系统公告成功";
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}


	public function sysconfig (){
		try{
			$defaultap 		= $this->input->post('defaultap'); 
			$defaultuser 	= $this->input->post('defaultuser'); 
			$defaulttmp 	= $this->input->post('defaulttmp'); 
			$redirect 		= $this->input->post('redirect'); 

			$redirect = !$redirect ? "http://www.baidu.com" : $redirect;
			$redirect = urlencode($redirect);
			$defaultap = !$defaultap ? 0 : $defaultap;
			$defaultuser = !$defaultuser ? 0 : $defaultuser;
			$defaulttmp = !$defaulttmp ? 0 : $defaulttmp;

			$res = $this->db->query( "select * from sysconfig" );
			if($res->num_rows() != 0){
				$sql = "update sysconfig set allow_aps = ?, allow_users =?, defaulttmpid=?, redirect=? where id =1";
			}else{
				$sql = "insert into sysconfig (allow_aps, allow_users, defaulttmpid, redirect) value (?,?,?,?)";	
			}
			$res = $this->db->query($sql , array($defaultap, $defaultuser, $defaulttmp, $redirect));
			$this->data['reson'] = "配置成功";
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