<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clearfix">
			<div class="row m-t-sm">
				<div class="col-sm-1 m-b-xs l-h-2x"><a class="btn btn-sm btn-default refresh" ><i class="fa fa-refresh"></i></a></div>
				<div class="col-sm-8 m-t-xs l-h-2x tip-msg font-bold"></div>
			</div>
		</header>    	
		<section class="scrollable padder">
			<div class="panel panel-default m-t-lg">
				<header class="panel-heading">增加分组</header>
				<div class="panel-body">
					<form class="form-horizontal" data-validate="parsley">
						<section>
							<div class="form-group">
								<label class="col-sm-2 control-label">分组名称</label>
								<div class="col-sm-9">
									<input type="text" class="groupname form-control">
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label">分组描述</label>
								<div class="col-sm-9">
									<textarea class="form-control remark" style="max-width:100%; height:100px;"></textarea>
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div> 
							<a class="btn btn-default btn-addgroup">添加</a>                                                                        
						</section>
					</form>
				</div>
			</div>
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php include ('footer.php') ?>

<script type="text/javascript">
$(function (){

$('.btn-addgroup').on('click', function (){
	$('.tip-msg').html("");
	var g = $('.groupname').val();
	if(g == ''){
		$('.tip-msg').html('<span class="text-danger">必须填写分组名称</span>');
	}
	var r = $('.remark').val();
	$.post('/corpoptions/addgroup', {groupname:g, remark:r}, function (data){
		if(data.state == "success"){
			$('.tip-msg').html('<span class="text-success">'+data.reson+'</span>');
		}else{
			$('.tip-msg').html('<span class="text-danger">'+data.reson+'</span>');
		}
		$('.groupname').val("");
		$('.remark').val("");
	}, "json");

});

})
function g(){ return "aplist";}
</script>

</body>
</html>