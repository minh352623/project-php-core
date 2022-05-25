<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa người dùng
echo 'ok';
$body = getBody();
if (!empty($body['id'])) {
    $groupId = $body['id'];
    $groupDetail = getRow("SELECT id FROM `groups` WHERE id=$groupId");
    if ($groupDetail > 0) {
        //kiểm tra xem trong nhóm còn người dùng hay không
        $userNumber = getRow("SELECT id FROM `users` WHERE group_id=$groupId");
        if ($userNumber > 0) {
            setFlashData('msg', 'Xóa nhóm người dùng không thành công. Trong nhóm vẫn còn ' . $userNumber . ' người dùng');
            setFlashData('msg_type', 'danger');
        } else {

            $condition = "id=$groupId";
            $deleteStatus = delete('groups', $condition);
            if (!empty($deleteStatus)) {
                setFlashData('msg', 'Xóa nhóm người dùng thành công!');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa nhóm người dùng không thành công! Vui lòng thử lại');
                setFlashData('msg_type', 'danger');
            }
        }

        // echo '<pre>';
        // print_r($groupDetail);
        // echo '</pre>';
    } else {
    }
} else {
    setFlashData('msg', 'Nhóm người dùng không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=groups');
