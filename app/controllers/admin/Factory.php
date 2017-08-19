<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Factory extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->check_adc_ajax_request ();
	}

	public function corpmanage($param)
	{
		$this->load->model ('admin/corpmanage', "model");
		$this->model->$param();
	}	

	public function smsmanage($param)
	{
		$this->load->model ('admin/smsmanage', "model");
		$this->model->$param();
	}	

	public function sysmanage($param)
	{
		$this->load->model ('admin/sysmanage', "model");
		$this->model->$param();
	}	

	public function tmpmanage($param)
	{
		$this->load->model ('admin/tmpmanage', "model");
		$this->model->$param();
	}		

	public function check_adc_ajax_request (){
		if(!isset($_SESSION['adcid']) || empty($_SESSION['adcid'])){
			die('Not logged in, no associated operation privileges');
		}
		if(!$this->input->is_ajax_request()){
			die("The requested is incorrect and system rejects the request");
		}
	}
}
