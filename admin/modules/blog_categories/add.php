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
    if (empty(trim($body['slug']))) {
        $errors['slug']['reuired'] =  'Đường dẫn bắt buộc phải nhập';
    }

    if (empty($errors)) {
        //không có lỗi xảy ra 

        $dataInsert = [
            'name' => trim($body['name']),
            'user_id' => $userId,
            'slug' => trim($body['slug']),

            'create_at' => date('Y-m-d H:i:s'),
        ];

        print_r($dataInsert);
        $insertStatus = insert('blog_categories', $dataInsert);

        // $insertStatus = false;

        if ($insertStatus) {

            setFlashData('msg', 'Thêm danh mục thành công!');
            setFlashData('msg_type', 'success');
            redirect('admin?module=blog_categories');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=blog_categories'); //load lại trang
        }
    } else {
        // die();
        //có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        //hàm reload lại trang 
        redirect('admin?module=blog_categories');
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
        <label for="">Tên blog</label>
        <input type="text" value="<?php echo old('name', $old) ?>" placeholder="Tên danh mục..." name='name' class="form-control slug">
        <?php echo form_error('name', $errors, '<span class="error">', '</span>');
        ?>
    </div>
    <div class="form-group">
        <label for="">Đường dẫn tĩnh</label>
        <input type="text" value="<?php echo old('slug', $old) ?>" placeholder="Đường dẫn tĩnh..." name='slug' class="form-control auto-slug">
        <?php echo form_error('slug', $errors, '<span class="error">', '</span>');

        ?>
        <p class="render-link"><b>Link</b>: <span></span></p>

    </div>
    <button type="submit" class="btn btn-primary">Thêm mới</button>
</form>