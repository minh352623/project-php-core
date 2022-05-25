<?php

// echo 'quan li user';
// echo '<pre>';
// print_r($_GET);
// echo '</pre>';
session_start();
ob_start();
require_once '../config.php';
require_once '../includes/phpmailer/PHPMailer.php';
require_once '../includes/phpmailer/SMTP.php';
require_once '../includes/phpmailer/Exception.php';

require_once '../includes/functions.php';
require_once '../includes/permalink.php';

require_once '../includes/connect.php';
require_once '../includes/database.php';
require_once '../includes/session.php';


$module = _MODULE_DEFAULT_ADMIN;
$action = _ACTION_DEFAULT;
ini_set('display_errors', 0);
error_reporting(0);
// if (!getSession('reload')) {
//     removeSession('debug_error');
// }
set_exception_handler("setExceptionErrors");
set_error_handler("setErrorHanlder");
loadExceptionError();
if (!empty($_GET['module'])) {
    if (is_string($_GET['module'])) {
        $module = trim($_GET['module']);
    }
}

if (!empty($_GET['action'])) {
    if (is_string($_GET['action'])) {
        $action = trim($_GET['action']);
    }
}


$path = 'modules/' . $module . '/' . $action . '.php';
// echo $path;
if (file_exists($path)) {
    require_once $path;
} else {
    require_once 'modules/error/404.php';
}

// echo _WEB_PATH_ROOT;