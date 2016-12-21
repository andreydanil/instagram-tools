<?php
if(!function_exists("TWITTER_GET_USER")){
    function TWITTER_GET_USER(){
        $TW = TWITTER_API();
        $TW->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        unset($_SESSION['oauth_verify']);
        $reply = $TW->oauth_accessToken(['oauth_verifier' => get('oauth_verifier')]);
        $_SESSION['oauth_token'] = $reply->oauth_token;
        $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;

        $TW->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $reply = $TW->account_verifyCredentials();
        unset($_SESSION['oauth_token']);
        if(!empty($reply) && $reply->httpstatus == 200){
            return $reply;
        }else{
            return false;
        }
    }
}

if(!function_exists("TWITTER_GET_LOGIN_URL")){
    function TWITTER_GET_LOGIN_URL(){
        
            $TW = TWITTER_API();
            $reply = $TW->oauth_requestToken(['oauth_callback' => PATH."openid/twitter"]);
            try {
                $TW->setToken($reply->oauth_token, $reply->oauth_token_secret);
            } catch (Exception $e) {
                return false;
            }
            $_SESSION['oauth_token'] = $reply->oauth_token;
            $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
            $_SESSION['oauth_verify'] = true;
            return $TW->oauth_authorize();
    }
}

if(!function_exists("TWITTER_API")){
    function TWITTER_API(){
        require_once APPPATH.'libraries/Twitter/TwitterApi.php';
        \Codebird\Codebird::setConsumerKey(TWITTER_ID, TWITTER_SECRET); // static, see README
        $TW = \Codebird\Codebird::getInstance();
        return $TW;
    }
}
?>