<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# page redirect
class Adc extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->check_adc_login_state ();
	}

	public function allcooperater (){
		$this->load->model ('admin/corpmanage', "model");
		$this->model->get_all_corp_page();			
	}

	public function corpgrant (){
		$this->load->model ('admin/corpmanage', "model");
		$this->model->get_corp_grant_page();			
	}

	public function addcooperater (){
		$this->load->model ('admin/corpmanage', "model");
		$this->model->get_add_corp_page();			
	}

	public function editcooperater(){
		$this->load->model ('admin/corpmanage', "model");
		$this->model->get_edit_corp_page();
	}

	public function apgrant (){
		$this->load->model ('admin/corpmanage', "model");
		$this->model->get_ap_grant_list();			
	}



	public function smscontentlist (){
		$this->load->model ('admin/smsmanage', "model");
		$this->model->get_sms_content_list();			
	}


	public function smscontentgrant (){
		$this->load->model ('admin/smsmanage', "model");
		$this->model->get_sms_content_grant_list();			
	}

	public function smscontentadd (){
		$this->load->model ('admin/smsmanage', "model");
		$this->model->get_sms_content_add_list();			
	}



	public function systemconfig (){
		$this->load->model ('admin/sysmanage', "model");
		$this->model->get_sys_conf_page();			
	}

	public function systemnotice (){
		$this->load->model ('admin/sysmanage', "model");
		$this->model->get_sys_notice_page();			
	}

	public function addsysnotice (){
		$this->load->model ('admin/sysmanage', "model");
		$this->model->get_add_sys_notice_page();			
	}	


	public function templatelist (){
		$this->load->model ('admin/tmpmanage', "model");
		$this->model->get_tmp_list_page();			
	}

	public function templategrant (){
		$this->load->model ('admin/tmpmanage', "model");
		$this->model->get_tmp_grant_page();			
	}

	public function logout() {
		setcookie("PHPSESSID", "", time()-3600);
		unset($_SESSION);
		session_destroy();
		redirect("/adc/login", "login", 302);
	}

	public function check_adc_login_state () {
		if(count($this->uri->segment_array()) > 2 ) {
			show_404("arguments to manay");
		}
		if(!isset($_SESSION['adcid']) || !isset($_SESSION['adcuser'])){
			if($this->input->is_ajax_request()) {
				die('Not logged in, no associated operation privileges');
			} else {
				redirect("/adc/login", "location", 302);
			}			
		}
	}
}
