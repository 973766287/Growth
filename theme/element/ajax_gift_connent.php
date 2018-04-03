<?php
$sale_count = $rt['thisorder'] == 'sale_count' ? ' class="ac" ' : "";
$add_time = !(isset($rt['thisorder'])) || $rt['thisorder'] == 'goods_id' ? ' class="ac" ' : "";
$pifa_price = $rt['thisorder'] == 'pifa_price' ? ' class="ac" ' : "";
$is_new = $rt['thisorder'] == 'is_new' ? ' class="ac" ' : "";
$p1 = '￥';
$p2 = '￥';
if (!empty($rt['price'])) {
    $p = explode('-', $rt['price']);
    $p1 = isset($p[0]) ? '￥' . $p[0] : '￥';
    $p2 = isset($p[1]) ? '￥' . $p[1] : '￥';
}
?>

<div class="list_bybox_gift ">
    <ul>
        <?php if (!empty($rt['lists'])) { foreach ($rt['lists'] as $row) { ?>
                <li>
                    <a href="<?php echo SITE_URL . 'user.php?act=gift_info&bid=' . $row['bid']; ?>">
                        <div class="list_bybox_gift_img"> <img src="<?php echo SITE_URL . $row['goods_thumb']; ?>" alt="<?php echo $row['bag_name']; ?>"/></div>

                        <div class="list_bybox_gift_desc">
                        <h3><?php echo $row['bag_name']; ?></h3>
                        <i>立即领取</i>
                        </div>
                    </a>
                </li>
        <?php } } ?>
    </ul>
</div>
<div class="page" id="js_page">
    <?php if (!empty($rt['categoodspage'])) { ?>
        <p class="pages">
            <span ><?php echo str_replace('首页', '首页', $rt['categoodspage']['first']); ?></span>
            <span><?php echo str_replace('上一页', '上一页', $rt['categoodspage']['prev']); ?></span>
            <?php
            if (isset($rt['categoodspage']['list']) && !empty($rt['categoodspage']['list'])) {
                foreach ($rt['categoodspage']['list'] as $aa) {
                    echo $aa . "\n";
                }
            }
            ?>
            <?php echo str_replace('下一页', '下一页', $rt['categoodspage']['next']); ?>
            <?php echo str_replace('尾页', '尾页', $rt['categoodspage']['last']); ?>
          <!--<em>到第<input type="text" name="pageindex" class="pageinput" value="<?php echo $rt['page'] + 1; ?>" maxlength="4">页</em><input type="submit" name="Submit" class="subtxt" value="确认" onclick="get_categoods_page_list($('.pageinput').val(),'<?php echo $rt['thiscid']; ?>','<?php echo $rt['thisbid']; ?>','<?php echo $rt['price']; ?>','<?php echo $rt['order']; ?>','<?php echo $rt['sort']; ?>','<?php echo $rt['limit']; ?>','<?php echo $rt['thisattr']; ?>')"/>-->
        </p>
    <?php } ?>
    <!--      
            <span class="prev">&nbsp;</span>
            
            <span class="current">1</span>
            <a onclick="return goodList.Go(2)" href="#" rel="2">2</a>
            <a onclick="return goodList.Go(3)" href="#" rel="3">3</a>
            <a onclick="return goodList.Go(4)" href="#" rel="4">4</a>
            <a onclick="return goodList.Go(5)" href="#" rel="5">5</a>
            <a onclick="return goodList.Go(6)" href="#" rel="6">6</a>
            <a onclick="return goodList.Go(7)" href="#" rel="7">7</a>
            <a onclick="return goodList.Go(8)" href="#" rel="8">8</a>
            <a onclick="return goodList.Go(9)" href="#" rel="9">9</a>
            <a onclick="return goodList.Go(10)" href="#" rel="10">10</a>
            <span class="morepage">…</span>
            <a onclick="return goodList.Next()" class="next" href="#" rel="2">&nbsp;</a>-->
</div>