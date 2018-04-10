<?php

require_once('load.php');
//echo '<script>alert("网站升级中");location.href="index.php";</script>';exit;
$id = isset($_GET['id'])&&!empty($_GET['id']) ? intval($_GET['id']) : 0;
$app->action('product','index',$id);
?>