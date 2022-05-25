<?php

$data = [
    'pageTitle' => "Cập nhật thông tin cá nhân",
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

    //validate họ tên => bắt buộc phải nhập, >= 5 kí tự
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['reuired'] =  'Họ tên bất buộc phải nhập';
    } else {
        if (strlen(trim($body['fullname'])) < 5) {
            $errors['fullname']['min'] = 'Hợ tên phải lơn hơn 4 kí tự';
        }
    }

    //validate email: bắt buộc phải nhập,định dạng email,email phải là duy nhất
    if (empty(trim($body['email']))) {
        $errors['email']['requied'] = 'Email băt buộc phải nhập';
    } else {
        //kiểm tra email hợp lệ
        if (!isEmail(trim($body['email']))) {
            $errors['email']['isEmail'] = 'Email không hợp lệ';
        } else {
            //kiểm tra email có tồn tại trong database hay không
            $email = trim($body['email']);
            $sql = "SELECT id from users where email='$email' AND id<>$userId";
            if (getRow($sql)  > 0) {
                $errors['email']['unique'] = 'Địa chỉ eamil đã tồn tại';
            }
        }
    }

    //kiểm tra mảng errors
    if (empty($errors)) {
        $dataUpdate = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'contact_facebook' => $body['contact_facebook'],
            'contact_twitter' => $body['contact_twitter'],
            'contact_linkedin' => $body['contact_linkedin'],
            'contact_pinterest' => $body['contact_pinterest'],
            'about_content' => $body['about_content'],

            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = "id = $userId";
        $updateStatus = update('users', $dataUpdate, $condition);
        if ($updateStatus) {

            setFlashData('msg', 'Cập nhật thông tin thành công!');
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



    redirect('admin?module=users&action=profile');
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
$userDetail = getFlashData('userDetail');
// print_r($userDetail);

if (!empty($userDetail) && empty($old)) {
    $old = $userDetail;
}
// print_r($old);
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php
        getMessage($msg, $msgtype);
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col-6">

                    <div class="form-group">
                        <label for="">Họ và tên</label>
                        <input type="text" placeholder="Họ và tên..." value="<?php echo old('fullname', $old) ?>" name="fullname" class="form-control">
                        <?php echo form_error('fullname', $errors, '<span class="error">', '</span>');
                        ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" placeholder="Email..." name="email" value="<?php echo old('email', $old) ?>" class="form-control">
                        <?php echo form_error('email', $errors, '<span class="error">', '</span>');
                        ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Facebook</label>
                        <input type="text" placeholder="Facebook..." name="contact_facebook" value="<?php echo old('contact_facebook', $old) ?>" class="form-control">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Twitter</label>
                        <input type="text" placeholder="Twitter..." name="contact_twitter" value="<?php echo old('contact_twitter', $old) ?>" class="form-control">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">LinkedIn</label>
                        <input type="text" placeholder="Linkedi+In..." name="contact_linkedin" value="<?php echo old('contact_linkedin', $old) ?>" class="form-control">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Pinterest</label>
                        <input type="text" placeholder="Pinterest..." name="contact_pinterest" value="<?php echo old('contact_pinterest', $old) ?>" class="form-control">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">

                        <label for="">Thông tin cá nhân</label>
                        <textarea name="about_content" class="form-control" placeholder="Nội dung giới thiệu..."><?php echo old('about_content', $old) ?></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
