<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Home extends MX_Controller {

	public function index(){
		if(session("uid")) redirect(PATH."dashboard");
		$data = array();
		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}

	public function verify(){
		$CI = &get_instance();
		$settings = $CI->db->select("*")->get(SETTINGS_TB)->row();
		if(!empty($settings)){
			$verification = VF_CODE($settings->purchase_code);
		    if (!empty($verification) && $verification->status == "success") {
		        redirect(PATH);
		        exit(0);
		    }
		}
		$data = array();
		$this->template->title('Verify', TITLE);
		$this->template->build('verify', $data);
	}

	public function dashboard(){
		$data = array();
		$this->template->title('Dashboard', TITLE);
		$this->template->build('dashboard', $data);
	}

	public function header(){
		$list_lang = scandir(APPPATH."../language/");
		unset($list_lang[0]);
		unset($list_lang[1]);
		$data_lang = array();
		foreach ($list_lang as $lang) {
			$arr_lang = explode(".", $lang);
			if(count($arr_lang) == 2 && strlen($arr_lang[0]) == 2 && $arr_lang[1] == "xml"){
				$data_lang[] = $arr_lang[0];
			}
		}

		$data = array(
			"lang" => $data_lang
		);
		$this->load->view("header", $data);
	}

	public function footer(){
		$data = array();
		$this->load->view("footer", $data);
	}

	public function language(){
		$list_lang = scandir(APPPATH."../language/");
		unset($list_lang[0]);
		unset($list_lang[1]);
		$data_lang = array();
		$data_lang = array();
		foreach ($list_lang as $lang) {
			$arr_lang = explode(".", $lang);
			if(count($arr_lang) == 2 && strlen($arr_lang[0]) == 2 && $arr_lang[1] == "xml"){
				if($arr_lang[0] == get('lang')){
					set_session("lang", get('lang'));
					redirect(PATH);		
				}
			}
		}
	}

	public function ajax_verify(){
		$this->db->update(SETTINGS_TB, array("purchase_code" => post("purchase_code")));

    	$json= array(
			'st' 	=> 'success'
		);

    	print_r(json_encode($json));
	}
}