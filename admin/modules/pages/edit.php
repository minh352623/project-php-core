<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Cập nhật dịch vụ",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);
//lấy dữ liệu củ của nhóm người dùng
$body = getBody('get');
if (!empty($body['id'])) {

    $pageId = $body['id'];
    //kiểm tra user Id có tồn tại trong database hong không
    //nếu tồn tại => lấy ra thong tin
    //không tồn tại => chuyển hướng vè trang list
    $pageDetail = firstRaw("SELECT * FROM `pages` WHERE id = $pageId");
    // print_r($pageDetail);
    if (empty($pageDetail)) {
        //nếu user này k tồn tại
        //gán giá trị userDetail vào flashData


        // print_r($pageDetail);

        // setFlashData('pageDetail', $userDetail);
        redirect('admin?module=pages');
    }
} else {
    redirect('admin?module=pages');
}


//xử lý update nhóm người dùng


if (isPost()) {
    $body = getBody(); //lấy tất cả dữ liệu trong from


    //mảng lưu trữ các lỗi
    $errors = [];

    //validate họ tên => bắt buộc phải nhập, >= 4 kí tự
    if (empty(trim($body['title']))) {
        $errors['title']['reuired'] =  'Tên trang bắt buộc phải nhập';
    }

    //validate đường dẫn tĩnh => bắt buộc phải nhập, 
    if (empty(trim($body['slug']))) {
        $errors['slug']['reuired'] =  'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    if (empty(trim($body['content']))) {
        $errors['content']['reuired'] =  'Nội dung bắt buộc phải nhập';
    }

    if (empty($errors)) {
        //không có lỗi xảy ra 
        $dataUpdate = [
            'title' => trim($body['title']),
            'slug' => trim($body['slug']),
            'content' => trim($body['content']),
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = "id=$pageId";
        $updateStatus = update('pages', $dataUpdate, $condition);
        if ($updateStatus) {

            setFlashData('msg', 'Cập nhật trang thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        //có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        //hàm reload lại trang 
    }

    //load lại trang sữa hiện tại
    redirect('admin?module=pages&action=edit&id=' . $pageId);
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
if (empty($old) && !empty($pageDetail)) {
    $old = $pageDetail;
}
?>


<!-- Main content -->
<section class="content">
    <?php
    getMessage($msg, $msgtype);
    ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="">Tên trang</label>
            <input type="text" class="form-control slug" value="<?php echo old('title', $old) ?>" placeholder="Tên dịch vụ..." name="title">
            <?php echo form_error('title', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Đường dẫn tĩnh</label>
            <input type="text" class="form-control auto-slug" value="<?php echo old('slug', $old) ?>" placeholder="Đường dẫn tĩnh..." name="slug">
            <?php echo form_error('slug', $errors, '<span class="error">', '</span>');
            ?>
            <p class="render-link"><b>Link</b>: <span></span></p>

        </div>

        <div class="form-group">
            <label for="">Nội dung</label>
            <textarea type="text" class="form-control editor" placeholder="Nội dung..." name="content"><?php echo old('content', $old) ?></textarea>
            <?php echo form_error('content', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
        <a href="<?php echo getLinkAdmin('pages', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>


<?php

layout('footer', 'admin', $data);
