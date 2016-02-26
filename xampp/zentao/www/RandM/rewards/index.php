<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>互联网产品研发中心员工闪光点</title>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script type="text/javascript"></script>
<style type="text/css">
/* Reset style */
* { margin:0; padding:0; word-break:break-all; }
body { background:#FFF; color:#333; font:12px/1.6em Helvetica, Arial, sans-serif; }
h1, h2, h3, h4, h5, h6 { font-size:1em; }
a { color:#333; text-decoration:none; }
a:hover { text-decoration:underline; }
ul, li { list-style:none; }
fieldset, img { border:none; }
/* Hotnews style */
#hotnews { width:1100px; margin:10px;}
#hotnews_caption { width:1100px; overflow:hidden; border-bottom:3px solid #C2130E; margin-left:30px;}
#hotnews_content ul { padding:10px 0px 0px 5px;  margin-left:30px;}
</style>

<style> 
.box{border:1px solid #C0C0C0;width:150px;height:40px;font-size:20px;margin-left:48px;} 
.button { 
border-right: #7b9ebd 1px solid; 
padding-right: 5px; 
border-top: #7b9ebd 1px solid; 
padding-left: 5px; 
font-size: 20px; 
border-left: #7b9ebd 1px solid; 
cursor: hand; 
color: black; 
padding-top: 5px; 
padding-bottom: 5px; 
border-bottom: #7b9ebd 1px solid 
}
</style>
</script>
</head>

<body>
<br><br><br>
<center><p><font color='#0066CC' size=6><b>互联网产品研发中心员工闪光点</b></font></p></center>
<br>

<?php	
	$logon_encode = $_GET['logon_encode'];		
	$logon_decode = base64_decode($logon_encode);
	
	$zentao_servicer = "127.0.0.1";
	$zentao_username = "root";
	$zentao_passwrod = "";
	$zentao_database = "zentao"; 
	
        $connection_zentao = mysql_connect($zentao_servicer,$zentao_username,$zentao_passwrod) or die ("不能连接数据库:"); 
        mysql_query("set names gbk"); 
        $db_selected_zentao = mysql_select_db($zentao_database, $connection_zentao); 
	
	echo "<a href='./add.php?logon_encode=$logon_encode'><input type='button' value='+ 新增记录' style='width:10%; height:5%; margin-left:77%;'></input></a>";
?> 

<div id="hotnews">
  <div id="hotnews_caption"></div>
  <div id="hotnews_content">
  <ul>
	<ul id="MenuBar1" class="MenuBarHorizontal">
	
	  <li class="user_t"><a class="MenuBarItemSubmenu">表扬人</a>
	    <ul class="MenuBarHorizontal_select">	     
	     <?php
	     	$strSql = "select * from zt_user where deleted = '0' order by account";  
		$users = mysql_query($strSql,$connection_zentao) or die("查询失败！错误是".mysql_error());
		while($user = mysql_fetch_array($users)){ 
			$username = $user['realname'];
			$account = $user['account'];
			echo "<li class='user'><a href='searchpresenter.php?logon_encode=$logon_encode&account=$account'>$username</a></li>";
		}
	    ?>	     
	    </ul>
	  </li>
	  
	  	
	  <li class="user_t"><a class="MenuBarItemSubmenu">表扬对象</a>
	    <ul class="MenuBarHorizontal_select">
	     <?php
		$strSql = "select * from zt_user where deleted = '0' order by account";  
		$users = mysql_query($strSql,$connection_zentao) or die("查询失败！错误是".mysql_error());
		while($user = mysql_fetch_array($users)){ 
			$username = $user['realname'];
			$account = $user['account'];
			echo "<li class='user'><a href='searchrewarder.php?logon_encode=$logon_encode&account=$account'>$username</a></li>";
		}
	    ?>	  
	    </ul>
	  </li>
	  
	  <li class="content_t">表扬内容</li>
	  <li class="way_t">方式</li> 
	  <li class="date_t">日期</li>
	  <li class="action_t">操作</li>
	</ul>
     
  	
	<?php	
		$reward_servicer = "127.0.0.1";
		$reward_username = "root";
		$reward_passwrod = "";
		$reward_database = "rewards"; 
		
		$connection_reward = mysql_connect($reward_servicer,$reward_username,$reward_passwrod) or die ("不能连接数据库:"); 
		mysql_query("set names gbk"); 
		$db_selected_reward = mysql_select_db($reward_database, $connection_reward); 					 
		$strSql = "select * from data order by id desc";  
		$result = mysql_query($strSql,$connection_reward) or die("查询失败！错误是".mysql_error()); 		
		
		//获取总数据量
		$amount = mysql_num_rows($result);
		
		//获取当前页数
		if(isset($_GET['page']))
		{
			$page = intval($_GET['page']);
		}
		else
		{
			$page = 1;
		}
		
		
		//每页数量
		$page_size = 30;
		
		//计算总共有多少页
		if($amount < $page_size)
		{
			$page_count = 1;
		}
		else if($amount % $page_size) //拿总数据除以每页的总数，如果有余数，那么总页数等于商+1
		{
			$page_count = (int)($amount/$page_size)+1;
		}
		else
		{
			$page_count = (int)($amount/$page_size);
		}
		
		
		//翻页链接
		$page_string = '';
		if($page == 1)
		{
			$page_string  ='首页|<a href=index.php?logon_encode='.$logon_encode.'&page='.($page+1).'>下页</a>';
		}
		elseif($page == $page_count || $page_count==0)
		{
			$page_string = '<a href=index.php?logon_encode='.$logon_encode.'&page='.($page-1).'>上页</a>|<a href=index.php?logon_encode='.$logon_encode.'&page='.$page_count.'>尾页</a>';
		}
		elseif($page>1 && $page<$page_count)
		{
			$page_string = '<a href=index.php?logon_encode='.$logon_encode.'&page='.($page-1).'>上页</a>|<a href=index.php?logon_encode='.$logon_encode.'&page='.($page+1).'>下页</a>';
		}
		
		
		$fromdata = ($page-1)*$page_size;
		$strSql = "select * from data order by id desc limit $fromdata,$page_size";
		$result = mysql_query($strSql,$connection_reward) or die("查询失败！错误是".mysql_error());		
		while($row = mysql_fetch_array($result)){
			$rewarder = $row['rewarder'];
			$content = $row['content'];
			$way = $row['way'];
			$date = $row['date'];
			$presenter = $row['presenter'];
			$pay = $row['pay'];

			echo "<ul id='MenuBar1' class='MenuBarHorizontal'>";
			echo "<li class='user_c'>$presenter</li>";			
			echo "<li class='user_c'>$rewarder</li>";
			echo "<li class='content_c'>$content</li>";
			echo "<li class='way_c'>$way</li>";
			echo "<li class='date_c'>$date</li>";	
			$id = $row['id'];			
			if("$logon_decode" == "shaoying" || "$logon_decode" == "zhangchen" || "$logon_decode" == "weishujuan" || "$logon_decode" == "caofubing" || "$logon_decode" == "heping" || "$logon_decode" == "qiaoting" || "$logon_decode" == "xiaoqin" || "$logon_decode" == "tongfang")
			{
				echo "<li class='action_c'><a href='./delete.php?id=$id&logon_encode=$logon_encode' onclick='if(confirm(\"确实要删除此条记录吗？\")) return true;else return false;' class='a'>删除</a><span><a href='./edit.php?id=$id&logon_encode=$logon_encode'>编辑</span></a></li>";
			}
			else
			{
				echo "<li class='action_c_u'><a href='./edit.php?id=$id&logon_encode=$logon_encode'>编辑</a></li>";
			}	

			echo "</ul>";

		}
		
		mysql_close($connection_zentao);
		mysql_close($connection_reward);
	 ?>

	 <tfoot>
	 <tr>
	  <td colspan='9'>
	   <?php 
	    echo "<br>";
	    echo "<div class='f-right'><div style='float:right; clear:none;'><font size='2px'>共<strong>$amount</strong>条记录，页面：<strong>$page/$page_count</strong>，$page_string</font></div></div>";
	    echo "<br>";
	   ?>
	 </td>
	 </tr>
	</tfoot>
	
		
      </ul>  
      
      
      <?php
       if("$logon_decode" == "shaoying" || "$logon_decode" == "zhangchen" || "$logon_decode" == "weishujuan" || "$logon_decode" == "caofubing" || "$logon_decode" == "heping" || "$logon_decode" == "qiaoting" || "$logon_decode" == "xiaoqin" || "$logon_decode" == "tongfang")
       {
       	echo "<a href='./export.php'><input type='button' value='统计数据' style='width:8%; height:4%; margin-left:93%;'></input></a>";
       }
      ?>  
		
    </div>    
  </div>  
</div>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
</html>