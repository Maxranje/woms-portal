<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

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
		<img src="/res/images/logo.png" class="m-r-sm">WOMS</a> 
		<a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user"> 	<i class="fa fa-cog"></i> </a> 
	</div>
	<ul class="nav navbar-nav navbar-right hidden-xs nav-user">
		<li class="hidden-xs"> <a href="#" class="dropdown-toggle dk" data-toggle="dropdown"> <i class="fa fa-bell"></i> <span class="badge badge-sm up bg-danger m-l-n-sm"><i class="fa fa-exclamation" aria-hidden="true"></i></span> </a>
			<section class="dropdown-menu aside-xl">
				<section class="panel bg-white">
					<header class="panel-heading b-light bg-light"> <strong>系统公告</strong> </header>
					<div class="list-group list-group-alt animated fadeInRight"> 
					</div>
					<footer class="panel-footer text-sm"> <a href="#notes" data-toggle="class:show animated fadeInRight">更多</a> </footer>
				</section>
			</section>
		</li>
		<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="thumb-sm avatar pull-left"> <img src="/res/images/corplogo/<?=$_SESSION['corplogo']?>"> </span> <?=$_SESSION['corpname']?> <b class="caret"></b> </a>
			<ul class="dropdown-menu animated fadeInRight"><span class="arrow top"></span>
				<li> <a href="/corp/setting">Settings</a> </li>
				<li class="divider"></li>
				<li> <a href="/corp/logout">Logout</a> </li>
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
								<li> <a href="/corp/manageval" class="manageval"> <i class="fa fa-user-plus icon"> <b class="bg-dark"></b> </i> <span>帐号管理</span> </a> </li>
								<li> <a href="/corp/authstatistics" class="authstatistics"> <i class="fa fa-line-chart icon"> <b class="bg-dark"></b> </i> <span>统计信息</span> </a> </li>
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
					<i class="fa fa-gg m-r-sm" aria-hidden="true"></i>系统版本 1.0.0.3
				</footer>				
			</section>
		</aside>

<section id="content">
	<section class="hbox stretch">
		<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							<div class="btn-group">		
								<a class="btn btn-sm btn-default m-r-sm refresh" ><i class="fa fa-refresh"></i></a>
								<a class="btn btn-sm btn-default m-r-sm " href="/corp/addap"><i class="fa fa-plus m-r-sm"></i>添加</a>
							</div>
						</div>
						<div class="col-sm-4 m-b-xs">
							<div class="input-group">
								<input type="text" class="input-sm form-control searchbox" placeholder="Search">		
								<span class="input-group-btn"><button class="btn btn-sm btn-default btn-search" type="button"><i class="fa fa-search"></i></button></span> 	
							</div>
						</div>
					</div>
				</header>
				
				<!-- device table --> 
				<section class="scrollable wrapper">
					<?php 
					if( isset($failed_reson) ) {
						echo '<p class="padder-v m-l-n m-r-n bg-warning lter text-center"><span class="text-danger font-bold m-t-sm">'.$failed_reson.'</span></p>';
					} 
					?>
					<table  id="table">
					</table>
				</section>
			</section>
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
</section></section></section>

<div class="modal fade" id="tip-msg">
	<div class="modal-dialog"><div class="modal-content">
	<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title"> Message</h4></div>	
    <div class="modal-body">
		<p class="text-center wrapper dialog-info m-t-n"></p>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">关闭</button>
		<a class="btn btn-info btn-sm return-list d-n" href="">返回列表</a>
	</div>	
</div></div></div>

<div class="modal fade" id="remove"><div class="modal-dialog"><div class="modal-content">
	<div class="modal-header"><h4 class="modal-title">删除</h4></div>
		<div class="modal-body"><p class="text-danger">是否确定删除?一旦删除无法恢复</p></div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default text-mt" data-dismiss="modal">关闭</button>
			<button type="button" class="btn btn-info text-mt remove_submit">确定</button>
		</div>
	</div>
</div></div>
<script src="/res/js/jquery/jquery-1.11.2.min.js"></script>
<script src="/res/js/app.min.js"></script>
<script src="/res/js/script/common.js"></script>
<script src="/res/js/script/global.js"></script>
<script type="text/javascript">
$(function (){
	$('.'+G.get('nav_name')).parents('li').addClass('active');
});

</script>


<link rel="stylesheet" href="/res/js/esgrid/themes/bootstrap/easyui.css" type="text/css" />
<link rel="stylesheet" href="/res/js/esgrid/themes/icon.css" type="text/css" />
<script src="/res/js/esgrid/jquery.easyui.min.js"></script>
<script type="text/javascript">
G.set("remove_url", "/corp/apoptions/apremove")
	.set('nav_name', 'aplist');

$(function (){

	$('.btn-search').on('click', function (){
		$('#table').datagrid('load',{sc: $('.searchbox').val()});
	});
	
	$(document).on('click','.apconfig', function (){
		let filed = $(this).parents('tr').find('.datagrid-cell-c1-id').html();
		let map = new Map().set('id',filed);
		zy.submit_form_action ("/corp/apauthconf", map);
	});
	
	initTables();

}); 
function initTables (){
	var columns=[
		{field:'id', title:'ID', align:'center', hidden:true},
		{field:'apname', title:'接入点名称', width:40, align:'center',formatter:function(value, row, index){
			return '<a class="text-info th-sortable apconfig font-bold" >'+value+'</a>';}},

		{field:'state', title:'状态', width:20,align:'center', formatter:function (value, row, index) {
			if(value == "nomal"){
				return '<a href="#" title="正常运行"><i class="fa fa-circle text-success"></i></a>';	
			}else{
				return '<a href="#" title="异常状态,'+value+'"><i class="fa fa-circle text-danger"></i></a>';	
			}
		}},
		{field:'usedgrant', title:'授权/IP',width:20, align:'center'},
		{field:'usedcount', title:'累计认证数',width:20, align:'center'},
		{field:'ollist', title:'当前在线',width:40, align:'center', formatter:function (value, row, index) {
			return value+'人 <a href="javascript:;" onclick="showstatistics('+row.id+')" class="text-success">[列表]</a>';}},
		{field:'hearttime', title:'最后同步',width:60, align:'center', formatter:function (value, row, index) {
			return '<i class="fa fa-globe text-success m-r-sm"></i> ' + value;}},
		{field:'protocol', title:'认证类型', width:30,align:'center'},
		{field:'more2', title:'', width:10,align:'center', formatter:function (value, row, index) {
			return '<a href="/corp/apinfoedit?apid='+row.id+'" title="编辑接入点基本信息"><i class="fa fa-info text-dark"></i></a>';
		}},
		{field:'more3', title:'', width:10,align:'center', formatter:function (value, row, index) {
			return '<a class="grid_item_remove th-sortable" title="删除"><i class="fa fa-trash text-dark"></i><i class="fa fa-ban text-danger hidden"></i></a>';
		}}
	];
	createTable($('#table'), '/corp/apoptions/getaplist', columns, true);
}

function showstatistics (id){
	let map = new Map();
	map.set ('id', id);
	zy.submit_form_action ('/corp/authstatistics', map);
}

</script>
</body>
</html>
