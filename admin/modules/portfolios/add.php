<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Thêm dự án",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);
//lấy userid đăng nhập
$userId = isLogin()['user_id'];
//xử lý thêm nhóm người dùng
if (isPost()) {
    $body = getBody(); //lấy tất cả dữ liệu trong from

    //mảng lưu trữ các lỗi
    $errors = [];

    //validate họ tên => bắt buộc phải nhập, >= 4 kí tự
    if (empty(trim($body['name']))) {
        $errors['name']['reuired'] =  'Tên dự án bắt buộc phải nhập';
    }

    //validate đường dẫn tĩnh => bắt buộc phải nhập, 
    if (empty(trim($body['slug']))) {
        $errors['slug']['reuired'] =  'Đường dẫn tĩnh bắt buộc phải nhập';
    }

    if (empty(trim($body['content']))) {
        $errors['content']['reuired'] =  'Nội dung bắt buộc phải nhập';
    }
    //vallidate video
    if (empty(trim($body['video']))) {
        $errors['video']['reuired'] =  'Link video bắt buộc phải nhập';
    }
    //validate danh mục
    if (empty(trim($body['portfolio_category_id']))) {
        $errors['portfolio_category_id']['reuired'] =  'Danh mục bắt buộc phải nhập';
    }
    //valdate ảnh đại diện
    if (empty(trim($body['thumbnail']))) {
        $errors['thumbnail']['reuired'] =  'Ảnh đại bắt buộc phải nhập';
    }
    //validate thêm ảnh dự án: bắt buộc nhập khi đã bấm vào thêm ảnh
    $galleryArr = $body['gallery'];
    if (!empty($galleryArr)) {
        foreach ($galleryArr as $key => $item) {
            if (empty(trim($item))) {

                $errors['gallery']['reuired'][$key] = 'Vui lòng chọn ảnh';
            }
        }
    }

    if (empty($errors)) {
        //không có lỗi xảy ra 

        $dataInsert = [
            'name' => trim($body['name']),
            'slug' => trim($body['slug']),
            'content' => trim($body['content']),
            'user_id' => $userId,
            'description' => trim($body['description']),
            'video' => trim($body['video']),
            'portfolio_category_id' => trim($body['portfolio_category_id']),
            'thumbnail' => trim($body['thumbnail']),

            'create_at' => date('Y-m-d H:i:s'),
        ];

        print_r($dataInsert);
        $insertStatus = insert('portfolios', $dataInsert);
        // $id = insertId();
        // echo $id;
        // $insertStatus = false;
        if ($insertStatus) {
            //xử lý thêm ảnh dự án
            $currentId = insertId(); //lấy id vừa insert
            if (!empty($galleryArr)) {
                foreach ($galleryArr as $key => $item) {
                    if (!empty($item)) {

                        $dataImages = [
                            'portfolio_id' => $currentId,
                            'image' => trim($item),
                            'create_at' => date('Y-m-d H:i:s')
                        ];
                        insert('portfolio_images', $dataImages);
                    }
                }
            }

            setFlashData('msg', 'Thêm dự án thành công!');
            setFlashData('msg_type', 'success');
            redirect('admin?module=portfolios');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
            redirect('admin?module=portfolios&action=add'); //load lại dự án
        }
    } else {
        // die();
        //có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        //hàm reload lại dự án 
        redirect('admin?module=portfolios&action=add');
    }
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
$old = getFlashData('old');
print_r($old);
//truy vấn lấy danh sách danh mục
$allCate = getRaw("SELECT * FROM portfolio_categories ORDER BY name");
?>


<!-- Main content -->
<section class="content">
    <?php
    getMessage($msg, $msgtype);
    ?>
    <form action="" method="post" class="form">
        <div class="form-group">
            <label for="">Tên dự án</label>
            <input type="text" class="form-control slug" value="<?php echo old('name', $old) ?>" placeholder="Tên dự án..." name="name">
            <?php echo form_error('name', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Đường dẫn tĩnh</label>
            <input type="text" class="form-control auto-slug" value="<?php echo old('slug', $old) ?>" placeholder="Đường dẫn tĩnh..." name="slug">
            <?php echo form_error('slug', $errors, '<span class="error">', '</span>');
            ?>
            <p class="render-link"><b>Link</b>: <span></span></p>
        </div>
        <div class="form-group">
            <label for="">Mô tả</label>
            <textarea type="text" name="description" placeholder="Mô tả ngắn..." class="form-control"><?php echo old('description', $old) ?></textarea>


        </div>
        <div class="form-group">
            <label for="">Nội dung</label>
            <textarea type="text" class="form-control editor" placeholder="Nội dung..." name="content"><?php echo old('content', $old) ?></textarea>
            <?php echo form_error('content', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Link video</label>
            <input type="url" class="form-control" placeholder="Link video youtube..." name="video" value="<?php echo old('video', $old) ?>">
            <?php echo form_error('video', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Chọn danh mục</label>
            <select name="portfolio_category_id" class="form-control">
                <option value="0">Chọn danh mục</option>
                <?php
                if (!empty($allCate)) {
                    foreach ($allCate as $item) {
                ?>
                        <option value="<?php echo $item['id'] ?>" <?php echo ((old('portfolio_category_id', $old) == $item['id']) ? 'selected' : false) ?>><?php echo $item['name'] ?></option>
                <?php        }
                }
                ?>
            </select>
            <?php echo form_error('portfolio_category_id', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">ẢNh đại diện</label>
            <div class="row ckfinder-group">
                <div class="col-10">

                    <input type="text" class="form-control image-reder" value="<?php echo old('thumbnail', $old) ?>" placeholder="Đường dẫn ảnh..." name="thumbnail">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-success btn-sm btn-block choose-image">Chọn ảnh</button>
                </div>
            </div>
            <?php echo form_error('thumbnail', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for="">Ảnh dự án</label>
            <div class="gallery-images">
                <?php
                $oldGallery = old('gallery', $old);
                if ($oldGallery) {
                    $galleryError = $errors['gallery'];
                    // echo '<pre>';
                    // print_r($galleryError);
                    // echo '</pre>';

                    foreach ($oldGallery as $key => $item) {
                ?>
                        <div class="gallery-item">
                            <div class="row">
                                <div class="col-11">
                                    <div class="row ckfinder-group">
                                        <div class="col-10">

                                            <input type="text" class="form-control image-reder" value="<?php echo (!empty($item)) ? $item : false; ?>" placeholder="Đường dẫn ảnh..." name="gallery[]">
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-success  btn-block choose-image">Chọn ảnh</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-1">
                                    <a href="#" class="btn  btn-danger btn-block remove"><i class="fa fa-times"></i></a>
                                </div>
                                <?php echo (!empty($galleryError['reuired'][$key]) ? '<span class="error" style="margin-left:8px;">' . $galleryError['reuired'][$key] . '</span>' : false);
                                ?>
                            </div>

                        </div>
                <?php
                    }
                }
                ?>


            </div>
            <p style="margin-top:10px;">

                <a href="#" class="btn btn-sm btn-warning add-gallery">Thêm ảnh</a>
            </p>
        </div>
        <button type="submit" class="btn btn-sm btn-primary">Thêm mới</button>
        <a href="<?php echo getLinkAdmin('portfolios', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>


<?php

layout('footer', 'admin', $data);
