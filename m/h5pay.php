<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
	<meta charset="utf-8" />
	<title>收银台</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" />
	<meta name="format-detection" content="telephone=no" />
	<script src="js/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="style.css" type="text/css">
    <link rel="stylesheet" href="css/comman.css" type="text/css">
</head>

<body>

 <?php
   	error_log('[' . date('Y-m-d H:i:s') . ']API:' . "\n" . var_export($_POST, true). "\n\n", 3, './app/shopping/h5pay/return_' . date('Y-m-d') . '.log');
   ?>
   
	<div>
    <header class="top_header">收银台</header>
    
    <dl style="text-align:center; line-height:30px;">
   <dt style="font-size: 14px;
    font-weight: 600; ">支付信息</dt>
   <dd style="border: 1px solid #0099e5;"><? if($_POST['status'] == 'SUCCESS'){ echo "交易成功";}else{echo "交易失败";}?></dd>
  
  </dl>
  
  
</div>
</body>
</html>