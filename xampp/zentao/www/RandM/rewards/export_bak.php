<html>
<head>
<title>export data</title>
</head>

<body>
<table width="100%" border="1" align="center" cellspacing="1" cellpadding="1">
<tr align="center">
    <td nowrap><b>差不多GG/MM</b></td>
    <td nowrap><b>被发现次数</b></td>
    <td nowrap><b>抵消次数</b></td>
    <td nowrap><b>抵消后总次数</b></td>
</tr>

<?php

$file_type = "vnd.ms-excel"; 
$file_ending = "xls"; 
header("Content-Type: application/$file_type;charset=big5"); 
header("Content-Disposition: attachment; filename=".$savename.".$file_ending"); 


$mysql_servicer = "127.0.0.1";
$mysql_username = "root";
$mysql_passwrod = "";
$mysql_database = "meticulous";  

$savename = date("YmjHis"); 
$connection = mysql_connect($mysql_servicer,$mysql_username,$mysql_passwrod) or die ("不能连接数据库:"); 	
mysql_query("Set Names gbk"); 

$file_type = "vnd.ms-excel"; 
$file_ending = "xls"; 
header("Content-Type: application/$file_type;charset=big5"); 
header("Content-Disposition: attachment; filename=".$savename.".$file_ending"); 


$strSql = "select * from user";  
$db_selected = mysql_select_db($mysql_database, $connection); 
$users = mysql_query($strSql,$connection) or die("查询失败！错误是".mysql_error()); 


while($user = mysql_fetch_array($users)){
	echo '<tr align="center">';
	$realname = $user["realname"];
	echo'<td nowrap>'.$realname.'</td>';	
	
	$strSql = "select * from data where maker='$realname'";  
	$data_maker = mysql_query($strSql, $connection) or die("查询失败！错误是".mysql_error()); 	
	$makecount = mysql_num_rows($data_maker);
	
	$strSql = "select * from data where finder='$realname'";  
	$data_finder = mysql_query($strSql, $connection) or die("查询失败！错误是".mysql_error()); 
	$findcount = mysql_num_rows($data_finder);
	
	
	$net = 3;	
	echo'<td nowrap>'.$makecount.'</td>';
	echo'<td nowrap>'.$findcount.'</td>';
	echo'<td nowrap>'.$net.'</td>';
	echo '</tr>';
}

?>
</table>
</body>
</html>

