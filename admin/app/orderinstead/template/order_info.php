<style type="text/css">
.pw,.pwt{
height:26px; line-height:26px;
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pw{ width:90%;}
.usertitle{
height:22px; line-height:22px;color:#666; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #ededed; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
table p{ line-height:22px;}
.order_basic table td{ border:1px solid #F4F6F1; }
.usertitle{background:#F5F7F2; text-align:center; line-height:25px; font-size:13px; font-weight:bold; margin-bottom:0px; margin-top:0px}
</style>
<div class="contentbox">
 	<div class="usertitle">扣款订单  <span style="float:right;"><a href="javascript:history.go(-1);"><input value=" 返回 " class="order_search" type="button"></a></span></div>
	<table cellspacing="2" cellpadding="5" width="100%">
    <tr>
	  
       <th> 序号 </th>
	   <th>订单号</th>
	   <th>扣款时间</th>
	   <th>手续费</th>
	   <th>刷卡费率</th>
       <th>结算金额</th>
	   <th>总金额</th>
	   <th>订单状态</th>
       <th>扣款描述</th>
       <th>操作</th>
	</tr>

	<tr>

    <td><?php echo $orderinfo['order_id'];?></td>
	<td><?php echo $orderinfo['order_sn'];?></td>
	<td><?php echo date('Y-m-d H:i:s',$orderinfo['pay_time']);?></td>
	<td><?php echo round(($orderinfo['feilv']/10000*$orderinfo['order_amount']),2);?></td>
	<td><?php echo $orderinfo['feilv']/10000;?></td>
	<td><?php echo $orderinfo['order_amount']-round(($orderinfo['feilv']/10000*$orderinfo['order_amount']),2);?></td>
	<td><?php echo $orderinfo['order_amount'];?></td>
	<td><?php if($orderinfo['pay_status'] == 1){echo "已支付";}else{echo "未支付";}?></td>
    <td><?php echo $orderinfo['orderdesc'];?></td>
	<td><?php if($orderinfo['pay_status'] == 0){echo "重新交易";}?></td>
	</tr>

	 </table>
     
     <?php if(!empty($drawmoneyinfo)){?>
	<div class="usertitle">代付订单</div>

	<table cellspacing="2" cellpadding="5" width="100%">
        <tr>
            <th><label>商户号</label></th>
             <th>订单号</th>
             <th>代付单号</th>
              <th>结账金额</th>
                 <th width="80">结账申请时间</th>
            <th>结算账户名</th>
             <th>结算银行</th>
               <th width="120">结算账号</th>
            <th>结算状态</th>
           <th>代付状态</th>
           <th>代付系统返回码</th>
           <th>代付系统返回信息</th>

          
         
       
           
            <th width="80">操作</th>
        </tr>

                <tr>
                    <td style="text-align: center"><?php echo $drawmoneyinfo['uid']; ?></td>
                    <td><?php echo $drawmoneyinfo['order_sn']; ?>
                     <td><?php echo $drawmoneyinfo['INFO_REQ_SN']; ?>
                    </td>

                    <td><?php echo $drawmoneyinfo['amount']; ?></td>
                    <td><?php echo !empty($drawmoneyinfo['addtime']) ? date('Y-m-d H:i:s', $drawmoneyinfo['addtime']) : '无知'; ?></td>
                     <td><?php echo $drawmoneyinfo['account_name']; ?></td>
                    <td><?php echo $drawmoneyinfo['bankname']; ?></td>
                    <td><?php echo $drawmoneyinfo['account_no']; ?></td>
                    <td><a href="<?php echo ADMIN_URL.'Instead.php?type=change_state&state='.$drawmoneyinfo['state'].'&id='.$drawmoneyinfo['id'];?>"><?php echo $drawmoneyinfo['state']?"已结账":"未结账"; ?></a><br><?php  echo !empty($drawmoneyinfo['upstate_time']) ? '时间:'.date('Y-m-d H:i:s', $drawmoneyinfo['upstate_time']) : ''; ?></td>
                    <td><?php  if($drawmoneyinfo['INFO_RET_CODE'] === "0000" ){echo "代付已发送";}else{echo "代付未发送"; }?></td>
                    <td><?php echo $drawmoneyinfo['RET_DETAILS_RET_CODE']; ?></td>
                    <td><?php echo $drawmoneyinfo['RET_DETAILS_ERR_MSG'] ;?></td>
                    <td >
                   
                       <a href="javascript:void(0);" onclick="ajax_pay_search(<? echo $drawmoneyinfo['id'];?>)">查询</a>
               <?php if($drawmoneyinfo['state'] !=1){?>
                         <a href="javascript:void(0);" onclick="ajax_pay_daifu_old(<? echo $drawmoneyinfo['id'];?>)">提现</a>
                      <?php }?>
                    </td>
                </tr>

    </table>
	<?php }?>
	 
</div>
<?php $thisurl = ADMIN_URL.'Instead.php'; ?>
<script type="text/javascript">
  function ajax_pay_search(id) {
		 
		
        $.post('<?php echo $thisurl; ?>', {action: 'yinlianapi_query', id: id}, function (data) {
               
                                  alert(data);

            });
			
			
			
	 }
	 
	 
	  function ajax_pay_daifu_old(id) {
		 
		
       $.post('<?php echo $thisurl; ?>', {action: 'yinlianapi_pay_daifu', id: id}, function (data) {
               
                                  alert(data);
								  
								location.href='<?php echo ADMIN_URL;?>Instead.php?type=order_info&id=<?php echo $drawmoneyinfo['plan_id'];?>';

            });
			
			
	 }

</script>
