<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Thêm trang",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);
//lấy userid đăng nhập
$userId = isLogin()['user_id'];
//xử lý thêm nhóm người dùng
if (isPost()) {
    $body = getBody(); //lấy tất cả dữ liệu trong from

    //mảng lưu trữ các lỗi
    $errors = [];

    //validate họ tên => bắt buộc phải nhập, >= 4 kí tự
    if (empty(trim($body['title']))) {
        $errors['title']['reuired'] =  'Tên nhóm bắt buộc phải nhập';
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

        $dataInsert = [
            'title' => trim($body['title']),
            'slug' => trim($body['slug']),
            'content' => trim($body['content']),
            'user_id' => $userId,
            'create_at' => date('Y-m-d H:i:s'),
        ];

        print_r($dataInsert);
        $insertStatus = insert('pages', $dataInsert);

        // $insertStatus = false;

        if ($insertStatus) {

            setFlashData('msg', 'Thêm pages thành công!');
            setFlashData('msg_type', 'success');
            redirect('admin?module=pages');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=pages&action=add'); //load lại trang
        }
    } else {
        // die();
        //có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        //hàm reload lại trang 
        redirect('admin?module=pages&action=add');
    }
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
?>


<!-- Main content -->
<section class="content">
    <?php
    getMessage($msg, $msgtype);
    ?>
    <form action="" method="post" class="form">
        <div class="form-group">
            <label for="">Tên pages</label>
            <input type="text" class="form-control slug" value="<?php echo old('title', $old) ?>" placeholder="Tên pages..." name="title">
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
        <button type="submit" class="btn btn-sm btn-primary">Thêm mới</button>
        <a href="<?php echo getLinkAdmin('pages', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>


<?php

layout('footer', 'admin', $data);
