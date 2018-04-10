<?php
$thisurl = ADMIN_URL . 'yuyue.php';
?>
<style>.vieworder{ width:55px; height:25px; display:block; cursor:pointer}
.alink{padding:1px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; color:#FF0000}</style>
<div class="contentbox">
    <table cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th colspan="14" align="left"><span style="float:left">报名订单</span></th>
        </tr>
        <tr>


            <th><a href="yuyue.php?type=bmorderlist&t=<?php echo $t; ?>&s=1">已支付</a></th>
            <th><a href="yuyue.php?type=bmorderlist&t=<?php echo $t; ?>&s=0">未支付</a></th>

            <th colspan="12" align="left">
                选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
                至
                <input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;导出已付款:
                <input type="text" name="pagestart" size="5" value="1" />页至
                <input type="text" name="pageend" size="5" value="50" />页&nbsp;&nbsp;
                <label>
                    <input type="submit" value="确认导出" style="cursor:pointer; padding:3px;" onclick="ajax_import_order_data(this)" />
                </label>	

            </th></tr>

        <tr>
             <th>序号</th>
            <th>订单编号</th>
            <th>类型</th>
            <th>缴费金额</th>
            <th>真实姓名</th>
            <th>微信昵称</th>
            <th>会员关系</th>
            <th>手机</th>
            <th>支付状态</th>
            <th>时间</th>
            <th>总佣金</th>
            <th>总余额</th>
            <th>提现记录</th>
            <th>操作</th>
        </tr>
        <?php
        if (!empty($rt)) {
            $i=0;
            foreach ($rt as $row) {
                $i++;
                ?>
                <tr>
                      <td> <?php echo $i; ?> </td>
                    <td><font color="blue"><?php echo $row['order_sn']; ?></font></td>
                    <td><?php echo $row['title']; ?></td>
                    <td>￥<?php echo $row['order_amount']; ?></td>
                    <td><?php echo $row['uname']; ?></td>
                    <td><?php echo $row['nickname']; ?></td>
                    <td> <a href="user.php?type=userrelate_own&id=<?php echo $row['user_id']; ?>"  class="alink">会员关系</a></td>
                    <td><?php echo $row['upne']; ?></td><?php $p = date('Y-m-d', $row['pay_time']); ?>
                    <td><?php echo $row['pay_status'] == '0' ? '未支付' : '<font color=blue>已支付(' . $p . ')</font>'; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', $row['add_time']); ?></td>
                  
                    <td><a href="user.php?type=usermoney&uid=<?php echo $row['user_id']; ?>" class="alink"><?php echo $row['money_ucount']; ?></a></td>
                    <td><a href="user.php?type=usermoney&uid=<?php echo $row['user_id']; ?>" class="alink"><?php echo $row['mymoney']; ?></a></td>
                    <td><a href="user.php?type=drawmoney&uid=<?php echo $row['user_id']; ?>" class="alink">提现记录</a></td>
                    <td>
                        <a href="yuyue.php?type=bmorderinfo&id=<?php echo $row['id']; ?>&url=<?php echo urlencode("yuyue.php?type=bmorderlist&t=".$t);?>" target="_blank"  class="alink">详情</a>
                        <a href="yuyue.php?type=bmorderlist&id=<?php echo $row['id']; ?>" onclick="return confirm('确定删除吗')" class="alink">删除</a>
                    </td>
                </tr>
                <?php }
            ?>
        <?php } ?>
    </table>
    <?php $this->element('page', array('pagelink' => $pagelink)); ?>
</div>
<script type="text/javascript">
    function ajax_import_order_data(obj) {
        ps = $('input[name="pagestart"]').val();
        pe = $('input[name="pageend"]').val();

        window.open('<?php echo ADMIN_URL; ?>yuyue.php?type=ajax_import_order_data&pagestart=' + ps + '&pageend=' + pe + "&t=" +<?php echo $t; ?>);
    }
</script>