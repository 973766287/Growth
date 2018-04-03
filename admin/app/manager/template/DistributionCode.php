<div class="contentbox">
	<form id="form1" name="form1" method="post" action="">
     <table cellspacing="0" cellpadding="5" width="100%" align="left">
	 	<tr>
			<th colspan="2" align="left">发放邀请码</th>
		</tr>
		<tr>
		<td class="label" width="15%">代理名称</td>
		<td width="85%">
		  <input name="adminname" class="adminname" readonly="readonly" maxlength="20" size="34" type="text" value="<?php echo isset($rts['adminname']) ? $rts['adminname'] : '';?>" /></td>
	  </tr>
	 
     	<tr>
		<td class="label" width="15%">邀请码数量</td>
		<td width="85%">
		  <input name="number" class="number" maxlength="20" size="34" type="text" value="" /><span class="require-field">*</span></td>
	  </tr>
      
	  <tr>
	  	<th style="border-right:1px solid #B4C9C6">&nbsp;</th>
	  	<td>
	  	    <input type="button" name="button" value="确认"  class="distribution"/>&nbsp;&nbsp;
  	     
        </td>
	  </tr>
     </table>
	</form>
	<div class="clear">&nbsp;</div>
</div>
<?php $this->element('showdiv');?>
<?php  $thisurl = ADMIN_URL.'manager.php'; ?>
<script type="text/javascript">
//jQuery(document).ready(function($){

	$('.distribution').click(function(){
		number  = $('.number').val();
		
		
		if(!number){
			alert("发放数量不能为空");
		   return false;
		}

		createwindow();
	
		$.post('<?php echo $thisurl;?>',{action:'distribution',number:number,dl_id:<?php echo $dl_id;?>},function(data){ 
			removewindow();
			if(data == ""){
		        alert("发放成功");
			}else{
				alert(data);
			}
		});
	});
	
//});
</script>