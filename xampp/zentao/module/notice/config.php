<?php
global $lang;

$config->notice = new stdclass();
$config->notice->create    = new stdclass();
$config->notice->edit      = new stdclass();

$config->notice->create->requiredFields = 'title';
$config->notice->edit->requiredFields   = 'title';

$config->notice->editor = new stdclass();
$config->notice->editor->create = array('id' => 'content', 'tools' => 'fullTools');
$config->notice->editor->edit   = array('id' => 'content,comment', 'tools' => 'fullTools');
