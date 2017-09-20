<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index (){
		$this->load->model ('api/client/Clear', 'model');
		$this->model->index ();
	}	
}
