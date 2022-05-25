<?php
$title = getOptions('home_face_title');
// echo $title;
$subTitle = getOptions('home_face_sub_title');
$desc = getOptions('home_face_desc');
$buttonText = getOptions('home_face_button_text');
$buttonLink = getOptions('home_face_button_link');
$yearNumber = getOptions('home_face_year_number');
$projectNumber = getOptions('home_face_project_number');
$earnNumber = getOptions('home_face_earn_number');
$awardNumber = getOptions('home_face_awards_number');

?>
<!-- Fun Facts -->
<section id="fun-facts" class="fun-facts section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 wow fadeInLeft" data-wow-delay="0.5s">
                <div class="text-content">
                    <div class="section-title">
                        <?php if (!empty($title) || !empty($subTitle)) : ?>
                            <h1><span><?php echo !empty($title) ? $title : false ?></span><?php echo !empty($subTitle) ? $subTitle : false ?></h1>
                        <?php endif; ?>
                        <p><?php echo !empty($desc) ? $desc : false ?></p>
                        <?php if (!empty($buttonLink)) : ?>
                            <a href="<?php echo !empty($buttonLink) ? $buttonLink : false ?>" class="btn primary"><?php echo !empty($buttonText) ? $buttonText : 'CONTACT' ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-12">
                <div class="row">
                    <?php if (!empty($yearNumber)) : ?>
                        <div class="col-lg-6 col-md-6 col-12 wow fadeIn" data-wow-delay="0.6s">
                            <!-- Single Fact -->
                            <div class="single-fact">
                                <div class="icon"><i class="fa fa-clock-o"></i></div>
                                <div class="counter">
                                    <p><span class="count"><?php echo $yearNumber ?></span></p>
                                    <h4>Năm thành lập</h4>
                                </div>
                            </div>
                            <!--/ End Single Fact -->
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($projectNumber)) : ?>

                        <div class="col-lg-6 col-md-6 col-12 wow fadeIn" data-wow-delay="0.8s">
                            <!-- Single Fact -->
                            <div class="single-fact">
                                <div class="icon"><i class="fa fa-bullseye"></i></div>
                                <div class="counter">
                                    <p><span class="count"><?php echo $projectNumber ?></span>K</p>
                                    <h4>Dự án thành công</h4>
                                </div>
                            </div>
                            <!--/ End Single Fact -->
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($earnNumber)) : ?>

                        <div class="col-lg-6 col-md-6 col-12 wow fadeIn" data-wow-delay="1s">
                            <!-- Single Fact -->
                            <div class="single-fact">
                                <div class="icon"><i class="fa fa-dollar"></i></div>
                                <div class="counter">
                                    <p><span class="count"><?php echo $earnNumber ?></span>M</p>
                                    <h4>Tổng danh thu</h4>
                                </div>
                            </div>
                            <!--/ End Single Fact -->
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($awardNumber)) : ?>

                        <div class="col-lg-6 col-md-6 col-12 wow fadeIn" data-wow-delay="1.2s">
                            <!-- Single Fact -->
                            <div class="single-fact">
                                <div class="icon"><i class="fa fa-trophy"></i></div>
                                <div class="counter">
                                    <p><span class="count"><?php echo $awardNumber ?></span></p>
                                    <h4>Giải thưởng</h4>
                                </div>
                            </div>
                            <!--/ End Single Fact -->
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Fun Facts -->