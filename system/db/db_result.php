<?php if(!defined('MPHP')) die(FORBIDDEN);

abstract class DB_Result extends DB_Action
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function generate_result($stmt)
	{
		$result = new stdClass();
		$stmt->store_result();

		$result->affected_rows = $stmt->affected_rows;
		$result->insert_id = $stmt->insert_id;
		$result->num_rows = $stmt->num_rows;
		$result->fetch_array = array();

		if($stmt->field_count)
		{
			$re = $stmt->result_metadata();
			while($row = $re->fetch_field())
			{
				$arr[$row->name] = &$arr[$row->name];
			}

			call_user_func_array(array($stmt, 'bind_result'), $arr);

			while($stmt->fetch())
			{
				$tmp_arr = array();
				foreach ($arr as $k => &$v) {
					$tmp_arr[$k] = $v;
				}
				$result->fetch_array[] = $tmp_arr;
			}
		}

		$this->clear();		
		return $result;
	}
}