<?php

session_start();
$id = $_SESSION['id']; // Get current id
$logon_encode = $_SESSION['logon_encode'];


$presenter = trim(htmlspecialchars($_POST["presenter"]));  
$rewarder = trim(htmlspecialchars($_POST["rewarder"]));  
$content = trim(htmlspecialchars($_POST["content"]));  
$way = trim(htmlspecialchars($_POST["way"]));  
$date = trim(htmlspecialchars($_POST["date"])); 



/* Get rewarder and presenter email from zt_user. */
$zentao_servicer = "127.0.0.1";
$zentao_username = "root";
$zentao_passwrod = "";
$zentao_database = "zentao"; 
$connection_zentao = mysql_connect($zentao_servicer,$zentao_username,$zentao_passwrod) or die ("�����������ݿ�:"); 
mysql_query("set names gbk"); 
$db_selected_zentao = mysql_select_db($zentao_database, $connection_zentao); 

$strSql = "select * from zt_user where realname = '$rewarder'";  
$rewarderinfo = mysql_query($strSql,$connection_zentao) or die("��ѯʧ�ܣ�������".mysql_error()); 
$user = mysql_fetch_array($rewarderinfo);
$rewarderemail = $user['email'];

$strSql = "select * from zt_user where realname = '$presenter'";  
$presenterinfo = mysql_query($strSql, $connection_zentao) or die("��ѯʧ�ܣ�������".mysql_error()); 
$user = mysql_fetch_array($presenterinfo);
$presenteremail = $user['email'];

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
 echo "ֻ���޸�5��֮�ڵ�����㣬�뷵��~~��";
 die();
}
else
{
 echo "<onclick=\"if(confirm('ȷʵҪ���Ӵ�����¼��')) return true;else return false; \" >" . "����";	
}

if($rewarder == "" || $content == "")
{
 echo "���ݲ�������������~~��";
 die();
}

/* Update data. */
/* Get rewarder and presenter email. */
$meti_servicer = "127.0.0.1";
$meti_username = "root";
$meti_passwrod = "";
$meti_database = "rewards"; 	

$connection_meti = mysql_connect($meti_servicer,$meti_username,$meti_passwrod) or die ("�����������ݿ�:"); 
if(!$connection_meti)
{
  die('Could not connect: ' . mysql_error());
}

mysql_query("set names gbk"); 
$db_selected_meti = mysql_select_db($meti_database, $connection_meti);

$sql = "update data set presenter='$presenter',rewarder='$rewarder',content='$content',way='$way',date='$date' where id='$id'";

if(mysql_query($sql, $connection_meti))
{
	$subject = "����������Ʒ�з�����Ա������㡿";
	$body = "���޸ļ�¼��" . "<br><br>" . "�����ˣ�" . "$presenter" . "<br><br>" . "�������" . "$rewarder" . "<br><br>" . "����㣺" . "$content" . "<br><br>" . "��ʽ��" . "$way" ;
	postmail($presenteremail, $rewarderemail, $subject, $body);
	
	sleep(3);
 	echo "<script type='text/javascript'>location.href='./index.php?logon_encode=$logon_encode'</script>";	
	
} 
else
{
	echo "����ʧ�ܣ�";
}

mysql_close($connection_meti);


/* ---------------------- postmail -------------------- */
function postmail($presenteremail, $rewarderemail, $subject = "", $body = ""){    
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
    $mail->AddAddress($presenteremail);
    $mail->AddAddress($rewarderemail);
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent! ��ϲ���ʼ����ͳɹ���";
        }
    }

?>





