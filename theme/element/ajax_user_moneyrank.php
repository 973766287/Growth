
<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:25px;">
    <tr>

    <td width="160" bgcolor="#f9f9f9">头像</td>
    <td   bgcolor="#f9f9f9">用户名</td>
    <td  bgcolor="#f9f9f9">资金</td>
    <td   bgcolor="#f9f9f9">邀请</td>
    <!--<td width="76" bgcolor="#f9f9f9">总积分</td>-->
    <td bgcolor="#f9f9f9">等级</td>
  </tr>
<?php if(!empty($rt['ulist'])){foreach($rt['ulist'] as $k=>$row){
?>
    <tr>

    <td><img src="<?php echo !empty($row['headimgurl']) ? $row['headimgurl'] : $this->img('noavatar_big.jpg');?>" width="80" style="margin-right:5px; padding:1px; border:1px solid #fafafa" /></td>
    <td class="cr2"><?php echo $row['nickname'];?></td>
    <td class="cr2"> ￥<?php echo $row['money_ucount'];?></td>
    <td class="cr2"> <?php echo $row['share_ucount'];?></td>
    <!--<td class="cr2">105</td>-->
    <td><?php if($k<3){
		$s = $k==0 ? 'mmexport1417022423647.png' : ($k==1?'mmexport1417022426972.png':'mmexport1417022429974.png')
		?>
		<img src="<?php echo $this->img('icon/'.$s);?>"   style="  padding:3px;float:right;height:40px;"/>
		<?php } else{?>
		<span style="  padding:3px;float:right; display:block;background:#B70000; text-align:center; font-size:12px; font-weight:bold; color:#FFF; "><i style="font-style:normal"><?php echo ++$k;?></i></span>
		<?php } ?></td>
  </tr>
  <?php } } ?>
  <tr>
  <td  colspan="6" style="text-align:left;" class="pagesmoney">
  <style>
  .pagesmoney a{ margin-right:5px; color:#FFF; background-color:#b70000; text-decoration:none; float:left; display:inherit; padding-left:5px; padding-right:5px; text-align:center}
  .pagesmoney a:hover{ text-decoration:underline}
  </style>
  <?php echo $rt['userpointpage']['showmes'].'&nbsp;'.$rt['userpointpage']['first'].'&nbsp;'.$rt['userpointpage']['prev'].'&nbsp;'.$rt['userpointpage']['next'].'&nbsp;'.$rt['userpointpage']['last'];?>
  </td>
  </tr>
</table>
