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
			</section>
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('foot.php'); ?>	
<div class="modal fade" id="reson"><div class="modal-dialog"><div class="modal-content">
	<div class="modal-header"><h4 class="modal-title">未通过原因</h4></div>
		<div class="modal-body">
			<textarea rows="3" name="reson" class="form-control resonarea" placeholder="审核不通过原因"></textarea>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default text-mt" data-dismiss="modal">关闭</button>
			<button type="button" class="btn btn-info text-mt reson_submit">确定</button>
		</div>
	</div>
</div></div>
<link rel="stylesheet" href="/res/js/esgrid/themes/bootstrap/easyui.css" type="text/css" />
<link rel="stylesheet" href="/res/js/esgrid/themes/icon.css" type="text/css" />
<script src="/res/js/esgrid/jquery.easyui.min.js"></script>
<script type="text/javascript">
G.set('nav_name', 'templategrant');
var state, id;
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

	$('.reson_submit').on('click', function (){
		var reson = $('.resonarea').val();
		var id = $('.resonarea').data('id');
		var state = $('.resonarea').data('state');
		$('#reson').modal('hide');
		submit_grant (state, reson, id );
	});

	initTables();
}); 
function initTables (){
	var columns=[
		{field:'id', title:'ID', width:30, align:'center', hidden:true},
		{field:'name', title:'模板名称', width:30, align:'center'},
		{field:'nickname', title:'商家昵称', width:20, align:'center'},
		{field:'name_manager', title:'负责人', width:20, align:'center'},
		{field:'phone', title:'负责人电话', width:20, align:'center'},
		{field:'createtime', title:'创建时间', width:20, align:'center'},
		{field:'more2', title:'', width:20,align:'center', formatter:function (value, row, index) {
			return '<a class="text-info cursor-pointer" onclick="grant(1, '+row.id+')" href="javascript:;">【审核通过】</a>'
		}},
		{field:'more', title:'', width:20,align:'center', formatter:function (value, row, index) {
			return '<a class="text-info cursor-pointer" onclick="grant(2, '+row.id+')" href="javascript:;">【审核不通过】</a>'
		}}		
	];
	createTable($('#table'), '/adc/tmpmanage/gettmpgrantlist', columns, true);
}

function grant(state, id){
	if(state == 2){
		$('.resonarea').data("state", state);
		$('.resonarea').data("id", id);
		$('#reson').modal("show");
	}else{
		submit_grant(state, "", id);
	}
}

function submit_grant (state, reson, id ){
	zy.send_sync_ajax('/adc/tmpmanage/tmpgrant', {id:id, state:state, reson:reson},function (data){
		if(data.state == "falied"){
			showTipMessageDialog (data.reson, data.state);
		}else{
			$('#table').datagrid('reload');
		}
	}, "json");
}

</script>
</body>
</html>
