<header class="top_header">银行卡管理</header>
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

   <dd>卡号：<? echo $row['bank_no'];?></dd>
   <header id="delete" onclick="delete_bank(<?php echo $row['id'];?>)" style=" cursor:pointer;background:#0099e5;color:#FFF; text-align:center; height:25px;letter-spacing: 5px; line-height:25px;">解绑</header>

  </dl>
     
     
   

   
<? }}?>
  
  		<a href="<?php echo ADMIN_URL;?>mycart.php?type=bangka_h5_hq"><p class="new">+ 添加银行卡</p></a>   
  		   
  			   
</div>


<script>
 function delete_bank(id){

$.post('<?php echo ADMIN_URL;?>mycart.php',{action:'delete_yl_hq',id:id},function(data){ 
			if(data == "success"){
				
				alert("解绑成功");
			window.location.href = "<?php echo ADMIN_URL;?>mycart.php?type=bangka_list_h5_hq";

 
 
}
		});
	

		}
</script>