<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/24/css.css" media="all" />
<link  type="text/css"  rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/3/style/index_style.css" />
<link  type="text/css"  rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/3/gift.css" />
<style type="text/css">
    .indexcon{ text-align:center}
    .indexcon img{ max-width:100%;}
    .footffont{ line-height:24px; }
    .footffontbox{  text-align:center; line-height:24px;}
    .gototop{height:32px; line-height:32px; position:fixed; bottom:65px; left:0px; right:0px; padding-right:5px; padding-left:5px; display:block}
    .pw2{background-color: #fff;}
    .pw{
        border: 1px solid #ddd;
        border-radius: 5px; margin-top:0.1rem;
        padding-left:5px; padding-right:5px;
        -moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
        -webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
        -khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
        border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
    }
    #main .indexcon img{ float:left; max-width:100%; margin:0px auto}
</style>

<script>


    // 5 图片接口
    // 5.1 拍照、本地选图
    var images = {
        localId: [],
        serverId: []
    };
    function chooseImage(id) {
   
      
        wx.chooseImage({
            success: function (res) {
                images.localId = res.localIds;
              
                $("#chooseImage"+id).hide();
                   $("#uploadImage"+id).removeClass('uploadImage_show');
            },
            fail:function(res){
                alert(res.errMsg);
            }
        })
        
    }
    


   

    // 5.3 上传图片
    function uploadImage(id) {
        if (images.localId.length == 0) {
            alert('请先使用 chooseImage 接口选择图片');
            return;
        }
        var i = 0, length = images.localId.length;
        images.serverId = [];
       
        function upload() {
         
            wx.uploadImage({
                
                            localId: images.localId[i],
                                    success: function (res) {
                                    i++;
                                         
                                       
                                            images.serverId.push(res.serverId);
                                            $.post(SITE_URL+'bm.php?act=uploadimg', {did:id, imgid: res.serverId}, function (txt) {
                                            if (txt == - 1) {
                                            alert("不支持类型");
                                            } else if (txt == - 2) {
                                            alert("图片太大");
                                            }
                                            else if (txt == - 3) {
                                            alert("网速太慢");
                                            } else {
                                                   
                                                    $("#showImage" + id).parent().removeClass('uploadImage_show');
                                                     $("#showImage" + id).attr("src", SITE_URL +  txt);
                                                    $("#cardphoto"+id).val(txt);
                                                    $("#uploadImage"+id).hide();
                                              
                                            }

                                            })
                                            if (i < length) {
                                    upload();
                                    }
                                    },
                                    fail: function (res) {
                                    alert(JSON.stringify(res));
                                    }
                            })
        }
        upload();
    }
    

    









</script>
<div class="gift_head"><h3></h3><?php
    echo $rt['pinfo']['title'];
    ?></div>
<div class="giftinfo_ct">

    <?php
    echo $rt['pinfo']['content'];
    ?>

    <div class="giftinfo_bt"><input style=" margin-left:0.1rem;" type="checkbox" id="select_all"  hidefocus="true" checked="checked"><label class="oauth_item_title" for="select_all">我已阅读并知晓、领用<span>《金葵花商城使用协议》</span></label></div>
</div>
<div class="footffont">
    <div class="footffontbox">
        <form id="ssumit" name="ssumit" method="post" action="<?php echo ADMIN_URL . 'bm.php?act=confirmpay'; ?>">
            <table cellpadding="3" cellspacing="5" border="0" width="100%">
                  <tr>
                    <td width="100%" align="center" style="text-align:center">
                       请认真填写以下全部信息
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="姓名" type="text" name="uname" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v8.png'); ?>) 5px center no-repeat #FFF; font-size:14px;background-size:26px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" ><div  style="width:80%; height:44px; line-height:44px; padding-left:35px; text-align:left; background:url(<?php echo $this->img('v7.png'); ?>) 5px center no-repeat #FFF; font-size:14px;background-size:26px;" class="pw pw2" >
                        <input  type="radio" name="gender" id="gender1" value="1"/>男 <input style="margin-left:0.3rem;" type="radio" name="gender" id="gender2" value="2"/>女
                   </div> </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="年龄" type="text" name="age" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v02.png'); ?>) 5px center no-repeat #FFF; font-size:14px;background-size:26px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="身份证号码" type="text" name="cardcode" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v03.png'); ?>) 5px center no-repeat #FFF; font-size:14px;background-size:26px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="毕业（所在）学校" type="text" name="school" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v9.png'); ?>) 5px center no-repeat #FFF; font-size:14px;background-size:26px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="院（系）" type="text" name="department" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v01.png'); ?>) 5px center no-repeat #FFF; font-size:14px;background-size:26px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="年级" type="text" name="grade" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v6.png'); ?>) 5px center no-repeat #FFF; font-size:14px;background-size:26px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="职务" type="text" name="job" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v5.png'); ?>) 5px center no-repeat #FFF; font-size:14px;background-size:26px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="籍贯详细地址" type="text" name="address" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v3.png'); ?>) 5px center no-repeat #FFF; font-size:14px;background-size:26px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="手机号码" type="text" name="upne" style="width:80%; height:44px; line-height:normal; padding-left:35px;background-size:26px;background:url(<?php echo $this->img('v4.png'); ?>) 7px center no-repeat #FFF;font-size:14px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="QQ号" type="text" name="qq" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v1.png'); ?>) 5px center no-repeat #FFF;background-size:26px; font-size:14px; " class="pw pw2"/>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center" style="text-align:center">
                        <input placeholder="微信号" type="text" name="weixin" style="width:80%; height:44px; line-height:normal; padding-left:35px; background:url(<?php echo $this->img('v2.png'); ?>) 5px center no-repeat #FFF; background-size:26px; font-size:14px;" class="pw pw2"/>
                    </td>
                </tr>
                <tr><td>	
                            <style>
                          
                            .uploadImage_show{display:none; }
                        </style>
                       <div class="giftinfo_sf">
                        <a  href="javascript:void(0)" onclick="chooseImage(1)" id="chooseImage1">身份证正面</a> 
                        <div class="uploadImage_show"> <img width="100%" src=""  id="showImage1"  /></div>
                        <br/>
                        <span style="background:#ec5151; color:#fff; padding:5px 10px; border-radius:5px;" onclick="uploadImage(1)" id="uploadImage1" class="uploadImage_show">上传身份证正面</span>
                                    <br/>  
                        <a  href="javascript:void(0)" onclick="chooseImage(2)" id="chooseImage2"> 身份证反面</a>
                          <div class="uploadImage_show">   <img width="100%" src=""  id="showImage2"  /></div>
                                    <br/>
                          <span  style="background:#ec5151; color:#fff; padding:5px 10px; border-radius:5px;" onclick="uploadImage(2)" id="uploadImage2" class="uploadImage_show">上传身份证反面</span>
                                      <br/> 
                          <a  href="javascript:void(0)" onclick="chooseImage(3)" id="chooseImage3">手持身份证正面大头照</a> 
                            <div class="uploadImage_show">        <img width="100%" src=""  id="showImage3"  /></div>
                                   <br/>
                            <span style="background:#ec5151; color:#fff; padding:5px 10px; border-radius:5px;"  onclick="uploadImage(3)" id="uploadImage3" class="uploadImage_show">上传手持身份证正面大头照</span>
                                            <br/>
                            <a  href="javascript:void(0)" onclick="chooseImage(4)" id="chooseImage4" class="uploadImage_show">个人全身照片</a> 
                           
                            <div class="uploadImage_show">  <img width="100%" src=""  id="showImage4"  /></div>
                                         <br/>
                            <span style="background:#ec5151; color:#fff; padding:5px 10px; border-radius:5px;"  onclick="uploadImage(4)" id="uploadImage4" class="uploadImage_show">上传个人全身照片</span>
                             <input type="hidden" name="cardphoto1" id="cardphoto1" value="" />
                             <input type="hidden" name="cardphoto2" id="cardphoto2" value="" />
                               <input type="hidden" name="cardphoto3" id="cardphoto3" value="" />
                                <input type="hidden" name="cardphoto4" id="cardphoto4" value="" />
                          
                       </div>
                    </td></tr>
                <tr>
                    <td align="center" style="color:#FF0000; font-size:14px;">
                        <span class="results"></span>
                    </td>
                </tr>
                <tr>
                    <td align="center" width="100%">
                        <a href="javascript:;" onclick="return check_senddata()" style="background:#ec5151; color:#FFF; font-size:14px; text-align:center; display:block; width:100%; padding-bottom:7px; padding-top:7px; height:24px; font-weight:bold;border-radius:5px;">立即报名(微信支付<?php echo $rt['pinfo']['price']; ?>)元</a>
                    </td>
                </tr>

            </table> 
            <input type="hidden" name="ids" value="<?php echo $rt['pinfo']['id']; ?>" />
            <input type="hidden" name="price" value="<?php echo $rt['pinfo']['price']; ?>" />
        </form>
    </div>
    <div style="clear:both"></div>
</div>
<div class="show_zhuan" style=" display:none;width:100%; height:100%; position:fixed; top:0px; right:0px; z-index:9999999;filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9; background:url(<?php echo $this->img('gz/121.png'); ?>) right top no-repeat #000;background-size:100% auto;" onclick="$(this).hide();"></div>
<?php
$thisurl1 = Import::basic()->thisurl();
$rr = explode('?', $thisurl1);
$t2 = isset($rr[1]) && !empty($rr[1]) ? $rr[1] : "";
$dd = array();
if (!empty($t2)) {
    $rr2 = explode('&', $t2);
    if (!empty($rr2))
        foreach ($rr2 as $v) {
            $rr2 = explode('=', $v);
            if ($rr2[0] == 'from' || $rr2[0] == 'isappinstalled' || $rr2[0] == 'code' || $rr2[0] == 'state')
                continue;
            $dd[] = $v;
        }
}
$thisurl = $rr[0] . '?' . (!empty($dd) ? implode('&', $dd) : 'tid=0');
?>
<script type="text/javascript">
    function check_senddata() {
        var select_all= $('#select_all:checked').val();
          if (select_all == "" || typeof (select_all) == 'undefined') {
            $('td .results').html("请先阅读《金葵花商城使用协议》");
            return false;
        }
        uuname = $('input[name="uname"]').val();
        if (uuname == "" || typeof (uuname) == 'undefined') {
            $('td .results').html("输入您的大名");
            return false;
        }
          gender = $('input[name="gender"]:checked').val();
        if (gender == "" || typeof (gender) == 'undefined') {
            $('td .results').html("请选择性别");
            return false;
        }
      
        age = $('input[name="age"]').val();
        if (age == "" || typeof (age) == 'undefined') {
            $('td .results').html("请输入年龄");
            return false;
        }
       cardcode = $('input[name="cardcode"]').val();
        if (cardcode == "" || typeof (cardcode) == 'undefined') {
            $('td .results').html("请输入身份证号码");
            return false;
        }
       school = $('input[name="school"]').val();
        if (school == "" || typeof (school) == 'undefined') {
            $('td .results').html("请输入毕业（所在）学校");
            return false;
        }
        
        
             department = $('input[name="department"]').val();
        if (department == "" || typeof (department) == 'undefined') {
            $('td .results').html("请输入院（系）");
            return false;
        }
             grade = $('input[name="grade"]').val();
        if (grade == "" || typeof (grade) == 'undefined') {
            $('td .results').html("年级");
            return false;
        }
             job = $('input[name="job"]').val();
        if (job == "" || typeof (job) == 'undefined') {
            $('td .results').html("职务");
            return false;
        }
          address = $('input[name="address"]').val();
        if (address == "" || typeof (address) == 'undefined') {
            $('td .results').html("籍贯详细地址");
            return false;
        }
              uupne = $('input[name="upne"]').val();
        if (uupne == "" || typeof (uupne) == 'undefined') {
            $('td .results').html("输入您正确的手机号码");
            return false;
        }
           qq = $('input[name="qq"]').val();
        if (qq == "" || typeof (qq) == 'undefined') {
            $('td .results').html("QQ");
            return false;
        }    weixin = $('input[name="weixin"]').val();
        if (weixin == "" || typeof (weixin) == 'undefined') {
            $('td .results').html("微信");
            return false;
        }
        
        
        
  
           cardphoto1 = $('input[name="cardphoto1"]').val();
        if (cardphoto1 == "" || typeof (cardphoto1) == 'undefined') {
            $('td .results').html("请上传身份证正面");
            return false;
        }
             cardphoto2 = $('input[name="cardphoto2"]').val();
        if (cardphoto2 == "" || typeof (cardphoto2) == 'undefined') {
            $('td .results').html("请上传身份证反面");
            return false;
        }
        
        
             cardphoto3 = $('input[name="cardphoto3"]').val();
        if (cardphoto3 == "" || typeof (cardphoto3) == 'undefined') {
            $('td .results').html("请上传手持身份证正面大头照");
            return false;
        }
        
             cardphoto4 = $('input[name="cardphoto4"]').val();
        if (cardphoto4 == "" || typeof (cardphoto4) == 'undefined') {
            $('td .results').html("请上传个人全身照片");
            return false;
        }
        
        
        $('#ssumit').submit();
        return true;
    }

    function show_zhuan() {
        $('.show_zhuan').show();
        $('body,html').animate({scrollTop: 0}, 500);
    }

    function ajax_submitbuy() {
        $('body,html').animate({scrollTop: 3000}, 500);
        check_senddata();
    }

    function _report(a, c) {
        $.post('<?php ADMIN_URL; ?>product.php', {action: 'ajax_share', type: a, msg: c, thisurl: '<?php echo Import::basic()->thisurl(); ?>', imgurl: '<?php echo!empty($rt['pinfo']['img']) ? SITE_URL . $rt['pinfo']['img'] : $this->img('logo4.png'); ?>', title: '<?php echo $title; ?>'}, function (data) {
        });
    }

<?php
$t = mktime();
$signature = sha1('jsapi_ticket=' . $lang['jsapi_ticket'] . '&noncestr=' . $lang['nonceStr'] . '&timestamp=' . $t . '&url=' . $thisurl1);
?>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?php echo $lang['appid']; ?>', // 必填，公众号的唯一标识
        timestamp: '<?php echo $t; ?>', // 必填，生成签名的时间戳
        nonceStr: '<?php echo $lang['nonceStr']; ?>', // 必填，生成签名的随机串
        signature: '<?php echo $signature; ?>', // 必填，签名，见附录1
        jsApiList: ['onMenuShareAppMessage', 'onMenuShareTimeline', 'onMenuShareQQ'  ,  'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });


  
</script>

