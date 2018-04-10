<header class="top_header">银行卡管理星洁</header>
	<style>
	.new {
  width: 86%;
  margin: 0.5rem auto;
  color: #999999;
  border: 0.0625rem dashed #ddd;
  text-align: center;
  padding: 0.3125rem;
  -webkit-border-radius: 0.15625rem;
  -moz-border-radius: 0.15625rem;
  -khtml-border-radius: 0.15625rem;
  border-radius: 0.15625rem;
}
	</style>
<div class="real_box">
  
  <? if($rt){ foreach($rt as $row){ ?>
  <dl>
   <dt style="width:40%;"><? echo $row['mobile'];?></dt>
   <dd style="width:55%;">卡号：<? echo $row['bank_no'];?></dd>
   <dd style="text-align:center;padding:2px;background:#CCC;border-radius:10px; cursor:pointer;"><a href="<?php echo ADMIN_URL;?>mycart.php?type=delete_ylbank&id=<? echo $row['id']?>">解绑</a></dd>
  </dl>
   
<? }}?>
  
  		<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_api"><p class="new">+ 添加银行卡</p></a>   
  		   
  			   
</div>

