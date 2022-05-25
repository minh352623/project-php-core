<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Thiết lập chung",
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
        <h5>Thông tin chung</h5>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_sitename', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('general_sitename'); ?>" placeholder="Hotline..." name="general_sitename">
            <?php echo form_error('general_sitename', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_sitedesc', 'label'); ?></label>
            <textarea type="text" class="form-control slug"  placeholder="Hotline..." name="general_sitedesc"><?php echo getOptions('general_sitedesc') ?></textarea>
            <?php echo form_error('general_sitedesc', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <hr>
        <h5>Thông tin liên hệ</h5>
        <hr>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_hotline', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('general_hotline'); ?>" placeholder="Hotline..." name="general_hotline">
            <?php echo form_error('general_hotline', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_email', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('general_email'); ?>" placeholder="Email..." name="general_email">
            <?php echo form_error('general_email', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_time', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('general_time'); ?>" placeholder="Thời gian làm việc..." name="general_time">
            <?php echo form_error('general_time', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_facebook', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('general_facebook'); ?>" placeholder="Facebook..." name="general_facebook">
            <?php echo form_error('general_facebook', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_twitter', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('general_twitter'); ?>" placeholder="Twitter..." name="general_twitter">
            <?php echo form_error('general_twitter', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_linkedin', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('general_linkedin'); ?>" placeholder="Linkedin..." name="general_linkedin">
            <?php echo form_error('general_linkedin', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_behance', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('general_behance'); ?>" placeholder="Behance..." name="general_behance">
            <?php echo form_error('general_behance', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('general_youtube', 'label'); ?></label>
            <input type="text" class="form-control slug" value="<?php echo getOptions('general_youtube'); ?>" placeholder="Youtube..." name="general_youtube">
            <?php echo form_error('general_youtube', $errors, '<span class="error">', '</span>');
            ?>
        </div>

        <button type="submit" class="btn btn-sm btn-primary">Lưu thay đổi</button>
        <a href="<?php echo getLinkAdmin('blog', 'lists') ?>" class="btn btn-sm btn-success">Quay lại</a>
    </form>
</section>
</div>


<?php

layout('footer', 'admin', $data);
