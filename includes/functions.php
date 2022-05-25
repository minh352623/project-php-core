<?php
//odirjiorctbhqaqi

if (!defined('_INCODE')) die('access define...');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layout($layoutName = 'header', $dir = '', $data = "")
{
    if (!empty($dir)) {
        $dir = '/' . $dir;
    }
    if (file_exists(_WEB_PATH_TEMPLATE . $dir . '/layouts/' . $layoutName . '.php')) {
        require_once _WEB_PATH_TEMPLATE  . $dir . '/layouts/' . $layoutName . '.php';
    }
}

function sendMail($to, $subtext, $content)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'minhb1910259@student.ctu.edu.vn';                     //SMTP username
        $mail->Password   = 'odirjiorctbhqaqi';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('minhb1910259@student.ctu.edu.vn', 'test chức năng gửi mail khi đăng kí');
        $mail->addAddress($to);     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo($to);
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->CharSet = "UTF-8";
        $mail->Subject = $subtext;
        $mail->Body    = $content;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        return $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


//kiểm tra phương thức post
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}

function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return  false;
}

//lấy giá trị phương thức POST,GET
function getBody($method = '')
{
    $bodyArr = [];
    if (empty($method)) {

        if (isGet()) {
            // return $_GET;
            //đọc key của mảng $_GET
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    //loại bỏ kí tự html trong $key
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {

                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
            // filter_input(); 
        }
        if (isPost()) {
            // return $_GET;
            //đọc key của mảng $_GET
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    //loại bỏ kí tự html trong $key
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {

                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
            // filter_input(); 
        }
    } else {
        if ($method == 'get') {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    //loại bỏ kí tự html trong $key
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {

                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        } elseif ($method == 'post') {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    //loại bỏ kí tự html trong $key
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {

                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }
    return $bodyArr;
}

//kiểm tra email

function isEmail($email)
{
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

//kiểm tra só nguyên
function isNumberInt($number, $range = [])
{
    /**
     * range = ['min_range'=>1,'max_range'=>20]
     */
    if (!empty($range)) {

        $options = ['options' => $range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT, $options);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    }
    return $checkNumber;
}
//kiểm tra số thực

function isNumberFloat($number, $range = [])
{
    /**
     * range = ['min_range'=>1,'max_range'=>20]
     */
    if (!empty($range)) {

        $options = ['options' => $range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT, $options);
    } else {
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    }
    return $checkNumber;
}

//kiểm tra sdt   bắt đàu bằng số 0
function isPhone($phone)
{
    $checkFirstZero = false;
    if ($phone[0] == '0') {
        $checkFirstZero = true;
        $phone = substr($phone, 1);
    }

    $checkNunberLast = false;
    if (isNumberInt($phone) && strlen($phone) == 9) {
        $checkNunberLast = true;
    }
    if ($checkFirstZero && $checkNunberLast) {
        return true;
    }
    return false;
}

//hàm tạo thông báo
function getMessage($msg, $type = 'success')
{
    if (!empty($msg)) {
        echo '<div class="alert alert-' . $type . '">';
        echo $msg;
        echo '</div>';
    }
}

//hàm chuyển hướng
function redirect($path)
{
    $url = _WEB_HOST_ROOT . '/' . $path;
    header('Location: ' . $url);
    exit;
}

//thông báo lỗi
function form_error($fieldname, $errors, $beforeHtmls = '', $afterHtmls = '')
{
    return (!empty($errors[$fieldname])) ? $beforeHtmls . reset($errors[$fieldname]) . $afterHtmls : null;
}

function old($fieldname, $olddata, $default = null)
{
    return !empty($olddata[$fieldname]) ? $olddata[$fieldname] : $default;
}

//hàm kiểm tra trạng thái đăng nhập
function  isLogin()
{
    $checkLogin = false;
    if (getSession('login_token')) {
        $tokenLogin = getSession('login_token');
        // print_r($tokenLogin);
        // if (!is_array($tokenLogin)) {

        $queryToken = firstRaw("SELECT user_id FROM login_token WHERE token='$tokenLogin'");
        // }

        if (!empty($queryToken)) {
            // $checkLogin=true;
            $checkLogin = $queryToken;
        } else {

            removeSession('login_token');
        }
    }
    return $checkLogin;
}

//tự động xóa token nếu đăng xuất nếu đăng xuất(xóa token login)
function autoRemoveTokenLogin()
{
    $allUsers  = getRaw("SELECT * FROM users WHERE status = 1");
    if (!empty($allUsers)) {
        foreach ($allUsers as $user) {

            $now = date("Y-m-d H:i:s");
            // echo strtotime($now); //quy đổi về sô giây
            $before = $user['last_activity'];
            $diff = strtotime($now) - strtotime($before);
            $diff = floor($diff / 60);
            // echo $diff;
            if ($diff > 1) {
                delete('login_token', "user_id =" . $user['id']);
            }
        }
    }
    // echo floor($diff / 60);
    // echo '<pre>';
    // print_r($allUsers);
    // echo '</pre>';
}


//lưu lại thời gian cuối cùng hoạt động
function saveActivity()
{
    if (!empty(isLogin()['user_id'])) {

        $user_id = isLogin()['user_id'];
        // echo $user_id;
        update('users', ['last_activity' => date('Y-m-d H:i:s')], "id=$user_id");
    }
}


//lấy thông tin users
function getUserInfo($user_id)
{

    $info = firstRaw("SELECT * FROM users WHERE id = '$user_id'");
    return $info;
}

//action menu sidebar
function activeMenuSidebar($module)
{

    if (!empty(getBody()['module'])) {
        if (getBody()['module'] == $module) {
            return true;
        }
    }
    if (empty(getBody()['module']) && empty($module)) {
        return true;
    }
    return false;
}

//get link 
function getLinkAdmin($module, $action = '', $params = [])
{
    $url = _WEB_HOST_ROOT_ADMIN;
    $url .= '?module=' . $module;
    if (!empty($action)) {
        $url .= '&action=' . $action;
    }
    if (!empty($params)) {
        //hàm http giúp chuyển dữ liệu từ dạng mảng sang dạng liên kết url
        $paramsStrings  = http_build_query($params);
        $url .= '&' . $paramsStrings;
    }
    return $url;
}


//format đate 
function getDateFormat($strDate, $format)
{
    $dataObject = date_create($strDate);
    if (!empty($dataObject)) {
        return date_format($dataObject, $format);
    }
    return false;
}


//check font-awesome-icon
function isFontIcon($input)
{
    $input = html_entity_decode($input);
    if (strpos($input, '<i class="') !== false) {
        return true;
    }
    return false;
}

//upload queryString

function getLinkQueryString($key, $value)
{
    $queryString = $_SERVER['QUERY_STRING'];
    $queryArr = explode('&', $queryString);
    // print_r($queryArr);
    $queryArr = array_filter($queryArr);
    $queryFinal = '';
    $check = false;
    // echo 'abc';
    if (!empty($queryArr)) {
        foreach ($queryArr as $item) {
            $itemArr = explode('=', $item);
            if (!empty($itemArr)) {
                if ($itemArr[0] == $key) {
                    $itemArr[1] = $value;
                    $check = true;
                }
                $item = implode('=', $itemArr);
                $queryFinal .= $item . '&';
            }
        }
    }
    if (!$check) {
        $queryFinal .= $key . '=' . $value;
    }
    if (!empty($queryFinal)) {
        $queryFinal = rtrim($queryFinal, '&');
    } else {
        $queryFinal = $queryString;
    }
    return $queryFinal;
}
function setExceptionErrors($exception)
{

    if (_DEBUG) {

        $debugError = getFlashData('debug_error');
        setFlashData('debug_error', [
            'error_code' => $exception->getCode(),
            'error_message' => $exception->getMessage(),
            'error_file' => $exception->getFile(),
            'error_line' => $exception->getLine()
        ]);
        $reload = getFlashData('reload');
        if (!$reload) {
            setFlashData('reload', 1);
            if (isAdmin()) {

                redirect(getPathAdmin());
            } else {
                redirect(getPath());
            }
        }
    } else {
        // removeSession('reload');
        // removeSession('debug_error');
        require_once _WEB_PATH_ROOT . '/modules/error/500.php';
    }
}

function loadExceptionError()
{
    $debugError = getFlashData('debug_error');


    if (!empty($debugError)) {
        if (_DEBUG) {

            require_once _WEB_PATH_ROOT . '/modules/error/exception.php';
        } else {

            require_once _WEB_PATH_ROOT . '/modules/error/500.php';
        }
    }
}

function setErrorHanlder($errno, $errstr, $errfile, $errline)
{
    if (!_DEBUG) {

        removeSession('reload');
        removeSession('debug_error');
        require_once _WEB_PATH_ROOT . '/modules/error/500.php';

        return;
    }
    setFlashData('debug_error', [
        'error_code' => $errno,
        'error_message' => $errstr,
        'error_file' => $errfile,
        'error_line' => $errline
    ]);
    $reload = getFlashData('reload');
    if (!$reload) {
        setFlashData('reload', 1);
        if (isAdmin()) {

            redirect(getPathAdmin());
        } else {
            redirect(getPath());
        }
    }
    // throw new ErrorException($errstr, 1, $errno, $errfile, $errline);
}
function getPathAdmin()
{
    $path = 'admin';
    if (!empty($_SERVER['QUERY_STRING'])) {
        $path .= '?' . trim($_SERVER['QUERY_STRING']);
    }
    return $path;
}
function getPath()
{
    $path = '';
    if (!empty($_SERVER['QUERY_STRING'])) {
        $path .= '?' . trim($_SERVER['QUERY_STRING']);
    }
    return $path;
}
function isAdmin()
{
    if (!empty($_SERVER['PHP_SELF'])) {
        $currentFile = $_SERVER['PHP_SELF'];
        $dirFile = dirname($currentFile);
        $baseNameDir = basename($dirFile);
        if (trim($baseNameDir) == "admin") {
            return true;
        }
    }
    return false;
    // echo '<pre>';
    // print_r($_SERVER);
    // echo '</pre>';
}
function getOptions($key, $type = '')
{
    $sql = "SELECT * FROM options WHERE opt_key='$key'";
    $options = firstRaw($sql);
    if (!empty($options)) {
        if ($type == 'label') {
            return $options['name'];
        }
        return $options['opt_value'];
    }
}

function updateOptions($data = [])
{
    if (isPost()) {
        $allField = getBody();
        if (!empty($data)) {
            $keysData = array_keys($data);
            $valuesData = array_values($data);

            foreach ($keysData as $key => $value) {
                $allField[$value] = $valuesData[$key];
            }
        }
        // echo '<pre>';
        // print_r($allField);
        // echo '</pre>';
        $countUpdate = 0;
        if (!empty($allField)) {
            foreach ($allField as $key => $value) {
                $dataUpdate = [
                    "opt_value" => trim($value)
                ];
                $condition = "opt_key='$key'";
                // echo $condition;
                $updateStatus = update('options', $dataUpdate, $condition);
                if ($updateStatus) {
                    $countUpdate++;
                }
            }
        }
        if ($countUpdate > 0) {
            setFlashData('msg', 'Đã cập nhật ' . $countUpdate . ' bản ghi thành công');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Cập nhật không thành công');
            setFlashData('msg_type', 'danger');
        }
        redirect(getPathAdmin()); //reload lại trang
    }
}
function getCountContacts()
{
    $sql = "SELECT id FROM contacts WHERE status = 0";
    $count = getRow($sql);
    return $count;
}
function head()
{
?>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ROOT; ?>/templates/core/css/style.css?ver=<?php echo rand(); ?>">

<?php
}
function foot()
{
}
