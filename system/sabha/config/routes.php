<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "dashboard";
$route['settings/closure'] = "settings/closure1";
$route['settings/closure/([a-z]+)'] = "settings/closure1/$1";
$route['404_override'] = '';

/* Location: ./application/config/routes.php */