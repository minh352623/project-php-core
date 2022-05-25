<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa người dùng
$body = getBody();
if (!empty($body['id'])) {
    $contactsId = $body['id'];
    $contactsDetail = getRow("SELECT id FROM contacts WHERE id=$contactsId");
    if ($contactsDetail > 0) {


        $condition = "id=$contactsId";
        $deleteStatus = delete('contacts', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa liên hệ thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa liên hệ không thành công! Vui lòng thử lại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Liên hệ không tồn tại trong hệ thống!');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=contacts');
