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
        $filter .= " $operator name LIKE '%$keyword%'";
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
        $filter .= " $operator user_id =$user_id";
        // echo $body['keyword'];
    }
}
// echo $filter;

//xử lý phân trang
//1.lấy số lượng bản ghi dịch vụ
$allServiceNum = getRow("SELECT id FROM `services` $filter");
//2.xác định số bản ghi trên 1 trang
$perPage = _PER_PAGE; //1 trang có 2 bản ghi

//3.tính số trang dựa trên số bản ghi đang có
$maxPage = ceil($allServiceNum / $perPage); //hàm làm tròn lên

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



//lấy dư liệu dịch vụ
$listServices = getRaw("SELECT services.id,name,icon,fullname,services.create_at,users.id as user_id FROM services INNER JOIN users ON users.id = services.user_id $filter ORDER BY services.create_at DESC LIMIT $offset,$perPage");
// echo '<pre>';
// print_r($listServices);
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
    $queryStr = str_replace('module=services', '', $queryStr);
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
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <a href="<?php echo getLinkAdmin('services', 'add') ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Thêm dịch vụ</a>
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
                <div class="col-6 ">
                    <input type="search" class="form-control" value="<?php echo !empty($keyword) ? $keyword : false ?>" name="keyword" placeholder="Nhập tên dịch vụ cần tìm...">
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary btn-block">Tìm Kiếm</button>
                </div>
            </div>
            <input type="hidden" name="module" value="services">
        </form>
        <hr>
        <?php
        getMessage($msg, $msgtype);
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="15%">Ảnh</th>
                    <th>Tên </th>
                    <th width="15%">Đăng bởi</th>
                    <th width="10%">Thời gian</th>

                    <th width="10%">Xem</th>

                    <th width="10%">Sửa</th>
                    <th width="10%">xóa</th>

                </tr>
            </thead>
            <tbody>
                <?php if (!empty($listServices)) :
                    foreach ($listServices as $key => $item) :
                ?>
                        <tr>
                            <td> <?php echo $key + 1 ?></td>
                            <td><?php echo (isFontIcon($item['icon'])) ? html_entity_decode($item['icon']) : '<img src="' . $item['icon'] . '" width="100"/>'  ?></td>
                            <td><a href="<?php echo getLinkAdmin('services', 'edit', ['id' => $item['id']]) ?>"><?php echo $item['name'] ?></a> <a href="<?php echo getLinkAdmin('services', 'duplicate', ['id' => $item['id']]) ?>" style="padding:0 5px;" class="btn btn-sm btn-danger">Nhân bản</a> </td>
                            <td><a href="?<?php echo getLinkQueryString('user_id', $item['user_id']) ?>"><?php echo $item['fullname'] ?></a></td>
                            <td> <?php echo getDateFormat($item['create_at'], 'd/m/Y H:i:s') ?></td>
                            </td>
                            <td class="text-center"><a href="" class="btn btn-sm btn-primary">Xem</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('services', 'edit', ['id' => $item['id']]) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Sửa</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('services', 'delete', ['id' => $item['id']]) ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Xóa</a></td>
                        </tr>
                    <?php endforeach;
                else : ?>

                    <tr>
                        <td colspan="8" class="text-center">Không có dịch vụ</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="d-flex justify-content-end">
            <ul class="pagination text-center">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=services' . $queryStr . '&page=' . $prevPage . '">Trước</a></li>';
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
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=services' . $queryStr . '&page=' . $i ?>"><?php echo $i ?></a></li>
                <?php } ?>

                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=services' . $queryStr . '&page=' . $nextPage . '">Sau</a></li>';
                }
                ?>

            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
