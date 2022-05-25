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

    $serviceId = $body['id'];
    //kiểm tra user Id có tồn tại trong database hong không
    //nếu tồn tại => lấy ra thong tin
    //không tồn tại => chuyển hướng vè trang list
    $serviceDetail = firstRaw("SELECT * FROM `services` WHERE id = $serviceId");
    // print_r($serviceDetail);
    if (empty($serviceDetail)) {
        //nếu user này k tồn tại
        //gán giá trị userDetail vào flashData


        // print_r($serviceDetail);

        // setFlashData('serviceDetail', $userDetail);
        redirect('admin?module=services');
    }
} else {
    redirect('admin?module=services');
}


//xử lý update nhóm người dùng


if (isPost()) {
    $body = getBody(); //lấy tất cả dữ liệu trong from


    //mảng lưu trữ các lỗi
    $errors = [];

    //validate họ tên => bắt buộc phải nhập, >= 4 kí tự
    if (empty(trim($body['name']))) {
        $errors['name']['reuired'] =  'Tên nhóm bắt buộc phải nhập';
    }

    //validate đường dẫn tĩnh => bắt buộc phải nhập, 
    if (empty(trim($body['slug']))) {
        $errors['slug']['reuired'] =  'Đường dẫn tĩnh bắt buộc phải nhập';
    }
    //validate icon => bắt buộc phải nhập, 
    if (empty(trim($body['icon']))) {
        $errors['icon']['reuired'] =  'Icon bắt buộc phải nhập';
    }
    if (empty(trim($body['content']))) {
        $errors['content']['reuired'] =  'Nội dung bắt buộc phải nhập';
    }

    if (empty($errors)) {
        //không có lỗi xảy ra 
        $dataUpdate = [
            'name' => trim($body['name']),
            'slug' => trim($body['slug']),
            'icon' => trim($body['icon']),
            'description' => trim($body['description']),
            'content' => trim($body['content']),
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = "id=$serviceId";
        $updateStatus = update('services', $dataUpdate, $condition);
        if ($updateStatus) {

            setFlashData('msg', 'Cập nhật dịch vụ thành công!');
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
    redirect('admin?module=services&action=edit&id=' . $serviceId);
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
if (empty($old) && !empty($serviceDetail)) {
    $old = $serviceDetail;
}
?>


<!-- Main content -->
<section class="content">
    <?php
    getMessage($msg, $msgtype);
    ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="">Tên dịch vụ</label>
            <input type="text" class="form-control slug" value="<?php echo old('name', $old) ?>" placeholder="Tên dịch vụ..." name="name">
            <?php echo form_error('name', $errors, '<span class="error">', '</span>');
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
            <label for="">Icon</label>
            <div class="row ckfinder-group">
                <div class="col-10">

                    <input type="text" class="form-control image-reder" value="<?php echo old('icon', $old) ?>" placeholder="Icon..." name="icon">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-success btn-sm btn-block choose-image">Chọn ảnh</button>
                </div>
            </div>
            <?php echo form_error('icon', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Mô tả ngắn</label>
            <textarea type="text" class="form-control" placeholder="Mô tả..." name="description"><?php echo old('description', $old) ?></textarea>
            <?php echo form_error('description', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Nội dung</label>
            <textarea type="text" class="form-control editor" placeholder="Nội dung..." name="content"><?php echo old('content', $old) ?></textarea>
            <?php echo form_error('content', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
        <a href="<?php echo getLinkAdmin('services', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>


<?php

layout('footer', 'admin', $data);
