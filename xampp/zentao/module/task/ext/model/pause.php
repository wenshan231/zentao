/**
 * coship start
 * 2013-08-08 add by fujia
 * Pause a task.
 * 
 * @param  int      $taskID 
 * @access public
 * @return void
 */
public function pause($taskID)
{
    $oldTask = $this->getById($taskID);
    $now     = helper::now();
    $task = fixer::input('post')
        ->setDefault('status', 'pause')
        ->setDefault('lastEditedBy', $this->app->user->account)
        ->setDefault('lastEditedDate', $now) 
        ->remove('comment')->get();
    $this->setStatusEx($task);

    $this->dao->update(TABLE_TASK)->data($task)
        ->autoCheck()
        ->check('consumed,left', 'float')
        ->where('id')->eq((int)$taskID)->exec();

    //if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
    if(!dao::isError()) return common::createChanges($oldTask, $task);
}
/* coship end */