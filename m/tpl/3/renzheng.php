<header class="top_header">实名认证</header>
	<form name="USERINFO2" id="USERINFO2" action="" method="post">
  
<div class="real_box">
  <dl>
   <dt>手机号</dt>
   <dd><input name="mobile" id="mobile" maxlength="11" type="text" value="<?php  echo $rts['mobile'];?>" placeholder="请输入您的常用手机号"></dd>
    <!--div style="background: #0099e5;width: 80%;font-size: 12px;border: 0;border-radius: 5px;color: #fff;height: 30px;text-align: center;"  id="get_yz_code">获取验证码</div--></dd>
  </dl>
  
  <dl>
   <dt>真实姓名</dt>
   <dd><input name="name" id="name"   type="text"  value="<?php  echo $rts['uname'];?>" placeholder="请如实填写"></dd>
  </dl>
  <dl>
   <dt>证件类型   </dt>
   <dd>身份证</dd>
  </dl>
  <dl>
   <dt>证件号码</dt>
   <dd><input name="card_no" id="card_no"   type="text" value="<?php  echo $rts['idcard'];?>"   placeholder="证件号码"></dd>
  </dl>
  <dl>
   <dt>身份证正面、银行卡正面</dt>
   <dd>

<input type="hidden" class="button" name="card_front_img" id="imageUp_1"  value="<? echo $rts['card_front_img'];?>">
   <img src="<?php  echo $rts['card_front_img']? "/".$rts['card_front_img']:"img/card1.png";?>" id="card_front" name="card_front" class="primary photo" style="width:90%; max-height:200px;">
 
   </dd>
  </dl>
  
  <dl>
   <dt>身份证反面、信用卡正面</dt>
   <dd>


   <input type="hidden" class="button" name="card_back_img" id="imageUp_2"  value="<? echo $rts['card_back_img'];?>">
    <img src="<?php  echo $rts['card_back_img']? "/".$rts['card_back_img']:"img/card2.png";?>" id="card_back" name="card_back" class="primary photo" style="width:90%; max-height:200px;">
   </dd>
  </dl>
  
  <dl>
   <dt>银行卡号</dt>
   <dd><input name="bank_no" id="bank_no"   type="text" value="<?php  echo $rts['banksn'];?>"   placeholder="银行卡号"></dd>
  </dl>
  <dl>
   <dt>开户行</dt>
   <dd><select style="border:0;" class="real_select" name="bank" id="bank">
     <option value="" style="border:0px; background:none;">请选择</option>
    <?  foreach($bank as $row){?>
            <option class="opt" value="<? echo $row['id']?>" <? if ($rts['bank'] == $row['id']){?>selected <? }?>><? echo $row['name'];?></option>
          
            <? }?>

   </select></dd>
  </dl>
 
  <dl>
   <dt>手持身份证正面及信用卡正面</dt>
   <dd>
 
       <input type="hidden" class="button" name="card_hand_img" id="imageUp_3"  value="<? echo $rts['card_hand_img'];?>">
       <img src="<?php  echo $rts['card_hand_img']? "/".$rts['card_hand_img']:"img/card3.png";?>" id="card_hand" name="card_hand" class="primary photo" style="width:90%; max-height:200px;">
   </dd>
  </dl>
  
  
 
                
  		   
  		   <dl>
   <dt>短信验证码</dt>
   <dd><input id="yz_code" name="yz_code"   type="yz_code"  placeholder="请输入手机验证码" ></dd>
   <dd><input id="get_yz_code" type="button"  style="background: #0099e5;width: 90%;font-size: 12px;border: 0;border-radius: 5px;color: #fff;height: 30px;text-align: center;" value="获取验证码"  /></dd>
  </dl>
  			   
</div>


<div class="real_sub" id="save">提交审核</div>
</form>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

<script>
  
 wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
	
	"chooseImage",
         
          "uploadImage",
       
      // 所有要调用的 API 都要加到这个列表中
    ]
  });

    // 5.1 拍照、本地选图
    var imagesA = {
        localId: [],
        serverId: []
    };

    var imagesB = {
        localId: [],
        serverId: []
    };

    var imagesC = {
        localId: [],
        serverId: []
    };
	

    document.querySelector('#card_front').onclick = function () {
        wx.chooseImage({
			
            count: 1, // 默认9
            //sizeType: ['original','compressed'], // 可以指定是原图还是压缩图，默认二者都有
			sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesA.localId = res.localIds;
                document.getElementById('card_front').src = imagesA.localId;

                $('#imageUp_1').val(imagesA.localId);
            upload(imagesA.localId,'imageUp_1');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
		
    };

    document.querySelector('#card_back').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
           sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesB.localId = res.localIds;
                document.getElementById('card_back').src = imagesB.localId;
                $('#imageUp_2').val(imagesB.localId);
                upload(imagesB.localId,'imageUp_2');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };

  
	
	
	  document.querySelector('#card_hand').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
          sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesC.localId = res.localIds;
                document.getElementById('card_hand').src = imagesC.localId;
                $('#imageUp_3').val(imagesC.localId);
                upload(imagesC.localId,'imageUp_3');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };

    function upload(localIds,inputid){
        // 上传照片
        wx.uploadImage({
            localId: '' + localIds,
            isShowProgressTips: 1,
            success: function(res) {
                serverId = res.serverId;
                $("#"+inputid).val(serverId); // 把上传成功后获取的值附上
            }
        });
    }
    


    $('#save').click(function () {
        var mobiles = document.getElementById('mobile').value;
        var names = document.getElementById('name').value;
        var card_no = document.getElementById('card_no').value;
        var bank_nos = document.getElementById('bank_no').value;
        var banks = document.getElementById('bank').value;
        var yz_codes = document.getElementById('yz_code').value;
     
		 var card_front_img = document.getElementById('imageUp_1').value;
		  var card_back_img = document.getElementById('imageUp_2').value;
		
		    var card_hand_img = document.getElementById('imageUp_3').value;
			
		
		var aCity={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"} 

    var iSum=0 ;
    var info="" ;
  

			  
        if (mobiles.length < 10)
        {
            alert('请填写手机号码！',{TimeShown : 2000});
            return false;
        }
		else if (yz_codes.length < 6)
        {
            alert('请填写手机验证码！',{TimeShown : 2000});
            return false;
        }
		else if (names.length < 1)
        {
            alert('请填写姓名！',{TimeShown : 2000});
            return false;
        }
        else if (card_no.length < 15)
        {
            alert('请填写身份证号码！',{TimeShown : 2000});
            return false;
        }
	
			  if(!/^\d{17}(\d|x)$/i.test(card_no)){
				   alert('你输入的身份证长度或格式错误！');
				   return false; 
			  }
    card_nos=card_no.replace(/x$/i,"a"); 
    if(aCity[parseInt(card_nos.substr(0,2))]==null){
		 alert('你的身份证地区非法！');
		 return false; 
	}
    sBirthday=card_nos.substr(6,4)+"-"+Number(card_nos.substr(10,2))+"-"+Number(card_nos.substr(12,2)); 
    var d=new Date(sBirthday.replace(/-/g,"/")) ;
	
    if(sBirthday!=(d.getFullYear()+"-"+ (d.getMonth()+1) + "-" + d.getDate())){
		 alert('身份证上的出生日期非法！');
		return false; 
	}
	
    for(var i = 17;i>=0; i--) iSum += (Math.pow(2,i) % 11) * parseInt(card_nos.charAt(17 - i),11) ;
	
    if(iSum%11!=1){
		 alert('你输入的身份证号非法！');
		 return false; 
	}
	
	
		
		
		 else if (card_front_img == '')
        {
            alert('请上传身份证正面、银行卡正面照！',{TimeShown : 2000});
            return false;
        }
        else if (card_back_img == '')
        {
            alert('请上传身份证反面、信用卡正面照！',{TimeShown : 2000});
            return false;
        }
        else if (bank_nos.length < 10)
        {
            alert('请填写银行卡号！',{TimeShown : 2000});
            return false;
        }
        else if (banks == "")
        {
            alert('请选择银行！',{TimeShown : 2000});
            return false;
        }
      
       
		
        else if (card_hand_img == '')
        {
            alert('请上传手持身份证正面、信用卡正面半身照！',{TimeShown : 2000});
            return false;
        }
		else{
		
				createwindow();
		
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'update_user_bank',mobile:mobiles,name:names,card_no:card_no,card_front_img:card_front_img,card_back_img:card_back_img,bank_no:bank_nos,bank:banks,card_hand_img:card_hand_img,yz_code:yz_codes},function(data){ 
		//alert(data);
			removewindow();
			if(data == "提交成功"){
				 if(confirm("继续商家认证，获取专属收款二维码"))
 {
 document.location.href="<?php echo ADMIN_URL;?>user.php?act=sj_renzheng";
 }else{
				
			WeixinJSBridge.call('closeWindow');
 }
}
		});
	
	
	
        }
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
			// 发送邮件
			var url = 'user.php?act=getcodes';
			$.post(url, {
				mobile: mobileObj.val()
			}, function(result) {
				if (result == 'ok') {
					// 倒计时
					countdown(sendButton);
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
