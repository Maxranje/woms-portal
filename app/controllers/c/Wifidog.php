<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wifidog extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function login(){
		$this->load->model ('api/wifidog/splash_mo', 'model');
		$this->model->index ();
	}

	public function ping (){
		$this->load->model ('api/wifidog/ping', 'model');
		$this->model->index ();
	}

	public function auth (){
		$this->load->model ('api/wifidog/auth', 'model');
		$this->model->index ();
	}

	public function portal(){
		$this->load->model ('api/wifidog/portal', 'model');
		$this->model->index ();
	}

	public function client(){
		$this->load->model ('api/wifidog/client', 'model');
		$this->model->index ();
	}	
}
