<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '编辑' : '添加';?>支付方式</th>
	</tr>
    <tr>
	   <td width="25%">支付名称</td>
	   <td>
	        <select name="pay_name">
       <option <? if($rt['pay_name'] == "银联支付"){?>selected="selected"<? }?>>银联支付</option>
        <option <? if($rt['pay_name'] == "微信支付"){?>selected="selected"<? }?>>微信支付</option>
         <option <? if($rt['pay_name'] == "支付宝支付"){?>selected="selected"<? }?>>支付宝支付</option>
          <option <? if($rt['pay_name'] == "海外支付"){?>selected="selected"<? }?>>海外支付</option>
           <option <? if($rt['pay_name'] == "京东支付"){?>selected="selected"<? }?>>京东支付</option>
              <option <? if($rt['pay_name'] == "商户扫码微信支付"){?>selected="selected"<? }?>>商户扫码微信支付</option>
            
       </select>
	   </td>
	</tr>
	   <tr>
	   <td>支付描述</td>
	   <td>
	       <textarea name="pay_desc" cols="50" rows="5"><?php echo isset($rt['pay_desc']) ? $rt['pay_desc'] : "";?></textarea>
	   </td>
	</tr>
	<?php //if(isset($_GET['id'])&&$_GET['id']=='1'){?>
	<tr>
	   <td>商户号</td>
	   <td>
	        <input type="text" name="pay_no" value="<?php echo isset($rt['pay_no']) ? $rt['pay_no'] : "";?>" size="50"/>
	   </td>
	</tr>
		<tr>
	   <td>商户密钥Key</td>
	   <td>
	        <input type="text" name="pay_code" value="<?php echo isset($rt['pay_code']) ? $rt['pay_code'] : "";?>" size="50"/>
	   </td>
	</tr>
		<tr>
	   <td>终端号</td>
	   <td>
	        <input type="text" name="pay_idt" value="<?php echo isset($rt['pay_idt']) ? $rt['pay_idt'] : "";?>" size="50"/>
	   </td>
	</tr>
    
    <tr>
	   <td>回调地址</td>
	   <td>
	        <input type="text" name="pay_address" value="<?php echo isset($rt['pay_address']) ? $rt['pay_address'] : "";?>" size="50"/>
	   </td>
	</tr>
	
	<?php //} ?>
	<tr>
	<td>&nbsp;</td>
	<td>
	  <input type="submit" value="保存" />
	</td>
	</tr>
	 </table>
 </form>
</div>
