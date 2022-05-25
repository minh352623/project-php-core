<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa người dùng
echo 'ok';
$body = getBody();
if (!empty($body['id'])) {
    $contact_typeId = $body['id'];
    $contact_typeDetail = getRow("SELECT id FROM `contact_type` WHERE id=$contact_typeId");
    if ($contact_typeDetail > 0) {
        //kiểm tra xem trong danh mục còn dự án hay không
        $contactsNumber = getRow("SELECT id FROM `contacts` WHERE type_id=$contact_typeId");
        if ($contactsNumber > 0) {
            setFlashData('msg', 'Xóa phòng ban không thành công. Trong phòng ban vẫn còn ' . $contactsNumber . ' liên hệ');
            setFlashData('msg_type', 'danger');
        } else {

            $condition = "id=$contact_typeId";
            $deleteStatus = delete('contact_type', $condition);
            if (!empty($deleteStatus)) {
                setFlashData('msg', 'Xóa phòng ban thành công!');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa phòng ban không thành công! Vui lòng thử lại');
                setFlashData('msg_type', 'danger');
            }
        }

        // echo '<pre>';
        // print_r($contact_typeDetail);
        // echo '</pre>';
    } else {
    }
} else {
    setFlashData('msg', 'Phòng ban không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=contact_type');
