<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_follow_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	function getList($limit=-1, $page=-1){
		if($limit == -1){
			$this->db->select('count(*) as sum');
		}else{
			$this->db->select('*');
		}
		
		$this->db->from(INSTAGRAM_FOLLOW_TB);

		if($limit != -1) {
			$this->db->limit($limit,$page);
		}

		if(get("type")){
			$this->db->where("type = '".get("type")."'");
		}

		if(get("id")){
			$this->db->where("account_id = '".get("id")."'");
		}

		$this->db->where("uid = '".session("uid")."'");

		$this->db->order_by('created','desc');
		$query = $this->db->get();

		if($query->result()){
			if($limit == -1){
				return $query->row()->sum;
			}else{
				$result =  $query->result();
				return $result;
			}
		}else{
			return false;
		}
	}

}