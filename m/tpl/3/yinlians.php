<header class="top_header">收银台</header>

	<form name="myform" id="myform" action="<?php echo ADMIN_URL;?>mycart.php?type=confirm" method="post">
    
   <input type="hidden" name="money"  value="<? echo $data['money']?>"/>
<input type="hidden" name="order_sn"  value="<? echo $data['order_sn'];?>"/>
<input name="pay_id"  id="pay_id" value="" type="hidden">
<input name="bank_no"  id="bank_no" value="<? echo $data['bank_no'];?>" type="hidden">

  <input   name="consignee"   value="<? echo $data['consignee'];?>" size="30" type="hidden" />
  
  <input  name="mobile"   value="<? echo $data['mobile'];?>" size="30" type="hidden" />
  
  <input  name="address"   value="<? echo $data['address'];?>" size="30"  type="hidden"/>
<div class="real_box">
  
  
  <dl>
   <dt style="width:120px;">金额</dt>
   <dd><? echo $data['money'];?></dd>
  </dl>
  
  
  
  <dl id="api">
   <dt style="width:120px;">银联快捷（API）</dt>
   <dd></dd>
  </dl>
  
  
  
  
  <dl id="h5">
   <dt style="width:120px;">银联快捷（H5）</dt>
   <dd></dd>
  
  </dl>
  
 
  

  		   
  		   
  			   
</div>

</form>

<script>

 
  $('#api').click(function () {
	
	
        document.getElementById('pay_id').value = 3;
	

	$('#myform').submit();

 });
 
 
  $('#h5').click(function () {
	
	
        document.getElementById('pay_id').value = 17;
	

	$('#myform').submit();

 });
 
</script>

