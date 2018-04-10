<style type="text/css">
    .gototype a{ padding:2px; border-bottom:2px solid #ccc; border-right:2px solid #ccc;}
</style>
<div class="contentbox">
    <form id="form1" name="form1" method="post" action="">
        <table cellspacing="2" cellpadding="5" width="100%">
            <tr>
                <th colspan="2" align="left" class="gototype">权限密码验证</th>
            </tr>
            <tr>

            <tr>
                <td class="label" width="15%">权限密码：</td>
                <td>
                    <input name="priv_password"   size="40" type="text" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><label><input name="url"     type="hidden" value="<?php echo $url; ?>" />
                        <input type="submit" value="登录" class="submit"/>
                    </label></td>
            </tr>
        </table>
    </form>
</div>

