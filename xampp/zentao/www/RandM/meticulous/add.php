<?php 
    function DateSelector($inName, $useDate=0) 
    { 
         /* ����һ���·��������� */ 
        $monthName = array(1=> "1",  "2",  "3", 
             "4",  "5",  "6",  "7",  "8", 
             "9",  "10",  "11",  "12"); 

         /* ������ݷǷ�����û�б��ṩ����ʹ�õ�ǰʱ��*/ 
        if($useDate == 0) 
        { 
            $useDate = Time();  
        } 
         
         /* �������ѡ����*/ 
        echo  "<SELECT NAME=" . "year>\n"; 
        $startYear = date( "Y", $useDate); 
        for($currentYear = $startYear - 5; $currentYear <= $startYear+5; $currentYear++) 
        { 
            echo  "<OPTION VALUE=\"$currentYear\""; 
            if(date( "Y", $useDate) == $currentYear) 
            { 
                echo  " SELECTED"; 
            } 
            echo  ">$currentYear\n"; 
        } 
        echo  "</SELECT>"; 
        echo "��";
        
         /* �����·�ѡ���� */ 
        echo  "<SELECT NAME=" . "month>\n"; 
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
        { 
            echo  "<OPTION VALUE=\""; 
            echo intval($currentMonth); 
            echo  "\""; 
            if(intval(date( "m", $useDate)) == $currentMonth) 
            { 
                echo  " SELECTED"; 
            } 
            echo  ">" . $monthName[$currentMonth] .  "\n"; 
        } 
        echo  "</SELECT>"; 
	echo "��";
	
	
         /* ��������ѡ����*/ 
        echo  "<SELECT NAME=" . "day>\n"; 
        for($currentDay=1; $currentDay <= 31; $currentDay++) 
        { 
            echo  "<OPTION VALUE=\"$currentDay\""; 
            if(intval(date( "d", $useDate)) == $currentDay) 
            { 
                echo  " SELECTED"; 
            } 
            echo  ">$currentDay\n"; 
        } 
        echo  "</SELECT>"; 
        
     
    } 
?> 

<html>
<body>

<?php	
	$logon_encode = $_GET['logon_encode'];
	$logon_decode = base64_decode($logon_encode);
	/* Get current logon then pass to insert.php */
	session_start();		
	$_SESSION['logon_encode'] = $logon_encode;		
	$_SESSION['logon_decode'] = $logon_decode;
	
	$zentao_servicer = "127.0.0.1";
	$zentao_username = "root";
	$zentao_passwrod = "";
	$zentao_database = "zentao"; 
	
        $connection_zentao = mysql_pconnect($zentao_servicer,$zentao_username,$zentao_passwrod) or die ("�����������ݿ�:"); 
        mysql_query("set names gbk"); 
        $db_selected_zentao = mysql_select_db($zentao_database, $connection_zentao); 
?>



<form action="insert.php" method='post' onsubmit="return check();">
  <table>
  <tr>
     <td class='rowhead'><?php echo "������";?></td>
      <td>
	<select size="1" name="finder" style="width:180px">
	  <?php   	 
		$strSql = "select * from zt_user where deleted = '0' order by account";  
		$users = mysql_query($strSql,$connection_zentao) or die("��ѯʧ�ܣ�������".mysql_error());  		
		
		/* Get current logon's realname */
		$strSql = "select * from zt_user where account = '$logon_decode'";  
		$visitorinfo = mysql_query($strSql, $connection_zentao) or die("��ѯʧ�ܣ�������".mysql_error()); 
		$row = mysql_fetch_array($visitorinfo);
		$visitor = $row['realname'];
		
		while($user = mysql_fetch_array($users)){ 
			/* ������ĸ���� 2014-01-06 fujia */
			$initial  = strtoupper(substr($user['account'], 0, 1)) . ':';
			$username = $user['realname'];
			if("$username" == "$visitor") 
			{ 
				echo "<option value='$username' selected='selected'>$initial$username</option>";
			}
			else
			{
				echo "<option value='$username'>$initial$username</option>";
			}
		}		
	  ?>
	</select>
      </td>
    </tr> 
    <tr>
      <td class='rowhead'><?php echo "���GG/MM";?></td>
      <td>
	<select size="1" name="maker" style="width:180px">
	  <?php 	 
		$strSql = "select * from zt_user where deleted = '0' order by account";  
		$db_selected_zentao = mysql_select_db($zentao_database, $connection_zentao); 
		$users = mysql_query($strSql,$connection_zentao) or die("��ѯʧ�ܣ�������".mysql_error()); 
			    
		echo "<option value=''></option>";
		while($user = mysql_fetch_array($users)){ 
			/* ������ĸ���� 2014-01-06 fujia */
			$initial  = strtoupper(substr($user['account'], 0, 1)) . ':';
			$username = $user['realname'];
			echo "<option value='$username'>$initial$username</option>";
		}
		mysql_close($connection_zentao);
	  ?>

	</select>
      </td>
    </tr> 
	  <input type="hidden" name="fine" value="">
    <!--<tr>
      <td class='rowhead'><?php echo "�۷�";?></td>
      <td>
      	<select size="1" name="fine" style="width:180px">-->
      	 <?php
      	  /*$fineindexs = array(1,2,3,4,5,6,7,8,9,10);	
      	  foreach($fineindexs as $fineindex)
      	  {
      	  	echo "<option value='$fineindex'>$fineindex</option>";
      	  }*/      	  	
      	 ?>  
      	<!--</select>      	
      </td>
    </tr>-->  
     
    <tr>
      <td class='rowhead'><?php echo "����";?></td>
      <td><?php DateSelector("Sample"); ?></td>
    </tr> 
    <tr>
      <td class='rowhead'><?php echo "����¼�";?></td>      
      <td><textarea name="content" id="textarea" cols="60" rows="10" style="overflow:hidden" wrap="no/off" class="textbox">
      </textarea></td>
    </tr>
   
  </table>
  <input type='hidden' name='pay' value=''>
<input type="submit" name="submit" value="����" style="background��#FFFF99; width:6%; height:4%; margin-left:39%;">
</form>
</body>
</html>

<script type="text/javascript">    
 document.getElementById("textarea").focus();
</script>

<script>	
function check(){		
if(confirm('�ύǰ��ȷ�������Ƿ���ȷ~~��')){			
return true;		
}else{			
return false;		
}	
}
</script>