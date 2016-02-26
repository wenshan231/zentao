<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<html>

<head>
<meta name="generator" Content="Microsoft Visual Studio 6.0">
<title>我要上头条</title>
<style type="text/css">
.textbox { BACKGROUND: 0; BORDER-TOP: #7F9DB9 0 solid; BORDER-LEFT: #7F9DB9 0 solid; BORDER-RIGHT: #7F9DB9 0 solid; BORDER-BOTTOM: #7F9DB9 0 solid; FONT-FAMILY: "宋体", "Verdana", "Arial", "Helvetica"; FONT-SIZE: 16px; TEXT-ALIGN: LEFT; WORD-SPACING: 5px; LINE-HEIGHT: 50x; FONT-WEIGHT: bold}
</style> 
</head>

<body alink="#FF0000" link="#000099" vlink="#CC6600" topmargin="10" leftmargin="10" bgColor="#FFFFFF" style="overflow:hidden">
<center>
  <br><p><font color="0066CC" size=5><b>我要上头条</b></font></p>
</center>


<?php	
	$logon_encode = $_GET['logon_encode'];	
?>

<div id="menu"> 
 <ul> 
   <?php
    echo "<li><a href='./meticulous/index.php?logon_encode=$logon_encode' target='_blank'>【差不多GG/MM】</a></li>"; 
    echo "<br>";
    echo "<li><a href='./rewards/index.php?logon_encode=$logon_encode' target='_blank'>【员工闪光点】</a></li>"; 
   ?>       
 </ul> 
</div> 

</body>
</html>
