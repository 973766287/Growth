<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:25px;">
    <tr>
        <td   <?php if($_GET['status']=='tongguo'){?>bgcolor="#75b600"<?php }else{?>bgcolor="#f9f9f9"<?php } ?>  ><a href="<?php echo SITE_URL . 'user.php?act=mymoneydata&status=tongguo'; ?>"><i></i>审核通过的 </a></td>

        <td  <?php if($_GET['status']=='weifu'){?>bgcolor="#75b600"<?php }else{?>bgcolor="#f9f9f9"<?php } ?> ><a href="<?php echo SITE_URL . 'user.php?act=mymoneydata&status=weifu'; ?>"><i></i>未付款订单 </a></td>



        <td   <?php if($_GET['status']=='yifu'){?>bgcolor="#75b600"<?php }else{?>bgcolor="#f9f9f9"<?php } ?> >	<a href="<?php echo SITE_URL . 'user.php?act=mymoneydata&status=yifu'; ?>"><i></i>已付款订单 </a></td>



        <td  <?php if($_GET['status']=='shouhuo'){?>bgcolor="#75b600"<?php }else{?>bgcolor="#f9f9f9"<?php } ?> ><a href="<?php echo SITE_URL . 'user.php?act=mymoneydata&status=shouhuo'; ?>"><i></i>已收货订单 </a></td>



        <td  <?php if($_GET['status']=='quxiao'){?>bgcolor="#75b600"<?php }else{?>bgcolor="#f9f9f9"<?php } ?> ><a href="<?php echo SITE_URL . 'user.php?act=mymoneydata&status=quxiao'; ?>"><i></i>已取消作废 </a></td>





    </tr>

</table>
<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:25px;">
    <tr>

        <td width="160" bgcolor="#f9f9f9"> 购买用户</td>
        <td width="51" bgcolor="#f9f9f9">备注</td>
        <td width="51" bgcolor="#f9f9f9">金额</td>
        <td width="51" bgcolor="#f9f9f9">时间</td>

    </tr>
    <?php
    if (!empty($rt['lists'])) {
        foreach ($rt['lists'] as $k => $row) {
            ++$k;
            ?>
            <tr>

                <td><?php if (!empty($row['nickname'])) { ?>

                        <?php echo $row['nickname']; ?>

                    <?php } ?></td>
                <td class="cr2"><?php echo empty($gname) ? $row['changedesc'] : $gname; ?></td>
                <td class="cr2"><?php echo!empty($row['time']) ? date('Y-m-d H:i:s', $row['time']) : '无知'; ?></td>
                <td class="cr2"><?php if ($row['money'] > 0) {
                echo '<font color="#3333FF">+￥' . $row['money'] . '</font>';
            } else {
                echo '<font color="#fe0000">-￥' . abs($row['money']) . '</font>';
            } ?></td>

            </tr>
    <?php }
} ?>
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
