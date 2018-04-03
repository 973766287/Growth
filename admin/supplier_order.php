<?php
require_once('load.php');

if($_POST['action']){
	switch($_POST['action']){
		case 'jiesuan':
			$app->action('supplier','ajax_supplier_jiesuan',$_POST['ids'],$_POST['rid']); //批量操作
			break;
		}
	exit;
}
$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'supplier_rebate_list';
$app->action('supplier',$type,$_GET);



?>