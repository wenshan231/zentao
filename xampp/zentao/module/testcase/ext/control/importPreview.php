<?php
/**
 * coship start
 * 2013-08-31 add file by fujia
 * 测试用例导入excel插件
*/
class testcase extends control
{
    /**
     * 导入excel预览
     * 模板中的前5列 title, precondition,steps,expects,pri
     */
    public function importPreview($productID = 0, $moduleID = 0, $opt = '')
    {
        $this->loadModel('product');
        $this->view->products = $this->products = $this->product->getPairs();

        $data = array();
        if(!empty($_POST))
        {
            $caseID = $this->testcase->import($productID, $moduleID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('testcase', 'browse', "productID=$productID&browseType=byModule&moduleID=$moduleID"), 'parent'));
        }
        elseif($_COOKIE['caseFile'] && empty($_POST))
        {
            require_once '../../../../lib/phpexcel/PHPExcel.php';
            require_once '../../../../lib/phpexcel/PHPExcel/IOFactory.php';
            require_once '../../../../lib/phpexcel/PHPExcel/Reader/Excel5.php';

            $uploadFile = $_COOKIE['caseFile'];
            try
            {
                /* excel2007格式使用 excel2007 */
                $objReader   = PHPExcel_IOFactory::createReader('Excel5');
                $objPHPExcel = $objReader->load($uploadFile);
                $sheet       = $objPHPExcel->getSheet(0);
            }
            catch (Exception $e)
            {
                echo '<script type="text/javascript">alert("' . $this->lang->testcase->confirmExcelType . '");history.back(-1);</script>';
                exit();
            }

            /* 默认字段与可选字段合并 */
            $length = 0;
            $fields = array('title', 'pri', 'precondition', 'steps', 'expects');
            if(!empty($opt))
            {
                $optFields = explode(',', $opt);
                $length    = count($optFields);
                $fields    = array_merge($optFields, $fields);
            }

            /* excel首行 */
            $headTitle=array();
            /* excel总行数 */
            $highestRow         = $sheet->getHighestRow();
            /* excel总列数 */
            $highestColumn      = $length + 5;

            $i = 0;
            /* 优先级：最高=>1, 高=>2, 中=>3, 低=>4 */
            $importPriList = array(
                $this->lang->testcase->priList[1] => 1,
                $this->lang->testcase->priList[2] => 2,
                $this->lang->testcase->priList[3] => 3,
                $this->lang->testcase->priList[4] => 4
                );

            /* 读取excel进数组，并格式化数据。 */
            for ($row = 2;$row <= $highestRow;$row++) 
            {
                /* 测试用例标题不能为空。 */
                $title = trim($sheet->getCellByColumnAndRow($length, $row)->getValue());
                if(empty($title)) continue;
                for ($col = 0;$col < $highestColumn;$col++)
                {
                    $data[$i][$fields[$col]] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                }
                $seperator = 'coshipSeperator';
                if(!empty($data[$i]['steps']))
                {
                    $str_step          = preg_replace("/[\n\r\t\v]{1,}/", $seperator, trim($data[$i]['steps']));
                    $steps             = explode($seperator, $str_step);
                    $data[$i]['steps'] = $steps;
                    $stepsCount        = count($steps);
                }
                if(!empty($data[$i]['expects']))
                {
                    $str_expects         = preg_replace("/[\n\r\t\v]{1,}/", $seperator, trim($data[$i]['expects']));
                    $expects             = explode($seperator, $str_expects);
                    $data[$i]['expects'] = $expects;
                    $expectsCount        = count($expects);
                }
                $data[$i]['total'] = ($expectsCount > $stepsCount) ? $expectsCount : $stepsCount;

                if(!empty($data[$i]['pri']))
                {
                    $pri             = $data[$i]['pri'];
                    $data[$i]['pri'] = $importPriList[$pri];
                }
                $i++;
            }
        }

        $this->testcase->setMenu($this->products, $productID);
        $this->view->fields = $fields;
        $this->view->cases  = $data;
        $this->display();
    }
}
/* coship end */