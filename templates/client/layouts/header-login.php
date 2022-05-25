<?php
if (!defined('_INCODE')) die('access define...');
saveActivity(); //lưu lại hoạt động cuối cùng của user
autoRemoveTokenLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/style.css?ver=<?php echo rand(); ?>">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : ''; ?></title>
</head>

<body>