<?php
include '../../control.php';
class mymy extends my
{
     /**
     * View task results.
     * 
     * @param  int    $runID 
     * @param  int    $caseID 
     * @access public
     * @return void
     */
    public function ajaxGetTodo()
    {
        $stmt = $this->dao->select('id,date,name,begin,end')->from(TABLE_TODO)
                ->query();
        while($todo = $stmt->fetch())
        {
        	$eventsArray['id'] =  $todo->id;
        	$eventsArray['title'] = $todo->name.'('.$todo->begin.'-'.$todo->end.')'; 
        	$eventsArray['start'] = $todo->date;
        	$eventsArray['end'] = $todo->date;
        	$eventsArray['url'] = $this->createLink('todo', 'view', "id=$todo->id&from=my");
        	$events[] = $eventsArray; 
        }
        echo json_encode($events);
    }
    }
?>