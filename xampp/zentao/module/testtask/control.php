<?php
/**
 * The control file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: control.php 4686 2013-04-27 06:43:11Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class testtask extends control
{
    public $products = array();

    /**
     * Construct function, load product module, assign products to view auto.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->view->products = $this->products = $this->product->getPairs();
    }

    /**
     * Index page, header to browse.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('testtask', 'browse'));
    }

    /**
     * Browse test tasks. 
     * 
     * @param  int    $productID 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($productID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $this->session->set('testtaskList', $this->app->getURI(true));

        /* Set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        $this->testtask->setMenu($this->products, $productID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title       = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]  = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]  = $this->lang->testtask->common;
        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->tasks       = $this->testtask->getProductTasks($productID);
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');

        $this->display();
    }

    /**
     * Create a test task.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function create($productID, $projectID = 0, $build = 0)
    {
        if(!empty($_POST))
        {
            $taskID = $this->testtask->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('testtask', $taskID, 'opened');
            if($this->post->owner)
            {
                $this->sendmail($taskID, $actionID, 'opened');
            }
            die(js::locate($this->createLink('testtask', 'browse', "productID=$productID"), 'parent'));
        }

        /* Create testtask from build of project.*/
        if($projectID != 0 and $build != 0)
        {
            $products = $this->dao->select('t2.id, t2.name')
                ->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')
                ->on('t1.product = t2.id')
                ->where('t1.project')->eq($projectID)
                ->fetchPairs('id');

            foreach($products as $key => $value)
            {
                $productID = $key;
                break;
            }

            $projects = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetchPairs('id');
            $builds   = $this->dao->select('id, name')->from(TABLE_BUILD)->where('id')->eq($build)->fetchPairs('id');
        }

        /* Create testtask from testtask of project.*/
        if($projectID != 0 and $build == 0)
        {
            $products = $this->dao->select('t2.id, t2.name')
                ->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')
                ->on('t1.product = t2.id')
                ->where('t1.project')->eq($projectID)
                ->fetchPairs('id');

            foreach($products as $key => $value)
            {
                $productID = $key;
                break;
            }

            $projects = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetchPairs('id');
            $builds   = $this->dao->select('id, name')->from(TABLE_BUILD)->where('project')->eq($projectID)->fetchPairs('id');
            $builds   = array('trunk' => 'Trunk') + $builds;
        }

        /* Create testtask from testtask of test.*/
        if($projectID == 0)
        {
            $projects = $this->product->getProjectPairs($productID, $params = 'nodeleted');
            $builds   = $this->loadModel('build')->getProductBuildPairs($productID);
        }

        /* Set menu. */
        $productID  = $this->product->saveState($productID, $this->products);
        $this->testtask->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->create;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->create;

        if($projectID != 0) 
        {
            $this->view->products  = $products;
            $this->view->projectID = $projectID;
        }
        $this->view->projects  = $projects;
        $this->view->productID = $productID;
        $this->view->builds    = $builds; 
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|nodeleted|qdfirst');

        $this->display();
    }

    /**
     * View a test task.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function view($taskID)
    {
        /* Get test task, and set menu. */
        $task = $this->testtask->getById($taskID, true);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));
        $productID = $task->product;
        $this->testtask->setMenu($this->products, $productID);

        $this->view->title      = "TASK #$task->id $task->name/" . $this->products[$productID];
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->view;

        $this->view->productID = $productID;
        $this->view->task      = $task;
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions   = $this->loadModel('action')->getList('testtask', $taskID);

        $this->display();
    }

    /**
     * Browse cases of a test task.
     * 
     * @param  string $taskID 
     * @param  string $browseType  bymodule|all|assignedtome
     * @param  int    $param 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function cases($taskID, $browseType = 'byModule', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save the session. */
        $this->app->loadLang('testcase');
        $this->session->set('caseList', $this->app->getURI(true));

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        /* Set the browseType and moduleID. */
        $browseType = strtolower($browseType);
        $moduleID  = ($browseType == 'bymodule') ? (int)$param : 0;

        /* Get task and product info, set menu. */
        $task = $this->testtask->getById($taskID);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));
        $productID = $task->product;
        $this->testtask->setMenu($this->products, $productID);
        if($browseType == 'bymodule' or $browseType == 'all')
        {
            $modules = '';
            if($moduleID) $modules = $this->loadModel('tree')->getAllChildID($moduleID);
            $this->view->runs      = $this->testtask->getRuns($taskID, $modules, $orderBy, $pager);
        }
        elseif($browseType == 'assignedtome')
        {
            $this->view->runs = $this->testtask->getUserRuns($taskID, $this->session->user->account, $orderBy, $pager);
        }
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'testcase', false);

        /* Save testcaseIDs session for get the pre and next testcase. */
        $testcaseIDs = '';
        foreach($this->view->runs as $run) $testcaseIDs .= ',' . $run->case;
        $this->session->set('testcaseIDs', $testcaseIDs . ',');

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->cases;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->cases;

        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->task        = $task;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed,nodeleted,qafirst');
        /**
         * coship start
         * 2013-11-04 add by fujia
         */
        $this->view->moduleTree    = $this->loadModel('tree')->getCaseTreeMenu($productID, $taskID);
        /* coship end */
        ////$this->view->moduleTree  = $this->loadModel('tree')->getTreeMenu($productID, $viewType = 'case', $startModuleID = 0, array('treeModel', 'createTestTaskLink'), $extra = $taskID);
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->taskID      = $taskID;
        $this->view->moduleID    = $moduleID;
        $this->view->treeClass   = $browseType == 'bymodule' ? '' : 'hidden';
        $this->view->pager       = $pager;

        $this->display();
    }

    /**
     * Edit a test task.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function edit($taskID)
    {
        if(!empty($_POST))
        {
            $changes = $this->testtask->update($taskID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'edited');
                $this->action->logHistory($actionID, $changes);

                /* send mail.*/
                $this->sendmail($taskID, $actionID, 'edited');
            }
            die(js::locate(inlink('view', "taskID=$taskID"), 'parent'));
        }

        /* Get task info. */
        $task      = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($task->product, $this->products);

        /* Set menu. */
        $this->testtask->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->edit;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->edit;

        $this->view->task      = $task;
        $this->view->projects  = $this->product->getProjectPairs($productID);
        $this->view->builds    = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->users     = $this->loadModel('user')->getPairs();

        $this->display();
    }

    /**
     * Start testtask.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function start($taskID)
    {
        $actions  = $this->loadModel('action')->getList('testtask', $taskID);

        if(!empty($_POST))
        {
            $changes = $this->testtask->start($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('testtask', $taskID, 'Started', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('testtask', 'view', "taskID=$taskID"), 'parent'));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->testtask->setMenu($this->products, $productID);

        $this->view->testtask   = $testtask;
        $this->view->title      = $testtask->name . $this->lang->colon . $this->lang->testtask->start;
        $this->view->position[] = $this->lang->testtask->start;
        $this->view->actions    = $actions;
        $this->display();
    }

    /**
     * Close testtask.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function close($taskID)
    {
        $actions  = $this->loadModel('action')->getList('testtask', $taskID);

        if(!empty($_POST))
        {
            $changes = $this->testtask->close($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('testtask', $taskID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('testtask', 'view', "taskID=$taskID"), 'parent'));
        }

        /* Get task info. */
        $testtask  = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($testtask->product, $this->products);

        /* Set menu. */
        $this->testtask->setMenu($this->products, $productID);

        $this->view->testtask   = $this->testtask->getById($taskID);
        $this->view->title      = $testtask->name . $this->lang->colon . $this->lang->close;
        $this->view->position[] = $this->lang->close;
        $this->view->actions    = $actions;
        $this->display();
    }

    /**
     * Delete a test task.
     * 
     * @param  int    $taskID 
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($taskID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->testtask->confirmDelete, inlink('delete', "taskID=$taskID&confirm=yes")));
        }
        else
        {
            $task = $this->testtask->getByID($taskID);
            $this->testtask->delete(TABLE_TESTTASK, $taskID);
            die(js::locate(inlink('browse', "product=$task->product"), 'parent'));
        }
    }

    /**
     * Link cases to a test task.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function linkCase($taskID, $param = 'all', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!empty($_POST))
        {
            $this->testtask->linkCase($taskID);
            $this->locate(inlink('cases', "taskID=$taskID"));
        }

        /* Save session. */
        $this->session->set('caseList', $this->app->getURI(true));

        /* Get task and product id. */
        $task      = $this->testtask->getById($taskID);
        $productID = $this->product->saveState($task->product, $this->products);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->loadModel('testcase');
        $this->config->testcase->search['params']['product']['values']= array($productID => $this->products[$productID], 'all' => $this->lang->testcase->allProduct);
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case');
        $this->config->testcase->search['actionURL'] = inlink('linkcase', "taskID=$taskID");
        $this->loadModel('search')->setSearchParams($this->config->testcase->search);

        /* Save session. */
        $this->testtask->setMenu($this->products, $productID);

        $this->view->title      = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->linkCase;
        $this->view->position[] = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[] = $this->lang->testtask->linkCase;

        /* Get cases. */
        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        $query = str_replace("`product` = 'all'", '1', $this->session->testcaseQuery); // If search all product, replace product = all to 1=1
        $linkedCases = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs('case');
        if($param == 'all')
        {
            $cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
                ->andWhere('product')->eq($productID)
                ->andWhere('id')->notIN($linkedCases)
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
        }
        if($param == 'bystory')
        {
            $stories = $this->dao->select('stories')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch('stories');

            $cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
                ->andWhere('product')->eq($productID)
                ->beginIF($linkedCases)->andWhere('id')->notIN($linkedCases)->fi()
                ->andWhere('story')->in($stories)
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
        }
        if($param == 'bybug')
        {
            $bugs  = $this->dao->select('bugs')->from(TABLE_BUILD)->where('id')->eq($task->build)->fetch('bugs');
            $cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
                ->andWhere('product')->eq($productID)
                ->beginIF($linkedCases)->andWhere('id')->notIN($linkedCases)->fi()
                ->andWhere('fromBug')->in($bugs)
                ->andWhere('deleted')->eq(0)
                ->orderBy('id desc')
                ->page($pager)
                ->fetchAll();
        }
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->cases   = $cases;
        $this->view->taskID  = $taskID;
        $this->view->pager   = $pager;

        $this->display();
    }

    /**
     * Remove a case from test task.
     * 
     * @param  int    $rowID 
     * @access public
     * @return void
     */
    public function unlinkCase($rowID)
    {
        $this->dao->delete()->from(TABLE_TESTRUN)->where('id')->eq((int)$rowID)->exec();
        die(js::reload('parent'));
    }

    /**
     * Run case.
     * 
     * @param  int    $runID 
     * @param  String $extras   others params, forexample, caseID=10, version=3
     * @access public
     * @return void
     */
    public function runCase($runID, $caseID = 0, $version = 0)
    {
        $preAndNext = $this->loadModel('common')->getPreAndNextObject('testcase', $caseID);
        if(!empty($_POST))
        {
            $this->testtask->createResult($runID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($preAndNext->next)
            {
                $nextRunID   = $runID ? $preAndNext->next->id : 0;
                $nextCaseID  = $runID ? $preAndNext->next->case : $preAndNext->next->id;
                $nextVersion = $preAndNext->next->version;
                die(js::locate(inlink('runCase', "runID=$nextRunID&caseID=$nextCaseID&version=$nextVersion")));
            }
            else
            {
                echo js::reload('parent');
                die(js::closeWindow());
            }
        }

        if(!$caseID) $run = $this->testtask->getRunById($runID);
        if($caseID)
        {
            $run = new stdclass();
            $run->case = $this->loadModel('testcase')->getById($caseID, $version);
        }

        $preCase  = '';
        $nextCase = '';
        $caseID   = $caseID ? $caseID : $run->case->id;
        if($preAndNext->pre)
        {
            $preCase['runID']   = $runID ? $preAndNext->pre->id : 0;
            $preCase['caseID']  = $runID ? $preAndNext->pre->case : $preAndNext->pre->id;
            $preCase['version'] = $preAndNext->pre->version;
        }
        if($preAndNext->next)
        {
            $nextCase['runID']   = $runID ? $preAndNext->next->id : 0;
            $nextCase['caseID']  = $runID ? $preAndNext->next->case : $preAndNext->next->id;
            $nextCase['version'] = $preAndNext->next->version;
        }
        
        $this->view->run      = $run;
        $this->view->preCase  = $preCase;
        $this->view->nextCase = $nextCase;

        die($this->display());
    }

    /**
     * Batch run case.
     * 
     * @param  int    $productID 
     * @param  string $orderBy 
     * @param  string $from 
     * @access public
     * @return void
     */
    public function batchRun($productID, $orderBy = 'id_desc', $from = 'testcase')
    {
        if(isset($_POST['caseIDList']))
        {
            if($from == 'testcase') $this->view->cases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($this->post->caseIDList)->orderBy($orderBy)->fetchAll('id');
            if($from == 'testtask')
            {
                $this->view->cases = $this->dao->select('t1.*,t2.lastRunResult')->from(TABLE_CASE)->alias('t1')
                    ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.id = t2.case')
                    ->where('t1.id')->in($this->post->caseIDList)
                    ->orderBy($orderBy)
                    ->fetchAll('id');
            }
        }
        else
        {
            $this->testtask->batchRun($from);
            $url = $this->session->caseList ? $this->session->caseList : $this->createLink('testcase', 'browse', "productID=$productID");
            die(js::locate($url, 'parent'));
        }
        $this->app->loadLang('testcase');
        $this->testtask->setMenu($this->products, $productID);

        $resultList = $this->lang->testcase->resultList;
        unset($resultList['n/a']);

        $steps = $this->dao->select('t1.*')->from(TABLE_CASESTEP)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('t2.id')->in($this->post->caseIDList)
            ->andWhere('t1.version=t2.version')
            ->fetchGroup('case', 'id');

        $this->view->title            = $this->lang->testtask->batchRun;
        $this->view->position[]       = $this->lang->testtask->batchRun;
        $this->view->moduleOptionMenu = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0);
        $this->view->resultList       = $resultList; 
        $this->view->steps            = $steps;
        $this->display();
    }

    /**
     * View test results of a test run.
     * 
     * @param  int    $runID 
     * @param  int    $caseID 
     * @access public
     * @return void
     */
    public function results($runID, $caseID = 0, $version = 0)
    {
        if($caseID)
        {
            $case    = $this->loadModel('testcase')->getByID($caseID, $version);
            $results = $this->testtask->getResults(0, $caseID);
        }
        else
        {
            $case    = $this->testtask->getRunById($runID)->case;
            $results = $this->testtask->getResults($runID);

            $testtaskID = $this->dao->select('task')->from(TABLE_TESTRUN)->where('id')->eq($runID)->fetch('task', false);
            $testtask   = $this->dao->select('build, product')->from(TABLE_TESTTASK)->where('id')->eq($testtaskID)->fetch();
            $builds     = $this->loadModel('build')->getProductBuildPairs($testtask->product);
            $this->view->build = isset($builds[$testtask->build]) ? $builds[$testtask->build] : '';
        }

        $this->view->case    = $case;
        $this->view->results = $results;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed, noletter');

        die($this->display());
    }

    /**
     * Batch assign cases.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function batchAssign($taskID)
    {
        $this->dao->update(TABLE_TESTRUN)
            ->set('assignedTo')->eq($this->post->assignedTo)
            ->where('task')->eq((int)$taskID)
            ->andWhere('`case`')->in($this->post->caseIDList)
            ->exec();
        die(js::locate($this->session->caseList));
    }

    /**
     * Send mail. 
     * 
     * @param  int    $testtaskID 
     * @param  int    $actionID 
     * @param  string $action 
     * @access public
     * @return void
     */
    public function sendmail($testtaskID, $actionID, $actionType)
    {
        $testtask = $this->testtask->getByID($testtaskID);
        $action   = $this->action->getById($actionID);
        $users    = $this->loadModel('user')->getPairs('noletter');

        $this->view->testtask = $testtask;
        $this->view->action   = $action;
        $this->view->users    = $users;

        $mailContent = $this->parse($this->moduleName, 'sendmail');

        if($actionType == 'opened')
        {
            $mailTitle = sprintf($this->lang->testtask->mail->create->title, $this->app->user->realname, $testtaskID, $this->post->name);
        }
        else
        {
            $mailTitle = sprintf($this->lang->testtask->mail->edit->title, $this->app->user->realname, $testtaskID, $this->post->name);
        }
        $this->loadModel('mail')->send($this->post->owner, $mailTitle, $mailContent); 
        if($this->mail->isError()) echo js::error($this->mail->getError());
    }

    /**
     * coship start
     * 2013-10-23 add by fujia
     * 测试任务执行报告 
     * 
     * @param  int    $productID 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function runReport($productID, $taskID)
    {
        $this->app->loadLang('testcase');
        $this->testtask->setMenu($this->products, $productID);

        $caseByModule     =$this->testtask->getLinkCasesTotal($taskID, 'module');
        $caseByAssignedTo =$this->testtask->getLinkCasesTotal($taskID, 'assignedTo');

        $this->view->position[]       = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]       = $this->lang->testcase->runReport;
        $this->view->taskID           = $taskID;
        $this->view->tasks            = $this->testtask->getProductTasks($productID);
        $this->view->caseByModule     = $caseByModule;
        $this->view->modules          = $this->loadModel('tree')->getOptionMenu($productID,'case');
        $this->view->caseByPri        = $this->testtask->getLinkCasesTotal($taskID, 'pri');
        $this->view->caseByAssignedTo = $caseByAssignedTo;
        $this->view->users            = $this->testtask->getUserPairs(array_keys($caseByAssignedTo));
        $this->view->taskCases        = $this->getCaseTotal($caseByModule);

        $this->display();
    }

    /**
     * 各状态关联用例数统计 
     * 2013-11-8 add by fujia
     * 
     * @param  array    $cases 
     * @access public
     * @return void
     */
    public function getCaseTotal($cases)
    {
        foreach($cases as $key => $value)
        {
            $total   = isset($total)   ? ($total + $value['total']) : $value['total'];
            $wait    = isset($wait)    ? ($wait + $value['waitTotal']) : $value['waitTotal'];
            $done    = isset($done)    ? ($done + $value['doneTotal']) : $value['doneTotal'];
            $pass    = isset($pass)    ? ($pass + $value['passTotal']) : $value['passTotal'];
            $fail    = isset($fail)    ? ($fail + $value['failTotal']) : $value['failTotal'];
            $blocked = isset($blocked) ? ($blocked + $value['blockedTotal']) : $value['blockedTotal'];
        }

        $casesTotal['total']        = isset($total)   ? $total   : 0;
        $casesTotal['waitTotal']    = isset($wait)    ? $wait    : 0;
        $casesTotal['doneTotal']    = isset($done)    ? $done    : 0;
        $casesTotal['passTotal']    = isset($pass)    ? $pass    : 0;
        $casesTotal['failTotal']    = isset($fail)    ? $fail    : 0;
        $casesTotal['blockedTotal'] = isset($blocked) ? $blocked : 0;

        return $casesTotal;
    }

    /**
     * 格式化百分比
     * 2013-11-8 add by fujia
     * 
     * @param  array  $part
     * @param  int    $total
     * @param  int    $prec
     * @access public
     * @return void
     */
    public function formatCaseRate($part, $total, $prec = 2)
    {
        if($total > 0)
        {
            $rate = round($part / $total * 100, $prec) . '%';
        }
        else
        {
            $rate = $this->lang->testcase->null;
        }

        return $rate;
    }
    /* coship end */
}