<?php include '../../../common/view/header.html.php';?>
<?php 
js::import($jsRoot . 'misc/calendar/fullcalendar.min.js');
js::import($jsRoot . 'misc/calendar/jquery-ui-1.8.17.custom.min.js');
css::import($defaultTheme . 'calendar/fullcalendar.css');
css::import($defaultTheme . 'calendar/main.css');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta name='' content=''>
<title>FullCalendar - Full-sized Calendar jQuery Plugin</title>

</head>
<body>
<div id='calendar'></div> 
</body>
</html>
<?php include '../../../common/view/footer.html.php';?>
