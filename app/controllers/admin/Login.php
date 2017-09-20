<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index (){
		try {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$secret = $this->input->post('secret');
			if(!$username || !$password || !$secret){
				$data['secret'] = $this->getSecretCode ();
				$_SESSION['secret'] = $data['secret'];
				$this->load_view('adc/login', $data);
			}

			if ($secret != $_SESSION['secret']){
				throw new Exception ("校验参数不正确");
			}
			$password = substr(md5($password), 0, 16);
			$sql = "select adcid from adc where binary adcuser = ? and adcpsw = ?";
			$res = $this->db->query ($sql, array($username, $password));
			if(!$res || $res->num_rows() == 0) {
				throw new Exception ('用户名密码错误');
			}
			$row = $res->row_array();
			$_SESSION['adcid'] = $row['adcid'];
			$_SESSION['adcuser'] = $username;

			redirect("/adc/allcooperater", 'location', 302);
		}
		catch(Exception $tt) {
			$this->data['state'] = "failed";
			$this->data['reson'] = $tt->getMessage(); 
			$this->data['secret'] = $this->getSecretCode();
			$_SESSION['secret'] = $this->data['secret'];
			$this->load_view('adc/login', $this->data);
		}
	}
	private function getSecretCode () {
		return  strtoupper( md5(mt_rand(11111, 99999)) );
	}
}
