<?php
$thisurl = ADMIN_URL . 'user.php';
if (isset($_GET['asc'])) {
    $uname = $thisurl . '?type=list&desc=user_name';
    $email = $thisurl . '?type=list&desc=email';
    $active = $thisurl . '?type=list&desc=active';
    $dt = $thisurl . '?type=list&desc=reg_time';
    $dts = $thisurl . '?type=list&desc=last_login';
    $ip = $thisurl . '?type=list&desc=reg_ip';
} else {
    $uname = $thisurl . '?type=list&asc=user_name';
    $email = $thisurl . '?type=list&asc=email';
    $active = $thisurl . '?type=list&asc=active';
    $dt = $thisurl . '?type=list&asc=reg_time';
    $dts = $thisurl . '?type=list&asc=last_login';
    $ip = $thisurl . '?type=list&asc=reg_ip';
}
?>
<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}</style>
<div class="contentbox">
    <table cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th colspan="8" align="left"><span style="float:left">会员列表</span></th>
        </tr>
        <tr><td colspan="8" align="left">
                <img src="<?php echo $this->img('icon_search.gif'); ?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
                关键字 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ""; ?>">
              <!--  <select name="keyword2" id="keyword2">
                    <option value="">审核状态</option>
                    <option value="1">审核通过</option>
                    <option value="0">审核未通过</option>
                    <option value="2">未认证</option>
                </select>-->
                <input value=" 搜索 " class="cate_search" type="button">
            </td></tr>
        <tr>
            <th width="50"><label>商户号</label></th>
            <th><a href="<?php echo $uname; ?>">会员昵称(真实姓名)</a><br />[会员级别]</th>
            <th>头像</th>
            <th>手机号</th>
           
            <th><a href="<?php echo $dt; ?>">商家认证时间</a></th>
           <!-- <th><a href="<?php echo $dts; ?>">最后登录[地区]</a></th>
            <th><a href="<?php echo $ip; ?>">加入IP地址[地区]</a></th>-->
            <th>商家认证</th>
         
        </tr>
        <?php
        if (!empty($userlist)) {
            foreach ($userlist as $row) {
                ?>
                <tr>
                    <td align="center"><?php echo $row['uid'] ?></td>
                    <td align="center"><?php echo empty($row['nickname']) ? '未知' : $row['nickname']; ?>(<?php echo empty($row['uname']) ? '未知' : $row['uname']?>)<br/><font color="#3399FF">[<?php echo $row['level_name']; ?>]</font></td>
                    <td><img src="<?php echo!empty($row['headimgurl']) ? $row['headimgurl'] : $this->img('tx_img.gif'); ?>" height="60" /></td>
                    <td><?php echo $row['mobile']; ?>&nbsp;</td>
                    
                    <td><?php echo!empty($row['uptime']) ? date('Y-m-d H:i:s', $row['uptime']) : '未知'; ?></td>
                  
                   
                   <td>
                 <? if($row['status'] == ""){?>
                 
                 <? echo "未认证";?>
                 <? }else{ ?>
                 
                 <? if($row['status'] == 1){echo "审核通过";}else if($row['status'] == 2){echo "未审核";}else{echo "审核未通过";}?>
                  <a href="user.php?type=sj_shenhe&id=<?php echo $row['uid']; ?>&goto=sj_list" title="审核"><img src="<?php echo $this->img('icon_edit.gif'); ?>" title="审核"/>审核</a>
                 <? }?>
                 
                
                 
                   </td>
                   
                </tr>
    <?php }
    ?>
          
    <?php } ?>
    </table>
<?php $this->element('page', array('pagelink' => $pagelink)); ?>
</div>
<script type="text/javascript">
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

    //批量删除
    $('.bathdel').click(function () {
      var url = '<?php echo urlencode($url); ?>';
        if (confirm("确定删除吗？")) {
            //	createwindow();
            var arr = [];
            $('input[name="quanxuan"]:checked').each(function () {
                arr.push($(this).val());
            });
            var str = arr.join('+');
            ;
            $.post('<?php echo $thisurl; ?>', {action: 'bathdel', ids: str, url: url}, function (data) {
                //removewindow();
                if (data == "") {
                    location.reload();
                } else {
                    alert(data);
                }
            });
        } else {
            return false;
        }
    });

    $('.deluserid').click(function () {
        ids = $(this).attr('id');
        thisobj = $(this).parent().parent();
        var url = '<?php echo urlencode($url); ?>';
        if (confirm("确定删除吗？")) {
            //   alert(1212);
            createwindow();
            $.post('<?php echo $thisurl; ?>', {action: 'bathdel', ids: ids, url: url}, function (data) {
                removewindow();
                if (data == "") {
                    thisobj.hide(300);
                } else {
                    //alert(data);

                    location.href = 'user.php?type=priv_user&url=' + url;
                }

            });
        } else {
            return false;
        }
    });

    $('.activeop').live('click', function () {
        star = $(this).attr('alt');
        uid = $(this).attr('id');
        type = $(this).attr('lang');
        obj = $(this);
        $.post('<?php echo $thisurl; ?>', {action: 'activeop', active: star, uid: uid, type: type}, function (data) {
            if (data == "") {
                if (star == 1) {
                    id = 0;
                    src = '<?php echo $this->img('yes.gif'); ?>';
                } else {
                    id = 1;
                    src = '<?php echo $this->img('no.gif'); ?>';
                }
                obj.attr('src', src);
                obj.attr('alt', id);
            } else {
                alert(data);
            }
        });
    });

    //sous
    $('.cate_search').click(function () {

        keys = $('input[name="keyword"]').val();

        keys2 = $("#keyword2").val();

        location.href = '<?php echo Import::basic()->thisurl(); ?>&keyword=' + keys + '&keyword2=' + keys2;
    });
</script>