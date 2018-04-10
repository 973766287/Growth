<style>
    .r{color:red}
</style>
<link href="zzf/css/store_joinin_new.css" rel="stylesheet" type="text/css">
<link href="zzf/css/store_joinin.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="zzf/js/home_index.js"></script>
<script type="text/javascript" src="zzf/js/utils.js"></script>
<script type="text/javascript" src="zzf/js/region.js"></script>


  <div class="banner">
   
      
            
	<ul id="slides">
        
                 <?php foreach($rt['ad37'] as $_k=>$_v){?>
		<a href="<?php echo $_v['ad_url'];?>" target="_blank"><li><img src="<?php echo $_v['ad_img'];?>"></li></a>
		  <?php } ?>
                
                
	</ul>

    
</div>


<div class="header-extra">
       	 <div class="panel-heading">
            <div class="more">
                <div class="progress">
                    <div class="progress-wrap">
                        <div class="progress-item tobe">
                            <div class="number">1</div>
                            <div class="progress-desc">入驻须知</div>
                        </div>
                    </div>
                    <div class="progress-wrap">
                        <div class="progress-item ongoing">
                            <div class="number">2</div>
                            <div class="progress-desc">公司信息认证</div>
                        </div>
                    </div>
                    <div class="progress-wrap">
                        <div class="progress-item tobe">
                            <div class="number">3</div>
                            <div class="progress-desc">店铺信息认证</div>
                        </div>
                    </div>
                    <div class="progress-wrap">
                        <div class="progress-item tobe">
                            <div class="number"><i class="tick"></i></div>
                            <div class="progress-desc">等待审核</div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
      </div>
      
      
 <div class="store-joinin-apply">
  <div class="main">

    <div id="apply_company_info" class="apply-company-info">
      <div class="note"><i></i>以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1024K之内。</div>
      <form id="form_store_info" name="form_store_info" action="" method="post" enctype="multipart/form-data" onSubmit="return supplier_store_Reg()">
      <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20" align="left">开户银行信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150"><i>*</i>银行开户名：</th>
          <td><input name="bank_account_name" id="bank_account_name" type="text" class="w200" value="<? echo $supplier['bank_account_name'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>公司银行账号：</th>
          <td><input name="bank_account_number" id="bank_account_number" type="text" class="w200" value="<? echo $supplier['bank_account_number'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>开户银行支行名称：</th>
          <td><input name="bank_name" type="text" id="bank_name" class="w200" value="<? echo$supplier['bank_name'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>支行联行号：</th>
          <td><input name="bank_code" type="text" id="bank_code" class="w200"  value="<? echo $supplier['bank_code'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>开户银行许可证电子版：</th>
          <td><input name="bank_licence_electronic" id="bank_licence_electronic" type="hidden" value="<? echo $supplier['bank_licence_electronic'];?>">
           <iframe id="iframe_t3" name="iframe_t3" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=bank_licence_electronic&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
              <? if ($supplier['bank_licence_electronic']){?><br>
              <img src="<? echo $supplier['bank_licence_electronic'];?>" width=80 height=80 id='ble'>
			  <? }?>
            <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span></td>
        </tr>
        <tr>
          <th></th>
          <td><input id="is_settlement_account" name="is_settlement_account" type="checkbox" onclick="cin()">
            <label for="is_settlement_account">此账号为结算账号</label></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <div id="div_settlement">
      <table border="0" cellpadding="0" cellspacing="0" class="all">
        <thead>
          <tr>
            <th colspan="20" align="left">结算账号信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150"><i>*</i>银行开户名：</th>
            <td><input id="settlement_bank_account_name" name="settlement_bank_account_name" type="text" class="w200" value="<? echo $supplier['settlement_bank_account_name'];?>">
              <span></span></td>
          </tr>
          <tr>
            <th><i>*</i>公司银行账号：</th>
            <td><input id="settlement_bank_account_number" name="settlement_bank_account_number" type="text" class="w200" value="<? echo $supplier['settlement_bank_account_number'];?>">
              <span></span></td>
          </tr>
          <tr>
            <th><i>*</i>开户银行支行名称：</th>
            <td><input id="settlement_bank_name" name="settlement_bank_name" type="text" class="w200" value="<? echo $supplier['settlement_bank_name'];?>">
              <span></span></td>
          </tr>
          <tr>
            <th><i>*</i>支行联行号：</th>
            <td><input id="settlement_bank_code" name="settlement_bank_code" type="text" class="w200" value="<? echo $supplier['settlement_bank_code'];?>">
              <span></span></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="20">&nbsp;</td>
          </tr>
        </tfoot>
      </table>
    </div>
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <thead>
        <tr>
          <th colspan="20" align="left">税务登记证</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150"><i>*</i>税务登记证号：</th>
          <td><input name="tax_registration_certificate" type="text" class="w200" value="<? echo $supplier['tax_registration_certificate'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>纳税人识别号：</th>
          <td><input name="taxpayer_id" type="text" class="w200" value="<? echo $supplier['taxpayer_id'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>税务登记证号电子版：</th>
          <td><input name="tax_registration_certificate_electronic" id="tax_registration_certificate_electronic" type="hidden" value="<? echo $supplier['tax_registration_certificate_electronic']; ?>">
            <iframe id="iframe_t3" name="iframe_t3" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=tax_registration_certificate_electronic&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
	  <? if ($supplier['tax_registration_certificate_electronic']){?><br>
              <img src="<? echo $supplier['tax_registration_certificate_electronic'];?>" width=80 height=80 id='trce'>
			  <? }?>
            <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <input type="hidden" name="applynum" value="2">
      <input type="hidden" name="do" value="1">
    
      <div class="bottom"><input type="button" value="上一步" class="btn" onclick="top.location.href='user.php?act=apply_two&shownum=1'"><input type="submit" value="下一步" class="btn"></div>
      </form>
    </div>
  </div>
</div>
<script>
function cin(){
    if( document.getElementById('is_settlement_account').checked == true){
	$('#settlement_bank_account_name').val($('#bank_account_name').val());
	$('#settlement_bank_account_number').val($('#bank_account_number').val());
	$('#settlement_bank_name').val($('#bank_name').val());
	$('#settlement_bank_code').val($('#bank_code').val());
    }else{
	$('#settlement_bank_account_name').val('');
	$('#settlement_bank_account_number').val('');
	$('#settlement_bank_name').val('');
	$('#settlement_bank_code').val('');
    }
}

function ispic(filepath){
		
	var extStart=filepath.lastIndexOf('.');
	var ext=filepath.substring(extStart,filepath.length).toUpperCase();
	if(ext!='.BMP'&&ext!='.PNG'&&ext!='.GIF'&&ext!='.JPG'&&ext!='.JPEG'){
	  return false;
	}
	return true;
}
function supplier_store_Reg()
{

	var msg = '';
	var frm = document.forms['form_store_info'];
	var bank_account_name =  frm.elements['bank_account_name'] ? Utils.trim(frm.elements['bank_account_name'].value) : '';
	var bank_account_number =  frm.elements['bank_account_number'] ? Utils.trim(frm.elements['bank_account_number'].value) : '';
	var bank_name =  frm.elements['bank_name'] ? Utils.trim(frm.elements['bank_name'].value) : '';
	var bank_code =  frm.elements['bank_code'] ? Utils.trim(frm.elements['bank_code'].value) : '';
	var bank_licence_electronic = frm.elements['bank_licence_electronic'].value;
	var ble	    = document.getElementById("ble");

	var settlement_bank_account_name =  frm.elements['settlement_bank_account_name'] ? Utils.trim(frm.elements['settlement_bank_account_name'].value) : '';
	var settlement_bank_account_number =  frm.elements['settlement_bank_account_number'] ? Utils.trim(frm.elements['settlement_bank_account_number'].value) : '';
	var settlement_bank_name =  frm.elements['settlement_bank_name'] ? Utils.trim(frm.elements['settlement_bank_name'].value) : '';
	var settlement_bank_code =  frm.elements['settlement_bank_code'] ? Utils.trim(frm.elements['settlement_bank_code'].value) : '';

	var tax_registration_certificate =  frm.elements['tax_registration_certificate'] ? Utils.trim(frm.elements['tax_registration_certificate'].value) : '';
	var taxpayer_id =  frm.elements['taxpayer_id'] ? Utils.trim(frm.elements['taxpayer_id'].value) : '';
	var tax_registration_certificate_electronic = frm.elements['tax_registration_certificate_electronic'].value;
	var trce	    = document.getElementById("trce");

	
	
	if (bank_account_name.length == 0)
	{
		msg += "(开户)银行开户名不能为空！" + '\n';
	}
	if (bank_account_number.length == 0)
	{
		msg += "(开户)公司银行账号不能为空！" + '\n';
	}
	if (bank_name.length == 0)
	{
		msg += "(开户)开户银行支行不能为空！" + '\n';
	}
	if (bank_code.length == 0)
	{
		msg += "(开户)支行联行号不能为空！" + '\n';
	}
	if(ispic(bank_licence_electronic) == false && ble == null){
	   msg += "请上传开户银行许可证！" + '\n';
	}


	if (settlement_bank_account_name.length == 0)
	{
		msg += "(结算)银行开户名不能为空！" + '\n';
	}
	if (settlement_bank_account_number.length == 0)
	{
		msg += "(结算)公司银行账号不能为空！" + '\n';
	}
	if (settlement_bank_name.length == 0)
	{
		msg += "(结算)开户银行支行不能为空！" + '\n';
	}
	if (settlement_bank_code.length == 0)
	{
		msg += "(结算)支行联行号不能为空！" + '\n';
	}


	if (tax_registration_certificate.length == 0)
	{
		msg += "税务登记证号不能为空！" + '\n';
	}
	if (taxpayer_id.length == 0)
	{
		msg += "纳税人识别号不能为空！" + '\n';
	}
	if(ispic(tax_registration_certificate_electronic) == false && trce == null){
	   msg += "请上传税务登记证！" + '\n';
	}

	if (msg.length > 0)
	{
		alert(msg);
		return false;
	}
	else
	{
		  var fromAttr        = new Object();  //
	   var form      = document.forms['form_store_info']; //
	    if(form){
			fromAttr = getFromAttributes(form);
	   }else{
			alert("检查是否存在表单REGISTER");
			return false;
	   }
	   createwindow();
	   $.ajax({
		   type: "POST",
		   url: SITE_URL+"user.php?action=apply_two2_store_add",
		   data: "fromAttr=" + $.toJSON(fromAttr),
		   dataType: "json",
		   success: function(data){
                      
			   removewindow();
                        
		   		if(data.error==1){
				
				JqueryDialog.Open('系统提醒你','<br />'+data.message,250,50);
				
				}else{
                                 
				
                                        window.location.href=SITE_URL+'user.php?act=apply_three';
                                       // $('table .returnmes').html(data.message);
				
				}
		   }
		});
	}
}

</script>