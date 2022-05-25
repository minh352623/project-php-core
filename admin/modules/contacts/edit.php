<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Cập nhật liên hệ",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);
$body = getBody('get');
if (!empty($body['id'])) {

    $contactsId = $body['id'];
    //kiểm tra user Id có tồn tại trong database hong không
    //nếu tồn tại => lấy ra thong tin
    //không tồn tại => chuyển hướng vè trang list
    $contactsDetail = firstRaw("SELECT * FROM contacts WHERE id = $contactsId");
    // print_r($contactsDetail);
    if (empty($contactsDetail)) {
        redirect('admin?module=contacts');
    }
} else {
    redirect('admin?module=contacts');
}

//xử lý thêm contacts
if (isPost()) {
    $body = getBody(); //lấy tất cả dữ liệu trong from

    //mảng lưu trữ các lỗi
    $errors = [];

    //validate họ tên => bắt buộc phải nhập, >= 4 kí tự
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['reuired'] =  'Họ và tên bắt buộc phải nhập';
    }

    //validate đường dẫn tĩnh => bắt buộc phải nhập, 
    if (empty(trim($body['email']))) {
        $errors['email']['reuired'] =  'Email bắt buộc phải nhập';
    }

    if (empty(trim($body['message']))) {
        $errors['message']['reuired'] =  'Nội dung bắt buộc phải nhập';
    }
    if (empty(trim($body['type_id']))) {
        $errors['type_id']['reuired'] =  'Phòng ban bắt buộc phải chọn';
    }

    if (empty($errors)) {
        //không có lỗi xảy ra 

        $dataInsert = [
            'fullname' => trim($body['fullname']),
            'email' => trim($body['email']),
            'message' => trim($body['message']),
            'type_id' => trim($body['type_id']),
            'status' => trim($body['status']),
            'note' => trim($body['note']),

            'update_at' => date('Y-m-d H:i:s'),
        ];

        // print_r($dataInsert);
        $condition = "id=$contactsId";
        $updateStatus = update('contacts', $dataInsert, $condition);

        // $updateStatus = false;

        if ($updateStatus) {

            setFlashData('msg', 'Cập nhật liên hệ thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        // die();
        //có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        //hàm reload lại contacts 
    }
    redirect('admin?module=contacts&action=edit&id=' . $contactsId);
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
if (empty($old) && !empty($contactsDetail)) {
    $old = $contactsDetail;
}
//lấy dữ liệu tất cả phòng ban
$allContactType  = getRaw("SELECT id,name FROM contact_type ORDER BY name");
// print_r($allContactType);
?>


<!-- Main message -->
<section class="content">
    <?php
    getMessage($msg, $msgtype);
    ?>
    <form action="" method="post" class="form">
        <div class="form-group">
            <label for="">Họ tên</label>
            <input type="text" class="form-control slug" value="<?php echo old('fullname', $old) ?>" placeholder="Họ tên..." name="fullname">
            <?php echo form_error('fullname', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="text" class="form-control slug" value="<?php echo old('email', $old) ?>" placeholder="Email..." name="email">
            <?php echo form_error('email', $errors, '<span class="error">', '</span>');
            ?>
        </div>


        <div class="form-group">
            <label for="">Nội dung</label>
            <textarea type="text" class="form-control" placeholder="Nội dung..." name="message"><?php echo old('message', $old) ?></textarea>
            <?php echo form_error('message', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Phòng ban</label>

            <select name="type_id" class="form-control">
                <option value="0">Chọn phòng ban</option>
                <?php
                if (!empty($allContactType)) {
                    foreach ($allContactType as $item) {
                ?>
                        <option value="<?php echo $item['id'] ?>" <?php echo (old('type_id', $old) ==  $item['id']) ? 'selected' : null; ?>><?php echo $item['name'] ?></option>
                <?php
                    }
                }
                ?>
            </select>
            <?php echo form_error('type_id', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Trạng thái</label>
            <select name="status" id="" class="form-control">
                <option value="0" <?php echo (old('status', $old) == 0) ? 'selected' : false; ?>>Chưa xử lý</option>
                <option value="1" <?php echo (old('status', $old) == 1) ? 'selected' : false; ?>>Đã xử lý</option>

            </select>
        </div>

        <div class="form-group">
            <label for="">Ghi chú</label>
            <textarea type="text" class="form-control" placeholder="Nội dung..." name="note"><?php echo old('note', $old) ?></textarea>

        </div>
        <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
        <a href="<?php echo getLinkAdmin('contacts', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>
</div>


<?php

layout('footer', 'admin', $data);
