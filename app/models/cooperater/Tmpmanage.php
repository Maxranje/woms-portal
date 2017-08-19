<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tmpmanage extends CI_Model {
	
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


    public function get_template_page (){
    	$type = $this->input->get('type');
    	if(!$type || $type == "all"){
    		$this->data['type'] = "all";
    		$sql = "select * from template where cid = ? UNION select * from template where cid = 0";	
    		$res = $this->db->query ($sql, array($_SESSION['cid']));
    	} else if($type == "glb"){	
    		$this->data['type'] = "glb";
    		$res = $this->db->query ("select * from template where cid = 0");
    	} else if($type == "cus"){	
    		$this->data['type'] = "cus";
    		$sql = "select * from template where cid = ?";	
    		$res = $this->db->query ($sql, array($_SESSION['cid']));
    	} else{
    		throw new Exception("Error Processing Request", 1);
    	}
		$tmp = $res->result_array();
		$this->data['tmp'] = $tmp;
		$this->load_view('authtemplate', $this->data);
    }

    public function get_add_sp_template_page (){
    	$this->load_view('addtemplate_sp');	
    }

    public function get_add_tp_template_page (){
    	$this->load_view('addtemplate_tp');	
    }

    public function get_add_wp_template_page (){
    	$this->load_view('addtemplate_wp');	
    }        

    public function  get_edit_template_page() {
		$tid = $this->input->post('tid');
		if(!$tid){
			throw new Exception("Error Processing Request");
		}
		$query = "select * from template where id =?";
		$res = $this->db->query ($query, array($tid));
		if(!$res || $res->num_rows()== 0 ){
			throw new Exception("请求失败, 要修改的模板不存在, 错误码为：T1443");
		}
		$this->data['tmp'] = $res->row_array();
		$this->load_view("edittemplate", $this->data);
    }

    public function get_sms_tmp_page (){
    	$this->load_view('authsmstmp');
    }

    public function get_add_sms_tmp_page(){
    	$this->load_view('addauthsmstmp');
    }

    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
	public function addtemplate () {
		try{
			$type = $this->input->post('type');
			$name = $this->input->post('name');
			$title = $this->input->post('title');
			$content = $this->input->post('content');

			if(!$name || !$type){
				throw new Exception("请求失败, 错误码:T1568");
			}

			$query = "select * from template where name = ? and cid = ?";
			$res = $this->db->query ($query, array($name, $_SESSION['cid']));
			if($res->num_rows() > 0){
				throw new Exception("模版名称已存在, 请重新命名");
			}

			$title = !$title ? "欢迎使用无限热点" : $title;
			$content = !$content ? "欢迎使用无限热点" : $content;
			$type = $type == "sp"?1:($type == 'wp'?2:3);

			if($type == '1' || $type== '2'){
				$phonebg = $this->do_upload_file('phonebg');
				$phonead1 = $this->do_upload_file('phonead1');
				$phonead2 = $this->do_upload_file('phonead2');
				$phonead3 = $this->do_upload_file('phonead3');
				$phonead = json_encode(array($phonead1, $phonead2, $phonead3));

				$pcbg = $this->do_upload_file('pcbg');
				$pcad1 = $this->do_upload_file('pcad1');
				$pcad2 = $this->do_upload_file('pcad2');
				$pcad3 = $this->do_upload_file('pcad3');
				$pcad = json_encode(array($pcad1, $pcad2, $pcad3));		
			}else{
				$phonebg1 = $this->do_upload_file('phonebg1');
				$phonebg2 = $this->do_upload_file('phonebg2');
				$phonebg3 = $this->do_upload_file('phonebg3');
				$phonebg = json_encode(array($phonebg1, $phonebg2, $phonebg3));
				$phonead = "";

				$pcbg1 = $this->do_upload_file('pcbg1');
				$pcbg2 = $this->do_upload_file('pcbg2');
				$pcbg3 = $this->do_upload_file('pcbg3');
				$pcbg = json_encode(array($pcbg1, $pcbg2, $pcbg3));	
				$pcad = "";		
			}

			$query = "insert into template (cid, name, title, createtime,content, type, phone_bgpic, phone_adpic, pc_bgpic, pc_adpic, state) value (?,?,?,?,?,?,?,?,?,?,?)";
			$res = $this->db->query($query, array($_SESSION['cid'], $name, $title, time(),$content,$type,$phonebg,$phonead, $pcbg, $pcad,'0'));
			if (!$res ||$this->db->affected_rows() == 0){
				throw new Exception("创建模版失败，更新数据信息失败");
			}
			$this->data['reson'] = "模板创建成功, 请查收";
		}catch (Exception $ec){
			$this->data['reson'] = $ec->getMessage();
			$this->data['state'] = "failed";
		}
		echo json_encode($this->data);
		exit();
	}	

	// 上传单个文件
	public function do_upload_file ($filename){
		if (!isset($_FILES[$filename])){
			return "";
		}
		$name = "";
 		if ( is_uploaded_file ( $_FILES [$filename][ 'tmp_name' ])) {
			$endname = substr($_FILES[$filename]['name'], strripos($_FILES[$filename]['name'], "."), strlen($_FILES[$filename]['name']));;
			$name = 'u'.$_SESSION['cid'].mt_rand(1111,9999).time().$endname; 			
			move_uploaded_file($_FILES[$filename]['tmp_name'], FCPATH."res/images/template/".$name);
		}
		return $name;
	}	

	public function removetmp (){
		try{
			$tid = $this->input->post('id');
			if(!$tid){
				throw new Exception("请求失败, 错误码为：T1444");
			}
			$query = "select * from template where id = ? and cid = ?";
			$res = $this->db->query($query, array($tid, $_SESSION['cid']));
			if($res->num_rows() == 0){
				throw new Exception("请求错误, 被删除模版不存在, 请刷新页面重试, 错误码: T1567");
			}
			$result = $res->row_array();
			
			$sql = "delete from template where id = ? and cid =?";
			$res = $this->db->query($sql, array($tid, $_SESSION['cid']));
			if (!$res){
				throw new Exception("请求错误, 删除模版失败, 错误码: T1568");
			}

			$phone_bgpic = $result['phone_bgpic'];
			$phone_adpic = $result['phone_adpic'];
			$pc_bgpic = $result['pc_bgpic'];
			$pc_adpic = $result['pc_adpic'];	

			if($result['type'] == "3"){
				$phone_bgpic = json_decode($phone_bgpic, true);
				$pc_bgpic = json_decode($pc_bgpic, true);
				$pc_bgpic = array();
				$pc_adpic = array();
			}else{
				$phone_adpic = json_decode($phone_adpic, true);
				$pc_adpic = json_decode($pc_adpic, true);
				$phone_bgpic = array($phone_bgpic);
				$pc_bgpic = array($pc_bgpic);
			}

			$array = array_merge($phone_adpic, $phone_bgpic, $pc_adpic, $pc_bgpic);

			foreach ($array as $index) {
				if(!empty($index) && file_exists(FCPATH."./res/images/template/".$index)){
					unlink(FCPATH."./res/images/template/".$index);
				}
			}
					
			$this->data['reson'] = "认证模版删除成功！";
		}catch(Exception $d ){
			$this->data['state'] = 'failed';
			$this->data['reson'] =  $d->getMessage();
		}
		echo json_encode($this->data);
		exit();		
	}


	public function edittemplate () {
		try{
			$id = $this->input->post("id");
			$type = $this->input->post('type');
			$name = $this->input->post('name');
			$title = $this->input->post('title');
			$content = $this->input->post('content');

			if(!$name || !$type || !$id){
				throw new Exception("请求失败, 请刷新页面 ,错误码:T1568");
			}

			$query = "select * from template where name = ? and cid = ?";
			$res = $this->db->query ($query, array($name, $_SESSION['cid']));
			if($res->num_rows() == 0){
				throw new Exception("修改模板不存在， 或已被删除， 请刷新重新编辑");
			}
			$tmp = $res->row_array ();

			$title = !$title ? "欢迎使用无限热点" : $title;
			$content = !$content ? "欢迎使用无限热点" : $content;
			$type = $type == "sp"?1:($type == 'wp' ? 2 : 3);

			
			if($type == '1' || $type== '2'){
				$tmp['phone_adpic'] = json_decode($tmp["phone_adpic"], true); 
				$phonebg  = $this->do_upload_file('phonebg');
				$phonead1 = $this->do_upload_file('phonead1');
				$phonead2 = $this->do_upload_file('phonead2');
				$phonead3 = $this->do_upload_file('phonead3');

				$phonebg  = empty($phonebg) ? $tmp['phone_bgpic'] : $phonebg;
				$phonead1 = empty($phonead1) ? $tmp['phone_adpic'][0] : $phonead1;
				$phonead2 = empty($phonead2) ? $tmp['phone_adpic'][1] : $phonead2;
				$phonead3 = empty($phonead3) ? $tmp['phone_adpic'][2] : $phonead3;

				$phonead = json_encode(array($phonead1, $phonead2, $phonead3));


				$tmp['pc_adpic'] = json_decode($tmp["pc_adpic"], true); 
				$pcbg  = $this->do_upload_file('pcbg');
				$pcad1 = $this->do_upload_file('pcad1');
				$pcad2 = $this->do_upload_file('pcad2');
				$pcad3 = $this->do_upload_file('pcad3');

				$pcbg  = !$pcbg ? $tmp['pc_bgpic'] : $pcbg;
				$pcad1 = !$pcad1 ? $tmp['pc_adpic'][0] : $pcad1;
				$pcad2 = !$pcad2 ? $tmp['pc_adpic'][1] : $pcad2;
				$pcad3 = !$pcad3 ? $tmp['pc_adpic'][2] : $pcad3;

				$pcad = json_encode(array($pcad1, $pcad2, $pcad3));		
			}else{
				$tmp['phone_bgpic'] = json_decode($tmp["phone_bgpic"], true); 
				$phonebg1 = $this->do_upload_file('phonebg1');
				$phonebg2 = $this->do_upload_file('phonebg2');
				$phonebg3 = $this->do_upload_file('phonebg3');

				$phonebg1 = !$phonebg1 ? $tmp['phone_bgpic'][0] : $phonebg1;
				$phonebg2 = !$phonebg2 ? $tmp['phone_bgpic'][1] : $phonebg2;
				$phonebg3 = !$phonebg3 ? $tmp['phone_bgpic'][2] : $phonebg3;

				$phonebg = json_encode(array($phonebg1, $phonebg2, $phonebg3));
				$phonead = "";

				$tmp['pc_bgpic'] = json_decode($tmp["pc_bgpic"], true); 
				$pcbg1 = $this->do_upload_file('pcbg1');
				$pcbg2 = $this->do_upload_file('pcbg2');
				$pcbg3 = $this->do_upload_file('pcbg3');

				$pcbg1 = !$pcbg1 ? $tmp['pc_bgpic'][0] : $pcbg1;
				$pcbg2 = !$pcbg2 ? $tmp['pc_bgpic'][1] : $pcbg2;
				$pcbg3 = !$pcbg3 ? $tmp['pc_bgpic'][2] : $pcbg3;

				$pcbg = json_encode(array($pcbg1, $pcbg2, $pcbg3));	
				$pcad = "";		
			}
			$query = "update template  set title = ?, content = ?, type = ?, phone_bgpic=?, phone_adpic=?, pc_bgpic=?, pc_adpic=?, state='0' where id = ? and name = ?";
			$res = $this->db->query($query, array($title, $content,$type,$phonebg,$phonead, $pcbg, $pcad,$id, $name));
			$this->data['reson'] = "模板修改成功";

		}catch (Exception $ec){
			$this->data['reson'] = $ec->getMessage();
			$this->data['state'] = "failed";
		}
		echo json_encode($this->data);
		exit();
	}	

	public function remtmpimg (){

		try{
			$img = $this->input->post('img');
			$type = $this->input->post('type');
			$tid = $this->input->post('id');
			if(!$img || !$type || !$tid){
				throw new Exception("请求失败, 请求参数不全， 错误码为：T1449");
			}
			$query = "select * from template where id = ?";
			$res = $this->db->query ($query, array($tid));
			if($res->num_rows() == 0){
				throw new Exception("请求失败，该模板不存在或已被删除, 请刷新页面重试");
			}
			$result = $res->row_array();
			$query_array = array();
			$query = "update template set ";
			if($type != "tp"){
				if($img == "phonebg"){
					if(file_exists(FCPATH."/res/images/template/".$result['phone_bgpic'])){
						unlink(FCPATH."/res/images/template/".$result['phone_bgpic']);
					}
					$query .= " phone_bgpic='' ";
					$query_array[] = "";
				}

				if($img == "pcbg"){
					if(file_exists(FCPATH."/res/images/template/".$result['pc_bgpic'])){
						unlink(FCPATH."/res/images/template/".$result['pc_bgpic']);
					}					
					$query .= " pc_bgpic=? ";
					$query_array[] = "";
				}


				if(strpos($img, "phonead") !== false){
					$index = substr($img, -1) - 1;
					$dbimg = json_decode($result['phone_adpic'], true);
					if(file_exists(FCPATH."/res/images/template/".$dbimg[$index])){
						unlink(FCPATH."/res/images/template/".$dbimg[$index]);
					}						
					$dbimg[$index] = "";
					$dbimg = json_encode($dbimg);
					$query .= " phone_adpic=? ";
					$query_array[] = $dbimg;
				}	

				if(strpos($img, "pcad") !== false){
					$index = substr($img, -1) - 1;
					$dbimg = json_decode($result['pc_adpic'], true);
					if(file_exists(FCPATH."/res/images/template/".$dbimg[$index])){
						unlink(FCPATH."/res/images/template/".$dbimg[$index]);
					}	
					$dbimg[$index] = "";
					$dbimg = json_encode($dbimg);
					$query .= " pc_adpic=? ";
					$query_array[] = $dbimg;
				}												
				$query .= " where id=".$tid;
				$this->db->query ($query, $query_array);
			}else{
				if(strpos($img, "phonebg") !== false){
					$index = substr($img, -1) - 1;
					$dbimg = json_decode($result['phone_bgpic'], true);
					if(file_exists(FCPATH."/res/images/template/".$dbimg[$index])){
						unlink(FCPATH."/res/images/template/".$dbimg[$index]);
					}	
					$dbimg[$index] = "";
					$dbimg = json_encode($dbimg);
					$query .= " phone_bgpic=? ";
					$query_array[] = $dbimg;
				}	

				if(strpos($img, "pcbg") !== false){
					$index = substr($img, -1) - 1;
					$dbimg = json_decode($result['pc_bgpic'], true);
					if(file_exists(FCPATH."/res/images/template/".$dbimg[$index])){
						unlink(FCPATH."/res/images/template/".$dbimg[$index]);
					}	
					$dbimg[$index] = "";
					$dbimg = json_encode($dbimg);
					$query .= " pc_bgpic=? ";
					$query_array[] = $dbimg;
				}	

				$query .= " where id = ? ";
				$query_array[] = $tid;

				$this->db->query ($query, $query_array);								
			}		

			$this->data['reson'] = "删除成功";
		}catch(Exception $ec) {
			$this->data['reson'] = $ec->getMessage();
			$this->data['state'] = "failed";
		}
		echo json_encode($this->data);
		exit();
	}

	//上传多个图片
	private  function do_upload_files ($filename){
		if (!isset($_FILES[$filename])){
			throw new Exception(" 请求参数错误， 错误码:4300");
		}
		$array = array();
		if($_FILES[$filename]['size'][0] > 0){
			for ($i=0; $i < count($_FILES[$filename]['size']); $i++) { 
				$endname = substr($_FILES[$filename]['name'][$i], strripos($_FILES[$filename]['name'][$i], "."), strlen($_FILES[$filename]['name'][$i]));;
				$name = 'u'.$_SESSION['cid'].mt_rand(1111,9999).time().$endname;
 				if ( is_uploaded_file ( $_FILES [$filename][ 'tmp_name' ][$i])) {
					move_uploaded_file($_FILES[$filename]['tmp_name'][$i], FCPATH."res/images/template/".$name);
				}
				$array[] = $name;
			}
		}
		return $array;
	}



	private function uploadfile ()
	{
		$cid = $_SESSION['cid'];
		$config['upload_path']      = "./res/images/template";
		$config['allowed_types']    = '*';
		$config['file_ext_tolower'] = true;
		$config['overwrite'] = true;
		$this->load->library('upload', $config);
		if(!isset($_FILES['upload']['name']))
		{
			throw new Exception ('上传文件错误, 错误码:4031');
		}
		$name = strtolower ($_FILES['upload']['name']);
		$query = "select id from correcting_job where jobname = ?";
		$res = $this->db->query($query, array( $name ));
		if ($res->num_rows() > 0)
		{
			throw new Exception ('上传错误， 作业名称不可以重复');
		}
		
		if ( ! $this->upload->do_upload('upload'))
		{
			throw new Exception ($this->upload->display_errors());
		}
		return $config['upload_path'].$this->upload->data('raw_name').$this->upload->data('file_ext');
	}

	public function getsmstmplist (){
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');
			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;
			if(!$sc) {
				$sql = "select * from smstemplate where cid = ? order by createtime desc limit ?, ?";
				$res = $this->db->query($sql , array($_SESSION['cid'], $page, $rows));
			} else {
				$sql = "select * from smstemplate where cid = ? and content like ? order by createtime desc limit ?, ?";				
				$res = $this->db->query($sql , array($_SESSION['cid'], '%'.$sc.'%', $page, $rows));				
			}
			$result = $res->result_array();
			$total = $res->num_rows();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$row['id'] = $row['smsid'];
				$row['time'] = date('Y-m-d H:i:s', $row['createtime']);
				$this->data['rows'][] = $row;
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

	public function addsmstmp (){
		try{
			$content = $this->input->post("smscontent");
			if(!$content){
				throw new Exception("参数不正确");
			}
			$sql = 'select * from smstemplate where cid =? and content = ?';
			$res = $this->db->query($sql, array($_SESSION['cid'], $content));
			if(!$res || $res->num_rows ()>0){
				throw new Exception("短信模版已经存在，请重试");
			}
			$sql = "insert into smstemplate (cid, createtime, content, valid) values (?,?,?,?)";
			$res = $this->db->query($sql, array($_SESSION['cid'], time(), $content, '0'));
			if(!$res || $this->db->affected_rows () == 0){
				throw new Exception("添加短信模版失败，请重试");
			}
			$this->data['reson']  = "短信模版添加成功";	
		}catch(Exception $ec){
			$this->data['status'] = 'failed' ;
			$this->data['reson']  = $ec->getMessage();			
		} 
		echo json_encode($this->data);
		exit();		
	}

	public function smstmpremove (){
		try{
			$id = $this->input->post("id");
			if(!$id){
				throw new Exception("参数不正确");
			}
			$sql = "delete from smstemplate where cid = ? and smsid =?";
			$res = $this->db->query($sql, array($_SESSION['cid'], $id));
			if(!$res ){
				throw new Exception("删除短信模版失败，请重试");
			}
			$this->data['reson']  = "短信模版删除成功";	
		}catch(Exception $ec){
			$this->data['status'] = 'failed' ;
			$this->data['reson']  = $ec->getMessage();			
		} 
		echo json_encode($this->data);
		exit();				
	}

}
?>