
/**
 * coship start
 * 2013-08-09 add by fujia
 * Get rasks can be imported.
 * 
 * @param  int    $projectID 
 * @access public
 * @return array
 * @modifier dengzhihui<dengzhihui.com@gmail.com>
 */
public function getTasks2Imported($projectID)
{
    $this->loadModel('task');
    $releatedProjects = $this->getRelatedProjects($projectID);
    if(!$releatedProjects) return array();
    $tasks = array();
    foreach($releatedProjects as $releatedProjectID => $releatedProjectName)
    {
        $projectTasks = $this->task->getProjectTasks($releatedProjectID, 'wait,doing,cancel,pause');
        if(!$projectTasks) continue;
        $tasks = array_merge($tasks, $projectTasks); 
    }
    return $tasks;
}
/* coship end */