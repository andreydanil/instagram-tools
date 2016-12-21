<?php
if(!function_exists("INSTAGRAM_TYPE")){
    function INSTAGRAM_TYPE($meadia_type){
        switch ($meadia_type) {
            case 1:
                $type = "Photo";
                break;
            case 2:
                $type = "Video";
                break;
        }

        return $type;
    }
}

if(!function_exists("INSTAGRAM_STATUS")){
    function INSTAGRAM_STATUS($status_id){
        switch ($status_id) {
            case 1:
                $json = array(
                    "label" => "primary",
                    "text"  => "Processing"
                );
                break;
            case 2:
                $json = array(
                    "label" => "success",
                    "text"  => "Published"
                );
                break;
            case 3:
                $json = array(
                    "label" => "danger",
                    "text"  => "Failure"
                );
                break;
            case 4:
                $json = array(
                    "label" => "warning",
                    "text"  => "Repost"
                );
                break;
            case 5:
                $json = array(
                    "label" => "default",
                    "text"  => "Cancel"
                );
                break;
        }

        return (object)$json;
    }
}

if(!function_exists("INSTAGRAM_SEARCH_FEED")){
    function INSTAGRAM_SEARCH_FEED($type,$account,$user_search){
        $i = Instagram($account, "");
        switch ($type) {
            case "timeline":
                $result = $i->timelineFeed();
                break;
            case 'popular':
                $result = $i->getPopularFeed();
                break;
            case 'explore':
                $result = $i->explore();
                break;
            case 'tray':
                $result = $i->getReelsTrayFeed();
                break;
            case 'self':
                $result = $i->getSelfUserFeed();
                break;
            case 'user':
                $result = $i->getHashtagFeed($user_search);
                break;
            case 'following':
                $result = $i->getSelfUsersFollowing();
                break;
            case 'followers':
                $result = $i->getSelfUserFollowers();
                break;
        }


        return $result;
    }
}

if(!function_exists("INSTAGRAM_SORT_HASHTAGS")){
    function INSTAGRAM_SORT_HASHTAGS($data){
        usort($data, function($a, $b) {
            if($a['media_count']==$b['media_count']) return 0;
            return $a['media_count'] < $b['media_count']?1:-1;
        });
        return $data;
    }
}

if(!function_exists("INSTAGRAM_FOLLOW")){
    function INSTAGRAM_FOLLOW($action, $account, $userId){
        $i = Instagram($account, "");
        $result = array();
        switch ($action) {
            case 'follow':
                $result = $i->follow($userId);
                break;
            case 'unfollow':
                $result = $i->unfollow($userId);
                break;
        }

        return $result;
    }
}

if(!function_exists("INSTAGRAM_SEARCH")){
    function INSTAGRAM_SEARCH($type,$account,$keyword){
        $i = Instagram($account, "");
        switch ($type) {
            case 'user':
                $result = $i->searchUsers($keyword);
                break;
            default:
                $result = $i->searchTags($keyword);
                break;
        }

        return $result;
    }
}

if(!function_exists("Instagram")){ 
    function Instagram($username, $password, $debug = false){
        $i = new \InstagramAPI\Instagram($username, $password, $debug);
        return $i;
    }
}

if(!function_exists("require_load")){
    function require_load($file){
        if(file_exists($file))
            include $file;
    }
}

if(!function_exists("INSTAGRAM_GET_POST")){
    function INSTAGRAM_GET_POST($data){
        $response = array();
        $i = Instagram($data->username, $data->password);
        try {
            $response =$i->mediaInfo($data->result);
        } catch (Exception $e){
            $response = "...";
        }
        return $response;
    }
}

if(!function_exists("INSTAGRAM_POST")){
    function INSTAGRAM_POST($data){
        $response = array();
        $i = Instagram($data->username, $data->password);
        if(!is_string($i)){
            switch ($data->schedule_type) {
                case 'post':
                    switch ($data->type) {
                        case 'photo':
                            try {
                                $response =$i->uploadPhoto($data->image, $data->description);
                            } catch (Exception $e){
                                $response = $e->getMessage();
                            }

                            break;
                        case 'video':
                            $url = $data->url;
                            $id = getIdYoutube($data->url);
                            if(strlen($id) == 11){
                                parse_str(file_get_contents('http://www.youtube.com/get_video_info?video_id='.$id),$info);
                                if($info['status'] == "ok"){
                                    $streams = explode(',',$info['url_encoded_fmt_stream_map']);
                                    $type = "video/mp4"; 
                                    foreach($streams as $stream){ 
                                        parse_str($stream,$real_stream);
                                        $stype = $real_stream['type'];
                                        if(strpos($real_stream['type'],';') !== false){
                                            $tmp = explode(';',$real_stream['type']);
                                            $stype = $tmp[0]; 
                                            unset($tmp); 
                                        } 
                                        if($stype == $type && ($real_stream['quality'] == 'large' || $real_stream['quality'] == 'medium' || $real_stream['quality'] == 'small')){
                                            try {
                                                $response =$i->uploadVideo($real_stream['url'].'&signature='.@$real_stream['sig'], $data->description);
                                            } catch (Exception $e){
                                                $response = $e->getMessage();
                                            }
                                        }
                                    }
                                }else{
                                    $response = array(
                                        "status"  => "fail",
                                        "message" => strip_tags($info['reason'])
                                    );
                                }
                            }else{
                                if (strpos($url, 'facebook.com') != false) {
                                    $url = fbdownloadVideo($url);
                                }

                                try {
                                    $response =$i->uploadVideo($url, $data->description);
                                } catch (Exception $e){
                                    $response = $e->getMessage();
                                }
                            }

                            break;
                    }

                    if(isset($response->status) && $response->status == "ok"){
                        $response = array(
                            "status"  => "success",
                            "id"      => $response->media_id,
                            "code"    => $response->media_code
                        );
                    }
                    break;
                
                case 'comment':
                    try {
                        $response = $i->comment($data->media_id, $data->description);
                        $response = array(
                            "status"  => "success",
                            "code"    => $data->code
                        );
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;
                case 'message':
                    try {
                        $response = $i->direct_message($data->media_id, $data->description);
                        $response = array(
                            "status"  => "success"
                        );
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;
                case 'like':
                    try {
                        $i->like($data->media_id);
                        $response = array(
                            "status"  => "success",
                            "code"    => $data->code
                        );
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;
                case 'follow':
                    try {
                        if(!empty($data->description)){
                            $hashtags = explode(",", $data->description);
                            $hashtag = array_rand($hashtags);
                            $result = $i->getHashtagFeed($hashtag);
                            $maximum = rand(1, $data->maximum);
                            if(!empty($result) && $result->status == "ok" && !empty($result->items)){
                                foreach ($result->items as $key => $row) {
                                    if($key + 1 <= $maximum){
                                        $response = $i->follow($row->user->pk);
                                        $CI =& get_instance();
                                        $CI->load->model('Schedule_model', 'schedule_model');
                                        $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                            "pk"           => $row->user->pk,
                                            "name"         => $row->user->username,
                                            "type"         => $data->schedule_type,
                                            "uid"          => $data->uid,
                                            "account_id"   => $data->account,
                                            "account_name" => $data->name,
                                            "created"      => NOW
                                        ));
                                    }else{
                                        break;
                                    }
                                }
                            }
                        }else{
                            $response = array(
                                "status"  => "fail",
                                "message" => "Hashtag null"
                            );
                        }
                        
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;
                case 'followback':
                    try { 
                        $result = $i->getRecentActivity();
                        $maximum = rand(1, $data->maximum);
                        $count = 0;
                        if(!empty($result) && $result->status == "ok" && !empty($result->old_stories)){
                            foreach ($result->old_stories as $key => $row) {
                                $text = $row->args->text;
                                if(strpos($text,"started following you.") != "" && $row->args->inline_follow['following'] == ""){
                                    if($count < $maximum){
                                        $response = $i->follow($row->args->profile_id);
                                        if($data->description != ""){
                                            $i->direct_message($row->args->profile_id, $data->description);
                                        }
                                        $CI =& get_instance();
                                        $CI->load->model('Schedule_model', 'schedule_model');
                                        $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                            "pk"           => $row->args->profile_id,
                                            "name"         => $row->args->inline_follow['user_info']['username'],
                                            "type"         => $data->schedule_type,
                                            "uid"          => $data->uid,
                                            "account_id"   => $data->account,
                                            "account_name" => $data->name,
                                            "created"      => NOW
                                        ));
                                    }else{
                                        break;
                                    }
                                    $count++;
                                }
                            }
                        }
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;
                case 'unfollow':
                    try {
                        $result = $i->getSelfUsersFollowing();
                        $maximum = rand(1, $data->maximum);
                        if(!empty($result) && $result->status == "ok" && !empty($result->followings)){
                            foreach ($result->followings as $key => $row) {
                                if($key + 1 <= $maximum){
                                    $response = $i->unfollow($row->pk);
                                    $CI =& get_instance();
                                    $CI->load->model('Schedule_model', 'schedule_model');
                                    $lang = $CI->db->insert(INSTAGRAM_FOLLOW_TB, array(
                                        "pk"           => $row->pk,
                                        "name"         => $row->username,
                                        "type"         => $data->schedule_type,
                                        "uid"          => $data->uid,
                                        "account_id"   => $data->account,
                                        "account_name" => $data->name,
                                        "created"      => NOW
                                    ));
                                }else{
                                    break;
                                }
                            }
                        }
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }
                    break;
            }

            if(is_string($response)){
                $response = array(
                    "status"  => "error",
                    "message" => $response
                );
            }
        }else{
            $response["message"] = "Upload faild, Please try again";
            $response = array(
                "status"  => "error",
                "message" => $response["message"]
            );
        }
        
        return $response;
        
    }
}
