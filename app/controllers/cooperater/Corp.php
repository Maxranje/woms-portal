<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Corp extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->check_corp_login_state ();
	}

	public function login (){
		$this->load->model ('cooperater/cooperater', "model");
		$this->model->login();
	}

	public function setting (){
		$this->load->model ('cooperater/cooperater', "model");
		$this->model->setting();			
	}

	public function dashboarts (){
		$this->load->model ('cooperater/dashboarts', "model");
		$this->model->index();
	}

	public function aplist (){
		$this->load->model ('cooperater/apmanage', "model");
		$this->model->get_ap_list_page();
	}

	public function addap (){
		$this->load->model ('cooperater/apmanage', "model");
		$this->model->get_add_ap_page();
	}

	public function apinfoedit (){
		$this->load->model ('cooperater/apmanage', "model");
		$this->model->get_ap_info_edit_page();
	}

	public function apauthconf (){
		$this->load->model ('cooperater/apmanage', "model");
		$this->model->get_ap_config_page();		
	}

	public function apauthtype (){
		$this->load->model ('cooperater/apmanage', "model");
		$this->model->get_ap_authtype_page();		
	}

	public function apauthtmp(){
		$this->load->model ('cooperater/apmanage', "model");
		$this->model->get_ap_tmp_page();		
	}	

	public function apauthassist(){
		$this->load->model ('cooperater/apmanage', "model");
		$this->model->get_ap_assist_page();		
	}

	public function manageval (){
		$this->load->model ('cooperater/authmanage', "model");
		$this->model->get_manageval_page();
	}

	public function addvalidate (){
		$this->load->model ('cooperater/authmanage', "model");
		$this->model->get_add_validate_page();
	}

	public function editvalidate (){
		$this->load->model ('cooperater/authmanage', "model");
		$this->model->get_edit_validate_page();	
	}

	public function authblacklist (){
		$this->load->model ('cooperater/wbmanage', "model");
		$this->model->get_black_list_page();			
	}

	public function addblackitem (){
		$this->load->model ('cooperater/wbmanage', "model");
		$this->model->get_black_item_page();			
	}	

	public function editblackitem (){
		$this->load->model ('cooperater/wbmanage', "model");
		$this->model->get_edit_black_item_page();			
	}	

	public function authwhitelist (){
		$this->load->model ('cooperater/wbmanage', "model");
		$this->model->get_white_list_page();		
	}

	public function addwhiteitem (){
		$this->load->model ('cooperater/wbmanage', "model");
		$this->model->get_white_item_page();	
	}

	public function editwhiteitem (){
		$this->load->model ('cooperater/wbmanage', "model");
		$this->model->get_edit_white_item_page();			
	}	

	public function authtemplate (){
		$this->load->model ('cooperater/tmpmanage', "model");
		$this->model->get_template_page();			
	}

	public function addsptemplate (){
		$this->load->model ('cooperater/tmpmanage', "model");
		$this->model->get_add_sp_template_page();			
	}

	public function addwptemplate (){
		$this->load->model ('cooperater/tmpmanage', "model");
		$this->model->get_add_wp_template_page();			
	}

	public function addtptemplate (){
		$this->load->model ('cooperater/tmpmanage', "model");
		$this->model->get_add_tp_template_page();			
	}		

	public function edittemplate (){
		$this->load->model ('cooperater/tmpmanage', "model");
		$this->model->get_edit_template_page();			
	}

	public function authsmstmp (){
		$this->load->model ('cooperater/tmpmanage', "model");
		$this->model->get_sms_tmp_page();			
	}

	public function addauthsmstmp (){
		$this->load->model ('cooperater/tmpmanage', "model");
		$this->model->get_add_sms_tmp_page();			
	}

	public function authweixin (){
		$this->load->model ('cooperater/wxmanage', "model");
		$this->model->get_weixin_page();			
	}

	public function updatewxconf (){
		$this->load->model ('cooperater/wxmanage', "model");
		$this->model->updatewxconf();			
	}

	public function authstatistics(){
		$this->load->model ('cooperater/usermanage', "model");
		$this->model->get_stattistics_page();			
	}


	public function logout() {
		setcookie("PHPSESSID", "", time()-3600);
		unset($_SESSION);
		session_destroy();
		redirect("/corp/login", "login", 302);
	}

	public function check_corp_login_state () {
		if(count($this->uri->segment_array()) > 2 ) {
			show_404();
		}
		if($this->uri->segment(2) === "login") {
			return ;
		}
		if(!isset($_SESSION['cid']) || empty($_SESSION['cid'])){
			if($this->input->is_ajax_request()) {
				die('Not logged in, no associated operation privileges');
			} else {
				redirect("/corp/login", "location", 302);
			}			
		}
	}
}
