<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clearfix">
			<div class="row m-t-sm">
				<div class="col-sm-8 m-b-xs">
					<div class="btn-group">		
						<a class="btn btn-sm btn-default m-r-sm refresh" ><i class="fa fa-refresh"></i></a>
						<a class="btn btn-sm btn-default m-r-sm"  href="/corp/addauthsmstmp"><i class="fa fa-plus m-r-sm"></i>添加</a>
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
		<section class="scrollable wrapper">
			<table class="table table-striped " id="table">
			</table>
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>	
<link rel="stylesheet" href="/res/js/esgrid/themes/bootstrap/easyui.css" type="text/css" />
<link rel="stylesheet" href="/res/js/esgrid/themes/icon.css" type="text/css" />
<script src="/res/js/esgrid/jquery.easyui.min.js"></script>
<script type="text/javascript">
G.set("remove_url", "/corp/tmpoptions/smstmpremove")
	.set('nav_name', 'authsmstmp');

$(function (){
	$('.btn-search').on('click', function (){
		$('#table').datagrid('load',{sc: $('.searchbox').val()});
	});
	initTables();
}); 
function initTables (){
	var columns=[
		{field:'id', title:'ID', align:'center', hidden:true},
		{field:'content', title:'短信内容', width:80, align:'center'},
		{field:'valid', title:'状态', width:20,align:'center',formatter:function (value, row, index) {
			var value = value == '0'?'<a class="text-warning font-bold">未审核</a>':(value=="1"?'<a class="text-info font-bold">审核通过</a>':'<a class="text-danger font-bold">审核不通过</a>');
			return value;
		}},
		{field:'time', title:'提交时间', width:30,align:'center'},
		{field:'more', title:'', width:10,align:'center', formatter:function (value, row, index) {
			return '<a class="grid_item_remove th-sortable" title="删除"><i class="fa fa-trash text-dark"></i><i class="fa fa-ban text-danger hidden"></i></a>';}}
	];
	createTable($('#table'), '/corp/tmpoptions/getsmstmplist', columns, true);
}
</script>
</body>
</html>