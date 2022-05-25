
<?php
if (!defined('_INCODE')) die('access define...');
$body = getBody();

if (!empty($body['id'])) {
    $pageId = $body['id'];
    $pageDetail = firstRaw("SELECT * FROM `pages` WHERE id=$pageId");
    if (!empty($pageDetail)) {
        // echo '<pre>';
        // print_r($pageDetail);
        // echo '</pre>';pages
        //loại bỏ thời gian tạo(create_at), và thời gian cập nhật(update_at)
        $pageDetail['create_at'] = date('Y-m-d H:i:s');
        unset($pageDetail['update_at']);
        unset($pageDetail['id']);

        $duplicate = $pageDetail['duplicate'];
        $duplicate++;

        $title = $pageDetail['title'] . ' (' . $duplicate . ')';

        $pageDetail['title'] = $title;

        $insertStatus = insert('pages', $pageDetail);
        if ($insertStatus) {

            setFlashData('msg', 'Nhân bản trang thành công!');
            setFlashData('msg_type', 'success');
            update('pages', ["duplicate" => $duplicate], "id=$pageId");
            // redirect('admin?module=pages');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=pages&action=add'); //load lại trang
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
