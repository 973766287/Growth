<?php
require_once('load.php');

if($_POST['action']){
	$app->action('supplier',$_POST['action'],$_POST);
	exit;
}
$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'supplier_list';
$app->action('supplier',$type,$_GET);



?>