<?php
helper::import('C:\xampp\zentao\module\testcase\model.php');
class exttestcaseModel extends testcaseModel 
{
/**
     * coship start
     * 2013-09-05 add by fujia
     * 测试用例导入excel
     */
    public function import($productID = 0, $moduleID = 0)
    {
        $now   = helper::now();
        $cases = fixer::input('post')->get();
        $totalNum = count($cases->title);
        for($i = 0; $i < $totalNum; $i++)
        {
            if($cases->type[$i] != '' and $cases->title[$i] != '')
            {
                $data[$i] = new stdclass();
                $data[$i]->product      = $productID;
                $data[$i]->module       = $moduleID;
                $data[$i]->type         = $cases->type[$i] == 'same' ? ($i == 0 ? '' : $data[$i-1]->type) : $cases->type[$i];
                $data[$i]->title        = htmlspecialchars($cases->title[$i]);
                $data[$i]->pri          = $cases->pri[$i];
                $data[$i]->precondition = htmlspecialchars($cases->precondition[$i]);
                $data[$i]->openedBy     = $this->app->user->account;
                $data[$i]->openedDate   = $now;
                $data[$i]->status       = 'normal';
                $data[$i]->version      = 1;
                /* 可选导入的测试用例编号和测试项。 */
                if(isset($cases->caseCode[$i]))
                {
                    $data[$i]->caseCode     = $cases->caseCode[$i];
                }
                if(isset($cases->testItem[$i]))
                {
                $data[$i]->testItem     = $cases->testItem[$i];
                }

                $this->dao->insert(TABLE_CASE)->data($data[$i])
                    ->autoCheck()
                    ->batchCheck($this->config->testcase->create->requiredFields, 'notempty')
                    ->exec();

                if(dao::isError()) 
                {
                    echo js::error(dao::getError());
                    die(js::reload('parent'));
                }

                /* 步骤及预期结果。 */
                $caseID = $this->dao->lastInsertID();
                for($tmp = 0; $tmp < (int)($cases->total[$i]); $tmp++)
                {
                    if(empty($cases->steps[$i][$tmp]) && empty($cases->expects[$i][$tmp])) continue;
                    $step->case    = $caseID;
                    $step->version = 1;
                    $step->desc    = empty($cases->steps[$i][$tmp]) ? '' : htmlspecialchars($cases->steps[$i][$tmp]);
                    $step->expect  = empty($cases->expects[$i][$tmp]) ? '' : htmlspecialchars($cases->expects[$i][$tmp]);
                    $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
                }
                if(dao::isError()) die(js::error(dao::getError()));
                $actionID = $this->loadModel('action')->create('case', $caseID, 'Imported');
            }
            else
            {
                //unset($cases->module[$i]);
                unset($cases->type[$i]);
                unset($cases->title[$i]);
            }
        }
    }
    /* coship end */
//**//
}