
<div id="fl">
    <div class="fl_top">
        <div class="fl_top_left fl l"><a href="catetree.php">全部商品分类</a></div>
		<div class="fl_top_right fr"><img src="images/6.jpg" /></div>
	</div>
	
</div>

<div class="head c">
    <div class="head_top">
	    <div class="head_top_main">
                <div class="head_top_main_left fl"><?php echo $lang['userinfo']['nickname']?>您好，欢迎来到金葵花商城 
    <?php  
  
    $uid = $this->Session->read('User.uid');
                    if(!empty($uid)){?>
                    [<a href="<?php echo SITE_URL.'user.php';?>">会员中心</a>]
                     [<a href="<?php echo SITE_URL.'user.php?act=logout';?>">退出登陆</a>]
                    <?php } else {?>
                    [<a href="user.php?act=login">登录</a>][<a href="user.php?act=register">注册</a>]
                    <?php } ?>
                
                </div>
                <div class="head_top_main_right fr" style="background:url(images/1.jpg) no-repeat right;">
                    
                    <?php $cart = $this->Session->read('cart');?>
                    <div class="ajaxshowcart"><?php $this->element("box/ajax_pop_cart",array('cart'=>$cart,'thisgid'=>0));?></div>
                    <a href="<?php echo SITE_URL.'user.php?act=myorder';?>">我的订单</a> | 
                    <a href="<?php echo SITE_URL;?>mycart.php">购物车<!--<span class="mycarts"><?php echo count($cart);?></span>件--></a> |
                    <a href="<?php echo get_url('帮助中心',0,SITE_URL.'about.php','category',array('about','index'));?>"  >帮助中心</a>|
                       <a href="<?php echo SITE_URL.'user.php?act=mycoll';?>">收藏夹 </a>| 　 
                        <a href="<?php echo SITE_URL.'user.php';?>"> 会员中心</a>
                
                
                </div>
		</div>
	</div>
	<div class="head_main">
	    <div class="head_main_left fl">
                            <a href="<?php echo SITE_URL;?>"><img src="<?php echo !empty($lang['site_logo']) ? SITE_URL.$lang['site_logo'] : $this->img('2.jpg');?>"  /></a>
            </div>
		<div class="head_main_main fl">
		    <div class="head_main_main_top">
			     <form id="form1" name="form1" method="get" action="<?php echo SITE_URL;?>catalog.php">
			    <input type='text' name="keyword" class="head_main_main_top_left fl"/>
				<div class="head_main_main_top_right fr">
                                    <input type="submit" value=""  class="searchbtn" />
                                    </div>
                                  </form>
			</div>
                 
			<div class="head_main_main_bottom l">热门搜索：   <?php foreach($lang['search_keys'] as $_k=>$_v){
                        $search_words=SITE_URL."catalog.php?keyword=".$_v;
                       ?><a href='<?php echo $search_words;?>'><?php echo $_v;?></a>
                            <?php
                    }?>
                            </div>
		</div>
		<div class="head_main_right fr"><img src="images/4.jpg" width="159" height="37" /></div>
	</div>
	<div class="head_bottom">
	    <div class="head_bottom_left fl">
		    <ul>
                      <li><a href="<?php echo SITE_URL;?>" >网站首页</a></li>
<!--                     <li><a href="<?php echo SITE_URL;?>"<?php echo strpos($_SERVER['PHP_SELF'],'index.php') ? ' class="acnav"' : '';?>>网站首页</a></li>-->
	<?php if(!empty($lang['navlist_middle']))foreach($lang['navlist_middle'] as $k=>$row){?>
	<li><a href="<?php echo $row['url'];?>"<?php echo $row['url']==substr($_SERVER['REQUEST_URI'],1) ? ' class="acnav"' : '';?>><?php echo $row['name'];?></a></li>
	<?php } ?>
			</ul>
		</div>
		<div class="head_bottom_right fr"><img src="images/5.jpg" width="139" height="35" /></div>
	</div>
</div>
