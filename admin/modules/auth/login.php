<?php
if (!defined('_INCODE')) die('access define...');


//file này chứa chức năng login
$data = [
    'pageTitle' => "Đăng nhập hệ thống",
];
layout('header-login', 'admin', $data);
// $session = setSession('login','congminh');
// var_dump($session);
// removeSession('login');
// echo getSession('login');
// setFlashData('msg','đăng nhập thành công');
// echo getFlashData('msg');

// $send = sendMail('congminh352623@gmail.com','learn php','met moi qua');
// if($send){
//     echo 'gui email thanh cong';
// }

// $body = getBody();
if (isPost()) {
    $body = getBody();
    // echo '<pre>';
    // print_r($body);
    // echo '</pre>';
}
// $checkEmail = isEmail('congminh@gmail.com');
// var_dump($checkEmail);
// $checkInt = isNumberFloat('a',['min_range'=>1,'max_range'=>20]);
// var_dump($checkInt);
// $password  = 123456;
//mã hóa mật khẩu bằng BÂM
// $passwordHash = password_hash($password,PASSWORD_DEFAULT);
// echo $passwordHash;
// $passwordHash = '$2y$10$C03M/W4gkYjvEpuoeFqfvulgBGrYdGGSYRHZPFnfmDdrXZuQlpqS2';
// $checkPassword = password_verify('123456',$passwordHash);

// var_dump($checkPassword);

/**
 * nếu true => xác thực thành công
 * 
 * nếu false xác thực không thành công
 */
//kiểm tra trạng thái đăng nhập


if (isLogin()) {
    redirect('admin');
}
// echo getSession('login_token');
//xử lý đăng nhập
if (isPost()) {

    $body = getBody();
    if (!empty($body['email']) && !empty($body['password'])) {
        //kiểm tra đăng nhập
        $email = $body['email'];
        $password = $body['password'];

        //truy vấn thông tin users theo email
        $userQuery = firstRaw("SELECT id,password from users where email='$email' AND status=1");
        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $user_id = $userQuery['id'];
            if (password_verify($password, $passwordHash)) {
                //tạo token login
                // echo 1;
                $tokenLogin = sha1(uniqid() . time());
                echo $tokenLogin;
                //insert dữ liệu vào bảng
                $dataToken = [
                    'user_id' => $user_id,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];
                $insertTokenStatus = insert('login_token', $dataToken);
                if ($insertTokenStatus) {
                    //insert token thành công

                    //lưu login_token vào session
                    setSession('login_token', $tokenLogin);


                    redirect('admin');
                } else {
                    //insert khong thành công
                    setFlashData('msg', 'Lỗi hệ thống bạn không thể đăng nhập vào lúc này.');
                    setFlashData('msg_type', 'danger');
                    // redirect('admin?module=auth&action=login');
                }
            } else {
                setFlashData('msg', 'Mật khẩu không chính xác');
                setFlashData('msg_type', 'danger');
                // redirect('?module=auth&action=login');
            }
        } else {
            setFlashData('msg', 'Email không chính xác hoặc tài khoản chưa được kích hoạt');
            setFlashData('msg_type', 'danger');
            // redirect('?module=auth&action=login');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập email và mật khẩu');
        setFlashData('msg_type', 'danger');
        // redirect('?module=auth&action=login');
    }
    redirect('admin?module=auth&action=login');
}

$msg = getFlashData('msg');
$msgtype = getFlashData('msg_type');
?>
<div class="row">
    <div class="col-4 mx-auto mt-4">
        <h1 class="text-center text-uppercase">Đăng nhập hệ thống</h1>
        <?php getMessage($msg, $msgtype) ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Nhập vào email...">
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu...">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            <hr>
            <div class="d-flex justify-content-between ">

                <span><a href="?module=auth&action=fogot">Quên mật khẩu</a></span>
                <!-- <span><a href="?module=auth&action=register">Đăng kí tài khoản</a></span> -->
            </div>
        </form>
    </div>
</div>

<?php

layout('footer-login', 'admin');


// echo 'login';