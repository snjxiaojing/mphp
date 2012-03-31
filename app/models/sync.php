<?php if(!defined('MPHP')) die(FORBIDDEN);

class Sync extends M_Model
{
	private $qq_appid = 0000;
	private $qq_scope = 'get_user_info,add_t,add_topic';
	private $qq_callback = 'http://isharp.me/admin/sync_callback';
	private $qq_appkey = '0000';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_status()
	{

		return 'sss';
	}

	public function qq_login_url()
	{
		$state = md5(uniqid(rand(), TRUE));

		$login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
		. $this->qq_appid . "&redirect_uri=" . urlencode($this->qq_callback)
		. "&state=" . $state
		. "&scope=".$this->qq_scope;
		return $login_url;
	}

	public function pro_qq($get)
	{
		// token
		$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
			. "client_id=" . $this->qq_appid. "&redirect_uri=" . urlencode($this->qq_callback)
			. "&client_secret=" . $this->qq_appkey. "&code=" . $get["code"];

		$response = $this->do_get($token_url);

		if (strpos($response, "callback") !== false)
		{
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg = json_decode($response);
			if (isset($msg->error))
			{
				echo "<h3>error:</h3>" . $msg->error;
				echo "<h3>msg  :</h3>" . $msg->error_description;
				exit;
			}
		}

		$params = array();
		parse_str($response, $params);

		$this->db->update('cfg', array('v'=>$params['access_token'], 'ctime'=>time(), 'expire'=>(time() + $params['expires_in'])), array('k'=>'qq_token'));

		// open id
		$graph_url = "https://graph.qq.com/oauth2.0/me?access_token="
		. $params['access_token'];

		$str  = $this->do_get($graph_url);
		if (strpos($str, "callback") !== false)
		{
			$lpos = strpos($str, "(");
			$rpos = strrpos($str, ")");
			$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		}

		$user = json_decode($str);
		if (isset($user->error))
		{
			echo "<h3>error:</h3>" . $user->error;
			echo "<h3>msg  :</h3>" . $user->error_description;
			exit;
		}

		$this->db->update('cfg', array('v'=>$user->openid, 'ctime'=>time()), array('k'=>'qq_openid'));

		return true;
	}

	public function qq_add_topic($cnt)
	{
		//get openid
		$re = $this->db->select('k,v')->like('k', 'qq_', false)->get('cfg');
		$re = $re->fetch_array;
		$qq = array();
		foreach($re as $v)
		{
			$qq[$v['k']] = $v['v'];
		}
		// p($qq);

		// Qzone
		$url  = "https://graph.qq.com/shuoshuo/add_topic";

		$data = "access_token=".$qq["qq_token"]
			."&oauth_consumer_key=".$this->qq_appid
			."&openid=".$qq["qq_openid"]
			."&format=json"
			."&con=".urlencode($cnt)
			."&third_source=1";

		$ret = $this->do_post($url, $data);
		echo $ret;

		// Q weibo
		$url  = "https://graph.qq.com/t/add_t";

		$data = "access_token=".$qq["qq_token"]
			."&oauth_consumer_key=".$this->qq_appid
			."&openid=".$qq["qq_openid"]
			."&format=json"
			."&content=".urlencode($cnt);

		echo "\n";
		echo $ret = $this->do_post($url, $data);
	}

	public function pro_sina()
	{

	}

	private function do_post($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		$ret = curl_exec($ch);

		curl_close($ch);
		return $ret;
	}

	private function do_get($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$result =  curl_exec($ch);
		curl_close($ch);

		return $result;
	}
}

/*EOF*/