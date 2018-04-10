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
                        <div class="progress-item tobe">
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
                        <div class="progress-item ongoing">
                            <div class="number"><i class="tick"></i></div>
                            <div class="progress-desc">等待审核</div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
      </div>
      
      <? if($supplier['status'] == 0){?>
      <div class="store-joinin-apply">
  <div class="main">
	<div class="explain1" style="text-align: center;"><i></i>入驻申请已经提交，请等待管理员审核</div>
    </div></div>
      
      <? }else{?>
 <div class="store-joinin-apply">
  <div class="main">
	<div class="explain1"><i></i>您好，审核通过，您可以通过<a href="supplier/" target="_blank" style="color:#1381c0;font-size:16px;">供货商管理中心</a>来登录供货商后台！</div>
    <div class="joinin-pay"> 
	<? if ($supplier['guimo']){?>
<table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="6">公司及联系人信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">公司名称：</th>
        <td colspan="5"><? echo $supplier['company_name'];?></td>
      </tr>
      <tr>
        <th class="w150">公司所在地：</th>
        <td colspan="5">
		
		<select name="province" id="selProvinces_0" onchange="region.changed(this, 2, 'selCities_0')" disabled>
		  <option value="0">{$lang.please_select}{$name_of_region[1]}</option>
		  <? foreach ($resslist as $province){?>
		  <option value="<? echo $province['region_id'];?>" <? if ($supplier['province'] == $province['region_id']){?>selected<? }?>><? echo $province['region_name'];?></option>
		<? }?>
		</select>
		<select name="city" id="selCities_0" onchange="region.changed(this, 3, 'selDistricts_0')" disabled>
		  <option value="0">{$lang.please_select}{$name_of_region[2]}</option>
		  <? foreach  ($city as $city){?>
		  <option value="<? echo $city['region_id']?>" <? if($supplier['city'] == $city['region_id']){?>selected<? }?>><? echo $city['region_name'];?></option>
		  <? }?>
		</select>
		<select name="district" id="selDistricts_0" {if !$district_list}style="display:none"{/if} disabled>
		  <option value="0">{$lang.please_select}{$name_of_region[3]}</option>
		  <? foreach  ($district as $district){?>
		  <option value="<? echo $district['region_id'];?>" <? if($supplier['district'] == $district['region_id']){?>selected<? }?>><? echo $district['region_name'];?></option>
		  <? }?>
		</select>
		</td>
      </tr>
      <tr>
        <th class="w150">公司详细地址：</th>
        <td colspan="5"><? echo $supplier['address'];?></td>
      </tr>
      <tr>
        <th class="w150">公司电话：</th>
        <td><? echo $supplier['tel'];?></td>
        <th class="w150">公司规模：</th>
        <td><? echo $supplier['guimo'];?></td>
        <th class="w150">公司类型：</th>
        <td><? echo $supplier['company_type'];?></td>
      </tr>
      <tr>
        <th class="w150">联系人姓名：</th>
        <td><? echo $supplier['contacts_name'];?></td>
        <th class="w150">联系人电话：</th>
        <td><? echo $supplier['contacts_phone'];?></td>
        <th class="w150">电子邮箱：</th>
        <td><? echo $supplier['email'];?></td>
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
        <th class="w150">营业执照号：</th>
        <td><? echo $supplier['business_licence_number'];?></td>
	</tr>
	<tr></tr>
      <tr>
        <th class="w150">法定经营范围：</th>
        <td colspan="20"><? echo $supplier['business_sphere'];?></td>
      </tr>
      <tr>
        <th class="w150">营业执照号<br>
	电子版：</th>
        <td colspan="20"><? if($supplier['zhizhao']){?><img src="<? echo $supplier['zhizhao'];?>" width=50 height=50>&nbsp;&nbsp;
	<input type="button" onclick="window.open('supplier/<? echo $supplier['zhizhao'];?>');" value="查看原图">
	<? }?></td>
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
        <th class="w150">组织机构代码：</th>
        <td><? echo $supplier['organization_code'];?></td>
      </tr>
      <tr>
        <th class="w150">组织机构代码证<br>
	电子版：</th>
        <td><? if($supplier['organization_code_electronic']){?><img src="<? echo $supplier['organization_code_electronic'];?>" width=50 height=50>&nbsp;&nbsp;
	<input type="button" onclick="window.open('<? echo $supplier['organization_code_electronic'];?>');" value="查看原图">
	<? }?></td>
      </tr>
    </tbody>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">一般纳税人证明</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">一般纳税人证明：</th>
        <td><? if($supplier['general_taxpayer']){?><img src="<? echo $supplier['general_taxpayer'];?>" width=50 height=50>&nbsp;&nbsp;
	<input type="button" onclick="window.open('<? echo $supplier['general_taxpayer'];?>');" value="查看原图">
	<? }?></td>
      </tr>
    </tbody>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">开户银行信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">银行开户名：</th>
        <td><? echo $supplier['bank_account_name'];?></td>
      </tr>
      <tr>
        <th class="w150">公司银行账号：</th>
        <td><? echo $supplier['bank_account_number'];?></td>
	</tr>
      <tr>
        <th class="w150">开户银行支行名称：</th>
        <td><? echo $supplier['bank_name'];?></td>
      </tr>
      <tr>
        <th class="w150">支行联行号：</th>
        <td><? echo $supplier['bank_code'];?></td>
      </tr>
      <tr>
        <th class="w150">开户银行许可证<br>
	电子版：</th>
        <td colspan="20"><? if ($supplier['bank_licence_electronic']){?><img src="<? echo $supplier['bank_licence_electronic'];?>" width=50 height=50>&nbsp;&nbsp;
	<input type="button" onclick="window.open('<? echo $supplier['bank_licence_electronic'];?>');" value="查看原图">
	<? }?></td>
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
        <th class="w150">银行开户名：</th>
        <td><? echo $supplier['settlement_bank_account_name'];?></td>
      </tr>
      <tr>
        <th class="w150">公司银行账号：</th>
        <td><? echo $supplier['settlement_bank_account_number'];?></td>
      </tr>
      <tr>
        <th class="w150">开户银行支行名称：</th>
        <td><? echo $supplier['settlement_bank_name'];?></td>
      </tr>
      <tr>
        <th class="w150">支行联行号：</th>
        <td><? echo $supplier['settlement_bank_code'];?></td>
      </tr>
    </tbody>
    
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">税务登记证</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">税务登记证号：</th>
        <td><? echo $supplier['tax_registration_certificate'];?></td>
      </tr>
      <tr>
        <th class="w150">纳税人识别号：</th>
        <td><? echo $supplier['taxpayer_id'];?></td>
      </tr>
      <tr>
        <th class="w150">税务登记证号<br>
	电子版：</th>
        <td><? if ($supplier['tax_registration_certificate_electronic']){?><img src="<? echo $supplier['tax_registration_certificate_electronic'];?>" width=50 height=50>&nbsp;&nbsp;
	<input type="button" onclick="window.open('<? echo $supplier['tax_registration_certificate_electronic'];?>');" value="查看原图">
	<? }?></td>
      </tr>
    </tbody>
  </table>


  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="2">店铺经营信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">店铺名称：</th>
          <td><? echo $supplier['supplier_name'];?></td>
        </tr>
        <tr>
          <th class="w150">店铺等级：</th>
          <td><? if($supplier['rank_id'] == 10){echo "收费店铺";}else{ echo "免费店铺";}?></td>
          </tr>
          <tr>
          <th class="w150">店铺分类：</th>
          <td><? echo $supplier['type_name'];?></td>
        </tr>
          <tr>
            <th class="w150">审核意见：</th>
            <td colspan="2"><? echo $supplier['supplier_remark']; ?></td>
        </tr>
        <tr>
            <th class="w150">审核状态：</td>
            <td  colspan="2"><select name="status" size=1 disabled>
                <option value="0" <? if($supplier['status'] == '0'){?>selected<? }?>>未审核</option>
                <option value="1" <? if($supplier['status'] == '1'){?>selected<? }?>>审核通过</option>
                <option value="-1" <? if($supplier['status'] == '-1'){?>selected<? }?>>审核不通过</option>
              </select></td>
          </tr>
        </tbody>
      </table>
     <? }else{?>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="6">入驻商个人信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">店铺名称：</th>
            <td colspan="5"><? echo $supplier['company_name'];?></td>
          </tr>
          <tr>
            <th class="w150">联系地址：</th>
            <td colspan="5">
              <select name="province" id="selProvinces_0" onchange="region.changed(this, 2, 'selCities_0')" disabled>
                <option value="0">{$lang.please_select}{$name_of_region[1]}</option>
               <? foreach ($resslist as $province){?>
                <option value="<? echo $province['region_id'];?>" <? if ($supplier['province'] ==  $province['region_id']){?>selected<? }?>><? echo $province['region_name'];?></option>
             <? }?>
              </select>
              <select name="city" id="selCities_0" onchange="region.changed(this, 3, 'selDistricts_0')" disabled>
                <option value="0">{$lang.please_select}{$name_of_region[2]}</option>
                <? foreach  ($city as $city){?>
                <option value="<? echo $city['region_id'];?>" <? if ($supplier['city'] == $city['region_id']){?>selected<? }?>><? echo $city['region_name'];?></option>
                <? }?>
              </select>
              <select name="district" id="selDistricts_0" {if !$district_list}style="display:none"{/if} disabled>
                <option value="0">{$lang.please_select}{$name_of_region[3]}</option>
                <? foreach  ($district as $district){?>
                <option value="<? echo $district['region_id'];?>" <? if ($supplier['district'] ==  $district['region_id']){?>selected<? }?>><? echo $district['region_name'];?></option>
                <? }?>
              </select></td>
          </tr>
          <tr>
            <th class="w150">详细地址：</th>
            <td colspan="5"><? echo $supplier['address'];?></td>
          </tr>
          <tr>
            <th class="w150">姓名：</th>
            <td><? echo $supplier['contacts_name'];?></td>
            <th class="w150">联系人电话：</th>
            <td><? echo $supplier['contacts_phone'];?></td>
            <th class="w150">电子邮箱：</th>
            <td><? echo $supplier['email'];?></td>
          </tr>
          <tr>
            <th class="w150">身份证号码：</th>
            <td colspan="5"><? echo $supplier['id_card_no'];?></td>
          </tr>
          <tr>
            <th class="w150">手持身份证照片：</th>
            <td><? if($supplier['handheld_idcard']){?><img src="<? echo $supplier['handheld_idcard'];?>" width=50 height=50>&nbsp;&nbsp;
              <input type="button" onclick="window.open('<? echo $supplier['handheld_idcard'];?>');" value="查看原图">
              <? }?></td>
            <th class="w150">身份证正面：</th>
            <td><? if($supplier['idcard_front']){?><img src="<? echo $supplier['idcard_front'];?>" width=50 height=50>&nbsp;&nbsp;
              <input type="button" onclick="window.open('<? echo $supplier['idcard_front'];?>');" value="查看原图">
              <? }?></td>
            <th class="w150">身份证反面：</th>
            <td><? if($supplier['idcard_reverse']){?><img src="<? echo $supplier['idcard_reverse'];?>" width=50 height=50>&nbsp;&nbsp;
              <input type="button" onclick="window.open('<? echo $supplier['idcard_reverse'];?>');" value="查看原图">
              <? }?></td>
        </tbody>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="2">开户银行信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">银行开户名：</th>
            <td><? echo $supplier['bank_account_name'];?></td>
          </tr>
          <tr>
            <th class="w150">个人银行账号：</th>
            <td><? echo $supplier['bank_account_number'];?></td>
          </tr>
          <tr>
            <th class="w150">开户银行支行名称：</th>
            <td><? echo $supplier['bank_name'];?></td>
          </tr>
          <tr>
            <th class="w150">支行联行号：</th>
            <td><? echo $supplier['bank_code'];?></td>
          </tr>
        </tbody>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="2">店铺经营信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">供货商名称：</th>
            <td><? echo $supplier['supplier_name'];?></td>
          </tr>
         <!-- <tr>
            <th class="w150">店铺等级：</th>
            <td><? echo $supplier['rank_name'];?> </td>
          </tr>-->
          <th class="w150">店铺分类：</th>
          <td><? echo $supplier['type_name'];?></td>
        </tr>
        <tr>
          <th class="w150">审核意见：</th>
          <td colspan="2"><? echo $supplier['supplier_remark'];?></td>
        </tr>
	<tr>
	<th class="w150">审核状态：</th>
	<td  colspan="2"><select name="status" size=1 disabled>
	<option value="0" <? if($supplier['status'] == '0'){?>selected<? }?>>未审核</option>
	<option value="1" <? if($supplier['status'] == '1'){?>selected<? }?>>审核通过</option>
	<option value="-1" <? if($supplier['status'] == '-1'){?>selected<? }?>>审核不通过</option>
	</select></td>
	</tr>
      </tbody>
    </table>

     <? }?> </div>
  </div>
</div>
<? }?>