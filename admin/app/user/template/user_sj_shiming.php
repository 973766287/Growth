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
			<th colspan="2" align="left" class="gototype"><span style="float:left">商家认证</span><a href="user.php?type=list" style="float:right; margin-left:10px;">返回会员列表</a></th>
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
			<td class="label" width="15%">店铺名称：</td>
			<td>
			<?php echo isset($rt['s_name']) ? $rt['s_name'] : '';?>
			</td>
		</tr>
        
          <tr>
			<td class="label" width="15%">所在行业：</td>
			<td>
			<?php echo isset($rt['s_hangye']) ? $rt['s_hangye'] : '';?>
			</td>
		</tr>
        
          <tr>
			<td class="label" width="15%">店铺地址：</td>
			<td>
			<?php echo isset($rt['s_address']) ? $rt['s_address'] : '';?>
			</td>
		</tr>
        
    <? if($rt['s_zz'] == 1){?>
        
        <tr>
			<td class="label" width="15%">店铺营业执照原件照片：</td>
			<td>
            
            <?php 
			if(isset($rt['s_y_zhizhao_img'])&&!empty($rt['s_y_zhizhao_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_y_zhizhao_img'];?>')  alt="店铺营业执照原件照片" src="/<? echo $rt['s_y_zhizhao_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="店铺营业执照原件照片" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
         
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">申请人与店铺门头合照（能看到店铺名）：</td>
			<td>
                 <?php 
			if(isset($rt['s_y_mentou_img'])&&!empty($rt['s_y_mentou_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_y_mentou_img'];?>')  alt="申请人与店铺门头合照（能看到店铺名）" src="/<? echo $rt['s_y_mentou_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="申请人与店铺门头合照（能看到店铺名）" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
		
			</td>
		</tr>

         <tr>
			<td class="label" width="15%">店铺内景照：</td>
			<td>
              <?php 
			if(isset($rt['s_y_neijing_img'])&&!empty($rt['s_y_neijing_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_y_neijing_img'];?>')  alt="店铺内景照" src="/<? echo $rt['s_y_neijing_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="店铺内景照" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
		
			</td>
		</tr>
        
          <tr>
			<td class="label" width="15%">营业执照法人身份证原件照片-正面：</td>
			<td>
              <?php 
			if(isset($rt['s_y_card_front_img'])&&!empty($rt['s_y_card_front_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_y_card_front_img'];?>')  alt="营业执照法人身份证原件照片-正面" src="/<? echo $rt['s_y_card_front_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="营业执照法人身份证原件照片-正面" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
			
			</td>
		</tr>
        
          <tr>
			<td class="label" width="15%">营业执照法人身份证原件照片-反面：</td>
			<td>
            
              <?php 
			if(isset($rt['s_y_card_back_img'])&&!empty($rt['s_y_card_back_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_y_card_back_img'];?>')  alt="营业执照法人身份证原件照片-反面" src="/<? echo $rt['s_y_card_back_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="营业执照法人身份证原件照片-反面" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
			
			</td>
		</tr>
        
        <? }else{?>
       
             <tr>
			<td class="label" width="15%">申请人身份证原件照片-正面：</td>
			<td>
            
              <?php 
			if(isset($rt['s_n_card_front_img'])&&!empty($rt['s_n_card_front_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_n_card_front_img'];?>')  alt="申请人身份证原件照片-正面" src="/<? echo $rt['s_n_card_front_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="申请人身份证原件照片-正面" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
		
			</td>
		</tr>
        
        <tr>
			<td class="label" width="15%">申请人身份证原件照片-反面：</td>
			<td>
            
             <?php 
			if(isset($rt['s_n_card_back_img'])&&!empty($rt['s_n_card_back_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_n_card_back_img'];?>')  alt="申请人身份证原件照片-反面" src="/<? echo $rt['s_n_card_back_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="申请人身份证原件照片-反面" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
		
			</td>
		</tr>

       
          <tr>
			<td class="label" width="15%">申请人与店铺门头合照（能看到店铺名）：</td>
			<td>
               <?php 
			if(isset($rt['s_n_mentou_img'])&&!empty($rt['s_n_mentou_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_n_mentou_img'];?>')  alt="申请人与店铺门头合照（能看到店铺名）" src="/<? echo $rt['s_n_mentou_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="申请人与店铺门头合照（能看到店铺名）" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
		
			</td>
		</tr>
        
          <tr>
			<td class="label" width="15%">店铺内景照：</td>
			<td>
               <?php 
			if(isset($rt['s_n_neijing_img'])&&!empty($rt['s_n_neijing_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_n_neijing_img'];?>')  alt="店铺内景照" src="/<? echo $rt['s_n_neijing_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="店铺内景照" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
		
			</td>
		</tr>
        
        
          <tr>
			<td class="label" width="15%">申请人手持身份证正面在店铺收银台照片：</td>
			<td>
             <?php 
			if(isset($rt['s_n_card_hand_img'])&&!empty($rt['s_n_card_hand_img'])){
				?>
			<img onclick=big_img('<? echo $rt['s_n_card_hand_img'];?>')  alt="申请人手持身份证正面在店铺收银台照片" src="/<? echo $rt['s_n_card_hand_img'];?>" width="200" style="border:1px dotted #ccc; padding:2px"/>
			
			<? }else{?>
			<img  alt="申请人手持身份证正面在店铺收银台照片" src="<? echo $this->img("tx_img.gif");?>" width="100" style="border:1px dotted #ccc; padding:2px"/>
			<? }?>
            
		
			</td>
		</tr>
        
        
        
        
        <? }?>
	
    
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
			<textarea type="text" name="beizhu" rows="20" style="width:400px;"><? echo $rt['beizhu'];?></textarea>
      
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