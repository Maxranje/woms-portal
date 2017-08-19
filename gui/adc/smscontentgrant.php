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
								<!-- <a class="btn btn-sm btn-default m-r-sm " href="/corp/addap"><i class="fa fa-plus m-r-sm"></i>添加</a> -->
							</div>
						</div>
						<div class="col-sm-4 m-b-xs">
							<div class="input-group">
								<input type="text" class="input-sm form-control searchbox" placeholder="商家昵称模糊查找">		
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
<?php  include ('foot.php'); ?>	
<link rel="stylesheet" href="/res/js/esgrid/themes/bootstrap/easyui.css" type="text/css" />
<link rel="stylesheet" href="/res/js/esgrid/themes/icon.css" type="text/css" />
<script src="/res/js/esgrid/jquery.easyui.min.js"></script>
<script type="text/javascript">
G.set('nav_name', 'smscontentgrant');

$(function (){

	$('.btn-search').on('click', function (){
		$('#table').datagrid('load',{sc: $('.searchbox').val()});
	});
	initTables();
}); 
function initTables (){
	var columns=[
		{field:'id', title:'ID', align:'center', hidden:true},
		{field:'reson', title:'', align:'center', hidden:true},
		{field:'content', title:'短信内容', width:80, align:'center'},
		{field:'nickname', title:'商家昵称', width:20, align:'center'},
		{field:'createtime', title:'创建时间', width:20, align:'center'},
		{field:'more2', title:'', width:20,align:'center', formatter:function (value, row, index) {
			return '<a class="text-info cursor-pointer" onclick="grant(1, '+row.id+')" href="javascript:;">【审核通过】</a>'
		}},
		{field:'more', title:'', width:20,align:'center', formatter:function (value, row, index) {
			return '<a class="text-info cursor-pointer" onclick="grant(2, '+row.id+')" href="javascript:;">【审核不通过】</a>'
		}}
	];
	createTable($('#table'), '/adc/smsmanage/getsmsgrantlist', columns, true);
}

function grant(state, id){
	zy.send_sync_ajax('/adc/smsmanage/smsgrant', {id:id, state:state},function (data){
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
