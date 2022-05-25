<?php
if (!defined('_INCODE')) die('access define...');

//file này dùng để xóa dịch vụ
echo 'ok';
$body = getBody();
if (!empty($body['id'])) {
    $portfoliosId = $body['id'];
    $portfoliosDetail = getRow("SELECT id FROM `portfolios` WHERE id=$portfoliosId");
    if ($portfoliosDetail > 0) {
        //xử lý xóa thư viện ảnh
        delete('portfolio_images', "portfolio_id=$portfoliosId");

        $condition = "id=$portfoliosId";
        $deleteStatus = delete('portfolios', $condition);
        if (!empty($deleteStatus)) {

            setFlashData('msg', 'Xóa dự án thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Xóa dự án không thành công! Vui lòng thử lại');
            setFlashData('msg_type', 'danger');
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
