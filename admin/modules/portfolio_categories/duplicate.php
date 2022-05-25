
<?php
if (!defined('_INCODE')) die('access define...');
$body = getBody();

if (!empty($body['id'])) {
    $pageId = $body['id'];
    $cateDetail = firstRaw("SELECT * FROM `portfolio_categories` WHERE id=$pageId");
    if (!empty($cateDetail)) {
        // echo '<pre>';
        // print_r($cateDetail);
        // echo '</pre>';portfolio_categories
        //loại bỏ thời gian tạo(create_at), và thời gian cập nhật(update_at)
        $cateDetail['create_at'] = date('Y-m-d H:i:s');
        unset($cateDetail['update_at']);
        unset($cateDetail['id']);

        $duplicate = $cateDetail['duplicate'];
        $duplicate++;

        $name = $cateDetail['name'] . ' (' . $duplicate . ')';

        $cateDetail['name'] = $name;

        $insertStatus = insert('portfolio_categories', $cateDetail);
        if ($insertStatus) {

            setFlashData('msg', 'Nhân bản danh mục thành công!');
            setFlashData('msg_type', 'success');
            update('portfolio_categories', ["duplicate" => $duplicate], "id=$pageId");
            // redirect('admin?module=portfolio_categories');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=portfolio_categories'); //load lại trang
        }
    } else {
        setFlashData('msg', 'Danh mục không tồn tại trong hệ thống!');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=portfolio_categories');
