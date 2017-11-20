<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="en" class="app">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!--ie使用edge渲染模式-->
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
<meta name="renderer" content="webkit"><!--360渲染模式-->
<meta name="format-detection" content="telephone=notelphone=no, email=no" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<meta name="apple-touch-fullscreen" content="yes"/><!-- 是否启用 WebApp 全屏模式，删除苹果默认的工具栏和菜单栏 -->
<meta name="apple-mobile-web-app-status-bar-style" content="black"/><!-- 设置苹果工具栏颜色:默认值为 default，可以定为 black和 black-translucent-->
<meta http-equiv="Cache-Control" content="no-siteapp" /><!-- 不让百度转码 -->
<meta name="HandheldFriendly" content="true"><!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
<meta name="MobileOptimized" content="320"><!-- 微软的老式浏览器 -->
<meta name="screen-orientation" content="portrait"><!-- uc强制竖屏 -->
<meta name="x5-orientation" content="portrait"><!-- QQ强制竖屏 -->
<meta name="browsermode" content="application"><!-- UC应用模式 -->
<meta name="x5-page-mode" content="app"><!-- QQ应用模式 -->
<meta name="msapplication-tap-highlight" content="no"><!-- windows phone 点击无高光 -->
<meta charset="utf-8" />
<title>Web Application</title>
<meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="stylesheet" type="text/css" href="/res/js/jqslider/slider.css">
<style type="text/css">
body,html{
	width: 100%;
	height: 100%;
}
.ad{
    position: absolute;
    background: #fff;
    font-size: 10px;
    opacity: 0.5;
    padding: 2px 5px;
    top:5px;
    right: 55px;
    z-index: 10000;
}
</style>
</head>
<body>
    <div class="ad">广告倒计时:<span class="time">20</span>秒</div>
    <div class="js-silder">
        <div class="silder-scroll">
            <div class="silder-main">
            <?php 
                foreach ($phonead as $row) {
                    if(!empty($row)){
                        echo '<div class="silder-main-img">';
                        echo '<img src="/res/images/template/'.$row.'" alt="" class="img">';
                        echo '</div>';                        
                    }
                }
            ?>
            </div>
        </div>
    </div>    
    <p class="uri" style="display: none"><?=$url?></p>
</body>
<script type="text/javascript" src="/res/js/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/res/js/jqslider/slider.js"></script>
<script type="text/javascript">
$(function (){
    var si = setInterval(function (){
        var time = parseInt($('.time').html());
        if(time == 0){
            clearInterval(si);
            var uri = $('.uri').html();
            window.location.href = uri;
        }else{
            time--;
            $('.time').html(time);            
        }

    }, 1000);  

	$(function (){
		$(".js-silder").silder({
            auto: true,//自动播放，传入任何可以转化为true的值都会自动轮播
            speed: 20,//轮播图运动速度
            sideCtrl: true,//是否需要侧边控制按钮
            bottomCtrl: true,//是否需要底部控制按钮
            defaultView: 0,//默认显示的索引
            interval: 3000,//自动轮播的时间，以毫秒为单位，默认3000毫秒
            activeClass: "active",//小的控制按钮激活的样式，不包括作用两边，默认active
        });
	});	
    $('.img').css('height', $(window).height());
    $('.img').css('width', $(window).width());    
});
</script>
</html>

