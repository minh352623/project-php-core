<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa người dùng
echo 'ok';
$body = getBody();
if (!empty($body['id'])) {
    $userId = $body['id'];
    $userDetail = getRow("SELECT id FROM users WHERE id=$userId");
    if ($userDetail > 0) {
        //thực hiện xóa
        //b1. xóa khoa ngoại bên loginToken trước
        $delateToken = delete("login_token", "user_id = $userId");
        if ($delateToken) {
            //xóa users
            $deleteUser = delete("users", "id = $userId");
            if ($deleteUser) {
                setFlashData('msg', 'Xóa người dùng thành công!');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Lỗi hệ thống vui lòng thử lại sau!');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Lỗi hệ thống vui lòng thử lại sau!');
            setFlashData('msg_type', 'danger');
        }
    } else {
    }
} else {
    setFlashData('msg', 'Người dùng không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=users');
