<?php
if(!defined('_INCODE')) die('access define...');

try{    
    
    //kiểm tra PDO đã bật chưa
    if(class_exists('PDO')){
        $dsn  = _DRIVER.':dbname='._DB.';host='._HOST;
        // echo $dsn;
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',// set utf-8
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //Tạo thông báo ra ngoại lệ khi gặp lỗi
        ];
        $conn = new PDO($dsn,_USER,_PASS,$options);

    }
}catch(Exception $e){
    //nếu kết nối thất bại sẽ chạy vào đây
    require_once 'modules/error/database.php';//import error
    die();

}