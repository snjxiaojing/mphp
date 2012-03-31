<?php if(!defined('MPHP')) die(FORBIDDEN);

abstract class DB_Action extends DB_Condition
{
	public $last_query = '';

	public function __construct()
	{
		parent::__construct();
	}

	private function generate_select()
	{
		$select = $this->p['select'];
		$str = '';
		if('*' == $select[0])
		{
			$str = '*';
		}
		else
		{
			foreach ($select as $v) {
				$str .= "$v,";
			}
			$str = rtrim($str, ',');
		}
		return $str;
	}

	private function generate_join()
	{
		$str = '';
		$join = $this->p['join'];
		foreach ($join as $v) {
			$str .= " JOIN {$v['tabname']} ON {$v['method']}";
		}

		return $str;
	}

	private function generate_having()
	{
		return $this->generate_where(true);
	}

	private function generate_where($is_having = false)
	{
		$str = '';
		if($is_having)
		{
			$arr = $this->p['having'];
			$str = ' HAVING';
		}
		else
		{
			$arr = $this->p['where'];
			$str = ' WHERE';
		}

		if('' != $arr[0])
		{
			if(count($arr) == 1)
			{
				$str .= ' ' . rtrim($arr[0], 'AND ');
			}
			else
			{
				foreach ($arr as $v) {
					$str .= " (" . rtrim($v, 'AND ') . ") OR";
				}
				$str = rtrim($str, 'OR');
			}
		}
		else
		{
			$str = '';
		}

		return $str;
	}

	private function generate_order()
	{
		$str = '';
		$order = $this->p['order'];
		if(count($order))
		{
			$str = ' ORDER BY ';
			foreach ($order as $k => $v) {
				$str .= $k . ' ' . $v . ",";
			}
			$str = rtrim($str, ',');
		}

		return $str;
	}

	private function generate_limit()
	{
		return ' ' . $this->p['limit'];
	}

	private function generate_stmt($sql, $param = array())
	{
		$this->last_query = $sql;
		$stmt = $this->o->prepare($sql);
		$this->check_error($this->o, $sql);
		if(count($param))
		{
			call_user_func_array(array($stmt,'bind_param'), $param);
			$this->check_error($stmt, $sql);
		}
		$stmt->execute();
		$this->check_error($stmt, $sql);

		global $sql_num;
		$sql_num++;

		return $stmt;
	}

	private function check_error($o, $sql)
	{
		if($o->errno)
		{
			show_error(E_ERROR, $o->error . '<br>SQL:' . $sql);
		}
	}

	public function get($table, $limit = 0, $start = 0)
	{
		if(0 !== $limit*$start)
		{
			$this->limit($limit, $start);
		}

		$table = $this->parse_as($table, true);
		$sql = 'SELECT '
				. $this->generate_select()
				. " FROM $table"
				. $this->generate_join()
				. $this->generate_where()
				. $this->generate_having()
				. $this->generate_order()
				. $this->generate_limit();

		$stmt = $this->generate_stmt($sql);
		return $this->generate_result($stmt);
	}

	public function get_where($table,$where)
	{
		$this->where($where);
		return $this->get($table);
	}

	public function insert($table, $data)
	{
		return $this->_insert($table, $data, 'INSERT');
	}

	private function _insert($table, $data, $type)
	{
		$table = $this->parse_as($table, true);
		$sql = $type . " INTO $table(";
		$param = array('');
		foreach ($data as $k => &$v) {
			$sql .= "`$k`,";
			$param[] = &$v;
		}
		$param[0] = str_repeat('s', count($data));
		$sql = rtrim($sql, ',') . ')';
		$sql .= ' VALUES(' . str_repeat('?,', count($data));
		$sql = rtrim($sql, ',') . ')';

		$stmt = $this->generate_stmt($sql, $param);
		return $this->generate_result($stmt);
	}

	public function replace_insert($table, $data)
	{
		return $this->_insert($table, $data, 'REPLACE');
	}

	public function ignore_insert($table, $data)
	{
		return $this->_insert($table, $data, 'INSERT IGNORE');
	}

	public function replace_multi_insert($table, $data)
	{
		return $this->_multi_insert($table, $data, 'REPLACE');
	}

	public function ignore_multi_insert($table, $data)
	{
		return $this->_multi_insert($table, $data, 'INSERT IGNORE');
	}

	public function multi_insert($table, $data)
	{
		return $this->_multi_insert($table, $data, 'INSERT');
	}

	private function _multi_insert($table, $data, $type)
	{
		$table = $this->parse_as($table, true);
		$sql = $type . " INTO $table(";
		foreach($data[0] as $k=>$v)
		{
			$sql .= "`$k`,";
		}
		$sql = rtrim($sql, ',') . ')';
		$sql .= ' VALUES(' . str_repeat('?,', count($data[0]));
		$sql = rtrim($sql, ',') . ')';

		$this->last_query = $sql;
		$stmt = $this->o->prepare($sql);
		$this->check_error($this->o, $sql);
		foreach($data as $v)
		{
			$param = array(0=>'');
			foreach($v as &$vv)
			{
				$param[] = &$vv;
			}
			$param[0] = str_repeat('s', count($data[0]));
			call_user_func_array(array($stmt, 'bind_param'), $param);
			$this->check_error($stmt, $sql);
			$stmt->execute();
			$this->check_error($stmt, $sql);
		}

		global $sql_num;
		$sql_num++;

		$this->generate_result($stmt);
	}

	public function delete($table)
	{
		$table = $this->parse_as($table, true);

		$sql = "DELETE FROM $table"
				. $this->generate_where();

		$stmt = $this->generate_stmt($sql);
		return $this->generate_result($stmt);
	}

	public function delete_where($table, $where)
	{
		$this->where($where);
		return $this->delete($table);
	}

	public function update($table, $data, $where = array())
	{
		if(count($where))
		{
			$this->where($where);
		}
		$table = $this->parse_as($table, true);
		$sql = "UPDATE $table SET ";
		$param = array(0=>'');
		$type = '';
		foreach($data as $k=>&$v)
		{
			$sql .= $this->parse_as($k) . "=?,";
			$param[] = &$v;
			if(is_numeric($v))
			{
				$type .= 'd';
			}
			else
			{
				$type .= 's';
			}
		}
		$param[0] = $type;
		$sql = rtrim($sql, ',');
		$sql .= $this->generate_where();

		$stmt = $this->generate_stmt($sql, $param);
		return $this->generate_result($stmt);
	}

	public function count($table, $where = array())
	{
		$table = $this->parse_as($table, true);
		$sql = "SELECT COUNT(*) AS n FROM $table";
		if(count($where))
		{
			$this->where($where);
		}
		$sql .= $this->generate_where();

		$stmt = $this->generate_stmt($sql);

		$stmt->bind_result($count);
		$stmt->fetch();
		$this->clear();
		return $count;
	}

	public function query($sql, $data = array())
	{
		if(count($data))
		{
			$param[0] = str_repeat('s', count($data));
			foreach ($data as &$v) {
				$param[] = &$v;
			}
		}
		else
		{
			$param = array();
		}
		$stmt = $this->generate_stmt($sql, $param);
		return $this->generate_result($stmt);
	}

	public function incre($table, $key, $i = 1)
	{
		$table = $this->parse_as($table, true);
		$sql = "UPDATE $table SET ";
		$key = '`' . str_replace('`', '', trim($key)) . '`';
		$sql .= "$key=$key+$i";
		$sql .= $this->generate_where();

		$stmt = $this->generate_stmt($sql);
		return $this->generate_result($stmt);
	}

	public function empty_table($table)
	{
		$table = $this->parse_as($table);
		$sql = "DELETE FROM $table";

		$stmt = $this->generate_stmt($sql);
		return $this->generate_result($stmt);
	}

	public function truncate($table)
	{
		$table = $this->parse_as($table);
		$sql = "TRUNCATE TABLE FROM $table";

		$stmt = $this->generate_stmt($sql);
		return $this->generate_result($stmt);
	}
}