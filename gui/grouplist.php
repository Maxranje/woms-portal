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
								<a class="btn btn-sm btn-default m-r-sm refresh" ><i class="fa fa-refresh m-r-sm"></i>刷新</a>
								<a class="btn btn-sm btn-default m-r-sm"  href="/corp/addgroup"><i class="fa fa-plus m-r-sm"></i>添加热点</a>
							</div>
						</div>
						<div class="col-sm-4 m-b-xs">
							<div class="input-group">
								<input type="text" class="input-sm form-control searchbox" placeholder="分组名称搜索">		
								<span class="input-group-btn"><button class="btn btn-sm btn-default btn-search" type="button"><i class="fa fa-search"></i></button></span> 	
							</div>
						</div>
					</div>
				</header>
				
				<!-- device table --> 
				<section class="scrollable wrapper w-f">
					<section class="panel panel-default">
						<div class="table-responsive">
							<table class="table table-striped m-b-none">
								<thead><tr><th class="d-n">ID</th><th width="150">分组名称</th><th>接入点</th><th>创建时间</th><th>操作</th></tr>
								</thead>
								<tbody>
									<?php
									foreach ($data as $row)
									{
										echo '<tr><td class="apid d-n">'.$row['id'].'</td><td>'.$row['groupname'].'</td>';
										echo '<td></td>';
										echo '<td>'.date('Y-m-d H:i', intval($row['addtime'])).'</td>';
										echo '<td><a href="#" class="text-dker " title="删除"><i class="fa fa-trash"></i></a>';
									}
									?>
								</tbody>
							</table>
						</div>
					</section>
				</section>
				<footer class="footer bg-white b-t clearfix">
					<div id="paginational" class="pull-right m-t-sm">
					</div>
					<span class="d-n nums"><?=$nums?></span><span class="d-n pages"><?=$pages?></span>
				</footer>
			</section>
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>
<script>
function g(){ return "aplist"}


$(function (){
var nums = parseInt($('.nums').html());
var pages = parseInt($('.pages').html());
$('#paginational').pagination({
	dataSource:function (){
		var resutl = [];
		for(let a = 1; a<=nums; a++){
			resutl.push(a);
		}
		return resutl;
	}(),
	pageSize:50,
	pageNumber:pages,
	callback:function (response, pagination){
	}
});

$('.btn-search').on('click', function (){
	var map = new Map();
	map.set("sc", $('.searchbox').val());
	submitForm("/corp/groupmanage", map);
});

});

</script>
<div class="modal fade" id="remove"><div class="modal-dialog"><div class="modal-content">
	<div class="modal-header"><h4 class="modal-title">删除设备</h4></div>
		<div class="modal-body"><p class="text-danger">是否确定删除设备?一旦删除无法恢复</p></div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default text-mt" data-dismiss="modal">关闭</button>
			<button type="button" class="btn btn-info text-mt r_submit">确定</button>
		</div>
	</div>
</div></div>
</body>
</html>
