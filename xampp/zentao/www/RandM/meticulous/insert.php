<?php
$finder = trim(htmlspecialchars($_POST["finder"]));  
$maker = trim(htmlspecialchars($_POST["maker"]));  
$content = trim(htmlspecialchars($_POST["content"]));  
$fine = trim(htmlspecialchars($_POST["fine"]));  
$year = trim(htmlspecialchars($_POST["year"]));  
$month = trim(htmlspecialchars($_POST["month"]));  
$day = trim(htmlspecialchars($_POST["day"]));  
$date = $year . "-" . $month . "-" . $day;


/* Get maker and finder email from zt_user. */
$zentao_servicer = "127.0.0.1";
$zentao_username = "root";
$zentao_passwrod = "";
$zentao_database = "zentao"; 
$connection_zentao = mysql_connect($zentao_servicer,$zentao_username,$zentao_passwrod) or die ("�����������ݿ�:"); 
mysql_query("set names gbk"); 
$db_selected_zentao = mysql_select_db($zentao_database, $connection_zentao); 

$strSql = "select * from zt_user where realname = '$maker'";  
$makerinfo = mysql_query($strSql,$connection_zentao) or die("��ѯʧ�ܣ�������".mysql_error()); 
$user = mysql_fetch_array($makerinfo);
$makeremail = $user['email'];

$strSql = "select * from zt_user where realname = '$finder'";  
$finderinfo = mysql_query($strSql, $connection_zentao) or die("��ѯʧ�ܣ�������".mysql_error()); 
$user = mysql_fetch_array($finderinfo);
$finderemail = $user['email'];

mysql_close($connection_zentao);



/* Get current logon */
session_start();
$logon_encode = $_SESSION['logon_encode'];

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
 echo "ֻ������5��֮�ڵĲ���¼����뷵��~~��";
 die();
}
else
{
 echo "<onclick=\"if(confirm('ȷʵҪ���Ӵ�����¼��')) return true;else return false; \" >" . "����";	
}

if($maker == "" || $content == "")
{
 echo "���ݲ�������������~~��";
 die();
}


$meti_servicer = "127.0.0.1";
$meti_username = "root";
$meti_passwrod = "";
$meti_database = "meticulous"; 

$connection_meti = mysql_connect($meti_servicer,$meti_username,$meti_passwrod) or die ("�����������ݿ�:");  
if(!$connection_meti)
{
  die('Could not connect: ' . mysql_error());
}

mysql_query("set names gbk"); 
$db_selected_meti = mysql_select_db($meti_database, $connection_meti); 

$sql = "insert into data(finder,maker,content,fine,date) values ('$finder','$maker','$content','$fine','$date')";
if(mysql_query($sql, $connection_meti))
{
	$subject = "����������Ʒ�з����Ĳ��GG/MM��";
	$body = "��������¼��" . "<br><br>" . "�����ˣ�" . "$finder" . "<br><br>" . "���GG/MM��" . "$maker" . "<br><br>" . "����¼���" . "$content" . "<br><br>" . "�۷֣�" . "$fine";
	postmail($finderemail, $makeremail, $subject, $body);
	
	sleep(3);
 	echo "<script type='text/javascript'>location.href='./index.php?logon_encode=$logon_encode'</script>";	
	
} 
else
{
 echo "����ʧ�ܣ�";
}

mysql_close($connection_meti);

/* ---------------------- postmail -------------------- */
function postmail($finderemail, $makeremail, $subject = "", $body = ""){    
    error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");		//�趨ʱ��������
    require_once('class.phpmailer.php');
    include("class.smtp.php"); 
    $mail             = new PHPMailer(); 		//newһ��PHPMailer�������
    $body             = eregi_replace("[\]",'',$body); 	//���ʼ����ݽ��б�Ҫ�Ĺ���
    $mail->CharSet ="GBK";				//�趨�ʼ����룬Ĭ��ISO-8859-1����������Ĵ���������ã���������
    $mail->IsSMTP(); 					// �趨ʹ��SMTP����
    $mail->SMTPDebug  = 1;                     		// ����SMTP���Թ���
                                           		// 1 = errors and messages
                                           		// 2 = messages only
    $mail->SMTPAuth   = true;                  		// ���� SMTP ��֤����
    $mail->Host       = "omail.coship.com";      	// SMTP ������
    $mail->Port       = 25;                   		// SMTP�������Ķ˿ں�
    $mail->Username   = "zhangchen@coship.com";  		// SMTP�������û���
    $mail->Password   = "905012";            		// SMTP����������
    $mail->SetFrom('zhangchen@coship.com', 'Administrator');
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($finderemail);
    $mail->AddAddress($makeremail);

    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent! ��ϲ���ʼ����ͳɹ���";
        }
    }



?>




