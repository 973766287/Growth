<header class="top_header">商户基础信息入驻</header>


    <div class="real_box">
  
<dl>
   <dt>商户名称：</dt>
   <dd><input type="text" id='merchantName' name='merchantName' value="烟台瀚诺科技" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>商户简称：</dt>
   <dd><input type="text" id='shortName' name='shortName' value="瀚诺" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>商户城市：</dt>
   <dd><input type="text" id='city' name='city' value="4560" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>商户地址：</dt>
   <dd><input type="text" id='merchantAddress' name='merchantAddress' value="烟台市芝罘区峰山路一号" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>客服电话：</dt>
   <dd><input type="text" id='servicePhone' name='servicePhone' value="0535-8458351" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>组织机构代码：</dt>
   <dd><input type="text" id='orgCode' name='orgCode' value="" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>商户类型：</dt>
   <dd><input type="text" id='merchantType' name='merchantType' value="01" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>经营类目代码：</dt>
   <dd><input type="text" id='category' name='category' value="5813" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>法人姓名：</dt>
   <dd><input type="text" id='corpmanName' name='corpmanName' value="邹智飞" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>法人身份证：</dt>
   <dd><input type="text" id='corpmanId' name='corpmanId' value="37028519871116295X" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>法人联系电话：</dt>
   <dd><input type="text" id='corpmanPhone' name='corpmanPhone' value="8458351" size=110 maxlength=20></dd>
  </dl>

<dl>
   <dt>法人联系手机：</dt>
   <dd><input type="text" id='corpmanMobile' name='corpmanMobile' value="18553574543" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>法人邮箱：</dt>
   <dd><input type="text" id='corpmanEmail' name='corpmanEmail' value="" size=110 maxlength=20></dd>
  </dl>
  
   <dt>银行代码：</dt>
   <dd><input type="text" id='bankCode' name='bankCode' value="102" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>开户行全称：</dt>
   <dd><input type="text" id='bankName' name='bankName' value="中国工商银行" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>开户行账号：</dt>
   <dd><input type="text" id='bankaccountNo' name='bankaccountNo' value="6222021606015220091" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>开户户名：</dt>
   <dd><input type="text" id='bankaccountName' name='bankaccountName' value="邹智飞" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>自动提现：</dt>
   <dd><input type="text" id='autoCus' name='autoCus' value="0" size=110 maxlength=20></dd>
  </dl>
<dl>
   <dt>备注：</dt>
   <dd><input type="text" id='remark' name='remark' value="测试" size=110 maxlength=20></dd>
  </dl>

  			   
</div>

<div class="real_sub" id="save">提交</div>




<script>

    


    $('#save').click(function () {
        var merchantName = document.getElementById('merchantName').value;
        var shortName = document.getElementById('shortName').value;
        var city = document.getElementById('city').value;
        var merchantAddress = document.getElementById('merchantAddress').value;
        var servicePhone = document.getElementById('servicePhone').value;
        var orgCode = document.getElementById('orgCode').value;
     
		 var merchantType = document.getElementById('merchantType').value;
		  var category = document.getElementById('category').value;
		    var corpmanName = document.getElementById('corpmanName').value;
			
			
			var corpmanId = document.getElementById('corpmanId').value;
			var corpmanPhone = document.getElementById('corpmanPhone').value;
			var corpmanMobile = document.getElementById('corpmanMobile').value;
			var corpmanEmail = document.getElementById('corpmanEmail').value;
			var bankCode = document.getElementById('bankCode').value;
			var bankName = document.getElementById('bankName').value;
			var bankaccountNo = document.getElementById('bankaccountNo').value;
			
			var bankaccountName = document.getElementById('bankaccountName').value;
			var autoCus = document.getElementById('autoCus').value;
			var remark = document.getElementById('remark').value;
			

		
				createwindow();
		
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'update_user_sj1',merchantName:merchantName,shortName:shortName,city:city,merchantAddress:merchantAddress,servicePhone:servicePhone,orgCode:orgCode,merchantType:merchantType,category:category,corpmanName:corpmanName,corpmanId:corpmanId,corpmanPhone:corpmanPhone,corpmanMobile:corpmanMobile,corpmanEmail:corpmanEmail,bankCode:bankCode,bankName:bankName,bankaccountNo:bankaccountNo,bankaccountName:bankaccountName,autoCus:autoCus,remark:remark},function(data){ 
		alert(data);
			removewindow();
			if(data['respCode'] == "000000"){
				
 document.location.href="<?php echo ADMIN_URL;?>user.php?act=sj_2";

 
}
		});
	
	
	
       
    });
	
	
	    


</script>
