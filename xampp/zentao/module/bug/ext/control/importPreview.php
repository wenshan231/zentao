<?php
/**
 * coship start
 * 2013-11-27 add file by liuzhiwei
 * bug导入excel插件
*/
class bug extends control
{
    /**
     * 模板中的
     * 导入excel预览
     */
    public function importPreview($productID, $moduleID = 0, $projectID, $openedBuild)
    {
        $this->loadModel('product');
        $products = $this->product->getPairs();
        $users    = $this->loadModel("user")->getPairs('nodeleted');

        $data = array();
        if(!empty($_POST))
        {
            $bugID = $this->bug->import($productID, $moduleID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('bug', 'browse', "productID=$productID&browseType=byModule&moduleID=$moduleID"), 'parent'));
        }
        elseif($_COOKIE['bugFile'] && empty($_POST))
        {
            require_once '../../../../lib/phpexcel/PHPExcel.php';
            require_once '../../../../lib/phpexcel/PHPExcel/IOFactory.php';
            require_once '../../../../lib/phpexcel/PHPExcel/Reader/Excel5.php';
            require_once '../../../../lib/phpexcel/PHPExcel/Style/NumberFormat.php';

            $uploadFile = $_COOKIE['bugFile'];
            try
            {
                /* excel2007格式使用 excel2007 */
                $objReader   = PHPExcel_IOFactory::createReader('Excel5');
                $objPHPExcel = $objReader->load($uploadFile);
                $sheet       = $objPHPExcel->getSheet(0);
            }
            catch (Exception $e)
            {
                echo '<script type="text/javascript">alert("' . $this->lang->bug->confirmExcelType . '");history.back(-1);</script>';
                exit();
            }

            $fields = array('title', 'severity', 'pri', 'type', 'steps', 'status', 'openedBy', 'openedDate', 'assignedTo');
            $importList = $this->lang->bug->importList;

            /* excel总行数 */
            $highestRow    = $sheet->getHighestRow();
            /* excel总列数 */
            $highestColumn = 9;

            $i = 0;
            /* 读取excel进数组 */
            for ($row = 2;$row <= $highestRow;$row++) 
            {
                /* 测试用例标题不能为空。 */
                $title = trim($sheet->getCellByColumnAndRow(0, $row)->getValue());
                if(empty($title)) continue;
                for ($col = 0;$col < $highestColumn;$col++)
                {
                    $data[$i][$fields[$col]] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                }

                /* 标题 */
                if(!empty($data[$i]['title']))
                {
                    $data[$i]['title'] = preg_replace("/[\n\r\t\v]{1,}/", '', trim($data[$i]['title']));
                }

                /* 严重程度 */
                if(!isset($importList->severity[$data[$i]['severity']]))
                {
                    $data[$i]['severity'] = 3;
                }
                else
                {
                    $data[$i]['severity'] = $importList->severity[$data[$i]['severity']];
                }

                /* 优先级 */
                if(!isset($importList->pri[$data[$i]['pri']]))
                {
                    $data[$i]['pri'] = 1;
                }
                else
                {
                    $data[$i]['pri'] = $importList->pri[$data[$i]['pri']];
                }

                /* 类型 */
                if(!isset($importList->type[$data[$i]['type']]))
                {
                    $data[$i]['type'] = 'codeerror';
                }
                else
                {
                    $data[$i]['type'] = $importList->type[$data[$i]['type']];
                }

                /* 状态 */
                if(!isset($importList->status[$data[$i]['status']]))
                {
                    $data[$i]['status'] = 'active';
                }
                else
                {
                    $data[$i]['status'] = $importList->status[$data[$i]['status']];
                }

                /*创建者*/
                if(!empty($data[$i]['openedBy']))
                {
                    $openedBy     = $data[$i]['openedBy'];
                    foreach($users as $key=>$val)
                    {
                        if(stripos($val,$openedBy) !== false)
                        {
                            $data[$i]['openedBy'] = $key;
                        }
                    }
                    /* 创建日期 */
                    if(!empty($data[$i]['openedDate']) && is_numeric($data[$i]['openedDate']))
                    {
                        $value = $data[$i]['openedDate'];
                        if (preg_match('/^(\[\$[A-Z]*-[0-9A-F]*\])*[hmsdy]/i', $value))
                        {
                            $data[$i]['openedDate'] = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($value));
                        }
                        else
                        {
                            $data[$i]['openedDate'] = PHPExcel_Style_NumberFormat::toFormattedString($value, 'yyyy-mm-dd');
                        }
                    }
                    else
                    {
                        $data[$i]['openedDate'] = date('Y-m-d');
                    }
                }
                else
                {
                    $data[$i]['openedBy']   = $this->app->user->account;
                    $data[$i]['openedDate'] = date('Y-m-d');
                }

                /* 指派给 */
                if(!empty($data[$i]['assignedTo']))
                {
                    $assignedTo     = $data[$i]['assignedTo'];
                    foreach($users as $key=>$val)
                    {
                        if(stripos($val,$assignedTo) !== false)
                        {
                            $data[$i]['assignedTo'] = $key;
                        }
                    }
                }
                else
                {
                    $data[$i]['assignedTo'] = $this->app->user->account;
                }

                $i++;
            }
        }

        $this->bug->setMenu($products, $productID);
        $this->view->productID = $productID;
        $this->view->products  = $products;
        $this->view->projectID = $projectID;
        $this->view->projects  = $this->product->getProjectPairs($productID);
        $this->view->buildID   = $openedBuild;
        $this->view->builds    = $this->loadModel('build')->getProjectBuildPairs($projectID,$productID);
        $this->view->users     = $users;
        $this->view->fields    = $fields;
        $this->view->bugs      = $data;
        $this->display();
    }
}
/* coship end */