<?php 

// DB abstraction by Ugin Nekoz
class DB
{
	var $host	 	= '';
	var $user		= '';
	var $password	= '';
	var $database	= '';
	var $persistent	= false;
	var $conn		= null;
	var $result		= false;

	function DB($host,$user,$pass,$db,$persistent=false)
	{
		$this->host=$host;
		$this->user=$user;
		$this->password=$pass;
		$this->database=$db;
		$this->persistent=$persistent;
	}

	function open()
	{
		if($this->persistent) {
			$func="mysql_pconnect";
		} else {
			$func="mysql_connect";
		}
		$this->conn=$func($this->host,$this->user,$this->password);
		if(!$this->conn) {
			return false;
		}
		if(!@mysql_select_db($this->database,$this->conn)) {
			return false;
		}

		return true;
	}

	function close()
	{
		return(@mysql_close($this->conn));
	}

	function error()
	{
		return (mysql_error());
	}

	function query($sql_query)
	{
		$this->result=$result=@mysql_query($sql_query,$this->conn);
		return $result;
	}

	function query_first_row($sql_query)
	{
		$this->query($sql_query);
		$row=$this->fetchArray($this->result);
		return $row[0];
	}
	
	function query_array($sql_query)
	{
		$this->query($sql_query);
		while($row=$this->fetchArray()) {
			$arr[]=$row[0];
		}
		return $arr;
	}	

	function affectedRows()
	{
		return(@mysql_affected_rows($this->conn));
	}

	function numRows($result)
	{
		return(@mysql_num_rows($result));
	}

	function fetchObject($result)
	{
		return(@mysql_fetch_object($result,MYSQL_ASSOC));
	}

	function fetchArray($result)
	{
		return(@mysql_fetch_array($result,MYSQL_NUM));
	}

	function fetchAssoc($result)
	{
		return(@mysql_fetch_assoc($result));
	}

	function freeResult($result)
	{
		return(@mysql_free_result($result));
	}

	function lastID()
	{
		return(@mysql_insert_id());
	}        

	function get_host()
	{
		return $this->host;
	}

	function get_db()
	{
		return $this->database;
	}

	function get_user()
	{
		return $this->user;
	}

	function get_hash()
	{
		return md5($this->password);
	}

}

?>