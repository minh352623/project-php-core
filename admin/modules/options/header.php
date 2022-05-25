<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Thiết lập header",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);

updateOptions();

$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
// print_r($msgtype);


$errors = getFlashData('errors');
// print_r($allCategories);
?>


<!-- Main content -->
<section class="content">
    <?php
    getMessage($msg, $msgtype);
    ?>
    <form action="" method="post" class="form">
        <h5>Thiết lập Logo - favicon</h5>
        <div class="form-group">
            <label for=""><?php echo getOptions('header_logo', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-10">
                    <input type="text" class="form-control image-reder" value="<?php echo getOptions('header_logo'); ?>" placeholder="Từ khóa tìm kiếm..." name="header_logo">

                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
                </div>
            </div>
            <?php echo form_error('header_logo', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('header_favicon', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-10">
                    <input type="text" class="form-control image-reder" value="<?php echo getOptions('header_favicon'); ?>" placeholder="Từ khóa tìm kiếm..." name="header_favicon">

                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
                </div>
            </div>
            <?php echo form_error('header_favicon', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <h5>Thiết lập tìm kiếm</h5>
        <hr>
        <div class="form-group">
            <label for=""><?php echo getOptions('header_placeholder', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('header_placeholder'); ?>" placeholder="Từ khóa tìm kiếm..." name="header_placeholder">
            <?php echo form_error('header_placeholder', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <hr>
        <h5>Thiết lập khác</h5>
        <div class="form-group">
            <label for=""><?php echo getOptions('header_quote_text', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('header_quote_text'); ?>" placeholder="Từ khóa tìm kiếm..." name="header_quote_text">
            <?php echo form_error('header_quote_text', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('header_quote_link', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('header_quote_link'); ?>" placeholder="Từ khóa tìm kiếm..." name="header_quote_link">
            <?php echo form_error('header_quote_link', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <button type="submit" class="btn btn-sm btn-primary">Lưu thay đổi</button>
        <a href="<?php echo getLinkAdmin('blog', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>
</div>


<?php

layout('footer', 'admin', $data);
