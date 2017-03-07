<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_account extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}
	
	public function index(){
		$page_size      = 25;
        $page_num       = (get('p')) ? get('p') : 1;
        $total_row      = $this->model->getList(-1,-1);
        $start_row      = (get('p'))?$page_num:0;

        $config['base_url'] = PATH."instagram/account"."?";
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

	public function ajax_update(){
		$i = Instagram(post("username"), post("password"));
		$login = $i->login();
		if(!empty($login) && $login->status == "fail"){
			$json= array(
				'st' 	=> 'error',
				'txt' 	=> $login->message
			);
		}else{
			$data = array(
				"username" => $i->username,
				"uid"      => session("uid"),
				"changed"  => NOW 
			);

			$check = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "username = '".$i->username."' and uid = '".session("uid")."'");
			if(empty($check)){
				$data['created'] = NOW;
				if(COUNT_ACCOUNT < MAXIMUM_ACCOUNT){
					$this->db->insert(INSTAGRAM_ACCOUNT_TB, $data);
					$json= array(
						'st' 	=> 'success',
						'txt' 	=> l('add-new-successfully')
					);
				}else{
					$json= array(
						'st' 	=> 'error',
						'txt' 	=> l('desc-limit-account')
					);
				}

				
			}else{
				deleteDir(APPPATH.'libraries/Instagram/data/'.$i->username);
				$i = Instagram(post("username"), post("password"));
				$this->db->update(INSTAGRAM_ACCOUNT_TB, $data, array("id" => $check->id));

				$json= array(
					'st' 	=> 'success',
					'txt' 	=> l('update-successfully')
				);
			}
		}

		print_r(json_encode($json));
	}

	public function ajax_action_item(){
		$id = (int)post('id');
		$POST = $this->model->get('*', INSTAGRAM_ACCOUNT_TB, "id = '{$id}' AND uid = '".session("uid")."'");
		if(!empty($POST)){
			switch (post("action")) {
				case 'delete':
					deleteDir(APPPATH.'libraries/Instagram/data/'.$POST->username);
					$this->db->delete(INSTAGRAM_ACCOUNT_TB, "id = '{$id}' AND uid = '".session("uid")."'");
					break;
				
				case 'active':
					$this->db->update(INSTAGRAM_ACCOUNT_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
					break;

				case 'disable':
					$this->db->update(INSTAGRAM_ACCOUNT_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
					break;
			}
		}

		$json= array(
			'st' 	=> 'success',
			'txt' 	=> l('successfully')
		);

		print_r(json_encode($json));
	}

	public function ajax_action_multiple(){
		$ids =$this->input->post('id');
		if(!empty($ids)){
			foreach ($ids as $id) {
				$POST = $this->model->get('*', INSTAGRAM_ACCOUNT_TB, "id = '{$id}' AND uid = '".session("uid")."'");
				if(!empty($POST)){
					switch (post("action")) {
						case 'delete':
							deleteDir(APPPATH.'libraries/Instagram/data/'.$POST->username);
							$this->db->delete(INSTAGRAM_ACCOUNT_TB, "id = '{$id}' AND uid = '".session("uid")."'");
							break;
						
						case 'active':
							$this->db->update(INSTAGRAM_ACCOUNT_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
							break;

						case 'disable':
							$this->db->update(INSTAGRAM_ACCOUNT_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
							break;
					}
				}
			}
		}

		print_r(json_encode(array(
			'st' 	=> 'success',
			'txt' 	=> l('-successfully')
		)));
	}
}