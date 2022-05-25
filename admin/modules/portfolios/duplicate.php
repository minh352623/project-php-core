
<?php
if (!defined('_INCODE')) die('access define...');
$body = getBody();

if (!empty($body['id'])) {
    $portfoliosId = $body['id'];
    $portfoliosDetail = firstRaw("SELECT * FROM `portfolios` WHERE id=$portfoliosId");
    if (!empty($portfoliosDetail)) {
        // echo '<pre>';
        // print_r($portfoliosDetail);
        // echo '</pre>';
        //loại bỏ thời gian tạo(create_at), và thời gian cập nhật(update_at)
        $portfoliosDetail['create_at'] = date('Y-m-d H:i:s');
        unset($portfoliosDetail['update_at']);
        unset($portfoliosDetail['id']);

        $duplicate = $portfoliosDetail['duplicate'];
        $duplicate++;

        $name = $portfoliosDetail['name'] . ' (' . $duplicate . ')';

        $portfoliosDetail['name'] = $name;

        $insertStatus = insert('portfolios', $portfoliosDetail);
        if ($insertStatus) {

            setFlashData('msg', 'Nhân bản dự án thành công!');
            setFlashData('msg_type', 'success');
            update('portfolios', ["duplicate" => $duplicate], "id=$portfoliosId");
            // redirect('admin?module=portfolios');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=portfolios&action=add'); //load lại trang
        }
    } else {
        setFlashData('msg', 'Dự án không tồn tại trong hệ thống!');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=portfolios');
