<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include ("top.php");
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
								<a class="btn btn-sm btn-default m-r-sm " href="/adc/addcooperater"><i class="fa fa-plus m-r-sm"></i>添加</a>
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
					<table class="table table-striped " id="table">
					</table>
				</section>
			</section>
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php include ('foot.php'); ?>
<link rel="stylesheet" href="/res/js/esgrid/themes/bootstrap/easyui.css" type="text/css" />
<link rel="stylesheet" href="/res/js/esgrid/themes/icon.css" type="text/css" />
<script src="/res/js/esgrid/jquery.easyui.min.js"></script>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
G.set("remove_url", "/adc/corpmanage/corpremove")
	.set('nav_name', 'allcooperater');

var cid ;
$(function (){
	initTables ();

	$('.btn-change-submit').on('click', function(){
		if(!$('form').parsley().validate()){
			return false;
		}	
		$.post('/adc/corpmanage/changepw', $('form').serialize(), function (data){
			$('#changepw').modal('hide');
			if(data.state == "failed"){
				showTipMessageDialog ("<span class='text-danger font-bold'>"+data.reson+"</span>");
			}
		}, "json");
	});
});

function initTables (){
	var columns=[
		{field:'id', title:'ID', align:'center', hidden:true},
		{field:'cpun', title:'登录名称', width:40, align:'center'},		
		{field:'nickname', title:'商家昵称', width:40, align:'center'},
		{field:'industry', title:'行业', width:40,align:'center'},
		{field:'name_manager', title:'负责人',width:40, align:'center'},
		{field:'phone', title:'负责人电话',width:40, align:'center'},
		{field:'more', title:'', width:10,align:'center', formatter:function (value, row, index) {
			return '<a class="th-sortable text-info dker" title="进入后台" href="/corp/login"><i class="fa fa-sign-in text-dark" aria-hidden="true"></i></a>';
		}},
		{field:'more1', title:'', width:10,align:'center', formatter:function (value, row, index) {
			return '<a class="th-sortable text-info dker" onclick ="edit('+row.id+')" ><i class="fa fa-pencil text-dark"></i></a>';
		}},
		{field:'more2', title:'', width:10,align:'center', formatter:function (value, row, index) {
			return '<a class="th-sortable text-info dker grid_item_remove"><i class="fa fa-trash text-dark"></i></a>';}
		}
	];
	createTable($('#table'), '/adc/corpmanage/getcorplist', columns, true);
}

function edit (id){
	var map = new Map ();
	map.set('id', id);
	zy.submit_form_action ("/adc/editcooperater", map);
}
</script>
</body>
</html>
