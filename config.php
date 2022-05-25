<?php
date_default_timezone_set(
    'Asia/Ho_Chi_Minh'
);
//file nàu chứa các hằng số cấu hình
//thiết lập hằng số cho client
const _MODULE_DEFAULT = 'home'; //module mặc định
const _ACTION_DEFAULT = 'lists'; //action dèault
//thiết lập hằng số cho client
const _MODULE_DEFAULT_ADMIN = 'dashboard';



const _INCODE = true; //ngăn chặn hành vi truy cập trực tiếp vào file


//thiết lập host
define('_WEB_HOST_ROOT', 'http://' . $_SERVER['HTTP_HOST'] . '/learn_php_co_ban/module6/radix'); //địa chỉ trang chủ

define('_WEB_HOST_TEMPLATE', _WEB_HOST_ROOT . '/templates/client');

define('_WEB_HOST_ROOT_ADMIN', _WEB_HOST_ROOT . '/admin');
define('_WEB_HOST_ADMIN_TEMPLATE', _WEB_HOST_ROOT . '/templates/admin');

//thiết lập path

define('_WEB_PATH_ROOT', __DIR__);
define('_WEB_PATH_TEMPLATE', __DIR__ . '/templates');

//thiết lập kết nối database
//thông tin kết nối
const _HOST = 'localhost';
const _USER = 'root';
const _PASS = '';
const _DB = 'phponline_radix';
const _DRIVER = 'mysql';

//thiết lập đebug

const _DEBUG = false;
//thiết lập số lượng bản ghi trên 1 trang
const _PER_PAGE = 3;
