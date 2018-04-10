<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<title>智能还款</title>
<link rel="stylesheet" href="Instead/css/reset.css" type="text/css">
<link rel="stylesheet" href="Instead/css/css.css" type="text/css">
<script type="text/javascript" src="Instead/js/jquery-1.8.3.min.js"></script>
<?php echo '<script> var SITE_URL="'.ADMIN_URL.'";</script>'."\n";?>
<?php echo $this->js(array('jquery.min.js','jquery_dialog.js','common.js?v=21','fastclick.js'));?>
<script src="jquery-1.7.2.min.js" type="text/javascript"></script>
<style>
.black_overlay{
      		/*display:none;*/
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 130%;
            background-color:#ededed;
            z-index:9999;
            -moz-opacity: 0.8;
            opacity:.80;
            filter: alpha(opacity=50);
}

/*弹出框的css*/
.openwindow{ position:absolute;filter:alpha(opacity=50); -moz-opacity:0.8; -khtml-opacity:0.8;opacity:0.8;}
.openwindow{    z-index: 99999; left:30%; top:70%;padding-left:20px;text-align:left; background:url(images/loading.png) no-repeat;padding-top:18px;height:52px; width:240px;}


</style>
</head>

<body class="addBody">

<form name="myform" id="myform" action="<?php echo ADMIN_URL;?>user.php?act=bk_confirm_instead" method="post">
<ul class="addFrame">
    <h2 class="bBor">请输入您的身份信息</h2>
    <!--真实姓名-->
    <li class="addName bBor">
        <div class="add_name">
        <h1>真实姓名</h1>
        <input type="text"  name="name" id="name" readonly value="<?php echo $user['uname']?>"/>
        </div>
       
    </li>
   <!-- <script type="text/javascript">
            $(document).ready(function(e){
                /*点击删除 清空输入框的内容*/
                $('.btn').click(function(){
                    $('.add_name input').val('');
                });
            });
    </script>-->
    <!--身份证号-->
    <li class="addId bBor">
        <h1>身份证号</h1>
        <input type="text" name="idcard" id="idcard" readonly value="<?php echo $user['idcard']?>"/>
    </li>
    <h2 class="bBor tBor">请输入您的银行卡信息</h2>
    <!--银行卡号-->
    <li class="addNumber bBor">
        <h1>银行卡号</h1>
        <input type="text" name="bank_no" id="bank_no"  placeholder="请输入您银行卡号"/>
    </li>
    <!--银行-->
<script type="text/javascript">
    $(function(){
        $(".select").each(function(){
            var s=$(this);
            var z=parseInt(s.css("z-index"));
            var dt=$(this).children("dt");
            var dd=$(this).children("dd");
            var _show=function(){dd.slideDown(200);dt.addClass("cur");s.css("z-index",z+1);};   //展开效果
            var _hide=function(){dd.slideUp(200);dt.removeClass("cur");s.css("z-index",z);};    //关闭效果
            dt.click(function(){dd.is(":hidden")?_show():_hide();});
            dd.find("a").click(function(){dt.html($(this).html());_hide(); document.getElementById("bank").value=$(this).parent("li").val(); });     //选择效果（如需要传值，可自定义参数，在此处返回对应的“value”值 ）
            $("body").click(function(i){ !$(i.target).parents(".select").first().is(s) ? _hide():"";});
        })
    })
</script>
    <li class="addBank bBor">
        <div class="demo">
            <dl class="select">
                <dt><span>选择银行</span></dt>
                <dd>
                <ul>
                <?php foreach($bank as $b){?>
                <li value="<?php echo $b['id']?>" class="bBor"><a href="###"><img src="../<?php echo $b['pic']?>"><span><?php echo $b['name'];?></span></a></li>
                <?php }?>
              
                </ul>
                </dd>
            </dl>	
        </div>
    </li>
    
    <input type="hidden" name="bank" id="bank" value="0"/>
    <!--有效期限-->
    <li class="addValid bBor">
        <h1>有效期限</h1>
        <input type="text" name="valid" id="valid" placeholder="格式(月月年年)如：0715"/>
    </li>
    <!--安全码-->
    <li class="addSafe bBor">
        <h1>CVN2</h1>
        <input type="text" name="cvn2" id="cvn2" placeholder="卡背面末三位"/>
    </li>
    <!--预留手机-->
    <li class="addTel bBor">
        <h1>预留手机</h1>
        <input type="text" name="mobile" id="mobile" placeholder="请输入预留手机号"/>
    </li>
    <!--短信验证-->
    <li class="addValidation bBor">
        <h1>短信验证</h1>
        <input type="text" name="yz_code" id="yz_code" placeholder="输入短信验证码"/>
        <input id="get_yz_code" type="button"  style="width:26%;height:26px;position:absolute;right:5%;top:12px;
    line-height: 26px !important;
    background-color: #2cd792;
    border-radius: 13px;
    font-size: 12px;
    text-align: center;
    color: #fff;" value="获取验证码"  />
       
    </li>
    <!--我已同意XXXX《服务协议》-->
    <li class="addAgree">
        <input type="radio" checked value=""><p>我已同意收银服务<a href="new.php?id=23"><span>《服务协议》</span></a></p>
    </li>
    <h3><input value="确认绑卡" type="button"  id="save"></h3>
</ul>
</form>

<script>
 $('#save').click(function () {

	
	  var name = document.getElementById('name').value;
	   var idcard = document.getElementById('idcard').value;
        var bank_no = document.getElementById('bank_no').value;
        var mobile = document.getElementById('mobile').value;
	     var bank = document.getElementById('bank').value;
		 var valid = document.getElementById('valid').value;
		  var cvn2 = document.getElementById('cvn2').value;
		  var yz_code = document.getElementById('yz_code').value;
		
		  if (bank_no.length < 1)
        {
            alert('请填写信用卡！');
            return false;
        }
		
		else if(bank == 0){
			  alert('请选择开户行！');
            return false;
			}
		
		  else if (mobile.length < 1)
        {
            alert('请填写手机号码！');
            return false;
        }
		
		  else if (valid.length < 1)
        {
            alert('请填写信用卡有效期！');
            return false;
        }
		  else if (cvn2.length < 1)
        {
            alert('请填写信用卡背末三位！');
            return false;
        }
		 else if (yz_code.length < 1)
        {
            alert('请填写手机验证码！');
            return false;
        }
		
		$.post('<?php echo ADMIN_URL;?>user.php',{action:'validation_yz_code',mobile:mobile,yz_code:yz_code},function(data){ 
		if(data == 'success'){
	createwindow_instead();
			document.myform.submit();
			}else{
				alert(data);
				 return false;
				}
		});
		
	
	//	}	
 });
</script>

<script>
$("#get_yz_code").click(function(){

	sendMobileCode($("#mobile"), $("#yz_code"), $("#get_yz_code"));

		});
/**
 * 发送验证码
 * 
 * @param mobileObj
 *            手机号对象
 * @param mobileCodeObj
 *            短信验证码对象
 * @param sendButton
 *            点击发送短信证码的按钮对象，用于显示倒计时信息
 */
function sendMobileCode(mobileObj, mobileCodeObj, sendButton) {
	        countdown(sendButton);
			// 发送邮件
			var url = 'user.php?act=getcodes';
			$.post(url, {
				mobile: mobileObj.val()
			}, function(result) {
				if (result == 'ok') {
					// 倒计时
					/*countdown(sendButton);*/
				} else {
					alert(result);
				}
			}, 'text');
}
var wait = 60;
function countdown(obj, msg) {
	obj = $(obj);

	if (wait == 0) {
		obj.removeAttr("disabled");
		obj.val(msg);
		wait = 60;
	} else {
		if (msg == undefined || msg == null) {
			msg = obj.val();
		}
		obj.attr("disabled", "disabled");
		obj.val(wait + "秒后重新获取");
		wait--;
		setTimeout(function() {
			countdown(obj, msg)
		}, 1000)
	}
}


</script>
</body>
</html>
