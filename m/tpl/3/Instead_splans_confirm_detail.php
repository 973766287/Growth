<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title>提交详情</title>
<link rel="stylesheet" href="Instead/css/reset.css" type="text/css">
<link rel="stylesheet" href="Instead/css/css.css" type="text/css">
</head>

<body class="sucBody">
<div class="success">
	<div class="sucImg">
    	<img src="Instead/images/suc.png" width="60" height="60">
        <p>提交成功</p>
    </div>
    <h1>代还款期间，请确保卡余额保持不变否则会影响还款成功率！</h1>
    <div class="suc_complete_look">
        <a href="user.php?act=Instead">
    	<div class="suc_complete fl"><span>完成</span></div>
        </a>
        <a href="user.php?act=Instead_splans&card_id=<?php echo $card_id;?>">
        <div class="suc_look fr"><span>查看还款详情</span></div>
        </a>
    </div>
</div>
</body>
</html>
