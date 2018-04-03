


<div class="gift_head" ><h3></h3>礼包领取-<?php echo $gift['bag_name'] ?></div>



<div class="giftbag_div" >
    <div class="giftbag_img"><img src="<?php echo SITE_URL . $gift['goods_thumb']; ?>"  width="260"/> </div>
    <div class="giftbag_action">
    

        <form action="<?php echo SITE_URL; ?>user.php?act=gift_save" method="post" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS">

            <?php if ($has != 1) { ?>
                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;" >
                    <input type="hidden" name="bid" value="<?php echo $gift['bid'] ?>"/>
                    <?php if (!empty($rt['userress'])) { ?>
                        <tr   bgcolor="#f4f4f4" align="center"  height="30px;">
                            <td   width="5%">   选择  </td>
                            <td width="20%">联系人</td>
                            <td width="20%">联系电话</td>
                            <td width="45%">    地址  </td>
                            <td width="10%"> 操作 </td>
                        </tr>

                        <?php
                        $userress_id = 0;
                        foreach ($rt['userress'] as $row) {
                            ?>
                            <tr    align="center"  height="30px;" >
                                <td   >
                                    <input<?php echo $row['is_default'] == '1' ? ' checked="checked"' : ''; ?> type="radio" class="showaddress" name="userress_id" value="<?php echo $row['address_id']; ?>"/>
                                </td>
                                <td><?php echo $row['consignee']; ?></td>
                                <td><?php echo!empty($row['mobile']) ? $row['mobile'] : $row['tel']; ?></td>
                                <td>     <?php
                                    echo $row['provincename'] . $row['cityname'] . $row['districtname'] . $row['address'];
                                    ?> 
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="ressinfoop('<?php echo $row['address_id']; ?>', 'showupdate', this)" style="border-radius:5px;display:block;background:#ec5151;cursor:pointer;width:60px; height:22px; line-height:22px; font-size:12px; color:#FFF; text-align:center">修改</a>

                                </td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    <?php
                    $userress_id = $userress_id > 0 ? $userress_id : (isset($rt['userress'][0]) ? $rt['userress'][0]['address_id'] : 0);
                    ?>
                    <tr height="30px;"  align="left" > 
                        <td colspan="5"><label style="padding-left:10px;"><input class="showaddress" name="userress_id" type="radio" value="0" />&nbsp;添加新收货地址</label></td>
                    </tr>
                    <tr>
                        <td align="left" colspan="5">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0"<?php if (!empty($rt['userress'])) echo ' style="display:none"'; ?> class="userreddinfo">
                                <tr>
                                    <td align="right">姓名：</td>
                                    <td align="left"><input type="text" value="" name="consignee"  class="pw" style="width:95%;;"/> 
                                    </td>
                                </tr>
                                <tr style="height:30px;">
                                    <td align="right">区域：</td>
                                    <td align="left">
    <?php $this->element('address', array('resslist' => $rt['province'])); ?>
                                    </td>

                                </tr>
                                <tr class="address_sh"  style="height:30px;">
                                    <td align="right">地址：</td>
                                    <td align="left"><input type="text" value="" name="address"  class="pw" style="width:95%;;"/></td>
                                </tr>
                                <tr  style="height:30px;">
                                    <td align="right">电话：</td>
                                    <td align="left"><input type="text" value="" name="mobile"  class="pw" style="width:95%;"/></td>
                                </tr>
                                <tr  style="height:30px;">
                                    <td>&nbsp;</td>
                                    <td align="left" colspan="2"><img src="<?php echo $this->img('btu_add.gif'); ?>" alt="" style="cursor:pointer" onclick="ressinfoop('0', 'add', 'CONSIGNEE_ADDRESS')"/></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

<?php } ?>
            <div style=" width:600px;margin:10px auto;height:30px; line-height:30px;" >


                <p style="height:30px; line-height:30px; margin-top:10px;">
                    <?php if ($has == 1) { ?>
                        您已经领取了该礼包
                        <?php
                    } elseif ($gift['type'] < $userinfo['user_rank']) {
                        echo '您无法领取该礼包';
                    } else {
                        ?>
                        <input value="领取礼包" type="submit" align="absmiddle" onclick="return checkvar()" style=" height:30px; line-height:30px; background:#ec5151; font-size:14px; color:#FFFFFF; font-weight:bold; text-align:center; cursor:pointer; border:0px;padding:0px 20px"/>
<?php } ?>
                </p>
            </div> 
        </form>
    </div>
    <div class="clear"></div>
    <div class="giftbag_desc_head">礼包简介</div>
    <div class="giftbag_desc">
<?php echo $gift['bag_desc'] ?>
    </div>


</div>


<script>
    $('.showaddress').live('click', function () {
        var vv = $(this).val();
        if (vv == 0) {
            $('.userreddinfo').show();
        } else {
            $('.userreddinfo').hide();
        }
        //$('.userreddinfo').toggle();
    });
    function checkvar() {
        userress_id = $('input[name="userress_id"]:checked').val();
        if (userress_id == '0' || userress_id == '' || typeof (userress_id) == 'undefined') {
            consignee = $('input[name="consignee"]').val();
            if (typeof (consignee) == 'undefined' || consignee == "") {
                alert("收货人不能为空！");
                return false;
            }

            provinces = $('select[name="province"]').val();
            if (provinces == '0')
            {
                alert("请选择收货地址！");
                return false;
            }

            city = $('select[name="city"]').val();
            if (city == '0')
            {
                alert("请完整选择收货地址！");
                return false;
            }

            district = $('select[name="district"]').val();
            if (district == '0')
            {
                alert("请完整选择收货地址！");
                return false;
            }

            address = $('input[name="address"]').val();
            if (typeof (address) == 'undefined' || address == "") {
                alert("详细地址不能为空！");
                return false;
            }

            mobile = $('input[name="mobile"]').val();
            tel = $('input[name="tel"]').val();
            if (mobile == "" && tel == "") {
                alert("请输入手机或者电话号码！");
                return false;
            }
        }
    }
</script>



<?php $this->element('3/footer', array('lang' => $lang)); ?>