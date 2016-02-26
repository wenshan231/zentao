<?php

session_start();
$id = $_SESSION['id']; // Get current id
$logon_encode = $_SESSION['logon_encode'];


$finder = trim(htmlspecialchars($_POST["finder"]));  
$maker = trim(htmlspecialchars($_POST["maker"]));  
$content = trim(htmlspecialchars($_POST["content"]));  
$fine = trim(htmlspecialchars($_POST["fine"]));  
$date = trim(htmlspecialchars($_POST["date"])); 
$pay = trim(htmlspecialchars($_POST["pay"])); 


/* Get maker and finder email from zt_user. */
$zentao_servicer = "127.0.0.1";
$zentao_username = "root";
$zentao_passwrod = "";
$zentao_database = "zentao"; 
$connection_zentao = mysql_connect($zentao_servicer,$zentao_username,$zentao_passwrod) or die ("不能连接数据库:"); 
mysql_query("set names gbk"); 
$db_selected_zentao = mysql_select_db($zentao_database, $connection_zentao); 

$strSql = "select * from zt_user where realname = '$maker'";  
$makerinfo = mysql_query($strSql,$connection_zentao) or die("查询失败！错误是".mysql_error()); 
$user = mysql_fetch_array($makerinfo);
$makeremail = $user['email'];

$strSql = "select * from zt_user where realname = '$finder'";  
$finderinfo = mysql_query($strSql, $connection_zentao) or die("查询失败！错误是".mysql_error()); 
$user = mysql_fetch_array($finderinfo);
$finderemail = $user['email'];

mysql_close($connection_zentao);


/* compute 5 days before now */
function date_plus($date, $days)
{ 
	$t1 = strtotime($date); 
	$t2 = $t1 - $days*3600*24; 
	return date("Y-n-j", $t2); 
}
$wk_day = date("w");   			
$nowdate = date("Y-m-d");
$beginDate = date_plus($nowdate, ($wk_day+4));
$time = strtotime($date);
$beginTime = strtotime($beginDate);
if($time < $beginTime)
{
 echo "只能修改5天之内的差不多事件，请返回~~！";
 die();
}
else
{
 echo "<onclick=\"if(confirm('确实要增加此条记录吗？')) return true;else return false; \" >" . "增加";	
}

if($maker == "" || $content == "")
{
 echo "内容不完整，请重填~~！";
 die();
}

/* Update data. */
/* Get maker and finder email. */
$meti_servicer = "127.0.0.1";
$meti_username = "root";
$meti_passwrod = "";
$meti_database = "meticulous"; 	

$connection_meti = mysql_connect($meti_servicer,$meti_username,$meti_passwrod) or die ("不能连接数据库:"); 
if(!$connection_meti)
{
  die('Could not connect: ' . mysql_error());
}

mysql_query("set names gbk"); 
$db_selected_meti = mysql_select_db($meti_database, $connection_meti);

$sql = "update data set finder='$finder',maker='$maker',content='$content',fine='$fine',date='$date',pay='$pay' where id='$id'";

if(mysql_query($sql, $connection_meti))
{
	$subject = "【互联网产品研发中心差不多GG/MM】";
	$body = "【修改记录】" . "<br><br>" . "爆料人：" . "$finder" . "<br><br>" . "差不多GG/MM：" . "$maker" . "<br><br>" . "差不多事件：" . "$content" . "<br><br>" . "扣分：" . "$fine";
	//postmail($finderemail, $makeremail, $subject, $body);
	
	sleep(3);
	echo "<script type='text/javascript'>location.href='./index.php?logon_encode=$logon_encode'</script>";
} 
else
{
	echo "更新失败！";
}

mysql_close($connection_meti);


/* ---------------------- postmail -------------------- */
function postmail($finderemail, $makeremail, $subject = "", $body = ""){    
    error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");		//设定时区东八区
    require_once('class.phpmailer.php');
    include("class.smtp.php"); 
    $mail             = new PHPMailer(); 		//new一个PHPMailer对象出来
    $body             = eregi_replace("[\]",'',$body); 	//对邮件内容进行必要的过滤
    $mail->CharSet ="GBK";				//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); 					// 设定使用SMTP服务
    $mail->SMTPDebug  = 1;                     		// 启用SMTP调试功能
                                           		// 1 = errors and messages
                                           		// 2 = messages only
    $mail->SMTPAuth   = true;                  		// 启用 SMTP 验证功能
    $mail->Host       = "omail.coship.com";      	// SMTP 服务器
    $mail->Port       = 25;                   		// SMTP服务器的端口号
    $mail->Username   = "zhangchen@coship.com";  		// SMTP服务器用户名
    $mail->Password   = "905012";            		// SMTP服务器密码
    $mail->SetFrom('zhangchen@coship.com', 'Administrator');
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($finderemail);
    $mail->AddAddress($makeremail);
    $mail->AddAddress("heping@coship.com");
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent! 恭喜，邮件发送成功！";
        }
    }

?>





