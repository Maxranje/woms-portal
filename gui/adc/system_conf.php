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
				<section class="scrollable wrapper m-b-n-lg w-f">
					<div class="panel panel-default">
						<header class="panel-heading b-t">全局配置</header>
						<div class="panel-body">
							<form class="form-horizontal" data-validate="parsley">
								<section>	
									<div class="form-group">
										<label class="col-sm-2 control-label">默认商家接入点上限</label>
										<div class="col-sm-6"><input type="text" class="form-control" placeholder="0" name="defaultap" value="<?=$conf['allow_aps']?>"></div>
									</div>	
									<div class="line line-dashed line-lg pull-in"></div>
									<div class="form-group">
										<label class="col-sm-2 control-label">默认接入点授权人数</label>
										<div class="col-sm-6"><input type="text" class="form-control" placeholder="0" name="defaultuser"  value="<?=$conf['allow_users']?>"></div>
									</div>					
									<div class="line line-dashed line-lg pull-in"></div>					
									<div class="form-group">
										<label class="col-sm-2 control-label">新增接入点默认模板</label>
										<div class="col-sm-6"><input type="text" class="form-control" placeholder="0" name="defaulttmp"  value="<?=$conf['defaulttmpid']?>"></div>
									</div>																										
									<div class="line line-dashed line-lg pull-in"></div>
									<div class="form-group">
										<label class="col-sm-2 control-label">默认全局跳转地址</label>
										<div class="col-sm-6"><input type="text" class="form-control" placeholder="http://www.baidu.com" name="redirect"  value="<?=urldecode($conf['redirect'])?>"></div>
									</div>										
								</section>
							</form>
							<div class="line line-dashed line-lg pull-in"></div>		
							<button type="button" class="btn btn-default btn-sm btn-next">保存</button>		
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
	$('.btn-next').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}	
		zy.send_sync_ajax('/adc/sysmanage/sysconfig', $('form').serialize(), function (data){
			showTipMessageDialog(data.reson, data.state);
		});
	});
});
G.set('nav_name', 'systemconfig');
</script>
</body>
</html>