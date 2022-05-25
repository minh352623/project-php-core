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
        $filter .= " $operator (fullname LIKE '%$keyword%' OR email LIKE '%$keyword%' OR message LIKE '%$keyword%')";
        // echo $body['keyword'];
    }
    //xử lý lọc theo phòng ban
    if (!empty($body['type_id'])) {
        $type_id  = $body['type_id'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator contacts.type_id  =$type_id";
        // echo $body['keyword'];
    }
}
// echo $filter;

//xử lý phân contacts
//1.lấy số lượng bản ghi contacts
$allcontactsNum = getRow("SELECT id FROM `contacts` $filter");
//2.xác định số bản ghi trên 1 contacts
$perPage = _PER_PAGE; //1 contacts có 2 bản ghi

//3.tính số contacts dựa trên số bản ghi đang có
$maxPage = ceil($allcontactsNum / $perPage); //hàm làm tròn lên

//4.xử lý số contacts dựa vào phương thúc get
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



//lấy dư liệu contacts
$listcontacts = getRaw("SELECT contacts.id,type_id,fullname,email,contacts.create_at,message,status,note,contact_type.name as type_name FROM contacts INNER JOIN contact_type ON  contacts.type_id = contact_type.id $filter ORDER BY contacts.create_at DESC LIMIT $offset,$perPage");
// echo '<pre>';
// print_r($listcontacts);
// echo '</pre>';
$queryStr = null;
if (!empty($_SERVER['QUERY_STRING'])) {

    $queryStr =  $_SERVER['QUERY_STRING'];
    //thay thế &module=users thành rỗng
    $queryStr = str_replace('module=contacts', '', $queryStr);
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
$allContactType  = getRaw("SELECT id,name FROM contact_type ORDER BY name");
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <form action="" method="get">
            <div class="row form-group">
                <div class="col-3">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="0">Chọn trạng thái</option>
                            <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : false; ?>>Đã xử lý</option>
                            <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : false; ?>>Chưa xử lý</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <select name="type_id" class="form-control">
                        <option value="0">Chọn phòng ban</option>
                        <?php
                        if (!empty($allContactType)) {
                            foreach ($allContactType as $item) {
                        ?>
                                <option value="<?php echo $item['id'] ?>" <?php echo (!empty($type_id) && $item['id'] == $type_id) ? 'selected' : null; ?>><?php echo $item['name'] ?></option>
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
            <input type="hidden" name="module" value="contacts">
        </form>
        <hr>
        <?php
        getMessage($msg, $msgtype);
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="20%">Thông tin</th>
                    <th>Nội dung liên hệ</th>
                    <th width="10%">Trạng thái</th>
                    <th width="10%">Ghi chú</th>
                    <th width="10%">Thời gian</th>




                    <th width="10%">Hành động</th>

                </tr>
            </thead>
            <tbody>
                <?php if (!empty($listcontacts)) :
                    foreach ($listcontacts as $key => $item) :
                ?>
                        <tr>
                            <td> <?php echo $key + 1 ?></td>
                            <td>
                                Họ tên: <?php echo $item['fullname'] ?>
                                <br />
                                Email: <?php echo $item['email'] ?>
                                <br />
                                Phòng ban: <?php echo $item['type_name'] ?>
                            </td>
                            <td>
                                <?php echo $item['message'] ?>
                            </td>

                            <td><?php echo $item['status'] == 1 ? '<button type="button" class="btn btn-success">Đã xử lý</button>' : '<button type="button" class="btn btn-warning">Chưa xử lý</button>'; ?></td>
                            <td>
                                <?php echo $item['note'] ?>
                            </td>
                            <td> <?php echo getDateFormat($item['create_at'], 'd/m/Y H:i:s') ?></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('contacts', 'delete', ['id' => $item['id']]) ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Xóa</a><br /><br /><a href="<?php echo getLinkAdmin('contacts', 'edit', ['id' => $item['id']]) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Sửa</a></td>
                        </tr>
                    <?php endforeach;
                else : ?>

                    <tr>
                        <td colspan="8" class="text-center">Không có liên hệ</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="d-flex justify-content-end">
            <ul class="pagination text-center">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=contacts' . $queryStr . '&page=' . $prevPage . '">Trước</a></li>';
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
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=contacts' . $queryStr . '&page=' . $i ?>"><?php echo $i ?></a></li>
                <?php } ?>

                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?module=contacts' . $queryStr . '&page=' . $nextPage . '">Sau</a></li>';
                }
                ?>

            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
