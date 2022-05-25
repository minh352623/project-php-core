<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Thiết lập trang chủ",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);

// updateOptions();
if (isPost()) {
    $homeSlideJson = '';
    if (!empty(getBody()['home_slide'])) {
        $homeSlide = getBody()['home_slide'];


        $homeSlideArr = [];
        if (!empty($homeSlide['slide_title'])) {
            foreach ($homeSlide['slide_title'] as $key => $value) {
                $homeSlideArr[] = [
                    'slide_title' => $value,
                    'slide_button_text' => ($homeSlide['slide_button_text'][$key]) ? ($homeSlide['slide_button_text'][$key]) : '',
                    'slide_button_link' => ($homeSlide['slide_button_link'][$key]) ? ($homeSlide['slide_button_link'][$key]) : '',
                    'slide_youtube' => ($homeSlide['slide_youtube'][$key]) ? ($homeSlide['slide_youtube'][$key]) : '',
                    'slide_image_1' => ($homeSlide['slide_image_1'][$key]) ? ($homeSlide['slide_image_1'][$key]) : '',
                    'slide_image_2' => ($homeSlide['slide_image_2'][$key]) ? ($homeSlide['slide_image_2'][$key]) : '',
                    'slide_bg' => ($homeSlide['slide_bg'][$key]) ? ($homeSlide['slide_bg'][$key]) : '',
                    'slide_desc' => ($homeSlide['slide_desc'][$key]) ? ($homeSlide['slide_desc'][$key]) : '',
                    'slide_position' => ($homeSlide['slide_position'][$key]) ? ($homeSlide['slide_position'][$key]) : 'left'


                ];
            }

            $homeSlideJson = json_encode($homeSlideArr);
        }
    }
    $homeAbout = [];
    if (!empty(getBody()['home_about'])) {
        $homeAbout['infomation'] = json_encode(getBody()['home_about']);
    }
    $skillJson = '';
    if (!empty(getBody()['home_about']['skill'])) {
        $skillArr = [];
        if (!empty(getBody()['home_about']['skill']['name'])) {
            $skillNameArr = getBody()['home_about']['skill']['name'];
            foreach ($skillNameArr as $key => $value) {
                $skillArr[] = [
                    'name' => $value,
                    'value' => getBody()['home_about']['skill']['value'][$key]
                ];
            }
            $skillJson = json_encode($skillArr);
        }
    }
    $homeAbout['skill'] = $skillJson;
    $homeAboutJson = json_encode($homeAbout);
    // echo '<pre>';
    // print_r($homeAbout);
    // echo '</pre>';
    // die();
    $data = [
        "home_slide" => $homeSlideJson,
        'home_about' => $homeAboutJson
    ];
    updateOptions($data);



    /**
     * cấu trúc mảng cần chuyển
     */
}
// echo '<pre>';
// print_r($homeSlideArr);
// echo '</pre>';
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
        <h5>Thiết lập slide</h5>

        <div class="slide-warpper">
            <?php
            $homeSlideJson = getOptions('home_slide');
            if (!empty($homeSlideJson)) {
                $homeSlideArr = json_decode($homeSlideJson, true);
                if (!empty($homeSlideArr)) {
                    foreach ($homeSlideArr as $item) {
            ?>
                        <div class="slide-item">
                            <div class="row">
                                <div class="col-11">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Tiêu đề</label>
                                                <input type="text" placeholder="Tiêu đề.." name="home_slide[slide_title][]" value="<?php echo $item['slide_title'] ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Nút xem thêm</label>
                                                <input name="home_slide[slide_button_text][]" value="<?php echo $item['slide_button_text'] ?>" placeholder="Chữ của nút..." class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Link xem thêm</label>
                                                <input name="home_slide[slide_button_link][]" value="<?php echo $item['slide_button_link'] ?>" placeholder="Link của nút..." class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Link youtube</label>
                                                <input name="home_slide[slide_youtube][]" value="<?php echo $item['slide_youtube'] ?>" placeholder="Link của nút..." class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Ảnh 1</label>
                                                <div class="row ckfinder-group">
                                                    <div class="col-10">

                                                        <input type="text" value="<?php echo $item['slide_image_1'] ?>" class="form-control image-reder" placeholder="Đường dẫn ảnh..." name="home_slide[slide_image_1][]">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-success  btn-block choose-image"><i class="fas fa-upload"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Ảnh 2</label>
                                                <div class="row ckfinder-group">
                                                    <div class="col-10">

                                                        <input type="text" class="form-control image-reder" value="<?php echo $item['slide_image_2'] ?>" placeholder="Đường dẫn ảnh..." name="home_slide[slide_image_2][]">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-success   btn-block choose-image"><i class="fas fa-upload"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Ảnh nền</label>
                                                <div class="row ckfinder-group">
                                                    <div class="col-10">

                                                        <input type="text" class="form-control image-reder" value="<?php echo $item['slide_bg'] ?>" placeholder="Đường dẫn ảnh..." name="home_slide[slide_bg][]">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-success   btn-block choose-image"><i class="fas fa-upload"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Mô tả</label>
                                                <textarea name="home_slide[slide_desc][]" placeholder="Mô tả slide..." class="form-control"><?php echo $item['slide_desc'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Vị trí</label>
                                                <select name="home_slide[slide_position][]" class="form-control">
                                                    <option value="left" <?php echo $item['slide_position'] == 'left' ? 'selected' : false ?>>Trái</option>
                                                    <option value="center" <?php echo $item['slide_position'] == 'center' ? 'selected' : false ?>>Giữa</option>
                                                    <option value="right" <?php echo $item['slide_position'] == 'right' ? 'selected' : false ?>>Phải</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <a href="#" style="font-size:20px;" class="btn remove btn-block btn-danger">&times;</a>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                }
            }
            ?>

        </div>
        <p>

            <button type="button" class="btn btn-warning add-slide">Thêm slide</button>
        </p>
        <h5>Thiết lập giới thiệu</h5>
        <?php
        $homeAbout = getOptions('home_about');
        $homeAboutArr = [];
        $homeAboutInfo = [];
        $homeAboutSkill = [];
        if (!empty($homeAbout)) {
            $homeAboutArr  = json_decode($homeAbout, true);
            $homeAboutInfo = json_decode($homeAboutArr['infomation'], true);
            $homeAboutSkill = json_decode($homeAboutArr['skill'], true);

            // echo '<pre>';
            // print_r($homeAboutArr);
            // echo '</pre>';
        }
        ?>
        <div class="form-group">
            <label for="">Tiêu đề nền</label>
            <input type="text" placeholder="Tiêu đề nền..." value="<?php echo !empty($homeAboutInfo['title_bg']) ? $homeAboutInfo['title_bg'] : false; ?>" name="home_about[title_bg]" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Mô tả</label>
            <textarea type="text" placeholder="Mô tả..." name="home_about[desc]" class="form-control editor"><?php echo !empty($homeAboutInfo['desc']) ? $homeAboutInfo['desc'] : false; ?></textarea>
        </div>
        <div class="form-group">
            <label for="">Hình ảnh</label>
            <div class="row ckfinder-group">
                <div class="col-10">

                    <input type="text" class="form-control image-reder" value="<?php echo !empty($homeAboutInfo['image']) ? $homeAboutInfo['image'] : false; ?>" name="home_about[image]" laceholder="Đường dẫn ảnh..." name="">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-success   btn-block choose-image"><i class="fas fa-upload"></i></button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="">Video</label>
            <input type="text" placeholder="Link video youtube..." value="<?php echo !empty($homeAboutInfo['video']) ? $homeAboutInfo['video'] : false; ?>" name="home_about[video]" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Nội dung giới thiệu</label>
            <textarea type="text" placeholder="Nội dung giới thiệu..." name="home_about[content]" class="form-control editor"><?php echo !empty($homeAboutInfo['content']) ? $homeAboutInfo['content'] : false; ?></textarea>
        </div>
        <h5>Thiết lập năng lực</h5>
        <div class="skill-wrapper">
            <?php if (!empty($homeAboutSkill)) :
                // echo '<pre>';
                // print_r($homeAboutSkill);
                // echo '</pre>';
                foreach ($homeAboutSkill as $key => $item) :

            ?>
                    <div class="skill-item">
                        <div class="row">
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Tên năng lực</label>
                                            <input type="text" name="home_about[skill][name][]" value="<?php echo $item['name'] ?>" placeholder="Tên năng lực..." class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Giá trị</label>
                                            <input type="text" value="<?php echo $item['value'] ?>" name="home_about[skill][value][]" class="ranger form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1">
                                <a href="#" class="btn btn-danger btn-block remove">&times;</a>
                            </div>
                        </div>

                    </div>
            <?php endforeach;
            endif; ?>


        </div>
        <p>

            <button type="button" class="btn btn-warning add-skill">Thêm năng lực</button>
        </p>
        <!--End skill-->
        <h5>Thiết lập dịch vụ</h5>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_service_title_bg', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_service_title_bg'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_service_title_bg">

                </div>

            </div>
            <?php echo form_error('home_service_title_bg', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_service_title', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_service_title'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_service_title">

                </div>

            </div>
            <?php echo form_error('home_service_title', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_service_desc', 'label'); ?></label>
            <textarea type="text" class="form-control slug" placeholder="Hotline..." name="home_service_desc"><?php echo getOptions('home_service_desc') ?></textarea>
            <?php echo form_error('home_service_desc', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <h5>Thiết lập thành tựu</h5>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_face_title', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_face_title'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_face_title">

                </div>

            </div>
            <?php echo form_error('home_face_title', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_face_sub_title', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_face_sub_title'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_face_sub_title">

                </div>

            </div>

        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_face_desc', 'label'); ?></label>
            <textarea type="text" class="form-control slug" placeholder="Hotline..." name="home_face_desc"><?php echo getOptions('home_face_desc') ?></textarea>
            <?php echo form_error('home_face_desc', $errors, '<span class="error">', '</span>');
            ?>
        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_face_button_text', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_face_button_text'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_face_button_text">

                </div>

            </div>

        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_face_button_link', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_face_button_link'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_face_button_link">

                </div>

            </div>

        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_face_year_number', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_face_year_number'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_face_year_number">

                </div>

            </div>

        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_face_project_number', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_face_project_number'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_face_project_number">

                </div>

            </div>

        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_face_earn_number', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_face_earn_number'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_face_earn_number">

                </div>

            </div>

        </div>
        <div class="form-group">
            <label for=""><?php echo getOptions('home_face_awards_number', 'label'); ?></label>
            <div class="row ckfinder-group">
                <div class="col-12">
                    <input type="text" class="form-control" value="<?php echo getOptions('home_face_awards_number'); ?>" placeholder="Từ khóa tìm kiếm..." name="home_face_awards_number">

                </div>

            </div>

        </div>
        <button type="submit" class="btn  btn-primary">Lưu thay đổi</button>
        <a href="<?php echo getLinkAdmin('blog', 'lists') ?>" class="btn  btn-success">Quay lại</a>
    </form>
</section>
</div>


<?php

layout('footer', 'admin', $data);
