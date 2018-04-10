<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/3/css.css" media="all" />
<?php $this->element('3/top', array('lang' => $lang)); ?>
<style type="text/css">
    .pw,.pwt{
        height:26px; line-height:normal;
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
  
</style>

<div id="main" style="min-height:300px;">
   <?php if( $rt['userinfo']['bdcount']==0){?>
    <form name="USERINFO" id="USERINFO" action="" method="post"  >
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:30px; padding:10px;">
            <tr>
                <td   style="padding-bottom:2px;" colspan="2" >如果您已经有平台的账号和密码，您可以选择绑定平台账号</td>

            </tr>
             <tr>
                 <td    width="50%" align="center" > 
                     <input type="radio" name="bdtype" id="bdtyp1" value="1"/>保留微信账号     </td>
                      <td    width="50%" align="center" >   <input type="radio" name="bdtype" id="bdtyp2" value="2"/>保留平台账号 
                 </td>

            </tr>
            <tr>
                <td width="20%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b>平台账号：</td>
                <td width="80%" align="left" style="padding-bottom:2px;">
                    <input type="text" value="" name="user_name"  class="pw" placeholder="请输入平台账号" style="padding-left:25px; background:url(<?php echo $this->img('u.png'); ?>) 3px center no-repeat"/></td>
            </tr>
            <tr>
                <td width="20%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b>平台密码：</td>
                <td width="80%" align="left" style="padding-bottom:2px;">
                    <input type="password" value="" name="pass"  class="pw"  placeholder="请输入平台密码" style="padding-left:25px; background:url(<?php echo $this->img('p.png'); ?>) 3px center no-repeat"/></td>
            </tr>
            <tr>

            <tr>
                <td align="center" style="padding-top:20px;" colspan="2">
                    <a href="javascript:;" onclick="return bd_account(1);" style="display:block;background:#e13935;cursor:pointer;width:110px; height:24px; line-height:24px; font-size:14px; color:#FFF; font-weight:bold; text-align:center;border-radius: 5px; overflow:hidden">确定修改</a>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <span class="returnmes" style="color:#FF0000"></span>
                </td>
            </tr>
        </table>
    </form>
       <?php } else{?>
    <p>您已经绑定过平台账号和密码。</p>
    <?php
   }?>
</div>

<?php $this->element('3/footer', array('lang' => $lang)); ?>