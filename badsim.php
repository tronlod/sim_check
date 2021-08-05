<?php
include("config.php");
header ("Content-Type: text/html; charset=utf-8");
//Блок симок от светофорных объектов
//Получение информации о числе симок с пустой авторизацией
$get_act1_counts = mysql_query("select count(id) as counts from test_result where chk=2 and tip=0");
while ($act1_counts = mysql_fetch_array($get_act1_counts))
{
	$count1 = $act1_counts[counts];
}
//Получение информации о числе симок с множественной авторизацией
$get_act2_counts = mysql_query("select count(id) as counts  from test_result where chk=1 and tip=0");
while ($act2_counts = mysql_fetch_array($get_act2_counts))
{
	$count2 = $act2_counts[counts];
}
//Получение информации о числе телефонных номеров с пустой авторизацией
$get_act3_counts = mysql_query("select count(id) as counts from test_result where chk=3 and tip=0 and result=''");
while ($act3_counts = mysql_fetch_array($get_act3_counts))
{
	$count3 = $act3_counts[counts];
}
//Блок симок от Шаттлов
//Получение информации о числе симок с пустой авторизацией
$get_act4_counts = mysql_query("select count(id) as counts from test_result where chk=2 and tip=1");
//print "$act1_counts[counts] <BR>";
while ($act4_counts = mysql_fetch_array($get_act4_counts))
{
	$count4 = $act4_counts[counts];
}
//Получение информации о числе симок с множественной авторизацией
$get_act5_counts = mysql_query("select count(id) as counts  from test_result where chk=1 and tip=1");
while ($act5_counts = mysql_fetch_array($get_act5_counts))
{
	$count5 = $act5_counts[counts];
}
//Получение информации о числе телефонных номеров с пустой авторизацией
$get_act6_counts = mysql_query("select count(id) as counts from test_result where chk=3 and tip=1 and result=''");
while ($act6_counts = mysql_fetch_array($get_act6_counts))
{
	$count6 = $act6_counts[counts];
}

//Получение информации о числе телефонных номеров с запутанной историей
$get_act7_counts = mysql_query("select count(test_result.id) as counts from sim_old, test_result where sim_old.id=test_result.id_sim and test_result.chk=3 and number<>result and result <>''");
while ($act7_counts = mysql_fetch_array($get_act7_counts))
{
	$count7 = $act7_counts[counts];
}
//Конец подсчетов

$act = $_GET[act];
$act2 = $_GET[act2];
$sub = $_GET[sub];

$tel_num = $_POST[tel_num];
$icc = $_POST[icc];
$info = $_POST[info];

print "<a href=?sub=sim>Симки</a><BR>";
//print "<a href=?sub=auth>Аутентификация</a><BR>";
//print "<a href=?sub=obj>Объекты</a><BR><BR>";

if($sub == "sim")
{
	print "Работа с симками:<BR>
		<table border=0>
		<tr>
		<td>
		Traffic lights
		</td>
		<td>
		Shuttle
		</td>
		</tr>
		<tr>
		<td>
		- <a href=?sub=sim&act=lst1>Логины (пустая авторизация) ($count1)</a><BR>
		- <a href=?sub=sim&act=lst2>Логины (множественная авторизация)($count2) </a><BR>
		- <a href=?sub=sim&act=lst3>Телефонный номер (пустая авторизация) ($count3) </a><BR>
		</td>
		<td>
		- <a href=?sub=sim&act=lst4>Логины (пустая авторизация) ($count4)</a><BR>
		- <a href=?sub=sim&act=lst5>Логины (множественная авторизация) ($count5)</a><BR>
		- <a href=?sub=sim&act=lst6>Телефонный номер (пустая авторизация) ($count6)</a><BR>
		</td>
		<td>
		- <a href=?sub=sim&act=lst7>Логины не на своем месте ($count7)</a><BR>
		</td>
		</tr>
		</table>
		<BR><BR>
		<!-- - <a href=?sub=sim&act=lst2>Пинг (нет пинга)</a><BR> -->
		<HR>
	";
	if($act == lst1 || $act == lst2 || $act == lst3 || $act == lst7)
	{
		print "<font color=green><h1>Traffic lights</h1></font>";
	}
	if($act == lst4 || $act == lst5 || $act == lst6)
	{
		print "<font color=red><h1>Shuttle</h1></font>";
	}	
	
	if($act == lst1)
	{
		print "<h2> Список (пустая авторизация)</h2>";
		print "
			<table border=1>
			<thead>
				<tr><th width=20><b><u>ID</th><th width=100><b><u>Номер</th><th width=100><b><u>Login</th><th width=100><b><u>IP</th><th width=50><b><u>ICC</th><th><b><u>Адрес</th><th><b><u>Инфо</th><th width=20><b><u>Тип симки</th></tr>
			</thead>
		";
		
		$sim_list = mysql_query("select * from test_result where chk=2 and tip=0");
		while ($mylist = mysql_fetch_array($sim_list)) 
		{
			$sim_id = mysql_fetch_array(mysql_query("select * from sim_old where id = '$mylist[id_sim]'"));
			print "<tr><td><b>$mylist[id_sim]</B></td><td>$sim_id[number]</td><td>$sim_id[login]</td><td>$sim_id[ip]</td><td>$sim_id[icc]</td><td>$sim_id[adr]</td><td>$sim_id[info]</td><td>$sim_id[tip]</td></tr>";

		}		
		
		print "</table>";
	}

	if($act == lst2)
	{
		print "<h2> Список (множественная авторизация)</h2>";
		print "
			<table border=1>
			<thead>
				<tr><th width=20><b><u>ID</th><th width=100><b><u>Номер</th><th width=100><b><u>Login</th><th width=100><b><u>IP</th><th width=50><b><u>ICC</th><th><b><u>Адрес</th><th><b><u>Инфо</th><th><b><u>Номера авторизаций</th><th><b><u>Тип симки</th></tr>
			</thead>
		";
		
		$sim_list = mysql_query("select * from test_result where chk=1 and tip=0");
		while ($mylist = mysql_fetch_array($sim_list)) 
		{
			$sim_id = mysql_fetch_array(mysql_query("select * from sim_old where id = '$mylist[id_sim]'"));
			
			$tels = "";
			$tel_nums = explode(";", $mylist[result]);
			foreach($tel_nums as $tel_num) {
				$tels .= "$tel_num<BR>";
			}
			
			print "<tr><td><b>$mylist[id_sim]</B></td><td>$sim_id[number]</td><td>$sim_id[login]</td><td><a href=http://$sim_id[ip] target=_blank>$sim_id[ip]</a></td><td>$sim_id[icc]</td><td>$sim_id[adr]</td><td>$sim_id[info]</td><td>$tels</td><td>$sim_id[tip]</td></tr>";

		}		
		
		print "</table>";
	}
	
	if($act == lst3)
	{
		print "<h2> Список (пустая авторизация)</h2>";
		print "
			<table border=1>
			<thead>
				<tr><th width=20><b><u>ID</th><th width=100><b><u>Номер</th><th width=100><b><u>Login</th><th width=100><b><u>IP</th><th width=50><b><u>ICC</th><th><b><u>Адрес</th><th><b><u>Инфо</th><th width=20><b><u>Тип симки</th></tr>
			</thead>
		";
		
		$sim_list = mysql_query("select * from test_result where chk=3 and tip=0 and result=''");
		while ($mylist = mysql_fetch_array($sim_list)) 
		{
			$sim_id = mysql_fetch_array(mysql_query("select * from sim_old where id = '$mylist[id_sim]'"));
			if($sim_id[number] !=0)
				print "<tr><td><b>$mylist[id_sim]</B></td><td>$sim_id[number]</td><td>$sim_id[login]</td><td>$sim_id[ip]</td><td>$sim_id[icc]</td><td>$sim_id[adr]</td><td>$sim_id[info]</td><td>$sim_id[tip]</td></tr>";

		}		
		
		print "</table>";
	}

	if($act == lst4)
	{
		print "<h2> Список (пустая авторизация)</h2>";
		print "
			<table border=1>
			<thead>
				<tr><th width=20><b><u>ID</th><th width=100><b><u>Номер</th><th width=100><b><u>Login</th><th width=100><b><u>IP</th><th width=50><b><u>ICC</th><th><b><u>Адрес</th><th><b><u>Инфо</th><th width=20><b><u>Тип симки</th></tr>
			</thead>
		";
		
		$sim_list = mysql_query("select * from test_result where chk=2 and tip=1");
		while ($mylist = mysql_fetch_array($sim_list)) 
		{
			$sim_id = mysql_fetch_array(mysql_query("select * from sim_old where id = '$mylist[id_sim]'"));
			print "<tr><td><b>$mylist[id_sim]</B></td><td>$sim_id[number]</td><td>$sim_id[login]</td><td>$sim_id[ip]</td><td>$sim_id[icc]</td><td>$sim_id[adr]</td><td>$sim_id[info]</td><td>$sim_id[tip]</td></tr>";

		}		
		
		print "</table>";
	}

	if($act == lst5)
	{
		print "<h2> Список (множественная авторизация)</h2>";
		print "
			<table border=1>
			<thead>
				<tr><th width=20><b><u>ID</th><th width=100><b><u>Номер</th><th width=100><b><u>Login</th><th width=100><b><u>IP</th><th width=50><b><u>ICC</th><th><b><u>Адрес</th><th><b><u>Инфо</th><th><b><u>Номера авторизаций</th><th><b><u>Тип симки</th></tr>
			</thead>
		";
		
		$sim_list = mysql_query("select * from test_result where chk=1 and tip=1");
		while ($mylist = mysql_fetch_array($sim_list)) 
		{
			$sim_id = mysql_fetch_array(mysql_query("select * from sim_old where id = '$mylist[id_sim]'"));
			
			$tels = "";
			$tel_nums = explode(";", $mylist[result]);
			foreach($tel_nums as $tel_num) {
				$tels .= "$tel_num<BR>";
			}
			
			print "<tr><td><b>$mylist[id_sim]</B></td><td>$sim_id[number]</td><td>$sim_id[login]</td><td><a href=http://$sim_id[ip] target=_blank>$sim_id[ip]</a></td><td>$sim_id[icc]</td><td>$sim_id[adr]</td><td>$sim_id[info]</td><td>$tels</td><td>$sim_id[tip]</td></tr>";

		}		
		
		print "</table>";
	}
	
	if($act == lst6)
	{
		print "<h2> Список (пустая авторизация)</h2>";
		print "
			<table border=1>
			<thead>
				<tr><th width=20><b><u>ID</th><th width=100><b><u>Номер</th><th width=100><b><u>Login</th><th width=100><b><u>IP</th><th width=50><b><u>ICC</th><th><b><u>Адрес</th><th><b><u>Инфо</th><th width=20><b><u>Тип симки</th></tr>
			</thead>
		";
		
		$sim_list = mysql_query("select * from test_result where chk=3 and tip=1 and result=''");
		while ($mylist = mysql_fetch_array($sim_list)) 
		{
			$sim_id = mysql_fetch_array(mysql_query("select * from sim_old where id = '$mylist[id_sim]'"));
			if($sim_id[number] !=0)
				print "<tr><td><b>$mylist[id_sim]</B></td><td>$sim_id[number]</td><td>$sim_id[login]</td><td>$sim_id[ip]</td><td>$sim_id[icc]</td><td>$sim_id[adr]</td><td>$sim_id[info]</td><td>$sim_id[tip]</td></tr>";

		}		
		
		print "</table>";
	}
//	
	if ($act == lst7)
	{
		print "<h2> Логины не на своем месте </h2>";
		print "
			<table border=1>
			<thead>
				<tr><th width=10><b><u>№ п/п</th><th width=100><b><u>Номер</th><th width=100><b><u>Фактический номер</th><th width=100><b><u>Login</th><th width=100><b><u>IP</th></tr>
			</thead>
		";
		$counters = 0;
		$sim_list = mysql_query("select number,result,login,ip from sim_old, test_result where sim_old.id=test_result.id_sim and test_result.chk=3 and number<>result and result <> ''");
		while ($mylist = mysql_fetch_array($sim_list))
		{
			$counters ++;
			print "<tr><td>$counters</td><td>$mylist[number]</td><td>$mylist[result]</td><td>$mylist[login]</td><td><a href=http://$mylist[ip] target=_blank>$mylist[ip]</a></td></tr>";
		}
		
	}
	
	if($act == add)
	{	

		if($act2 == "yes")
		{
			
			#Проверка ICC на дубликат
			$check_icc = mysql_num_rows(mysql_query("select id from simcards where icc = '$icc'"));
			if($check_icc > 0)
			{
				$icc_id = mysql_fetch_array(mysql_query("select id from simcards where icc = '$icc'"));
				print "ICC: <b>$icc</b> уже присутствует в базе (ID №$icc_id[id])";
				
			}
			else
			{
				print " Информация добавлена:<BR><BR>
						ICC: $icc<BR> Телефонный номер: $tel_num<BR> Инфо: $info<BR>
						";
						
				mysql_query("insert into simcards set icc='".$icc."', tel_num='".$tel_num."', info='".$info."'");
				//exit;
			}
		}
?>

		<div class="span3">


		<form method=post action="?sub=sim&act=add&act2=yes" class="form-horizontal">
		<fieldset>
										
			<div class="control-group">											
				<label class="control-label" for="username">Номер телефона</label>
				<div class="controls">
				<input type="text" class="span3" name=tel_num value="8911" placeholder="8911">

				</div> <!-- /controls -->				
			</div> <!-- /control-group -->
			
		
			<div class="control-group">											
				<label class="control-label" for="username">ICC</label>
				<div class="controls">
				<input type="text" class="span3" name=icc placeholder="">

				</div> <!-- /controls -->				
			</div> <!-- /control-group -->

			<div class="control-group">											
				<label class="control-label" for="username">Инфо</label>
				<div class="controls">
				<input type="text" class="span3" name=info value="">

				</div> <!-- /controls -->				
			</div> <!-- /control-group -->
			
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Добавить</button> 
			</div> <!-- /form-actions -->
		</fieldset>
	</form>	
	</div>	

<?
		
		
	}
}


?>
