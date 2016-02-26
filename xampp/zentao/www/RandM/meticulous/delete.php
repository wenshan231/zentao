<?php
$mysql_servicer = "127.0.0.1";
$mysql_username = "root";
$mysql_passwrod = "";
$mysql_database = "meticulous";



$connection = mysql_pconnect($mysql_servicer,$mysql_username,$mysql_passwrod) or die ("不能连接数据库:");  
if(!$connection)
{
  die('Could not connect: ' . mysql_error());
}

mysql_query("set names gbk"); 
$db_selected = mysql_select_db($mysql_database, $connection); 


$id = trim(htmlspecialchars($_GET["id"]));  
$logon_encode = $_GET['logon_encode'];


$sql = "delete from data where id='$id'";
if(mysql_query($sql, $connection))
{
 echo "<script type='text/javascript'>location.href='./index.php?logon_encode=$logon_encode'</script>";
} 
else
{
 echo "删除失败！";
}

mysql_close($connection);
?>






