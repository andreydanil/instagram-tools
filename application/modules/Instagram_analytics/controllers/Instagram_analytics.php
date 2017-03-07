<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_analytics extends MX_Controller {
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

	//Chart
	public function ajax_report_posts(){
		$post_success = 0;
		$post_error   = 0;

		$post_complete    = 0;
		$post_processing  = 0;
		$post_cancel      = 0;
		$post_repost      = 0;
		$post_fail        = 0;

		$comment_complete    = 0;
		$comment_processing  = 0;

		$like_complete    = 0;
		$like_processing  = 0;

		$dm_complete    = 0;
		$dm_processing  = 0;

		$post_day  = array();
		$comment_day  = array();
		$like_day  = array();
		$dm_day  = array();

		$follow = array(
			"follow"     => 0,
			"followback" => 0,
			"unfollow"   => 0
		);

		$result = $this->model->fetch("*", SCHEDULE_TB, "uid = '".session("uid")."'");
		$follows = $this->model->fetch("*", INSTAGRAM_FOLLOW_TB, "uid = '".session("uid")."'");
		if(!empty($result)){
			foreach ($result as $key => $row) {
				if($row->schedule_type == "post"){
					switch ($row->status) {
						case 5:
							$post_cancel++;
							break;
						case 1:
							$post_processing++;
							break;
						case 2:
							$post_complete++;
							//check posts
							$post_success++;
							$date = date("Y-m-d", strtotime($row->created));
							if(!isset($post_day[$date])){
								$post_day[$date] = 0;
							}

							$post_day[$date] += 1;
							break;
						case 3:
							$post_fail++;
							$post_error++;
							break;
						case 4:
							$post_repost++;
							//check posts
							if($row->result != ""){
								$post_success++;
								$date = date("Y-m-d", strtotime($row->created));
								if(!isset($post_day[$date])){
									$post_day[$date] = 0;
								}

								$post_day[$date] += 1;
							}else{
								$post_error++;
							}
							break;
					}
				}

				if($row->schedule_type == "message"){
					switch ($row->status) {
						case 1:
							$dm_processing++;
							break;
						case 2:
							$dm_complete++;
							//check dms
							$date = date("Y-m-d", strtotime($row->created));
							if(!isset($dm_day[$date])){
								$dm_day[$date] = 0;
							}

							$dm_day[$date] += 1;
							break;
					}
				}

				if($row->schedule_type == "comment"){
					switch ($row->status) {
						case 1:
							$comment_processing++;
							break;
						case 2:
							$comment_complete++;
							//check comments
							$date = date("Y-m-d", strtotime($row->created));
							if(!isset($comment_day[$date])){
								$comment_day[$date] = 0;
							}

							$comment_day[$date] += 1;
							break;
					}
				}

				if($row->schedule_type == "like"){
					switch ($row->status) {
						case 1:
							$like_processing++;
							break;
						case 2:
							$like_complete++;
							//check likes
							$date = date("Y-m-d", strtotime($row->created));
							if(!isset($like_day[$date])){
								$like_day[$date] = 0;
							}

							$like_day[$date] += 1;
							break;
					}
				}
			}
		}

		if(!empty($follows)){
			foreach ($follows as $value) {
				switch ($value->type) {
					case 'follow':
						$follow['follow'] += 1;
						break;
					case 'followback':
						$follow['followback'] += 1;
						break;
					case 'unfollow':
						$follow['unfollow'] += 1;
						break;
				}
			}
		}

		$post_by_status    = "['Complete',".$post_complete."],['Failure',".$post_fail."],['Processing',".$post_processing."],['Repost',".$post_repost."],['Cancel',".$post_cancel."]";
		$comment_by_status = "['Complete',".$comment_complete."],['Processing',".$comment_processing."]";
		$like_by_status    = "['Complete',".$like_complete."],['Processing',".$like_processing."]";
		$dm_by_status      = "['Complete',".$dm_complete."],['Processing',".$dm_processing."]";

		$post_by_day = "";
		if(!empty($post_day)){
			foreach ($post_day as $key => $value) {
				$year  = date("Y", strtotime($key));
	            $month = date("n", strtotime($key)) - 1;
	            $day   = date("j", strtotime($key));
				$post_by_day.="[Date.UTC(".$year.",".$month.",".$day."),".$value."],";
			}
		}

		$comment_by_day = "";
		if(!empty($comment_day)){
			foreach ($comment_day as $key => $value) {
				$year  = date("Y", strtotime($key));
	            $month = date("n", strtotime($key)) - 1;
	            $day   = date("j", strtotime($key));
				$comment_by_day.="[Date.UTC(".$year.",".$month.",".$day."),".$value."],";
			}
		}

		$like_by_day = "";
		if(!empty($like_day)){
			foreach ($like_day as $key => $value) {
				$year  = date("Y", strtotime($key));
	            $month = date("n", strtotime($key)) - 1;
	            $day   = date("j", strtotime($key));
				$like_by_day.="[Date.UTC(".$year.",".$month.",".$day."),".$value."],";
			}
		}

		$dm_by_day = "";
		if(!empty($dm_day)){
			foreach ($dm_day as $key => $value) {
				$year  = date("Y", strtotime($key));
	            $month = date("n", strtotime($key)) - 1;
	            $day   = date("j", strtotime($key));
				$dm_by_day.="[Date.UTC(".$year.",".$month.",".$day."),".$value."],";
			}
		}
		
		$data = array(
			"post_by_day"         => $post_by_day,
			"post_by_status"      => $post_by_status,
			"comment_by_day"      => $comment_by_day,
			"comment_by_status"   => $comment_by_status,
			"like_by_day"         => $like_by_day,
			"like_by_status"      => $like_by_status,
			"dm_by_day"           => $dm_by_day,
			"dm_by_status"        => $dm_by_status,
			"follow"              => $follow
		);

		$this->load->view("chart/post",$data);
	}
}