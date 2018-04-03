<?php

require_once("load.php");

if ($_POST['action']) {
    switch ($_POST['action']) {
        case 'delbags': //删除商品
            $app->action('gift_bag', 'ajax_delbags', $_POST['ids'], (isset($_POST['reduction']) ? $_POST['reduction'] : ""));
            break;
    }
}
$type = isset($_GET['type']) ? $_GET['type'] : "lists";

switch ($type) {
    case 'list':
        $app->action('gift_bag', 'lists');
        break;

    case 'bag_info':
        $app->action('gift_bag', 'bag_info', (isset($_GET['id']) ? $_GET['id'] : 0),$url);
        break;
    case 'bag_print':
        $app->action('gift_bag', 'bag_print');
        break;
    default:
        $app->action('gift_bag', $type, $_GET);
        break;
}
?>