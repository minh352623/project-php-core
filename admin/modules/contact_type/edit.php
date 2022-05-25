<?php
//lấy userid đăng nhập
$body = getBody('get');
if (!empty($body['id'])) {

    $contact_typeId = $body['id'];
    //kiểm tra user Id có tồn tại trong database hong không
    //nếu tồn tại => lấy ra thong tin
    //không tồn tại => chuyển hướng vè trang list
    $contact_typeDetail = firstRaw("SELECT * FROM `contact_type` WHERE id = $contact_typeId");
    // print_r($contact_typeDetail);
    if (empty($contact_typeDetail)) {
        //nếu user này k tồn tại
        //gán giá trị userDetail vào flashData


        // print_r($contact_typeDetail);

        // setFlashData('contact_typeDetail', $userDetail);
        redirect('admin?module=contact_type');
    }
} else {
    redirect('admin?module=contact_type');
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




    if (empty($errors)) {
        //không có lỗi xảy ra 
        $dataUpdate = [
            'name' => trim($body['name']),

            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = "id=$contact_typeId";
        $updateStatus = update('contact_type', $dataUpdate, $condition);
        if ($updateStatus) {

            setFlashData('msg', 'Cập nhật phòng ban thành công!');
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
    redirect('admin?module=contact_type&action=lists&view=edit&id=' . $contact_typeId);
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
if (empty($old) && !empty($contact_typeDetail)) {
    $old = $contact_typeDetail;
}

?>

<h4>Cập nhật phòng ban</h4>
<form action="" method="post">
    <div class="form-group">
        <label for="">Tên dự án</label>
        <input type="text" value="<?php echo old('name', $old) ?>" placeholder="Tên blog..." name='name' class="form-control">
        <?php echo form_error('title', $errors, '<span class="error">', '</span>');
        ?>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="<?php echo getLinkAdmin('contact_type', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>

</form>