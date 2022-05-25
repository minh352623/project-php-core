<?php
if (!defined('_INCODE')) die('access define...');

//fiel này chứa chức năng đăng xuất
if (isLogin()) {
    $token = getSession('login_token');
    delete('login_token', "token='$token'");
    removeSession('login_token');
    redirect('/admin?module=auth&action=login');
}
