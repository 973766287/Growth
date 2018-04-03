<table width="100%" border="0" cellpadding="1"  cellspacing="1" style="line-height:40px;">
  <tr class="thth">
    <td  class=" bg_grey">礼包名称</td>
    <td class=" bg_grey">领取时间</td>
  
  </tr>
    <?php if(!empty($rt)){foreach($rt as $row){
	$ts = '';
	?>
  <tr bgcolor="#FFFFFF"  >
    

   
      <td>  <a href="<?php echo SITE_URL;?>user.php?act=gift_info&bid=<?php echo $row['bid'];?>">  <?php echo $row['bag_name'];?></a></td>
    <td ><?php  echo date('Y-m-d',$row['create_time']);?></td>
    
  </tr>
<?php } } ?>
</table>
				  
			