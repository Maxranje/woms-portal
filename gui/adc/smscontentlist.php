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
							 </div>
						</div>
						<div class="col-sm-4 m-b-xs">
							<div class="input-group">
								<input type="text" class="input-sm form-control searchbox" placeholder="短信内容模糊检索">		
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

				<div id="tb" style="height:auto; padding: 5px 10px">
					<form class="form-inline" role="form">
						<div class="row">
							<div class="form-group col-sm-2">
								<select name="" class="form-control input-sm statefilter">
									<option class="text-muted" value="all">状态</option>
									<option value="1">未审核</option>
									<option value="2">审核通过</option>
									<option value="3">审核未通过</option>
								</select>
							</div>
							<div class="form-group col-sm-2">
								<select name="" class="form-control input-sm corpfilter">
									<option class="text-muted" value="all">商家昵称</option>
									<?php
									foreach ($corp as $row) {
										echo '<option value="'.$row['cid'].'">'.$row['nickname'].'</option>';
									}
									?>
								</select>
							</div>					
							<button class="btn btn-default btn-sm btn-filter">过滤</button>
						</div>				
					</form>
				</div>	
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
G.set('nav_name', 'smscontentlist');

$(function (){

	$('.btn-search').on('click', function (){
		var sc = $('.searchbox').val();
		var state = $('.statefilter').val();
		var corp = $('.corpfilter').val();
		$('#table').datagrid('load',{sc: sc, state:state, corp:corp});
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
		{field:'id', title:'ID', align:'center', hidden:true},
		{field:'reson', title:'', align:'center', hidden:true},
		{field:'content', title:'短信内容', width:80, align:'center'},
		{field:'nickname', title:'商家昵称', width:20, align:'center'},
		{field:'name_manager', title:'负责人', width:20, align:'center'},
		{field:'phone', title:'负责人电话', width:20, align:'center'},
		{field:'createtime', title:'创建时间', width:20, align:'center'},
		{field:'valid', title:'状态', width:20,align:'center', formatter:function (value, row, index) {
			if(value == "1"){
				return '<a href="#" title="审核通过" class="text-success"><i class="fa fa-circle m-r-sm"></i>审核通过</a>';	
			}else if(value=='2'){
				return '<a href="#" title="审核未通过, '+row.reson+'" class="text-danger"><i class="fa fa-circle m-r-sm"></i>审核未通过</a>';	
			}else{
				return '<a href="#" title="未审核" class="text-warning"><i class="fa fa-circle m-r-sm"></i>未审核</a>';
			}
		}}
	];
	createTable($('#table'), '/adc/smsmanage/getcontentlist', columns, true);
}

</script>
</body>
</html>
