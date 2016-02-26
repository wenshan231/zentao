<?php
/**
 * coship start
 * 2013-08-31 add file by fujia
 * 测试用例导入excel插件
*/
class testcase extends control
{
    /**
     * 选择上传文件及模板
     */
    public function import($productID = 0, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            echo "<div style='margin-top:100px;margin-left:270px;color:blue;'>";
            echo $this->lang->submitting;
            echo "</div>";

            $file         = $_FILES['file']['name'];
            $fileTempName = $_FILES['file']['tmp_name'];
            $filePath     = '../../../../tmp/';
            $time         = date("YmdHis");
            $extend       = strchr ($file,'.');
            $uploadFile   = $filePath.$time.$extend;
            $result       = move_uploaded_file($fileTempName,$uploadFile);

            if($result)
            {
                setcookie('caseFile', $uploadFile, $this->config->cookieLife, $this->config->webRoot);
                $param = 'productID=' . $productID . '&moduleID=' . $moduleID;
                if(!empty($_POST['optionTitle']))
                {
                    $opt = '&opt=';
                    foreach($_POST['optionTitle'] as $title)
                    {
                        $opt .= $title . ',';
                    }
                    $param .= '&opt=' . rtrim($opt, ',');
                }
                die(js::locate($this->createLink('testcase', 'importPreview', $param), 'parent'));
            }
        }
        die($this->display());
    }
}
/* coship end */