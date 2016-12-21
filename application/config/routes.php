<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['dashboard'] = 'Home/dashboard';
$route['verify'] = 'Home/verify';
$route['language'] = 'Home/language';
$route['openid/(:any)'] = 'Users/openid/$1';
$route['logout'] = 'Users/logout';
$route['settings'] = 'Settings/index';
$route['users'] = 'Users/index';
$route['users/update'] = 'Users/update';
$route['users/profile'] = 'Users/profile';

$route['cronjob/post'] = 'Schedule/cronjob1';
$route['cronjob/directmessage'] = 'Schedule/cronjob2';
$route['cronjob/comment'] = 'Schedule/cronjob3';
$route['cronjob/like'] = 'Schedule/cronjob4';
$route['cronjob/follow'] = 'Schedule/cronjob5';
$route['cronjob/followback'] = 'Schedule/cronjob6';
$route['cronjob/unfollow'] = 'Schedule/cronjob7';
$route['schedule'] = 'Schedule/index';
$route['instagram/save'] = 'Instagram_save/index';
$route['instagram/analytics'] = 'Instagram_analytics/index';
$route['instagram/account'] = 'Instagram_account/index';
$route['instagram/search'] = 'Instagram_search/index';
$route['instagram/direct-message'] = 'Instagram_direct_message/index';
$route['instagram/comment'] = 'Instagram_comment/index';
$route['instagram/like'] = 'Instagram_like/index';
$route['instagram/follow/log'] = 'Instagram_follow/index';
$route['instagram/follow'] = 'Instagram_follow/follow';
$route['instagram/followback'] = 'Instagram_follow/followback';
$route['instagram/unfollow'] = 'Instagram_follow/unfollow';
$route['instagram/account'] = 'Instagram_account/index';