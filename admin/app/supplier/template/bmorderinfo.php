<div class="contentbox">

    <div class="menu_content">
        <!--start 通用信息-->
        <table cellspacing="2" cellpadding="5" width="100%" id="tab1">


            <tr>
                <td >编号:</td>
                <td><?php echo isset($rt['order_sn']) ? $rt['order_sn'] : ''; ?></td>
            </tr>
                        <tr>
                <td  >微信昵称:</td>
                <td><?php echo isset($rt['nickname']) ? $rt['nickname'] : ''; ?></td>
            </tr>
              <tr>
                <td  >缴费金额:</td>
                <td><?php echo isset($rt['order_amount']) ? $rt['order_amount'] : ''; ?></td>
            </tr>
            
  
              <tr>
                <td >支付状态:</td>
                <td><?php echo $row['pay_status']=='0' ? '未支付' : '<font color=blue>已支付 </font>';?></td>
            </tr>
            
            <tr>
                <td >真实姓名:</td>
                <td><?php echo isset($rt['uname']) ? $rt['uname'] : ''; ?></td>
            </tr>
            <tr>
                <td >手机号码:</td>
                <td>
                    <?php echo isset($rt['upne']) ? $rt['upne'] : ''; ?>
                </td>
            </tr>
            
<!--                <tr>
                <td >年龄:</td>
                <td>
                    <?php echo isset($rt['age']) ? $rt['age'] : ''; ?>
                </td>
            </tr>-->
                <tr>
                <td >性别:</td>
                <td>
                    <?php echo $row['gender']=='1' ? '男' : ' 女';?>
                </td>
            </tr>
                <tr>
                <td >身份证号码:</td>
                <td>
                    <?php echo isset($rt['cardcode']) ? $rt['cardcode'] : ''; ?>
                </td>
            </tr>
<!--                <tr>
                <td >毕业（所在）学校:</td>
                <td>
                    <?php echo isset($rt['school']) ? $rt['school'] : ''; ?>
                </td>
            </tr>
                <tr>
                <td >院（系）:</td>
                <td>
                    <?php echo isset($rt['department']) ? $rt['department'] : ''; ?>
                </td>
            </tr>
                <tr>
                <td >年级:</td>
                <td>
                    <?php echo isset($rt['grade']) ? $rt['grade'] : ''; ?>
                </td>
            </tr>
                <tr>
                <td >职务:</td>
                <td>
                    <?php echo isset($rt['job']) ? $rt['job'] : ''; ?>
                </td>
            </tr> 
            <tr>
                <td >籍贯详细地址:</td>
                <td>
                    <?php echo isset($rt['address']) ? $rt['address'] : ''; ?>
                </td>
            </tr>
            -->
              <tr>
                <td >QQ号:</td>
                <td>
                    <?php echo isset($rt['qq']) ? $rt['qq'] : ''; ?>
                </td>
            </tr>
              <tr>
                <td >微信:</td>
                <td>
                    <?php echo isset($rt['weixin']) ? $rt['weixin'] : ''; ?>
                </td>
            </tr>
<!--              <tr>
                <td >身份证正面:</td>
                <td>
                    <img src="../<?php echo isset($rt['cardphoto1']) ? $rt['cardphoto1'] : ''; ?>" width="100"  height="100"/>
                </td>
            </tr>
              <tr>
                <td >身份证反面:</td>
                <td>
                    <img src="../<?php echo isset($rt['cardphoto2']) ? $rt['cardphoto2'] : ''; ?>" width="100"  height="100"/>
                </td>
            </tr>-->
              <tr>
                <td >手持身份证正面大头照:</td>
                <td>
                    <a href="../<?php echo isset($rt['cardphoto3']) ? $rt['cardphoto3'] : ''; ?>" target="_blank"> <img src="../<?php echo isset($rt['cardphoto3']) ? $rt['cardphoto3'] : ''; ?>" width="100"  height="100"/></a>
                </td>
            </tr>
<!--              <tr>
                <td >个人全身照片:</td>
                <td>
                    <img src="../<?php echo isset($rt['cardphoto4']) ? $rt['cardphoto4'] : ''; ?>" width="100"  height="100"/>
                </td>
            </tr>-->
            
            

        </table>
        <!--end 通用信息-->


    </div> 

</div>





<!-------------- look修改  结束-------------- ------------------------------------------------->
