<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="hbox stretch">
		<aside>
			<section class="vbox">			
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
					<!-- other button -->
						<div class="col-sm-8 m-b-xs"> 
							<div class="btn-group">		
								<a class="btn btn-sm btn-default m-r-sm refresh" ><i class="fa fa-refresh"></i></a>
								<a class="btn btn-sm btn-default m-r-sm"  href="/corp/addblackitem"><i class="fa fa-plus m-r-sm"></i>添加</a>
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
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>	
<link rel="stylesheet" href="/res/js/esgrid/themes/bootstrap/easyui.css" type="text/css" />
<link rel="stylesheet" href="/res/js/esgrid/themes/icon.css" type="text/css" />
<script src="/res/js/esgrid/jquery.easyui.min.js"></script>
<script type="text/javascript">
G.set("remove_url", "/corp/wboptions/blackremove")
	.set('nav_name', 'authblacklist')
	.set('edit_url', '/corp/editblackitem');

$(function (){
	$('.btn-search').on('click', function (){
		$('#table').datagrid('load',{sc: $('.searchbox').val()});
	});
	initTables();
}); 
function initTables (){
	var columns=[
		{field:'id', title:'ID', align:'center', hidden:true},
		{field:'bname', title:'	黑名单名', width:40, align:'center'},
		{field:'type', title:'黑名单类型',width:30, align:'center'},
		{field:'apname', title:'所属接入点', width:30,align:'center'},
		{field:'time', title:'创建时间',width:30, align:'center'},
		{field:'validtime', title:'有效时间',width:30, align:'center'},
		{field:'more', title:'', width:10,align:'center', formatter:function (value, row, index) {
			return '<a class="grid_item_edit th-sortable" title="编辑"><i class="fa fa-pencil text-dark"></i></a>';
		}},
		{field:'more1', title:'', width:10,align:'center', formatter:function (value, row, index) {
			return '<a class="grid_item_remove th-sortable" title="删除"><i class="fa fa-trash text-dark"></i><i class="fa fa-ban text-danger hidden"></i></a>';
		}},
	];
	createTable($('#table'), '/corp/wboptions/getblacklist', columns, true);
}
</script>
</body>
</html>