<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		if(session("admin") != 1){ redirect(PATH); }

		$page_size      = 25;
        $page_num       = (get('p')) ? get('p') : 1;
        $total_row      = $this->model->getList(-1,-1);
        $start_row      = (get('p'))?$page_num:0;

        $config['base_url'] = PATH."users"."?";
        $config['total_rows'] = $total_row;
        $config['per_page'] = $page_size;
        $config['query_string_segment'] = 'p';
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

		$data= array(
			'result' => $this->model->getList($page_size, $start_row)
		);

		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}

	public function update(){
		if(session("admin") != 1) redirect(PATH);

		$id   = (int)get("id");
		
		$data = array(
			"result" => $this->model->get("*", USERS_TB, "id = '{$id}'")
		);
		$this->template->title(TITLE);
		$this->template->build('update', $data);
	}

	public function profile(){
		$data = array(
			"result" => $this->model->get("*", USERS_TB, "id = '".session("uid")."'")
		);
		$this->template->title(TITLE);
		$this->template->build('profile', $data);
	}

	public function ajax_action_item(){
		if(session("admin") != 1){ redirect(PATH); }

		$id = (int)post('id');
		$POST = $this->model->get('*', USERS_TB, "id = '{$id}'");
		if(!empty($POST)){
			switch (post("action")) {
				case 'delete':
					$this->db->delete(USERS_TB, "id = '{$id}'");
					break;
				
				case 'active':
					$this->db->update(USERS_TB, array("status" => 1), "id = '{$id}'");
					break;

				case 'disable':
					$this->db->update(USERS_TB, array("status" => 0), "id = '{$id}'");
					break;
			}
		}

		$json= array(
			'st' 	=> 'success',
			'txt' 	=> l('successfully')
		);

		print_r(json_encode($json));
	}

	public function postUpdate(){
		if(session("admin") != 1) redirect(PATH);

		$id = (int)post('id');
    	$data = array(
    		'admin'           => (post('admin')!= 0)?1:0,
    		'fullname'        => post('fullname'),
    		'email'           => post('email'),
    		'timezone'        => TIMEZONE, //post('timezone'),
    		'maximum_account' => (int)post('maximum_account'),
    		'status'          => (post('status')!= 0)?1:0,
    		'changed'         => NOW
    	);

    	if(strlen(post('fullname')) < 3){
    		$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('fullname-invalid')
			);
    		print_r(json_encode($json));
    		exit(0);
    	}

    	if(strlen(post('email')) == ""){
    		$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('email-is-required')
			);
    		print_r(json_encode($json));
    		exit(0);
    	}

    	if (filter_var(post('email'), FILTER_VALIDATE_EMAIL) === false) {
		  	$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('email-invalid')
			);
			print_r(json_encode($json));
    		exit(0);
		}

		/*if(post('timezone') == ""){
    		$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('select-timezone')
			);
    		print_r(json_encode($json));
    		exit(0);
    	}*/

    	if($id == 0){
			if(strlen(post('password')) == ''){
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('password-is-required')
				);
	    		print_r(json_encode($json));
	    		exit(0);
	    	}

	    	if(strlen(post('password')) < 5){
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('password-at-least-5-characters')
				);
	    		print_r(json_encode($json));
	    		exit(0);
	    	}

	    	if(post('password') != post('re-password')){
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('password-is-not-matching')
				);
	    		print_r(json_encode($json));
	    		exit(0);
	    	}

    		$data['password'] = md5(post('password'));
    		$data['created'] = NOW;
    		$POST = $this->model->get('*', USERS_TB, "email = '".post("email")."'");
    		if(empty($POST)){
        		$this->db->insert(USERS_TB, $data);
        		$json= array(
					'st' 	   => 'success',
					'redirect' => PATH.'users',
					'txt' 	   => l('add-new-successfully')
				);
        	}else{
        		$json= array(
					'st' 	=> 'error',
					'redirect' => PATH.'users',
					'txt' 	=> l('email-already-exists')
				);
        	}
    	}else{
    		if(post('password') != ""){
    			if(strlen(post('password')) < 5){
	        		$json= array(
						'st' 	=> 'error',
						'txt' 	=> l('password-at-least-5-characters')
					);
	        		print_r(json_encode($json));
	        		exit(0);
	        	}

	        	if(post('password') != post('re-password')){
	        		$json= array(
						'st' 	=> 'error',
						'txt' 	=> l('password-is-not-matching')
					);
	        		print_r(json_encode($json));
	        		exit(0);
	        	}
	        	
	        	$data['password'] = md5(post('password'));
    		}

    		$POST = $this->model->get("*", USERS_TB, "email = '".post("email")."' AND id != '{$id}'");
			if(empty($POST)){
				$this->db->update(USERS_TB, $data, "id = '{$id}'");
    			$json= array(
					'st' 	   => 'success',
					'redirect' => PATH.'users',
					'txt' 	   => l('update-successfully')
				);
			}else{
        		$json= array(
					'st' 	   => 'error',
					'redirect' => PATH.'users',
					'txt' 	   => l('email-already-exists')
				);
        	}
    	}
		print_r(json_encode($json));
	}

	public function postProfile(){
    	$data = array(
    		'fullname'  => post('fullname'),
    		'email'     => post('email'),
    		'timezone'  => TIMEZONE, //post('timezone'),
    		'changed'   => NOW
    	);

    	
		if(strlen(post('fullname')) < 3){
    		$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('fullname-invalid')
			);
    		print_r(json_encode($json));
    		exit(0);
    	}

    	if(strlen(post('email')) == ""){
    		$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('email-is-required')
			);
    		print_r(json_encode($json));
    		exit(0);
    	}

    	if (filter_var(post('email'), FILTER_VALIDATE_EMAIL) === false) {
		  	$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('email-invalid')
			);
			print_r(json_encode($json));
    		exit(0);
		}

    	/*if(post('timezone') == ""){
    		$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('select-timezone')
			);
    		print_r(json_encode($json));
    		exit(0);
    	}*/

		if(post('password') != ""){
			if(strlen(post('password')) == ''){
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('password-is-required')
				);
	    		print_r(json_encode($json));
	    		exit(0);
	    	}

			if(strlen(post('password')) < 5){
        		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('password-at-least-5-characters')
				);
        		print_r(json_encode($json));
        		exit(0);
        	}

        	if(post('password') != post('re-password')){
        		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('password-is-not-matching')
				);
        		print_r(json_encode($json));
        		exit(0);
        	}
        	
        	$data['password'] = md5(post('password'));
		}

		$CHECK = $this->model->get("*", USERS_TB, "email = '".post("email")."' AND id != '".session("uid")."'");
		if(empty($CHECK)){
			$this->db->update(USERS_TB, $data, "id = '".session("uid")."'");
			$USER = $this->model->get("*", USERS_TB, "id = '".session("uid")."'");
			$this->model->session($USER);
			$json= array(
				'st' 	   => 'success',
				'redirect' => PATH,
				'txt' 	   => l('update-successfully')
			);
		}else{
    		$json= array(
				'st' 	   => 'error',
				'redirect' => PATH,
				'txt' 	   => l('email-already-exists')
			);
    	}
		print_r(json_encode($json));
	}

	public function ajax_action_multiple(){
		if(session("admin") != 1){ redirect(PATH); }

		$ids =$this->input->post('id');
		if(!empty($ids)){
			foreach ($ids as $id) {
				$POST = $this->model->get('*', USERS_TB, "id = '{$id}'");
				if(!empty($POST)){
					switch (post("action")) {
						case 'delete':
							$this->db->delete(USERS_TB, "id = '{$id}'");
							break;
						
						case 'active':
							$this->db->update(USERS_TB, array("status" => 1), "id = '{$id}'");
							break;

						case 'disable':
							$this->db->update(USERS_TB, array("status" => 0), "id = '{$id}'");
							break;
					}
				}
			}
		}

		print_r(json_encode(array(
			'st' 	=> 'success',
			'txt' 	=> l('successfully')
		)));
	}

	public function openid($type = ""){
		$result = array();
		switch ($type) {
			case 'facebook':
				if(get('code') && get('state')){
					$USER = FACEBOOK_GET_USER();
					if(!empty($USER)){
						$data = array(
							"type"            => "facebook",
							"pid"             => $USER['id'],
							"fullname"        => $USER['name'],
							"maximum_account" => MAXIMUM_ACCOUNT,
							"status"          => AUTO_ACTIVE_USER,
							"changed"         => NOW
						);

						if(isset($USER['email'])){
							$data['email'] = $USER['email'];
						}

						if(isset($data['email'])){
							$result = $this->model->get('*', USERS_TB, "email = '".$data['email']."'");
						}else{
							$result = $this->model->get('*', USERS_TB, "pid = '".$USER['id']."'");
						}
						if(empty($result)){
							if(REGISTER_ALLOWED == 1){
								$data["created"] = NOW;
								$this->db->insert(USERS_TB, $data);
								$id = $this->db->insert_id();
								$result = $this->model->get('*', USERS_TB, "id = '".$id."'");
							}
						}else{
							$this->db->update(USERS_TB, $data, array('id' => $result->id));
						}
					}
				}
				break;
			case 'google':
				if(get('code')){
					$USER = GOOGLE_GET_USER(get('code'));
					if(!empty($USER)){
						$data = array(
							"type"            => "google",
							"pid"             => $USER->id,
							"fullname"        => $USER->name,
							"email"           => $USER->email,
							"maximum_account" => MAXIMUM_ACCOUNT,
							"status"          => AUTO_ACTIVE_USER,
							"changed"         => NOW
						);

						$result = $this->model->get('*', USERS_TB, "email = '".$USER->email."'");
						if(empty($result)){
							if(REGISTER_ALLOWED == 1){
								$data["created"] = NOW;
								$this->db->insert(USERS_TB, $data);
								$id = $this->db->insert_id();
								$result = $this->model->get('*', USERS_TB, "id = '".$id."'");
							}
						}else{
							$this->db->update(USERS_TB, $data, array('id' => $result->id));
						}
					}
				}
				break;
			case 'twitter':
				if(get('oauth_token') && get('oauth_verifier')){
					$USER = TWITTER_GET_USER();
					if(!empty($USER)){
						$data = array(
							"type"            => "twitter",
							"pid"             => $USER->id_str,
							"fullname"        => $USER->name,
							"maximum_account" => MAXIMUM_ACCOUNT,
							"status"          => AUTO_ACTIVE_USER,
							"changed"         => NOW
						);

						if(isset($USER->email)){
							$data['email'] = $USER->email;
						}

						if(isset($data['email'])){
							$result = $this->model->get('*', USERS_TB, "email = '".$data['email']."'");
						}else{
							$result = $this->model->get('*', USERS_TB, "pid = '".$USER->id_str."'");
						}
						if(empty($result)){
							if(REGISTER_ALLOWED == 1){
								$data["created"] = NOW;
								$this->db->insert(USERS_TB, $data);
								$id = $this->db->insert_id();
								$result = $this->model->get('*', USERS_TB, "id = '".$id."'");
							}
						}else{
							$this->db->update(USERS_TB, $data, array('id' => $result->id));
						}
					}
				}
				break;
		}

		if(!empty($result) && $result->status == 1){
			$this->model->session($result);
			redirect(PATH."#login-success");
		}else{
			redirect(PATH."#not-active");
		}
	}

	public function ajax_register(){
		if(REGISTER_ALLOWED == 1){
	    	$data = array(
	    		'type'            => 'direct',
	    		'fullname'        => post('fullname'),
	    		'email'           => post('email'),
	    		'password'        => md5(post('password')),
	    		'timezone'        => TIMEZONE,
	    		'maximum_account' => MAXIMUM_ACCOUNT,
	    		"status"          => AUTO_ACTIVE_USER,
	    		'changed'         => NOW,
	    		'created'         => NOW
	    	);

	    	if(strlen(post('fullname')) < 3){
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('fullname-invalid')
				);
	    		print_r(json_encode($json));
	    		exit(0);
	    	}

	    	if(strlen(post('email')) == ""){
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('email-is-required')
				);
	    		print_r(json_encode($json));
	    		exit(0);
	    	}

	    	if (filter_var(post('email'), FILTER_VALIDATE_EMAIL) === false) {
			  	$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('email-invalid')
				);
				print_r(json_encode($json));
	    		exit(0);
			}

			if(strlen(post('password')) == ''){
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('password-is-required')
				);
	    		print_r(json_encode($json));
	    		exit(0);
	    	}

	    	if(strlen(post('password')) < 5){
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('password-at-least-5-characters')
				);
	    		print_r(json_encode($json));
	    		exit(0);
	    	}

	    	if(post('password') != post('repassword')){
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('password-is-not-matching')
				);
	    		print_r(json_encode($json));
	    		exit(0);
	    	}

			$result = $this->model->get('*', USERS_TB, "email = '".post('email')."'");
			if(empty($result)){
	    		$this->db->insert(USERS_TB, $data);
	    		$id = $this->db->insert_id();
	    		$result = $this->model->get('*', USERS_TB, "id = '".$id."'");

	    		if($result->status == 1){
	    			$this->model->session($result);
	    			$json= array(
						'st' 	=> 'success',
						'txt' 	=> l('register-successfully')
					);
	    		}else{
	    			$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('your-account-is-not-activated')
				);
	    		}
	    		
	    	}else{
	    		$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('email-already-exists')
				);
	    	}

			print_r(json_encode($json));
		}
	}

	public function ajax_login(){
		if(strlen(post('email')) == ""){
    		$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('email-is-required')
			);
    		print_r(json_encode($json));
    		exit(0);
    	}

    	if (filter_var(post('email'), FILTER_VALIDATE_EMAIL) === false) {
		  	$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('email-invalid')
			);
			print_r(json_encode($json));
    		exit(0);
		}

		if(strlen(post('password')) == ''){
    		$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('password-is-required')
			);
    		print_r(json_encode($json));
    		exit(0);
    	}

    	$result = $this->model->get('*', USERS_TB, "email = '".post('email')."' AND password = '".md5(post('password'))."'");
		if(!empty($result)){
			if($result->status != 1){
				$json= array(
					'st' 	=> 'error',
					'txt' 	=> l('your-account-is-not-activated')
				);
				print_r(json_encode($json));
				exit();
			}

    		$this->model->session($result);
    		$json= array(
				'st' 	=> 'success',
				'txt' 	=> l('login-successfully')
			);
    	}else{
    		$json= array(
				'st' 	=> 'error',
				'txt' 	=> l('email-or-password-incorrect')
			);
    	}

    	print_r(json_encode($json));
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect(PATH);
	}

}