<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa người dùng
echo 'ok';
$body = getBody();
if (!empty($body['id'])) {
    $cateId = $body['id'];
    $cateDetail = getRow("SELECT id FROM `portfolio_categories` WHERE id=$cateId");
    if ($cateDetail > 0) {
        //kiểm tra xem trong danh mục còn dự án hay không
        $portfoliosNumber = getRow("SELECT id FROM `portfolios` WHERE portfolio_category_id =$cateId");
        if ($portfoliosNumber > 0) {
            setFlashData('msg', 'Xóa danh mục không thành công. Trong nhóm vẫn còn ' . $portfoliosNumber . ' dự án');
            setFlashData('msg_type', 'danger');
        } else {

            $condition = "id=$cateId";
            $deleteStatus = delete('portfolio_categories', $condition);
            if (!empty($deleteStatus)) {
                setFlashData('msg', 'Xóa danh mục thành công!');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Xóa danh mục không thành công! Vui lòng thử lại');
                setFlashData('msg_type', 'danger');
            }
        }

        // echo '<pre>';
        // print_r($cateDetail);
        // echo '</pre>';
    } else {
    }
} else {
    setFlashData('msg', 'Danh mục không tồn tại trong hệ thống!');
    setFlashData('msg_type', 'danger');
}

redirect('admin?module=portfolio_categories');
