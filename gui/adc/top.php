<!DOCTYPE html>
<html lang="en" class="app">
<head>
<meta charset="utf-8" />
<title>Web Application</title>
<meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="stylesheet" href="/res/css/app.v2.css" type="text/css" />
<link rel="stylesheet" href="/res/css/landing.css" type="text/css" />
<link rel="stylesheet" href="/res/css/font.css" type="text/css" cache="false" />
</head>
<body>
<section class="vbox">
<header class="bg-dark dk header navbar navbar-fixed-top-xs">
	<div class="navbar-header aside-md"> 
		<a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav"> 
		<i class="fa fa-bars"></i> </a> <a href="#" class="navbar-brand" data-toggle="fullscreen">
		<img src="/res/images/logo_r.png" class="m-r-sm">WOMS管理员平台</a> 
		<a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user"> 	<i class="fa fa-cog"></i> </a> 
	</div>
	<ul class="nav navbar-nav navbar-right hidden-xs nav-user">
		<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?=$_SESSION['adcuser']?> <b class="caret"></b> </a>
			<ul class="dropdown-menu animated fadeInRight"><span class="arrow top"></span>
				<li> <a href="/adc/logout">退出</a> </li>
			</ul>
		</li>
	</ul>
</header>
  <!-- header end -->

<section>
	<section class="hbox stretch"> <!-- .aside -->
		<aside class="bg-light lter b-r aside-md hidden-print" id="nav">
			<section class="vbox">
				<section class="w-f scrollable">
					<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333"> <!-- nav -->
						<nav class="nav-primary hidden-xs">
							<ul class="nav text-md">
								<li> <a href="#layout" > <i class="fa fa-paw icon"> <b class="bg-dark"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>商家管理</span> </a>
									<ul class="nav lt">
										<li> <a href="/adc/allcooperater" class="allcooperater"> <i class="fa fa-angle-right"></i> <span></span>所有商家 </a> </li>
										<li> <a href="/adc/corpgrant" class="corpgrant"> <i class="fa fa-angle-right"></i> <span>商家接入点授权</span> </a> </li>
										<li> <a href="/adc/apgrant" class="apgrant"> <i class="fa fa-angle-right"></i> <span></span> 接入点用户授权</a> </li>
									</ul>
								</li> 							
								<li> <a href="#layout" > <i class="fa fa-envelope icon"> <b class="bg-dark"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>短信内容管理</span> </a>
									<ul class="nav lt">
										<li> <a href="/adc/smscontentlist" class="smscontentlist"> <i class="fa fa-angle-right"></i> <span></span> 短信内容查看</a> </li>
										<li> <a href="/adc/smscontentgrant" class="smscontentgrant"> <i class="fa fa-angle-right"></i> <span>短信内容审核</span> </a> </li>
										<li> <a href="/adc/smscontentadd" class="smscontentadd"> <i class="fa fa-angle-right"></i> <span>添加短信模版</span> </a> </li>
									</ul>
								</li>
								<li> <a href="#layout" > <i class="fa fa-send icon"> <b class="bg-dark"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>认证模版管理</span> </a>
									<ul class="nav lt">
										<li> <a href="/adc/templatelist" class="templatelist"> <i class="fa fa-angle-right"></i> <span></span>商家模板列表 </a> </li>
										<li> <a href="/adc/templategrant" class="templategrant"> <i class="fa fa-angle-right"></i> <span></span>商家模板审核 </a> </li>
									</ul>
								</li> 	
								<!-- <li> <a href="#layout" > <i class="fa fa-users icon"> <b class="bg-dark"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>信息统计</span> </a>
									<ul class="nav lt">
										<li> <a href="/adc/allusers" class="allusers"> <i class="fa fa-angle-right"></i> <span></span>所有用户 </a> </li>
									</ul>
								</li>	 -->
								<li> <a href="#layout" > <i class="fa fa-windows icon"> <b class="bg-dark"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>系统信息管理</span> </a>
									<ul class="nav lt">
										<li> <a href="/adc/systemconfig" class="systemconfig"> <i class="fa fa-angle-right"></i> <span></span>系统参数 </a> </li>
										<li> <a href="/adc/systemnotice" class="systemnotice"> <i class="fa fa-angle-right"></i> <span></span> 系统公告</a> </li>
									</ul>
								</li>																					                  
							</ul>
						</nav>
					</div>
				</section>
				<footer class="footer lt hidden-xs b-t b-light h6" style="min-height: 30px; text-align: center; line-height: 30px;">
					<i class="fa fa-gg m-r-sm" aria-hidden="true"></i>系统版本 1.0.0.8
				</footer>					
			</section>
		</aside>