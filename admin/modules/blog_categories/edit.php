<?php
//lấy userid đăng nhập
$body = getBody('get');
if (!empty($body['id'])) {

    $blog_categoriesId = $body['id'];
    //kiểm tra user Id có tồn tại trong database hong không
    //nếu tồn tại => lấy ra thong tin
    //không tồn tại => chuyển hướng vè trang list
    $blog_categoriesDetail = firstRaw("SELECT * FROM `blog_categories` WHERE id = $blog_categoriesId");
    // print_r($blog_categoriesDetail);
    if (empty($blog_categoriesDetail)) {
        //nếu user này k tồn tại
        //gán giá trị userDetail vào flashData


        // print_r($blog_categoriesDetail);

        // setFlashData('blog_categoriesDetail', $userDetail);
        redirect('admin?module=blog_categories');
    }
} else {
    redirect('admin?module=blog_categories');
}


//xử lý update nhóm người dùng


if (isPost()) {
    $body = getBody(); //lấy tất cả dữ liệu trong from


    //mảng lưu trữ các lỗi
    $errors = [];

    //validate họ tên => bắt buộc phải nhập, >= 4 kí tự
    if (empty(trim($body['name']))) {
        $errors['name']['reuired'] =  'Tên trang bắt buộc phải nhập';
    }
    if (empty(trim($body['slug']))) {
        $errors['slug']['reuired'] =  'Đường dẫn bắt buộc phải nhập';
    }



    if (empty($errors)) {
        //không có lỗi xảy ra 
        $dataUpdate = [
            'name' => trim($body['name']),
            'slug' => trim($body['slug']),

            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = "id=$blog_categoriesId";
        $updateStatus = update('blog_categories', $dataUpdate, $condition);
        if ($updateStatus) {

            setFlashData('msg', 'Cập nhật blog thành công!');
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
    redirect('admin?module=blog_categories&action=lists&view=edit&id=' . $blog_categoriesId);
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
if (empty($old) && !empty($blog_categoriesDetail)) {
    $old = $blog_categoriesDetail;
}

?>

<h4>Cập nhật blog</h4>
<form action="" method="post">
    <div class="form-group">
        <label for="">Tên dự án</label>
        <input type="text" value="<?php echo old('name', $old) ?>" placeholder="Tên blog..." name='name' class="form-control slug">
        <?php echo form_error('title', $errors, '<span class="error">', '</span>');
        ?>
    </div>
    <div class="form-group">
        <label for="">Đường dẫn tĩnh</label>
        <input type="text" value="<?php echo old('slug', $old) ?>" placeholder="Đường dẫn tĩnh..." name='slug' class="form-control auto-slug">
        <?php echo form_error('slug', $errors, '<span class="error">', '</span>');
        ?>

        <p class="render-link"><b>Link</b>: <span></span></p>

    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="<?php echo getLinkAdmin('blog_categories', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>

</form>