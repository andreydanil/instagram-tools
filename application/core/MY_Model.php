<?php
class MY_Model extends CI_Model
{
	function __construct(){
		parent::__construct();
		// Load the Database Module REQUIRED for this to work.
		$this->load->database();//Without it -> Message: Undefined property: XXXController::$db
	}
	
	function fetch($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $start = -1, $limit = 0, $return_array = false)
	{
		$this->db->select($select);
		if($where != "")
		{
			$this->db->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$this->db->order_by('rand()');
			}else{
				$this->db->order_by($order, $by);
			}
		}
		
		if((int)$start >= 0 && (int)$limit > 0)
		{
			$this->db->limit($limit, $start);
		}
		#Query
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->result_array();
		} else {
			$result = $query->result();
		}
		$query->free_result();
		return $result;
	}	
	
	function get($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $return_array = false)
	{
		$this->db->select($select);
		if($where != "")
		{
			$this->db->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$this->db->order_by('rand()');
			}else{
				$this->db->order_by($order, $by);
			}
		}		
		#Query
		$query = $this->db->get($table);
		if($return_array){
			$result = $query->row_array();
		} else {
			$result = $query->row();
		}
		$query->free_result();
		return $result;
	}
	
	function history_ip($USER= array()){
		if(!empty($USER)){
			$ip= getIP();$arr_ip= array();$key= false;
			if(!is_array(json_decode($USER->history_ip))){
				$arr_ip= @get_object_vars(json_decode($USER->history_ip));
			}else{
				$arr_ip= json_decode($USER->history_ip);
			}
	
			if(!empty($arr_ip)){
				$key = array_key_exists($ip, $arr_ip);   // $key = 1;
			}else{
				$arr_ip= array($ip=>1);
			}
			
			if(empty($key)){
				//NOT EXIST
				if(empty($USER->history_ip)){
					$arr_ip= array($ip=>1);
				}else{
					$new_arr_ip= array($ip=>1);
					$arr_ip= array_merge($arr_ip, $new_arr_ip);
				}
			}else{
				$arr_ip[$ip]= $arr_ip[$ip]+1;
			}

			$update['history_ip']= json_encode($arr_ip);
			$this->db->update(USER_TB, $update, "id = '{$USER->id}'");			
		}
	}			
	
	function session($USER= ''){
		set_session('uid', $USER->id);
		set_session('admin', $USER->admin);
		set_session('pid', $USER->pid);
		set_session('fullname', $USER->fullname);
		set_session('username', $USER->email);
		set_session('user_type', $USER->type);
		set_session('user_created', $USER->created);
		if (!file_exists('uploads/user'.$USER->id)) {
		    mkdir('uploads/user'.$USER->id, 0777, true);
		}
		$this->input->set_cookie('folderid', 'user'.$USER->id, 86400);
	}
	
	function validate_error(&$error='', &$form_error=''){
		$error= (empty($error))?$form_error:true;
		return $error;
	}

	function validate_null(&$arr_error='', &$form_error='', $field=''){
		$field= $this->input->post($field);

		if(!is_array($field))
		{
			$field= trim($field);
			if($field == '')
			{
				$form_error= true;
				return FALSE;
			}
		}
		else
		{
			$field= trim($field[0]);
			if($field == '')
			{
				$form_error= true;
				return FALSE;
			}
		}
		return TRUE;
	}	
	
	function validate_ext(&$arr_error='', &$error='', $field='', $txt=''){
		if($this->validate_null($arr_error, $form_error, $field)){		
		}else{
			$arr_error[]= array(
				'field'	=> $field,
				'txt'	=> (!empty($txt)) ? $txt : $this->require_txt
			);			
		}
		$this->validate_error($error, $form_error);
	}		
	
	function validate_youtube(&$arr_error='', &$error='', $field='', $txt='', &$youtube_id=''){
		if($this->validate_null($arr_error, $form_error, $field)){	
			$youtube_id= youtube_id($this->input->post($field));
			if(empty($youtube_id))
			{
				$arr_error[]= array(
					'field'	=> $field,
					'txt'	=> $txt
				);
				$form_error= true;				
			}
		}else{
			$arr_error[]= array(
				'field'	=> $field,
				'txt'	=> $txt
			);			
		}
		$this->validate_error($error, $form_error);
	}			
	
	function permission($mid= 0, $type= ''){
		if(!$this->session->userdata('admin'))
		{
			if(!empty($mid))
			{
				$rid= $this->session->userdata('admin_rid');
				$role_permission= $this->adminls_model->get('*', ADMIN_ROLE_PERMISSION_TB, "rid = '{$rid}' AND mid = '{$mid}'");

				if(empty($role_permission))
				{
					redirect(base_url().LINK_ADMIN_PERMISSION_DENY);
				}
				else
				{
					$permission= json_decode($role_permission->permission);
					switch($type){
						case('read'):
						case('edit'):
						case('delete'):
						case('manage'):
							if(empty($permission->$type)) redirect(base_url().LINK_ADMIN_PERMISSION_DENY);
							break;
					}
				}
			}
			else
			{
				redirect(base_url().LINK_ADMIN_PERMISSION_DENY);
			}
		}
		else
		{
			$module= $this->get('*', ADMIN_MODULE_TB, "id = '{$mid}'");
			if(!empty($module))
			{
				$data_module= json_decode($module->data);
				switch($type){
					case('edit'):
						if(empty($data_module->edit)) redirect(base_url().LINK_ADMIN_PERMISSION_DENY);
						break;
					case('delete'):
						if(empty($data_module->delete)) redirect(base_url().LINK_ADMIN_PERMISSION_DENY);
						break;
				}			
			}
		}
	}	
}