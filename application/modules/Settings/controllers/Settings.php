<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		if(session("admin") != 1) redirect(PATH);
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

		$id   = (int)get("id");
		$result = $this->model->get("*", SETTINGS_TB, "id = 1");
		$data = array(
			"lang"   => $data_lang,
			"verify" => verify($result->purchase_code),
			"result" => $result
		);

		if(post('title')){
			unset_session("verify");
			
			$data = array(
				"title"             => post('title'),
				"description"       => post('description'),
				"keywords"          => post('keywords'),
				"theme"             => post('theme'),
				"register"          => (int)post('register'),
				"auto_active_user"  => (int)post('auto_active_user'),
				"maximum_account"   => (int)post('maximum_account'),
				"upload_max_size"   => (int)post('upload_max_size'),
				"default_language"  => post('default_language'),
				"default_timezone"  => post('default_timezone'),
				"purchase_code"     => post('purchase_code'),
				"default_deplay"    => post('default_deplay'),
				"minimum_deplay"    => post('minimum_deplay'),
				"facebook_id"       => post('facebook_id'),
				"facebook_secret"   => post('facebook_secret'),
				"google_id"         => post('google_id'),
				"google_secret"     => post('google_secret'),
				"twitter_id"        => post('twitter_id'),
				"twitter_secret"    => post('twitter_secret')
			);

			foreach ($_FILES as $key => $value) {
			    if (!empty($value['tmp_name']) && $value['size'] > 0) {
			    	$this->load->library('upload');
			    	if($key == "language"){
			    		$config['upload_path'] = "language/";
					    $config['allowed_types'] = 'xml';
					    $config['remove_spaces'] = TRUE;
				    	$this->upload->initialize($config); 
				    	if ($this->upload->do_upload($key)) {}
			    	}else{
			    		$path = "assets/img/";
			    		$config['upload_path'] = $path;
					    $config['allowed_types'] = 'jpg|png';
					    $config['remove_spaces'] = TRUE;
				    	$this->upload->initialize($config); 
				    	if ($this->upload->do_upload($key)) {
			            	$data_file = $this->upload->data();
		    				$data["logo"] = $path.$data_file["file_name"];
			        	}
			    	}
			    }
			}

			$this->db->update(USERS_TB, array('timezone' => post('default_timezone')), "id = 1");
			$this->db->update(SETTINGS_TB, $data);

		    redirect(PATH."settings");
		}
		
		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}
}