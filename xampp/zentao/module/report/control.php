<?php
/**
 * The control file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: control.php 4622 2013-03-28 01:09:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class report extends control
{
    /**
     * The index of report, goto project deviation.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('productinfo')); 
    }
    
    /**
     * Project deviation report.
     * 
     * @access public
     * @return void
     */
    public function projectDeviation()
    {
        $this->view->title    = $this->lang->report->projectDeviation;
        $this->view->projects = $this->report->getProjects();
        $this->view->submenu  = 'project';
        $this->display();
    }

    /**
     * Product information report.
     * 
     * @access public
     * @return void
     */
    public function productInfo()
    {
        $this->app->loadLang('product');
        $this->app->loadLang('productplan');
        $this->app->loadLang('story');
        $this->view->title    = $this->lang->report->productInfo;
        $this->view->products = $this->report->getProducts();
        $this->view->users    = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->submenu  = 'product';
        $this->display();
    }

    /**
     * Bug summary report.
     * 
     * @param  int    $begin 
     * @param  int    $end 
     * @access public
     * @return void
     */
    public function bugSummary($begin = 0, $end = 0)
    {
        $this->app->loadLang('bug');
        if($begin == 0) 
        {
            $begin = date('Y-m-d', strtotime('last month'));
        }
        else
        {
            $begin = date('Y-m-d', strtotime($begin));
        }
        if($end == 0)
        {
            $end = date('Y-m-d', strtotime('now'));
        }
        else
        {
            $end = date('Y-m-d', strtotime($end));
        }
        $this->view->title   = $this->lang->report->bugSummary;
        $this->view->begin   = $begin;
        $this->view->end     = $end;
        $this->view->bugs    = $this->report->getBugs($begin, $end);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->view->submenu = 'test';
        $this->display(); 
    }

    /**
     * Bug assign report.
     * 
     * @access public
     * @return void
     */
    public function bugAssign()
    {
        $this->view->title   = $this->lang->report->bugAssign;
        $this->view->submenu = 'test';
        $this->view->assigns = $this->report->getBugAssign();
        $this->view->users   = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->display(); 
    }

    /**
     * Workload report.
     * 
     * @access public
     * @return void
     */
    public function workload()
    {
        $this->view->title    = $this->lang->report->workload;
        $this->view->workload = $this->report->getWorkload();
        $this->view->users    = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        $this->view->submenu  = 'staff';
        $this->display();
    }

    /**
     * Send daily reminder mail.
     * 
     * @access public
     * @return void
     */
    public function remind()
    {
        if($this->config->report->dailyreminder->bug)  $bugs  = $this->report->getUserBugs();
        if($this->config->report->dailyreminder->task) $tasks = $this->report->getUserTasks();
        if($this->config->report->dailyreminder->todo) $todos = $this->report->getUserTodos();
        
        $reminder = array();

        $users = array_unique(array_merge(array_keys($bugs), array_keys($tasks), array_keys($todos)));
        if(!empty($users)) foreach($users as $user) $reminder[$user] = new stdclass();

        if(!empty($bugs))  foreach($bugs as $user => $bug)   $reminder[$user]->bugs  = $bug;
        if(!empty($tasks)) foreach($tasks as $user => $task) $reminder[$user]->tasks = $task;
        if(!empty($todos)) foreach($todos as $user => $todo) $reminder[$user]->todos = $todo;

        $this->loadModel('mail');

        /* Check mail turnon.*/
        if(!$this->config->mail->turnon) die("You should turn on the Email feature first.\n");

        foreach($reminder as $user => $mail)
        {
            /* Get email content and title.*/
            $this->view->mail = $mail;
            $mailContent = $this->parse('report', 'dailyreminder');
            $mailTitle   = $this->lang->report->mailtitle->begin;
            $mailTitle  .= isset($mail->bugs)  ? sprintf($this->lang->report->mailtitle->bug,  count($mail->bugs))  : '';
            $mailTitle  .= isset($mail->tasks) ? sprintf($this->lang->report->mailtitle->task, count($mail->tasks)) : '';
            $mailTitle  .= isset($mail->todos) ? sprintf($this->lang->report->mailtitle->todo, count($mail->todos)) : '';
            $mailTitle   = rtrim($mailTitle, ',');
            $this->clear();
            
            /* Send email.*/
            echo date('Y-m-d H:i:s') . " sending to $user, ";
            $this->mail->send($user, $mailTitle, $mailContent, '', true);
            if($this->mail->isError())
            {
                echo "fail: \n" ;
                a($this->mail->getError());
            }
            echo "ok\n";
        }
    }

    /**
     * coship start
     * 每周统计
     * 2013-09-24 add by fujia
     */
    public function weeklyReport()
    {
        $datas['assignedTo'] = $this->report->getDataOfBugAssignedTo();
        $datas['openedBy']   = $this->report->getDataOfBugOpenedBy();

        $chart = $this->lang->report->chart;
        $XMLAssignedTo  = $this->report->createSingleXML($datas['assignedTo'], $chart->graph);
        $this->view->charts['assignedTo'] = $this->report->createJSChart($chart->swf, $XMLAssignedTo, $chart->width, $chart->height);
        $XMLOpenedBy  = $this->report->createSingleXML($datas['openedBy'], $chart->graph);
        $this->view->charts['openedBy'] = $this->report->createJSChart($chart->swf, $XMLOpenedBy, $chart->width, $chart->openedBy->height);
        $this->view->datas['assignedTo'] = $datas['assignedTo'];
        $this->view->datas['openedBy']   = $datas['openedBy'];

        $this->view->renderJS = $this->report->renderJsCharts(2);
        $this->view->submenu  = 'week';
        $this->display();
    }

    /**
     * coship start
     * 每周统计详情导出
     * 2013-10-08 add by fujia
     */
    public function export($exportType)
    {
        if($_POST)
        {
            if($exportType == 'assignedTo')
            {
                $assignedTo    = $this->report->getAssignedToPairs();
                $strAssignedTo = implode(array_keys($assignedTo), ',');
                $data          = $this->report->getBugsByAssignedTo($strAssignedTo);
            }
            elseif($exportType == 'openedBy')
            {
                $openedBy    = $this->report->getOpenedByPairs();
                $strOpenedBy = implode(array_keys($openedBy), ',');
                $data        = $this->report->getBugsByOpenedBy($strOpenedBy);
            }
            else
            {
                echo '<script type="text/javascript">alert("' . $this->lang->report->exportError . '");history.back(-1);</script>';
                exit();
            }

            $this->app->loadLang('bug');
            include_once '../../lib/phpexcel/PHPExcel/Writer/IWriter.php';
            include_once '../../lib/phpexcel/PHPExcel/Reader/Excel5.php';
            include_once '../../lib/phpexcel/PHPExcel.php';
            include_once '../../lib/phpexcel/PHPExcel/IOFactory.php';
            $obj_phpexcel = new PHPExcel();
            $owner        = ($exportType == 'assignedTo') ? $this->lang->bug->assignedTo : $this->lang->bug->openedBy;
            $user         = ($exportType == 'assignedTo') ? $this->lang->bug->openedBy : $this->lang->bug->assignedTo;
            $obj_phpexcel->getActiveSheet()->setCellValue('A1', $owner);
            $obj_phpexcel->getActiveSheet()->setCellValue('B1', $this->lang->bug->product);
            $obj_phpexcel->getActiveSheet()->setCellValue('C1', $this->lang->bug->id);
            $obj_phpexcel->getActiveSheet()->setCellValue('D1', $this->lang->bug->title);
            $obj_phpexcel->getActiveSheet()->setCellValue('E1', $this->lang->bug->severity);
            $obj_phpexcel->getActiveSheet()->setCellValue('F1', $this->lang->bug->type);
            $obj_phpexcel->getActiveSheet()->setCellValue('G1', $this->lang->bug->status);
            $obj_phpexcel->getActiveSheet()->setCellValue('H1', $this->lang->bug->confirmed);
            $obj_phpexcel->getActiveSheet()->setCellValue('I1', $user);

            if($data){
                $i =2;
                foreach ($data as $value)
                {
                    $owner     = ($exportType == 'assignedTo') ? $assignedTo[$value->assignedTo] : $openedBy[$value->openedBy];
                    $severity  = $this->lang->bug->severityList[$value->severity];
                    $type      = $this->lang->bug->typeList[$value->type];
                    $status    = $this->lang->bug->statusList[$value->status];
                    $confirmed = $this->lang->bug->confirmedList[$value->confirmed];
                    $user      = ($exportType == 'assignedTo') ? $value->openedBy : $value->assignedTo;

                    $obj_phpexcel->getActiveSheet()->setCellValue('A' . $i, $owner);
                    $obj_phpexcel->getActiveSheet()->setCellValue('B' . $i, $value->product);
                    $obj_phpexcel->getActiveSheet()->setCellValue('C' . $i, $value->id);
                    $obj_phpexcel->getActiveSheet()->setCellValue('D' . $i, $value->title);
                    $obj_phpexcel->getActiveSheet()->setCellValue('E' . $i, $severity);
                    $obj_phpexcel->getActiveSheet()->setCellValue('F' . $i, $type);
                    $obj_phpexcel->getActiveSheet()->setCellValue('G' . $i, $status);
                    $obj_phpexcel->getActiveSheet()->setCellValue('H' . $i, $confirmed);
                    $obj_phpexcel->getActiveSheet()->setCellValue('I' . $i, $user);
                    $i++;
                }
            }

            $obj_Writer = PHPExcel_IOFactory::createWriter($obj_phpexcel,'Excel5');
            $fileName = $_POST['fileName'] . '.xls';
            
            header("Content-Type: application/force-download"); 
            header("Content-Type: application/octet-stream"); 
            header("Content-Type: application/download"); 
            header('Content-Disposition:inline;filename="'.$fileName.'"'); 
            header("Content-Transfer-Encoding: binary"); 
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
            header("Pragma: no-cache"); 
            $obj_Writer->save('php://output');
        }
        $this->display();
    }

    /**
     * coship start
     * 每月产品质量分析
     * 2013-09-25 add by fujia
     */
    public function monthlyReport()
    {
        if(!empty($_POST))
        {
            $beginDate = $_POST['beginDate'];
            $endDate   = $_POST['endDate'];
            $productsProjects = $this->report->getDataOfProductsProjects($beginDate, $endDate);
        }
        else
        {
            $beginDate = date('Y-m-d', strtotime('last month'));
            $endDate   = date('Y-m-d', strtotime('now'));
        }

        $this->view->products  = empty($productsProjects) ? '' : $productsProjects;
        $this->view->submenu   = 'month';
        $this->view->beginDate = $beginDate;
        $this->view->endDate   = $endDate;
        $this->display();
    }

    /**
     * coship start
     * Bug环比数据
     * 2013-09-27 add by fujia
     */
    public function bugMonthlyReport()
    {
        $openedMonths   = $this->report->getBugMonths('openedDate');
        $resolvedMonths = $this->report->getBugMonths('resolvedDate');

        foreach($openedMonths as $value)
        {
            $datas[$value->month]['opened'] = $this->report->getOpenedBugs($value->month);
        }
        foreach($resolvedMonths as $value)
        {
            $datas[$value->month]['resolved'] = $this->report->getResolvedBugs($value->month);
        }

        $this->view->submenu = 'month';
        $this->view->datas   = $datas;
        $this->display();
    }
    /* coship end */
}
