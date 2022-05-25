<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Thêm nhóm người dùng",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);

//xử lý thêm nhóm người dùng
if (isPost()) {
    $body = getBody(); //lấy tất cả dữ liệu trong from


    //mảng lưu trữ các lỗi
    $errors = [];

    //validate họ tên => bắt buộc phải nhập, >= 4 kí tự
    if (empty(trim($body['name']))) {
        $errors['name']['reuired'] =  'Tên nhóm bắt buộc phải nhập';
    } else {
        if (strlen(trim($body['name'])) < 4) {
            $errors['name']['min'] = 'Họ tên phải lơn hơn hoặc bằng 4 kí tự';
        }
    }

    if (empty($errors)) {
        //không có lỗi xảy ra 
        $dataInsert = [
            'name' => $body['name'],
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $insertStatus = insert('groups', $dataInsert);
        if ($insertStatus) {

            setFlashData('msg', 'Thêm nhóm người dùng thành công!');
            setFlashData('msg_type', 'success');
            redirect('admin?module=groups');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=groups&action=add'); //load lại trang thêm người dùng
        }
    } else {
        //có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        //hàm reload lại trang 
        redirect('admin?module=groups&action=add');
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
    <form action="" method="post">
        <div class="form-group">
            <label for="">Tên nhóm</label>
            <input type="text" class="form-control" value="<?php echo old('name', $old) ?>" placeholder="Tên nhóm..." name="name">
            <?php echo form_error('name', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <button type="submit" class="btn btn-sm btn-primary">Thêm mới</button>
        <a href="<?php echo getLinkAdmin('groups', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>


<?php

layout('footer', 'admin', $data);
