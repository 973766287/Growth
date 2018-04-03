<style type="text/css">
.gototype a{ padding:2px; border-bottom:2px solid #ccc; border-right:2px solid #ccc;}
</style>
<div class="contentbox">
<form id="form1" name="form1" method="post" action="">

     <table cellspacing="2" cellpadding="5" width="100%">
		<tr>
			<td class="label" width="15%">代理商费率：</td>
			<td>
			<input name="dl_feilv" value="<?php echo isset($rt['dl_feilv']) ? $rt['dl_feilv'] : '0';?>" size="10" type="text" />
            请填写为0-100的整数,如填入45，表示代理商费率为0.45%
			</td>
		</tr>
		<tr>
			<td class="label">代理商手续费：</td>
			<td>
			 <input name="dl_sxf" value="<?php echo isset($rt['dl_sxf']) ? $rt['dl_sxf'] : '0';?>" size="10" type="text" />
			  </td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td><label>
			  <input type="submit" value="确认保存" class="submit" style="cursor:pointer; padding:2px 4px 2px 4px"/>
			</label></td>
		</tr>
		</table>
</form>
</div>
