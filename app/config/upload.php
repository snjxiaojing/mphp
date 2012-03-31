<?php if(!defined('MPHP')) die(FORBIDDEN);

$upload = array();

$upload['savedPath'] = ROOT_PATH . 'static/img/tmp/';
$upload['allowType'] = array('jpg', 'gif', 'png');
$upload['allowSize'] = pow(2, 20);