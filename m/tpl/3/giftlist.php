
<link  type="text/css"  rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/3/gift.css" />

<div class="gift_head" ><h3></h3>礼包领取</div>

<?php if(!empty($lists)){
        foreach($lists as $_k=>$row){
    ?>
<a href="<?php echo ADMIN_URL.'user.php?act=gift_info&bid='.$row['bid'];?>">
<div class="giftbag_div" style="margin-top:1rem">
<img src="<?php echo SITE_URL .$row['goods_thumb'];?>" width="100%"> 
<div class="morph-button morph-button-inflow ">
	<button type="button"><span>立即领取</span></button>
	
</div>
</div>
</a>

<?php } }?>



<?php $this->element('3/footer',array('lang'=>$lang)); ?>