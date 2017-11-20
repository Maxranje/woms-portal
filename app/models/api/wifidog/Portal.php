<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Portal extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library ('func');
		$this->data = array('adstate'=>'off');
	}

	public function index (){
		$gw_id = $this->input->get('gw_id');

		if($gw_id) {
			$query = "select ac.*, a.* from apconfig ac, ap a where ac.apid = a.apid and a.apname = ? ";
			$res = $this->db->query ($query, array($gw_id));
		}else{
			$query = "select ac.*, a.* from apconfig ac, ap a where ac.apid = a.apid and a.apid = ? ";
			$res = $this->db->query ($query, array($_SESSION['apid']));
		}
		
		if(!$res || $res->num_rows() == 0){
			throw new Exception ('认证参数不正确， 取得页面失败');
		}
		$ap = $res->row_array();	

		if($ap['showstatuspage'] == 's' && isset($_SESSION['url'])) {
			$this->data['url'] = urldecode($_SESSION['url']);
		} else{
			$this->data['url'] = $ap['customurl'];
		}

		if(strpos($ap['customurl'], "http://") == 0 && strpos($ap['customurl'], "https://") == 0){
			$this->data['url'] = 'http://'.$this->data['url'];
		}

		if((isset($_SESSION['wxlogin']) && $_SESSION['wxlogin']=='1') || $ap['showad'] == '1'){
			redirect ($this->data['url']);
		}

		$res = $this->db->query("select * from template where id = ? and cid != 0", array($ap['templateid']));
		if($res && $res->num_rows() > 0){
			$result = $res->row_array();
			$this->data['phonead'] = json_decode($result['phone_adpic'], true);
			$this->data['pcad']    = json_decode($result['pc_adpic'], true);			
		}

		if($this->func->ismobile()){
			$this->load_view('ad_mobile', $this->data);
		}else{
			$this->load_view('ad_pc', $this->data);
		}
	}
}
?>