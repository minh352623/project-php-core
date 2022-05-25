<?php
if (!defined('_INCODE')) die('access define...');

//chứa các hàm liên quan đến thao tác session

//hàm gán session
function setSession($key, $value)
{
    if (!empty(session_id())) {

        $_SESSION[$key] = $value;
        return true;
    }
    return false;
}

//hàm đọc session
function getSession($key = '')
{
    if (empty($key)) {
        return $_SESSION;
    } else {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
    return false;
}

//hàm xóa session

function removeSession($key = '')
{
    if (empty($key)) {
        session_destroy();
        return true;
    } else {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
    }
    return false;
}


//hàm gán flash data 

function setFlashData($key, $value)
{
    $key = 'flash_' . $key;
    return setSession($key, $value);
}

//hàm đọc flash data
function getFlashData($key)
{
    $key = 'flash_' . $key;
    $data = getSession($key);
    removeSession($key);
    return $data;
}
