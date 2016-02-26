<?php 
     /***************************************************************/ 
     /*������DateSelector v1.1                                      */ 
     /*���룺 PHP 3                                                 */ 
     /*���ߣ� Leon Atkinson < leon@clearink.com >                   */ 
     /*�����������ֶΣ����������� �·�/����/���                  */ 
     /*���룺 ����Ĭ��ֵ�Լ����������                              */ 
     /*����� ��HTML����������������ֶ�                            */ 
     /***************************************************************/ 

    function DateSelector($inName, $useDate=0) 
    { 
         /* ����һ���·��������� */ 
        $monthName = array(1=> "January",  "February",  "March", 
             "April",  "May",  "June",  "July",  "August", 
             "September",  "October",  "November",  "December"); 
      
         /* ������ݷǷ�����û�б��ṩ����ʹ�õ�ǰʱ��*/ 
        if($useDate == 0) 
        { 
            $useDate = Time();  
        } 

         /* �����·�ѡ���� */ 
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

         /* ��������ѡ����*/ 
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
         
         /* ��������ѡ����*/ 
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
