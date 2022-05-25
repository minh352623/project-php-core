<?php
if (!defined('_INCODE')) die('access define...');

//file này chứa chức năng quên mật khẩu
$data = [
    'pageTitle' => "Đăng nhập hệ thống",
];
layout('header-login', 'admin', $data);

if (isPost()) {
    $body = getBody();
    // echo '<pre>';
    // print_r($body);
    // echo '</pre>';
}



if (isLogin()) {
    redirect('?module=users');
}
// echo getSession('loginToken');
//xử lý đăng nhập
if (isPost()) {
    $body = getBody();
    if (!empty($body['email'])) {
        $email = $body['email'];
        // echo $email;
        $queryUser = firstRaw("SELECT id FROM users WhERE email='$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];
            //tạo forgotToken 
            $forgotToken = sha1(uniqid() . time());
            $dataUpdate = [
                'forget_token' => $forgotToken

            ];
            $updateStatus = update('users', $dataUpdate, "id = $userId");
            if ($updateStatus) {
                //tạo link khôi phục
                $linkReset = _WEB_HOST_ROOT_ADMIN . '?module=auth&action=reset&token=' . $forgotToken;
                //thiết lập gửi mail
                $subject = 'Yêu cầu khôi phục mật khẩu';
                $content = 'Chào bạn: ' . $email . '</br>';
                $content .= 'Chúng tôi nhận yêu cầu khôi phục mật khẩu từ bạn. Vui lòng nhấp vào link sau để khôi phục tài khoản: ';
                $content .= $linkReset . '</br>';
                $content .= 'Trân trọng!';

                //tiên hành gửi mail
                $sendStatus = sendMail($email, $subject, $content);
                if ($sendStatus) {
                    setFlashData('msg', 'Vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu');
                    setFlashData('msg_type', 'success');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống bạn không thẻ sử dụng chức năng này');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Lỗi hệ thống bạn không thẻ sử dụng chức năng này');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Địa chỉ email không tồn tại trong hệ thông');
            setFlashData('msg_type', 'danger');
        }
        // die();
    } else {
        setFlashData('msg', 'Vui lòng nhập địa chỉ email');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=fogot');
}

$msg = getFlashData('msg');
$msgtype = getFlashData('msg_type');
?>
<div class="row">
    <div class="col-4 mx-auto mt-4">
        <h1 class="text-center text-uppercase">Đặt Lại Mật Khẩu</h1>
        <?php getMessage($msg, $msgtype) ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Nhập vào email...">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Xác Nhận</button>
            <hr>
            <div class="d-flex justify-content-between ">

                <span><a href="?module=auth&action=login">Đăng Nhập</a></span>
                <!-- <span><a href="?module=auth&action=register">Đăng kí tài khoản</a></span> -->
            </div>
        </form>
    </div>
</div>

<?php

layout('footer-login', 'admin');
