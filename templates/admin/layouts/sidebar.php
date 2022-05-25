<?php

$userId = isLogin()['user_id'];
// echo $userId;
$userDetail = getUserInfo($userId);
?>


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo _WEB_HOST_ROOT_ADMIN ?>" class="brand-link">
        <span class="brand-text font-weight-light text-uppercase">Radix Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE ?>/assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?php echo getLinkAdmin('users', 'profile') ?>" class="d-block"><?php echo $userDetail['fullname']  ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- trang tổng quan -->
                <li class="nav-item">
                    <a href="<?php echo _WEB_HOST_ROOT_ADMIN ?>" class="nav-link <?php echo activeMenuSidebar('') ? 'active' : false  ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Tổng quan
                        </p>
                    </a>
                </li>
                <!-- quản lí dịch vụ begin -->

                <li class="nav-item <?php echo activeMenuSidebar('services') ? 'menu-open' : false ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('services') ? 'active' : false ?>">
                        <i class="fab fa-servicestack"></i>
                        <p>
                            Quản lí dịch vụ
                            <i class="fas fa-angle-left right"></i>
                            <!-- <span class="badge badge-info right">6</span> -->
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=services' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=services&action=add' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- quản lí dịch vụ end -->


                <!-- quản lí nhóm người begin -->

                <li class="nav-item <?php echo activeMenuSidebar('groups') ? 'menu-open' : false ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('groups') ? 'active' : false ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Nhóm người dùng
                            <i class="fas fa-angle-left right"></i>
                            <!-- <span class="badge badge-info right">6</span> -->
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=groups' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=groups&action=add' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <!-- quản lí nhóm người dùng end -->


                <!-- quản lí người dùng begin -->

                <li class="nav-item <?php echo activeMenuSidebar('users') ? 'menu-open' : false ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('users') ? 'active' : false ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Quản lí người dùng
                            <i class="fas fa-angle-left right"></i>
                            <!-- <span class="badge badge-info right">6</span> -->
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=users' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=users&action=add' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- quản lí người dùng end -->
                <!-- quản lí trang -->
                <li class="nav-item <?php echo activeMenuSidebar('pages') ? 'menu-open' : false ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('pages') ? 'active' : false ?>">
                        <i class="nav-icon far fa-file"></i>
                        <p>
                            Quản lí trang
                            <i class="fas fa-angle-left right"></i>
                            <!-- <span class="badge badge-info right">6</span> -->
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=pages' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=pages&action=add' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- end quản lí trang -->
                <!-- quản lí dự án -->
                <li class="nav-item <?php echo activeMenuSidebar('portfolios') || activeMenuSidebar('portfolio_categories') ? 'menu-open' : false ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('portfolios') || activeMenuSidebar('portfolio_categories') ? 'active' : false ?>">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        <p>
                            Quản lí dự án
                            <i class="fas fa-angle-left right"></i>
                            <!-- <span class="badge badge-info right">6</span> -->
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=portfolios' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách dự án</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=portfolios&action=add' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới dự án</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=portfolio_categories' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh mục dự án</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- end quản lí trang -->
                <!-- quản lí blog begin -->
                <li class="nav-item <?php echo activeMenuSidebar('blog') || activeMenuSidebar('blog_categories') ? 'menu-open' : false ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('blog') || activeMenuSidebar('blog_categories') ? 'active' : false ?>">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p>
                            Quản lí blog
                            <i class="fas fa-angle-left right"></i>
                            <!-- <span class="badge badge-info right">6</span> -->
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=blog' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách blog</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=blog&action=add' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới blog</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=blog_categories' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh mục blog</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- quản lí blog end -->
                <!-- Cấu hình website begin -->

                <li class="nav-item <?php echo activeMenuSidebar('options') ? 'menu-open' : false ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('options') ? 'active' : false ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Thiết lập
                            <i class="fas fa-angle-left right"></i>
                            <!-- <span class="badge badge-info right">6</span> -->
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=options&action=general' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thiết lập chung</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=options&action=header' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thiết lập header</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=options&action=footer' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thiết lập footer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=options&action=home' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thiết lập trang chủ</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- Cấu hình website end -->
                <!-- quản lí liên hệ begin -->

                <li class="nav-item <?php echo activeMenuSidebar('contacts') || activeMenuSidebar('contact_type') ? 'menu-open' : false ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('contacts') || activeMenuSidebar('contact_type') ? 'active' : false ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Quản lí liên hệ<span class="badge badge-danger"><?php echo getCountContacts() ?></span>
                            <i class="fas fa-angle-left right"></i>
                            <!-- <span class="badge badge-info right">6</span> -->
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=contacts' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách <span class="badge badge-danger"><?php echo getCountContacts() ?></span></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=contact_type' ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Quản lí phòng ban</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!-- quản lí liên hệ end -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<div class="content-wrapper">