<?php 
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


/* compute begindate and enddate of lastweek */
function date_plus($date, $days)
{ 
	$t1 = strtotime($date); 
	$t2 = $t1 - $days*3600*24; 
	return date("Y-n-j", $t2); 
}
$wk_day = date("w");   			
$nowdate = date("Y-m-d");
$beginDate = date_plus($nowdate, ($wk_day+7));
$endDate = date_plus($nowdate, ($wk_day+1));

$strSql = "select * from data where date >= '$beginDate' and date <= '$endDate'";  
$db_selected = mysql_select_db($mysql_database, $connection); 
$result = mysql_query($strSql,$connection) or die("查询失败！错误是".mysql_error()); 

$sep = "\t"; 
for ($i = 0; $i < mysql_num_fields($result); $i++) { 
	echo mysql_field_name($result,$i) . "\t"; 
} 
print("\n"); 


$i = 0; 
while($row = mysql_fetch_row($result)) { 
	$schema_insert = ""; 
	for($j=0; $j<mysql_num_fields($result);$j++) { 
		if(!isset($row[$j])) 
			$schema_insert .= "NULL".$sep; 
		elseif ($row[$j] != "") 
			$schema_insert .= "$row[$j]".$sep; 
		else 
			$schema_insert .= "".$sep; 
	} 
	
	$schema_insert = str_replace($sep."$", "", $schema_insert); 
	$schema_insert .= "\t"; 
	print(trim($schema_insert)); 
	print "\n"; 
	$i++; 
} 

return (true); 
?> 

