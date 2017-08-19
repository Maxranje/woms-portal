<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en" class="app">
<head>
<meta charset="utf-8" />
<title><?=$tmp['title']?></title>
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
<meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="stylesheet" href="/res/css/app.v2.css" type="text/css" />
<link rel="stylesheet" href="/res/css/landing.css" type="text/css" />
<link rel="stylesheet" href="/res/css/font.css" type="text/css" cache="false" />
<link rel="stylesheet" href="/res/js/fuelux/fuelux.css" type="text/css" cache="false">
<link rel="stylesheet" type="text/css" href="/res/css/template.css">
<link rel="stylesheet" type="text/css" href="/res/js/jqslider/slider.css">
<style type="text/css">
html, body,section{ height: 100%; width: 100%; }
</style>
</head>
<body>
	<section>
		<?php
		if($tmp['type'] != '3'){
			$tp = empty($tmp['phone_bgpic']) ? "top.jpg" : $tmp['phone_bgpic'];
			echo '<div style="height:100%;" >';
			echo '<img src="/res/images/template/'.$tp.'" style="height: 100%; width: 100%;">';
			echo '<div>';
		}else{
			$tp = json_decode($tmp['phone_bgpic'], true);
			$tp[0] = empty($tp[0]) ? "top.jpg" : $tp[0];
			echo '<div class="js-silder"><div class="silder-scroll"><div class="silder-main">';
			foreach ($tp as $row) {
				if(!empty($row)){
					echo '<div class="silder-main-img">';
					echo '<img src="/res/images/template/'.$row.'" alt="" class="img">';
					echo '</div>';
				}
			}
			echo '</div></div></div>';
		}
		if($tmp['type'] == 2){
			echo '<div class="tmp-top-model"><span>'.$row['content'].'</span></div>';
		}
		if($tmp['type'] == 3){
			echo '<div class="tmp-center-model">';
		}else {
			echo '<div class="tmp-bt-model">';
		}
		?>
			<div class="auth">
				<form class="form-horizontal" method="post" style="z-index: 10000">
					<?php
					if ($authtype == "authuser") {
					?>
					<div class="input-group m-b"> 
						<span class="input-group-addon b-success bg-success"><i class="fa fa-user-plus"></i></span>
						<input type="text" class="form-control b-success input-sm" name="authname" placeholder="用户名">
					</div>
					<div class="input-group m-b"> 
						<span class="input-group-addon b-success bg-success"><i class="fa fa-key"></i></span>
						<input type="password" class="form-control b-success input-sm" name="authpass" placeholder="密码">
						<input type="hidden"  name="acctype" value="userlogin">
					</div>
					<button class="btn btn-success btn-block m-t-md m-b-md btn-sm">登录验证</button>
					<?php
					} else if($authtype == "authphone"){
					?>
					<div class="input-group m-b"> 
						<span class="input-group-addon b-info bg-info"><i class="fa fa fa-mobile fa-lg m-l-xs"></i></span>
						<input type="text" class="form-control b-info input-sm" name="authname" placeholder="手机号">
					</div>
					<div class="input-group m-b"> 
						<span class="input-group-addon b-info bg-info"><i class="fa fa-envelope"></i></span>
						<input type="text" class="form-control b-info input-sm" name="authpass" placeholder="短信验证码">
						<input type="hidden"  name="acctype" value="mobilevalidatelogin">
					</div>			
					<div class="row col-lg-12">
						<a class="btn btn-info btn-getcoude" href="javascript:;">获取验证码</a>	
						<button class="btn btn-default" type="submit">登录验证</button>
					</div>
					<?php
					} else {
					?>
					<input type="hidden"  name="acctype" value="login">
					<button class="btn btn-success btn-block m-t-md m-b-md btn-sm" type="submit">登录上网</button>
					<?php
					}
					?>
					
				</form>
				<div class="text-center errormsg">
					<?php if($state != "success") echo "<span class='text-danger font-bold'>".$reson."</span>";?>
				</div>	
			</div>						
		</div>
	</section>
</body>
<script src="/res/js/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/res/js/jqslider/slider.js"></script>
<script src="/res/js/app.min.js"></script>
<script type="text/javascript">
$(function (){
	$('.btn-getcoude').on('click', function (){
		if($(this).hasClass('disabled')){
			return ;
		}
		var number = $('input[name=authname]').val();
		if(number == ""){
			alert('请输入手机号');
			return ;
		}

		var uri = window.location.href;
		$.post(uri, {acctype:'mobileauthcode', phone:number}, function (data){
			if(data.state == "failed"){
				$('.errormsg').html("<span class='text-danger font-bold'>"+data.reson+"</span>");
				return ;
			}
			$('.btn-getcoude').addClass('disabled');
			var index = 120;
			var si = setInterval (function (){
				if(index == 0){
					clearInterval(si);
					$('.btn-getcoude').removeClass('disabled');
					$('.btn-getcoude').html("获取验证码");
				}				
				$('.btn-getcoude').html(index+"秒后重新获取");
				index--;
			}, 1000);
		}, "json");
	});

	$(".js-silder").silder({
        auto: true,//自动播放，传入任何可以转化为true的值都会自动轮播
        speed: 40,//轮播图运动速度
        sideCtrl: true,//是否需要侧边控制按钮
        bottomCtrl: false,//是否需要底部控制按钮
        defaultView: 0,//默认显示的索引
        interval: 3000,//自动轮播的时间，以毫秒为单位，默认3000毫秒
        activeClass: "active",//小的控制按钮激活的样式，不包括作用两边，默认active
    });
    $('.img').css('height', $(window).height());
    $('.img').css('width', $(window).width());       	
});



</script>
</html>

