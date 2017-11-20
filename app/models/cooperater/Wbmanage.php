<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Wbmanage extends CI_Model {
	
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
	public function get_black_list_page (){
		$this->load_view('authblacklist');
	}
	
	public function get_black_item_page (){
		$res = $this->db->query ("select apid, apname from ap where cid = ?", array($_SESSION['cid']));
		$this->data['ap'] = $res->result_array ();
		$this->load_view('addblackitem', $this->data);
	}

	public function get_edit_black_item_page(){
		$id = $this->input->post('id');
		if(!$id){
			throw new Exception("请求参数不正确，无法获取数据");
		}
		$res = $this->db->query ("select * from blacklist where id = ? and cid = ?", array($id, $_SESSION['cid']));
		if(!$res || $res->num_rows() == 0){
			throw new Exception("黑名单条目不存在");
		}
		$this->data['item'] = $res->row_array ();
		$res = $this->db->query ("select apid, apname from ap where cid = ?", array($_SESSION['cid']));
		$this->data['ap'] = $res->result_array ();
		$this->load_view('editblackitem', $this->data);		
	}

	public function get_white_list_page (){
		$this->load_view('authwhitelist');
	}

	public function get_white_item_page (){
		$res = $this->db->query ("select apid, apname from ap where cid = ? and protocol = 'w'", array($_SESSION['cid']));
		$this->data['ap'] = $res->result_array ();		
		$this->load_view('addwhiteitem', $this->data);
	}

	public function get_edit_white_item_page (){
		$id = $this->input->post('id');
		if(!$id){
			throw new Exception("请求参数不正确，无法获取数据");
		}
		$res = $this->db->query ("select * from whitelist where id = ? and cid = ?", array($id, $_SESSION['cid']));
		if(!$res || $res->num_rows() == 0){
			throw new Exception("白名单条目不存在");
		}
		$this->data['item'] = $res->row_array ();
		$res = $this->db->query ("select apid, apname from ap where cid = ? and protocol = 'w'", array($_SESSION['cid']));
		$this->data['ap'] = $res->result_array ();
		$this->load_view('editwhiteitem', $this->data);				
	}

    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/

	public function getblacklist(){
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			if(!$sc) {
				$query = "select count(*) as total from blacklist b where b.cid = ?";
				$res = $this->db->query($query , array($_SESSION['cid']));
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql = "select ifnull(a.apname, 'all') as apname, b.* from blacklist b left join ap a on a.apid = b.apid where b.cid = ? order by createtime desc limit ?, ?";
				$res = $this->db->query($sql , array($_SESSION['cid'], $page, $rows));
			} else {
				$query = "select count(*) as total from blacklist b where b.cid = ? and b.content like ?";
				$res = $this->db->query($query , array($_SESSION['cid'], '%'.$sc.'%'));
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql = "select ifnull(a.apname, 'all') as apname, b.* from blacklist b left join ap a on a.apid = b.apid where b.cid = ? and b.content like ? order by createtime desc limit ?, ?";
				$res = $this->db->query($sql , array($_SESSION['cid'], '%'.$sc.'%', $page, $rows));				
			}
			$result = $res->result_array();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$account = array();
				$account['apname']	 	= $row['apname'] == "all"?"所有热点":$row['apname'];
				$account['time'] 	 	= date('Y-m-d', $row['createtime']);
				$account['validtime'] 	= date('Y-m-d', $row['validtime']);
				$account['bname'] 		= $row['content'];
				$account['type'] 		= $row['type'] == "m" ? "MAC 黑名单" : "手机黑名单";
				$account['id'] 			= $row['id'];
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
	public function blackremove(){
		try{
			$id = $this->input->post('id');
			if(!$id){
				throw new Exception ('无删除选项');
			}
			$this->db->trans_start();
			$query = "delete from blacklist where id = ? and cid = ?";
			$res = $this->db->query($query, array($id, $_SESSION['cid']));
			if(!$res || $this->db->affected_rows ()== 0){
				throw new Exception ('删除黑名单失败, 黑名单不存在');
			}
			$this->db->trans_commit();
			$this->data['reson']="删除黑名单成功";
		}
		catch (Exception $ae){
			$this->db->trans_rollback();
			$this->data['state'] = "failed";
			$this->data['reson'] = $ae->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}

	public function addblack (){
		try{
			$blacktype = $this->input->post('blacktype');
			$content = $this->input->post('content');
			$ap = $this->input->post('ap');
			$remark = $this->input->post('remark');
			$datetime = $this->input->post('datetime');

			if(!$blacktype || !$content || !$datetime ){
				throw new Exception ("请求失败, 错误码: WB1574");
			}
			$ap = intval($ap);
			$content = strtoupper($content);
			$datetime = strtotime($datetime);
			if($datetime <= time()){
				throw new Exception ("创建失败, 当前日期不可以作为截至日期使用");
			}

			$this->db->trans_start();
			$sql = "select id from blacklist where cid = ? and content = ?";
			$res = $this->db->query($sql, array($_SESSION['cid'], $content));
			if(!$res || $res->num_rows() > 0){
				throw new Exception("黑名单存在，重新添加");
			}
			$query = 'insert into blacklist (`type`, `apid`,`cid`, `createtime`, `validtime`, `content`, `remark`) values (?,?,?,?,?,?,?) ';
			$res = $this->db->query($query, array($blacktype, $ap, $_SESSION['cid'], time(), $datetime, $content, $remark));
			if(!$res || $this->db->affected_rows() == 0) {   
				throw new Exception ("创建黑名单失败");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "创建黑名单创建成功";

		} catch(Exception $ec) { 
			$this->db->trans_rollback();  			
			$this->data['reson']=$ec->getMessage();
			$this->data['state']="failed";		
		}
		echo json_encode($this->data);
		exit();
	}


	public function blackedit (){
		try{
			$blacktype = $this->input->post('blacktype');
			$content = $this->input->post('content');
			$ap = $this->input->post('ap');
			$id = $this->input->post('id');
			$remark = $this->input->post('remark');
			$datetime = $this->input->post('datetime');

			if(!$blacktype || !$content || !$id || !$datetime){
				throw new Exception ("请求失败, 错误码: WB1612");
			}
			$ap = intval($ap);
			$id = intval($id);
			$content = strtoupper($content);
			$datetime = strtotime($datetime);
			
			if($datetime <= time()){
				throw new Exception ("创建失败, 当前日期不可以作为截至日期使用");
			}

			$this->db->trans_start();
			$sql = "select content from blacklist where cid = ? and content = ? and id != ? ";
			$res = $this->db->query($sql, array($_SESSION['cid'], $content, $id));
			if(!$res || $res->num_rows() > 0){
				throw new Exception("黑名单内容已经存在，重新添加");
			}
			$query = 'update blacklist set `type`=?,`apid`=?,`content`=?,`validtime`=?,`remark`=? where id=?';
			$res = $this->db->query($query, array($blacktype, $ap, $content, $datetime, $remark, $id));
			if(!$res) {   
				throw new Exception ("黑名单修改失败");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "黑名单修改成功";

		} catch(Exception $ec) { 
			$this->db->trans_rollback();  			
			$this->data['reson']=$ec->getMessage();
			$this->data['state']="failed";		
		}
		echo json_encode($this->data);
		exit();		
	}


	# 白名单
	# 
	public function getwhitelist(){
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			if(!$sc) {
				$query = "select count(*) from whitelist b left join ap a on a.apid = b.apid and a.protocol='w' where b.cid = ?";
				$res = $this->db->query($query , array($_SESSION['cid']));
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql = "select ifnull(a.apname, 'all') as apname, b.* from whitelist b left join ap a on a.apid = b.apid and a.protocol='w' where b.cid = ? order by createtime desc limit ?, ?";
				$res = $this->db->query($sql , array($_SESSION['cid'], $page, $rows));
			} else {
				$query = "select count(*) as total from whitelist b left join ap a on a.apid = b.apid and a.protocol='w' where b.cid = ? and b.content like ?";
				$res = $this->db->query($query , array($_SESSION['cid'], '%'.$sc.'%'));
				$result = $res->row_array();
				$this->data['total'] = $result['total'];

				$sql = "select ifnull(a.apname, 'all') as apname, b.* from whitelist b left join ap a on a.apid = b.apid and a.protocol='w' where b.cid = ? and b.content like ? order by createtime desc limit ?, ?";
				$res = $this->db->query($sql , array($_SESSION['cid'], '%'.$sc.'%', $page, $rows));				
			}
			$result = $res->result_array();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$account = array();
				$account['apname'] = $row['apname'] == "all"?"所有WIFIDOG热点":$row['apname'];
				$account['time'] = date('Y-m-d', $row['createtime']);
				$account['wname'] = $row['content'];
				$account['type'] = $row['type'] == "m" ? "MAC 白名单" : "白名单";
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



	public function whiteremove(){
		try{
			$id = $this->input->post('id');
			if(!$id){
				throw new Exception ('无删除选项');
			}
			$this->db->trans_start();
			$query = "delete from whitelist where id = ? and cid = ?";
			$res = $this->db->query($query, array($id, $_SESSION['cid']));
			if(!$res || $this->db->affected_rows ()== 0){
				throw new Exception ('删除白名单失败');
			}
			$this->db->trans_commit();
			$this->data['reson']="删除白名单成功";
		}
		catch (Exception $ae){
			$this->db->trans_rollback();
			$this->data['state'] = "failed";
			$this->data['reson'] = $ae->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}

	public function addwhite (){
		try{
			$whitetype = $this->input->post('whitetype');
			$content = $this->input->post('content');
			$ap = $this->input->post('ap');
			$remark = $this->input->post('remark');
			$crossplat = $this->input->post('crossplat');

			if(!$whitetype || !$content ){
				throw new Exception ("参数不正确，无法添加");
			}
			$crossplat = !$crossplat ? '0' : '1';
			$ap = intval($ap);
			$content = strtoupper($content);

			$this->db->trans_start();
			$sql = "select id from whitelist where cid = ? and content = ?";
			$res = $this->db->query($sql, array($_SESSION['cid'], $content));
			if(!$res || $res->num_rows() > 0){
				throw new Exception("白名单存在，重新添加");
			}
			$query = 'insert into whitelist (`type`, `apid`,`cid`, `createtime`, `content`, `remark`, `crossplat`) values (?,?,?,?,?,?,?) ';
			$res = $this->db->query($query, array($whitetype, $ap, $_SESSION['cid'], time(), $content, $remark, $crossplat));
			if(!$res || $this->db->affected_rows() == 0) {   
				throw new Exception ("创建白名单失败");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "创建白名单创建成功";

		} catch(Exception $ec) { 
			$this->db->trans_rollback();  			
			$this->data['reson']=$ec->getMessage();
			$this->data['state']="failed";		
		}
		echo json_encode($this->data);
		exit();
	}

	public function whiteedit (){
		try{
			$whitetype = $this->input->post('whitetype');
			$content = $this->input->post('content');
			$ap = $this->input->post('ap');
			$id = $this->input->post('id');
			$remark = $this->input->post('remark');
			$crossplat = $this->input->post('crossplat');

			if(!$whitetype || !$content || !$id){
				throw new Exception ("参数不正确，无法添加");
			}
			$crossplat = !$crossplat ? '0' : '1';
			$ap = intval($ap);
			$id = intval($id);
			$content = strtoupper($content);

			$this->db->trans_start();
			$sql = "select content from whitelist where cid = ? and content = ? and id != ? ";
			$res = $this->db->query($sql, array($_SESSION['cid'], $content, $id));
			if(!$res || $res->num_rows() > 0){
				throw new Exception("白名单已经存在，重新添加");
			}
			$query = 'update whitelist set `type`=?,`apid`=?,`content`=?,`remark`=?, `crossplat`=? where id=?';
			$res = $this->db->query($query, array($whitetype, $ap, $content, $remark, $crossplat,$id));
			if(!$res) {   
				throw new Exception ("白名单修改失败");
			}
			$this->db->trans_commit();
			$this->data['reson'] = "白名单修改成功";

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