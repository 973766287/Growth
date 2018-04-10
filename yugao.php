<?php
require_once('load.php');
$action = isset($_GET['act']) && !empty($_GET['act']) ? $_GET['act'] : "default";
switch ($action) {
    case 'login': //用户登录
        $app->action('user', 'login');
        break;
    default:
        $app->action('user', 'error_jump');
        break;
}
?>