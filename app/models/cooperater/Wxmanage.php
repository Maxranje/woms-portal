<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Wxmanage extends CI_Model {
	
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
	public function get_weixin_page(){
		$sql = "select * from cooperater where cid = ?";
		$res = $this->db->query($sql, array($_SESSION['cid']));
		$result = $res->row_array();
		$this->data['url'] = "";		
		if(isset($result['wxtoken']) && !empty($result['wxtoken'])){
			if($_SERVER['SERVER_PORT'] == 443){
				$this->data['url'] = "https://".$_SERVER['SERVER_NAME']."/c/api/thlogin/weixin/?cid=".$_SESSION['cid']."&token=".$result['wxtoken'];
			}else{
				$this->data['url'] = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/c/api/thlogin/weixin/?cid=".$_SESSION['cid']."&token=".$result['wxtoken'];
			}
		}
		$this->data['wx'] = $result ;
		$this->load_view('authweixin', $this->data);
	}	

	public function updatewxconf () {
		try {
			$wxaccount 		= $this->input->post('wxaccount');
			$wxlinktitle 	= $this->input->post('wxlinktitle');
			$wxlinkcontent 	= $this->input->post('wxlinkcontent');
			$wxlinkurl 		= $this->input->post('wxlinkurl');
			if(!$wxaccount) {
				throw new Exception ('请求失败， 必填参数未填写, 请确认');
			}
			$wxrqcode 		= $this->uploadfile ("wxrqcode");
			$wxlinklogo 	= $this->uploadfile ("wxlinklogo");

			$wxtoken = $this->func->getToken(17);
			$this->db->trans_start();
			$query = 'update cooperater set wxaccount=?, wxtoken=?, wxlinktitle=?, wxlinkcontent=?, wxlinkurl=?, wxdocurl=?';
			$array = array($wxaccount, $wxtoken, $wxlinktitle, $wxlinkcontent, $wxlinkurl, $wxdockurl);
			if(!empty($wxrqcode)){
				$query .= ' ,wxrqcode=? ';
				$array[] = $wxrqcode;
			}
			if(!empty($wxlinklogo)){
				$query .= ' ,wxlinklogo=? ';
				$array[] = $wxlinklogo;
			}
			$query .=" where cid = ?";
			$array[] = $_SESSION['cid'];
			$res = $this->db->query($query, $array);
			if(!$res || $this->db->affected_rows () == 0){
				throw new Exception ('无法提交数据, 请重试');
			}
			$this->db->trans_commit();
			$this->data['reson'] = "配置成功";
		}
		catch(Exception $ec) {
			$this->db->trans_rollback();
			if (isset($path) && file_exists($path)) {
				unlink($path);
			}
			$this->data['state'] = 'failed' ;
			$this->data['reson']  = '配置失败, '.$ec->getMessage();
		}
		echo json_encode($this->data);
		exit;
	}

	private function uploadfile ($name) {
		if(!isset($_FILES[$name]['name']) || empty($_FILES[$name]['name'])) {
			return "";
		}
		$config['upload_path']     	 = "./res/images/wx/";
		$config['allowed_types']   	 = 'png|jpg|jpeg';
		$config['file_ext_tolower']	 = true;
		$config['overwrite']		 = true;
		$config['file_name']		 = "u".$_SESSION['cid']."wxrqcode";

		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload($name)){
			throw new Exception ($this->upload->display_errors());
		}
		return $this->upload->data('raw_name').$this->upload->data('file_ext');
	}
}
?>