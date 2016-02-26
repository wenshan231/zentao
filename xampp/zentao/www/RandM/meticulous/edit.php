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
	$meti_servicer = "127.0.0.1";
	$meti_username = "root";
	$meti_passwrod = "";
	$meti_database = "meticulous"; 	
	
	$connection_meti = mysql_pconnect($meti_servicer,$meti_username,$meti_passwrod) or die ("不能连接数据库:"); 
	$strSql_id = "select * from data where id='$id'";	 
	
	mysql_query("set names gbk"); 
	$db_selected_meti = mysql_select_db($meti_database, $connection_meti); 
	$user = mysql_query($strSql_id, $connection_meti) or die("查询失败！错误是：".mysql_error()); //执行sql查询
	$row = mysql_fetch_array($user);	
	$maker = $row['maker'];
	$fine = $row['fine'];
	$finder = $row['finder'];
	$date = $row['date'];
	$content = $row['content'];
	
	mysql_close($connection_meti);
	
	
	/* according to the id, get the item's info */	
	$zentao_servicer = "127.0.0.1";
	$zentao_username = "root";
	$zentao_passwrod = "";
	$zentao_database = "zentao"; 
	
	$connection_zentao = mysql_pconnect($zentao_servicer,$zentao_username,$zentao_passwrod) or die ("不能连接数据库:"); 	 
	$strSql = "select * from zt_user where deleted = '0' order by account";  
	mysql_query("set names gbk"); 
	$db_selected_zentao = mysql_select_db($zentao_database, $connection_zentao); 
	$users = mysql_query($strSql,$connection_zentao) or die("查询失败！错误是".mysql_error()); 

	echo "<tr>";
	echo "<td align='center' height='20px' valign='center'>" . "爆料人" . "</td>";
	echo "<td>";
	echo  "<select style='width:155px' name='finder'>\n"; 
        while($user = mysql_fetch_array($users))
        { 
            $username = $user['realname'];
            echo  "<option value='$username'"; 
            if("$username" == "$finder") 
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
	echo "<td align='center' height='20px' valign='center'>" . "差不多GG/MM" . "</td>";
	echo "<td>";
	echo  "<select style='width:155px' name='maker'>\n"; 
        while($user = mysql_fetch_array($users))
        { 
            $username = $user['realname'];
            echo  "<option value='$username'"; 
            if("$username" == "$maker") 
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
	
    echo '<input type="hidden" name="fine" value="">';

	/*echo "<tr>";
	echo "<td align='center' height='20px' valign='center'>" . "扣分" . "</td>";
	echo "<td>";
	echo  "<select style='width:155px' name='fine'>\n"; 
	$fineindexs = array(1,2,3,4,5,6,7,8,9,10);	
        foreach($fineindexs as $fineindex)
        { 	
            echo  "<option value='$fineindex'"; 
            if("$fineindex" == "$fine") 
            { 
                echo  " selected='selected'"; 
            } 
            echo  ">$fineindex\n"; 
        } 
        echo  "</select>"; 
	echo "</td>";
	echo "</tr>";*/

	
	echo "<tr>";
	echo "<td align='center' height='20px' valign='center'>" . "日期" . "</td>";
	echo "<td><input type='text' name='date' value='$date'></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td align='center' height='20px' valign='center'>" . "差不多事件" . "</td>";
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


