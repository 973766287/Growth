<div class="m_left user_menu">
    <div class="left_content">
        <?php
        $rank = $this->Session->read('User.rank');
        ?>
        <h1><span>信息管理</span><b></b></h1>
        <h2><a href="<?php echo SITE_URL . 'user.php' ?>">个人中心</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=myinfo' ?>">修改个人资料</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=address_list' ?>">收货地址簿</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=editpass' ?>">修改密码</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=mycoll' ?>">我的收藏</a></h2>
<!--        <h2><a href="<?php echo SITE_URL . 'user.php?act=account_bd' ?>">绑定微信</a></h2>-->
        <h2><a href="<?php echo SITE_URL . 'user.php?act=logout' ?>">退出管理</a></h2>

        <h1><span>订单管理</span><b></b></h1>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=myorder' ?>">我的订单</a></h2>
        <h1><span>账户管理</span><b></b></h1>

        <h2><a href="<?php echo SITE_URL . 'user.php?act=myinfos_b' ?>">银行卡资料</a></h2>
<!--        <h2><a href="<?php echo SITE_URL . 'user.php?act=mymoney' ?>">账户余额</a></h2>-->
        <h2><a href="<?php echo SITE_URL . 'user.php?act=pointinfo' ?>">积分详情</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=monrydeial' ?>">资金变动明细</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=postmoney' ?>">申请提款</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=postmoneydata' ?>">提款记录</a></h2>

        <h1><span>我的团队</span><b></b></h1>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=myuser&t=1' ?>">我的会员</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=mymoneydata&status=tongguo' ?>">我的佣金</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=moneyrank' ?>">佣金榜</a></h2>


        <h1><span>会员升级</span><b></b></h1>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=mygiftbag' ?>">我领取的礼包 </a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=baoming&id=3' ?>">品牌推广商</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=baoming&id=4' ?>">微店代理商</a></h2>
        <h2><a href="<?php echo SITE_URL . 'user.php?act=baoming&id=5' ?>">黄金会员</a></h2>


        <div class="clear"></div>
    </div>
</div>