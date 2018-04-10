<header class="top_header">添加新卡</header>
	<form name="myform" id="myform" action="<?php echo ADMIN_URL;?>mycart.php?type=bk_confirm_h5_hq" method="post">
<div class="real_box">
  
  
  <dl>
   <dt style="width:120px;">持卡人姓名</dt>
   <dd><input name="name" id="name"   type="text"  value="<?php  echo $rt['uname'];?>" readonly ></dd>
  </dl>
  
  <dl>
   <dt style="width:120px;">身份证号</dt>
   <dd><input name="idcard" id="idcard"   type="text" value="<?php  echo $rt['idcard'];?>"  readonly ></dd>
  </dl>
  
    <dl>
   <dt style="width:120px;">开户行</dt>
   <dd>
   <select name="b_code" id="b_code">
   <option value="ICBC">中国工商银行</option>
    <option value="BOC">中国银行</option>
     <option value="CMBCHINA">招商银行</option>
      <option value="CGB">广发银行</option>
       <option value="POST">中国邮政储蓄银行</option>
        <option value="CMBC">民生银行</option>
         <option value="HXB">华夏银行</option>
          <option value="ABC">中国农业银行</option>
           <option value="CCB">中国建设银行</option>
            <option value="SPDB">上海浦东发展银行</option>
             <option value="BOCO">交通银行</option>
              <option value="ECITIC">中信银行</option>
              <option value="CEB">中国光大银行</option>
              <option value="CIB">兴业银行</option>
              <option value="PINGANBANK">平安银行</option>
              <option value="SHB">上海银行</option>
   </select>
   </dd>
  </dl>
  
  
  
  <dl>
   <dt style="width:120px;">信用卡卡号</dt>
   <dd><input name="bank_no" id="bank_no"   type="text" value=""   placeholder="请输入您的银行卡号"></dd>
  </dl>
  <!-- <dl>
   <dt style="width:120px;">预留手机号</dt>
   <dd><input name="mobile" id="mobile"   type="text" value=""   placeholder="请输入您的银行卡预留手机号"></dd>
  </dl>-->
  
<!--   <dl>
   <dt style="width:120px;">有效期</dt>
   <dd><input name="valid" id="valid"   type="text" value=""   placeholder="格式(月月年年)如：0715"></dd>
  </dl>
   <dl>
   <dt style="width:120px;">卡背面末三位</dt>
   <dd><input name="cvn2" id="cvn2"   type="text" value=""   placeholder="请输入信用卡背末三位"></dd>
  </dl>-->
  
  		   
  		   
  			   
</div>


<div class="real_sub" id="save">提交</div>
</form>

<script>
 $('#save').click(function () {
	
	  var name = document.getElementById('name').value;
	   var idcard = document.getElementById('idcard').value;
        var bank_no = document.getElementById('bank_no').value;
		 var b_code = document.getElementById('b_code').value;
       // var mobile = document.getElementById('mobile').value;
	
		/* var valid = document.getElementById('valid').value;
		  var cvn2 = document.getElementById('cvn2').value;*/
		
		  if (bank_no.length < 1)
        {
            alert('请填写信用卡！');
            return false;
        }
		
		//  else if (mobile.length < 1)
//        {
//            alert('请填写手机号码！');
//            return false;
//        }
		
	/*	  else if (valid.length < 1)
        {
            alert('请填写信用卡有效期！');
            return false;
        }
		  else if (cvn2.length < 1)
        {
            alert('请填写信用卡背末三位！');
            return false;
        }*/
		
	
		//else{
		//alert("sssssssssss");
	$('#myform').submit();
	//	}	
 });
</script>
