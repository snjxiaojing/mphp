<?php if(!defined('MPHP')) die(FORBIDDEN);




function str_find($str, $search = ' ')
{
	return (false !== strpos($str, $search));
}


function str_clean($str, $url_encoded = false)
{
	$danger_char = array();

	if ($url_encoded)
	{
		$danger_char[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
		$danger_char[] = '/%1[0-9a-f]/';	// url encoded 16-31
	}

	$danger_char[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

	do
	{
		$str = preg_replace($danger_char, '', $str, -1, $count);
	}
	while ($count);

	if(get_magic_quotes_gpc())
	{
		$str = stripslashes($str);
	}

	return $str;
}

function str_to_utf8($str, $encoding)
{
	if (function_exists('iconv'))
	{
		$str = @iconv($encoding, 'UTF-8', $str);
	}
	elseif (function_exists('mb_convert_encoding'))
	{
		$str = @mb_convert_encoding($str, 'UTF-8', $encoding);
	}
	else
	{
		return FALSE;
	}

	return $str;
}

function str_utf8_substr($str,$len)
{
	for($i=0;$i<$len;$i++)
	{
		$temp_str=substr($str,0,1);
		if(ord($temp_str) > 127)
		{
			$j = $i;
			$j++;
			if($j<$len)
			{
				$new_str[]=substr($str,0,3);
				$str=substr($str,3);
			}
		}
		else
		{
			$new_str[]=substr($str,0,1);
			$str=substr($str,1);
		}
	}
	return implode('', $new_str);
}

function str_ltrim($str, $chars)
{
	if(strlen($chars) > strlen($str))
	{
		return $str;
	}
	elseif($str === $chars)
	{
		return '';
	}

	if(0 === strpos($str, $chars))
	{
		$str = substr($str, strlen($chars));
	}
	return $str;
}

function str_rtrim($str, $chars)
{
	if(strlen($chars) > strlen($str))
	{
		return $str;
	}
	elseif($str === $chars)
	{
		return '';
	}
	if(strrpos($str, $chars, strlen($str) - strlen($chars)))
	{
		$str = substr($str, 0, strlen($str) - strlen($chars));
	}
	return $str;
}

function str_trim($str, $chars)
{
	if(strlen($chars) > strlen($str))
	{
		return $str;
	}
	elseif($str === $chars)
	{
		return '';
	}
	return str_ltrim(str_rtrim($str, $chars), $chars);
}

/*EOF*/