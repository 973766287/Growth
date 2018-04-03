<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/3/css.css" media="all" />
<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/3/gift.css" media="all" />
<div class="gift_head"><h3></h3>礼品领取</div>
<div class="gift_adver"><img src="<?php echo ADMIN_URL;?>tpl/3/gfimg/新人.jpg" width="100%"></div>
<div class="gift_box">
<ul>
    <?php $imgs = array(); if(!empty($rt['categoodslist']))foreach($rt['categoodslist'] as $k=>$row){ $imgs[] = $row['goods_img'];?>
  <li><a href="<?php echo ADMIN_URL.($row['is_jifen']=='1'?'exchange':'product').'.php?id='.$row['goods_id'];?>">
   <div class="gift_box_in">
    <span><img src="<?php echo ADMIN_URL;?>tpl/3/gfimg/gf1.png" width="100%" ></span>
    <div class="gift_box_in_img"><img src="<?php echo $row['goods_img'];?>" width="100%" ></div>
    <div class="gift_box_in_wen">
      <h4><?php echo $row['goods_name'];?></h4>
      <h5>立即领取</h5>
      <h6>赠</h6>
    </div>
   </div>
  </a></li>
 
  <?php } ?>
  
  
</ul>
</div>

<?php foreach($userrank as $_k=>$_v){
    ?>
<div class='prize_head'>
     <h3 ></h3><?php echo $_v['level_name'];?></div>
<div class="gift_box">
<ul>
    <?php $imgs = array(); if(!empty($_v['bag']))foreach($_v['bag'] as $k=>$row){ $imgs[] = $row['goods_thumb'];?>
  <li><a href="<?php echo ADMIN_URL.'user.php?act=gift_info&bid='.$row['bid'];?>">
   <div class="gift_box_in">
    <span><img src="<?php echo ADMIN_URL;?>tpl/3/gfimg/gf1.png" width="100%" ></span>
    <div class="gift_box_in_img"><img src="<?php echo $row['goods_thumb'];?>" width="100%" ></div>
    <div class="gift_box_in_wen">
      <h4><?php echo $row['bag_name'];?></h4>
      <h5>立即领取</h5>
      <h6>赠</h6>
    </div>
   </div>
  </a></li>
 
  <?php } ?>
  
  
</ul>
</div>

<?php
    
}?>
<?php $this->element('3/footer',array('lang'=>$lang)); ?>