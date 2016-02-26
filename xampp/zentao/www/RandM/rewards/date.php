<?php 
     /***************************************************************/ 
     /*函数：DateSelector v1.1                                      */ 
     /*编码： PHP 3                                                 */ 
     /*作者： Leon Atkinson < leon@clearink.com >                   */ 
     /*创建三个表单字段，以用来先择 月份/日期/年份                  */ 
     /*输入： 日期默认值以及输入的日期                              */ 
     /*输出： 被HTML所定义的三个日期字段                            */ 
     /***************************************************************/ 

    function DateSelector($inName, $useDate=0) 
    { 
         /* 创建一个月份名的数组 */ 
        $monthName = array(1=> "January",  "February",  "March", 
             "April",  "May",  "June",  "July",  "August", 
             "September",  "October",  "November",  "December"); 
      
         /* 如果数据非法或是没有被提供，就使用当前时间*/ 
        if($useDate == 0) 
        { 
            $useDate = Time();  
        } 

         /* 创建月份选择器 */ 
        echo  "<SELECT NAME=" . $inName .  "Month>\n"; 
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
        { 
            echo  "<OPTION VALUE=\""; 
            echo intval($currentMonth); 
            echo  "\""; 
            if(intval(date( "m", $useDate))==$currentMonth) 
            { 
                echo  " SELECTED"; 
            } 
            echo  ">" . $monthName[$currentMonth] .  "\n"; 
        } 
        echo  "</SELECT>"; 

         /* 创建日期选择器*/ 
        echo  "<SELECT NAME=" . $inName .  "Day>\n"; 
        for($currentDay=1; $currentDay <= 31; $currentDay++) 
        { 
            echo  "<OPTION VALUE=\"$currentDay\""; 
            if(intval(date( "d", $useDate))==$currentDay) 
            { 
                echo  " SELECTED"; 
            } 
            echo  ">$currentDay\n"; 
        } 
        echo  "</SELECT>"; 
         
         /* 创建处份选择器*/ 
        echo  "<SELECT NAME=" . $inName .  "Year>\n"; 
        $startYear = date( "Y", $useDate); 
        for($currentYear = $startYear - 5; $currentYear <= $startYear+5;$currentYear++) 
        { 
            echo  "<OPTION VALUE=\"$currentYear\""; 
            if(date( "Y", $useDate)==$currentYear) 
            { 
                echo  " SELECTED"; 
            } 
            echo  ">$currentYear\n"; 
        } 
        echo  "</SELECT>"; 
     
    } 
?> 

<HTML> 
<BODY> 
<FORM> 
Choose a Date:  <?php DateSelector( "Sample"); ?> 
</FORM> 
</BODY> 
</HTML>
