<?

error_reporting( E_ERROR );
header ("Content-Type: text/html; charset=utf-8");
include("config.php");

require_once dirname(__FILE__).'/dir.php';
require_once BASE_DIR.'/inc/db.class.php';
require_once BASE_DIR.'/inc/pregm.php';
require_once BASE_DIR.'/inc/spider.class.php';

/* Удаляем старые результаты */

mysql_query("truncate table test_result");
mysql_query("update sim_old set chk=0");

/*

Проверка:
1 - Несколько разных номеров в диапазоне
2 - Нет авторизаций
3 - Одна авторизация

*/

$blocks_find_tr ='\<tr[^\>]*>(.*)\<\/tr\>';
$blocks_find_td ='\<td[^\>]*>(.*)\<\/td\>';

/* Глубина поиска */
$days = "-1 days";

$sim_list = mysql_query("select * from sim_old where chk=0");
while ($mylist = mysql_fetch_array($sim_list)) 
{
	print "Login: $mylist[login]<BR>";	
	$data_end = date("d.m.Y");
	$data_start = date('d.m.Y', strtotime($days));
	$f_name = "https://freemanager.sats.spb.ru/ajax/logsearch.php?datestart=$data_start&dateend=$data_end&username=$mylist[login]&phone=";
	
	//$f_name = "https://freemanager.sats.spb.ru/ajax/logsearch.php?datestart=$data_start&dateend=$data_end&username=test&phone=";
	
	print "$f_name<BR>";
	//exit;
	$file = file_get_contents($f_name, true);
	
	$file=str_replace(array("\n", "\r", "\t"), '',$file);
	// print "$file<BR>" ;//Added 19.07.21 test Kalsin
	
	if ($blocks_tr=pregma($blocks_find_tr, $file)) 
	{	
		$count = 0;
		$numbers = Array();
		foreach ($blocks_tr as $block_tr) 
		{
			if($count > 0)
			{
				$find_number = 0;
				$blocks_td = pregma($blocks_find_td, $block_tr);
				foreach ($blocks_td as $block_td) 
				{	
					//print "$block_td<BR>"; //Uncomment Kalsin 19.07.21
					if($find_number == 2)
						array_push($numbers, $block_td);  
					$find_number++;
				}			
			}
			$count++;
		}
		$result_sql = "";
		$result_sql_1 = "";
		$result = array_unique($numbers);
		foreach ($result as &$value) {
			print "Вот что нашлось ----- > $value<BR>";
			$result_sql .= "$value;";
			//print "Вот что запишем ----- > $result_sql<BR>";
			$result_sql_1 = "$value";
			//print "Вот что нашлось ----- > $result_sql<BR>";
		}
		$count = count($result);
		print "Всего: $count<BR>";
		if($count == 0)
		{
			mysql_query("insert into test_result set id_sim ='".$mylist[id]."', result='', chk=2, tip='".$mylist[tip]."'");
		}
		if($count == 1)
		{
			//добавил запись результат в любом случае Кальсин С.А.
			mysql_query("insert into test_result set id_sim ='".$mylist[id]."', result='".$result_sql_1."', chk=3, tip='".$mylist[tip]."'");
		//	$f_mysql="insert into test_result set id_sim ='".$mylist[id]."', result='".$result_sql_1."', chk=3, tip='".$mylist[tip]."'";
		//	print "$f_mysql<BR>";
		}
		if($count > 1)
		{
			mysql_query("insert into test_result set id_sim ='".$mylist[id]."', result='".$result_sql."', chk=1, tip='".$mylist[tip]."'");
		}
	}
	else
	{
		
		//mysql_query("insert into test_result set id_sim ='".$mylist[id]."', result='".$result_sql_1."', chk=2, tip='".$mylist[tip]."'");
		mysql_query("insert into test_result set id_sim ='".$mylist[id]."', result='', chk=2, tip='".$mylist[tip]."'");
	}
	
	mysql_query("update sim_old set chk=1 where id=$mylist[id]");
	//sleep(3);
}	
		
$blocks_find_tr ='\<tr[^\>]*>(.*)\<\/tr\>';
$blocks_find_td ='\<td[^\>]*>(.*)\<\/td\>';

$sim_list = mysql_query("select * from sim_old");
while ($mylist = mysql_fetch_array($sim_list)) 
{
	print "Login: $mylist[login]<BR>";	
	$data_end = date("d.m.Y");
	$data_start = date('d.m.Y', strtotime($days));
	$f_name = "https://freemanager.sats.spb.ru/ajax/logsearch.php?datestart=$data_start&dateend=$data_end&phone=$mylist[number]";
	
	//$f_name = "https://freemanager.sats.spb.ru/ajax/logsearch.php?datestart=$data_start&dateend=$data_end&username=dodd0000&phone=";
	
	print "$f_name<BR>";
	//exit;
	$file = file_get_contents($f_name, true);
	
	$file=str_replace(array("\n", "\r", "\t"), '',$file);
	
	
	if ($blocks_tr=pregma($blocks_find_tr, $file)) 
	{	
		$count = 0;
		$numbers = Array();
		foreach ($blocks_tr as $block_tr) 
		{
			if($count > 0)
			{
				$find_number = 0;
				$blocks_td = pregma($blocks_find_td, $block_tr);
				foreach ($blocks_td as $block_td) 
				{	
					//print "$block_td<BR>";
					if($find_number == 2)
						array_push($numbers, $block_td);  
					$find_number++;
				}			
			}
			$count++;
		}
		$result_sql = "";
		$result = array_unique($numbers);
		foreach ($result as &$value) {
			print "$value<BR>";
			$result_sql .= "$value;";
		}
		$count = count($result);
		print "Всего: $count<BR>";
		if($count == 0)
		{
			mysql_query("insert into test_result set id_sim ='".$mylist[id]."', result='', chk=3, tip='".$mylist[tip]."'");
		}
		if($count > 1)
		{
			//mysql_query("insert into test_result set id_sim ='".$mylist[id]."', result='".$result_sql."', chk=1, tip='".$mylist[tip]."'");
		}
	}
	else
	{
		mysql_query("insert into test_result set id_sim ='".$mylist[id]."', result='', chk=3, tip='".$mylist[tip]."'");
	}
	
	mysql_query("update sim_old set chk=1 where id=$mylist[id]");
	//sleep(3);
}	
		
exit;		
$f_name = "test_diff.txt";
$file = file_get_contents($f_name, true);

$file=str_replace(array("\n", "\r", "\t"), '',$file);


?>