<?php if(!defined('MPHP')) die(FORBIDDEN);

class Upload
{
	public $savedPath = '';
	private $allowType = null;
	private $allowSize = 0;
	private $fileType = '';
	private $originName = '';
	private $ext = '';
	private $fileSize = 0;
	private $newFileName = '';
	private $errno = 0;
	private $error = '';
	private $cfg = null;

	public function __construct()
	{

	}

	public function set($option = array())
	{
		$attrs = get_class_vars(get_class($this));
		foreach ($option as $k => $v) {
			if(in_array($k, $attrs))
			{
				$this->$k = $v;
			}
		}
	}

	public function upload($file, $newFileName = '')
	{
		if(!array_key_exists($file, $_FILES))
		{
			$this->errno = 9;
			return false;
		}

		$f = $_FILES[$file];

		$this->originName = $f['name'];

		if($f['error'])
		{
			$this->errno = $f['error'];
			return false;
		}

		if(	!$this->check_type($f['name'])
			|| !$this->check_size($f['size'])
			|| !$this->check_path()
		)
		{
			return false;
		}

		$this->newFileName = $newFileName | $this->generate_rand_name($f);

		if(!is_uploaded_file($f['tmp_name']))
		{
			$this->errno = 14;
			return false;
		}

		$newFile = $this->savedPath . $this->newFileName;
		if(!file_exists($newFile))
		{
			if(!move_uploaded_file($f['tmp_name'], $newFile))
			{
				$this->errno = 15;
				return false;
			}
		}

		return $this->newFileName;
	}

	private function generate_rand_name($f)
	{
		return md5_file($f['tmp_name']) . '.' . $this->ext;
	}

	private function get_ext($file)
	{
		return strtolower(pathinfo($file, PATHINFO_EXTENSION));
	}

	private function get_cfg($key)
	{
		if($this->$key)
		{
			return $this->$key;
		}

		if($this->cfg)
		{
			return $this->cfg[$key];
		}
		else
		{
			$CFG = &load_class('config', 'core', 'M_');
			$this->cfg = $CFG->get_config('upload');
			return $this->cfg[$key];
		}
	}

	private function check_type($name)
	{
		$this->ext = $this->get_ext($name);
		$this->allowType = $this->get_cfg('allowType');
		if(!in_array($this->ext, $this->allowType))
		{
			$this->errno = 10;
			return false;
		}

		return true;
	}

	private function check_size($size)
	{
		$this->allowSize = $this->get_cfg('allowSize');
		if($size > $this->allowSize)
		{
			$this->errno = 11;
			return false;
		}

		return true;
	}

	private function check_path()
	{
		$this->savedPath = $this->get_cfg('savedPath');
		if(!file_exists($this->savedPath))
		{
			$this->errno = 12;
			return false;
		}
		elseif(!is_writable($this->savedPath))
		{
			$this->errno = 13;
			return false;
		}

		return true;
	}

	public function get_error()
	{
		if(0 == $this->errno)
		{
			return 'No error occured.';
		}
		$str = 'File ' . $this->originName . ' upload error: ';
		switch($this->errno)
		{
			case UPLOAD_ERR_INI_SIZE: // 1
				$str .= 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case UPLOAD_ERR_FORM_SIZE: // 2
				$str .= 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case UPLOAD_ERR_PARTIAL: // 3
				$str .= 'The uploaded file was only partially uploaded';
				break;
			case UPLOAD_ERR_NO_FILE: // 4
				$str .= 'No file was uploaded';
				break;
			case UPLOAD_ERR_NO_TMP_DIR: // 6
				$str .= 'Missing a temporary folder';
				break;
			case UPLOAD_ERR_CANT_WRITE: // 7
				$str .= 'Failed to write file to disk';
				break;
			case UPLOAD_ERR_EXTENSION: // 8
				$str .= 'File upload stopped by extension';
				break;
			case 9:
				$str .= 'FileField not exists in $_FILES';
				break;
			case 10:
				$str .= 'File type not allowed';
				break;
			case 11:
				$str .= 'Too large filesize';
				break;
			case 12:
				$str .= 'The save path ' . $this->savedPath . ' not exists';
				break;
			case 13:
				$str .= 'The save path ' . $this->savedPath . ' is not writable';
				break;
			case 14:
				$str .= 'The uploaded file may cause a secure problem';
				break;
			case 15:
				$str .= 'Move_uploaded_file error';
			default :
				$str .= 'Unknown error';
		}

		return $str;
	}
}