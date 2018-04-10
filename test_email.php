<?php
require_once("load.php");
$basic=Import::basic(); 
$data['name'] = '熊永金';
$data['email'] = isset($_POST['useremail']) ? trim($_POST['useremail']): "";
$data['subject'] = '电子邮箱标题';
$data['content'] = '电子邮箱发布的内容';
$data['type'] = 1;
$data['notification'] = false;

if(isset($_POST['subemail'])){
	if($basic->ecshop_sendemail($data)){
	echo "发送成功！";
	}else{
	$rt = Import::error()->get_all();
	print_r($rt);
	echo "发送失败！";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>email 发送</title>
</head>

<body>
  <form name="form1" method="post" action="">
  <p>发送EMAIL    </p>
  <p>对方EMAIL:
    <label>
    <input type="text" name="useremail"/>
    </label>
 &nbsp;&nbsp;&nbsp; 
 <label>
 <input type="submit" name="subemail" value="发送"/>
 </label>
  </p>
</form>
</body>
</html>