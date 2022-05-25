<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Cập nhật người dùng",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);
//lấy dữ liệu củ của nhóm người dùng
$body = getBody('get');
if (!empty($body['id'])) {

    $userId = $body['id'];
    //kiểm tra user Id có tồn tại trong database hong không
    //nếu tồn tại => lấy ra thong tin
    //không tồn tại => chuyển hướng vè trang list
    $userDetail = firstRaw("SELECT * FROM `users` WHERE id = $userId");
    // print_r($groupDetail);
    if (empty($userDetail)) {
        //nếu user này k tồn tại
        //gán giá trị userDetail vào flashData


        // print_r($groupDetail);

        // setFlashData('groupDetail', $userDetail);
        redirect('admin?module=users');
    }
} else {
    redirect('admin?module=users');
}
//lấy ra danh sách tất cả các nhóm
$allGroups = getRaw("SELECT id,name FROM groups ORDER BY name");

//xử lý update nhóm người dùng


if (isPost()) {
    //validate form
    $body = getBody(); //lấy tất cả dữ liệu trong from


    //mảng lưu trữ các lỗi
    $errors = [];

    //validate họ tên => bắt buộc phải nhập, >= 5 kí tự
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['reuired'] =  'Họ tên bất buộc phải nhập';
    } else {
        if (strlen(trim($body['fullname'])) < 5) {
            $errors['fullname']['min'] = 'Hợ tên phải lơn hơn 4 kí tự';
        }
    }

    //validate nhóm người dùng : bắt buộc phải chọn
    if (empty(trim($body['group_id']))) {
        $errors['group_id']['reuired'] = 'Vui lòng chọn nhóm người dùng';
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
    //validate mật khẩu:bắt buộc phải nhập,>= 8 kí tự
    // if (empty(trim($body['password']))) {
    //     $errors['password']['reuired'] = 'Mật khẩu bắt buộc phải nhập';
    // } else {
    //     if (strlen(trim($body['password'])) < 8) {
    //         $errors['password']['min'] = 'Mật khẩu ít nhất 8 kí tự';
    //     }
    // }
    //validate nhập lại mật khẩu :bắt buộc phải nhâp,giống trường mật khẩu
    if (!empty(trim($body['password']))) {
        //chỉ validate khi mật khẩu dc nhập lại

        if (empty(trim($body['confirm_password']))) {
            $errors['confirm_password']['reuired'] = 'Xác nhận mật khẩu không được để trống';
        } else {
            if (trim($body['password']) != trim($body['confirm_password'])) {
                $errors['confirm_password']['match'] = 'Mật khẩu không khớp';
            }
        }
    }
    // echo '<pre>';
    // print_r($errors);
    // echo '</pre>';
    //kiểm tra mảng errors
    if (empty($errors)) {
        //không có lỗi xảy ra 
        // setFlashData('msg' ,'Đăng kí thành công');
        // setFlashData('msg_type','success');
        $dataUpdate = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'group_id' => $body['group_id'],
            'status' => $body['status'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        if (!empty(trim($body['password']))) {
            $dataUpdate['password'] = password_hash($body['password'], PASSWORD_DEFAULT);
        }
        $condition = "id = $userId";
        $updateStatus = update('users', $dataUpdate, $condition);
        if ($updateStatus) {

            setFlashData('msg', 'Cập nhật dùng thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }


        // redirect('?module=auth&action=register');
    } else {
        //có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        //hàm reload lại trang 
    }
    redirect('admin?module=users&action=edit&id=' . $userId);
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
if (empty($old) && !empty($userDetail)) {
    $old = $userDetail;
}
?>


<!-- Main content -->
<section class="content">
    <?php
    getMessage($msg, $msgtype);
    ?>
    <form action="" method="post">
        <div class="row">
            <div class="col">

                <div class="form-group">
                    <label for="">Họ tên</label>
                    <input type="text" class="form-control" value="<?php echo old('fullname', $old) ?>" name="fullname" placeholder="Họ và tên...">
                    <?php echo form_error('fullname', $errors, '<span class="error">', '</span>');
                    ?>
                </div>
                <div class="form-group">
                    <label for="">Nhóm người dùng</label>
                    <select name="group_id" class="form-control">
                        <option value="0">Chọn nhóm người dùng</option>
                        <?php if (!empty($allGroups)) {
                            foreach ($allGroups as $group) {
                        ?>
                                <option value="<?php echo $group['id'] ?>" <?php echo (old('group_id', $old) == $group['id']) ? 'selected' : false; ?>><?php echo $group['name'] ?></option>
                        <?php
                            }
                        } ?>
                    </select>
                    <?php echo form_error('group_id', $errors, '<span class="error">', '</span>');
                    ?>
                </div>
                <div class="form-group">
                    <label for="">Email </label>
                    <input type="text" class="form-control" value="<?php echo old('email', $old) ?>" name="email" placeholder="Email...">
                    <?php echo form_error('email', $errors, '<span class="error">', '</span>');
                    ?>
                </div>
            </div>
            <div class="col">

                <div class="form-group">
                    <label for="">Mật khẩu</label>
                    <input type="text" class="form-control" name="password" placeholder="Mật khẩu(không nhập nếu không thay đổi)...">
                    <?php echo form_error('password', $errors, '<span class="error">', '</span>');
                    ?>
                </div>
                <div class="form-group">
                    <label for="">Nhập lại mật khẩu</label>
                    <input type="text" class="form-control" value="<?php echo old('confirm_password', $old) ?>" name="confirm_password" placeholder="Nhập lại mật khẩu(không nhập nếu không thay đổi)...">
                    <?php echo form_error('confirm_password', $errors, '<span class="error">', '</span>');
                    ?>
                </div>
                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <select name="status" id="" class="form-control">
                        <option value="0" <?php echo (old('status', $old) == 0) ? 'selected' : false; ?>>Chưa kích hoạt</option>
                        <option value="1" <?php echo (old('status', $old) == 1) ? 'selected' : false; ?>>Kích hoạt</option>

                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
        <a href="<?php echo getLinkAdmin('users', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>


<?php

layout('footer', 'admin', $data);
