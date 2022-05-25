
<?php
if (!defined('_INCODE')) die('access define...');
$body = getBody();

if (!empty($body['id'])) {
    $contactTypeId = $body['id'];
    $contactTypeDetail = firstRaw("SELECT * FROM `contact_type` WHERE id=$contactTypeId");
    if (!empty($contactTypeDetail)) {
        // echo '<pre>';
        // print_r($contactTypeDetail);
        // echo '</pre>';contact_type
        //loại bỏ thời gian tạo(create_at), và thời gian cập nhật(update_at)
        $contactTypeDetail['create_at'] = date('Y-m-d H:i:s');
        unset($contactTypeDetail['update_at']);
        unset($contactTypeDetail['id']);

        $duplicate = $contactTypeDetail['duplicate'];
        $duplicate++;

        $name = $contactTypeDetail['name'] . ' (' . $duplicate . ')';

        $contactTypeDetail['name'] = $name;

        $insertStatus = insert('contact_type', $contactTypeDetail);
        if ($insertStatus) {

            setFlashData('msg', 'Nhân bản phòng ban thành công!');
            setFlashData('msg_type', 'success');
            update('contact_type', ["duplicate" => $duplicate], "id=$contactTypeId");
            // redirect('admin?module=contact_type');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=contact_type'); //load lại trang
        }
    } else {
        setFlashData('msg', 'Phòng ban không tồn tại trong hệ thống!');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=contact_type');
