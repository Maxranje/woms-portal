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

	public function get_tmp_list_page (){
		$this->load_view('adc/template_list');
	}

	public function get_tmp_grant_page (){
		$this->load_view('adc/template_grant');
	}	
	
    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
	public function gettemplatelist () {
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

			$sql = "select t.*, c.nickname, c.name_manager, c.phone from template t, cooperater c where t.cid = c.cid " ;
			if($sc){
				$sql .=" and t.name like ?";
				$array[] = '%'.$sc.'%';
			}

			$sql .= " order by t.createtime limit ?,?";
			$array[] = $page;
			$array[] = $rows;
			$res = $this->db->query($sql , $array);
			$result = $res->result_array();

			$this->data['rows'] = array();
			foreach ($result as $row) {
				$array = array();
				$array['id']			= $row['id'];
				$array['cid']			= $row['cid'];
				$array['name']			= $row['name'];
				$array['createtime']	= date("Y-m-d", $row['createtime']);
				$array['state']			= $row['state'];
				//$array['reson']			= $row['reson'];	
							
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

	public function gettmpgrantlist () {
		try{
			$page = $this->input->post('page'); 
			$rows = $this->input->post('rows');
			$sc = $this->input->post('sc');

			$page = intval($page);
			$rows = intval($rows);
			$page = ($page - 1) * $rows;

			$array = array();

			$sql = "select t.*, c.nickname, c.name_manager, c.phone from template t, cooperater c where t.cid = c.cid and state = '0'" ;
			if($sc){
				$sql .=" and t.name like ?";
				$array[] = '%'.$sc.'%';
			}

			$sql .= " order by t.createtime limit ?,?";
			$array[] = $page;
			$array[] = $rows;
			$res = $this->db->query($sql , $array);
			$result = $res->result_array();

			$this->data['rows'] = array();
			foreach ($result as $row) {
				$array = array();
				$array['id']			= $row['id'];
				$array['cid']			= $row['cid'];
				$array['name']			= $row['name'];
				$array['createtime']	= date("Y-m-d", $row['createtime']);
				$array['state']			= $row['state'];
				//$array['reson']			= $row['reson'];	
							
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

	public function tmpgrant (){
		try{
			$state = $this->input->post('state'); 
			$reson = $this->input->post('reson');
			$tid = $this->input->post('id');
			if(!$state || !$tid){
				throw new Exception("请求失败，错误码为：T1755");
			}

			$sql = "update template set state = ?, reson = ? where id = ?";				
			$res = $this->db->query($sql , array($state, $reson,$tid));
			if($this->db->affected_rows() == 0){
				throw new Exception("更新短信内容模版失败, 错误码：T176".$tid);
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

}
?>