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