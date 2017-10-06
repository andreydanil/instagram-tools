<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_follow extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$page_size      = 100;
        $page_num       = (get('p')) ? get('p') : 1;
        $total_row      = $this->model->getList(-1,-1);
        $start_row      = (get('p'))?$page_num:0;

        $config['base_url'] = PATH."instagram/follow/log"."?type=".get('type')."&id=".get("id");
        $config['total_rows'] = $total_row;
        $config['per_page'] = $page_size;
        $config['query_string_segment'] = 'p';
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

		$data= array(
			'accounts' => $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."'"),
			'result'   => $this->model->getList($page_size, $start_row)
		);

		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}

	public function follow(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$schedule = $this->model->fetch("*", SCHEDULE_TB, "uid = '".session("uid")."' and schedule_type = 'follow'", "time_post", "desc");
		$data = array(
			'accounts' => $accounts,
			'schedule' => $schedule
		);
		$this->template->title(TITLE);
		$this->template->build('follow', $data);
	}

	public function unfollow(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$schedule = $this->model->fetch("*", SCHEDULE_TB, "uid = '".session("uid")."' and schedule_type = 'unfollow'", "time_post", "desc");
		$data = array(
			'accounts' => $accounts,
			'schedule' => $schedule
		);
		$this->template->title(TITLE);
		$this->template->build('unfollow', $data);
	}

	public function followback(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$schedule = $this->model->fetch("*", SCHEDULE_TB, "uid = '".session("uid")."' and schedule_type = 'followback'", "time_post", "desc");
		$data = array(
			'accounts' => $accounts,
			'schedule' => $schedule
		);
		$this->template->title(TITLE);
		$this->template->build('followback', $data);
	}

	public function ajax_action_follow(){
		$account = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1 AND username = '".post("username")."'");
		if(!empty($account)){
			INSTAGRAM_FOLLOW(post("action"), $account->username, post("id"));
		}
		print_r(json_encode(array(
			'st' 	=> 'success',
			'txt' 	=> l('successfully')
		)));
	}

	public function ajax_action_item(){
		$id = (int)post('id');
		$POST = $this->model->get('*', INSTAGRAM_FOLLOW_TB, "id = '{$id}' AND uid = '".session("uid")."'");
		if(!empty($POST)){
			switch (post("action")) {
				case 'delete':
					$this->db->delete(INSTAGRAM_FOLLOW_TB, "id = '{$id}' AND uid = '".session("uid")."'");
					break;
				
				case 'active':
					$this->db->update(INSTAGRAM_FOLLOW_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
					break;

				case 'disable':
					$this->db->update(INSTAGRAM_FOLLOW_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
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
				$POST = $this->model->get('*', INSTAGRAM_FOLLOW_TB, "id = '{$id}' AND uid = '".session("uid")."'");
				if(!empty($POST)){
					switch (post("action")) {
						case 'delete':
							$this->db->delete(INSTAGRAM_FOLLOW_TB, "id = '{$id}' AND uid = '".session("uid")."'");
							break;
						
						case 'active':
							$this->db->update(INSTAGRAM_FOLLOW_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
							break;

						case 'disable':
							$this->db->update(INSTAGRAM_FOLLOW_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
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