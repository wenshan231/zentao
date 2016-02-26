<?php
$config->user = new stdclass();
$config->user->create = new stdclass();
$config->user->edit   = new stdclass();

/**
 * coship start
 * 2013-08-14 update by fujia
 */
$config->user->create->requiredFields = 'account,realname,password,password1,password2';
$config->user->edit->requiredFields   = 'account,realname';
/* coship end */
////$config->user->create->requiredFields = 'account,realname,password,password1,password2';
////$config->user->edit->requiredFields   = 'account,realname';
$config->user->failTimes   = 6;
$config->user->lockMinutes = 10;
$config->user->batchCreate = 10;