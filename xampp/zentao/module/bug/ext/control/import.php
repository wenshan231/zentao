<?php
/**
 * coship start
 * 2013-12-11 add file by fujia
 * bug导入excel插件
*/
class bug extends control
{
   
    /**
     * 选择上传文件及模板
     */
    public function import($productID, $moduleID = 0)
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
                setcookie('bugFile', $uploadFile, $this->config->cookieLife, $this->config->webRoot);
                $param = 'productID=' . $productID . '&moduleID=' . $moduleID;
                if(!empty($_POST['project']))
                {
                    $param .=  '&projectID=' .$_POST['project'];
                }

                if(!empty($_POST['openedBuild'][0]))
                {
                    $param .=  '&openedBuild=' .$_POST['openedBuild'][0];
                }

                die(js::locate($this->createLink('bug', 'importPreview', $param), 'parent'));
            }
        }

        $this->view->productID        = $productID;
        $this->view->buildID          = 0;
        $this->view->projectID        = 0;
        $this->view->projects         = $this->loadModel('product')->getProjectPairs($productID);
        $this->view->builds           = $this->loadModel('build')->getProductBuildPairs($productID);

        die($this->display());
    }
}
/* coship end */