<?php
require_once('load.php');

//ajax操作
if($_POST['action']){
	switch($_POST['action']){
		case 'deladmin':
		    $app->action('manager','ajax_deladmin',($_POST['id'] ? $_POST['id'] : 0));
			break;
				
			case 'deldaili':
		    $app->action('manager','ajax_deldaili',($_POST['id'] ? $_POST['id'] : 0));
			break;
			
		case 'addmanmger':
			$data['adminname'] = $_POST['uname'];
			$data['password'] = md5(trim($_POST['pass']));
                        $data['priv_password'] = md5(trim($_POST['priv_password']));
			$data['email'] = $_POST['email'];
			//$groupid = $_POST['groupid'];
			$data['addtime'] = time();
			$data['groupid'] = $_POST['groupid'];
		    $app->action('manager','ajax_addmanmger',$data,$_POST['aid']);
			break;
			
			case 'adddailimanmger':
			$data['adminname'] = $_POST['uname'];
			$data['password'] = md5(trim($_POST['pass']));
                        $data['priv_password'] = md5(trim($_POST['priv_password']));
			
			$data['addtime'] = time();
			$data['groupid'] = $_POST['groupid'];
		    $app->action('manager','ajax_adddailimanmger',$data,$_POST['aid']);
			break;
			
		case 'addgroup':
			$data['groupname'] = $_POST['gname'];
			$data['active'] = $_POST['active'];
			$data['remark'] = $_POST['remark'];
			$data['option_group'] = $_POST['groupvar'];
			$data['addtime'] = time(); 
			 $app->action('manager','ajax_addgroup',$data,$_POST['gid']);
			break;
		case 'delgroup':
		    $app->action('manager','ajax_delgroup',($_POST['gid'] ? $_POST['gid'] : 0));
			break;
		case 'activeop':
			 $data['active'] = $_POST['active'];
			 $data['addtime'] = time();
			 $app->action('manager','ajax_addgroup',$data,$_POST['gid']);
			break;
		case 'dellog':
			$app->action('manager','ajax_dellog',$_POST['logid']);
			break;
		case 'delmes':
			$app->action('manager','ajax_delmes',$_POST['tids']);
			break;
		case 'savemes':
			$app->action('manager','ajax_savemes',$_POST);
			break;
			
			case 'CreateInviteCode':
			$app->action('manager','ajax_CreateInviteCode');
			break;
			
			case 'distribution':
			$app->action('manager','ajax_distribution',$_POST);
			break;
		default:
			$app->action('manager',$_POST['action'],$_POST);
			break;
	}
	exit;
}
if(isset($_GET['type'])){
	switch($_GET['type']){
		case 'list':
			$app->action('manager','managerlist');
			break;
			case 'daililist':
			$app->action('manager','managerdaililist');
			break;
			
		case 'add':
		case 'edit':
			$app->action('manager','manageredit',$_GET['type'],($_GET['id'] ?  $_GET['id'] : 0));
			break;
			
		case 'dailiadd':
		case 'dailiedit':
			$app->action('manager','managerdailiedit',$_GET['type'],($_GET['id'] ?  $_GET['id'] : 0));
			break;
			
		case 'dailiset':
		    $app->action('manager','managerdailiset');
			break;
			
			case 'distribution':
		    $app->action('manager','distribution');
			break;
			
			case 'insteadorder_summary':
		    $app->action('manager','insteadorder_summary');
			break;
			
			case 'export_invitecode':
		    $app->action('manager','export_invitecode');
			break;
			
		case 'Invitecode':
		    $app->action('manager','Invitecodelist');
			break; 
			
			case 'insteadorder':
		    $app->action('manager','insteadorderlist',$_GET);
			break; 
			
		case 'loglist':
			$app->action('manager','managerlog',($_GET['tt'] ? $_GET['tt'] : ""));
			break;
		case 'group':
			$app->action('manager','managergroup',($_GET['tt'] ? $_GET['tt'] : ""),($_GET['id'] ? $_GET['id'] : 0));
			break;
		case 'meslist':
			$app->action('manager','message_list',(isset($_GET['tt']) ? $_GET['tt'] : 0));
			break;
		case 'mes_info':
			$app->action('manager','message_info',($_GET['id'] ? $_GET['id'] : 0));
			break;
		default:
			$app->action('manager','managerlist');
			break;
	}
}else{
$app->jump('manager.php?type=list'); exit;
}
?>