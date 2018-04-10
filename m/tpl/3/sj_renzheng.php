<header class="top_header">商家版认证</header>
	<form name="USERINFO2" id="USERINFO2" action="" method="post">
  
<div class="real_box">
  <dl>
   <dt>店铺名称</dt>
   <dd><input name="s_name" id="s_name" type="text" value="<?php  echo $rts['s_name'];?>" placeholder="请填写店铺名称"></dd>
  </dd>
  </dl>
  
  <dl>
   <dt>所在行业</dt>
   <dd><input name="s_hangye" id="s_hangye"   type="text"  value="<?php  echo $rts['s_hangye'];?>" placeholder="请填写所在行业" ></dd>
  </dl>

  <dl>
   <dt>店铺地址</dt>
   <dd><input name="s_address" id="s_address"   type="text" value="<?php  echo $rts['s_address'];?>" placeholder="请填写店铺地址" ></dd>
  </dl>
  
    <div style="border-bottom:1px solid #e6e6e6;overflow:hidden;padding:10px;display:-webkit-box;display:box;display:-moz-box;line-height:35px;font-size:16px;font-weight:bolder;">    开通流程
    </div>
  
  <div style="border-bottom:1px solid #e6e6e6;overflow:hidden;padding:10px;display:-webkit-box;display:box;display:-moz-box;line-height:35px;"> 
 <label style="padding-left:20px;font-size:16px;"><input onclick="y_zz(this.value)"  name="zhizhao" type="radio" value="1" checked="checked" />有营业执照 </label> 
 <label style="padding-left:20px;font-size:16px;"><input onclick="n_zz(this.value)"  name="zhizhao" type="radio" value="0" />无营业执照 </label>  
 </div>
 <input type="hidden" id="s_zz" name="s_zz" value="1"/>
  
  <div id="ye_s">
  <dl>
   <dt>店铺营业执照原件照片</dt>
   <dd>

<input type="hidden" class="button" name="s_y_zhizhao_img" id="imageUp_1_1"  value="<? echo $rts['s_y_zhizhao_img'];?>">
   <img src="<?php  echo $rts['s_y_zhizhao_img']? "/".$rts['s_y_zhizhao_img']:"img/file.png";?>" id="s_y_zhizhao" name="s_y_zhizhao" class="primary photo" style="width:90%; max-height:200px;">
 
   </dd>
  </dl>
  
  <dl>
   <dt>申请人与店铺门头合照（能看到店铺名）</dt>
   <dd>


   <input type="hidden" class="button" name="s_y_mentou_img" id="imageUp_1_2"  value="<? echo $rts['s_y_mentou_img'];?>">
    <img src="<?php  echo $rts['s_y_mentou_img']? "/".$rts['s_y_mentou_img']:"img/file.png";?>" id="s_y_mentou" name="s_y_mentou" class="primary photo" style="width:90%; max-height:200px;">
   </dd>
  </dl>
  
 
  <dl>
   <dt>店铺内景照</dt>
   <dd>
 
       <input type="hidden" class="button" name="s_y_neijing_img" id="imageUp_1_3"  value="<? echo $rts['s_y_neijing_img'];?>">
       <img src="<?php  echo $rts['s_y_neijing_img']? "/".$rts['s_y_neijing_img']:"img/file.png";?>" id="s_y_neijing" name="s_y_neijing" class="primary photo" style="width:90%; max-height:200px;">
   </dd>
  </dl>
  
   <dl>
   <dt>营业执照法人身份证原件照片-正面</dt>
   <dd>
 
       <input type="hidden" class="button" name="s_y_card_front_img" id="imageUp_1_4"  value="<? echo $rts['s_y_card_front_img'];?>">
       <img src="<?php  echo $rts['s_y_card_front_img']? "/".$rts['s_y_card_front_img']:"img/file.png";?>" id="s_y_card_front" name="s_y_card_front" class="primary photo" style="width:90%; max-height:200px;">
   </dd>
  </dl>
  
   <dl>
   <dt>营业执照法人身份证原件照片-反面</dt>
   <dd>
 
       <input type="hidden" class="button" name="s_y_card_back_img" id="imageUp_1_5"  value="<? echo $rts['s_y_card_back_img'];?>">
       <img src="<?php  echo $rts['s_y_card_back_img']? "/".$rts['s_y_card_back_img']:"img/file.png";?>" id="s_y_card_back" name="s_y_card_back" class="primary photo" style="width:90%; max-height:200px;">
   </dd>
  </dl>
  		   
  	</div>
    
    
    
    <!--无营业执照-->
    <div id="no_s" style="display:none;">
  <dl>
   <dt>申请人身份证原件照片-正面</dt>
   <dd>

<input type="hidden" class="button" name="s_n_card_front_img" id="imageUp_2_1"  value="<? echo $rts['s_n_card_front_img'];?>">
   <img src="<?php  echo $rts['s_n_card_front_img']? "/".$rts['s_n_card_front_img']:"img/file.png";?>" id="s_n_card_front" name="s_card_front" class="primary photo" style="width:90%; max-height:200px;">
 
   </dd>
  </dl>
  <dl>
   <dt>申请人身份证原件照片-反面</dt>
   <dd>

<input type="hidden" class="button" name="s_n_card_back_img" id="imageUp_2_2"  value="<? echo $rts['s_n_card_back_img'];?>">
   <img src="<?php  echo $rts['s_n_card_back_img']? "/".$rts['s_n_card_back_img']:"img/file.png";?>" id="s_n_card_back" name="s_n_card_back" class="primary photo" style="width:90%; max-height:200px;">
 
   </dd>
  </dl>
  
  
  <dl>
   <dt>申请人与店铺门头合照（能看到店铺名）</dt>
   <dd>


   <input type="hidden" class="button" name="s_n_mentou_img" id="imageUp_2_3"  value="<? echo $rts['s_n_mentou_img'];?>">
    <img src="<?php  echo $rts['s_n_mentou_img']? "/".$rts['s_n_mentou_img']:"img/file.png";?>" id="s_n_mentou" name="s_n_mentou" class="primary photo" style="width:90%; max-height:200px;">
   </dd>
  </dl>
  
 
  <dl>
   <dt>店铺内景照</dt>
   <dd>
 
       <input type="hidden" class="button" name="s_n_neijing_img" id="imageUp_2_4"  value="<? echo $rts['s_n_neijing_img'];?>">
       <img src="<?php  echo $rts['s_n_neijing_img']? "/".$rts['s_n_neijing_img']:"img/file.png";?>" id="s_n_neijing" name="s_n_neijing" class="primary photo" style="width:90%; max-height:200px;">
   </dd>
  </dl>
  
   <dl>
   <dt>申请人手持身份证正面在店铺收银台照片</dt>
   <dd>
 
       <input type="hidden" class="button" name="s_n_card_hand_img" id="imageUp_2_5"  value="<? echo $rts['s_n_card_hand_img'];?>">
       <img src="<?php  echo $rts['s_n_card_hand_img']? "/".$rts['s_n_card_hand_img']:"img/file.png";?>" id="s_n_card_hand" name="s_n_card_hand" class="primary photo" style="width:90%; max-height:200px;">
   </dd>
  </dl>
  

  		   
  	</div>
    
    
      
  			   
</div>


<div class="real_sub" id="save">提交审核</div>
</form>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

<script>
   function y_zz(v){
	document.getElementById("ye_s").style.display = "block";
	document.getElementById("no_s").style.display = "none"
     document.getElementById("s_zz").value = v; 
	
alert(zz);
	}
	
	function n_zz(v){
	document.getElementById("ye_s").style.display = "none";
	document.getElementById("no_s").style.display = "block"
	 document.getElementById("s_zz").value = v;
	
alert(zz);
	}

</script>
<script>
  
 wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
	
	"chooseImage",
         
          "uploadImage",
       
      // 所有要调用的 API 都要加到这个列表中
    ]
  });

    // 5.1 拍照、本地选图
    var imagesA = {
        localId: [],
        serverId: []
    };

    var imagesB = {
        localId: [],
        serverId: []
    };

    var imagesC = {
        localId: [],
        serverId: []
    };
	 var imagesD = {
        localId: [],
        serverId: []
    };

    var imagesE = {
        localId: [],
        serverId: []
    };

    var imagesF = {
        localId: [],
        serverId: []
    };
	 var imagesG = {
        localId: [],
        serverId: []
    };

    var imagesH = {
        localId: [],
        serverId: []
    };

    var imagesI = {
        localId: [],
        serverId: []
    };
	 var imagesJ = {
        localId: [],
        serverId: []
    };
	

    document.querySelector('#s_y_zhizhao').onclick = function () {
        wx.chooseImage({
			
            count: 1, // 默认9
            //sizeType: ['original','compressed'], // 可以指定是原图还是压缩图，默认二者都有
			sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesA.localId = res.localIds;
                document.getElementById('s_y_zhizhao').src = imagesA.localId;

                $('#imageUp_1_1').val(imagesA.localId);
            upload(imagesA.localId,'imageUp_1_1');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
		
    };

    document.querySelector('#s_y_mentou').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
           sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesB.localId = res.localIds;
                document.getElementById('s_y_mentou').src = imagesB.localId;
                $('#imageUp_1_2').val(imagesB.localId);
                upload(imagesB.localId,'imageUp_1_2');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };

  
	
	
	  document.querySelector('#s_y_neijing').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
          sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesC.localId = res.localIds;
                document.getElementById('s_y_neijing').src = imagesC.localId;
                $('#imageUp_1_3').val(imagesC.localId);
                upload(imagesC.localId,'imageUp_1_3');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };


 document.querySelector('#s_y_card_front').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
          sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesD.localId = res.localIds;
                document.getElementById('s_y_card_front').src = imagesD.localId;
                $('#imageUp_1_4').val(imagesD.localId);
                upload(imagesD.localId,'imageUp_1_4');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };


 document.querySelector('#s_y_card_back').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
          sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesE.localId = res.localIds;
                document.getElementById('s_y_card_back').src = imagesE.localId;
                $('#imageUp_1_5').val(imagesE.localId);
                upload(imagesE.localId,'imageUp_1_5');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };



 document.querySelector('#s_n_card_front').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
          sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesF.localId = res.localIds;
                document.getElementById('s_n_card_front').src = imagesF.localId;
                $('#imageUp_2_1').val(imagesF.localId);
                upload(imagesF.localId,'imageUp_2_1');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };


 document.querySelector('#s_n_card_back').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
          sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesG.localId = res.localIds;
                document.getElementById('s_n_card_back').src = imagesG.localId;
                $('#imageUp_2_2').val(imagesG.localId);
                upload(imagesG.localId,'imageUp_2_2');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };


 document.querySelector('#s_n_mentou').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
          sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesH.localId = res.localIds;
                document.getElementById('s_n_mentou').src = imagesH.localId;
                $('#imageUp_2_3').val(imagesH.localId);
                upload(imagesH.localId,'imageUp_2_3');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };


 document.querySelector('#s_n_neijing').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
          sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesI.localId = res.localIds;
                document.getElementById('s_n_neijing').src = imagesI.localId;
                $('#imageUp_2_4').val(imagesI.localId);
                upload(imagesI.localId,'imageUp_2_4');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };


 document.querySelector('#s_n_card_hand').onclick = function () {
        wx.chooseImage({
            count: 1, // 默认9
          sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                imagesJ.localId = res.localIds;
                document.getElementById('s_n_card_hand').src = imagesJ.localId;
                $('#imageUp_2_5').val(imagesJ.localId);
                upload(imagesJ.localId,'imageUp_2_5');
            },
            cancel: function (res) {
                alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    };

    function upload(localIds,inputid){
        // 上传照片
        wx.uploadImage({
            localId: '' + localIds,
            isShowProgressTips: 1,
            success: function(res) {
                serverId = res.serverId;
                $("#"+inputid).val(serverId); // 把上传成功后获取的值附上
            }
        });
    }
    


    $('#save').click(function () {
		
		var s_zz = document.getElementById("s_zz").value;
		
        var s_name = document.getElementById('s_name').value;
        var s_hangye = document.getElementById('s_hangye').value;
        var s_address = document.getElementById('s_address').value;
		
		
        var s_y_zhizhao_img = document.getElementById('imageUp_1_1').value;
        var s_y_mentou_img = document.getElementById('imageUp_1_2').value;
        var s_y_neijing_img = document.getElementById('imageUp_1_3').value;
		 var s_y_card_front_img = document.getElementById('imageUp_1_4').value;
		  var s_y_card_back_img = document.getElementById('imageUp_1_5').value;
		  
		  
      
		 var s_n_card_front_img = document.getElementById('imageUp_2_1').value;
		  var s_n_card_back_img = document.getElementById('imageUp_2_2').value;
		    var s_n_mentou_img = document.getElementById('imageUp_2_3').value;
              var s_n_neijing_img = document.getElementById('imageUp_2_4').value;
			   var s_n_card_hand_img = document.getElementById('imageUp_2_5').value;
		
		
			
		
	

			  
        if (s_name.length < 1)
        {
            alert('请填写店铺名称！');
            return false;
        }
		if (s_hangye.length < 1)
        {
            alert('请填写所在行业！');
            return false;
        }
	    if (s_address.length < 1)
        {
            alert('请填写店铺地址！');
            return false;
        }
		
        if(s_zz == 1){
			
		 if (s_y_zhizhao_img == '')
        {
            alert('请上传店铺营业执照原件照片！');
            return false;
        }
         if (s_y_mentou_img == '')
        {
            alert('请上传申请人与店铺门头合照（能看到店铺名）！');
            return false;
        }
		  if (s_y_neijing_img == '')
        {
            alert('请上传店铺内景照！');
            return false;
        }
		  if (s_y_card_front_img == '')
        {
            alert('请上传营业执照法人身份证原件照片-正面！');
            return false;
        }
		  if (s_y_card_back_img == '')
        {
            alert('请上传营业执照法人身份证原件照片-反面！');
            return false;
        }
      
        }else if(s_zz == 0){
			
			  if (s_n_card_front_img == '')
        {
            alert('请上传申请人身份证原件照片-正面！');
            return false;
        }
		  if (s_n_card_back_img == '')
        {
            alert('请上传申请人身份证原件照片-反面！');
            return false;
        }
			
         if (s_n_mentou_img == '')
        {
            alert('请上传申请人与店铺门头合照（能看到店铺名）！');
            return false;
        }
		  if (s_n_neijing_img == '')
        {
            alert('请上传店铺内景照！');
            return false;
        }
		
		 if (s_n_card_hand_img == '')
        {
            alert('请上传申请人手持身份证正面在店铺收银台照片！');
            return false;
        }
			
			}
		

				createwindow();
		
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'update_user_shop',s_zz:s_zz,s_name:s_name,s_hangye:s_hangye,s_address:s_address,s_y_zhizhao_img:s_y_zhizhao_img,s_y_mentou_img:s_y_mentou_img,s_y_neijing_img:s_y_neijing_img,s_y_card_front_img:s_y_card_front_img,s_y_card_back_img:s_y_card_back_img,s_n_card_front_img:s_n_card_front_img,s_n_card_back_img:s_n_card_back_img,s_n_mentou_img:s_n_mentou_img,s_n_neijing_img:s_n_neijing_img,s_n_card_hand_img:s_n_card_hand_img},function(data){ 
		alert(data);
			removewindow();
			if(data == "成功提交申请，请等待审核"){
			WeixinJSBridge.call('closeWindow');
}
		});
	
	
	
    });
	
	
	    


</script>
