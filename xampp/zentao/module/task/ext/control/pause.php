<?php
/**
 * coship start
 * 2013-08-08 add by fujia
 * @modifier dengzhihui<dengzhihui.com@gmail.com>
*/
//include '../../control.php';
class task extends control
{
    /**
     * Pause a task.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function pause($taskID)
    {
        $this->commonActionEx($taskID);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->pause($taskID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'paused', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                //$this->sendmail($taskID, $actionID);
            }
            die(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $this->view->header->title = $this->view->project->name . $this->lang->colon .$this->lang->task->pause;
        $this->view->position[]    = $this->lang->task->pause;
        
        $this->display();
    }
    /**
     * Common actions of task module.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function commonActionEx($taskID)
    {
    		$this->loadModel('project');
    	
        $this->view->task    = $this->loadModel('task')->getByID($taskID);
        $this->view->project = $this->project->getById($this->view->task->project);
        $this->view->members = $this->project->getTeamMemberPairs($this->view->project->id ,'nodeleted');
        $this->view->users   = $this->loadModel('user')->getPairs('noletter'); 
        $this->view->actions = $this->loadModel('action')->getList('task', $taskID);

        /* Set menu. */
        $this->project->setMenu($this->project->getPairs(), $this->view->project->id);
        $this->view->position[] = html::a($this->createLink('project', 'browse', "project={$this->view->task->project}"), $this->view->project->name);

    }    
}
/* coship end */