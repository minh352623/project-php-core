<?php
if (!defined('_INCODE')) die('access define...');

$data = [
    'pageTitle' => "Danh mục blog",
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);

//xử lý lọc dữ liệu
$filter = '';
$keyword = '';
$view = 'add';
$id = 0; //id mặc định
$body = getBody('get');
// print_r($body);


//xử lý lọc theo từ khóa
if (!empty($body['keyword'])) {

    $keyword  = $body['keyword'];
}
if (!empty($keyword)) {

    $filter .= "WHERE name LIKE '%$keyword%'";
}
// echo $body['keyword'];
if (!empty($body['view'])) {
    $view = $body['view'];
}
if (!empty($body['id'])) {
    $id = $body['id'];
}

//xử lý phân trang
//1.lấy số lượng bản ghi danh mục blog
$allBlogNum = getRow("SELECT id FROM blog_categories $filter");
//2.xác định số bản ghi trên 1 trang
$perPage = _PER_PAGE; //1 trang có 2 bản ghi

//3.tính số trang dựa trên số bản ghi đang có
$maxPage = ceil($allBlogNum / $perPage); //hàm làm tròn lên

//4.xử lý số trang dựa vào phương thúc get
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



//lấy dư liệu nhóm người dùng
$listblog_categories = getRaw("SELECT *,(SELECT count(blog.id) FROM blog WHERE category_id  = blog_categories.id ) as blog_count FROM `blog_categories` $filter ORDER BY create_at DESC LIMIT $offset,$perPage");
// echo '<pre>';
// print_r($listblog_categories);
// echo '</pre>';


$queryStr = null;
if (!empty($_SERVER['QUERY_STRING'])) {

    $queryStr =  $_SERVER['QUERY_STRING'];
    //thay thế &module=users thành rỗng
    $queryStr = str_replace('module=blog_categories', '', $queryStr);
    $queryStr = str_replace('&page=' . $page, '', $queryStr);
    $queryStr = trim($queryStr, '&');
    $queryStr = '&' . $queryStr;
}
$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php
        getMessage($msg, $msgtype);
        ?>
        <div class="row">
            <div class="col-6">
                <?php
                if (!empty($view) && !empty($id)) {
                    require_once $view . '.php';
                } else {

                    require_once 'add.php';
                }
                ?>
            </div>
            <div class="col-6">
                <h4>Danh sách danh mục</h4>
                <form action="" method="get">
                    <div class="row form-group">

                        <div class="col-9 ">
                            <input type="search" class="form-control" value="<?php echo !empty($keyword) ? $keyword : false ?>" name="keyword" placeholder="Nhập tên danh mục...">
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary btn-block">Tìm Kiếm</button>
                        </div>
                    </div>
                    <input type="hidden" name="module" value="blog_categories">
                </form>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">STT</th>
                            <th>Tên </th>
                            <th width="25%">Thời gian</th>


                            <th width="10%">Sửa</th>
                            <th width="10%">xóa</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listblog_categories)) :
                            foreach ($listblog_categories as $key => $item) :
                        ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><a href="<?php echo getLinkAdmin('blog_categories', '', ['id' => $item['id'], 'view' => 'edit']) ?>"><?php echo $item['name'] ?></a>(<?php echo $item['blog_count'] ?>)<a href="<?php echo getLinkAdmin('blog_categories', 'duplicate', ['id' => $item['id']]) ?>" style="padding:0 5px;" class="btn btn-sm btn-danger">Nhân bản</a><a href="#" style="padding:0 5px; margin-left:5px;" class="btn btn-sm btn-success" target="_blank">Xem</a></td>
                                    <td><?php echo !empty($item['create_at']) ? getDateFormat($item['create_at'], 'd/m/Y H:i:s') : '' ?></td>
                                    <td><a href="<?php echo getLinkAdmin('blog_categories', '', ['id' => $item['id'], 'view' => 'edit']) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> </a></td>
                                    <td><a href="<?php echo getLinkAdmin('blog_categories', 'delete', ['id' => $item['id']]) ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a></td>
                                </tr>
                            <?php endforeach;
                        else : ?>

                            <tr>
                                <td colspan="5" class="text-center">Không có danh mục blog</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
                <nav aria-label="Page navigation example" class="d-flex justify-content-end">
                    <ul class="pagination text-center">
                        <?php
                        if ($page > 1) {
                            $prevPage = $page - 1;
                            echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=blog_categories' . $queryStr . '&page=' . $prevPage . '">Trước</a></li>';
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
                            <li class="page-item <?php echo ($i == $page) ? 'active' : false ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=blog_categories' . $queryStr . '&page=' . $i ?>"><?php echo $i ?></a></li>
                        <?php } ?>

                        <?php
                        if ($page < $maxPage) {
                            $nextPage = $page + 1;
                            echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=blog_categories' . $queryStr . '&page=' . $nextPage . '">Sau</a></li>';
                        }
                        ?>

                    </ul>
                </nav>
            </div>
        </div>


    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
