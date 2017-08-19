<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Factory extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->check_corp_ajax_request ();
	}

	public function dashboarts($param){
		$this->load->model ('cooperater/dashboarts', "model");
		$this->model->$param();	
	}

	public function apoptions($param)
	{
		$this->load->model ('cooperater/apmanage', "model");
		$this->model->$param();
	}
	
	public function authoptions($param)
	{
		$this->load->model ('cooperater/authmanage', "model");
		$this->model->$param();
	}	
	public function wboptions($param)
	{
		$this->load->model ('cooperater/wbmanage', "model");
		$this->model->$param();
	}
	public function tmpoptions($param)
	{
		$this->load->model ('cooperater/tmpmanage', "model");
		$this->model->$param();	
	}
	public function wxoptions($param)
	{
		$this->load->model ('cooperater/wxmanage', "model");
		$this->model->$param();	
	}
	public function cptoptions($param){
		$this->load->model ('cooperater/cooperater', "model");
		$this->model->$param();
	}
	public function useroptions($param){
		$this->load->model ('cooperater/usermanage', "model");
		$this->model->$param();
	}



	# white list or black list
	public function addblackitem(){
		$this->load->model ('corpjson/wbmanage', "model");
		$this->model->addblackitem();		
	}



	public function check_corp_ajax_request (){
		if(!isset($_SESSION['cid']) || empty($_SESSION['cid'])){
			die('Not logged in, no associated operation privileges');
		}
		if(!$this->input->is_ajax_request()){
			die("The requested is incorrect and system rejects the request");
		}
	}
}
