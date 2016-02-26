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
    public function todocalendar()
    {
        $this->display();
    }
    }
?>