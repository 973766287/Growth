<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
    <input type="hidden" name="is_show" value="1" />
    <table cellspacing="2" cellpadding="5" width="100%">
	 	 <tr>
			<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>会员等级<span style="float:right"><a href="user.php?type=levellist">返回会员等级</a></span></th>
		</tr>
		<tr>
			<td class="label" width="15%">会员等级名称：</td>
			<td>
			<input name="level_name" value="<?php echo isset($rt['level_name']) ? $rt['level_name'] : '';?>" size="40" type="text" />
			</td>
		</tr>
		<tr>
			<td class="label">扣率/T+0
：</td>
			<td>
			<input name="discount" value="<?php echo isset($rt['discount']) ? $rt['discount'] : '100';?>" size="40" type="text" />
			<br />请填写为0-100的整数,如填入45，表示初始扣率为0.45%
			</td>
		</tr>
        
        
        <tr>
			<td class="label">银联支付(API)扣率/T+0
：</td>
			<td>
			<input name="yinlian" value="<?php echo isset($rt['yinlian']) ? $rt['yinlian'] : '10000';?>" size="40" type="text" />
            <br />请填写为0-100的整数,如填入45，表示初始扣率为0.45%
			</td>
		</tr>
        
        
         <tr>
			<td class="label">API快捷（商旅）扣率/T+0
：</td>
			<td>
			<input name="yinlian_api" value="<?php echo isset($rt['yinlian_api']) ? $rt['yinlian_api'] : '10000';?>" size="40" type="text" />
            <br />请填写为0-100的整数,如填入45，表示初始扣率为0.45%
            <br />
            <input name="sxf_api" value="<?php echo isset($rt['sxf_api']) ? $rt['sxf_api'] : '2';?>" size="40" type="text" />
            <br />每笔手续费
			</td>
         
		</tr>
        
         <tr>
			<td class="label">信用卡还款扣率/T+0
：</td>
			<td>
			<input name="yinlian_instead" value="<?php echo isset($rt['yinlian_instead']) ? $rt['yinlian_instead'] : '10000';?>" size="40" type="text" />
            <br />请填写为0-100的整数,如填入45，表示初始扣率为0.45%
            <br />
            <input name="sxf_instead" value="<?php echo isset($rt['sxf_instead']) ? $rt['sxf_instead'] : '2';?>" size="40" type="text" />
            <br />每笔手续费
			</td>
         
		</tr>
        
        
        
          <tr>
			<td class="label">银联支付扣率(H5)/T+0
：</td>
			<td>
			<input name="yinlian_h5" value="<?php echo isset($rt['yinlian_h5']) ? $rt['yinlian_h5'] : '10000';?>" size="40" type="text" />
            <br />请填写为0-100的整数,如填入45，表示初始扣率为0.45%
			</td>
		</tr>
        
        <tr>
			<td class="label">微信支付扣率/T+0
：</td>
			<td>
			<input name="weixin" value="<?php echo isset($rt['weixin']) ? $rt['weixin'] : '10000';?>" size="40" type="text" />
            <br />请填写为0-100的整数,如填入45，表示初始扣率为0.45%
			</td>
		</tr>
        <tr>
			<td class="label">支付宝支付扣率/T+0
：</td>
			<td>
			<input name="zhifubao" value="<?php echo isset($rt['zhifubao']) ? $rt['zhifubao'] : '10000';?>" size="40" type="text" />
            <br />请填写为0-100的整数,如填入45，表示初始扣率为0.45%
			</td>
		</tr>
        <tr>
			<td class="label">海外支付扣率/T+0
：</td>
			<td>
			<input name="haiwai" value="<?php echo isset($rt['haiwai']) ? $rt['haiwai'] : '10000';?>" size="40" type="text" />
            <br />请填写为0-100的整数,如填入45，表示初始扣率为0.45%
			</td>
		</tr>
        <tr>
			<td class="label">京东支付扣率/T+0
：</td>
			<td>
			<input name="jingdong" value="<?php echo isset($rt['jingdong']) ? $rt['jingdong'] : '10000';?>" size="40" type="text" />
            <br />请填写为0-100的整数,如填入45，表示初始扣率为0.45%
			</td>
		</tr>
        
        

		<tr>
			<td>&nbsp;</td>
			<td><label>
			  <input type="submit" value="<?php echo $type=='edit' ? '确认修改' : '确认添加';?>" class="submit"/>
			</label></td>
		</tr>
	</table>
</form>
</div>