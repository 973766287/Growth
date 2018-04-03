<?php
require_once('load.php');

$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'list';

switch($type){
	case 'list':
		$app->action('bank','index');
		break;	
	case 'info':
		$app->action('bank','info',isset($_GET['id'])?$_GET['id'] : 0);
		break;
	default:
		die("没有定义页面");
		break;
}
?>