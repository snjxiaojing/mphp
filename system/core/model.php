<?php if(!defined('MPHP')) die(FORBIDDEN);

abstract class M_Model
{
	public function __construct()
	{

	}

	public function __get($key)
	{
		$M = get_instance();
		// p(get_class_vars('M'));
		return $M->$key;
	}
}