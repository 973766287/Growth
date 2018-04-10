  <p class="cr5">账户余额：￥<?php echo empty($rt['zmoney']) ? 0 : $rt['zmoney'];?></p>
<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:25px;">
    <tr>

    <td width="160" bgcolor="#f9f9f9"> 购买用户</td>
    <td width="51" bgcolor="#f9f9f9">备注</td>
    <td width="51" bgcolor="#f9f9f9">金额</td>
    <td width="51" bgcolor="#f9f9f9">时间</td>

  </tr>
<?php if(!empty($rt['lists']))foreach($rt['lists'] as $k=>$row){
?>
    <tr>

    <td><img src="<?php echo !empty($row['headimgurl']) ? $row['headimgurl'] : $this->img('noavatar_big.jpg');?>" width="60"  /><br/>
        <?php if(!empty($row['nickname'])){?>
			
			<?php echo  '购买用户:'.$row['nickname'];?>
			
			<?php } ?></td>
    <td class="cr2"><?php $gname = $this->action('user','_return_goods_name',$row['order_sn']); ?>
			<?php echo empty($gname) ? $row['changedesc'] : $gname;?></td>
    <td class="cr2"><?php if($row['money']>0){ echo '<font color="#3333FF">+￥'.$row['money'].'</font>'; }else{ echo '<font color="#fe0000">-￥'.abs($row['money']).'</font>'; }?></td>
    <td class="cr2"><?php echo !empty($row['time']) ? date('Y-m-d H:i:s',$row['time']) : '无知';?></td>
   

  </tr>
  <?php }  ?>
  <tr>
  <td  colspan="6" style="text-align:left;" class="pagesmoney">
  <style>
  .pagesmoney a{ margin-right:5px; color:#FFF; background-color:#b70000; text-decoration:none; float:left; display:inherit; padding-left:5px; padding-right:5px; text-align:center}
  .pagesmoney a:hover{ text-decoration:underline}
  </style>
              <?php
            if (!empty($rt['pages'])) {
                echo $rt['pages']['showmes'];
                echo $rt['pages']['first'];
                echo $rt['pages']['previ'];
                echo $rt['pages']['next'];
                echo $rt['pages']['Last'];
            }
            ?>
  </td>
  </tr>
</table>
