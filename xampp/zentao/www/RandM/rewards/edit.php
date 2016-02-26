<html>
<body>
<form action="update.php" method='post' onsubmit="return check();">
  <table>   
  <?php
	$id = trim(htmlspecialchars($_GET["id"])); 	

	$logon_encode = $_GET['logon_encode'];
	$logon_decode = base64_decode($logon_encode);
	/* Get current logon then pass to update.php */
	session_start();	
	$_SESSION['id'] = $id;	
	$_SESSION['logon_encode'] = $logon_encode;		
	$_SESSION['logon_decode'] = $logon_decode;

	/* according to the id, get the item's info */
	$reward_servicer = "127.0.0.1";
	$reward_username = "root";
	$reward_passwrod = "";
	$reward_database = "rewards"; 	
	
	$connection_reward = mysql_connect($reward_servicer,$reward_username,$reward_passwrod) or die ("不能连接数据库:"); 
	$strSql_id = "select * from data where id='$id'";	 
	
	mysql_query("set names gbk"); 
	$db_selected_reward = mysql_select_db($reward_database, $connection_reward); 
	$user = mysql_query($strSql_id, $connection_reward) or die("查询失败！错误是：".mysql_error()); //执行sql查询
	$row = mysql_fetch_array($user);	
	$rewarder = $row['rewarder'];
	$way = $row['way'];
	$presenter = $row['presenter'];
	$date = $row['date'];
	$content = $row['content'];
	
	mysql_close($connection_reward);
	
	
	/* according to the id, get the item's info */	
	$zentao_servicer = "127.0.0.1";
	$zentao_username = "root";
	$zentao_passwrod = "";
	$zentao_database = "zentao"; 
	
	$connection_zentao = mysql_connect($zentao_servicer,$zentao_username,$zentao_passwrod) or die ("不能连接数据库:"); 	 
	$strSql = "select * from zt_user where deleted = '0' order by account";  
	mysql_query("set names gbk"); 
	$db_selected_zentao = mysql_select_db($zentao_database, $connection_zentao); 
	$users = mysql_query($strSql,$connection_zentao) or die("查询失败！错误是".mysql_error()); 

	echo "<tr>";
	echo "<td align='center' height='20px' valign='center'>" . "表扬人" . "</td>";
	echo "<td>";
	echo  "<select style='width:155px' name='presenter'>\n"; 
        while($user = mysql_fetch_array($users))
        { 
            $username = $user['realname'];
            echo  "<option value='$username'"; 
            if("$username" == "$presenter") 
            { 
                echo  " selected='selected'"; 
            } 
            echo  ">$username\n"; 
        } 
        echo  "</select>"; 
	echo "</td>";
	echo "</tr>";


	$users = mysql_query($strSql,$connection_zentao) or die("查询失败！错误是".mysql_error());
	echo "<tr>";
	echo "<td align='center' height='20px' valign='center'>" . "表扬对象" . "</td>";
	echo "<td>";
	echo  "<select style='width:155px' name='rewarder'>\n"; 
        while($user = mysql_fetch_array($users))
        { 
            $username = $user['realname'];
            echo  "<option value='$username'"; 
            if("$username" == "$rewarder") 
            { 
                echo  " selected='selected'"; 
            } 
            echo  ">$username\n"; 
        } 
        echo  "</select>"; 
	echo "</td>";
	echo "</tr>";
	
	mysql_close($connection_zentao);
	/* end zentao */
	

	echo "<tr>";
	echo "<td align='center' height='20px' valign='center'>" . "方式" . "</td>";
	echo "<td><input type='text' name='way' value='$way'></td>";
	echo "</tr>";


	echo "<tr>";
	echo "<td align='center' height='20px' valign='center'>" . "日期" . "</td>";
	echo "<td><input type='text' name='date' value='$date'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td align='center' height='20px' valign='center'>" . "闪光点" . "</td>";
	echo "<td><textarea name='content' id='textarea' cols='60' rows='10' style='overflow:hidden' wrap='no/off' class='textbox'>$content</textarea></td>";
	echo "</tr>";

  ?>
  

   
  </table>
  <input type='hidden' name='pay' value=''>
  <input type="submit" name="submit" value="更新" style="background：#FFFF99; width:6%; height:4%; margin-left:39%;">
</form>

</body>
</html>

<script type="text/javascript">    
 document.getElementById("textarea").focus();
</script>
<script>	
function check(){		
if(confirm('提交前请确认内容是否正确~~！')){			
return true;		
}else{			
return false;		
}	
}
</script>


