<style type="text/css">
.gototype a{ padding:2px; border-bottom:2px solid #ccc; border-right:2px solid #ccc;}
</style>
<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 	
		

		<tr>
			<td class="label" width="15%">商户ID：</td>
			<td>
			<input name="user_id" value="42" size="40" type="text" />
			</td>
		</tr>
		
   
		<tr>
			<td>&nbsp;</td>
			<td>
              <label>
			  <input type="button" value="查询数据库sj1是否有数据" class="chaxun1"/>（没有数据，继续。。。）
			</label>
            
              <label>
			  <input type="button" value="查询新认证信息是否与O单平台sj1一致" class="chaxun2"/>（手机号一致，继续。。。）
			</label>
            
             <label>
			  <input type="button" value="查询结算卡是否与O单平台sj2一致" class="chaxun3"/>（银行卡号一致，继续。。。）
			</label>
            
            
             <label>
			  <input type="button" value="数据写入sj1数据库" class="re_sj1"/>
			</label>
            
             <label>
			  <input type="button" value="数据写入sj2数据库" class="re_sj2"/>
			</label>
            
             <label>
			  <input type="button" value="数据写入sj3数据库" class="re_sj3"/>
			</label>
            
             <label>
			  <input type="button" value="数据写入daifu数据库" class="re_daifu"/>
			</label>
            
             <label>
			  <input type="button" value="数据写入card数据库" class="re_card"/>
			</label>
         
            </td>
          
            
		</tr>
        
        
		</table>
</form>
</div>
<script language="javascript">




$('.chaxun1').click(function(){
	
	var uid = $('input[name="user_id"]').val(); 
	
	$.post('user.php',{action:'chaxun_1',uid:uid},function(data){
		alert(data);
	});
	
});


$('.chaxun2').click(function(){
	
	var uid = $('input[name="user_id"]').val(); 
	
	$.post('user.php',{action:'chaxun_2',uid:uid},function(data){
		alert(data);
	});
	
});


$('.chaxun3').click(function(){
	
	var uid = $('input[name="user_id"]').val(); 
	
	$.post('user.php',{action:'chaxun_3',uid:uid},function(data){
		alert(data);
	});
	
});



$('.re_sj1').click(function(){
	
	var uid = $('input[name="user_id"]').val(); 
	
	$.post('user.php',{action:'re_sj1',uid:uid},function(data){
		alert(data);
	});
	
});






$('.delete_b').click(function(){
	
	var uid = $('input[name="user_id"]').val(); 
	
	$.post('user.php',{action:'delete_bankno_old',uid:uid},function(data){
		if(data == "000000"){
			alert("旧卡删除成功");
		}else{
					
		alert(data);
			
			}

		
	});
	
});



$('.re_ruzhu').click(function(){
	
	var uid = $('input[name="user_id"]').val(); 
	
	$.post('user.php',{action:'re_sj2',uid:uid},function(data){
		if(data == "000000"){
			alert("新卡更新成功");
		}else{
					
		alert(data);
			
			}

		
	});
	
});



$('.re_drawmoney').click(function(){
	
	var order_sn = $('input[name="order_sn"]').val(); 
	
	$.post('user.php',{action:'re_drawmoney',order_sn:order_sn},function(data){
		if(data == "000000"){
			alert("修改成功");
		}else{
					
		alert("修改失败");
			
			}

		
	});
	
});


</script>
