<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Danh sách nhóm",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);

//xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();
    // print_r($body);


    //xử lý lọc theo từ khóa
    if (!empty($body['keyword'])) {
        $keyword  = $body['keyword'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator (title LIKE '%$keyword%' OR content LIKE '%$keywork%')";
        // echo $body['keyword'];
    }

    //xử lý lọc theo user
    if (!empty($body['user_id'])) {
        $user_id  = $body['user_id'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator blog.user_id =$user_id";
        // echo $body['keyword'];
    }
    //xử lý lọc theo chuyên mục
    if (!empty($body['cate_id'])) {
        $cate_id  = $body['cate_id'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator blog.category_id  =$cate_id";
        // echo $body['keyword'];
    }
}
// echo $filter;

//xử lý phân blog
//1.lấy số lượng bản ghi blog
$allBlogNum = getRow("SELECT id FROM `blog` $filter");
//2.xác định số bản ghi trên 1 blog
$perPage = _PER_PAGE; //1 blog có 2 bản ghi

//3.tính số blog dựa trên số bản ghi đang có
$maxPage = ceil($allBlogNum / $perPage); //hàm làm tròn lên

//4.xử lý số blog dựa vào phương thúc get
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}
//5.tính toán offset trong limit dựa vào biến $page

$offset = ($page - 1) * $perPage;



//lấy dư liệu blog
$listblog = getRaw("SELECT blog.id,blog.`view_count`,category_id,title,fullname,blog.create_at,users.id as user_id,blog_categories.name as cate_name FROM blog INNER JOIN users ON  users.id = blog.user_id INNER JOIN blog_categories ON blog_categories.id = blog.category_id  $filter ORDER BY blog.create_at DESC LIMIT $offset,$perPage");
// echo '<pre>';
// print_r($listblog);
// echo '</pre>';

//lấy dữ liệu tất cả người dùng
$allUser = getRaw("SELECT id,fullname,email FROM users ORDER BY fullname");
// echo '<pre>';
// print_r($allUser);
// echo '</pre>';
$queryStr = null;
if (!empty($_SERVER['QUERY_STRING'])) {

    $queryStr =  $_SERVER['QUERY_STRING'];
    //thay thế &module=users thành rỗng
    $queryStr = str_replace('module=blog', '', $queryStr);
    $queryStr = str_replace('&page=' . $page, '', $queryStr);
    $queryStr = trim($queryStr, '&');
    $queryStr = '&' . $queryStr;
}
// echo $queryStr;
// echo '<pre>';

// print_r(getLinkQueryString($queryStr, 'user_id', 1));
// echo '</pre>';
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');

//lấy dữ liệu tất cả chuyên mục
$allCategories  = getRaw("SELECT id,name FROM blog_categories ORDER BY name");
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <a href="<?php echo getLinkAdmin('blog', 'add') ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Thêm blog</a>
        <hr>
        <form action="" method="get">
            <div class="row form-group">
                <div class="col-3">
                    <select name="user_id" class="form-control">
                        <option value="0">Chọn người đăng</option>
                        <?php
                        if (!empty($allUser)) {
                            foreach ($allUser as $user) {
                        ?>
                                <option value="<?php echo $user['id'] ?>" <?php echo (!empty($user_id) && $user_id == $user['id']) ? 'selected' : false ?>><?php echo $user['fullname'] . '  ('  . $user['email'] . ')' ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-3">
                    <select name="cate_id" class="form-control">
                        <option value="0">Chọn chuyên mục</option>
                        <?php
                        if (!empty($allCategories)) {
                            foreach ($allCategories as $item) {
                        ?>
                                <option value="<?php echo $item['id'] ?>" <?php echo (!empty($cate_id) && $item['id'] == $cate_id) ? 'selected' : null; ?>><?php echo $item['name'] ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-5 ">
                    <input type="search" class="form-control" value="<?php echo !empty($keyword) ? $keyword : false ?>" name="keyword" placeholder="Nhập từ khóa tìm kiếm...">
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-primary btn-block">Tìm Kiếm</button>
                </div>
            </div>
            <input type="hidden" name="module" value="blog">
        </form>
        <hr>
        <?php
        getMessage($msg, $msgtype);
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th>Tiêu đề </th>
                    <th width="15%">Chuyên mục</th>
                    <th width="15%">Đăng bởi</th>
                    <th width="10%">Thời gian</th>


                    <th width="10%">Sửa</th>
                    <th width="10%">xóa</th>

                </tr>
            </thead>
            <tbody>
                <?php if (!empty($listblog)) :
                    foreach ($listblog as $key => $item) :
                ?>
                        <tr>
                            <td> <?php echo $key + 1 ?></td>
                            <td><a href="<?php echo getLinkAdmin('blog', 'edit', ['id' => $item['id']]) ?>"><?php echo !empty($item['title']) ? $item['title'] : false; ?></a> <br><a href="<?php echo getLinkAdmin('blog', 'duplicate', ['id' => $item['id']]) ?>" style="padding:0 5px;" class="btn btn-sm btn-danger">Nhân bản</a><span class="btn btn-success btn-sm" style="padding:0 5px; margin-left:4px;"><?php echo $item['view_count'] ?> lượt xem</span><a href="#" class="btn btn-primary btn-sm" style="padding:0 5px; margin-left:4px" target="_blank">Xem</a> </td>
                            <td><a href="?<?php echo getLinkQueryString('cate_id', $item['category_id']) ?>"><?php echo $item['cate_name']; ?></a></td>
                            <td><a href="?<?php echo getLinkQueryString('user_id', $item['user_id']) ?>"><?php echo $item['fullname'] ?></a></td>
                            <td> <?php echo getDateFormat($item['create_at'], 'd/m/Y H:i:s') ?></td>
                            </td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('blog', 'edit', ['id' => $item['id']]) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Sửa</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('blog', 'delete', ['id' => $item['id']]) ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Xóa</a></td>
                        </tr>
                    <?php endforeach;
                else : ?>

                    <tr>
                        <td colspan="8" class="text-center">Không có blog</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="d-flex justify-content-end">
            <ul class="pagination text-center">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=blog' . $queryStr . '&page=' . $prevPage . '">Trước</a></li>';
                }
                ?>

                <?php
                $begin = $page - 2;
                if ($begin < 1) {
                    $begin = 1;
                }
                $end  = $page + 2;
                if ($end > $maxPage) {
                    $end = $maxPage;
                }
                for ($i = $begin; $i <= $end; $i++) {


                ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=blog' . $queryStr . '&page=' . $i ?>"><?php echo $i ?></a></li>
                <?php } ?>

                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=blog' . $queryStr . '&page=' . $nextPage . '">Sau</a></li>';
                }
                ?>

            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
