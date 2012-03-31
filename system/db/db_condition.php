<?php if(!defined('MPHP')) die(FORBIDDEN);

abstract class DB_Condition extends DB_Main
{
	protected $p; // pieces

	public function __construct()
	{
		parent::__construct();
	}

	public function select($select = '*')
	{
		$p = &$this->p;

		if('*' == $p['select'][0])
		{
			array_shift($p['select']);
		}

		if(is_string($select))
		{
			if(str_find($select, '*'))
			{
				$p['select'] = array('*');
				return $this;
			}
			else
			{
				$select = explode(',', $select);
			}
		}

		foreach ($select as $v)
		{
			$v = trim($v);
			if('*' == $v)
			{
				$p['select'] = array('*');
				return $this;
			}
			else
			{
				$v = $this->parse_as($v);
				array_push($p['select'], $v);
			}
		}

		return $this;
	}

	public function select_max()
	{
		
	}

	public function select_min()
	{
		
	}

	public function select_avg()
	{
		
	}

	public function select_sum()
	{
		
	}

	public function from($tabname)
	{
		$p = &$this->p;
		$p['tabname'] = $this->parse_as($tabname, true);
		return $this;
	}

	public function join($tabname, $method)
	{
		$p = &$this->p;
		$join = array('tabname' => $this->parse_as($tabname, true));

		$tmp_arr = explode('=', $method);

		foreach ($tmp_arr as $k=>$v)
		{
			$tmp_arr2 = explode('.', $v);
			foreach ($tmp_arr2 as $kk => $vv)
			{
				$s = trim(trim($vv), '`');
				$tmp_arr2[$kk] = "`$s`";
			}
			$tmp_arr[$k] = implode('.', $tmp_arr2);
		}

		$join['method'] = implode('=', $tmp_arr);
		$p['join'][] = $join;
		return $this;
	}

	protected function parse_as($str, $is_tabname = false)
	{
		$str = trim($str);
		if(str_find($str, ' as '))
		{
			$tmp_arr = explode('as', $str);
			$s = trim(trim($tmp_arr[0]), '`');
			$d = trim($tmp_arr[1]);
			if($is_tabname)
			{
				$str = "`$this->tp$s` AS `$d`";
			}
			else
			{
				$str = "`$s` AS `$d`";
			}
		}
		elseif(str_find($str, ' '))
		{
			$tmp_arr = explode(' ', $str);
			$s = trim(trim($tmp_arr[0]), '`');
			$d = trim($tmp_arr[count($tmp_arr) - 1]);
			if($is_tabname)
			{
				$str = "`$this->tp$s` AS `$d`";
			}
			else
			{
				$str = "`$s` AS `$d`";
			}
		}
		else
		{
			$str = trim(trim($str), '`');
			if($is_tabname)
			{
				$str = "`$this->tp$str`";
			}
			else
			{
				$str = "`$str`";
			}
		}
		$str = str_replace('.', '`.`', $str);
		$str = str_replace('``', '`', $str);
		return $str;
	}

	public function where($key, $value = null)
	{
		return $this->_where($key, $value);
	}

	public function or_where($key, $value = null)
	{
		return $this->_where($key, $value, true, false);
	}

	public function group_by($key)
	{
		$p = &$this->p;
		if(is_string($key))
		{
			$key = explode(',', $key);
		}

		foreach ($key as $v) {
			$p['group'][] = $this->parse_as($v);
		}

		return $this;
	
	}

	public function having($key, $value = null)
	{
		return $this->_where($key, $value, false, true);
	}

	public function or_having($key, $value = null)
	{
		return $this->_where($key, $value, true, true);
	}

	public function where_in($col, $set)
	{
		$str = $this->_pro_where_in($col, $set);
		return $this->_where($str, null);
	}

	public function or_where_in($col, $set)
	{
		$str = $this->_pro_where_in($col, $set);
		return $this->_where($str, null, true);
	}

	public function where_not_in($col, $set)
	{
		$str = $this->_pro_where_in($col, $set, true);
		return $this->_where($str, null);
	}

	public function or_where_not_in($col, $set)
	{
		$str = $this->_pro_where_in($col, $set, true);
		return $this->_where($str, null, true);
	}

	private function _pro_where_in($col, $set, $not = false)
	{
		$str = '';
		$str .= $this->parse_as($col);
		if($not)
		{
			$str .= ' NOT';
		}
		$str .= ' IN (';
		foreach ($set as $v) {
			$str .= "'$v',";
		}
		$str = rtrim($str, ',');
		$str .= ')';
		return $str;
	}

	public function like($key, $value, $left = true, $right = true)
	{
		$value = $this->_pro_like($value, $left, $right);
		return $this->_where($key . ' LIKE ' . $value, null);
	}

	public function or_like($key, $value, $left = true, $right = true)
	{
		$value = $this->_pro_like($value, $left, $right);
		return $this->_where($key . ' LIKE ' . $value, null, true);
	}

	public function not_like($key, $value, $left = true, $right = true)
	{
		$value = $this->_pro_like($value, $left, $right);
		return $this->_where($key . ' NOT LIKE ' . $value, null);
	}

	public function or_not_like($key, $value, $left = true, $right = true)
	{
		$value = $this->_pro_like($value, $left, $right);
		return $this->_where($key . ' NOT LIKE ' . $value, null, true);
	}

	private function _pro_like($value, $left, $right)
	{
		if($left)
		{
			$value = '%' . $value;
		}
		if($right)
		{
			$value .= '%';
		}
		$value = "'" . str_replace("'", "\'", $value) . "'";
		return $value;
	}

	private function _where($key, $value, $or = false, $having = false)
	{
		$p = &$this->p;

		if(!$or)
		{
			$str = $p['where'][0];
		}
		else
		{
			$str = '';
		}

		if(is_string($key))
		{
			if(!is_null($value))
			{
				if(!is_numeric($value))
				{
					$value = "'" . str_replace("'", "\'", $value) . "'";
				}
				$key = $this->_pro_where_str($key) . $value;
			}
			$str .= trim($key) . ' AND ';
		}
		elseif(is_array($key))
		{
			foreach($key as $k=>$v)
			{
				if(!is_numeric($v))
				{
					$v = "'" . str_replace("'", "\'", $v) . "'";
				}
				$str .= $this->_pro_where_str($k) . "$v AND ";
			}
		}

		if($or)
		{
			$str = rtrim($str, ' AND ');
		}

		if($having)
		{
			$arr = &$p['having'];
		}
		else
		{
			$arr = &$p['where'];
		}

		if($or)
		{
			array_push($arr, $str);
		}
		else
		{
			$arr[0] = $str;
		}
		
		return $this;
	}

	private function _pro_where_str($k)
	{
		$k = trim($k);
		if($this->_has_operater($k))
		{
			$tmp_arr = explode(' ', $k);
			$str = "`$tmp_arr[0]`{$tmp_arr[count($tmp_arr) - 1]}";
		}
		else
		{
			$k = $this->parse_as($k);
			$str = "$k=";
		}

		return $str;
	}

	private function _has_operater($k)
	{
		return (1 === preg_match("/(\s|<|>|!|=)/i", trim($k)));
	}

	public function order_by($col, $order = 'ASC')
	{
		$p = &$this->p;
		$order = strtoupper($order);

		if(is_array($col))
		{
			foreach ($col as $v)
			{
				$v = $this->parse_as($v);
				$p['order'][$v] = $order;
			}
		}
		else
		{
			$col = $this->parse_as($col);
			$p['order'][$col] = $order;
		}

		return $this;
	}

	public function limit($limit, $start = 0)
	{
		$p = &$this->p;

		$p['limit'] = "LIMIT $limit";
		if($start > 0)
		{
			$p['limit'] .= ",$start";
		}

		return $this;
	}
}

/*EOF*/