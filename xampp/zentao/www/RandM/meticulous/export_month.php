<html>
<head>
<title>export data</title>
</head>

<body>
<table width="100%" border="1" align="center" cellspacing="1" cellpadding="1">
<tr align="center">
    <td nowrap><b>差不多GG/MM</b></td>
    <td nowrap><b>被发现次数</b></td>
    <td nowrap><b>发现次数</b></td>
</tr>

<?php
$file_type = "vnd.ms-excel"; 
$file_ending = "xls"; 
header("Content-Type: application/$file_type;charset=big5"); 
header("Content-Disposition: attachment; filename=".$savename.".$file_ending"); 



$zentao_servicer = "127.0.0.1";
$zentao_username = "root";
$zentao_passwrod = "";
$zentao_database = "zentao";  
$connection_zentao = mysql_connect($zentao_servicer,$zentao_username,$zentao_passwrod) or die ("不能连接数据库:"); 
mysql_query("set names gbk"); 
$db_selected_zentao = mysql_select_db($zentao_database, $connection_zentao); 


$meti_servicer = "127.0.0.1";
$meti_username = "root";
$meti_passwrod = "";
$meti_database = "meticulous"; 
$connection_meti = mysql_connect($meti_servicer,$meti_username,$meti_passwrod) or die ("不能连接数据库:"); 
mysql_query("set names gbk"); 
$db_selected_meti = mysql_select_db($meti_database, $connection_meti); 

	

$savename = date("YmjHis"); 
$file_type = "vnd.ms-excel"; 
$file_ending = "xls"; 
header("Content-Type: application/$file_type;charset=big5"); 
header("Content-Disposition: attachment; filename=".$savename.".$file_ending"); 


/* 上个月的起始和结束日期 */
function date_plus($date, $days)
{ 
	$t1 = strtotime($date); 
	$t2 = $t1 - $days*3600*24; 
	return date("Y-n-j", $t2); 
}

function date_add($date, $days)
{ 
	$t1 = strtotime($date); 
	$t2 = $t1 + $days*3600*24; 
	return date("Y-n-j", $t2); 
}

$nowdate = date("Y-m-d"); 				
$tmp_year =substr($nowdate,0,4);
$tmp_mon =substr($nowdate,5,2); //切割出月份
$tmp_forwardmonth = mktime(0,0,0,$tmp_mon-1,1,$tmp_year);  
$lastdate=date("Y-m-d",$tmp_forwardmonth); 
$timestamp = strtotime($lastdate);
$mdays = date('t',$timestamp); // 上个月的天数
$tmp_beginDate = date('Y-m-01',$timestamp);
$tmp_endDate = date('Y-m-'.$mdays,$timestamp);				

$beginDate = date_plus($tmp_beginDate, 1);					
$endDate = date_add($tmp_endDate, 1);

$strSql = "select * from zt_user where deleted = '0'";  
$users = mysql_query($strSql,$connection_zentao) or die("查询失败！错误是".mysql_error()); 
while($user = mysql_fetch_array($users)){
	echo '<tr align="center">';
	$realname = $user["realname"];
	echo'<td nowrap>'.$realname.'</td>';	
	
	$strSql = "select * from data where maker='$realname' and date >= '$beginDate' and date <= '$endDate'";  
	$data_maker = mysql_query($strSql, $connection_meti) or die("查询失败！错误是".mysql_error()); 	
	$makecount = mysql_num_rows($data_maker);
	
	$strSql = "select * from data where finder='$realname' and date >= '$beginDate' and date <= '$endDate'";  
	$data_finder = mysql_query($strSql, $connection_meti) or die("查询失败！错误是".mysql_error()); 
	$findcount = mysql_num_rows($data_finder);	
	
	echo'<td nowrap>'.$makecount.'</td>';
	echo'<td nowrap>'.$findcount.'</td>';
	echo '</tr>';
}


mysql_close($connection_zentao);
mysql_close($connection_meti);

?>
</table>
</body>
</html>

