<?php
if(!function_exists("FACEBOOK_GET_USER")){
    function FACEBOOK_GET_USER(){
    	$FB = FACEBOOK_API();
    	$access_token = $FB->getAccessToken();
        try{
            $params = array("fields=id,name,email", "access_token" => $access_token);
            return $FB->api( '/v2.7/me' , 'GET', $params );
        }catch ( Exception $e ) {
            return false;
        }
    }
}

if(!function_exists("FACEBOOK_GET_ACCESS_TOKEN_FROM_CODE")){
	function FACEBOOK_GET_ACCESS_TOKEN_FROM_CODE($code = ""){
		$FB = FACEBOOK_API();
		return $FB->getAccessTokenFromCode($code);
	}
}

if(!function_exists("FACEBOOK_GET_LOGIN_URL")){
	function FACEBOOK_GET_LOGIN_URL(){
		$FB = FACEBOOK_API();
		return $FB->getLoginUrl(array('scope' => 'email', 'redirect' => PATH."openid/facebook"));
	}
}


if(!function_exists("FACEBOOK_API")){
    function FACEBOOK_API(){
        require_once( APPPATH."libraries/Facebook/facebook.php" );
        $FB  = new FacebookCustom( array("appId" => FACEBOOK_ID, "secret" => FACEBOOK_SECRET) );
        return $FB;
    }
}
?>