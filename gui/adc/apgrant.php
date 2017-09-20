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
								<input type="text" class="input-sm form-control searchbox" placeholder="模糊搜索接入点名称">		
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
G.set("remove_url", "/corp/apoptions/apremove").set('nav_name', 'apgrant');

$(function (){

	$('.btn-search').on('click', function (){
		$('#table').datagrid('load',{sc: $('.searchbox').val()});
	});
	
	initTables();

}); 
function initTables (){
	var columns=[
		{field:'id', title:'接入点ID', width:10, align:'center', },
		{field:'cpun', title:'商家登录名', width:20, align:'center'},
		{field:'nickname', title:'商家昵称', width:20, align:'center'},
		{field:'apname', title:'接入点名称', width:30, align:'center'},
		{field:'devtype', title:'接入点类型', width:20, align:'center'},
		{field:'usecountgrant', title:'授权用户数', width:20, align:'center',formatter:function (value, row, index) {
			return '<span class="text-danger font-bold">'+value+' 人</span>';
		}},
		{field:'grant', title:'授权接入用户数', width:30,align:'center', formatter:function (value, row, index) {
			return '<input type="text" style="width:80px;"><button  class="m-l-xs" onclick="corp_grant('+row.id+', event)">授权</button>';}
		}			
	];
	createTable($('#table'), '/adc/corpmanage/getapgrantlist', columns, true);
}
function corp_grant (id, event){
	var g = parseInt($(event.target).prev().val());
	if (isNaN(g)){
		alert("请填写有效数字");
		return;
	}
	$.post("/adc/corpmanage/apgrant", {id:id, count:g}, function (data){
		showTipMessageDialog(data.reson, data.state);
		if(data.state == "success"){
			$('#table').datagrid("reload");			
		}
	}, "json");
}

</script>
</body>
</html>
