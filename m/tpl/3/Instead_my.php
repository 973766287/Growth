<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title>智能还款</title>
<link rel="stylesheet" href="Instead/css/reset.css" type="text/css">
<link rel="stylesheet" href="Instead/css/css.css" type="text/css">
<script type="text/javascript" src="Instead/js/jquery-1.8.3.min.js"></script>

</head>

<body class="myBody">
<div class="cardAreaBox">
<?php if (!empty($card_insteads)){?>
   <?php foreach($card_insteads as $card){?>
   
	<div class="cardArea">
    <a href="user.php?act=Instead_setting&id=<?php echo $card['id'];?>">
    	<div class="cardArea_top bBor">
        	<div class="cardArea_number">
            	<h1><span>信用卡</span><?php echo $card['bankname'];?></h1>
                <h2><?php echo   preg_replace('/(\d{4})(?=\d)/', '$1 ', substr_replace($card['bank_no']," **** **** ",4,8));?></h2>
            </div>
            <div class="cardArea_pos"><img src="../<?php echo $card['bankpic'];?>"></div>
        </div>
        </a>
        <div class="cardArea_bottom">
        	<h3><?php echo $card['instead_desc'];?></h3>
             <span class="delete_card" onClick="jb(<?php echo $card['id'];?>)">解绑</span>
             <a href="user.php?act=Instead_setting&id=<?php echo $card['id'];?>"><span>设置</span></a>
        </div>
    </div>
	
    <?php }?>
    <?php }?>
    
</div>
<div class="addCard" style="margin-bottom: 44px;"><a href="user.php?act=Instead_bangka"><span>+&nbsp;添加银行卡</span></a></div>
<!-- <div class="strategy"><a href="https://mp.weixin.qq.com/s/lBl_wxN0nBPRRxOsgO68xA"><span>代还款攻略></span></a></div> -->
<div class="fixed tBor">

  <ul>

    <a href="<?php echo ADMIN_URL;?>user.php?act=baoming"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-home-g.png" height="25"><p>会员中心</p></li></a>

    <a href="<?php echo ADMIN_URL;?>daili.php?act=myusertype"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-promote-g.png" height="25"><p >推广</p></li></a>

    <a href="<?php echo ADMIN_URL;?>user.php?act=Instead"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-return-g.png" height="25"><p>还款</p></li></a>

    <a href="<?php echo ADMIN_URL;?>mycart.php?type=shoukuan"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-return-g.png" height="25"><p>收款</p></li></a>

    <a href="<?php echo ADMIN_URL;?>user.php"><li><img src="<?php echo ADMIN_URL;?>../photos/hk2bkucn/footer/B-member-b.png" height="25"><p class="on">我的</p></li></a>

    </ul>

</div>
<script>
function jb(id){
	
	if(confirm("确定解绑信用卡吗？")){
	$.post('<?php echo ADMIN_URL;?>user.php',{action:'delete_card',card_id:id},function(data){ 
			/*removewindow();*/
			if(data == "success"){
				document.location.href="<?php echo ADMIN_URL;?>user.php?act=Instead&t=<?php echo rand(1,999);?>";
			}else{
				alert(data);
				}
		});
	}else{
		return false;
		}
	
	}
</script>
</body>
</html>
