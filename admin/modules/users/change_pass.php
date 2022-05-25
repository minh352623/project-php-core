<?php

$data = [
    'pageTitle' => "Đổi mật khẩu",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);

$userId = isLogin()['user_id'];
// echo $userId;
$userDetail = getUserInfo($userId);
setFlashData('userDetail', $userDetail);

//xử lý cập nhật thông tin cá nhân

if (isPost()) {
    $errors = [];
    // print_r($_POST);
    //validate form
    $body = getBody(); //lấy tất cả dữ liệu trong from


    //mảng lưu trữ các lỗi

    //validate mật khẩu cũ=> bắt buộc nhập, trùng với mật khẩu trong database
    if (empty(trim($body['old_pass']))) {
        $errors['old_pass']['reuired'] =  'Vui lòng nhập mật khẩu cũ';
    } else {
        $oldPass = trim($body['old_pass']);
        // echo $oldPass;
        $hashPass = $userDetail['password'];
        // echo $hashPass;
        if (!password_verify($oldPass, $hashPass)) {
            $errors['old_pass']['match'] =  'Mật khẩu cũ không chinh xác';
        }
    }
    //validate mật khẩu:bắt buộc phải nhập,>= 8 kí tự
    if (empty(trim($body['password']))) {
        $errors['password']['reuired'] = 'Mật khẩu bắt buộc phải nhập';
    } else {
        if (strlen(trim($body['password'])) < 8) {
            $errors['password']['min'] = 'Mật khẩu ít nhất 8 kí tự';
        }
    }
    //validate nhập lại mật khẩu :bắt buộc phải nhâp,giống trường mật khẩu
    if (empty(trim($body['confirm_password']))) {
        $errors['confirm_password']['reuired'] = 'Xác nhận mật khẩu không được để trống';
    } else {
        if (trim($body['password']) != trim($body['confirm_password'])) {
            $errors['confirm_password']['match'] = 'Mật khẩu không khớp';
        }
    }


    //kiểm tra mảng errors
    if (empty($errors)) {
        $dataUpdate = [
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),

            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = "id = $userId";
        $updateStatus = update('users', $dataUpdate, $condition);
        if ($updateStatus) {

            setFlashData('msg', 'Đổi mật khẩu thành công!. Bạn có thể đăng nhập bằng mật khẩu mới ngay ngay bây giờ.');
            setFlashData('msg_type', 'success');


            //thiết lập gửi mail
            $subject = $userDetail['fullname'] . 'Thay đổi mật khẩu thành công!';
            $content = 'Chào ' . $userDetail['fullname'] . '<br/>';
            $content .= 'Chúc mừng bạn thay đổi mật khẩu thành công. Hiện tại bạn có thể đăng nhập với mật khẩu mới </br>';
            $content .= 'Nếu không phải bạn thay đổi, vui lòng liên hệ ngay với chúng tôi <br/>';
            $content .= 'Trân trọng cảm ơn';

            //tiến hành gửi mail
            $sendStatus = sendMail($userDetail['email'], $subject, $content);

            //tự động đăng xuất khi dổi mật khẩu thành công 
            redirect('admin?module=auth&action=logout');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        //có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        //hàm reload lại trang 
    }



    redirect('admin?module=users&action=change_pass');
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');

// print_r($old);
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php
        getMessage($msg, $msgtype);
        ?>
        <form action="" method="post">

            <div class="form-group">
                <label for="">Mật khẩu cũ</label>
                <input type="password" placeholder="Mật khẩu cũ..." name="old_pass" class="form-control">
                <?php echo form_error('old_pass', $errors, '<span class="error">', '</span>');
                ?>
            </div>
            <div class="form-group">
                <label for="">Mật khẩu mới</label>
                <input type="password" placeholder="Mật khẩu mới..." name="password" class="form-control">
                <?php echo form_error('password', $errors, '<span class="error">', '</span>');
                ?>
            </div>
            <div class="form-group">
                <label for="">Nhập lại mật khẩu mới</label>
                <input type="password" placeholder="Nhập lại mật khẩu mới..." name="confirm_password" class="form-control">
                <?php echo form_error('confirm_password', $errors, '<span class="error">', '</span>');
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
