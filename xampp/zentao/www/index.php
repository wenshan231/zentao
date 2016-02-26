<?php
/**
 * The router file of ZenTaoPMS.
 *
 * All request should be routed by this router.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.php 4700 2013-05-02 06:04:02Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(0);

/* Start output buffer. */
ob_start();

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Log the time and define the run mode. */
$startTime = getTime();

/* Instance the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)));

/* Check the reqeust is getconfig or not. Check installed or not. */
if(isset($_GET['mode']) and $_GET['mode'] == 'getconfig') die($app->exportConfig());  // 
if(!isset($config->installed) or !$config->installed) die(header('location: install.php'));

/* Run the app. */
$common = $app->loadCommon();

/* Check for need upgrade. */
$config->installedVersion = $common->loadModel('setting')->getVersion();
if(!(!is_numeric($config->version{0}) and $config->version{0} != $config->installedVersion{0}) and version_compare($config->version, $config->installedVersion, '>')) die(header('location: upgrade.php'));

$app->parseRequest();
$common->checkPriv();
$app->loadModule();

/* Flush the buffer. */
echo removeUTF8Bom(ob_get_clean());
