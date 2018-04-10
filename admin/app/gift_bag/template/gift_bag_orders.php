<?php
$thisurl = ADMIN_URL . 'gift_bag.php';
?>
<style>.vieworder{ width:55px; height:25px; display:block; cursor:pointer}</style>
<div class="contentbox">
    <table cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th colspan="14" align="left"><span style="float:left">礼包领取记录</span></th>
        </tr>

        <tr>

  <th>编号</th>
            <th>礼包名称</th>
            <th style="width:225px;">领取会员</th>

            <th>地址</th>

            <th>时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php
        if (!empty($lists)) {
            foreach ($lists as $row) {
                ?>
                <tr>
  <td><?php echo $row['oid']; ?></td>

                    <td><a href='<?php echo ADMIN_URL."gift_bag.php?type=bag_info&id=". $row['bid']; ?>'> <?php echo $row['bag_name']; ?></td>
                    <td><?php echo $row['nickname']; ?></td>
                    <td>
                        <?php echo $row['consignee'] . $row['province'] . $row['city'] . $row['district'] . $row['address']; ?>
                    </td>
                    <td><?php echo!empty($row['add_time']) ? date('Y-m-d', $row['create_time']) : "无知"; ?></td>
                    <td><?php echo ($row['status'] == 1) ? '未发放' : "已发放"; ?></td>
                    <td>
                        <?php if ($row['status'] == 1) { ?>
                            <a href="gift_bag.php?type=mksure&id=<?php echo $row['oid']; ?>" title="发放礼包">发放礼包</a>
                        <?php } elseif ($row['status'] == 2) { ?>&nbsp;&nbsp;&nbsp;
                            <a href="gift_bag.php?type=unmksure&id=<?php echo $row['oid']; ?>" title="取消发放礼包">取消发放礼包</a>
                        <?php } ?>
                            <a href="gift_bag.php?type=bag_print&oid=<?php echo $row['oid']; ?>" title="取消发放礼包" target="_blank">领取详情</a>
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
    $('.delgoodsid').click(function () {
         var url = '<?php echo urlencode($url); ?>';
        ids = $(this).attr('id');
        thisobj = $(this).parent().parent();
        if (confirm("确定加入回收站吗？")) {
            createwindow();
            $.post('<?php echo $thisurl; ?>', {action: 'delbags', ids: ids, reduction: '1'}, function (data) {
                removewindow();
                if (data == "") {
                    thisobj.hide(300);
                } else {
                   // alert(data);
                    location.href = 'user.php?type=priv_user&url=' + url;
                }
            });
        } else {
            return false;
        }
    });
</script>