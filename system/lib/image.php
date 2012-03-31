<?php if(!defined('MPHP')) die(FORBIDDEN);

class Image
{
	public $errno;
	public $error;

	private $err_arr = array(
		UPLOAD_ERR_INI_SIZE 	=> 'The uploaded file exceeds the upload_max_filesize directive in php.ini', // 1
		UPLOAD_ERR_FORM_SIZE	=> 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', // 2
		UPLOAD_ERR_PARTIAL		=> 'The uploaded file was only partially uploaded', // 3
		UPLOAD_ERR_NO_FILE		=> 'No file was uploaded', // 4
		UPLOAD_ERR_NO_TMP_DIR	=> 'Missing a temporary folder', // 6
		UPLOAD_ERR_CANT_WRITE	=> 'Failed to write file to disk', // 7
		UPLOAD_ERR_EXTENSION	=> 'File upload stopped by extension' // 8
		);

	public function __construct()
	{
		
	}

	public function upload($name)
	{
		if(!array_key_exists($name, $_FILES))
		{
			return false;
		}
	}

	public function resize()
	{
		echo '1';
	}

	public function get_msg($errno)
	{
		if(array_key_exists($errno, $this->err_arr))
		{
			return $this->err_arr($errno);
		}
		else
		{
			return 'Unknown error.'
		}
	}
}