<?php
//lấy userid đăng nhập
$userId = isLogin()['user_id'];
//xử lý thêm nhóm người dùng
if (isPost()) {
    $body = getBody(); //lấy tất cả dữ liệu trong from

    //mảng lưu trữ các lỗi
    $errors = [];

    //validate tên => bắt buộc phải nhập, >= 4 kí tự
    if (empty(trim($body['name']))) {
        $errors['name']['reuired'] =  'Tên nhóm bắt buộc phải nhập';
    }

    if (empty($errors)) {
        //không có lỗi xảy ra 

        $dataInsert = [
            'name' => trim($body['name']),
            'user_id' => $userId,

            'create_at' => date('Y-m-d H:i:s'),
        ];

        print_r($dataInsert);
        $insertStatus = insert('portfolio_categories', $dataInsert);

        // $insertStatus = false;

        if ($insertStatus) {

            setFlashData('msg', 'Thêm danh mục thành công!');
            setFlashData('msg_type', 'success');
            redirect('admin?module=portfolio_categories');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=portfolio_categories'); //load lại trang
        }
    } else {
        // die();
        //có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        //hàm reload lại trang 
        redirect('admin?module=portfolio_categories');
    }
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');

?>
<h4>Thêm danh mục</h4>
<form action="" method="post">
    <div class="form-group">
        <label for="">Tên dự án</label>
        <input type="text" value="<?php echo old('name', $old) ?>" placeholder="Tên danh mục..." name='name' class="form-control">
        <?php echo form_error('title', $errors, '<span class="error">', '</span>');
        ?>
    </div>
    <button type="submit" class="btn btn-primary">Thêm mới</button>
</form>