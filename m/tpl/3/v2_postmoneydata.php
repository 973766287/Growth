<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/3/css.css" media="all" />
<?php $this->element('3/top',array('lang'=>$lang)); ?>

<style type="text/css">
table td:hover{ background:#ededed;}
.pages a{ background:#ededed; padding:2px 4px 2px 4px; border-bottom:2px solid #ccc; border-right:2px solid #ccc; margin-right:5px;}

</style>
<div id="main" style=" min-height:300px">
<table  width="100%" border="0" cellpadding="0" cellspacing="0">
<?php if(!empty($rt))foreach($rt as $k=>$row){
?>
<tr>
	<td align="left">
		<div style="padding:10px;border-bottom:1px solid #d5d5d5"> 
		申请时间:<?php echo !empty($row['addtime']) ? date('Y-m-d H:i:s',$row['addtime']) : '无知';?>
		<p style="line-height:26px; height:26px; position:relative;"><span style="float:left">金额:<font color="#FF0000">￥<?php echo floor($row['amount']*100)/100;?></font></span><span style="float:right">状态:<font color="#FF0000"><?php echo $row['state']=='0' ? '审核中' : '已结算';?></font></span></p>
		<p style="line-height:26px;">姓名:<?php echo $row['account_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;手机:<?php echo $row['mobile'];?></p>
                <p style="line-height:26px;">
                       <?php echo ($row['bankname'])?"银行账号：".$row['bankname'].$row['account_no']:'';?>
                </p>
		</div>
	</td>
</tr>
<?php }else{ ?>
<tr>
	<td style="text-align:center">
		<div style=" font-size:18px;padding:15%">
		暂无提款记录
		</div>
	</td>
</tr>
<?php } ?>

<tr>
	  <td style="text-align:left;" class="pagesmoney">
	  <?php if(!empty($orderpage)){?>
	  <div class="pages" style="line-height: 60px;"><?php echo $orderpage['showmes'];?><?php echo $orderpage['first'].$orderpage['previ'].$orderpage['next'].$orderpage['Last'];?></div>
	  <?php } ?>
	  </td>
	  </tr>
</table>

</div>
