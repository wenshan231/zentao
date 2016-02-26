<html>
<head>
<title>export data</title>
</head>

<body>
<table width="100%" border="1" align="center" cellspacing="1" cellpadding="1">
<tr align="center">
    <td nowrap><b>发现人</b></td>
    <td nowrap><b>差不多GG/MM</b></td>    
    <td nowrap><b>内容</b></td>
    <td nowrap><b>日期</b></td>
</tr>

<?php
$file_type = "vnd.ms-excel"; 
$file_ending = "xls"; 
header("Content-Type: application/$file_type;charset=big5"); 
header("Content-Disposition: attachment; filename=".$savename.".$file_ending"); 


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

/* compute begindate and enddate of lastweek */
function date_plus($date, $days)
{ 
	$t1 = strtotime($date); 
	$t2 = $t1 - $days*3600*24; 
	return date("Y-n-j", $t2); 
}
$wk_day = date("w");   			
$nowdate = date("Y-m-d");
$beginDate = date_plus($nowdate, ($wk_day+6));
$endDate = date_plus($nowdate, ($wk_day));

$strSql = "select * from data where date >= '$beginDate' and date <= '$endDate'";  
$results = mysql_query($strSql,$connection_meti) or die("查询失败！错误是".mysql_error()); 

while($result = mysql_fetch_array($results)){
	echo '<tr align="center">';
	$finder = $result["finder"];
	$maker = $result["maker"];
	$content = $result["content"];
	$fine = $result["fine"];
	$date = $result["date"];
	
	echo'<td nowrap>'.$finder.'</td>';	
	echo'<td nowrap>'.$maker.'</td>';
	echo'<td nowrap>'.$content.'</td>';
	echo'<td nowrap>'.$date.'</td>';
	echo '</tr>';
}



mysql_close($connection_meti);

?>
</table>
</body>
</html>

