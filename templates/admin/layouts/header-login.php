<?php
if (!defined('_INCODE')) die('access define...');
// saveActivity(); //lưu lại hoạt động cuối cùng của user
// autoRemoveTokenLogin();
autoRemoveTokenLogin();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATE ?>/assets/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATE ?>/assets/css/adminlte.min.css">

    <link rel="stylesheet" href="<?php echo _WEB_HOST_ADMIN_TEMPLATE ?>/assets/css/auth.css?ver=<?php echo rand(); ?>">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : ''; ?></title>
</head>

<body>