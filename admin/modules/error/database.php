<?php
if(!defined('_INCODE')) die('access define...');

?>
<div style="text-align: center; width:600px;padding:20px 30px; margin: 0 auto;">
    <h2 style="text-transform: uppercase;">

        Lỗi liên quan đến cơ sở dữ liệu
    </h2>
    <hr>
    <p><?php echo $e->getMessage(); ?></p>
    <p>File:<?php echo $e->getFile(); ?></p>
    <p>Line <?php echo $e->getLine(); ?></p>


</div>