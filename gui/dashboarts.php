<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clearfix"> </header>        
		<section class="scrollable wrapper">
			<div class="row">
				<div class="col-lg-4">
					<section class="panel panel-default">
						<div class="panel-body">
							<span class="fa-stack fa-2x pull-left m-r-sm"> <i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-male fa-stack-1x text-white"></i> </span> 
							<a class="clear"> <span class="h3 block m-t-xs"><strong><?=$oluser?></strong></span> <small class="text-muted text-sm">当前在线用户数</small> </a> 
						</div>
					</section>
				</div>
				<div class="col-lg-4">
					<section class="panel panel-default">
						<div class="panel-body">
							<span class="fa-stack fa-2x pull-left m-r-sm"><i class="fa fa-circle fa-stack-2x text-info"></i><i class="fa fa-users fa-stack-1x text-white"></i> </span>
							<a class="clear" href="#"> <span class="h3 block m-t-xs"><strong><?=$alluser?></strong></span> <small class="text-muted text-sm">历史认证用户总量</small> </a> 
						</div>
					</section>
				</div>
				<div class="col-lg-4">
					<section class="panel panel-default">
						<div class="panel-body">
							<span class="fa-stack fa-2x pull-left m-r-sm"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-wifi fa-stack-1x text-white"></i> </span>
							<a class="clear" href="#"> <span class="h3 block m-t-xs"><strong><?=$apcount?></strong></span> <small class="text-muted text-sm">接入点数量</small> </a> 
						</div>
					</section>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-12"><section class="panel panel-default"><div class="panel-body chart-default-panel" id="historycharts"></div></section></div>
			</div>
            <!-- list -->	
			<section class="panel panel-default panel-body" style="height: 600px;" id="map-chart">
			</section>
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>	
<link rel="stylesheet" href="/res/js/esgrid/themes/bootstrap/easyui.css" type="text/css" />
<link rel="stylesheet" href="/res/js/esgrid/themes/icon.css" type="text/css" />
<script src="/res/js/echarts/echarts.min.js"></script>
<script src="/res/js/echarts/china.js"></script>
<script src="/res/js/script/charts.js"></script>
<script src="/res/js/esgrid/jquery.easyui.min.js"></script>
<script type="text/javascript">
G.set("remove_url", "/corp/apoptions/apremove")
	.set('nav_name', 'dashboarts')
	.set('edit_url', '/corp/apedit');

var chart, map_chart;
$(function(){
	
	line_chart = echarts.init(document.getElementById('historycharts'), 'infographic');
	map_chart = echarts.init(document.getElementById('map-chart'), 'infographic');

	$.post("/corp/dashboarts/getchartsdata", function (data){
		var xa = data.xAxis.split(",");
		var yb = data.yAxis.split(",");
		console.log(typeof(xa));
		line_chart.setOption(getLineChartsOptions(xa, yb));
		map_chart.setOption(getMapChartsOptions( data.location));
	}, "json");

	initTables();
});

function initTables (){
	var apcolumns=[
		{field:'apname', title:'接入点名称', width:40, align:'center'},
		{field:'time', title:'运行时长', width:40, align:'center'},
	];
	var usercolumns=[
		{field:'uname', title:'用户', width:50, align:'center'},
		{field:'apname', title:'所属AP', width:50, align:'center'},
	];	
	createTable($('#table_ap'), '/corp/dashboarts/getaplist', apcolumns, true);
	createTable($('#table_user'), '/corp/dashboarts/getuserlist', usercolumns, true);
}

</script>
</body>
</html>
