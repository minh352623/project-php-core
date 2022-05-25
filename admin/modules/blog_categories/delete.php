<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa người dùng
echo 'ok';
$body = getBody();
if (!empty($body['id'])) {
    $blog_categoriesId = $body['id'];
    $blog_categoriesDetail = getRow("SELECT id FROM `blog_categories` WHERE id=$blog_categoriesId");
    if ($blog_categoriesDetail > 0) {
        //kiểm tra xem trong danh mục còn dự án hay không
        $blogNumber = getRow("SELECT id FROM `blog` WHERE category_id  =$blog_categoriesId");
        if ($blogNumber > 0) {
            setFlashData('msg', 'Xóa blog không thành công. Trong nhóm vẫn còn ' . $blogNumber . ' dự án');
            setFlashData('msg_type', 'danger');
        } else {

            $condition = "id=$blog_categoriesId";
            $deleteStatus = delete('blog_categories', $condition);
            if (!empty($deleteStatus)) {
                setFlashData('msg', 'Xóa blog thành công!');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa blog không thành công! Vui lòng thử lại');
                setFlashData('msg_type', 'danger');
            }
        }

        // echo '<pre>';
        // print_r($blog_categoriesDetail);
        // echo '</pre>';
    } else {
    }
} else {
    setFlashData('msg', 'Danh mục không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=blog_categories');
