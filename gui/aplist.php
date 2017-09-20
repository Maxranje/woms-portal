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
						echo '<p class="padder-v m-l-n m-r-n m-t-n bg-warning lter text-center"><span class="text-danger font-bold m-t-sm">'.$failed_reson.'</span></p>';
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
<?php  include ('footer.php'); ?>	
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
