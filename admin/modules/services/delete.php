<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa dịch vụ
echo 'ok';
$body = getBody();
if (!empty($body['id'])) {
    $serviceId = $body['id'];
    $serviceDetail = getRow("SELECT id FROM `services` WHERE id=$serviceId");
    if ($serviceDetail > 0) {


        $condition = "id=$serviceId";
        $deleteStatus = delete('services', $condition);
        if (!empty($deleteStatus)) {
            setFlashData('msg', 'Xóa dịch vụ thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa dịch vụ không thành công! Vui lòng thử lại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Dịch vụ không tồn tại trong hệ thống!');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=services');
