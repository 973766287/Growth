
<div class="index c" style="height:4740px;">
    <div class="index_main">
        <div class="index_main1">
            <div class="index_main1_left fl">
                <div class="index_main1_left_top">
                    <div class="index_main1_left_top_left fl"><img src="images/26.jpg" /></div>
                    <div class="index_main1_left_top_right fl l">每天都有哦~快来【关注】我吧！</div>
                </div>
                <div class="index_main1_left_bottom">

                    <?php if (!empty($qx)) { ?>
                        <?php foreach ($qx as $k => $row) { ?>
                            <a href="<?php echo SITE_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>">
                                <div class="index_main1_left_bottom_main fl">
                                    <div class="index_main1_left_bottom_main1"><img src="<?php echo SITE_URL . $row['goods_img']; ?>" /></div>
                                    <div class="index_main1_left_bottom_main2 zt6"><?php echo mb_substr(trim($row['goods_name']), 0, 12, 'utf-8'); ?></div>
                                    <div class="index_main1_left_bottom_main3"><?php echo $row['sort_desc'] ? mb_substr($row['sort_desc'], 0, 15, 'utf-8') : mb_substr(trim($row['goods_name']), 0, 15, 'utf-8'); ?></div>
                                    <div class="index_main1_left_bottom_main4"><span class="zt8">￥<?php echo str_replace('.00', '', $row['pifa_price']); ?> </span><span class="zt5">￥<?php echo str_replace('.00', '', $row['shop_price']); ?></span></div>
                                    <div class="index_main1_left_bottom_main5"><span class="zt7"><?php echo $row['sale_count']; ?></span>人已付款</div>
                                </div>
                            </a>
                        <?php }
                    } ?>

                </div>
            </div>
            <div class="index_main1_right fr">
                <a href="<?php echo $rt['ad39']['ad_url']; ?>"><img src="<?php echo $rt['ad39']['ad_img']; ?>"   width='189' height='418'/></a>
                <!--
                   <div class="index_main1_right1 zt8">免费试吃</div>
                       <div class="index_main1_right2">
                           <ul>
                                   <li><a href="#">今日试吃</a></li>
                                       <li><a href="#">即将开始</a></li>
                               </ul>
                       </div>
                       <div class="index_main1_right3 zt6 l">夏威夷果218gx2袋</div>
                       <div class="index_main1_right4 l">香甜细腻、健康美味、无坏果</div>
                       <div class="index_main1_right5 l">市场价178.00元</div>
                       <div class="index_main1_right6 l"><span class="zt8">27738 </span><span class="zt5">人想吃</span></div>
                       <div class="index_main1_right7 l"><img src="images/29.jpg" width="80" height="24" /></div>
                       <div class="index_main1_right8"><img src="images/28.jpg" width="189" height="111" /></div>
               
                -->
            </div>
        </div>
        <div class="index_main2">
            <a href="<?php echo $rt['ad46']['ad_url']; ?>"><img src="<?php echo $rt['ad46']['ad_img']; ?>"  width="1160" height="60"/></a>
        </div>
        <div class="index_main3" style="height:3850px;">
            <div class="index_main31">
                <div class="index_main31_top">
                    <div class="index_main31_top_left fl l">美妆专区</div>
                    <div class="index_main31_top_right fr"><a href="<?php echo SITE_URL; ?>catalog.php?cid=589"><img src="images/35.jpg" width="57" height="26" /></a></div>
                </div>
                <div class="index_main31_bottom">
                    <div class="index_main31_bottom_left fl">

                        <?php if (!empty($DryFruits)) { ?>
    <?php foreach ($DryFruits as $k => $row) { ?>
                                <a href="<?php echo SITE_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>">
                                    <div class="index_main31_bottom_left_main fl">
                                        <div class="index_main31_bottom_left_main_top"><img src="<?php echo $row['goods_img']; ?>" width="173" height="173" /></div>
                                        <div class="index_main31_bottom_left_main_main l"><?php echo mb_substr(trim($row['goods_name']), 0, 12, 'utf-8'); ?>...</div>
                                        <div class="index_main31_bottom_left_main_bottom l"><span class="zt9">￥</span><span class="zt10"><?php echo $row['pifa_price']; ?></span> <span class="zt5">原价：<?php echo $row['shop_price']; ?></span></div>
                                    </div>
    <?php }
} ?>

                    </div>
                    <div class="index_main31_bottom_right fr"><a href="<?php echo $rt['ad40']['ad_url']; ?>"><img src="<?php echo $rt['ad40']['ad_img']; ?>" width="212" height="476" /></a></div>
                </div>
            </div>
            <div class="index_main31">
                <div class="index_main32_top">
                    <div class="index_main32_top_left fl l">潮服专区</div>
                    <div class="index_main31_top_right fr"><a href="<?php echo SITE_URL; ?>catalog.php?cid=606"><img src="images/36.jpg" width="57" height="26" /></a></div>
                </div>
                <div class="index_main31_bottom">
                    <div class="index_main31_bottom_left fr">

<?php if (!empty($Chaofu)) { ?>
    <?php foreach ($Chaofu as $k => $row) { ?>
                                <a href="<?php echo SITE_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>">
                                    <div class="index_main31_bottom_left_main fl">
                                        <div class="index_main31_bottom_left_main_top"><img src="<?php echo $row['goods_img']; ?>" width="173" height="173" /></div>
                                        <div class="index_main31_bottom_left_main_main l"><?php echo mb_substr(trim($row['goods_name']), 0, 12, 'utf-8'); ?>...</div>
                                        <div class="index_main31_bottom_left_main_bottom l"><span class="zt9">￥</span><span class="zt10"><?php echo $row['pifa_price']; ?></span>  <span class="zt5">原价：<?php echo $row['shop_price']; ?></span></div>
                                    </div>
                                </a>
    <?php }
} ?>
                    </div>
                    <div class="index_main31_bottom_right fl"><a href="<?php echo $rt['ad41']['ad_url']; ?>"><img src="<?php echo $rt['ad41']['ad_img']; ?>" width="212" height="476" /></a></div>
                </div>
            </div>
            <div class="index_main31">
                <div class="index_main33_top">
                    <div class="index_main33_top_left fl l">鞋子专区</div>
                    <div class="index_main31_top_right fr"><a href="<?php echo SITE_URL; ?>catalog.php?cid=590"><img src="images/37.jpg" width="57" height="26" /></a></div>
                </div>
                <div class="index_main31_bottom">
                    <div class="index_main31_bottom_left fl">

<?php if (!empty($Shoes)) { ?>
    <?php foreach ($Shoes as $k => $row) { ?>
                                <a href="<?php echo SITE_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>">
                                    <div class="index_main31_bottom_left_main fl">
                                        <div class="index_main31_bottom_left_main_top"><img src="<?php echo $row['goods_img']; ?>" width="173" height="173" /></div>
                                        <div class="index_main31_bottom_left_main_main l"><?php echo mb_substr(trim($row['goods_name']), 0, 12, 'utf-8'); ?>...</div>
                                        <div class="index_main31_bottom_left_main_bottom l"><span class="zt9">￥</span><span class="zt10"><?php echo $row['pifa_price']; ?></span> <span class="zt5">原价：<?php echo $row['shop_price']; ?></span></div>
                                    </div>
                                </a>
    <?php }
} ?>

                    </div>
                    <div class="index_main31_bottom_right fr"><a href="<?php echo $rt['ad42']['ad_url']; ?>"><img src="<?php echo $rt['ad42']['ad_img']; ?>" width="212" height="476" /></a></div>
                </div>
            </div>
            <div class="index_main31">
                <div class="index_main34_top">
                    <div class="index_main34_top_left fl l">箱包专区</div>
                    <div class="index_main31_top_right fr"><a href="<?php echo SITE_URL; ?>catalog.php?cid=583"><img src="images/38.jpg" width="57" height="26" /></a></div>
                </div>
                <div class="index_main31_bottom">
                    <div class="index_main31_bottom_left fr">

<?php if (!empty($Stockings)) { ?>
    <?php foreach ($Stockings as $k => $row) { ?>
                                <a href="<?php echo SITE_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>">
                                    <div class="index_main31_bottom_left_main fl">
                                        <div class="index_main31_bottom_left_main_top"><img src="<?php echo $row['goods_img']; ?>" width="173" height="173" /></div>
                                        <div class="index_main31_bottom_left_main_main l"><?php echo mb_substr(trim($row['goods_name']), 0, 12, 'utf-8'); ?>...</div>
                                        <div class="index_main31_bottom_left_main_bottom l"><span class="zt9">￥</span><span class="zt10"><?php echo $row['pifa_price']; ?></span>  <span class="zt5">原价：<?php echo $row['shop_price']; ?></span></div>
                                    </div>
                                </a>
    <?php }
} ?>

                    </div>
                    <div class="index_main31_bottom_right fl"><a href="<?php echo $rt['ad43']['ad_url']; ?>"><img src="<?php echo $rt['ad43']['ad_img']; ?>" width="212" height="476" /></a></div>
                </div>
            </div>
            
            
            
            
            
            
            <div class="index_main31">
                <div class="index_main33_top">
                    <div class="index_main33_top_left fl l">内衣专区</div>
                    <div class="index_main31_top_right fr"><a href="<?php echo SITE_URL; ?>catalog.php?cid=632"><img src="images/37.jpg" width="57" height="26" /></a></div>
                </div>
                <div class="index_main31_bottom">
                    <div class="index_main31_bottom_left fl">

<?php if (!empty($neiyis)) { ?>
    <?php foreach ($neiyis as $k => $row) { ?>
                                <a href="<?php echo SITE_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>">
                                    <div class="index_main31_bottom_left_main fl">
                                        <div class="index_main31_bottom_left_main_top"><img src="<?php echo $row['goods_img']; ?>" width="173" height="173" /></div>
                                        <div class="index_main31_bottom_left_main_main l"><?php echo mb_substr(trim($row['goods_name']), 0, 12, 'utf-8'); ?>...</div>
                                        <div class="index_main31_bottom_left_main_bottom l"><span class="zt9">￥</span><span class="zt10"><?php echo $row['pifa_price']; ?></span> <span class="zt5">原价：<?php echo $row['shop_price']; ?></span></div>
                                    </div>
                                </a>
    <?php }
} ?>

                    </div>
                    <div class="index_main31_bottom_right fr"><a href="<?php echo $rt['ad47']['ad_url']; ?>"><img src="<?php echo $rt['ad47']['ad_img']; ?>" width="212" height="476" /></a></div>
                </div>
            </div>
            
            
            
            
            
            <div class="index_main31">
                <div class="index_main34_top">
                    <div class="index_main34_top_left fl l">配饰专区</div>
                    <div class="index_main31_top_right fr"><a href="<?php echo SITE_URL; ?>catalog.php?cid=639"><img src="images/38.jpg" width="57" height="26" /></a></div>
                </div>
                <div class="index_main31_bottom">
                    <div class="index_main31_bottom_left fr">

<?php if (!empty($peishis)) { ?>
    <?php foreach ($peishis as $k => $row) { ?>
                                <a href="<?php echo SITE_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>">
                                    <div class="index_main31_bottom_left_main fl">
                                        <div class="index_main31_bottom_left_main_top"><img src="<?php echo $row['goods_img']; ?>" width="173" height="173" /></div>
                                        <div class="index_main31_bottom_left_main_main l"><?php echo mb_substr(trim($row['goods_name']), 0, 12, 'utf-8'); ?>...</div>
                                        <div class="index_main31_bottom_left_main_bottom l"><span class="zt9">￥</span><span class="zt10"><?php echo $row['pifa_price']; ?></span>  <span class="zt5">原价：<?php echo $row['shop_price']; ?></span></div>
                                    </div>
                                </a>
    <?php }
} ?>

                    </div>
                    <div class="index_main31_bottom_right fl"><a href="<?php echo $rt['ad48']['ad_url']; ?>"><img src="<?php echo $rt['ad48']['ad_img']; ?>" width="212" height="476" /></a></div>
                </div>
            </div>
            
            
            
            <div class="index_main31">
                <div class="index_main33_top">
                    <div class="index_main33_top_left fl l">孕婴童专区</div>
                    <div class="index_main31_top_right fr"><a href="<?php echo SITE_URL; ?>catalog.php?cid=645"><img src="images/37.jpg" width="57" height="26" /></a></div>
                </div>
                <div class="index_main31_bottom">
                    <div class="index_main31_bottom_left fl">

<?php if (!empty($yunyings)) { ?>
    <?php foreach ($yunyings as $k => $row) { ?>
                                <a href="<?php echo SITE_URL . ($row['is_jifen'] == '1' ? 'exchange' : 'product') . '.php?id=' . $row['goods_id']; ?>">
                                    <div class="index_main31_bottom_left_main fl">
                                        <div class="index_main31_bottom_left_main_top"><img src="<?php echo $row['goods_img']; ?>" width="173" height="173" /></div>
                                        <div class="index_main31_bottom_left_main_main l"><?php echo mb_substr(trim($row['goods_name']), 0, 12, 'utf-8'); ?>...</div>
                                        <div class="index_main31_bottom_left_main_bottom l"><span class="zt9">￥</span><span class="zt10"><?php echo $row['pifa_price']; ?></span> <span class="zt5">原价：<?php echo $row['shop_price']; ?></span></div>
                                    </div>
                                </a>
    <?php }
} ?>

                    </div>
                    <div class="index_main31_bottom_right fr"><a href="<?php echo $rt['ad49']['ad_url']; ?>"><img src="<?php echo $rt['ad49']['ad_img']; ?>" width="212" height="476" /></a></div>
                </div>
            </div>
            
            
        </div>
        <div class="index_main4">
            <div class="index_main4_left fl">
                <div class="index_main4_left_top">
                    <div class="index_main4_left_top_left fl">大家都在说</div>
                    <div class="index_main4_left_top_right fr"></div>
                </div>
                <div class="index_main4_left_bottom">

<?php if (!empty($rt['allcommentlist'])) {
    foreach ($rt['allcommentlist'] as $k => $row) {
        ?>
                            <a href="<?php echo $row['goodsurl']; ?>">

                                <div class="index_main4_left_bottom_main">
                                    <div class="index_main4_left_bottom_main_left fl"><img src="<?php echo $row['goods_thumb']; ?>" /></div>
                                    <div class="index_main4_left_bottom_main_right fr l">
                                        <div class="index_main4_left_bottom_main_right_top zt9"><?php echo $row['goods_name']; ?> </div>
                                        <div class="index_main4_left_bottom_main_right_main"><?php echo mb_substr(trim($row['content']), 0, 24, 'utf-8'); ?></div>
                                        <div class="index_main4_left_bottom_main_right_bottom"><?php echo $row['nickname']; ?> <?php echo date("Y-m-d H:i:s", $row['add_time']); ?></div>
                                    </div>
                                </div>
                            </a>

    <?php }
} ?>




                </div>
            </div>
            <div class="index_main4_main fl">
                <div class="index_main4_main_top l">宣传视频</div>
                <div class="index_main4_main_bottom"><img src="images/45.jpg" /></div>
            </div>
            <div class="index_main4_right fr"><a href="<?php echo $rt['ad44']['ad_url']; ?>"><img src="<?php echo $rt['ad44']['ad_img']; ?>" width="341" height="335" /></a></div>
        </div>
    </div>
</div>
