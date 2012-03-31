<?php if(!defined('MPHP')) die(FORBIDDEN);

$router = array();

$router['default_app'] = 'index';


$router['page/(:num).html'] = 'index/page/$1';
$router['post/(:num).html'] = 'index/post/$1';
$router['category/(:num).html'] = 'index/category/$1';
$router['category/(:num)/(:num).html'] = 'index/category/$1/$2';
$router['tag/(:num).html'] = 'index/tag/$1';
$router['tag/(:num)/(:num).html'] = 'index/tag/$1/$2';
$router['tblog/(:num).html'] = 'tblog/index/$1';
$router['tblog/(:num)/(:num).html'] = 'tblog/index/$1/$2';