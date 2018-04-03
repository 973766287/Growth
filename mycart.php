<?php

require_once('load.php');
//echo '<script>alert("网站升级中");location.href="index.php";</script>';exit;
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'changeprice':
            $app->action('shopping', 'ajax_change_price', $_POST);
            break;
        case 'delcartid':
            $app->action('shopping', 'ajax_delcart_goods', isset($_POST['id']) ? $_POST['id'] : 0);
            break;
    }
    exit;
}

$type = !isset($_REQUEST['type']) || empty($_REQUEST['type']) ? 'cartlist' : $_REQUEST['type'];
switch ($type) {
    case 'cartlist':
        $app->action('shopping', 'index');
        break;
    case 'clear':
        $app->action('shopping', 'mycart_clear');
        break;
    case 'checkout':
        $app->action('shopping', 'checkout');
        break;
    case 'confirm':
        $app->action('shopping', 'confirm');
        break;
    case 'fastcheckout':
        $app->action('shopping', 'fastcheckout');
        break;
    case 'pay2':
        $app->action('shopping', 'pay2');
        break;
    case 'fastpay2':
        $app->action('shopping', 'fastpay2');
        break;
    default:
        $app->action('shopping', 'mycart_list');
        break;
}
?>