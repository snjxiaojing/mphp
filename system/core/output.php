<?php if(!defined('MPHP')) die(FORBIDDEN);

class M_Output
{
	private $output = '';

	public function __construct()
	{

	}

	public function append($str)
	{
		$this->output .= $str;
	}

	public function get_buffer()
	{
		return $this->output;
	}

	public function __destruct()
	{
		echo $this->output;
	}
}