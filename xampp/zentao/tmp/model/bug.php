<?php
helper::import('C:\xampp\zentao\module\bug\model.php');
class extbugModel extends bugModel 
{
/**
     * coship start
     * 2013-12-17 add by fujia
     * bug导入excel
     */
    public function import($productID, $moduleID = 0)
    {
        $now      = helper::now();
        $bugs     = fixer::input('post')->get();
        $totalNum = count($bugs->title);
        for($i = 0; $i < $totalNum; $i++)
        {
            $data[$i] = new stdclass();
            $data[$i]->product      = $productID;
            $data[$i]->module       = $moduleID;
            $data[$i]->project      = $bugs->project[$i];
            $data[$i]->openedBuild  = $bugs->openedBuild[$i];
            $data[$i]->title        = htmlspecialchars($bugs->title[$i]);
            $data[$i]->severity     = $bugs->severity[$i];
            $data[$i]->pri          = $bugs->pri[$i];
            $data[$i]->type         = $bugs->type[$i];
            $data[$i]->steps        = preg_replace("/[\n\r\t\v]{1,}/", "<br />", htmlspecialchars(trim($bugs->steps[$i])));
            $data[$i]->status       = $bugs->status[$i];
            $data[$i]->openedBy     = $bugs->openedBy[$i];
            $data[$i]->openedDate   = (empty($bugs->openedDate[$i]) || $bugs->openedDate[$i] > $now) ? $now : $bugs->openedDate[$i];
            $data[$i]->assignedTo   = $bugs->assignedTo[$i];
            $data[$i]->assignedDate = empty($data[$i]->assignedTo) ? '' : $now;

            $this->dao->insert(TABLE_BUG)->data($data[$i])
                ->autoCheck()
                ->batchCheck($this->config->bug->import->requiredFields, 'notempty')
                ->checkIF($data[$i]->openedDate != $now, 'openedDate', 'date')
                ->exec();

            if(dao::isError()) 
            {
                echo js::error(dao::getError());
                die(js::reload('parent'));
            }                
            $bugID    = $this->dao->lastInsertID();
            $actionID = $this->loadModel('action')->create('bug', $bugID, 'Imported');
        }
    }
    /* coship end */
//**//
}