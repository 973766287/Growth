<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '编辑' : '添加';?>银行</th>
	</tr>
    <tr>
	   <td width="25%">银行名称</td>
	   <td>
	       <input type="text" name="name" value="<?php echo isset($rt['name']) ? $rt['name'] : "";?>" size="50"/>
	   </td>
	</tr>
	
     <tr>
		<td class="label">图片:</td>
		<td>
		  <?php if(isset($rt['pic'])){ ?><img src="<?php echo !empty($rt['pic']) ? SITE_URL.$rt['pic'] : $this->img('no_picture.gif');?>" width="100" style="padding:1px; border:1px solid #ccc"/><?php } ?>
		  <input name="pic" id="bankinfo" type="hidden" value="<?php echo isset($rt['pic']) ? $rt['pic'] : '';?>"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['pic'])&&!empty($rt['pic'])? 'show' : '';?>&ty=bankinfo&files=<?php echo isset($rt['pic']) ? $rt['pic'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
      
       <tr>
	   <td width="25%">银行代码</td>
	   <td>
	       <input type="text" name="code" value="<?php echo isset($rt['code']) ? $rt['code'] : "";?>" size="50"/>
	   </td>
	</tr>
    

	<tr>
	<td>&nbsp;</td>
	<td>
	  <input type="submit" value="保存" />
	</td>
	</tr>
	 </table>
 </form>
</div>
