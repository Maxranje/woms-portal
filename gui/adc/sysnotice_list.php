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
						<div class="col-sm-8 m-b-xs">
							<div class="btn-group">		
								<a class="btn btn-sm btn-default m-r-sm refresh" ><i class="fa fa-refresh"></i></a>
								<a class="btn btn-sm btn-default m-r-sm btn-add m-l-sm" href="/adc/addsysnotice" ><i class="fa fa-plus m-r-sm"></i>添加</a>
							 </div>
						</div>
						<div class="col-sm-4 m-b-xs">
							<div class="input-group">
								<input type="text" class="input-sm form-control searchbox" placeholder="公告标题模糊检索">		
								<span class="input-group-btn"><button class="btn btn-sm btn-default btn-search" type="button"><i class="fa fa-search"></i></button></span> 	
							</div>
						</div>
					</div>
				</header>
				
				<!-- device table --> 
				<section class="scrollable wrapper">
					<table  id="table">
					</table>
				</section>
			</section>
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('foot.php'); ?>	
<link rel="stylesheet" href="/res/js/esgrid/themes/bootstrap/easyui.css" type="text/css" />
<link rel="stylesheet" href="/res/js/esgrid/themes/icon.css" type="text/css" />
<script src="/res/js/esgrid/jquery.easyui.min.js"></script>
<script type="text/javascript">
G.set('nav_name', 'systemnotice').set('remove_url', "/adc/sysmanage/removenotice");

$(function (){

	$('.btn-search').on('click', function (){
		$('#table').datagrid('load',{sc: $('.searchbox').val()});
	});

	$('.btn-filter').on('click', function (){
		var sc = $('.searchbox').val();
		var state = $('.statefilter').val();
		var corp = $('.corpfilter').val();
		$('#table').datagrid('load',{sc: sc, state:state, corp:corp});
	});

	initTables();
}); 
function initTables (){
	var columns=[
		{field:'id', title:'ID', width:30, align:'center', hidden:true},
		{field:'title', title:'标题', width:20, align:'center'},
		{field:'content', title:'内容', width:70, align:'center'},
		{field:'createtime', title:'创建时间', width:20, align:'center'},
		{field:'more3', title:'', width:10,align:'center', formatter:function (value, row, index) {
			return '<a class="grid_item_remove th-sortable" title="删除"><i class="fa fa-trash text-dark"></i><i class="fa fa-ban text-danger hidden"></i></a>';
		}}
	];
	createTable($('#table'), '/adc/sysmanage/getnoticelist', columns, true);
}

</script>
</body>
</html>
