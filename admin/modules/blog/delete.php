<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa người dùng
$body = getBody();
if (!empty($body['id'])) {
    $blogId = $body['id'];
    $blogDetail = getRow("SELECT id FROM blog WHERE id=$blogId");
    if ($blogDetail > 0) {


        $condition = "id=$blogId";
        $deleteStatus = delete('blog', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa blog thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa blog không thành công! Vui lòng thử lại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Blog không tồn tại trong hệ thống!');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=blog');
