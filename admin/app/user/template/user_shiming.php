<style type="text/css">
.box{width:100%;height:100%;background:rgba(0,0,0,0.5);position:fixed;z-index:100;display:none;}
.hint{width:350px;margin:0 auto;position:fixed;top:30%;left:40%;margin-left:-250px;margin-top:-166px;z-index:111;display:none;}
.hint-in1{width:350px;height:30px;position:relative;}
.hint-in2 img{width:350px;}

.hint3{width:38px;height:37px;background:url(images/hint3.png) no-repeat;-webkit-background-size:38px 37px;background-size:38px 37px; position:absolute;top:7px;right:-3px;}
.hint3:hover{width:38px;height:37px;background:url(images/hint33.png) no-repeat;-webkit-background-size:38px 37px;background-size:38px 37px; position:absolute;top:7px;right:-3px;}
.gototype a{ padding:2px; border-bottom:2px solid #ccc; border-right:2px solid #ccc;}
</style>
<script type="text/javascript">
function big_img(src){
		$(".hint").css({"display":"block"});
		document.getElementById("hint").innerHTML="<img src=../"+src+">";
		$(".box").css({"display":"block"});

}

	function cl(){
	   $(".hint").css({"display":"none"});
		$(".box").css({"display":"none"});
	}
</script>

<div class="box"></div>

<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 	 <tr>
			<th colspan="2" align="left" class="gototype"><span style="float:left">实名认证</span><a href="user.php?type=list" style="float:right; margin-left:10px;">返回会员列表</a></th>
		</tr>
		<tr>
			<td class="label" width="15%">用户头像：</td>
			<td>
			<?php 
			if(isset($rt['headimgurl'])&&!empty($rt['headimgurl'])){
			echo '<img  alt="头像" src="'.$rt['headimgurl'].'" width="100" style="border:1px dotted #ccc; padding:2px"/>';
			}else{
			echo '<img  alt="头像" src="'.$this->img("tx_img.gif").'" width="100" style="border:1px dotted #ccc; padding:2px"/>';
			}
			?>
		
			</td>
		</tr>

		<tr>
			<td class="label" width="15%">昵称：</td>
			<td>
			<?php echo isset($rt['nickname']) ? $rt['nickname'] : '';?>
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">手机号：</td>
			<td>
			<?php echo isset($rt['mobile']) ? $rt['mobile'] : '';?>
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">真实姓名：</td>
			<td>
			<?php echo isset($rt['uname']) ? $rt['uname'] : '';?>
			</td>
		</tr>
        
        
        <tr>
			<td class="label" width="15%">证件号码：</td>
			<td>
			<?php echo isset($rt['idcard']) ? $rt['idcard'] : '';?>
			</td>
		</tr>
        
        
        <tr>
			<td class="label" width="15%">身份证正面、银行卡正面照片：</td>
			<td>
			<?php 
			if(isset($rt['card_front_img'])&&!empty($rt['card_front_img'])){
				?>
		
			
			<img onclick=big_img('<? echo $rt['card_front_img'];?>')  alt="身份证正面、银行卡正面照片" src="/<? echo $rt['card_front_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="身份证正面、银行卡正面照片" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">身份证反面、信用卡正面照片：</td>
			<td>
            
            <?php 
			if(isset($rt['card_back_img'])&&!empty($rt['card_back_img'])){
				?>
		
			
			<img onclick=big_img('<? echo $rt['card_back_img'];?>')  alt="身份证反面、信用卡正面照片" src="/<? echo $rt['card_back_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="身份证反面、信用卡正面照片" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
            
		
			</td>
		</tr>

        <tr>
			<td class="label" width="15%">银行卡号：</td>
			<td>
			<?php echo isset($rt['banksn']) ? $rt['banksn'] : '';?>
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">开户行：</td>
			<td>
			<?php echo isset($rt['name']) ? $rt['name'] : '';?>
			</td>
		</tr>

         <tr>
			<td class="label" width="15%">手持身份证正面、信用卡正面半身照片：</td>
			<td>
            
              <?php 
			if(isset($rt['card_hand_img'])&&!empty($rt['card_hand_img'])){
				?>
		
			
			<img onclick=big_img('<? echo $rt['card_hand_img'];?>')  alt="手持身份证正面、信用卡正面半身照片" src="/<? echo $rt['card_hand_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="手持身份证正面、信用卡正面半身照片" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
            
		
			</td>
		</tr>
	
    
        <tr>
			<td class="label" width="15%">审核状态：</td>
			<td>
			<label for="male">审核通过</label>
<input type="radio" name="status" id="male" value="1" <? if($rt['status'] == 1){?>checked="checked" <? }?> /> &nbsp;&nbsp;

<label for="female">审核未通过</label>
<input type="radio" name="status" id="female" value="0" <? if($rt['status'] == 0){?>checked="checked" <? }?>/>
			</td>
		</tr>
        
         <tr>
			<td class="label" width="15%">审核未通过原因：</td>
			<td>
			<textarea type="text" name="yijian" rows="20" style="width:400px;"><? echo $rt['yijian'];?></textarea>
      
			</td>
		</tr>
        

		<tr>
			<td>&nbsp;</td>
			<td><label>
			  <input type="submit" value="保存" class="submit"/>
			</label></td>
		</tr>
		</table>
</form>
</div>


<div class="hint">
<div class="hint-in1">
		<div class="hint3" onclick="cl()"></div>
	</div>
<div class="hint-in2" id="hint"></div>
</div>