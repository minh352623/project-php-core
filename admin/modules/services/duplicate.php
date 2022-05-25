
<?php
if (!defined('_INCODE')) die('access define...');
$body = getBody();

if (!empty($body['id'])) {
    $serviceId = $body['id'];
    $serviceDetail = firstRaw("SELECT * FROM `services` WHERE id=$serviceId");
    if (!empty($serviceDetail)) {
        // echo '<pre>';
        // print_r($serviceDetail);
        // echo '</pre>';
        //loại bỏ thời gian tạo(create_at), và thời gian cập nhật(update_at)
        $serviceDetail['create_at'] = date('Y-m-d H:i:s');
        unset($serviceDetail['update_at']);
        unset($serviceDetail['id']);

        $duplicate = $serviceDetail['duplicate'];
        $duplicate++;

        $name = $serviceDetail['name'] . ' (' . $duplicate . ')';

        $serviceDetail['name'] = $name;

        $insertStatus = insert('services', $serviceDetail);
        if ($insertStatus) {

            setFlashData('msg', 'Nhân bản dịch vụ thành công!');
            setFlashData('msg_type', 'success');
            update('services', ["duplicate" => $duplicate], "id=$serviceId");
            // redirect('admin?module=services');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=services&action=add'); //load lại trang
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
