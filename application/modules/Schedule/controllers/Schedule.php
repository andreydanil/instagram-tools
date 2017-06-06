<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$schedule = $this->model->fetch("*", SCHEDULE_TB, "uid = '".session("uid")."' and schedule_type = 'post'", "time_post", "desc");
		$savepost = $this->model->fetch("*", INSTAGRAM_SAVE_TB, "uid = '".session("uid")."'", "created", "desc");
		$data = array(
			'accounts' => $accounts,
			'schedule' => $schedule,
			'savepost' => $savepost
		);
		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}

	public function ajax_action_item(){
		$id = (int)post('id');
		$POST = $this->model->get('*', SCHEDULE_TB, "id = '{$id}' AND uid = '".session("uid")."'");
		if(!empty($POST)){
			switch (post("action")) {
				case 'delete':
					$this->db->delete(SCHEDULE_TB, "id = '{$id}' AND uid = '".session("uid")."'");
					break;
				
				case 'repost':
					$this->db->update(SCHEDULE_TB, array("status" => 4), "id = '{$id}' AND uid = '".session("uid")."'");
					break;

				case 'cancel':
					$this->db->update(SCHEDULE_TB, array("status" => 5), "id = '{$id}' AND uid = '".session("uid")."'");
					break;

				default:
					$result = INSTAGRAM_MEDIA_INFO(post("action"));
					pr($result,1);
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
				$POST = $this->model->get('*', SCHEDULE_TB, "id = '{$id}' AND uid = '".session("uid")."'");
				if(!empty($POST)){
					switch (post("action")) {
						case 'delete':
							$this->db->delete(SCHEDULE_TB, "id = '{$id}' AND uid = '".session("uid")."'");
							break;
						
						case 'cancel':
							$this->db->update(SCHEDULE_TB, array("status" => 5), "id = '{$id}' AND uid = '".session("uid")."'");
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

	public function ajax_schedule_check(){
		$json = array();
		switch (post('type')) {
			case 'video':
				if(post('media') == ""){
					$json[] = array(
						"type"   => "media",
						"text"   => l('video-is-required')
					);
				}
				break;
			default:
				if(!post('media')){
					$json[] = array(
						"type"   => "media",
						"text"   => l('image-is-required')
					);
				}
				break;
		}

		$accounts = post('accounts[]');
		$account  = explode("{-}", post('account'));
		if(empty($accounts) && count($account) != 2){
			$json[] = array(
				"type"   => "account",
				"text"   => l('please-select-an-account')
			);
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			$json = array(
				'st' 	=> 'success',
				'txt' 	=> l('successfully')
			);
		}

		print_r(json_encode($json));
	}

	public function ajax_post_now(){
		$json = array();
		switch (post('type')) {
			case 'video':
				if(post('media') == ""){
					$json[] = array(
						"type"   => "media",
						"text"   => l('video-is-required')
					);
				}

				$data = array(
					"type"        => "video",
					"description" => post("description"),
					"url"         => post("media")
				);
				break;
			default:
				if(!post('media')){
					$json[] = array(
						"type"   => "media",
						"text"   => l('image-is-required')
					);
				}

				$data = array(
					"type"        => "photo",
					"description" => post("description"),
					"image"       => post("media")
				);
				break;
		}

		$accounts = post('accounts[]');
		$account  = explode("{-}", post('account'));
		if(empty($accounts) && count($account) != 2){
			$json[] = array(
				"type"   => "account",
				"text"   => l('please-select-an-account')
			);
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			$data['schedule_type'] = 'post';
			$data['social']        = 'instagram';
			$data['time_post']     = NOW;
			$data['account']       = $account[0];
			$data['name']          = $account[1];
			$data['uid']           = session("uid");
			$data['status']        = 3;
			$data['changed']       = NOW;
			$data['created']       = NOW;

 			$this->db->insert(SCHEDULE_TB, $data);
 			$id = $this->db->insert_id();
 			$post = $this->model->get("*", SCHEDULE_TB, "id = '".$id."'");

 			$spintax = new Spintax();
 			$post->url         = $spintax->process($post->url);
			$post->message     = $spintax->process($post->message);
			$post->title       = $spintax->process($post->title);
			$post->description = $spintax->process($post->description);
			$post->image       = $spintax->process($post->image);
			$post->caption     = $spintax->process($post->caption);

			$INSTAGRAM = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "id = '".$post->account."'");
			if(!empty($INSTAGRAM)){
				$post->username = $INSTAGRAM->username;
				$post->password = $INSTAGRAM->password;
				$response = INSTAGRAM_POST($post);
				if(!empty($response)){
					$response = (array)$response;
					$this->db->update(SCHEDULE_TB,array("status" => isset($response["message"])?3:2, 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$post->id}");
				}
			}

			$json = array(
				'st' 	=> 'success',
				'txt' 	=> l('successfully')
			);
		}

		print_r(json_encode($json));
	}

	public function ajax_schedule(){
		ini_set('max_execution_time', 3000);
		ini_set('max_input_vars', 10000);

		$json = array();
		$accounts = $this->input->post('accounts');

		switch (post('type')) {
			case 'video':
				if(post('media') == ""){
					$json[] = array(
						"type"   => "media",
						"text"   => l('video-is-required')
					);
				}

				$data = array(
					"type"        => "video",
					"description" => post("description"),
					"url"         => post("media")
				);
				break;
			default:
				if(!post('media')){
					$json[] = array(
						"type"   => "media",
						"text"   => l('image-is-required')
					);
				}

				$data = array(
					"type"        => "photo",
					"description" => post("description"),
					"image"       => post("media")
				);
				break;
		}

		if(post('time_post') == ""){
			$json[] = array(
				"type"   => "time_post",
				"text"   => l('time-post-required')
			);
		}

		if(post('delete_complete')){
			$data["delete"] = 1;
		}else{
			$data["delete"] = 0;
		}

		if(post('repeat_post')){
			$data["repeat_post"] = 1;
			$data["repeat_time"] = (int)post("repeat_time");
			$data["repeat_end"]  = date("Y-m-d", strtotime(post('repeat_end')));
		}else{
			$data["repeat_post"] = 0;
		}

		if(empty($accounts)){
			$json[] = array(
				"type"   => "list_accounts",
				"text"   => l('please-select-an-account')
			);
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			$count = 0;
			$deplay = (int)post('deplay');
			$list_deplay = array();
			for ($i=0; $i < count($accounts); $i++) { 
				$list_deplay[] = $deplay*$i;
			}

			if(post('auto_pause')){
				$pause = 0;
				$count_deplay = 0;
				for ($i=0; $i < count($list_deplay); $i++) { 
					$item_deplay = 1;
					if(post('pause_post') == $count_deplay){
						$pause += post('pause_time')*60;
						$count_deplay = 0;
					}

					$list_deplay[$i] += $pause;
					$count_deplay++;
				}
			}

			if(post('random_post')){
				shuffle($list_deplay);
			}

			$time_post = strtotime(post('time_post').":00");
			$time_now  = strtotime(NOW) + 60;
			if($time_post < $time_now){
				$time_post = $time_now;
			}

			foreach ($accounts as $key => $row) {
				
				$value  = explode("{-}", $row);
				if(count($value) == 2){
					$data["social"]        = 'instagram';
					$data["schedule_type"] = 'post';
					$data["account"]       = $value[0];
					$data["name"]          = $value[1];
					$data["uid"]           = session("uid");
					$data["deplay"]        = $deplay;
					$data["changed"]       = NOW;
					$data["created"]       = NOW;
					$data["time_post"]     = date("Y-m-d H:i:s", $time_post + $list_deplay[$key]);

					$this->db->insert(SCHEDULE_TB, $data);
					$count++;
				}
			}

			if($count != 0){
				$json[] = array(
					"text"   => l('add-successfully')
				);
				$json["st"] = "success";
			}else{
				$json[] = array(
					"text"   => l('the-error-occurred-during-processing')
				);
				$json["st"] = "error";
			}
		}

		printf(json_encode($json));
	}

	public function ajax_schedule_direct_message(){
		ini_set('max_execution_time', 3000);
		ini_set('max_input_vars', 10000);

		$json = array();
		$media_id = $this->input->post('media_id');

		if(post('description') == ""){
			$json[] = array(
				"type"   => "description",
				"text"   => l('description-is-required')
			);
		}

		$data = array(
			"type"        => "message",
			"description" => post("description"),
		);

		if(post('time_post') == ""){
			$json[] = array(
				"type"   => "time_post",
				"text"   => l('time-post-required')
			);
		}


		if(empty($media_id)){
			$json[] = array(
				"type"   => "list_media",
				"text"   => l('select-at-least-a-user')
			);
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			$count = 0;
			$deplay = (int)post('deplay');
			$list_deplay = array();
			for ($i=0; $i < count($media_id); $i++) { 
				$list_deplay[] = $deplay*$i;
			}

			$time_post = strtotime(post('time_post').":00");
			$time_now  = strtotime(NOW) + 60;
			if($time_post < $time_now){
				$time_post = $time_now;
			}

			foreach ($media_id as $key => $row) {
				$value = post("account");
				$value = explode("{-}", $value);
				$media = explode("{-}", $row);
				if(count($value) == 2){
					$data["social"]        = 'instagram';
					$data["schedule_type"] = 'message';
					$data["account"]       = $value[0];
					$data["name"]          = $value[1];
					$data["media_id"]      = $media[0];
					$data["title"]         = $media[1];
					$data["uid"]           = session("uid");
					$data["deplay"]        = $deplay;
					$data["changed"]       = NOW;
					$data["created"]       = NOW;
					$data["time_post"]     = date("Y-m-d H:i:s", $time_post + $list_deplay[$key]);

					$this->db->insert(SCHEDULE_TB, $data);
					$count++;
				}
			}

			if($count != 0){
				$json[] = array(
					"text"   => l('add-successfully')
				);
				$json["st"] = "success";
			}else{
				$json[] = array(
					"text"   => l('the-error-occurred-during-processing')
				);
				$json["st"] = "error";
			}
		}

		printf(json_encode($json));
	}

	public function ajax_schedule_comment(){
		ini_set('max_execution_time', 3000);
		ini_set('max_input_vars', 10000);

		$json = array();
		$media_id = $this->input->post('media_id');

		if(post('description') == ""){
			$json[] = array(
				"type"   => "description",
				"text"   => l('description-is-required')
			);
		}

		$data = array(
			"type"        => "comment",
			"description" => post("description"),
		);

		if(post('time_post') == ""){
			$json[] = array(
				"type"   => "time_post",
				"text"   => l('time-post-required')
			);
		}


		if(empty($media_id)){
			$json[] = array(
				"type"   => "list_media",
				"text"   => l('select-at-least-a-media')
			);
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			$count = 0;
			$deplay = (int)post('deplay');
			$list_deplay = array();
			for ($i=0; $i < count($media_id); $i++) { 
				$list_deplay[] = $deplay*$i;
			}

			$time_post = strtotime(post('time_post').":00");
			$time_now  = strtotime(NOW) + 60;
			if($time_post < $time_now){
				$time_post = $time_now;
			}

			foreach ($media_id as $key => $row) {
				$value = post("account");
				$value = explode("{-}", $value);
				$media = explode("{-}", $row);
				if(count($value) == 2){
					$data["social"]        = 'instagram';
					$data["schedule_type"] = 'comment';
					$data["account"]       = $value[0];
					$data["name"]          = $value[1];
					$data["media_id"]      = $media[0];
					$data["code"]          = $media[1];
					$data["uid"]           = session("uid");
					$data["deplay"]        = $deplay;
					$data["changed"]       = NOW;
					$data["created"]       = NOW;
					$data["time_post"]     = date("Y-m-d H:i:s", $time_post + $list_deplay[$key]);

					$this->db->insert(SCHEDULE_TB, $data);
					$count++;
				}
			}

			if($count != 0){
				$json[] = array(
					"text"   => l('add-successfully')
				);
				$json["st"] = "success";
			}else{
				$json[] = array(
					"text"   => l('the-error-occurred-during-processing')
				);
				$json["st"] = "error";
			}
		}

		printf(json_encode($json));
	}

	public function ajax_schedule_like(){
		ini_set('max_execution_time', 3000);
		ini_set('max_input_vars', 10000);

		$json = array();
		$media_id = $this->input->post('media_id');

		if(post('time_post') == ""){
			$json[] = array(
				"type"   => "time_post",
				"text"   => l('time-post-required')
			);
		}


		if(empty($media_id)){
			$json[] = array(
				"type"   => "list_media",
				"text"   => l('select-at-least-a-media')
			);
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			$count = 0;
			$deplay = (int)post('deplay');
			$list_deplay = array();
			for ($i=0; $i < count($media_id); $i++) { 
				$list_deplay[] = $deplay*$i;
			}

			$time_post = strtotime(post('time_post').":00");
			$time_now  = strtotime(NOW) + 60;
			if($time_post < $time_now){
				$time_post = $time_now;
			}

			foreach ($media_id as $key => $row) {
				$value = post("account");
				$value = explode("{-}", $value);
				$media = explode("{-}", $row);
				if(count($value) == 2){
					$data["social"]        = 'instagram';
					$data["schedule_type"] = 'like';
					$data["account"]       = $value[0];
					$data["name"]          = $value[1];
					$data["media_id"]      = $media[0];
					$data["code"]          = $media[1];
					$data["uid"]           = session("uid");
					$data["deplay"]        = $deplay;
					$data["changed"]       = NOW;
					$data["created"]       = NOW;
					$data["time_post"]     = date("Y-m-d H:i:s", $time_post + $list_deplay[$key]);

					$this->db->insert(SCHEDULE_TB, $data);
					$count++;
				}
			}

			if($count != 0){
				$json[] = array(
					"text"   => l('add-successfully')
				);
				$json["st"] = "success";
			}else{
				$json[] = array(
					"text"   => l('the-error-occurred-during-processing')
				);
				$json["st"] = "error";
			}
		}

		printf(json_encode($json));
	}

	public function ajax_schedule_follow(){
		ini_set('max_execution_time', 3000);
		ini_set('max_input_vars', 10000);

		$json = array();
		$accounts = $this->input->post('accounts');

		if(!post("tags")){
			$json[] = array(
				"type"   => "list_accounts",
				"text"   => l('please-add-hashtags')
			);
		}

		if(empty($accounts)){
			$json[] = array(
				"type"   => "list_accounts",
				"text"   => l('please-select-an-account')
			);
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			$count = 0;
			$deplay = (int)post('deplay');
			$list_deplay = array();
			for ($i=0; $i < count($accounts); $i++) { 
				$list_deplay[] = $deplay*$i;
			}

			$time_post  = strtotime(NOW) + 60;

			foreach ($accounts as $key => $row) {
				$value = explode("{-}", $row);
				if(count($value) == 2){
					$data["social"]        = 'instagram';
					$data["schedule_type"] = 'follow';
					$data["account"]       = $value[0];
					$data["name"]          = $value[1];
					$data["uid"]           = session("uid");
					$data["deplay"]        = (int)post("deplay");
					$data["maximum"]       = (int)post("maximum");
					$data["description"]   = post("tags");
					$data["repeat_post"]   = 1;
					$data["repeat_time"]   = $deplay;
					$data["repeat_end"]    = "2030-09-09 00:00:00";
					$data["changed"]       = NOW;
					$data["created"]       = NOW;
					$data["time_post"]     = date("Y-m-d H:i:s", $time_post + $list_deplay[$key]);

					$check = $this->model->get("*", SCHEDULE_TB, "schedule_type = 'follow' AND uid = '".session("uid")."' AND account = '".$value[0]."'");
					if(empty($check)){
						$this->db->insert(SCHEDULE_TB, $data);
					}else{
						$this->db->update(SCHEDULE_TB, $data, "id = '".$check->id."'");
					}
					$count++;
				}
			}

			if($count != 0){
				$json[] = array(
					"text"   => l('add-successfully')
				);
				$json["st"] = "success";
			}else{
				$json[] = array(
					"text"   => l('the-error-occurred-during-processing')
				);
				$json["st"] = "error";
			}
		}

		printf(json_encode($json));
	}

	public function ajax_schedule_followback(){
		ini_set('max_execution_time', 3000);
		ini_set('max_input_vars', 10000);

		$json = array();
		$accounts = $this->input->post('accounts');

		if(empty($accounts)){
			$json[] = array(
				"type"   => "list_accounts",
				"text"   => l('please-select-an-account')
			);
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			$count = 0;
			$deplay = (int)post('deplay');
			$list_deplay = array();
			for ($i=0; $i < count($accounts); $i++) { 
				$list_deplay[] = $deplay*$i;
			}

			$time_post  = strtotime(NOW) + 60;

			foreach ($accounts as $key => $row) {
				$value = explode("{-}", $row);
				if(count($value) == 2){
					$data["social"]        = 'instagram';
					$data["schedule_type"] = 'followback';
					$data["account"]       = $value[0];
					$data["name"]          = $value[1];
					$data["uid"]           = session("uid");
					$data["deplay"]        = (int)post("deplay");
					$data["maximum"]       = (int)post("maximum");
					$data["description"]   = post("description");
					$data["repeat_post"]   = 1;
					$data["repeat_time"]   = $deplay;
					$data["repeat_end"]    = "2030-09-09 00:00:00";
					$data["changed"]       = NOW;
					$data["created"]       = NOW;
					$data["time_post"]     = date("Y-m-d H:i:s", $time_post + $list_deplay[$key]);

					$check = $this->model->get("*", SCHEDULE_TB, "schedule_type = 'followback' AND uid = '".session("uid")."' AND account = '".$value[0]."'");
					if(empty($check)){
						$this->db->insert(SCHEDULE_TB, $data);
					}else{
						$this->db->update(SCHEDULE_TB, $data, "id = '".$check->id."'");
					}
					$count++;
				}
			}

			if($count != 0){
				$json[] = array(
					"text"   => l('add-successfully')
				);
				$json["st"] = "success";
			}else{
				$json[] = array(
					"text"   => l('the-error-occurred-during-processing')
				);
				$json["st"] = "error";
			}
		}

		printf(json_encode($json));
	}

	public function ajax_schedule_unfollow(){
		ini_set('max_execution_time', 3000);
		ini_set('max_input_vars', 10000);

		$json = array();
		$accounts = $this->input->post('accounts');

		if(empty($accounts)){
			$json[] = array(
				"type"   => "list_accounts",
				"text"   => l('please-select-an-account')
			);
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			$count = 0;
			$deplay = (int)post('deplay');
			$list_deplay = array();
			for ($i=0; $i < count($accounts); $i++) { 
				$list_deplay[] = $deplay*$i;
			}

			$time_post  = strtotime(NOW) + 60;

			foreach ($accounts as $key => $row) {
				$value = explode("{-}", $row);
				if(count($value) == 2){
					$data["social"]        = 'instagram';
					$data["schedule_type"] = 'unfollow';
					$data["account"]       = $value[0];
					$data["name"]          = $value[1];
					$data["uid"]           = session("uid");
					$data["deplay"]        = (int)post("deplay");
					$data["maximum"]       = (int)post("maximum");
					$data["repeat_post"]  = 1;
					$data["repeat_time"]   = $deplay;
					$data["repeat_end"]    = "2030-09-09 00:00:00";
					$data["changed"]       = NOW;
					$data["created"]       = NOW;
					$data["time_post"]     = date("Y-m-d H:i:s", $time_post + $list_deplay[$key]);

					$check = $this->model->get("*", SCHEDULE_TB, "schedule_type = 'unfollow' AND uid = '".session("uid")."' AND account = '".$value[0]."'");
					if(empty($check)){
						$this->db->insert(SCHEDULE_TB, $data);
					}else{
						$this->db->update(SCHEDULE_TB, $data, "id = '".$check->id."'");
					}
					$count++;
				}
			}

			if($count != 0){
				$json[] = array(
					"text"   => l('add-successfully')
				);
				$json["st"] = "success";
			}else{
				$json[] = array(
					"text"   => l('the-error-occurred-during-processing')
				);
				$json["st"] = "error";
			}
		}

		printf(json_encode($json));
	}

	public function cronjob1(){
		$spintax = new Spintax();
		ini_set('max_execution_time', 300000);

	 	$result = $this->db
	    ->select('*')
	    ->from(SCHEDULE_TB)
	    ->where('status != ', 2)
	    ->where('status != ', 3)
	    ->where('status != ', 5)
	    ->where('schedule_type', 'post')
	    ->where('time_post <= ', NOW)
	    ->get()->result();
	    
		if(!empty($result)){
			foreach ($result as $key => $row) {
		    	$this->db->update(SCHEDULE_TB, array('status' => 3), "id = '".$row->id."'");
		    }

			foreach ($result as $key => $row) {
				$delete       = $row->delete;
				$repeat       = $row->repeat_post;
				$repeat_time  = $row->repeat_time;
				$repeat_end   = $row->repeat_end;
				$time_post    = $row->time_post;
				$deplay       = $row->deplay;

				$time_post          = strtotime($time_post) + $repeat_time;
				$time_post_only_day = date("Y-m-d", $time_post);
				$time_post_day      = strtotime($time_post_only_day);
				$repeat_end         = strtotime($repeat_end);

				$row->url         = $spintax->process($row->url);
				$row->message     = $spintax->process($row->message);
				$row->title       = $spintax->process($row->title);
				$row->description = $spintax->process($row->description);
				$row->image       = $spintax->process($row->image);
				$row->caption     = $spintax->process($row->caption);

				$INSTAGRAM = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "id = '".$row->account."'");
				if(!empty($INSTAGRAM)){
					$row->username = $INSTAGRAM->username;
					$row->password = $INSTAGRAM->password;
					$response = INSTAGRAM_POST($row);
					if(!empty($response)){
						if($repeat == 1 && $time_post_day < $repeat_end){
							$this->db->update(SCHEDULE_TB,array("status" => 4, 'time_post' => date("Y-m-d H:i:s", $time_post), 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
						}else{
							if($delete == 1){
								$this->db->delete(SCHEDULE_TB, "id = {$row->id}");
							}else{
								$this->db->update(SCHEDULE_TB,array("status" => isset($response["message"])?3:2, 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
							}
						}
					}
				}
			}
		}
		echo "Successfully";
	}

	public function cronjob2(){
		$spintax = new Spintax();
		ini_set('max_execution_time', 300000);

	 	$result = $this->db
	    ->select('*')
	    ->from(SCHEDULE_TB)
	    ->where('status != ', 2)
	    ->where('status != ', 3)
	    ->where('status != ', 5)
	    ->where('schedule_type', 'message')
	    ->where('time_post <= ', NOW)
	    ->get()->result();
	    
		if(!empty($result)){
			foreach ($result as $key => $row) {
		    	$this->db->update(SCHEDULE_TB, array('status' => 3), "id = '".$row->id."'");
		    }

			foreach ($result as $key => $row) {
				$delete       = $row->delete;
				$repeat       = $row->repeat_post;
				$repeat_time  = $row->repeat_time;
				$repeat_end   = $row->repeat_end;
				$time_post    = $row->time_post;
				$deplay       = $row->deplay;

				$time_post          = strtotime($time_post) + $repeat_time;
				$time_post_only_day = date("Y-m-d", $time_post);
				$time_post_day      = strtotime($time_post_only_day);
				$repeat_end         = strtotime($repeat_end);

				$row->url         = $spintax->process($row->url);
				$row->message     = $spintax->process($row->message);
				$row->title       = $spintax->process($row->title);
				$row->description = $spintax->process($row->description);
				$row->image       = $spintax->process($row->image);
				$row->caption     = $spintax->process($row->caption);

				$INSTAGRAM = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "id = '".$row->account."'");
				if(!empty($INSTAGRAM)){
					$row->username = $INSTAGRAM->username;
					$row->password = $INSTAGRAM->password;
					$response = INSTAGRAM_POST($row);
					if(!empty($response)){
						if($repeat == 1 && $time_post_day < $repeat_end){
							$this->db->update(SCHEDULE_TB,array("status" => 4, 'time_post' => date("Y-m-d H:i:s", $time_post), 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
						}else{
							if($delete == 1){
								$this->db->delete(SCHEDULE_TB, "id = {$row->id}");
							}else{
								$this->db->update(SCHEDULE_TB,array("status" => isset($response["message"])?3:2, 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
							}
						}
					}
				}
			}
		}
		echo "Successfully";
	}

	public function cronjob3(){
		$spintax = new Spintax();
		ini_set('max_execution_time', 300000);

	 	$result = $this->db
	    ->select('*')
	    ->from(SCHEDULE_TB)
	    ->where('status != ', 2)
	    ->where('status != ', 3)
	    ->where('status != ', 5)
	    ->where('schedule_type', 'comment')
	    ->where('time_post <= ', NOW)
	    ->get()->result();
	    
		if(!empty($result)){
			foreach ($result as $key => $row) {
		    	$this->db->update(SCHEDULE_TB, array('status' => 3), "id = '".$row->id."'");
		    }

			foreach ($result as $key => $row) {
				$delete       = $row->delete;
				$repeat       = $row->repeat_post;
				$repeat_time  = $row->repeat_time;
				$repeat_end   = $row->repeat_end;
				$time_post    = $row->time_post;
				$deplay       = $row->deplay;

				$time_post          = strtotime($time_post) + $repeat_time;
				$time_post_only_day = date("Y-m-d", $time_post);
				$time_post_day      = strtotime($time_post_only_day);
				$repeat_end         = strtotime($repeat_end);

				$row->url         = $spintax->process($row->url);
				$row->message     = $spintax->process($row->message);
				$row->title       = $spintax->process($row->title);
				$row->description = $spintax->process($row->description);
				$row->image       = $spintax->process($row->image);
				$row->caption     = $spintax->process($row->caption);

				$INSTAGRAM = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "id = '".$row->account."'");
				if(!empty($INSTAGRAM)){
					$row->username = $INSTAGRAM->username;
					$row->password = $INSTAGRAM->password;
					$response = INSTAGRAM_POST($row);
					if(!empty($response)){
						if($repeat == 1 && $time_post_day < $repeat_end){
							$this->db->update(SCHEDULE_TB,array("status" => 4, 'time_post' => date("Y-m-d H:i:s", $time_post), 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
						}else{
							if($delete == 1){
								$this->db->delete(SCHEDULE_TB, "id = {$row->id}");
							}else{
								$this->db->update(SCHEDULE_TB,array("status" => isset($response["message"])?3:2, 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
							}
						}
					}
				}
			}
		}
		echo "Successfully";
	}

	public function cronjob4(){
		$spintax = new Spintax();
		ini_set('max_execution_time', 300000);

	 	$result = $this->db
	    ->select('*')
	    ->from(SCHEDULE_TB)
	    ->where('status != ', 2)
	    ->where('status != ', 3)
	    ->where('status != ', 5)
	    ->where('schedule_type', 'like')
	    ->where('time_post <= ', NOW)
	    ->get()->result();
	    
		if(!empty($result)){
			foreach ($result as $key => $row) {
		    	$this->db->update(SCHEDULE_TB, array('status' => 3), "id = '".$row->id."'");
		    }
		    
			foreach ($result as $key => $row) {
				$delete       = $row->delete;
				$repeat       = $row->repeat_post;
				$repeat_time  = $row->repeat_time;
				$repeat_end   = $row->repeat_end;
				$time_post    = $row->time_post;
				$deplay       = $row->deplay;

				$time_post          = strtotime($time_post) + $repeat_time;
				$time_post_only_day = date("Y-m-d", $time_post);
				$time_post_day      = strtotime($time_post_only_day);
				$repeat_end         = strtotime($repeat_end);

				$row->url         = $spintax->process($row->url);
				$row->message     = $spintax->process($row->message);
				$row->title       = $spintax->process($row->title);
				$row->description = $spintax->process($row->description);
				$row->image       = $spintax->process($row->image);
				$row->caption     = $spintax->process($row->caption);

				$INSTAGRAM = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "id = '".$row->account."'");
				if(!empty($INSTAGRAM)){
					$row->username = $INSTAGRAM->username;
					$row->password = $INSTAGRAM->password;
					$response = INSTAGRAM_POST($row);
					if(!empty($response)){
						if($repeat == 1 && $time_post_day < $repeat_end){
							$this->db->update(SCHEDULE_TB,array("status" => 4, 'time_post' => date("Y-m-d H:i:s", $time_post), 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
						}else{
							if($delete == 1){
								$this->db->delete(SCHEDULE_TB, "id = {$row->id}");
							}else{
								$this->db->update(SCHEDULE_TB,array("status" => isset($response["message"])?3:2, 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
							}
						}
					}
				}
			}
		}
		echo "Successfully";
	}

	public function cronjob5(){
		$spintax = new Spintax();
		ini_set('max_execution_time', 300000);

	 	$result = $this->db
	    ->select('*')
	    ->from(SCHEDULE_TB)
	    ->where('status != ', 2)
	    ->where('status != ', 3)
	    ->where('status != ', 5)
	    ->where('schedule_type', 'follow')
	    ->where('time_post <= ', NOW)
	    ->get()->result();
	    
		if(!empty($result)){
			foreach ($result as $key => $row) {
				$delete       = $row->delete;
				$repeat       = $row->repeat_post;
				$repeat_time  = $row->repeat_time;
				$repeat_end   = $row->repeat_end;
				$time_post    = $row->time_post;
				$deplay       = $row->deplay;

				$time_post          = strtotime($time_post) + $repeat_time;
				$time_post_only_day = date("Y-m-d", $time_post);
				$time_post_day      = strtotime($time_post_only_day);
				$repeat_end         = strtotime($repeat_end);

				$row->url         = $spintax->process($row->url);
				$row->message     = $spintax->process($row->message);
				$row->title       = $spintax->process($row->title);
				$row->description = $spintax->process($row->description);
				$row->image       = $spintax->process($row->image);
				$row->caption     = $spintax->process($row->caption);

				$INSTAGRAM = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "id = '".$row->account."'");
				if(!empty($INSTAGRAM)){
					$row->username = $INSTAGRAM->username;
					$row->password = $INSTAGRAM->password;
					$response = INSTAGRAM_POST($row);
					if(!empty($response)){
						if($repeat == 1 && $time_post_day < $repeat_end){
							$this->db->update(SCHEDULE_TB,array("status" => 4, 'time_post' => date("Y-m-d H:i:s", $time_post), 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
						}else{
							if($delete == 1){
								$this->db->delete(SCHEDULE_TB, "id = {$row->id}");
							}else{
								$this->db->update(SCHEDULE_TB,array("status" => isset($response["message"])?3:2, 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
							}
						}
					}
				}
			}
		}
		echo "Successfully";
	}

	public function cronjob6(){
		$spintax = new Spintax();
		ini_set('max_execution_time', 300000);

	 	$result = $this->db
	    ->select('*')
	    ->from(SCHEDULE_TB)
	    ->where('status != ', 2)
	    ->where('status != ', 3)
	    ->where('status != ', 5)
	    ->where('schedule_type', 'followback')
	    ->where('time_post <= ', NOW)
	    ->get()->result();
	    
		if(!empty($result)){
			foreach ($result as $key => $row) {
				$delete       = $row->delete;
				$repeat       = $row->repeat_post;
				$repeat_time  = $row->repeat_time;
				$repeat_end   = $row->repeat_end;
				$time_post    = $row->time_post;
				$deplay       = $row->deplay;

				$time_post          = strtotime($time_post) + $repeat_time;
				$time_post_only_day = date("Y-m-d", $time_post);
				$time_post_day      = strtotime($time_post_only_day);
				$repeat_end         = strtotime($repeat_end);

				$row->url         = $spintax->process($row->url);
				$row->message     = $spintax->process($row->message);
				$row->title       = $spintax->process($row->title);
				$row->description = $spintax->process($row->description);
				$row->image       = $spintax->process($row->image);
				$row->caption     = $spintax->process($row->caption);

				$INSTAGRAM = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "id = '".$row->account."'");
				if(!empty($INSTAGRAM)){
					$row->username = $INSTAGRAM->username;
					$row->password = $INSTAGRAM->password;
					$response = INSTAGRAM_POST($row);
					if(!empty($response)){
						if($repeat == 1 && $time_post_day < $repeat_end){
							$this->db->update(SCHEDULE_TB,array("status" => 4, 'time_post' => date("Y-m-d H:i:s", $time_post), 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
						}else{
							if($delete == 1){
								$this->db->delete(SCHEDULE_TB, "id = {$row->id}");
							}else{
								$this->db->update(SCHEDULE_TB,array("status" => isset($response["message"])?3:2, 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
							}
						}
					}
				}
			}
		}
		echo "Successfully";
	}

	public function cronjob7(){
		$spintax = new Spintax();
		ini_set('max_execution_time', 300000);

	 	$result = $this->db
	    ->select('*')
	    ->from(SCHEDULE_TB)
	    ->where('status != ', 2)
	    ->where('status != ', 3)
	    ->where('status != ', 5)
	    ->where('schedule_type', 'unfollow')
	    ->where('time_post <= ', NOW)
	    ->get()->result();

	    
		if(!empty($result)){
			foreach ($result as $key => $row) {
				$delete       = $row->delete;
				$repeat       = $row->repeat_post;
				$repeat_time  = $row->repeat_time;
				$repeat_end   = $row->repeat_end;
				$time_post    = $row->time_post;
				$deplay       = $row->deplay;

				$time_post          = strtotime($time_post) + $repeat_time;
				$time_post_only_day = date("Y-m-d", $time_post);
				$time_post_day      = strtotime($time_post_only_day);
				$repeat_end         = strtotime($repeat_end);

				$row->url         = $spintax->process($row->url);
				$row->message     = $spintax->process($row->message);
				$row->title       = $spintax->process($row->title);
				$row->description = $spintax->process($row->description);
				$row->image       = $spintax->process($row->image);
				$row->caption     = $spintax->process($row->caption);

				$INSTAGRAM = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "id = '".$row->account."'");
				if(!empty($INSTAGRAM)){
					$row->username = $INSTAGRAM->username;
					$row->password = $INSTAGRAM->password;
					$response = INSTAGRAM_POST($row);
					if(!empty($response)){
						if($repeat == 1 && $time_post_day < $repeat_end){
							$this->db->update(SCHEDULE_TB,array("status" => 4, 'time_post' => date("Y-m-d H:i:s", $time_post), 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
						}else{
							if($delete == 1){
								$this->db->delete(SCHEDULE_TB, "id = {$row->id}");
							}else{
								$this->db->update(SCHEDULE_TB,array("status" => isset($response["message"])?3:2, 'code' => isset($response["code"])?$response["code"]:"", 'result' => isset($response["id"])?$response["id"]:"", 'message_error' => isset($response["message"])?$response["message"]:""), "id = {$row->id}");
							}
						}
					}
				}
			}
		}
		echo "Successfully";
	}
}