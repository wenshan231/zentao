<?php
helper::import('C:\xampp\zentao\module\report\model.php');
class extreportModel extends reportModel 
{
public function getSysURL()
{
    if($this->server->argv[1])
    {
        $url = parse_url(trim($this->server->argv[1]));
        $port = (empty($url['port']) or $url['port'] == 80) ? '' : $url['port'];
        $host = empty($port) ? $url['host'] : $url['host'] . ':' . $port;
        return $url['scheme'] . '://' . $host;
    }
    else
    {
        return common::getSysURL();
    }
}
public function getUserBugs()
{
    $bugs = $this->dao->select('t1.id, t1.title, t2.account as user')
        ->from(TABLE_BUG)->alias('t1')
        ->leftJoin(TABLE_USER)->alias('t2')
        ->on('t1.assignedTo = t2.account')
        ->where('t1.assignedTo')->ne('')
        ->andWhere('t1.assignedTo')->ne('closed')
        ->andWhere('t1.deleted')->eq(0)
        ->andWhere('t2.deleted')->eq(0)
        ->fetchGroup('user');
    return $bugs;
}
public function getUserTasks()
{
    $tasks = $this->dao->select('t1.id, t1.name, t2.account as user')
        ->from(TABLE_TASK)->alias('t1')
        ->leftJoin(TABLE_USER)->alias('t2')
        ->on('t1.assignedTo = t2.account')
        ->where('t1.assignedTo')->ne('')
        ->andWhere('t1.deleted')->eq(0)
        ->andWhere('t2.deleted')->eq(0)
        ->andWhere('t1.status')->eq('wait')
        ->orWhere('t1.status')->eq('doing')
        ->fetchGroup('user');

    return $tasks;
}
public function getUserTodos()
{
    $stmt = $this->dao->select('t1.*, t2.account as user')
        ->from(TABLE_TODO)->alias('t1')
        ->leftJoin(TABLE_USER)->alias('t2')
        ->on('t1.account = t2.account')
        ->where('t1.status')->eq('wait')
        ->orWhere('t1.status')->eq('doing')
        ->query();

    $todos = array();
    while($todo = $stmt->fetch())
    {
        if($todo->type == 'task') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
        if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
        $todos[$todo->user][] = $todo;
    }
    return $todos;
}
//**//
}