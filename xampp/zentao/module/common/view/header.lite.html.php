<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$webRoot      = $this->app->getWebRoot();
$jsRoot       = $webRoot . "js/";
$themeRoot    = $webRoot . "theme/";
$defaultTheme = $webRoot . 'theme/default/';
$langTheme    = $themeRoot . 'lang/' . $app->getClientLang() . '.css';
$clientTheme  = $this->app->getClientTheme();
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dli'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <?php
  echo html::title($title . ' - ' . $lang->zentaoPMS);

  js::exportConfigVars();
  if($config->debug)
  {
      js::import($jsRoot . 'jquery/lib.js', $config->version);
      js::import($jsRoot . 'my.min.js',     $config->version);

      css::import($defaultTheme . 'yui.css',   $config->version);
      css::import($defaultTheme . 'style.css', $config->version);
      css::import($langTheme, $config->version);
      if(strpos($clientTheme, 'default') === false) css::import($clientTheme . 'style.css', $config->version);
  }
  else
  {
      js::import($jsRoot . 'all.js', $config->version);
      css::import($defaultTheme . $this->cookie->lang . '.' . $this->cookie->theme . '.css', $config->version);
  }

  if(isset($pageCss)) css::internal($pageCss);

  echo html::icon($webRoot . 'favicon.ico');
  ?>
</head>
<body>
<!-- coship start 2014-03-01 add by shukaiming 增加一个周六升级维护系统的操作 -->
<marquee id="affiche" align="left" behavior="alternate" bgcolor="#003366" direction="left" height="20" width="100%" hspace="0" vspace="0" loop="-1" scrollamount="20" scrolldelay="400" onMouseOut="this.start()" onMouseOver="this.stop()">
    <div style="font-size: 16px;color:red ;font-weight:bold ">尊敬的禅道用户，您好，禅道项目管理系统将于2014-03-01 20:00-22:30进行数据更新与功能升级维护，期间系统将暂停访问，请提前保存好你的工作备份并互相转告，谢谢合作！</div>
</marquee>
<!-- coship end -->