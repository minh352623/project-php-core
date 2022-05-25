<?php
if (!defined('_INCODE')) die('access define...');

//file này chứa chức năng đặt lại mật khẩu

layout('header-login', 'admin');
echo '<div class="container text-center"></br>';
if (!empty(getBody()['token'])) {

    $token = getBody()['token'];
}

// echo $token;
if (!empty($token)) {
    // echo $token;
    $tokenQuery = firstRaw("SELECT id,fullname,email FROM users WHERE forget_token='$token'");
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        $email = $tokenQuery['email'];
        if (isPost()) {
            $body = getBody();
            $errors = [];

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
            // print_r($errors);
            if (empty($errors)) {
                //xử lý upadte mật khẩu
                $passwordHash = password_hash($body['password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forget_token' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];
                $updateStatus = update('users', $dataUpdate, "id=$userId");
                if ($updateStatus) {
                    setFlashData('msg', 'Thay đổi mật khẩu thành công');
                    setFlashData('msg_type', 'success');

                    //gửi email thông báo khi đổi xong 
                    $subject = 'Bạn vừa đổi mật khẩu';
                    $content = 'Chúc mừng bạn đã đổi mật khẩu thành công';
                    sendMail($email, $subject, $content);

                    redirect('?module=auth&action=login');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống! Bạn không thể đổi mật khẩu');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                //có lỗi xảy ra
                setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setFlashData('msg_type', 'danger');
                setFlashData('errors', $errors);
                //hàm reload lại trang 
                redirect('?module=auth&action=reset&token=' . $token);
            }
            // redirect('?module=auth&action=reset&token='.$token);
        }
        $msg = getFlashData('msg');
        $msgtype = getFlashData('msg_type');
        $errors = getFlashData('errors');
?>
        <div class="row text-left">
            <div class="col-6 mx-auto mt-4">
                <h1 class="text-center text-uppercase">Đặt lại mật khẩu</h1>
                <?php getMessage($msg, $msgtype) ?>
                <form action="" method="post">

                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu mới...">
                        <?php echo form_error('password', $errors, '<span class="error">', '</span>');
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="">Đặt lại Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu mới...">
                        <?php echo form_error('confirm_password', $errors, '<span class="error">', '</span>');
                        ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
                    <hr>
                    <div class="d-flex justify-content-between ">

                        <span><a href="?module=auth&action=login">Đăng nhập</a></span>
                        <span><a href="?module=auth&action=register">Đăng kí tài khoản</a></span>
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                    </div>
                </form>
            </div>
        </div>


<?php
    } else {
        getMessage('Liên kết không tồn tại hoặc đã hết hạn.', 'danger');
    }
} else {
    getMessage('Liên kết không tồn tại hoặc đã hết hạn.', 'danger');
}






echo '</div>';
layout('footer-login', 'admin');
