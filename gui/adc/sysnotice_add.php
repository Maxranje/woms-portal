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
							</div>
						</div>
					</div>
				</header>
				
				<!-- device table --> 
				<section class="scrollable padder m-t">
					<div class="panel panel-default">
						<header class="panel-heading text-danger">添加系统公告</header>
						<div class="panel-body">
							<form class="form">
								<section>	
									<div class="form-group">
										<label><a class="text-danger m-r-sm">*</a>标题</label>
										<input type="text" class="form-control input-sm" data-parsley-required="true" name="title">
									</div>
									<div class="line line-dashed line-lg pull-in"></div>	
									<div class="form-group">
										<label><a class="text-danger m-r-sm">*</a>内容</label>
										<textarea class="form-control input-sm" data-parsley-required="true" name="msg" rows="6"></textarea>
									</div>
									<div class="line line-dashed line-lg pull-in"></div>	
								</section>
							</form>
							<button type="button" class="btn btn-default btn-sm btn-submit" >添加公告</button>	
						</div>	
					</div>										
				</section>
			</section>
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php include ('foot.php'); ?>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
$(function (){
	$('.btn-submit').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}	
		zy.send_sync_ajax('/adc/sysmanage/addsystemnotice', $('form').serialize(), function (data){
			showTipMessageDialog(data.reson, data.state, "提示信息", "/adc/systemnotice");
		});
	});
});


G.set('nav_name', 'systemnotice');
</script>
</body>
</html>