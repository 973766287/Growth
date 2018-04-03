<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/24/css.css" media="all" />
<link  type="text/css"  rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/3/style/index_style.css" />
<link  type="text/css"  rel="stylesheet" href="<?php echo ADMIN_URL; ?>tpl/3/gift.css" />
<style type="text/css">
    .indexcon{ text-align:center}
    .indexcon img{ max-width:100%;}
    .footffont{ line-height:24px; }
    .footffontbox{  text-align:center; line-height:24px;}
    .gototop{height:32px; line-height:32px; position:fixed; bottom:65px; left:0px; right:0px; padding-right:5px; padding-left:5px; display:block}
    .pw2{background-color: #fff;}
    .pw{
        border: 1px solid #ddd;
        border-radius: 5px; margin-top:0.1rem;
        padding-left:5px; padding-right:5px;
        -moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
        -webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
        -khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
        border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
    }
    #main .indexcon img{ float:left; max-width:100%; margin:0px auto}
</style>

<div class="gift_head"><h3></h3><a href="<?php echo ADMIN_URL; ?>bm.php?act=baoming&id=<?php echo $rt['pinfo']['id'];?>">返回</a></div>
<div class="giftinfo_ct">

    <?php
    echo $rt['pinfo']['content'];
    ?>
 
</div>
