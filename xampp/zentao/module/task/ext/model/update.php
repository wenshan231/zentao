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
