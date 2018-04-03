<header class="top_header">店员管理</header>
<style>
.real_box dl dt{width:80px;font-size:14px;}
.real_box dl dd{font-size:14px; font-family:微软雅黑; }
.real_box dl{ line-height:20px;}
</style>
   <div style="font-weight:600;padding: 20px;width:100%; font-family: 微软雅黑;">顾客付款后，店员将可收到公众号推送的收款通知</div>


<div class="real_box">

  
   <dl>
   <dt style="width:120px; font-family: 微软雅黑;">店员</dt>
  </dl>
  
  <div id="assistant_list">
  <?php
			if(!empty($assistant_list)){
			 foreach($assistant_list as $row){ 
			?>
  <dl>
   <dt style="width:60px;"><img width="40" height="40" src="<?php echo $row['headimgurl'];?>"/></dt>
   <dd><?php echo $row['nickname'];?></dd>
   <div onclick="del(<?php echo $row['id'];?>);" style="font-family: 微软雅黑; cursor:pointer;"><img width="15" height="15"  src="img/delete.jpg"/>&nbsp;删除店员</div>
  </dl>
  <?php }}?>
  
  </div>
  
    <dl>
   <dt style="width:120px; font-family: 微软雅黑;"><a href="<?php echo ADMIN_URL;?>user.php?act=assistant_add"><img width="15" height="15" src="img/add.jpg"/>&nbsp;添加店员</a></dt>
  </dl>
  			   
</div>

<script>
function del(aid){
//	
//	$.post('user.php',{action:'del_assistant',assistant_id:aid},function(data){
//			if(data == 'success'){
//				alert('已删除店员！');
//			location.href="<?php echo ADMIN_URL;?>user.php?act=assistant";
//			}else{
//					alert('删除店员失败，请重新删除！');
//				}
//		});
		
		if(confirm("删除店员后店员将无法接收店铺收款通知,确定删除吗？")){
	$.ajax({
		   type: "POST",
		   url: "<?php echo ADMIN_URL;?>user.php?action=del_assistant",
		   data: "assistant_id="+aid,
		   dataType: "json",
		   success: function(data){ 
		   if(data.error == 0){
			   alert("店员删除成功！");
			    if (data.data.length > 0) {
					
					for(var i = 0; i< data.data.length; i++){
						
						
					htmls = '<dl>'+
   '<dt style="width:60px;"><img width="40" height="40" src="'+data.data[i].headimgurl+'"/></dt>'+
   '<dd>'+data.data[i].nickname+'</dd>'+
   '<div onclick=del('+data.data[i].id+') style="font-family: 微软雅黑; cursor:pointer;">'+
   '<img width="15" height="15"  src="img/delete.jpg"/>&nbsp;删除店员</div>'+
   '</dl>';	
					}
					
					$('#assistant_list').html(htmls);
					
				}else{
					htmls = '';
					$('#assistant_list').html(htmls);
					}
			   
			   }else{
				     alert(data.message); 
				   
				   }
		   }
		
		});
		}
	}
</script>
