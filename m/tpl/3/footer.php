<style>
    /*###############footer##################*/

#footer{
	width:100%;
        
	height:45px;
	background:#eaeceb;
	border-top:#e0e0e0 solid 1px;
	position:fixed;
	bottom:0;
	z-index:100;
}

#footer ul{
	width:100%;
	height:45px;
}
#footer ul a{
	color:#666666;
}

#footer ul li{
	width:24.75%;
	padding-top:2px;
	height:43px;
	border-right:#e1e3e2 solid 1px;
	text-align:center;
	float:left;
}

#footer .pic-wrap{
	width:24%;
	height:auto;
	padding:0 38%;
}
#footer .pic-wrap img{
	width:100%;
}

#footer ul li p{
	line-height:20px;
	text-align:center; font-size:12px;}

#footer ul li strong{
	width:14px;
	height:14px;
	font-size:0.9em;
	line-height:14px;
	border-radius:7px;
	background:#fd4708;
	color:#fff;
	position:absolute;
	top:1px;
	right:24px;
	z-index:200;
}


</style>
<?php
$nums = 0;
$thiscart = $this->Session->read('cart');
if(!empty($thiscart))foreach($thiscart as $row){
	$nums +=$row['number'];
}
?>
<div style="height:3rem;"></div>
<div id="footer">
  <ul>
   <a href="<?php echo ADMIN_URL;?>"><li>
     <div class="pic-wrap"><img src="<?php echo ADMIN_URL; ?>tpl/3/images/bottom/1.png" /></div>
     <p>商城首页</p>
   </li></a>
   <a href="<?php echo ADMIN_URL.'user.php?act=orderlist';?>"><li>
     <div class="pic-wrap"><img src="<?php echo ADMIN_URL; ?>tpl/3/images/bottom/2.png" /></div>
     <p>我的订单</p>
   </li></a>
   <a href="<?php echo ADMIN_URL.'mycart.php';?>"><li style="position:relative;">
     <div class="pic-wrap"><img src="<?php echo ADMIN_URL; ?>tpl/3/images/bottom/3.png" /></div>
     <p>购物车</p>
     <strong><?php echo $nums;?></strong>
   </li></a>
   <a href="<?php echo ADMIN_URL.'user.php';?>"><li style="border-right:none;">
     <div class="pic-wrap"><img src="<?php echo ADMIN_URL; ?>tpl/3/images/bottom/4.png" /></div>
     <p>我的分销</p>
   </li></a>
  </ul>
</div>
