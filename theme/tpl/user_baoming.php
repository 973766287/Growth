<style>
    .r{color:red}
</style>
<div id="wrap" style="padding-top:10px">
    <div class="clear7"></div>
    <?php $this->element('user_menu'); ?>
    <div class="mt">
        <h2><?php echo NAVNAME; ?></h2>
        <?php
        echo $rt['pinfo']['content'];
        ?>
    </div>
     <?php if ($rank == 1 || $rank > $rt['pinfo']['rank_id']) {
            ?>
    <div class="mt"><input style=" margin-left:0.1rem;" type="checkbox" id="select_all"  hidefocus="true" checked="checked">
        <label class="oauth_item_title" for="select_all">我已阅读并知晓、领用<span>《金葵花商城使用协议》</span></label>
   
        <span class="select_all_mes  r">*</span>
    </div>
 <?php }?>
    <div id="content_1" class="content" style="text-align:center">
                <?php if ($rank != 1 and $rank <= $rt['pinfo']['rank_id']) {
            ?>
            <table cellpadding="3" cellspacing="5" border="0" width="100%">
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <?php if($rank==10){
                            ?>
                        您已经是最高级别了
                 <?php        }else{?>
                        您的级别高于当前级别，您可以向更高级别进军了！   <br/>
                        <a href="<?php echo SITE_URL; ?>user.php?act=baoming&id=<?php echo $rt['pinfo']['id']+1; ?>" style="color:red">立即升级</a>
                 <?php } ?>
                    </td>    
                </tr>
                <?php if($hasgift){
                   ?>
                 <tr>
                    <td width="100%" align="center" style="text-align:center">
                        您的奖品已经领取
                    </td>
                </tr>
                <?php
                }else{?>
                  <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <a href="<?php echo SITE_URL; ?>user.php?act=giftlist&rank=<?php echo $rank;?>" style="color:red" target="_blank">您还未免费领取奖品，请前往领取！</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <?php
        } else {
            ?>
        
        <form id="BAOMING" name="BAOMING" method="post" action="<?php echo SITE_URL . 'user.php?act=confirmpay'; ?> " enctype="multipart/form-data"   >
            <table border="0" style="line-height:40px; padding-top:50px; margin:0px auto">
                <tr>
                    <td colspan="2"> 请认真填写以下全部信息</td>

                </tr>
                <tr>
                    <td width="35%" style="text-align:right">姓名：</td>
                    <td align="left"  width="50%">
                        <input type="text" name="uname"  style="height:28px; line-height:28px; width:88%;"/>
                    </td>
                    <td align="left" width="15%"  class="textred uname_mes r">*</td>
                </tr>
                <tr>
                    <td  width="35%" style="text-align:right">性别：</td>
                    <td align="left" width="50%">  <input  type="radio" name="gender" id="gender1" value="1"/>男 
                        <input style="margin-left:0.3rem;" type="radio" name="gender" id="gender2" value="2"/>女</td>
                    <td align="left" width="15%"  class="textred gender_mes r">*</td>
                </tr>

<!--                <tr>
                    <td  width="35%" style="text-align:right">年龄：</td>
                    <td align="left" width="50%">  <input  type="text"  name="age"  value="" style="height:28px; line-height:28px; width:88%;"/> </td>
                    <td  align="left" width="15%"   class="textred age_mes">*</td>
                </tr>-->
                <tr>
                    <td  width="35%" style="text-align:right">身份证号码：</td>
                    <td align="left"width="50%">  <input  type="text"  name="cardcode"  value="" style="height:28px; line-height:28px; width:88%;"/> </td>
                    <td  align="left" width="15%"   class="textred cardcode_mes r">*</td>
                </tr>
<!--                <tr>
                    <td  width="35%" style="text-align:right">毕业（所在）学校：</td>
                    <td align="left" width="50%">  <input  type="text"  name="school"  value="" style="height:28px; line-height:28px;width:88%;"/> </td>
                    <td  align="left" width="15%"   class="textred school_mes">*</td>
                </tr>

                <tr>
                    <td  width="35%" style="text-align:right">院（系）：</td>
                    <td align="left" width="50%">  <input  type="text"  name="department"  value="" style="height:28px; line-height:28px;width:88%;"/> </td>
                    <td  align="left" width="15%"   class="textred department_mes">*</td>
                </tr>

                <tr>
                    <td  width="35%" style="text-align:right">年级：</td>
                    <td align="left" width="50%">  <input  type="text"  name="grade"  value="" style="height:28px; line-height:28px; width:88%;"/> </td>
                    <td  align="left" width="15%"   class="textred grade_mes">*</td>
                </tr>

                <tr>
                    <td width="35%"  style="text-align:right">职务：</td>
                    <td align="left" width="50%">  <input  type="text"  name="job"  value="" style="height:28px; line-height:28px;width:88%;"/> </td>
                    <td  align="left" width="15%"   class="textred job_mes">*</td>
                </tr>
                <tr>
                    <td  width="35%" style="text-align:right">籍贯详细地址：</td>
                    <td align="left" width="50%">  <input  type="text"  name="address"  value="" style="height:28px; line-height:28px; width:88%;"/> </td>
                    <td align="left" width="15%"   class="textred address_mes">*</td>
                </tr>-->
                <tr>
                    <td  width="35%" style="text-align:right">手机号码：</td>
                    <td align="left" width="50%">  <input  type="text"  name="upne"  value="" style="height:28px; line-height:28px; width:88%;"/> </td>
                    <td  align="left" width="15%"   class="textred upne_mes r">*</td>
                </tr>

                <tr>
                    <td  width="35%" style="text-align:right">QQ号：</td>
                    <td align="left" width="50%">  <input  type="text"  name="qq"  value="" style="height:28px; line-height:28px; width:88%;"/> </td>
                    <td  align="left" width="15%"   class="textred qq_mes r">*</td>
                </tr>

                <tr>
                    <td  width="35%" style="text-align:right">微信号：</td>
                    <td align="left" width="50%">  <input  type="text"  name="weixin"  value="" style="height:28px; line-height:28px; width:88%;"/> </td>
                    <td  align="left" width="15%"  class="textred weixin_mes r">*</td>
                </tr>
<!--                <tr>
                    <td  width="35%" style="text-align:right">身份证正面：</td>
                    <td align="left" width="50%">   <input name="cardphoto1" id="cardphoto1" type="hidden"  >
                        <iframe id="iframe_t1" name="iframe_t1" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=cardphoto1&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>

                    </td>
                    <td align="left" class="textred cardphoto1_mes"></td>

                </tr>
                <tr>
                    <td  width="35%" style="text-align:right">身份证反面：</td>
                    <td align="left" width="50%">   <input name="cardphoto2" id="cardphoto2" type="hidden"  >
                        <iframe id="iframe_t2" name="iframe_t2" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=cardphoto2&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe> </td>
                    <td align="left" class="textred cardphoto2_mes"></td>
                </tr>-->
                <tr>
                    <td  width="35%" style="text-align:right">手持身份证正面大头照：</td>
                    <td align="left" width="50%" >  <input name="cardphoto3" id="cardphoto3" type="hidden"  >
                        <iframe id="iframe_t3" name="iframe_t3" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=cardphoto3&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe> </td>
                    <td align="left" class="textred cardphoto3_mes r"></td>
                </tr>
<!--                <tr>
                    <td  width="35%" style="text-align:right">个人全身照片：</td>
                    <td align="left" width="50%">  <input name="cardphoto4" id="cardphoto4" type="hidden"  >
                        <iframe id="iframe_t4" name="iframe_t4" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=cardphoto4&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe> </td>
                    <td align="left" class="textred cardphoto4_mes"></td>
                </tr>-->


                <tr>
                    <td>  <input type="hidden" name="ids" value="<?php echo $rt['pinfo']['id']; ?>" />
                        <input type="hidden" name="price" value="<?php echo $rt['pinfo']['price']; ?>" /></td>
                    <td align="left"><label>
                            <input type="button" onclick="check_bm()" name="Submit" value="立即充值报名"  style="text-align:center;padding:0px 10px;line-height: 28px; height:28px; cursor:pointer"/>
                        </label></td>
                </tr>
            </table>
        </form>
        <?php } ?>
        <div class="clear10"></div>
    </div>
    <script>
        function check_bm() {
             var select_all = $('#select_all:checked').val();
        if (select_all == "" || typeof (select_all) == 'undefined') {
           $('.select_all_mes').html("请先阅读《金葵花商城使用协议》");
            return false;
        }  clearmes_item1();

            var uname = $('#BAOMING input[name="uname"]').val();
            var gender = $('#BAOMING input[name="gender"]:checked').val();
            //var age = $('#BAOMING input[name="age"]').val();
            var cardcode = $('#BAOMING input[name="cardcode"]').val();
           // var school = $('#BAOMING input[name="school"]').val();
           // var department = $('#BAOMING input[name="department"]').val();
           // var grade = $('#BAOMING input[name="grade"]').val();
           // var job = $('#BAOMING input[name="job"]').val();
        //    var address = $('#BAOMING input[name="address"]').val();
            var upne = $('#BAOMING input[name="upne"]').val();
            var qq = $('#BAOMING input[name="qq"]').val();
            var weixin = $('#BAOMING input[name="weixin"]').val();
         //   var cardphoto1 = $('#BAOMING input[name="cardphoto1"]').val();
         //   var cardphoto2 = $('#BAOMING input[name="cardphoto2"]').val();
            var cardphoto3 = $('#BAOMING input[name="cardphoto3"]').val();
         //   var cardphoto4 = $('#BAOMING input[name="cardphoto4"]').val();

            if (uname == "" || typeof (uname) == 'undefined') {
                $('#BAOMING .uname_mes').html("姓名不能为空！");
                return false;
            }
            clearmes_item1();

            if (gender == "" || typeof (gender) == 'undefined') {
                $('#BAOMING .gender_mes').html("请选择性别！");
                return false;
            }
            clearmes_item1();

           /* if (age == "" || typeof (age) == 'undefined') {
                $('#BAOMING .age_mes').html("年龄不能为空！");
                return false;
            }
            clearmes_item1();*/

            if (cardcode == "" || typeof (cardcode) == 'undefined') {
                $('#BAOMING .cardcode_mes').html("身份证号码不能为空！");
                return false;
            }
            clearmes_item1();

          /*  if (school == "" || typeof (school) == 'undefined') {
                $('#BAOMING .school_mes').html("毕业（所在）学校不能为空！");
                return false;
            }
            clearmes_item1();

            if (department == "" || typeof (department) == 'undefined') {
                $('#BAOMING .department_mes').html("院（系）不能为空！");
                return false;
            }
            clearmes_item1();

            if (grade == "" || typeof (grade) == 'undefined') {
                $('#BAOMING .grade_mes').html("年级不能为空！");
                return false;
            }
            clearmes_item1();

            if (job == "" || typeof (job) == 'undefined') {
                $('#BAOMING .job_mes').html("职务不能为空！");
                return false;
            }
            clearmes_item1();
            if (address == "" || typeof (address) == 'undefined') {
                $('#BAOMING .address_mes').html("籍贯详细地址不能为空！");
                return false;
            }
            clearmes_item1();*/
            if (upne == "" || typeof (upne) == 'undefined') {
                $('#BAOMING .upne_mes').html("手机号码不能为空！");
                return false;
            }
                   clearmes_item1();
            if(upne!=""&&typeof(upne)!='undefined'){
				clearmes_item1();
				if(!isMobile(upne)){
					    $('#BAOMING .upne_mes').html("你输入的手机号码不合法！");
					return false;
				}
			}
     
            if (qq == "" || typeof (qq) == 'undefined') {
                $('#BAOMING .qq_mes').html("QQ号不能为空！");
                return false;
            }     clearmes_item1();
                 if(qq!=""&&typeof(qq)!='undefined'){
				clearmes_item1();
				if(!isqq(upne)){
					    $('#BAOMING .qq_mes').html("你输入的QQ号不合法！");
					return false;
				}
			}
       
            if (weixin == "" || typeof (weixin) == 'undefined') {
                $('#BAOMING .weixin_mes').html("微信号不能为空！");
                return false;
            }
            clearmes_item1();
         /*   if (cardphoto1 == "" || typeof (cardphoto1) == 'undefined') {
                $('#BAOMING .cardphoto1_mes').html("身份证正面不能为空！");
                return false;
            }
            clearmes_item1();
            if (cardphoto2 == "" || typeof (cardphoto2) == 'undefined') {
                $('#BAOMING .cardphoto2_mes').html("身份证反面不能为空！");
                return false;
            }*/
            clearmes_item1();
            if (cardphoto3 == "" || typeof (cardphoto3) == 'undefined') {
                $('#BAOMING .cardphoto3_mes').html("手持身份证正面大头照不能为空！");
                return false;
            }
            clearmes_item1();
         /*   if (cardphoto4 == "" || typeof (cardphoto4) == 'undefined') {
                $('#BAOMING .cardphoto4_mes').html("个人全身照片不能为空！");
                return false;
            }
            clearmes_item1();*/
            $("#BAOMING").submit();
        }
        function clearmes_item1() {
            arr = ['select_all_mes','uname_mes', 'gender_mes', 'age_mes', 'cardcode_mes', 'school_mes', 'department_mes', 'grade_mes', 'job_mes', 'address_mes', 'upne_mes', 'qq_mes', 'weixin_mes', 'cardphoto1_mes', 'cardphoto2_mes', 'cardphoto3_mes', 'cardphoto4_mes'];
            for (i = 0; i < arr.length; i++) {
                $('.' + arr[i]).html("*");
            }
        }
    </script>

</div>
<div class="clear20"></div> 
