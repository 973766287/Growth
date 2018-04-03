<link href="styles/general.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">

<script language="javascript" src="js/validator.js"></script>
<script language="javascript" src="../jss/transport.org.js"></script>
<script language="javascript" src="../jss/region.js"></script>
<script language="javascript" src="../jss/utils.js"></script>

<div class="main-div" style="padding:10px;background:#fff;">
<style type="text/css">
.store-joinin th{padding:10px;text-align:left;text-indent:10px;font-weight:bold;background:#F7F7F7;color:#1F84B0;margin-bottom:15px;}
.store-joinin td{padding:5px 1em}
</style>
  <!--如果公司类型不为空，显示公司申请的信息，如果为空显示个人申请的信息-->
  <? if ($supplier['company_type']){?>}
<table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">公司及联系人信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="label">公司名称：</td>
        <td><input type="text" name="company_name" value="<? echo $supplier['company_name'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">公司所在地：</td>
        <td>
		<select name="country" id="selCountries_0" onchange="region.changed(this, 1, 'selProvinces_0')" disabled>
		  <option value="1">中国</option>
		
		</select>
		<select name="province" id="selProvinces_0" onchange="region.changed(this, 2, 'selCities_0')" disabled>
		  <option value="<? echo $supplier['province'];?>"><? echo $supplier['province_name'];?></option>
		
		</select>
		<select name="city" id="selCities_0" onchange="region.changed(this, 3, 'selDistricts_0')" disabled>
		  <option value="<? echo $supplier['city'];?>"><? echo $supplier['city_name'];?></option>
		
		</select>
		<select name="district" id="selDistricts_0" {if !$district_list}style="display:none"{/if} disabled>
		  <option value="<? echo $supplier['district'];?>"><? echo $supplier['district_name'];?></option>
		 
		</select>
		</td>
      </tr>
      <tr>
      	<td class="label">公司详细地址：</td>
        <td><input type="text" name="address" value="<? echo $supplier['address'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">公司电话：</td>
        <td><input type="text" name="tel" value="<? echo $supplier['tel'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">公司规模：</td>
        <td><input type="text" name="guimo" value="<? echo $supplier['guimo'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">公司类型：</td>
        <td><input type="text" name="company_type" value="<? echo $supplier['company_type'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">联系人姓名：</td>
        <td><input type="text" name="contacts_name" value="<? echo $supplier['contacts_name'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">联系人电话：</td>
        <td><input type="text" name="contacts_phone" value="<? echo $supplier['contacts_phone'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">电子邮箱：</td>
        <td><input type="text" name="email" value="<? echo $supplier['email'];?>" style="float:left;" size="30" /></td>
      </tr>
    </tbody>
  </table>


  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">营业执照信息（副本）</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="label">营业执照号：</td>
        <td><input type="text" name="business_licence_number" value="<? echo $supplier['business_licence_number'];?>" style="float:left;" size="30" /></td></tr><tr>
      </tr>
      <tr>
        <td class="label">法定经营范围：</td>
        <td><input type="text" name="business_sphere" value="<? echo $supplier['business_sphere'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">营业执照号<br>电子版：</td>
        <td><? if ($supplier['zhizhao']){?><img src="../<? echo $supplier['zhizhao'];?>" width=50 height=50>&nbsp;&nbsp;<input type="button" onclick="window.open('../<? echo $supplier['zhizhao'];?>');" value="查看原图"><? }?></td>
      </tr>
    </tbody>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">组织机构代码证</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="label">组织机构代码：</td>
        <td><input type="text" name="organization_code" value="<? echo $supplier['organization_code'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">组织机构代码证<br>电子版：</td>
        <td><? if ($supplier['organization_code_electronic']){?><img src="../<? echo $supplier['organization_code_electronic'];?>" width=50 height=50>&nbsp;&nbsp;<input type="button" onclick="window.open('../<? echo $supplier['organization_code_electronic'];?>');" value="查看原图"><? }?></td>
      </tr>
    </tbody>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">一般纳税人证明</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="label">一般纳税人证明：</td>
        <td><? if ($supplier['general_taxpayer']){?><img src="../<? echo $supplier['general_taxpayer'];?>" width=50 height=50>&nbsp;&nbsp;<input type="button" onclick="window.open('../<? echo $supplier['general_taxpayer'];?>');" value="查看原图"><? }?></td>
      </tr>
    </tbody>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">税务登记证</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="label">税务登记证号：</td>
        <td><input type="text" name="tax_registration_certificate" value="<? echo $supplier['tax_registration_certificate'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">纳税人识别号：</td>
        <td><input type="text" name="taxpayer_id" value="<? echo $supplier['taxpayer_id'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">税务登记证号<br>电子版：</td>
        <td><? if ($supplier['tax_registration_certificate_electronic']){?><img src="../<? echo $supplier['tax_registration_certificate_electronic'];?>" width=50 height=50>&nbsp;&nbsp;<input type="button" onclick="window.open('../<? echo $supplier['tax_registration_certificate_electronic'];?>');" value="查看原图"><? }?></td>
      </tr>
    </tbody>
  </table>

  <form method="post" action="" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
   
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">开户银行信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="label">银行开户名：</td>
        <td><input type="text" name="bank_account_name" value="<? echo $supplier['bank_account_name'];?>" style="float:left;" size="30" /></td>
      </tr><tr>
        <td class="label">公司银行账号：</td>
        <td><input type="text" name="bank_account_number" value="<? echo $supplier['bank_account_number'];?>" style="float:left;" size="30" /></td></tr>
      <tr>
        <td class="label">开户银行支行名称：</td>
        <td><input type="text" name="bank_name" value="<? echo $supplier['bank_name'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">支行联行号：</td>
        <td><input type="text" name="bank_code" value="<? echo $supplier['bank_code'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">开户银行许可证<br>电子版：</td>
        <td><? if ($supplier['bank_licence_electronic']){?><img src="../<? echo $supplier['bank_licence_electronic'];?>" width=50 height=50>&nbsp;&nbsp;<input type="button" onclick="window.open('../<? echo $supplier['bank_licence_electronic'];?>');" value="查看原图"><? }?></td>
      </tr>
    </tbody>
    
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">结算账号信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="label">银行开户名：</td>
        <td><input type="text" name="settlement_bank_account_name" value="<? echo $supplier['settlement_bank_account_name'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">公司银行账号：</td>
        <td><input type="text" name="settlement_bank_account_number" value="<? echo $supplier['settlement_bank_account_number'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">开户银行支行名称：</td>
        <td><input type="text" name="settlement_bank_name" value="<? echo $supplier['settlement_bank_name'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">支行联行号：</td>
        <td><input type="text" name="settlement_bank_code" value="<? echo $supplier['settlement_bank_code'];?>" style="float:left;" size="30" /></td>
      </tr>
    </tbody>
    
  </table>
  
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="2">店铺经营信息</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="label">供货商名称：</td>
          <td><input type="text" name="supplier_name" value="<? echo $supplier['supplier_name'];?>" style="float:left;" size="30" /></td>
        </tr>
        <tr>
          <td class="label">店铺等级：</td>
          <td><input type="text" name="rank_name" value="<? echo $supplier['rank_name'];?>" style="float:left;" size="30" />
		  </td>
        </tr>
       <tr>
          <td class="label">店铺分类：</td>
          <td><input type="text" name="type_name" value="<? echo $supplier['type_name'];?>" style="float:left;" size="30" /></td>
        </tr>
		<tr>
			<td class="label">结算类型：</td>
			<td>
				<select name="supplier_rebate_paytime" size=1>
				<option value="0">请选择</option>
				<option value="1" <? if ($supplier['supplier_rebate_paytime'] == '1'){?>selected<? }?>>周</option>
				<option value="2" <? if ($supplier['supplier_rebate_paytime'] == '2'){?>selected<? }?>>月</option>
				<option value="3" <? if ($supplier['supplier_rebate_paytime'] == '3'){?>selected<? }?>>季度</option>
				<option value="4" <? if ($supplier['supplier_rebate_paytime'] == '4'){?>selected<? }?>>年</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="label">平台使用费：</td>
			<td><input type="text" name="system_fee" value="<? if ($supplier['system_fee'] >0.00){?><? echo $supplier['system_fee'];?><? }?>"></td>
		</tr>
		<tr>
		<td class="label">商家保证金：</td>
		<td><input type="text" name="supplier_bond" value="<? if ($supplier['supplier_bond']){?><? echo $supplier['supplier_bond'];?><? }?>"></td>
		</tr>
		<tr>
		<td class="label">分成百分比：</td>
		<td><input type="text" name="supplier_rebate" value="<? if ($supplier['supplier_rebate']){?><? echo $supplier['supplier_rebate']?><? }?>">%</td>
		</tr>
		<tr>
		<td class="label">审核意见：</td><td><textarea name="supplier_remark" rows=4 cols=50><? echo $supplier['supplier_remark'];?></textarea></td>
		</tr>
		<tr>
		<td class="label">审核状态：</td><td>
		<select name="status" size=1>
        <option value="0" <? if ($supplier['status'] == '0'){?>selected <? }?>>未审核</option>
        <option value="1" <? if ($supplier['status'] == '1'){?>selected<? }?>>审核通过</option>
        <option value="-1" <? if ($supplier['status'] == '-1'){?>selected<? }?>>审核不通过</option>
        </select>
        <span style="color:red"><br>1,店铺由<b>"审核通过"</b>变为<b>"审核不通过"</b>等同于关闭店铺，店铺相关商品下架，店铺街不再显示此店铺；<br>2,由<b>"审核不通过"</b>再次变为<b>"审核通过"</b>,相关商品需要手动上架，店铺街展示需要再次申请；<br>3,确定后，入驻商后台登陆密码将与前台登陆密码同步；</span></td>
		</tr>
      </tbody>
    </table>

	<table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
	  <tr>
		<td align="center">
		  <input type="submit" class="button" value="确定" />
            <input type="hidden" id="company" value="1">
		 
		</td>
	  </tr>
	</table>

  </form>
 <? }else{?>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">入驻商个人信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="label">店铺名称：</td>
        <td><input type="text" name="company_name" value="<? echo $supplier['company_name'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">联系地址：</td>
        <td>
		<select name="country" id="selCountries_0" onchange="region.changed(this, 1, 'selProvinces_0')" disabled>
		  <option value="1">中国</option>
		
		</select>
		<select name="province" id="selProvinces_0" onchange="region.changed(this, 2, 'selCities_0')" disabled>
		  <option value="<? echo $supplier['province'];?>"><? echo $supplier['province_name'];?></option>
		
		</select>
		<select name="city" id="selCities_0" onchange="region.changed(this, 3, 'selDistricts_0')" disabled>
		  <option value="<? echo $supplier['city'];?>"><? echo $supplier['city_name'];?></option>
		
		</select>
		<select name="district" id="selDistricts_0" {if !$district_list}style="display:none"{/if} disabled>
		  <option value="<? echo $supplier['district'];?>"><? echo $supplier['district_name'];?></option>
		 
		</select>
		</td>
      </tr>
      <tr>
      	<td class="label">详细地址：</td>
        <td><input type="text" name="address" value="<? echo $supplier['address'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">姓名：</td>
        <td><input type="text" name="contacts_name" value="<? echo $supplier['contacts_name'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">联系人电话：</td>
        <td><input type="text" name="contacts_phone" value="<? echo $supplier['contacts_phone'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">电子邮箱：</td>
        <td><input type="text" name="email" value="<? echo $supplier['email'];?>" style="float:left;" size="30" /></td>
      </tr>
      
      <tr>
        <td class="label">身份证号码：</td>
        <td><input type="text" name="id_card_no" value="<? echo $supplier['id_card_no'];?>" style="float:left;" size="30" /></td></tr><tr>
      </tr>
      <tr>
        <td class="label">手持身份证照片：</td>
        <td><? if ($supplier['handheld_idcard']){?><img src="../<? echo $supplier['handheld_idcard'];?>" width=50 height=50>&nbsp;&nbsp;<input type="button" onclick="window.open('../<? echo $supplier['handheld_idcard'];?>');" value="查看原图"><? }?></td>
      </tr>
      <tr>
        <td class="label">身份证正面：</td>
        <td><? if ($supplier['idcard_front']){?><img src="../<? echo $supplier['idcard_front'];?>" width=50 height=50>&nbsp;&nbsp;<input type="button" onclick="window.open('../<? echo $supplier['idcard_front'];?>');" value="查看原图"><? }?></td>
      </tr>
      <tr>
        <td class="label">身份证反面：</td>
        <td><? if ($supplier['handheld_idcard']){?><img src="../<? echo $supplier['idcard_reverse'];?>" width=50 height=50>&nbsp;&nbsp;<input type="button" onclick="window.open('../<? echo $supplier['idcard_reverse'];?>');" value="查看原图"><? }?></td>
      </tr>
    </tbody>
  </table>
    
    <form method="post" action="" name="theForm" enctype="multipart/form-data" >
	
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">开户银行信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="label">银行开户名：</td>
        <td><input type="text" name="bank_account_name" value="<? echo $supplier['bank_account_name'];?>" style="float:left;" size="30" /></td>
      </tr><tr>
        <td class="label">个人银行账号：</td>
        <td><input type="text" name="bank_account_number" value="<? echo $supplier['bank_account_number'];?>" style="float:left;" size="30" /></td></tr>
      <tr>
        <td class="label">开户银行支行名称：</td>
        <td><input type="text" name="bank_name" value="<? echo $supplier['bank_name'];?>" style="float:left;" size="30" /></td>
      </tr>
      <tr>
        <td class="label">支行联行号：</td>
        <td><input type="text" name="bank_code" value="<? echo $supplier['bank_code'];?>" style="float:left;" size="30" /></td>
      </tr>
    </tbody>
  </table>

    
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="2">店铺经营信息</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="label">供货商名称：</td>
          <td><input type="text" name="supplier_name" value="<? echo $supplier['supplier_name'];?>" style="float:left;" size="30" /></td>
        </tr>
        <tr>
          <td class="label">店铺等级：</td>
          <td><input type="text" name="rank_name" value="<? echo $supplier['rank_name'];?>" style="float:left;" size="30" />
		  </td>
        </tr>
       <tr>
          <td class="label">店铺分类：</td>
          <td><input type="text" name="type_name" value="<? echo $supplier['type_name'];?>" style="float:left;" size="30" /></td>
        </tr>
		<tr>
			<td class="label">结算类型：</td>
			<td>
				<select name="supplier_rebate_paytime" size=1>
				<option value="0">请选择</option>
				<option value="1" <? if ($supplier['supplier_rebate_paytime'] == '1'){?>selected<? }?>>周</option>
				<option value="2" <? if ($supplier['supplier_rebate_paytime'] == '2'){?>selected<? }?>>月</option>
				<option value="3" <? if ($supplier['supplier_rebate_paytime'] == '3'){?>selected<? }?>>季度</option>
				<option value="4" <? if ($supplier['supplier_rebate_paytime'] == '4'){?>selected<? }?>>年</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="label">平台使用费：</td>
			<td><input type="text" name="system_fee" value="<? if ($supplier['system_fee'] >0.00){?><? echo $supplier['system_fee'];?><? }?>"></td>
		</tr>
		<tr>
		<td class="label">商家保证金：</td>
		<td><input type="text" name="supplier_bond" value="<? if ($supplier['supplier_bond']){?><? echo $supplier['supplier_bond'];?><? }?>"></td>
		</tr>
		<tr>
		<td class="label">分成百分比：</td>
		<td><input type="text" name="supplier_rebate" value="<? if ($supplier['supplier_rebate']){?><? echo $supplier['supplier_rebate']?><? }?>">%</td>
		</tr>
		<tr>
		<td class="label">审核意见：</td><td><textarea name="supplier_remark" rows=4 cols=50><? echo $supplier['supplier_remark'];?></textarea></td>
		</tr>
		<tr>
		<td class="label">审核状态：</td><td>
		<select name="status" size=1>
        <option value="0" <? if ($supplier['status'] == '0'){?>selected <? }?>>未审核</option>
        <option value="1" <? if ($supplier['status'] == '1'){?>selected<? }?>>审核通过</option>
        <option value="-1" <? if ($supplier['status'] == '-1'){?>selected<? }?>>审核不通过</option>
        </select>
        <span style="color:red"><br>1,店铺由<b>"审核通过"</b>变为<b>"审核不通过"</b>等同于关闭店铺，店铺相关商品下架，店铺街不再显示此店铺；<br>2,由<b>"审核不通过"</b>再次变为<b>"审核通过"</b>,相关商品需要手动上架，店铺街展示需要再次申请；<br>3,确定后，入驻商后台登陆密码将与前台登陆密码同步；</span></td>
		</tr>
      </tbody>
    </table>

	<table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
	  <tr>
		<td align="center">
		  <input type="submit" class="button" value="确定" />
		   <input type="hidden" id="person" value="1">
		</td>
	  </tr>
	</table>

  </form>

 <? }?>
</div>


<script language="JavaScript">
function validate()
{
	var theForm=document.forms['theForm'];
    validator = new Validator("theForm");
	if (theForm.elements['status'].value == '1')
	{
		if(document.getElementById('company')){
			validator.required("settlement_bank_account_name",  "填写了银行开户名才能审核通过！");
			validator.required("settlement_bank_account_number",  "填写了公司银行账号才能审核通过！");
			validator.required("settlement_bank_name",  "填写了开户银行支行名称才能审核通过！");
			validator.required("settlement_bank_code",  "填写了支行联行号才能审核通过！");
			
			validator.required("system_fee",  "填写了平台使用费才能审核通过！");
			validator.required("supplier_bond",  "填写了商家保证金才能审核通过！");
			validator.required("supplier_rebate",  "填写了分成百分比才能审核通过！");
		}
		if(document.getElementById('person')){
			validator.required("bank_account_name",  "填写了银行开户名才能审核通过！");
			validator.required("bank_account_number",  "填写了个人银行账号才能审核通过！");
			validator.required("bank_name",  "填写了开户银行支行名称才能审核通过！");
			validator.required("bank_code",  "填写了支行联行号才能审核通过！");
			
			validator.required("system_fee",  "填写了平台使用费才能审核通过！");
			validator.required("supplier_bond",  "填写了商家保证金才能审核通过！");
			validator.required("supplier_rebate",  "填写了分成百分比才能审核通过！");
		}	
	}
    
    return validator.passed();
}

</script>

