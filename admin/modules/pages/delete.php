<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa người dùng
$body = getBody();
if (!empty($body['id'])) {
    $pageId = $body['id'];
    $pageDetail = getRow("SELECT id FROM `pages` WHERE id=$pageId");
    if ($pageDetail > 0) {


        $condition = "id=$pageId";
        $deleteStatus = delete('pages', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa trang thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa trang không thành công! Vui lòng thử lại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Trang không tồn tại trong hệ thống!');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=pages');
