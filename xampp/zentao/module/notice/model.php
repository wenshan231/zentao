<?php
/**
 * coship start
 * 公告文件
 * 2013-10-10 add file by fujia
 */
class noticeModel extends model
{
    /**
     * Set menu.
     * 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function setMenu($moduleID = 0)
    {
        foreach($this->lang->notice->menu as $key => $menu)
        {
            common::setMenuVars($this->lang->notice->menu, $key,  $moduleID);
        }
    }

    /**
     * Get notices.
     * 
     * @param  int          $module 
     * @param  string       $orderBy 
     * @param  object       $pager 
     * @access public
     * @return void
     */
    public function getNotices($module, $orderBy, $pager)
    {
        $datas = $this->dao->select('*')->from(TABLE_NOTICE)
            ->where('deleted')->eq(0)
            ->beginIF($module)->andWhere('module')->in($module)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
        if(!$datas) $datas = array();
        return $datas;
    }

    /**
     * Create a notice.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $now    = helper::now();
        $notice = fixer::input('post')
            ->add('addedBy', $this->app->user->account)
            ->add('addedDate', $now)
            ->setDefault('module', 0)
            ->specialChars('title')
            ->cleanInt('module')
            ->remove('files, labels')
            ->get();
        $condition = "module = $notice->module";
        $this->dao->insert(TABLE_NOTICE)
            ->data($notice)
            ->autoCheck()
            ->batchCheck($this->config->notice->create->requiredFields, 'notempty')
            ->check('title', 'unique', $condition)
            ->exec();
        if(!dao::isError())
        {
            $noticeID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('notice', $noticeID);
            return $noticeID;
        }
        return false;
    }

    /**
     * Update a notice.
     * 
     * @param  int    $noticeID 
     * @access public
     * @return void
     */
    public function update($noticeID)
    {
        $oldnotice = $this->getById($noticeID);
        $now = helper::now();
        $notice = fixer::input('post')
            ->cleanInt('module')
            ->setDefault('module', 0)
            ->specialChars('title')
            ->remove('files, labels')
            ->add('editedBy',   $this->app->user->account)
            ->add('editedDate', $now)
            ->get();

        $condition = "module = $notice->module AND id != $noticeID";
        $this->dao->update(TABLE_NOTICE)->data($notice)
            ->autoCheck()
            ->batchCheck($this->config->notice->edit->requiredFields, 'notempty')
            ->check('title', 'unique', $condition)
            ->where('id')->eq((int)$noticeID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldnotice, $notice);
    }

    /**
     * Get notice info by id.
     * 
     * @param  int    $noticeID 
     * @param  bool   $setImgSize 
     * @access public
     * @return void
     */
    public function getById($noticeID, $setImgSize = false)
    {
        $notice = $this->dao->select('*')
            ->from(TABLE_NOTICE)
            ->where('id')->eq((int)$noticeID)
            ->fetch();
        if(!$notice) return false;
        if($setImgSize) $notice->content = $this->loadModel('file')->setImgSize($notice->content);
        $notice->files = $this->loadModel('file')->getByObject('notice', $noticeID);

        $notice->moduleName  = '';
        if($notice->module)  $notice->moduleName  = $this->dao->findByID($notice->module)->from(TABLE_MODULE)->fetch('name');
        return $notice;
    }

    /**
     * Extract css styles for tables created in kindeditor.
     *
     * Like this: <table class="ke-table1" style="width:100%;" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
     * 
     * @param  string    $content 
     * @access public
     * @return void
     */
    public function extractKETableCSS($content)
    {
        $css = '';
        $rule = '/<table class="ke(.*)" .*/';
        if(preg_match_all($rule, $content, $results))
        {
            foreach($results[0] as $tableLine)
            {
                $attributes = explode(' ', str_replace('"', '', $tableLine));
                foreach($attributes as $attribute)
                {
                    if(strpos($attribute, '=') === false) continue;
                    list($attributeName, $attributeValue) = explode('=', $attribute);
                    $$attributeName = trim(str_replace('>', '', $attributeValue));
                }

                if(!isset($class)) continue;
                $className   = $class;
                $borderSize  = isset($border)      ? $border . 'px' : '1px';
                $borderColor = isset($bordercolor) ? $bordercolor : 'gray';
                $borderStyle = "{border:$borderSize $borderColor solid}\n";
                $css .= ".$className$borderStyle";
                $css .= ".$className td$borderStyle";
            }
        }
        return $css;
    }
}
