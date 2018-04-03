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
                        <div class="progress-item passed">
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
  	<div>
    <script>
	function radioShow()
	{	
		var apply_type = document.getElementsByName('apply_type');
		var showdiv = document.getElementsByClassName("apply-company-info");
		for(i=0;i<showdiv.length;i++)
		{
			if(apply_type[i].checked)
			{
				showdiv[i].style.display = "block";
			}
			else
			{
				showdiv[i].style.display = "none";
			}
		}
				
	}
	</script>
    
    <table border="0" cellpadding="0" cellspacing="0" class="all">
          <thead>
            <tr>
              <th colspan="2" align="left">请选择您的开店身份</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
              <label style="cursor:pointer;"><input type="radio" name="apply_type" <? if (!$supplier['guimo']){?>checked<? }?> onClick="radioShow()"> 个人</label>　
              <label style="cursor:pointer;"><input type="radio" name="apply_type" <? if ($supplier['guimo']){?>checked<? }?> onClick="radioShow()"> 企业</label>
              </td>
            </tr>
          </tbody>
        </table>
    </div>
    <div id="apply_person_info" class="apply-company-info" <? if ($supplier['guimo']){?>style="display:none;"<? }?>>
          <div class="note"><i></i>以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1024K之内。</div>
      <form id="form_person_info" name="form_person_info" action="" method="post" enctype="multipart/form-data" onSubmit="return supplier_person_Reg()">
        <table border="0" cellpadding="0" cellspacing="0" class="all">
          <thead>
            <tr>
              <th colspan="2" align="left">请按照提示填写本人真实的资料</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th><i>*</i>店铺名称：</th>
              <td>
	      		<input name="company_name"  id="company_name" type="text" value="<? echo $supplier['company_name'];?>" class="w200"  />
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>联系地址：</th>
              <input name="country" id="country" value="1" type="hidden"/>
               <td>	
			   <select name="province" id="select_province" onchange="ger_ress('2',this,'select_city')">
<option value="0">选择省</option>
<?php 
if(!empty($resslist)){
foreach($resslist as $row){
?>
<option value="<?php echo $row['region_id'];?>" <?php echo $supplier['province']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
<?php } } ?>													
</select>

<select name="city" id="select_city" onchange="ger_ress('3',this,'select_district')">
<option value="0">选择城市</option>
<?php
if(!empty($city)){
foreach($city as $row){
?>
<option value="<?php echo $row['region_id'];?>" <?php echo $supplier['city']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
<?php } } ?>	
</select>

<select <?php echo !isset($supplier['district'])? 'style="display: none;"':"";?> name="district" id="select_district">
<option value="0">选择区</option>	
<?php 
if(!empty($district)){
foreach($district as $row){
?>
<option value="<?php echo $row['region_id'];?>" <?php echo $supplier['district']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
<?php } } ?>													
</select>

</span>


               
               </td>
            </tr>
            <tr>
              <th><i>*</i>详细地址：</th>
              <td>
	      <input name="address" class="w200" type="text"   value="<? echo $supplier['address'];?>" />
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>姓名：</th>
              <td><input name="contacts_name" type="text" class="w100" value="<? echo $supplier['contacts_name'];?>">
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>联系人电话：</th>
              <td><input name="contacts_phone" type="text" class="w100" value="<? echo $supplier['contacts_phone'];?>">
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>电子邮箱：</th>
              <td><input type="text" name="email" size=45 value="<? echo $supplier['email'];?>" class="w200"  />
                <span></span></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="20">&nbsp;</td>
            </tr>
          </tfoot>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="all">
          <thead>
            <tr>
              <th colspan="20" align="left">身份证信息</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th><i>*</i>身份证号码：</th>
              <td><input name="id_card_no" type="text" class="w200" value="<? echo $supplier['id_card_no'];?>">
                <span></span></td>
            </tr>

            <tr>
              <th><i>*</i>手持身份证照片：</th>
              <td><input name="handheld_idcard"  id="handheld_idcard" type="hidden" value="<? echo $supplier['handheld_idcard'];?>">
                <iframe id="iframe_t3" name="iframe_t3" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=handheld_idcard&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
                  <? if ($supplier['handheld_idcard']){?><br>
                  <img src="<? echo $supplier['handheld_idcard'];?>" width=80 height=80 id='handheld_idcard_id'>
                 <? }?>
                  <br />
                  <span id="id-hand-s"></span>
              </td>
            </tr>
            
            <tr>
              <th><i>*</i>身份证正面：</th>
              <td><input name="idcard_front" id="idcard_front" type="hidden" value="<? echo $supplier['idcard_front'];?>">
                <iframe id="iframe_t3" name="iframe_t3" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=idcard_front&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
          <? if ($supplier['idcard_front']){?><br>
                  <img src="<? echo $supplier['idcard_front'];?>" width=80 height=80 id='idcard_front_id'>
                  <? }?>
               <br />
               <span id="id-front-s"></span>
              </td>
              
            </tr>
            
            <tr>
              <th><i>*</i>身份证反面：</th>
              <td><input name="idcard_reverse"  id="idcard_reverse" type="hidden" value="<? echo $supplier['idcard_reverse'];?>">
                <iframe id="iframe_t3" name="iframe_t3" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=idcard_reverse&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
          <? if ($supplier['idcard_reverse']){?><br>
                  <img src="<? echo $supplier['idcard_reverse'];?>" width=80 height=80 id='idcard_reverse_id'>
                  <? }?>
               <br />
               <span id="id-back-s"></span>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="20">&nbsp;</td>
            </tr>
          </tfoot>
    	</table>

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
          <th><i>*</i>个人银行账号：</th>
          <td><input name="bank_account_number" id="bank_account_number" type="text" class="w200" value="<? echo $supplier['bank_account_number'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>开户银行支行名称：</th>
          <td><input name="bank_name" type="text" id="bank_name" class="w200" value="<? echo $supplier['bank_name'];?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i>支行联行号：</th>
          <td><input name="bank_code" type="text" id="bank_code" class="w200"  value="<? echo $supplier['bank_code'];?>">
            <span></span></td>
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
      <input type="hidden" name="person" value="1">
      <div class="bottom">
      <input type="button" value="上一步" class="btn" onclick="top.location.href='apply.php?shownum=0'"><input type="submit" value="下一步" class="btn" ></div>
      </form>
    </div>
    

    <div id="apply_company_info" class="apply-company-info"  <? if ($supplier['guimo']){?>style="display:block;"<? }else{?>style="display:none"<? }?>>
      <div class="note"><i></i>以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1024K之内。</div>
      <form id="form_company_info" name="form_company_info" action="" method="post" enctype="multipart/form-data" onSubmit="return supplier_company_Reg()">
        <table border="0" cellpadding="0" cellspacing="0" class="all">
          <thead>
            <tr>
              <th colspan="2" align="left">公司及联系人信息</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th><i>*</i>公司名称：</th>
              <td>
	      <input name="company_name" type="text" value="<? echo $supplier['company_name'];?>" class="w200"  />
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>公司所在地：</th>
                  <input name="country" id="country" value="1" type="hidden"/>
              <td>	
			   <select name="province" id="select_province" onchange="ger_ress('2',this,'select_city')">
<option value="0">选择省</option>
<?php 
if(!empty($resslist)){
foreach($resslist as $row){
?>
<option value="<?php echo $row['region_id'];?>" <?php echo $supplier['province']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
<?php } } ?>													
</select>

<select name="city" id="select_city" onchange="ger_ress('3',this,'select_district')">
<option value="0">选择城市</option>
<?php
if(!empty($city)){
foreach($city as $row){
?>
<option value="<?php echo $row['region_id'];?>" <?php echo $supplier['city']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
<?php } } ?>	
</select>

<select <?php echo !isset($supplier['district'])? 'style="display: none;"':"";?> name="district" id="select_district">
<option value="0">选择区</option>	
<?php 
if(!empty($district)){
foreach($district as $row){
?>
<option value="<?php echo $row['region_id'];?>" <?php echo $supplier['district']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
<?php } } ?>													
</select>

</span>

              </td>
            </tr>
            <tr>
              <th><i>*</i>公司详细地址：</th>
              <td>
	      <input name="address" class="w200" type="text"   value="<? echo $supplier['address'];?>" />
                <span></span></td>
            </tr>
	    <tr>
              <th><i>*</i>公司类型：</th>
              <td><select name="company_type" class="w200">
                <option value="">请选择</option>
                 <option value="私营" >私营</option>
                 <option value="个体户" >个体户</option>
                 <option value="外企" >外企</option>
                 <option value="中外合资" >中外合资</option>
		
              </select> <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>公司电话：</th>
              <td><input type="text" name="tel"  value="<? echo $supplier['tel'];?>" class="w100" />
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>公司规模：</th>
              <td><input type="text" class="w200" name="guimo" size=45 value="<? if ($supplier['guimo']){?><? echo $supplier['guimo'];?><? }else{?>员工总数：XX人；注册资金：XX万元<? }?>"   /> <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>联系人姓名：</th>
              <td><input name="contacts_name" type="text" class="w100" value="<? echo $supplier['contacts_name'];?>">
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>联系人电话：</th>
              <td><input name="contacts_phone" type="text" class="w100" value="<? echo $supplier['contacts_phone'];?>">
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>电子邮箱：</th>
              <td><input type="text" name="email" size=45 value="<? echo $supplier['email'];?>" class="w200"  />
                <span></span></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="20">&nbsp;</td>
            </tr>
          </tfoot>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="all">
          <thead>
            <tr>
              <th colspan="20" align="left">营业执照信息（副本）</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th><i>*</i>营业执照号：</th>
              <td><input name="business_licence_number" type="text" class="w200" value="<? echo $supplier['business_licence_number'];?>">
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>法定经营范围：</th>
              <td><textarea name="business_sphere" rows="3" class="w200" ><? echo $supplier['business_sphere'];?></textarea>
                <span></span></td>
            </tr>

            <tr>
              <th><i>*</i>营业执照号电子版：</th>
              <td>
	      <input type="hidden" name="zhizhao" id="zhizhao" class="w200"  value="<? echo $supplier['zhizhao'];?>" />
          
           <iframe id="iframe_t3" name="iframe_t3" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=zhizhao&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
              <? if ($supplier['zhizhao']){?><br>
              <img src="<? echo $supplier['zhizhao'];?>" width=80 height=80 id='zz'><? }?>
                <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="20">&nbsp;</td>
            </tr>
          </tfoot>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="all">
          <thead>
            <tr>
              <th colspan="20" align="left">组织机构代码证</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th><i>*</i>组织机构代码：</th>
              <td><input name="organization_code" type="text" class="w200" value="<? echo $supplier['organization_code'];?>">
                <span></span></td>
            </tr>
            <tr>
              <th><i>*</i>组织机构代码证电子版：</th>
              <td> <input type="hidden" name="organization_code_electronic" id="organization_code_electronic" class="w200" value="<? echo $supplier['organization_code_electronic'];?>" />
			   <iframe id="iframe_t3" name="iframe_t3" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=organization_code_electronic&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
			  <? if ($supplier['organization_code_electronic']){?><br>
              <img src="<? echo $supplier['organization_code_electronic'];?>" width=80 height=80 id='oce'><? }?>
                <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="20">&nbsp;</td>
            </tr>
          </tfoot>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="all">
          <thead>
            <tr>
              <th colspan="20"  align="left">一般纳税人证明<em>注：所属企业具有一般纳税人证明时，此项为必填。</em></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th class="w150">一般纳税人证明：</th>
              <td><input type="hidden" name="general_taxpayer" id="general_taxpayer" class="w200" value="<? echo $supplier['general_taxpayer'];?>" />
			   <iframe id="iframe_t3" name="iframe_t3" border="0" src="<?php echo SITE_URL; ?>admin/upload.php?action=&ty=general_taxpayer&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
			  <? if ($supplier['general_taxpayer']){?><br>
              <img src="<? echo $supplier['general_taxpayer'];?>" width=80 height=80 id='gt'><? }?>
                <span class="block">请确保图片清晰，文字可辨并有清晰的红色公章。</span></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="20">&nbsp;</td>
            </tr>
          </tfoot>
        </table>
	<input type="hidden" name="applynum" value="1">
      <input type="hidden" name="do" value="1">
      <input type="hidden" name="company" value="1">
      <div class="bottom">
      <input type="button" value="上一步" class="btn" onclick="top.location.href='apply.php?shownum=1'"><input type="submit" value="下一步" class="btn" ></div>
      </form>
    </div>
    
    
  </div>
</div>

<script>
function ispic(filepath){
		
	var extStart=filepath.lastIndexOf('.');
	var ext=filepath.substring(extStart,filepath.length).toUpperCase();
	if(ext!='.BMP'&&ext!='.PNG'&&ext!='.GIF'&&ext!='.JPG'&&ext!='.JPEG'){
	  return false;
	}
	return true;
}
function supplier_company_Reg()
{

	var msg = '';
	var frm = document.forms['form_company_info'];
	var company_name =  frm.elements['company_name'] ? Utils.trim(frm.elements['company_name'].value) : '';
	var country =  frm.elements['country'] ? Utils.trim(frm.elements['country'].value) : '0';
	var province =  frm.elements['province'] ? Utils.trim(frm.elements['province'].value) : '0';
	var city =  frm.elements['city'] ? Utils.trim(frm.elements['city'].value) : '0';
	var district =  frm.elements['district'] ? Utils.trim(frm.elements['district'].value) : '0';
	var address =  frm.elements['address'] ? Utils.trim(frm.elements['address'].value) : '';
	var tel =  frm.elements['tel'] ? Utils.trim(frm.elements['tel'].value) : '';
	var guimo =  frm.elements['guimo'] ? Utils.trim(frm.elements['guimo'].value) : '';
	var company_type =  frm.elements['company_type'] ? Utils.trim(frm.elements['company_type'].value) : '';

	var contacts_name =  frm.elements['contacts_name'] ? Utils.trim(frm.elements['contacts_name'].value) : '';
	var contacts_phone =  frm.elements['contacts_phone'] ? Utils.trim(frm.elements['contacts_phone'].value) : '';

	var email = frm.elements['email'].value;
	var business_licence_number =  frm.elements['business_licence_number'] ? Utils.trim(frm.elements['business_licence_number'].value) : '';
	var business_sphere =  frm.elements['business_sphere'] ? Utils.trim(frm.elements['business_sphere'].value) : '';
	var zhizhao = frm.elements['zhizhao'].value;
	var zz	    = document.getElementById("zz");
	var organization_code =  frm.elements['organization_code'] ? Utils.trim(frm.elements['organization_code'].value) : '';
	var organization_code_electronic = frm.elements['organization_code_electronic'].value;
	var oce	    = document.getElementById("oce");
	//var general_taxpayer = frm.elements['general_taxpayer'].value;
	var gt	    = document.getElementById("gt");

	
	
	if (company_name.length == 0)
	{
		msg += "公司名称不能为空！" + '\n';
	}

	if (country == '0' || province=='0' || city=='0' || district=='0')
	{
		msg += "公司所在地不完整！" + '\n';
	}
	
	if (address.length == 0)
	{
		msg += "公司详细地址不能为空！" + '\n';
	}
	if (tel.length == 0)
	{
		msg += "公司电话不能为空！" + '\n';
	}
	if (tel.length > 0){
		var patrn=/^((\+?[0-9]{2,4}[0-9]{3,4})|([0-9]{3,4}))?([0-9]{7,8})(\-[0-9]+)?$/;
		if (!patrn.exec(tel)){
	　　     msg += "公司电话不正确！" + '\n';
	　　   }
	}
	if (guimo.length == 0)
	{
		msg += "企业规模不能为空！" + '\n';
	}
	if (email.length == 0)
	{
		msg += "电子邮箱不能为空！" + '\n';
	}
	else
	{
		if ( ! (Utils.isEmail(email)))
		{
			msg += "电子邮箱格式错误！" + '\n';
		}
	}
	if (company_type.length == 0)
	{
		msg += "企业类型不能为空！" + '\n';
	}

	if (contacts_name.length == 0)
	{
		msg += "联系人姓名不能为空！" + '\n';
	}

	if (contacts_phone.length == 0)
	{
		msg += "联系人电话不能为空！" + '\n';
	}
	if (contacts_phone.length > 0){
		var patrn=/^1[3|4|5|7|8][0-9]\d{8}$/;
		if (!patrn.exec(contacts_phone)){
	　　     msg += "联系人电话不正确！" + '\n';
	　　   }
	}

	if (business_licence_number.length == 0)
	{
		msg += "营业执照号不能为空！" + '\n';
	}

	if (business_sphere.length == 0)
	{
		msg += "法定经营范围不能为空！" + '\n';
	}

	if(ispic(zhizhao) == false && zz == null){
	   msg += "请上传营业执照！" + '\n';
	}

	if (organization_code.length == 0)
	{
		msg += "组织机构代码不能为空！" + '\n';
	}

	if(ispic(organization_code_electronic) == false && oce == null){
	   msg += "组织机构代码证！" + '\n';
	}

	/*if(ispic(general_taxpayer) == false && gt == null){
	   msg += "组织机构代码证！" + '\n';
	}*/

	if (msg.length > 0)
	{
		alert(msg);
		return false;
	}
	else
	{
		
	
	 var fromAttr        = new Object();  //
	   var form      = document.forms['form_company_info']; //
	    if(form){
			fromAttr = getFromAttributes(form);
	   }else{
			alert("检查是否存在表单REGISTER");
			return false;
	   }
	   createwindow();
	   $.ajax({
		   type: "POST",
		   url: SITE_URL+"user.php?action=apply_two2_add",
		   data: "fromAttr=" + $.toJSON(fromAttr),
		   dataType: "json",
		   success: function(data){
                      
			   removewindow();
                        
		   		if(data.error==1){
				
				JqueryDialog.Open('系统提醒你','<br />'+data.message,250,50);
				
				}else{
                                 
				
                                        window.location.href=SITE_URL+'user.php?act=apply_two2';
                                       // $('table .returnmes').html(data.message);
				
				}
		   }
		});
		
	}
		
}
function supplier_person_Reg()
{
	var msg = '';
	var frm = document.forms['form_person_info'];
	var company_name =  frm.elements['company_name'] ? Utils.trim(frm.elements['company_name'].value) : '';
	var country =  frm.elements['country'] ? Utils.trim(frm.elements['country'].value) : '0';
	var province =  frm.elements['province'] ? Utils.trim(frm.elements['province'].value) : '0';
	var city =  frm.elements['city'] ? Utils.trim(frm.elements['city'].value) : '0';
	var district =  frm.elements['district'] ? Utils.trim(frm.elements['district'].value) : '0';
	var address =  frm.elements['address'] ? Utils.trim(frm.elements['address'].value) : '';

	var contacts_name =  frm.elements['contacts_name'] ? Utils.trim(frm.elements['contacts_name'].value) : '';
	var contacts_phone =  frm.elements['contacts_phone'] ? Utils.trim(frm.elements['contacts_phone'].value) : '';
	var email = frm.elements['email'].value;
	
	var id_card_no =  frm.elements['id_card_no'] ? Utils.trim(frm.elements['id_card_no'].value) : '';
	
	var handheld_idcard = frm.elements['handheld_idcard'].value;
	var handheld_idcard_id	    = document.getElementById("handheld_idcard_id");

	var idcard_front = frm.elements['idcard_front'].value;
	var idcard_front_id	    = document.getElementById("idcard_front_id");
	
	var idcard_reverse = frm.elements['idcard_reverse'].value;
	var idcard_reverse_id	    = document.getElementById("idcard_reverse_id");

	
	var bank_account_name =  frm.elements['bank_account_name'] ? Utils.trim(frm.elements['bank_account_name'].value) : '';
	var bank_account_number =  frm.elements['bank_account_number'] ? Utils.trim(frm.elements['bank_account_number'].value) : '';
	var bank_name =  frm.elements['bank_name'] ? Utils.trim(frm.elements['bank_name'].value) : '';
	var bank_code =  frm.elements['bank_code'] ? Utils.trim(frm.elements['bank_code'].value) : '';
	
	if (company_name.length == 0)
	{
		msg += "店铺名称不能为空！" + '\n';
	}

	if (country == '0' || province=='0' || city=='0' || district=='0')
	{
		msg += "联系地址不完整！" + '\n';
	}
	
	if (address.length == 0)
	{
		msg += "详细地址不能为空！" + '\n';
	}
	
	if (contacts_name.length == 0)
	{
		msg += "姓名不能为空！" + '\n';
	}

	if (contacts_phone.length == 0)
	{
		msg += "联系人电话不能为空！" + '\n';
	}
	if (contacts_phone.length > 0){
		var patrn=/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/;
		if (!patrn.exec(contacts_phone)){
	　　     msg += "联系人电话不正确！" + '\n';
	　　   }
	}
	
	if (email.length == 0)
	{
		msg += "电子邮箱不能为空！" + '\n';
	}
	else
	{
		if ( ! (Utils.isEmail(email)))
		{
			msg += "电子邮箱格式错误！" + '\n';
		}
	}	

	if (id_card_no.length == 0)
	{
		msg += "身份证号码不能为空！" + '\n';
	}
	if (id_card_no.length > 0){
		var patrn=/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
		if (!patrn.exec(id_card_no)){
	　　     msg += "身份证格式错误！" + '\n';
	　　   }
	}

	if(ispic(handheld_idcard) == false && handheld_idcard_id == null){
	   msg += "手持身份证照片！" + '\n';
	}
	if(ispic(idcard_front) == false && idcard_front_id == null){
	   msg += "身份证正面照片！" + '\n';
	}
	if(ispic(idcard_reverse) == false && idcard_reverse_id == null){
	   msg += "身份证反面照片！" + '\n';
	}

	if (bank_account_name.length == 0)
	{
		msg += "(开户)银行开户名不能为空！" + '\n';
	}
	if (bank_account_number.length == 0)
	{
		msg += "(开户)个人银行账号不能为空！" + '\n';
	}
	if (bank_name.length == 0)
	{
		msg += "(开户)开户银行支行不能为空！" + '\n';
	}
	if (bank_code.length == 0)
	{
		msg += "(开户)支行联行号不能为空！" + '\n';
	}

	if (msg.length > 0)
	{
		alert(msg);
		return false;
	}
	else
	{
		
	
	
	
	 
	  var fromAttr        = new Object();  //
	   var form      = document.forms['form_person_info']; //
	    if(form){
			fromAttr = getFromAttributes(form);
	   }else{
			alert("检查是否存在表单REGISTER");
			return false;
	   }
	   createwindow();
	   $.ajax({
		   type: "POST",
		   url: SITE_URL+"user.php?action=apply_two1_add",
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
<script type="text/javascript">
function ger_ress(type,obj,seobj){
	parent_id = $(obj).val();
	if(parent_id=="" || typeof(parent_id)=='undefined'){ return false; }
	$.post(SITE_URL+'user.php',{action:'get_ress',type:type,parent_id:parent_id},function(data){
		if(data!=""){
			$(obj).parent().find('#'+seobj).html(data);
			
			if(type==5){ //村
				
				$(obj).parent().find('#'+seobj).show();
				$(obj).parent().find('#select_peisong').hide();
			//	$(obj).parent().find('#select_peisong').show();
			//	$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
			//	$(obj).parent().find('#select_village').show();
				
			//	$(obj).parent().find('#'+seobj).show();
		//	  	$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
		//		$(obj).parent().find('#select_peisong').hide();
			}else if(type==4){ //城镇
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				$(obj).parent().find('#select_peisong').hide();
				$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
				$(obj).parent().find('#select_town').show();
				//$(obj).parent().find('#select_town').html("");
			}else if(type==3){ //区
				$(obj).parent().find('#select_peisong').hide();
				$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
				
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				
				$(obj).parent().find('#select_town').hide();
				$(obj).parent().find('#select_town').html('<option value="0" >选择城镇</option>');
				
				$(obj).parent().find('#select_district').show();
				//$(obj).parent().find('#select_district').html("");
				
			}else if(type==2){ //市
				$(obj).parent().find('#select_peisong').hide();
				$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
				
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				
				$(obj).parent().find('#select_town').hide();
				$(obj).parent().find('#select_town').html('<option value="0" >选择城镇</option>');
				
				$(obj).parent().find('#select_district').hide();
				$(obj).parent().find('#select_district').html('<option value="0" >选择区</option>');
				
				//$(obj).parent().find('#select_city').hide();
				//$(obj).parent().find('#select_city').html("");
			}

		}else{
			alert(data);
		}
	});
}
//获取配送店

function get_peisong(obj,seobj){
//	village_id = $(obj).val();
	village_id = $(obj).parent().find('select[name="village"]').val();
	town_id = $(obj).parent().find('select[name="town"]').val();
	district_id = $(obj).parent().find('select[name="district"]').val();

	if(village_id=="" || typeof(village_id)=='undefined'){ return false; }
	$.post(SITE_URL+'user.php',{action:'get_peisong',village_id:village_id,town_id:town_id,district_id:district_id,type:'ajax'},function(data){
		if(data!=""){ 
			$(obj).parent().find('#'+seobj).html(data);
			
			$(obj).parent().find('#'+seobj).show();
			
		}else{
		/************* look 添加开始   *********************************/	
			$(obj).parent().find('#'+seobj).html('<option value="0" >此处暂无配送店</option>');
			
			$(obj).parent().find('#'+seobj).show();
		/************* look 添加结束   *********************************/		
		//	alert(data);  原代码
		}
	});
}
</script>