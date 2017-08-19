<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Thlogin extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function weixin(){
		$this->load->model('api/thlogin/weixin', 'model');
		$this->model->index();
	}
}
