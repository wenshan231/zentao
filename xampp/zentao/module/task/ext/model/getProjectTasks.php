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
