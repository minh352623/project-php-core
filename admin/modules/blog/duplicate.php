
<?php
if (!defined('_INCODE')) die('access define...');
$body = getBody();

if (!empty($body['id'])) {
    $blogId = $body['id'];
    $blogDetail = firstRaw("SELECT * FROM `blog` WHERE id=$blogId");
    if (!empty($blogDetail)) {
        // echo '<pre>';
        // print_r($blogDetail);
        // echo '</pre>';blog
        //loại bỏ thời gian tạo(create_at), và thời gian cập nhật(update_at)
        $blogDetail['create_at'] = date('Y-m-d H:i:s');
        unset($blogDetail['update_at']);
        unset($blogDetail['id']);

        $duplicate = $blogDetail['duplicate'];
        $duplicate++;

        $title = $blogDetail['title'] . ' (' . $duplicate . ')';

        $blogDetail['title'] = $title;

        $insertStatus = insert('blog', $blogDetail);
        if ($insertStatus) {

            setFlashData('msg', 'Nhân bản trang thành công!');
            setFlashData('msg_type', 'success');
            update('blog', ["duplicate" => $duplicate], "id=$blogId");
            // redirect('admin?module=blog');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
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

redirect('admin?module=blog');
