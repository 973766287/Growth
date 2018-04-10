<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/3/css.css?v=2" media="all" />
<link  type="text/css"  rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/3/style/index_style.css" />

<style>
    .div1{
        display:none;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        min-height:330px;
    }

    /*###############header##################*/





</style>
<script type="text/javascript">
    window.onload = function () {
        var oBox = document.getElementById('box');
        var oLi = oBox.getElementsByTagName('ul')[0].getElementsByTagName('li');
        var oDiv = oBox.getElementsByTagName('div');
        for (var i = 0; i < oLi.length; i++) {
            oLi[i].index = i;
            oLi[i].onclick = function () {
                for (var i = 0; i < oLi.length; i++) {
                    oLi[i].className = ''
                    oDiv[i].style.display = ''
                }
                this.className = 'active'

                oDiv[this.index].style.display = 'block'
            }
        }
    }
</script>

<style type="text/css">
.search_index {

height: 30px;
border-radius: 2px;
background: url(<?php echo $this->img('input_bg.png');?>) repeat-x top left;
}
.search_index .right {

float: right;
text-align: left;
}
.search_index .left {
text-align: center; height:30px;
}
.search_index .input1 {
height: 30px;
line-height: 30px;
text-indent: 5px;
color: #787575;
border: none;
background: url(<?php echo $this->img('611.png');?>) no-repeat center left;
display: block;
float: left;
width: 75%;
}
.goodslists{ min-height:300px;}
</style>
          <div class="top">
                <form id="form1" name="form1" method="get" action="<?php echo ADMIN_URL;?>catalog.php">
  <div class="search_index">
    <div class="right"><input type="image" src="<?php echo $this->img('submit3.png');?>" height="30" value=""></div>
    <div class="left"><input type="text" name="keyword" id="title" class="input1"  value="<?php echo !empty($keyword)&&!in_array($keyword,array('is_promote','is_qianggou','is_hot','is_best','is_new')) ? $keyword : "输入商品关键字";?>" onfocus="if(this.value=='输入商品关键字'){this.value='';}" onblur="if(this.value==''){this.value='输入商品关键字';}"></div>
  </div>
</form>

        
    </div>     
  
 
<div id="header_new">

    <div class="banner">
        <div class="wrap">

    <!-- <img src="<?php echo ADMIN_URL; ?>tpl/3/images/banner/1.png" />-->
            <?php $ad = $this->action('banner', 'banner', '首页轮播', 5); 
            
            ?>
            <?php if (!empty($ad)) { ?>
                <!--顶栏焦点图-->
                <div class="flexslider" style="margin-bottom:0px;">
                    <ul class="slides">
                        <?php
                     
                            foreach ($ad as $row) {
                                $a = basename($row['ad_img']);
                                ?>			 
                        <li> <a href="<?php echo $row['ad_url'];?>"> <img src="<?php echo SITE_URL . $row['ad_img']; ?>" style="width:100%" alt="<?php echo $row['ad_name']; ?>"/></a></li>
                            <?php } ?>												
                    </ul>
                </div>
            <?php } ?>

        </div>

    </div>

    <!--
  <span>分享商品页面可以获取提成哦！</span>
  <a href=""><button type="button">会员中心</button></a>-->




<?php


if($rt['tjr']){
?>

<div class="ad">
<span>来自好友<font color="#00761d"><?php echo empty($rt['tjr']['nickname'])?'官网':$rt['tjr']['nickname'];?></font>的推荐！</span>
    <a href="<?php echo ADMIN_URL.'user.php';?>"><button type="button">会员中心</button></a>
        </div>
<?php }else{
    ?>
        <div class="ad"><span>分享商品页面可以获取提成哦！</span>
    <a href="<?php if(empty($uid)){ echo ADMIN_URL.'user.php?act=login'; }else{ echo ADMIN_URL.'user.php'; }?>"><button type="button">会员中心</button></a>
    </div>
        <?php
} ?>
<?php $this->element('3/menu_top', array('lang' => $lang)); ?>	

</div>
<div id="content">
    <div class="ad-hot">
           <?php
                        if (!empty($rt['ad1'])){?>
                  
        <a href="<?php echo $rt['ad1']['ad_url'];?>"><img src="<?php echo SITE_URL . $rt['ad1']['ad_img']; ?>" /></a>
                
                  <?php } ?>	
    </div>
    <div class="list">
        <ul>
            <?php if (!empty($rt['listsjf'])) { ?>
                <?php foreach ($rt['listsjf'] as $k => $row) { ?>

                    <li>
                        <div class="pic"><a href="<?php echo ADMIN_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>"><img src="<?php echo SITE_URL . $row['goods_img']; ?>" /></a></div>
                        <div class="pic-detail">
                            <a href="<?php echo ADMIN_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>"><h2><?php echo $row['goods_name']; ?></h2></a>
                            <p style="font-size:1.2em; color:#f00; margin-top:16px;">￥<?php echo str_replace('.00', '', $row['pifa_price']); ?></p>
                            <p style="font-size:1em; color:#949494;">原价：￥<?php echo str_replace('.00', '', $row['shop_price']); ?></p>
                        </div>
                        <div class="buy"><a href="<?php echo ADMIN_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>"><img src="<?php echo ADMIN_URL; ?>tpl/3/images/buy.png" /></a></div>
                        <div style="clear:both;"></div>
                    </li>


                <?php } ?><?php } ?>

        </ul>
    </div>
    <div class="ad-hot">     <?php if (!empty($rt['ad2'])){?>
                  
        <a href="<?php echo $rt['ad2']['ad_url'];?>"> <img src="<?php echo SITE_URL . $rt['ad2']['ad_img']; ?>" /></a>
                
                  <?php } ?></div>

    <div id="box" class="list-all" style="overflow:hidden;">
        <ul class="nav-left">
            <?php
            if (!empty($rt['cat'])) {
                $i = 0;
                foreach ($rt['cat'] as $row) {
                    $i++;
                    ?>

                    <li <?php if ($i == 1) { ?>class="active"<?php } ?>><a  ><?php echo $row['cat_name']; ?></a></li>
                <?php
                }
            }
            ?>
        </ul>


        <?php
        if (!empty($rt['cat'])) {
            $i = 0;
            foreach ($rt['cat'] as $row) {
                $i++;
                ?>

                <div class="div1 list-right" <?php if ($i == 1) { ?>style="display:block;"<?php } ?>>
                    <h2 class="list-title"><span><?php echo $row['cat_name']; ?></span></h2>
                    <ul>
                        <?php
                        if (!empty($rt['goods'][$row['cat_id']])) {
                            foreach ($rt['goods'][$row['cat_id']] as $k => $rows) {
                                ?>
                                <li style="overflow:hidden;">
                                    <span class="pic"><a href="<?php echo ADMIN_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $rows['goods_id']; ?>"><img src="<?php echo SITE_URL . $rows['goods_img']; ?>" /></a></span>
                                    <span class="pic-detail">
                                        <a href="<?php echo ADMIN_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $rows['goods_id']; ?>"><h2><?php echo $rows['goods_name']; ?></h2></a>
                                        <p>￥<?php echo str_replace('.00', '', $rows['pifa_price']); ?></p>
                                    </span>
                                    <span class="buy"><a href="<?php echo ADMIN_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $rows['goods_id']; ?>"><img src="<?php echo ADMIN_URL; ?>tpl/3/images/buy_2.png" /></a></span>
                                    <span style="clear:both;"></span>
                                </li>
                            <?php
                            }
                        }
                        ?>
                    </ul>
                </div>


            <?php
            }
        }
        ?>

        <br style="clear:both" />
    </div>
</div>
<div id="copyright">
    <p>-Copyright©2015版权所有：金葵花微商城-山东金葵花网络科技有限公司
 All Rights Reserved</p>
</div>
<?php $this->element('3/footer', array('lang' => $lang)); ?>