<?php
/**
 * coship start
 * 公告文件
 * 2013-10-10 add file by fujia
 */
class notice extends control
{
    public $notices = array();

    /**
     * Construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        /* Load need modules. */
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->loadModel('action');
    }

    /**
     * The index page, locate to browse.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    /**
     * Browse notices.
     * 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($moduleID = 0, $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1, $browseType = 'byModule')
    {
        /* Set menu, save session. */
        $this->notice->setMenu($moduleID);
        $this->session->set('noticeList', $this->app->getURI(true));

        /* Set browseType.*/ 
        $browseType = strtolower($browseType);

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->noticeOrder ? $this->cookie->noticeOrder : 'id_desc';
        setcookie('noticeOrder', $orderBy, $this->config->cookieLife, $this->config->webRoot);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Get notices. */
        if($browseType == "bymodule")
        {
            $modules = ($moduleID > 0) ? $this->tree->getAllChildID($moduleID) : 0;
            $notices = $this->notice->getNotices($modules, $orderBy, $pager);
        }

        /* Get the tree menu. */
        $moduleTree = $this->tree->getTreeMenu(0, $viewType = 'notice', $startModuleID = 0, array('treeModel', 'createNoticeLink'));

        $this->view->title      = $this->lang->notice->index;
        $this->view->notices    = $notices;
        $this->view->moduleID   = $moduleID;
        $this->view->moduleTree = $moduleTree;
        $this->view->pager      = $pager;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType = $browseType;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->display();
    }

    /**
     * Create a notice. 
     * 
     * @access public
     * @return void
     */
    public function create($moduleID)
    {
        if(!empty($_POST))
        {
            $noticeID = $this->notice->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('notice', $noticeID, 'Created');

            $link = $this->createLink('notice', 'browse', "moduleID={$this->post->module}");
            die(js::locate($link, 'parent'));
        }

        /* Set menu. */
        $this->notice->setMenu($moduleID);

        $this->view->moduleID         = $moduleID;
        $this->view->title            = $this->lang->notice->index;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu(0, 'notice', $startModuleID = 0);
        $this->display();
    }

    /**
     * Edit a notice.
     * 
     * @param  int    $noticeID 
     * @access public
     * @return void
     */
    public function edit($noticeID)
    {
        if(!empty($_POST))
        {
            $changes  = $this->notice->update($noticeID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('notice', $noticeID);
            if(!empty($changes) or !empty($files))
            {
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('notice', $noticeID, 'Edited', $fileAction);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('notice', 'view', "noticeID=$noticeID"), 'parent'));
        }

        /* Get notice and set menu. */
        $notice = $this->notice->getById($noticeID);
        $this->notice->setMenu($notice->module);

        $this->view->title            = $this->lang->notice->index;
        $this->view->notice           = $notice;
        $this->view->users            = $this->user->getPairs('noclosed,nodeleted');
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu(0, 'notice', $startModuleID = 0);
        $this->display();
    }

    /**
     * View a notice.
     * 
     * @param  int    $noticeID 
     * @access public
     * @return void
     */
    public function view($noticeID)
    {
        /* Get notice. */
        $notice = $this->notice->getById($noticeID, true);
        if(!$notice) die(js::error($this->lang->notFound) . js::locate('back'));

        /* Set menu. */
        $this->notice->setMenu($notice->module);

        $this->view->notice     = $notice;
        $this->view->actions    = $this->loadModel('action')->getList('notice', $noticeID);
        $this->view->users      = $this->user->getPairs('noclosed,nodeleted,noletter');
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('notice', $noticeID);
        $this->view->keTableCSS = $this->notice->extractKETableCSS($notice->content);
        $this->display();
    }

    /**
     * Delete a notice.
     * 
     * @param  int    $noticeID 
     * @param  string $confirm    yes|no
     * @access public
     * @return void
     */
    public function delete($noticeID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->notice->confirmDelete, inlink('delete', "noticeID=$noticeID&confirm=yes")));
        }
        else
        {
            $this->notice->delete(TABLE_NOTICE, $noticeID);
            die(js::locate($this->session->noticeList, 'parent'));
        }
    }

    /**
     * coship start
     * 2013-12-02 add by fujia
     * 闪光点链接。
     *
     * @access public
     * @return void
     */
    public function shining()
    {
        $visitor = $this->app->user->account;
        $logon_encode = base64_encode($visitor);
        $shining = $this->lang->notice->shining;
        $url = $config->webRoot . "RandM?logon_encode=$logon_encode";
        die(js::locate($url));
    }
    /* coship end */
}
