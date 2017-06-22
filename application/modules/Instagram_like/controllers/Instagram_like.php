<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_like extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$schedule = $this->model->fetch("*", SCHEDULE_TB, "uid = '".session("uid")."' and schedule_type = 'like'", "time_post", "desc");
		$data = array(
			'accounts' => $accounts,
			'schedule' => $schedule
		);
		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}
}