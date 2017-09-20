<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en" class="app">
<head>
<meta charset="utf-8" />
<title>Web Application</title>
<meta name="renderer" content="webkit" />
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
		<img src="/res/images/logo_r.png" class="m-r-sm">WOMS</a> 
		<a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user"> 	<i class="fa fa-cog"></i> </a> 
	</div>
	<ul class="nav navbar-nav navbar-right hidden-xs nav-user">
		<li class="hidden-xs"> <a href="javascript:;" class="dropdown-toggle dk btn-notice" data-toggle="dropdown"> <i class="fa fa-bell"></i> <span class="badge badge-sm up bg-danger m-l-n-sm"><i class="fa fa-exclamation" aria-hidden="true"></i></span> </a>
			<section class="dropdown-menu aside-xl">
				<section class="panel bg-white">
					<header class="panel-heading b-light bg-light"> <strong>系统公告</strong> </header>
					<div class="list-group list-group-alt animated fadeInRight"> 
					</div>
					<footer class="panel-footer text-sm"> <a href="#notes" data-toggle="class:show animated fadeInRight"></a> </footer>
				</section>
			</section>
		</li>
		<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="thumb-sm avatar pull-left"> <img src="/res/images/corplogo/<?=$_SESSION['corplogo']?>"> </span> <?=$_SESSION['corpname']?> <b class="caret"></b> </a>
			<ul class="dropdown-menu animated fadeInRight"><span class="arrow top"></span>
				<li> <a href="/corp/setting">个人资料</a> </li>
				<li class="divider"></li>
				<li> <a href="/corp/logout">退出</a> </li>
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
							<ul class="nav text-md navgater">
								<li> <a href="/corp" class="dashboarts"> <i class="fa fa-dashboard icon"> <b class="bg-dark"></b> </i> <span>仪表盘</span> </a> </li>
								<li> <a href="/corp/aplist" class="aplist"> <i class="fa fa-wifi icon"> <b class="bg-dark"></b> </i> <span>接入列表</span> </a> </li>
								<li> <a href="/corp/authstatistics" class="authstatistics"> <i class="fa fa-line-chart icon"> <b class="bg-dark"></b> </i> <span>统计信息</span> </a> </li>
								<li> <a href="#layout"> <i class="fa fa-user-plus icon"> <b class="bg-dark"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>帐号管理</span> </a>
									<ul class="nav lt">
										<li> <a href="/corp/manageval" class="manageval"> <i class="fa fa-angle-right"></i> <span>系统账号</span> </a> </li>
										<li> <a href="/corp/authserverconf" class="authserverconf"> <i class="fa fa-angle-right"></i> <span>认证服务器</span> </a> </li>
									</ul>
								</li>								
								<li> <a href="#layout"> <i class="fa fa-list icon"> <b class="bg-dark"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>黑白名单</span> </a>
									<ul class="nav lt">
										<li> <a href="/corp/authblacklist" class="authblacklist"> <i class="fa fa-angle-right"></i> <span>黑名单</span> </a> </li>
										<li> <a href="/corp/authwhitelist" class="authwhitelist"> <i class="fa fa-angle-right"></i> <span>白名单</span> </a> </li>
									</ul>
								</li>
								<li> <a href="#layout" > <i class="fa fa-map icon"> <b class="bg-dark"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>模版配置</span> </a>
									<ul class="nav lt">
										<li> <a href="/corp/authtemplate" class="authtemplate"> <i class="fa fa-angle-right"></i> <span></span>认证模版配置 </a> </li>
										<li> <a href="/corp/authsmstmp" class="authsmstmp"> <i class="fa fa-angle-right"></i> <span>短信模版配置</span> </a> </li>
									</ul>
								</li>
								<li> <a href="/corp/authweixin" class="authweixin"> <i class="fa fa-weixin icon"> <b class="bg-dark"></b> </i> <span>微信配置</span> </a> </li>
							</ul>
						</nav>
					</div>
				</section>
				<footer class="footer lt hidden-xs b-t b-light h6" style="min-height: 30px; text-align: center; line-height: 30px;">
					<i class="fa fa-gg m-r-sm" aria-hidden="true"></i>系统版本 1.0.0.8
				</footer>				
			</section>
		</aside>
