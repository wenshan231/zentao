<?php
/**
 * coship start
 * 2013-08-08 add by fujia
 * @modifier dengzhihui<dengzhihui.com@gmail.com>
*/
include '../../control.php';
class taskex extends task
{
    /**
     * AJAX: return tasks of a user in html select. 
     * 
     * @param  string $account 
     * @param  string $status 
     * @access public
     * @return string
     */
    public function ajaxGetUserTasks($account = '', $status = 'wait,doing,pause')
    {
        if($account == '') $account = $this->app->user->account;
        $tasks = $this->task->getUserTaskPairs($account, $status);
        die(html::select('task', $tasks, '', 'class=select-1'));
    }
}
/* coship end */