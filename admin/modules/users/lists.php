<?php
//file này hiển thị danh sách người dùng
if (!defined('_INCODE')) die('access define...');
// echo 'day laf list cua module users';
// var_dump(_INCODE);
$data = [
    'pageTitle' => "Quản lí người dùng"
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);


layout('breadcrumb', 'admin', $data);
//lấy userId đang đăng nhập
$userId = isLogin()['user_id'];

//xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();
    // print_r($body);
    //xử lý lọc status 
    if (!empty($body['status'])) {
        $status = $body['status'];
        // echo $status;
        if ($status == 2) {

            $statusSql = 0;
        } else {
            $statusSql = $status;
        }
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        // echo $statusSql;
        $filter .= "$operator status = $statusSql";
    }

    //xử lý lọc theo từ khóa
    if (!empty($body['keyword'])) {
        $keyword  = $body['keyword'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator fullname LIKE '%$keyword%'";
        // echo $body['keyword'];
    }

    //xử lý lọc theo group
    if (!empty($body['group_id'])) {
        $groupId = $body['group_id'];

        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }

        $filter .= " $operator group_id = $groupId";
    }
}

// echo $filter;


//xử lý phân trang
//số lượng users đang có
$allUserNum = getRow("SELECT id FROM users $filter");
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
$listAllusers = getRaw("SELECT users.id,fullname,email,`status`,users.create_at,groups.name as groups_name FROM users INNER JOIN `groups` ON users.group_id = `groups`.id $filter ORDER BY users.create_at  DESC LIMIT $offset,$perPage");
// print_r($listAllusers);
// print_r($listAllusers);

//lấy ra danh sách tất cả các nhóm
$allGroups = getRaw("SELECT id,name FROM groups ORDER BY name");

// echo '<pre>';
// print_r($allGroups);
// echo '</pre>';



//xử lý query srting tìm kiếm với phân trang
$queryStr = null;
if (!empty($_SERVER['QUERY_STRING'])) {

    $queryStr =  $_SERVER['QUERY_STRING'];
    //thay thế &module=users thành rỗng
    $queryStr = str_replace('module=users', '', $queryStr);
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
        <a href="<?php echo getLinkAdmin('users', 'add') ?>" class="btn btn-primary my-3"><i class="fa fa-plus"></i>Thêm người dùng </a>
        <?php
        getMessage($msg, $msgtype);
        ?>
        <form action="" method="get">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <select name="group_id" class="form-control">
                            <option value="0">Chọn nhóm</option>
                            <?php if (!empty($allGroups)) {
                                foreach ($allGroups as $group) {
                            ?>
                                    <option value="<?php echo $group['id'] ?>" <?php echo (!empty($groupId) && $groupId == $group['id']) ? 'selected' : false; ?>><?php echo $group['name'] ?></option>
                            <?php
                                }
                            } ?>


                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="0">Chọn trạng thái</option>
                            <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : false; ?>>Kích hoạt</option>
                            <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : false; ?>>Chưa kích hoạt</option>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <input type="search" name="keyword" class="form-control" placeholder="Search..." value="<?php echo (!empty($keyword)) ? $keyword : false; ?>">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary d-block w-100">Tìm kiếm</button>
                </div>
                <input type="hidden" name="module" value="users">
            </div>
        </form>
        <table class=" table table-bordered">
            <thead>

                <tr>

                    <th width="5%">STT</th>
                    <th>Họ Tên</th>
                    <th>Email</th>
                    <th>Thời gian</th>
                    <th>Nhóm</th>
                    <th>Trạng thái</th>
                    <th width="10%">Sửa</th>
                    <th width="10%">Xóa</th>
                </tr>

            </thead>
            <tbody>
                <?php
                if (!empty($listAllusers)) :
                    $count = 0; //hiển thị số thứ tự
                    foreach ($listAllusers as $item) :
                        $count++;

                ?>
                        <tr>
                            <td><?php echo $count ?></td>
                            <td><a href="<?php echo getLinkAdmin('users', 'edit', ['id' => $item['id']]) ?>"><?php echo $item['fullname'] ?></a> </td>
                            <td><?php echo $item['email'] ?></td>
                            <td><?php echo !empty($item['create_at']) ? getDateFormat($item['create_at'], 'd/m/Y H:i:s') : false; ?></td>
                            <td><?php echo $item['groups_name'] ?></td>
                            <td><?php echo $item['status'] == 1 ? '<button type="button" class="btn btn-success">Kích Hoạt</button>' : '<button type="button" class="btn btn-warning">Chưa kích Hoạt</button>'; ?></td>

                            <td><a href="<?php echo getLinkAdmin('users', 'edit', ['id' => $item['id']]) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>Sửa</a></td>
                            <td>
                                <?php if ($item['id'] != $userId) : ?>
                                    <a href="<?php echo getLinkAdmin('users', 'delete', ['id' => $item['id']]) ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i>Xóa</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="alert alert-danger text-center">Không có người dùng</div>
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
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT . '?module=users' . $queryStr . '&page=' . $prevPage . '">Trước</a></li>';
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
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT . '?module=users' . $queryStr . '&page=' . $i ?>"><?php echo $i ?></a></li>
                <?php } ?>

                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT . '?module=users' . $queryStr . '&page=' . $nextPage . '">Sau</a></li>';
                }
                ?>

            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>


<?php
layout('footer', 'admin', $data);
