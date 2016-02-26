<?php
/**
 * coship start
 * 2013-12-17 add by liuzhiwei
 */
$lang->bug->importList = new stdclass();
$lang->bug->importList->severity['一般'] = 3;
$lang->bug->importList->severity['严重'] = 2;
$lang->bug->importList->severity['轻微'] = 4;
$lang->bug->importList->severity['建议'] = 5;
$lang->bug->importList->severity['致命'] = 1;

$lang->bug->importList->pri['中'] = 3;
$lang->bug->importList->pri['高'] = 2;
$lang->bug->importList->pri['低'] = 4;
$lang->bug->importList->pri['最高'] = 1;

$lang->bug->importList->type['UI页面显示'] = 'UI';
$lang->bug->importList->type['UE用户体验、易用性'] = 'UE';
$lang->bug->importList->type['FC功能问题'] = 'FC';
$lang->bug->importList->type['PF性能问题'] = 'performance';
$lang->bug->importList->type['IF接口问题'] = 'interface';
$lang->bug->importList->type['ST安全问题'] = 'security';
$lang->bug->importList->type['CK用户操作提示信息问题'] = 'CK';
$lang->bug->importList->type['BP程序打包问题'] = 'install';
$lang->bug->importList->type['DF设计问题'] = 'designdefect';
$lang->bug->importList->type['AL代码错误'] = 'codeerror';
$lang->bug->importList->type['RC与需求不符的问题'] = 'RC';
$lang->bug->importList->type['RRC新增需求或需求变更'] = 'newfeature';
$lang->bug->importList->type['设计变更'] = 'designchange';
$lang->bug->importList->type['配置相关'] = 'config';
$lang->bug->importList->type['标准规范'] = 'standard';
$lang->bug->importList->type['测试脚本'] = 'automation';
$lang->bug->importList->type['事务跟踪'] = 'trackthings';
$lang->bug->importList->type['其他'] = 'others';

$lang->bug->importList->status['激活']   = 'active';
$lang->bug->importList->status['已解决'] = 'resolved';
$lang->bug->importList->status['已关闭'] = 'closed';
/* coship end */