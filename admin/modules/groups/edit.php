<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Cập nhật nhóm người dùng",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);
//lấy dữ liệu củ của nhóm người dùng
$body = getBody('get');
if (!empty($body['id'])) {

    $groupId = $body['id'];
    //kiểm tra user Id có tồn tại trong database hong không
    //nếu tồn tại => lấy ra thong tin
    //không tồn tại => chuyển hướng vè trang list
    $groupDetail = firstRaw("SELECT * FROM `groups` WHERE id = $groupId");
    // print_r($groupDetail);
    if (empty($groupDetail)) {
        //nếu user này k tồn tại
        //gán giá trị userDetail vào flashData


        // print_r($groupDetail);

        // setFlashData('groupDetail', $userDetail);
        redirect('admin?module=groups');
    }
} else {
    redirect('admin?module=groups');
}


//xử lý update nhóm người dùng


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
        $dataUpdate = [
            'name' => $body['name'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = "id=$groupId";
        $updateStatus = update('groups', $dataUpdate, $condition);
        if ($updateStatus) {

            setFlashData('msg', 'Cập nhật nhóm người dùng thành công!');
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
    redirect('admin?module=groups&action=edit&id=' . $groupId);
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
if (empty($old) && !empty($groupDetail)) {
    $old = $groupDetail;
}
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
        <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
        <a href="<?php echo getLinkAdmin('groups', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>


<?php

layout('footer', 'admin', $data);
