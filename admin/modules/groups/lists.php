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
$keyword = '';
if (isGet()) {
    $body = getBody();
    // print_r($body);


    //xử lý lọc theo từ khóa
    if (!empty($body['keyword'])) {

        $keyword  = $body['keyword'];
    }
    if (!empty($keyword)) {

        $filter .= "WHERE name LIKE '%$keyword%'";
    }
    // echo $body['keyword'];
}

//xử lý phân trang
//1.lấy số lượng bản ghi nhóm người dùng 
$allGroupNum = getRow("SELECT id FROM groups $filter");
//2.xác định số bản ghi trên 1 trang
$perPage = _PER_PAGE; //1 trang có 2 bản ghi

//3.tính số trang dựa trên số bản ghi đang có
$maxPage = ceil($allGroupNum / $perPage); //hàm làm tròn lên

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
$listGroups = getRaw("SELECT * FROM `groups` $filter ORDER BY create_at DESC LIMIT $offset,$perPage");
// echo '<pre>';
// print_r($listGroups);
// echo '</pre>';


$queryStr = null;
if (!empty($_SERVER['QUERY_STRING'])) {

    $queryStr =  $_SERVER['QUERY_STRING'];
    //thay thế &module=users thành rỗng
    $queryStr = str_replace('module=groups', '', $queryStr);
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

        <a href="<?php echo getLinkAdmin('groups', 'add') ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Thêm nhóm mới</a>
        <hr>
        <form action="" method="get">
            <div class="row form-group">
                <div class="col-9 ">
                    <input type="search" class="form-control" value="<?php echo !empty($keyword) ? $keyword : false ?>" name="keyword" placeholder="Nhập tên nhóm cần tìm...">
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary btn-block">Tìm Kiếm</button>
                </div>
            </div>
            <input type="hidden" name="module" value="groups">
        </form>
        <hr>
        <?php
        getMessage($msg, $msgtype);
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th>Tên nhóm</th>
                    <th>Thời gian</th>
                    <th width="10%">Phân Quyền</th>

                    <th width="10%">Sửa</th>
                    <th width="10%">xóa</th>

                </tr>
            </thead>
            <tbody>
                <?php if (!empty($listGroups)) :
                    foreach ($listGroups as $key => $item) :
                ?>
                        <tr>
                            <td> <?php echo $key + 1 ?></td>
                            <td><a href="<?php echo getLinkAdmin('groups', 'edit', ['id' => $item['id']]) ?>"><?php echo $item['name'] ?></a> </td>
                            <td> <?php echo getDateFormat($item['create_at'], 'd/m/Y H:i:s') ?></td>
                            </td>
                            <td class="text-center"><a href="" class="btn btn-sm btn-primary">Phân quyền</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('groups', 'edit', ['id' => $item['id']]) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Sửa</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('groups', 'delete', ['id' => $item['id']]) ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Xóa</a></td>
                        </tr>
                    <?php endforeach;
                else : ?>

                    <tr>
                        <td colspan="6" class="text-center">Không có nhóm người dùng</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="d-flex justify-content-end">
            <ul class="pagination text-center">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=groups' . $queryStr . '&page=' . $prevPage . '">Trước</a></li>';
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
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=groups' . $queryStr . '&page=' . $i ?>"><?php echo $i ?></a></li>
                <?php } ?>

                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=groups' . $queryStr . '&page=' . $nextPage . '">Sau</a></li>';
                }
                ?>

            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
