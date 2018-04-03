<style type="text/css">
.gototype a{ padding:2px; border-bottom:2px solid #ccc; border-right:2px solid #ccc;}
</style>
<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 	 <tr>
			<th colspan="2" align="left" class="gototype"><span style="float:left"><?php echo $type=='edit' ? '修改' : '添加';?>会员</span><?php if(isset($_GET['goto'])&&$_GET['goto']!='suppliers'){?><?php } ?><a href="user.php?type=<?php echo isset($_GET['goto'])?$_GET['goto']:'list';?>&rank=<?php echo isset($_GET['rank']) ? $_GET['rank'] : ""; ?>" style="float:right; margin-left:10px;">返回会员列表</a></th>
		</tr>
		<tr>
			<td class="label" width="15%">用户头像：</td>
			<td>
			<?php 
			if(isset($rt['userinfo']['headimgurl'])&&!empty($rt['userinfo']['headimgurl'])){
			echo '<img  alt="头像" src="'.$rt['userinfo']['headimgurl'].'" width="100" style="border:1px dotted #ccc; padding:2px"/>';
			}else{
			echo '<img  alt="头像" src="'.$this->img("tx_img.gif").'" width="100" style="border:1px dotted #ccc; padding:2px"/>';
			}
			?>
		
			</td>
		</tr>

		<tr>
			<td class="label" width="15%">昵称：</td>
			<td>
			<input name="nickname" value="<?php echo isset($rt['userinfo']['nickname']) ? $rt['userinfo']['nickname'] : '';?>" size="40" type="text" />
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">会员级别：</td>
			<td>
				<select name="user_rank">
				<?php 
				if(!empty($rt['userinfo']['user_jibie'])){
				foreach($rt['userinfo']['user_jibie'] as $row){
				?>
				  <option value="<?php echo $row['lid'];?>" <?php echo isset($rt['userinfo']['user_rank'])&&$row['lid']==$rt['userinfo']['user_rank'] ? 'selected="selected"' : "";?>><?php echo $row['level_name'];?></option>

				  <?php }} ?>
			    </select>
		</td>
		</tr>
        
        
       
        

		<tr>
			<td class="label" width="15%">分润：</td>
			<td>
			￥<?php echo isset($rt['userinfo']['fenrun']) ? $rt['userinfo']['fenrun'] : '0.00';?>
			<a onclick="$('.user_money').toggle();" href="javascript:void(0)">[查看明细]</a>
			<div class="user_money" style="display:none;width:550px; border:1px solid #B4C9C6; position:absolute; left:-100px; top:55px; background-color:#ededed; padding:5px">
			<?php $this->element('ajax_user_money',array('rt'=>$rt));?>
			</div>
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">佣金：</td>
			<td>
			￥<?php echo isset($rt['userinfo']['yongjin']) ? $rt['userinfo']['yongjin'] : '0.00';?>
			
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">升级奖励：</td>
			<td>
			￥<?php echo isset($rt['userinfo']['tuiguang']) ? $rt['userinfo']['tuiguang'] : '0.00';?>
			
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">银联支付(商旅类)：</td>
			<td>
			￥<?php echo isset($rt['userinfo']['yinlian']) ? $rt['userinfo']['yinlian'] : '0.00';?>
			
			</td>
		</tr>
        
         <tr>
			<td class="label" width="15%">银联支付(缴费类)：</td>
			<td>
			￥<?php echo isset($rt['userinfo']['yinlian_h5']) ? $rt['userinfo']['yinlian_h5'] : '0.00';?>
			
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">微信支付：</td>
			<td>
			￥<?php echo isset($rt['userinfo']['weixin']) ? $rt['userinfo']['weixin'] : '0.00';?>
			
			</td>
		</tr>
        
         <tr>
			<td class="label" width="15%">支付宝支付：</td>
			<td>
			￥<?php echo isset($rt['userinfo']['zhifubao']) ? $rt['userinfo']['zhifubao'] : '0.00';?>
			
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">海外支付：</td>
			<td>
			￥<?php echo isset($rt['userinfo']['haiwai']) ? $rt['userinfo']['haiwai'] : '0.00';?>
			
			</td>
		</tr>
        
          <tr>
			<td class="label" width="15%">京东支付：</td>
			<td>
			￥<?php echo isset($rt['userinfo']['jingdong']) ? $rt['userinfo']['jingdong'] : '0.00';?>
			
			</td>
		</tr>
   
		<tr>
			<td>&nbsp;</td>
			<td><label>
			  <input type="submit" value="<?php echo $type=='edit' ? '确认修改' : '确认添加';?>" class="submit"/>
			</label>

            </td>
          
            
		</tr>
		</table>
</form>
</div>
<script language="javascript">
$('.submit').click(function(){
	user = $('input[name="user_name"]').val();
	if(typeof(user)=='undefined' || user==""){
		//alert("昵称不能为空！");
		//return false;
	}
	
/*	pass = $('input[name="password"]').val();
	if(typeof(pass)=='undefined' || pass==""){
		<?php if($type=='add'){?>
		alert("密码不能空！");
		return false;
		<?php } ?>
	}
	
	if(pass.length<6){
		<?php if($type=='add'){?>
		alert("密码过于短，至少6位！");
		return false;
		<?php } ?>
	}
	
	cf_pass = $('#confirm_pass').val();
	if(pass!=cf_pass){
		alert("两次密码不相同！");
		return false;
	}*/
	
	return true;
});


$('.chaxun').click(function(){
	
	
	$.post('user.php',{action:'chaxun_money',uid:<?php echo $rt['userinfo']['user_id'] ?>},function(data){
		if(data == "false"){
			alert("商户未入驻");
		}else{
					
		alert("可用余额"+data);
			
			}

		
	});
	
});



function ger_ress_copy(type,obj,seobj){
	parent_id = $(obj).val();
	if(parent_id=="" || typeof(parent_id)=='undefined'){ return false; }
	$.post('user.php',{action:'get_ress',type:type,parent_id:parent_id},function(data){
		if(data!=""){
			$(obj).parent().find('#'+seobj).html(data);
			
			if(type==5){ //村
				$(obj).parent().find('#'+seobj).show();
				$(obj).parent().find('#select_peisong').hide();
			}else if(type==4){ //城镇
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				$(obj).parent().find('#select_peisong').hide();
				
				$(obj).parent().find('#select_town').show();
				//$(obj).parent().find('#select_town').html("");
			}else if(type==3){ //区
				$(obj).parent().find('#select_peisong').hide();
				$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
				
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				
				$(obj).parent().find('#select_town').hide();
				$(obj).parent().find('#select_town').html('<option value="0" >选择城镇</option>');
				
				$(obj).parent().find('#select_district').show();
				//$(obj).parent().find('#select_district').html("");
				
			}else if(type==2){ //市
				$(obj).parent().find('#select_peisong').hide();
				$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
				
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				
				$(obj).parent().find('#select_town').hide();
				$(obj).parent().find('#select_town').html('<option value="0" >选择城镇</option>');
				
				$(obj).parent().find('#select_district').hide();
				$(obj).parent().find('#select_district').html('<option value="0" >选择区</option>');
				
				//$(obj).parent().find('#select_city').hide();
				//$(obj).parent().find('#select_city').html("");
			}

		}else{
			alert(data);
		}
	});
}




function change_user_points_money(uid,thisobj,type){
	val = $(thisobj).val();
	if(val>0 || val<0){
		if(confirm("你确定执行该操作吗？")){
			createwindow();
			$.post('user.php',{action:'change_user_points_money',uid:uid,type:type,val:val},function(data){
				if(typeof(data)!='undefined' && data!=""){
					removewindow();
					if(parseInt(data)>0){
						if(type=='money'){
							$(thisobj).parent().find('.thismoney').html(data);
						}else if(type =='points'){
							$(thisobj).parent().find('.thispoints').html(data);
						}
					}
					alert("操作成功！");
				}else{
					alert("操作失败！");
				}
			});
		}
	}
	return false;
}

  function get_userpoint_page_list(page,uid){
  		createwindow();
		$.post('user.php',{action:'pointinfo',page:page,uid:uid},function(data){
			removewindow();
			if(data !="" && typeof(data)!='undefined'){
				$('.user_point').html(data);
			}
		});
  }
  
  function get_usermoney_page_list(page,uid){
  		createwindow();
		$.post('user.php',{action:'mymoney',page:page,uid:uid},function(data){
			removewindow();
			if(data !="" && typeof(data)!='undefined'){
				$('.user_money').html(data);
			}
		});
}
</script>
