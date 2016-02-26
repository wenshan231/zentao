<?php
helper::import('C:\xampp\zentao\module\task\model.php');
class exttaskModel extends taskModel 
{
/**
 * Get tasks of a project.
 * 
 * @param  int    $projectID 
 * @param  string $status       all|needConfirm|wait|doing|done|cancel
 * @param  string $type 
 * @param  object $pager 
 * @access public
 * @return array
 */

public function getProjectTasks($projectID, $type = 'all', $orderBy = 'status_asc, id_desc', $pager = null)
{
    $orderBy = str_replace('status', 'statusCustom', $orderBy);
    $type    = strtolower($type);
    $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
        ->from(TABLE_TASK)->alias('t1')
        ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
        ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
        ->where('t1.project')->eq((int)$projectID)
        ->andWhere('t1.deleted')->eq(0)
		  /**
		   *coship start
		   *2014-01-22 update by shukaiming
		   */
        ->beginIF($type == 'undone')->andWhere('t1.status')->in('wait,doing')->fi()
		   /* coship end */
        ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
        ->beginIF($type == 'assignedtome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
        ->beginIF($type == 'finishedbyme')->andWhere('t1.finishedby')->eq($this->app->user->account)->fi()
        /**
         * coship start
         * 2013-08-08 update by fujia
         */
        ->beginIF($type == 'delayed')->andWhere('deadline')->between('1970-1-1', helper::now())->andWhere('t1.status')->in('wait,doing,pause')->fi()
        /* coship end */
        ////->beginIF($type == 'delayed')->andWhere('deadline')->between('1970-1-1', helper::now())->andWhere('t1.status')->in('wait,doing')->fi()
        ->beginIF(strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
        ->orderBy($orderBy)
        ->page($pager)
	      ->fetchAll();

    $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', $type == 'needconfirm' ? false : true);

    if($tasks) return $this->processTasks($tasks);
    return array();
}
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
/**
 * coship start
 * 2013-08-08 add by fujia
 * Set the status field of a task.
 * 
 * @param  object $task 
 * @access private
 * @return void
 */
public function setStatusEx($task)
{
		$CUSTOM_STATUS_ORDER_EX = 'wait,doing,done,cancel,closed,pause';
    $task->statusCustom = strpos($CUSTOM_STATUS_ORDER_EX, $task->status) + 1;
}
/* coship end */
/**
 * Update a task.
 * 
 * @param  int    $taskID 
 * @access public
 * @return void
 */
public function update($taskID)
{
    /**
     * coship start
     * 2013-08-08 add by fujia
     */
    $CUSTOM_STATUS_ORDER_EX = 'wait,doing,done,cancel,closed,pause';
    /* coship end */
    $oldTask = $this->getById($taskID);
    $now     = helper::now();
    $task    = fixer::input('post')
        ->striptags('name')
        ->setDefault('story, estimate, left, consumed', 0)
        ->setDefault('deadline', '0000-00-00')
        ->setIF($this->post->story != false and $this->post->story != $oldTask->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))

        ->setIF($this->post->status == 'done', 'left', 0)
        ->setIF($this->post->status == 'done'   and !$this->post->finishedBy,   'finishedBy',   $this->app->user->account)
        ->setIF($this->post->status == 'done'   and !$this->post->finishedDate, 'finishedDate', $now)

        ->setIF($this->post->status == 'cancel' and !$this->post->canceledBy,   'canceledBy',   $this->app->user->account)
        ->setIF($this->post->status == 'cancel' and !$this->post->canceledDate, 'canceledDate', $now)
        ->setIF($this->post->status == 'cancel', 'assignedTo',   $oldTask->openedBy)
        ->setIF($this->post->status == 'cancel', 'assignedDate', $now)

        ->setIF($this->post->status == 'closed' and !$this->post->closedBy,     'closedBy',     $this->app->user->account)
        ->setIF($this->post->status == 'closed' and !$this->post->closedDate,   'closedDate',   $now)
        ->setIF($this->post->consumed > 0 and $this->post->left > 0 and $this->post->status == 'wait', 'status', 'doing')

        ->setIF($this->post->assignedTo != $oldTask->assignedTo, 'assignedDate', $now)

        ->setIF($this->post->status == 'wait' and $this->post->left == $oldTask->left and $this->post->consumed == 0, 'left', $this->post->estimate)

        ->add('lastEditedBy',   $this->app->user->account)
        ->add('lastEditedDate', $now)
        ->remove('comment,files,labels')
        ->get();
        /**
         * coship start
         * 2013-08-08 update by fujia
         */
        $task->statusCustom = strpos($CUSTOM_STATUS_ORDER_EX, $task->status) + 1;
        /* coship end */
        ////$task->statusCustom = strpos(self::CUSTOM_STATUS_ORDER, $task->status) + 1;

        if($task->consumed < $oldTask->consumed) 
        {
            die(js::error($this->lang->task->error->consumedSmall));
        }
        else if($task->consumed != $oldTask->consumed or $task->left != $oldTask->left)
        {
            $estimate = new stdClass();
            $estimate->consumed = $task->consumed - $oldTask->consumed;
            $estimate->left     = $task->left;
            $estimate->task     = $taskID;
            $estimate->account  = $this->app->user->account;
            $estimate->date     = helper::now();

            $this->dao->insert(TABLE_TASKESTIMATE)->data($estimate)
                ->autoCheck()
                ->exec();
        }

    $this->dao->update(TABLE_TASK)->data($task)
        ->autoCheck()
        ->batchCheckIF($task->status != 'cancel', $this->config->task->edit->requiredFields, 'notempty')

        ->checkIF($task->estimate != false, 'estimate', 'float')
        ->checkIF($task->left     != false, 'left',     'float')
        ->checkIF($task->consumed != false, 'consumed', 'float')
        /**
         * coship start
         * 2013-08-08 update by fujia
         */
        ->checkIF($task->left == 0 and $task->status != 'cancel' and $task->status != 'closed', 'status', 'equal', 'done')
        ->batchCheckIF($task->status == 'wait' or $task->status == 'doing' or $task->status == 'pause', 'finishedBy, finishedDate,canceledBy, canceledDate, closedBy, closedDate, closedReason', 'empty')
        /* coship end */
        ////->checkIF($task->status != 'wait' and $task->left == 0 and $task->status != 'cancel' and $task->status != 'closed', 'status', 'equal', 'done')
        ////->batchCheckIF($task->status == 'wait' or $task->status == 'doing', 'finishedBy, finishedDate,canceledBy, canceledDate, closedBy, closedDate, closedReason', 'empty')

        ->checkIF($task->status == 'done', 'consumed', 'notempty')
        ->checkIF($task->status == 'done' and $task->closedReason, 'closedReason', 'equal', 'done')
        ->batchCheckIF($task->status == 'done', 'canceledBy, canceledDate', 'empty')

        ->checkIF($task->status == 'closed', 'closedReason', 'notempty')
        ->batchCheckIF($task->closedReason == 'cancel', 'finishedBy, finishedDate', 'empty')
        ->where('id')->eq((int)$taskID)->exec();

    if($this->post->story != false) $this->loadModel('story')->setStage($this->post->story);
    if(!dao::isError()) return common::createChanges($oldTask, $task);
}
//**//
}