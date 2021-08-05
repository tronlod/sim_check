<?php

class Spider
{
	public $BaseUrl 			= '';
	public $Cookie				= '';
    public $UserAgent			= 'FireFox';
	public $Referer				= '';
	public $Result				= '';
	public $Proxy				= '';
	public $Timeout				= 180;
	public $Errnum				= 0;
	public $ProxyUserPass		= '';
	public $Ranges				= '';
	public $MaxRedirs			= 5;
	public $Headers				= false;
	public $FollowLocation		= true;
	public $ReturnTransfer		= true;
	public $NoBody				= false;
	public $DriverUrl			= '';
	public $DriverPREGBlock		= '';
	public $Url					= '';
	public $AddHeaders			= array();
	public $ProxyList			= array();
	public $ProxyNum			= 0;
	
	public $UseCookieFile 		= false;
	public $UseProxyList		= false;

	public $Fields 				= '';
	public $Method				= 'GET';
	//public $Enctype				= 'multipart/form-data';
	public $Enctype			= 'application/x-www-form-urlencoded';

	public $Parser				= '';

	protected $curl_handler		= '';
	protected $action			= '';
	protected $tmp_url			= '';
	protected $tmp_method		= '';
	protected $driver_conf		= '';

	function Spider()
	{
		if (!function_exists("curl_init")) {
			trigger_error("PHP was not built with '--with-curl'", E_USER_ERROR);
		} else {
			$this->curl_handler = curl_init();
		}
	}

	public function __destruct() {
		$this->close();
	}

	public function close() {
		curl_close($this->curl_handler);
	}

	public function open() {
		$this->curl_handler = curl_init();
	}

	public function RestartConnection () {
		$this->close();
		$this->open();
	}

	public function GetCURLHandler() {
		$this->set_options();
		return $this->curl_handler;
	}

	public function set_options () {
		// Кукисы
		if($this->UseCookieFile) {
			if(is_writable($this->Cookie)) {
				curl_setopt($this->curl_handler, CURLOPT_COOKIEFILE, $this->Cookie);
				curl_setopt($this->curl_handler, CURLOPT_COOKIEJAR, $this->Cookie);
			} else {
				die('Файл не является записываемым: '.$this->Cookie);
			}
		} else {
			if (!empty($this->Cookie))
			{
				curl_setopt($this->curl_handler, CURLOPT_COOKIE, $this->Cookie);
			}
		}

		// Реферер
		if (!empty($this->Referer)) {
			curl_setopt($this->curl_handler, CURLOPT_REFERER, $this->Referer);
		} else {
			curl_setopt($this->curl_handler, CURLOPT_REFERER, $this->BaseUrl);
		}

		// Загрузка участков
		if (!empty($this->Ranges)) {
			curl_setopt($this->curl_handler, CURLOPT_RANGE, $this->Ranges);
		}

		// Метод запроса
		if (strtoupper(trim($this->Method)) == 'GET')
		{
			$action = $this->Url;
			if ($this->Fields != "") {
				$action .= "?".$this->Fields;
			}
			curl_setopt($this->curl_handler, CURLOPT_URL, $action);
		}

		if(strtoupper(trim($this->Method)) == 'POST') {
			$fields = $this->Fields;

			if (stristr($this->Enctype, 'multipart')) {
				$fields=parse_str($fields, $fields);
			} else {
				$fields = $this->Fields;
			}
			curl_setopt($this->curl_handler, CURLOPT_URL, $this->Url);
			curl_setopt($this->curl_handler, CURLOPT_POST, true);
			curl_setopt($this->curl_handler, CURLOPT_POSTFIELDS, $fields);
		}

		// Другие параметры
		curl_setopt($this->curl_handler, CURLOPT_HEADER, $this->Headers);
		curl_setopt($this->curl_handler, CURLOPT_FOLLOWLOCATION, $this->FollowLocation);
		curl_setopt($this->curl_handler, CURLOPT_RETURNTRANSFER, $this->ReturnTransfer);
		if(is_array($this->UserAgent)) {
			curl_setopt($this->curl_handler, CURLOPT_USERAGENT, array_rand($this->UserAgent));
		} else {
			curl_setopt($this->curl_handler, CURLOPT_USERAGENT, $this->UserAgent);
		}
		curl_setopt($this->curl_handler, CURLOPT_TIMEOUT, $this->Timeout);
		curl_setopt($this->curl_handler, CURLOPT_MAXREDIRS, $this->MaxRedirs);
		curl_setopt($this->curl_handler, CURLOPT_NOBODY, $this->NoBody);
		curl_setopt($this->curl_handler, CURLOPT_SSL_VERIFYPEER, 0);
		if (is_array($this->AddHeaders)) {
			curl_setopt($this->curl_handler, CURLOPT_HTTPHEADER, $this->AddHeaders);
		}
		if(!empty($this->Proxy)) {
			curl_setopt($this->curl_handler, CURLOPT_PROXY, $this->Proxy);
			curl_setopt($this->curl_handler, CURLOPT_PROXYUSERPWD, $this->ProxyUserPass);
		}
	}

	function GetContent()
	{
		$this->Headers = false;
		$this->NoBody = false;
		$this->set_options();
		$this->Result = curl_exec($this->curl_handler);
		$this->Errnum = curl_errno($this->curl_handler);

		if($this->Errnum != "0") return curl_error($this->curl_handler);
		else return true;
	}

	function GetHeaders () {
		$this->Headers = true;
		$this->NoBody = true;
		$this->set_options();
		$this->Result= curl_exec($this->curl_handler);
		$this->Errnum = curl_errno($this->curl_handler);

		if($this->Errnum != "0") return false;
		else return true;
	}

	function ResultStripWhite() {
		$this->Result=str_replace(array("\n", "\r", "\t"), '', $this->Result);
	}

	function ResultStripWhite_D() {
		$this->Result=str_replace("\n", "<ZZZ>", $this->Result);
		$this->Result=str_replace("\t", "<BBB>", $this->Result);
	}

	function GetLastURL() {
		return curl_getinfo($this->curl_handler, CURLINFO_EFFECTIVE_URL);
	}
}

?>