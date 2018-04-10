<?php
$thisurl = ADMIN_URL . 'gift_bag.php';

?>
<style>.vieworder{ width:55px; height:25px; display:block; cursor:pointer}</style>
<div class="contentbox">
    <table cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th colspan="14" align="left"><span style="float:left">礼包列表</span><a href="<?php echo ADMIN_URL . 'gift_bag.php?type=bag_info&t='.$t; ?>" style="float:right; padding:2px 4px 2px 4px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc">添加礼包</a></th>
        </tr>

        <tr>


            <th>图</th>
            <th style="width:225px;">标题</th>

            <th>录入时间</th>

            <th>操作</th>
        </tr>
        <?php
        if (!empty($lists)) {
            foreach ($lists as $row) {
                ?>
                <tr>


                    <td><a target="_blank" href="<?php echo $row['url']; ?>"><img src="<?php echo!empty($row['goods_thumb']) ? dirname(ADMIN_URL) . '/' . $row['goods_thumb'] : $this->img('no_picture.gif'); ?>" width="60"/></a></td>
                    <td><?php echo $row['bag_name']; ?></td>
                    <td><?php echo !empty($row['add_time']) ? date('Y-m-d', $row['add_time']) : "无知"; ?></td>

                    <td>
                        <a href="gift_bag.php?type=bag_info&id=<?php echo $row['bid']; ?>&url=<?php echo urlencode("gift_bag.php?type=lists&t=".$t);?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif'); ?>" title="编辑"/></a>&nbsp;
                        <img src="<?php echo $this->img('icon_drop.gif'); ?>" title="删除" alt="删除" id="<?php echo $row['bid']; ?>" class="delgoodsid"/>
                    </td>
                </tr>
                <?php }
            ?>

        <?php } ?>
    </table>
   <?php $this->element('page', array('pagelink' => $pagelink)); ?>
</div>
<?php $thisurl = ADMIN_URL . 'gift_bag.php'; ?>
<script>
   $('.delgoodsid').click(function(){
          var url = '<?php echo urlencode($url); ?>';
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定加入回收站吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'delbags',ids:ids,reduction:'1'},function(data){
				removewindow();
				if(data == ""){
					thisobj.hide(300);
				}else{
					//alert(data);	
                                         location.href = 'user.php?type=priv_user&url=' + url;
				}
			});
		}else{
			return false;	
		}
   });
</script>