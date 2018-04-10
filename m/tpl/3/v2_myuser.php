<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/3/css.css" media="all" />
<?php $this->element('3/top',array('lang'=>$lang)); ?>

<style type="text/css">
table td:hover{ background:#ededed;}
.pages a{background:#ededed; padding:2px 4px 2px 4px; border-bottom:2px solid #ccc; border-right:2px solid #ccc; margin-right:5px;}

</style>
<ul class="lbbody">

<?php if(!empty($rt['lists']))foreach($rt['lists'] as $k=>$row){?>
 <li ><a class="react">
   <span>
   <? if($row['p1_uid'] == $uid){?>
   直接客户
   <? }else{?>
   间接客户
   <? }?>
   
   </span>
  <img class="lb_tx" src="<?php echo !empty($row['headimgurl']) ? $row['headimgurl'] : $this->img('noavatar_big.jpg');?>"> 
  <h3><?php echo $row['nickname'];?>
  <? if($level == 9){?>
  <img class="lb_tb" src="img/mx_14.png">
  <? }else if($level == 10){?>
   <img class="lb_tb" src="img/mx_13.png">
   <? }else if($level == 11){?>
   <img class="lb_tb" src="img/mx_11.png">
    <? }else if($level == 12){?>
   <img class="lb_tb" src="img/mx_10.png">
     <? }else{?>
   <img class="lb_tb" src="img/mx_14.png">
  <? }?>
  </h3>
  <p><?php if($row['mobile']){echo "已认证";}else{echo "未认证";}?></p>
 </a></li>
<?php
	} ?>
    
 
      <?php if(!empty($pages)){?>
	  <div class="pages" style="line-height:60px;"><?php echo $pages['showmes'];?><?php echo $pages['first'].$pages['previ'].$pages['next'].$pages['Last'];?></div>
	  <?php } ?>
  
</ul>
<div class="lb_foot">
  <ul>
    <li><span><? echo $rr['zhijie'];?></span><br>直接客户</li>
    <li><span><? echo $rr['jianjie'];?></span><br>间接客户</li>
    <li class="blue"><span><? echo $zcount;?></span><br>合计</li>
  </ul>
</div>

