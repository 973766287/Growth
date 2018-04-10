<?php
$thisurl = ADMIN_URL . 'user.php';
?>
<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}
    .alink{padding:1px; background:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; color:#FF0000}
</style>
<div class="contentbox">
    <table cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th colspan="16" align="left" style="text-align:left">提款申请</th>
        </tr>
        <tr><td colspan="16" align="left">
                <img src="<?php echo $this->img('icon_search.gif'); ?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
                商户号       <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ""; ?>">
                法人姓名     <input name="keyword2" size="15" type="text" value="<?php echo isset($_GET['keyword2']) ? $_GET['keyword2'] : ""; ?>">
                结账状态     <select name="keyword3" id="keyword3">
                                <option value="">结账状态</option>
                                <option value="1">已结账</option>
                                <option value="0">未结账</option>
                             </select>
                代付状态     <select name="keyword4" id="keyword4">
                                <option value="">代付状态</option>
                                <option value="1">代付已发送</option>
                                <option value="0">代付未发送</option>
                             </select>
                代付结果     <select name="keyword5" id="keyword5">
                                <option value="">代付结果</option>
                                <option value="1">交易成功</option>
                                <option value="0">交易失败</option>
                             </select>
                <input value=" 搜索 " class="cate_search" type="button">
              
            </td></tr>
        <tr>
            <th><label>商户号</label></th>
             <th>订单号</th>
             <th>代付单号</th>
             <th>业务类型</th>
              <th>结账金额</th>
                 <th width="80">结账申请时间</th>
            <th>结算账户名</th>
             <th>结算银行</th>
               <th width="120">结算账号</th>
            <th>结算状态</th>
           <th>代付状态</th>
           <th>代付系统返回码</th>
           <th>代付系统返回信息</th>

          
         
          
            <th>会员关系</th>
      
            <th>提现记录</th>
           
            <th width="80">操作</th>
        </tr>
        <?php
        if (!empty($rt)) {
            foreach ($rt as $row) {
                ?>
                <tr>
                    <td style="text-align: center"><?php echo $row['uid']; ?></td>
                    <td><?php echo $row['order_sn']; ?>
                     <td><?php echo $row['INFO_REQ_SN']; ?>
                    </td>
                    <td>提款（提现）
                    </td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo !empty($row['addtime']) ? date('Y-m-d H:i:s', $row['addtime']) : '无知'; ?></td>
                     <td><?php echo $row['account_name']; ?></td>
                    <td><?php echo $row['bankname']; ?></td>
                    <td><?php echo $row['account_no']; ?></td>
                    <td><?php echo $row['state']?"已结账":"未结账"; ?></td>
                    <td><?php  if($row['INFO_RET_CODE'] === "0000" ){echo "代付已发送";}else{echo "代付未发送"; }?></td>
                    <td><?php echo $row['RET_DETAILS_RET_CODE']; ?></td>
                    <td><?php echo $row['RET_DETAILS_ERR_MSG'] ;?></td>
           
                    <td> <a href="user.php?type=userrelate_own&id=<?php echo $row['uid']; ?>"  class="alink">会员关系</a></td>
                  
                    <td><a href="user.php?type=drawmoney&uid=<?php echo $row['uid']; ?>" class="alink">提现记录</a></td>


                  
                    <td >
                      
                        <a href="<?php echo ADMIN_URL . 'user.php?type=info&id=' . $row['uid']; ?>" class="alink">查看代理</a>&nbsp;&nbsp;<br/>
                        
                        <?php if($row['RET_DETAILS_RET_CODE'] != '0000'){?>
                        <a href="<? echo 'user.php?type=pay&id=' . $row['id']; ?>"  onclick="return confirm('您确定要进行完成清算执行操作吗？')"  class="alink">同意提款</a>
                        <? }?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php $this->element('page', array('pagelink' => $pagelink)); ?>
</div>
<script type="text/javascript">
    function ajax_import_order_data(obj) {
        ps = $('input[name="pagestart"]').val();
        pe = $('input[name="pageend"]').val();

        window.open('<?php echo ADMIN_URL; ?>user.php?type=ajax_import_order_data&pagestart=' + ps + '&pageend=' + pe);
    }

    function ajax_confirm_drawmoney(id) {
      var url = '<?php echo urlencode($url); ?>';
        if (confirm("确认吗")) {
            createwindow();
            $.post('<?php echo $thisurl; ?>', {action: 'ajax_confirm_drawmoney', id: id}, function (data) {
                removewindow();
                if (data == "") {
                    location.reload();
                } else {
                    alert(data);
                    location.href = 'user.php?type=priv_user&url=' + url;
                }
                // removewindow();
                // window.location.href = '<?php echo ADMIN_URL . 'user.php?type=drawmoney'; ?>';
            });
        }
        return false;
    }
//全选
    $('.quxuanall').click(function () {
        if (this.checked == true) {
            $("input[name='quanxuan']").each(function () {
                this.checked = true;
            });
            document.getElementById("bathdel").disabled = false;
        } else {
            $("input[name='quanxuan']").each(function () {
                this.checked = false;
            });
            document.getElementById("bathdel").disabled = true;
        }
    });

    //是删除按钮失效或者有效
    $('.gids').click(function () {
        var checked = false;
        $("input[name='quanxuan']").each(function () {
            if (this.checked == true) {
                checked = true;
            }
        });
        document.getElementById("bathdel").disabled = !checked;
    });

    //查询
    $('.cate_search').click(function () {

        keys = $('input[name="keyword"]').val();
        keys2 = $('input[name="keyword2"]').val();
        keys3 = $("#keyword3").val();
        keys4 = $("#keyword4").val();
        keys5 = $("#keyword5").val();

        location.href = '<?php echo Import::basic()->thisurl(); ?>&keyword=' + keys + '&keyword2=' + keys2 + '&keyword3=' + keys3 + '&keyword4=' + keys4 + '&keyword5=' + keys5;
    });

</script>