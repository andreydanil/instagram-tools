<?php
if(!function_exists("GOOGLE_GET_USER")){
    function GOOGLE_GET_USER($code = ""){
        $GA = GOOGLE_API();
        $GA->authenticate($code);
        $oauth2 = new Google_Service_OAuth2($GA);
        $access_token = $GA->getAccessToken();
        $GA->setAccessToken($access_token);
        return $oauth2->userinfo->get();
    }
}

if(!function_exists("GOOGLE_GET_LOGIN_URL")){
    function GOOGLE_GET_LOGIN_URL(){
        $GA = GOOGLE_API();
        return $GA->createAuthUrl();
    }
}

if(!function_exists("GOOGLE_API")){
    function GOOGLE_API(){
        require_once APPPATH.'libraries/Google/autoload.php';

        $client = new Google_Client();
        $client->setAccessType('offline');
        $client->setApplicationName(TITLE);
        $client->setRedirectUri(PATH."openid/google");
        $client->setClientId(GOOGLE_ID);
        $client->setClientSecret(GOOGLE_SECRET);
        $client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile','https://www.googleapis.com/auth/plus.login'));
        return $client;
    }
}
?>