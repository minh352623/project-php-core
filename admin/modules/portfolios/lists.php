<?php
//file này hiển thị danh sách dự án
if (!defined('_INCODE')) die('access define...');
// echo 'day laf list cua module portfolios';

// var_dump(_INCODE);


$data = [
    'pageTitle' => "Quản lí dự án"
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);
//lấy portfolio_category_id  đang đăng nhập

//xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();
    // print_r($body);
    //xử lý lọc user_id 
    if (!empty($body['user_id'])) {
        $user_id = $body['user_id'];
        // echo $user_id;
        if ($user_id == 2) {

            $user_idSql = 0;
        } else {
            $user_idSql = $user_id;
        }
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        // echo $user_idSql;
        $filter .= "$operator  portfolios.user_id = $user_idSql";
    }

    //xử lý lọc theo từ khóa
    if (!empty($body['keyword'])) {
        $keyword  = $body['keyword'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator  portfolios.name LIKE '%$keyword%'";
        // echo $body['keyword'];
    }

    //xử lý lọc theo danh mục
    if (!empty($body['cate_id'])) {
        $cateId = $body['cate_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }

        $filter .= " $operator portfolio_category_id  = $cateId";
    }
}

// echo $filter;


//xử lý phân trang
//số lượng portfolios đang có
$allUserNum = getRow("SELECT id FROM portfolios $filter");
// echo $allUserNum;
//1. xác định dc số lượng bản ghi trên 1 trang
$perPage = _PER_PAGE; //1 trang có 3 bản ghi

//2.tính số trang dựa trên số bản ghi đang có

$maxPage = ceil($allUserNum / $perPage); //hàm làm tròn lên
// echo $maxPage;


//3.xử lý số trang dựa vào phương thúc get
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}
// echo $page;
//4.tính toán offset trong limit dựa vào biến $page
/**
 * $page =1 => offset = 0
 * $page =2 => offset = 3
 * $page =3 => offset = 6
 * => công thức ($page -1 )*$perPage
 * 
 */
$offset = ($page - 1) * $perPage;
//5.truy vấn lấy tất cả bản ghi
$listAllportfolios = getRaw("SELECT users.fullname as name_users,users.id as user_id, portfolios.id,portfolios.name,portfolios.create_at,portfolio_categories.name as cate_name,portfolio_categories.id as cate_id FROM portfolios INNER JOIN portfolio_categories ON portfolios.portfolio_category_id  = `portfolio_categories`.id INNER JOIN users ON users.id=portfolios.user_id  $filter ORDER BY portfolios.create_at  DESC LIMIT $offset,$perPage");
// print_r($listAllportfolios);
// print_r($listAllportfolios);

//lấy ra danh sách tất cả các danh mục
$allportfolio_categories = getRaw("SELECT id,name FROM portfolio_categories ORDER BY name");
//truy vấn lấy danh sách người dùng
$allUser = getRaw("SELECT * FROM users ORDER BY fullname");
// echo '<pre>';
// print_r($allportfolio_categories);
// echo '</pre>';



//xử lý query srting tìm kiếm với phân trang
$queryStr = null;
if (!empty($_SERVER['QUERY_STRING'])) {

    $queryStr =  $_SERVER['QUERY_STRING'];
    //thay thế &module=portfolios thành rỗng
    $queryStr = str_replace('module=portfolios', '', $queryStr);
    $queryStr = str_replace('&page=' . $page, '', $queryStr);
    $queryStr = trim($queryStr, '&');
    $queryStr = '&' . $queryStr;
}
// echo $queryStr;

$msg = getFlashData('msg');
// print_r($msg);
$msgtype = getFlashData('msg_type');
?>

<section class="content">
    <div class="container-fluid">
        <a href="<?php echo getLinkAdmin('portfolios', 'add') ?>" class="btn btn-primary my-3"><i class="fa fa-plus"></i>Thêm dự án </a>
        <?php
        getMessage($msg, $msgtype);
        ?>
        <form action="" method="get">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <select name="cate_id" class="form-control">
                            <option value="0">Chọn danh mục</option>
                            <?php if (!empty($allportfolio_categories)) {
                                foreach ($allportfolio_categories as $group) {
                            ?>
                                    <option value="<?php echo $group['id'] ?>" <?php echo (!empty($cateId) && $cateId == $group['id']) ? 'selected' : false; ?>><?php echo $group['name'] ?></option>
                            <?php
                                }
                            } ?>


                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <select name="user_id" class="form-control">
                            <option value="0">Chọn người đăng</option>
                            <?php if (!empty($allUser)) {
                                foreach ($allUser as $user) {
                            ?>
                                    <option value="<?php echo $user['id'] ?>" <?php echo (!empty($userId) && $user['id'] == $userId) ? 'selected' : null ?>><?php echo $user['fullname'] . ' (' . $user['email'] . ')' ?></option>
                            <?php
                                }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <input type="search" name="keyword" class="form-control" placeholder="Search..." value="<?php echo (!empty($keyword)) ? $keyword : false; ?>">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary d-block w-100">Tìm kiếm</button>
                </div>
                <input type="hidden" name="module" value="portfolios">
            </div>
        </form>
        <table class=" table table-bordered">
            <thead>

                <tr>

                    <th width="5%">STT</th>
                    <th>Tên</th>
                    <th>Danh mục</th>
                    <th>Đăng bởi</th>
                    <th width="15%">Thời gian</th>
                    <th width="10%">Sửa</th>
                    <th width="10%">Xóa</th>
                </tr>

            </thead>
            <tbody>
                <?php
                if (!empty($listAllportfolios)) :
                    $count = 0; //hiển thị số thứ tự
                    foreach ($listAllportfolios as $item) :
                        $count++;

                ?>
                        <tr>
                            <td><?php echo $count ?></td>
                            <td><a href="<?php echo getLinkAdmin('portfolios', 'edit', ['id' => $item['id']]) ?>"><?php echo $item['name'] ?></a> <a href="<?php echo getLinkAdmin('portfolios', 'duplicate', ['id' => $item['id']]) ?>" style="padding:0 5px;" class="btn btn-sm btn-danger">Nhân bản</a></td>
                            <td><a href="?<?php echo getLinkQueryString('cate_id', $item['cate_id']) ?>"><?php echo $item['cate_name'] ?></a></td>
                            <td><a href="?<?php echo getLinkQueryString('user_id', $item['user_id']) ?>"><?php echo $item['name_users'] ?></a></td>
                            <td><?php echo !empty($item['create_at']) ? getDateFormat($item['create_at'], 'd/m/Y H:i:s') : false; ?></td>

                            <td><a href="<?php echo getLinkAdmin('portfolios', 'edit', ['id' => $item['id']]) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>Sửa</a></td>
                            <td>
                                <a href="<?php echo getLinkAdmin('portfolios', 'delete', ['id' => $item['id']]) ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i>Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="alert alert-danger text-center">Không có dự án</div>
                        </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="d-flex justify-content-end">
            <ul class="pagination text-center">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=portfolios' . $queryStr . 'page=' . $prevPage . '">Trước</a></li>';
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
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=portfolios' . $queryStr . 'page=' . $i ?>"><?php echo $i ?></a></li>
                <?php } ?>

                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=portfolios' . $queryStr . 'page=' . $nextPage . '">Sau</a></li>';
                }
                ?>

            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>


<?php
layout('footer', 'admin', $data);
