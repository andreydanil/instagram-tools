<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Spintax
{
    public function process( $text )
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array( $this, 'replace' ),
            $text
        );
    }

    public function replace( $text )
    {
        $text = $this -> process( $text[1] );
        $parts = explode( '|', $text );
        return $parts[ array_rand( $parts ) ];
    }
}

if(!function_exists('check_FFMPEG')){
	function check_FFMPEG(){
	    @exec('ffmpeg -version 2>&1', $output, $returnvalue);
        if ($returnvalue === 0) {
            return 'ffmpeg';
        }
        @exec('avconv -version 2>&1', $output, $returnvalue);
        if ($returnvalue === 0) {
            return 'avconv';
        }

        return false;
	}
}

if(!function_exists('deplay_time')){
	function deplay_time(){
		return array(60,65,70,75,90,85,90,95,100,120,150,180,200,250,300,350,400,450,500,550,600,650,700,750,800,850,900,950,1000,1100,1200,1300,1400,1500,1600,1700,1800);
	}
}

if(!function_exists('time_pause')){
	function time_pause(){
		return array(15,30,45,60,90,120,150,180,210,240,270,300,330,360,390,420,450,480,510,540,570,600,660,720,780,840,900,960,1020,1080,1140,1200,1260,1320,1380,1440,2880,4320,5760,7200);
	}
}

if (!function_exists('l')) {
	function l($slug = ""){
		$CI =& get_instance();
		$lang = $CI->session->userdata("lang");
		$xml = simplexml_load_file(APPPATH."../language/".LANGUAGE.".xml") or die("Error: Cannot create object");
		$text = $slug;
		foreach ($xml->lang as $key => $row) {
			if(xml_attribute($row,"slug") == $slug){
				$text = html_entity_decode(ucfirst($row->text));
			}
		}
		return $text;
	}
}

function fbdownloadVideo($url) {
    $useragent = 'Mozilla/5.0 (Linux; U; Android 2.3.3; de-de; HTC Desire Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $source = curl_exec($ch);
    curl_close($ch);

    $download = explode('/video_redirect/?src=', $source);
    if(isset($download[1])){
	    $download = explode('&amp', $download[1]);
	    $download = rawurldecode($download[0]);
	    return $download;
    }
    
    return "error";
}

function getIdYoutube($link){
    preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $id);
    if(!empty($id)) {
        return $id = $id[0];
    }
    return $link;
}

function incrementalHash($len = 5){
  	$charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
  	$base = strlen($charset);
  	$result = '';

  	$now = explode(' ', microtime())[1];
  	while ($now >= $base){
    	$i = $now % $base;
    	$result = $charset[$i] . $result;
    	$now /= $base;
  	}
  return substr($result, -5);
}

function tz_list() {
  	$zones_array = array();
  	$timestamp = time();
  	foreach(timezone_identifiers_list() as $key => $zone) {
   		date_default_timezone_set($zone);
   		$zones_array[$key]['zone'] = $zone;
    	$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
  	}
  	return $zones_array;
}

if(!function_exists('list_time_zone')){
	function list_time_zone(){
		$regions = array(
		    'Africa' => DateTimeZone::AFRICA,
		    'America' => DateTimeZone::AMERICA,
		    'Antarctica' => DateTimeZone::ANTARCTICA,
		    'Aisa' => DateTimeZone::ASIA,
		    'Atlantic' => DateTimeZone::ATLANTIC,
		    'Europe' => DateTimeZone::EUROPE,
		    'Indian' => DateTimeZone::INDIAN,
		    'Pacific' => DateTimeZone::PACIFIC
		);
		$timezones = array();
		foreach ($regions as $name => $mask)
		{
		    $zones = DateTimeZone::listIdentifiers($mask);
		    foreach($zones as $timezone)
		    {
				// Lets sample the time there right now
				$time = new DateTime(NULL, new DateTimeZone($timezone));
				// Us dumb Americans can't handle millitary time
				$ampm = $time->format('H') > 12 ? ' ('. $time->format('g:i a'). ')' : '';
				// Remove region name and add a sample time
				$timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' - ' . $time->format('H:i') . $ampm;
			}
		}

		return $timezones;
	}
}

if (!function_exists('xml_attribute')) {
	function xml_attribute($object, $attribute)
	{
	    if(isset($object[$attribute]))
	        return (string) $object[$attribute];
	}
}

if (!function_exists('checkRemoteFile')) {
	function checkRemoteFile($url){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$url);
	    // don't download content
	    curl_setopt($ch, CURLOPT_NOBODY, 1);
	    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    if(curl_exec($ch)!==FALSE)
	    {
	        return true;
	    }
	    else
	    {
	        return false;
	    }
	}
}

if (!function_exists('deleteDir')) {
	function deleteDir($path){
		return is_file($path) ? @unlink($path) : array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
	}
}

if (!function_exists('format_number')) {
	function format_number($number = ""){
		return number_format($number, 0, ',',',');
	}
}

if (!function_exists('pr')) {
    function pr($data, $type = 0) {
        print '<pre>';
        print_r($data);
        print '</pre>';
        if ($type != 0) {
            exit();
        }
    }
}

if (!function_exists('filter_input_xss')){
	function filter_input_xss($input){
        if($input)
		  $input= htmlspecialchars($input, ENT_QUOTES);
		return $input;
	}
}

if (!function_exists('segment')){
	function segment($index){
		$CI = &get_instance();
        if($CI->uri->segment($index)){
		  return $CI->uri->segment($index);
        }else{
            return false;
        }
	}
}

if (!function_exists('post')){
	function post($input,$check=true){
		$CI = &get_instance();
        if($check){
		  return $CI->input->post($input);
        }else{
            return $CI->input->post($input);
        }
	}
}

if (!function_exists('get')){
	function get($input){
		$CI = &get_instance();
		return $CI->input->get($input);
	}
}

if (!function_exists('session')){
	function session($input){
		$CI = &get_instance();
		return $CI->session->userdata($input);
	}
}

if (!function_exists('set_session')){
	function set_session($name,$input){
		$CI = &get_instance();
		return $CI->session->set_userdata($name,$input);
	}
}

if (!function_exists('unset_session')){
	function unset_session($name){
		$CI = &get_instance();
		return $CI->session->unset_userdata($name);
	}
}

if (!function_exists('array_flatten')) {
	function array_flatten($data) { 
	  	$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($data));
		$l = iterator_to_array($it, false);
	  	return $l;
	} 
}

function verify($purchaseCode) {
	if(!session("verify")){
		$purchaseCode = ($purchaseCode == "")?"none":$purchaseCode;
		$result = false; // have we got a valid purchase code?
		$our_item_id = 15279075; // check if they've bought this item id.
		$username = 'tienpham1606'; // authors username
		$apiKey = 'xpny3lng9htbi7j6dyn84j556ixu4umv'; // api key from my account area

	    // Open cURL channel
	    $ch = curl_init();

	    // Set cURL options
	    curl_setopt($ch, CURLOPT_URL, "http://marketplace.envato.com/api/edge/$username/$apiKey/verify-purchase:$purchaseCode.json");
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'ENVATO-PURCHASE-VERIFY'); //api requires any user agent to be set

	    $result = json_decode( curl_exec($ch) , true );

	    //check if purchase code is correct
	    if($result != ""){
		    if ( !empty($result['verify-purchase']['item_id']) && $result['verify-purchase']['item_id'] ) {
		    	set_session("verify", $result['verify-purchase']);
		        return $result['verify-purchase'];
		    }
		}elseif($result == ""){	
			return true;
		}

	    //invalid purchase code
	    return false;
	}else{
		return session("verify");
	}
}

?>