<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('top.php');
?>
<style type="text/css">
.tooltip-inner {max-width:500px;min-width: 200px; text-align: left;line_height:2; padding:10px;}	
</style>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clearfix">
			<p>接入列表 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 新增接入点</p>
		</header> 
		<section class="scrollable padder">
			<div class="panel panel-default m-t">
				<div class="wizard clearfix" id="form-wizard">
					<ul class="steps">
						<li data-target="#step1" class="step1 active"><span class="badge badge-info">1</span><span class="text-md m-l">填写接入点基本信息</span></li>
						<li data-target="#step2" class="step2" ><span class="badge">2</span><span class="text-md m-l">接入点与设备对接</span></li>
					</ul>
				</div>
				<div class="step-content">
					<div class="step-pane active" id="step1">
						<form class="form-horizontal">
							<section>
								<div class="form-group">
									<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>接入点名称</label>
									<div class="col-sm-9"><input type="text" class="form-control" data-parsley-required="true" name="apname" data-parsley-length="[4, 32]"></div>
								</div>
								<div class="line line-dashed line-lg pull-in"></div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>接入点类型</label>
									<div class="col-sm-9"><select class="form-control protocol-type" name="protocol">
										<option value="w"> WIFIDOG 协议</option><option value="p"> PORTAL2 协议</option></select></div>
								</div>
								<div class="form-group portal2 d-n">
									<label class="col-sm-2 control-label"></label>
									<div class="col-sm-9">
										<div class="panel panel-default panel-body">
											<div class="form-group">
												<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>协议模式</label>
												<div class="col-sm-9">
													<select class="form-control" name="chaporpap">
														<option value="c">CHAP 模式</option>
														<option value="p">PAP 模式</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>协议密钥</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" placeholder="密钥必须与设备配置界面的KEY相同" name="key">
												</div>
											</div>	
											<div class="form-group">
												<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>公网IP</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" name="wanip">
												</div>
											</div>	
											<div class="form-group">
												<label class="col-sm-2 control-label">SSID</label>
												<div class="col-sm-9">
												<input type="text" class="form-control" placeholder="SSID " name="ssid">
												</div>
											</div>	
											<div class="form-group">
												<label class="col-sm-2 control-label">NASID</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" placeholder="NASID " name="nasid" >
												</div>
											</div>																																															
										</div>
									</div>
								</div>							
								<div class="line line-dashed line-lg pull-in"></div>   
								<div class="form-group">
									<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>设备类型</label>
									<div class="col-sm-9">
										<div class="radio">
											<label class="radio-custom"><input type="radio" name="devtype" value="r" checked="checked"><i class="fa fa-circle-o checked"></i> 路由器 </label>
											<label class="radio-custom"><input type="radio" name="devtype" value="w"><i class="fa fa-circle-o m-l-lg"></i> 网关设备 </label>
										</div>
									</div>
								</div>										
								<div class="line line-dashed line-lg pull-in"></div>   
								<div class="form-group">
									<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>归属组</label>
									<div class="col-sm-9">
										<select class="form-control" name="group"><option value='0'> 默认组</option></select>
									</div>
								</div>									
								<div class="line line-dashed line-lg pull-in machcode-label"></div>
								<div class="form-group machcode-label">
									<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>设备机器码</label>
									<div class="col-sm-9">
										<div class="radio">
											<label class="radio-custom"><input type="radio" name="machcode-radio" checked="checked" value="n"><i class="fa fa-circle-o checked"></i> 自适配 </label>
											<label class="radio-custom"><input type="radio" name="machcode-radio" value="o"><i class="fa fa-circle-o m-l-lg"></i> 绑定 </label>
											<a class="m-l machinecode-tip"  href="#" data-toggle="tooltip" data-placement="right" data-html="true" data-original-title="可以一个接入点绑定多个设备, 实现设备间无漫游"> <i class="fa fa-question-circle" aria-hidden="true" style="font-size:14px;"></i> </a>
										</div>
									</div>
								</div>										
								<div class="form-group dev-mach d-n">
									<label class="col-sm-2 control-label"></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="machcode" placeholder="设备的机器码">
									</div>
								</div>   
								<div class="line line-dashed line-lg pull-in"></div>                  
								<div class="form-group">
									<label class="col-sm-2 control-label">接入点城市</label>
									<div class="col-sm-9"><input type="text" class="form-control" name="location" placeholder="市级别为单位, 默认为北京"></div>
								</div>								     
								<div class="line line-dashed line-lg pull-in"></div>
								<div class="form-group">
									<label class="col-sm-2 control-label">接入点描述</label>
									<div class="col-sm-9">
										<textarea class="form-control remark" style="max-width:100%; height:100px;" name="remark"></textarea>
									</div>
								</div>
								<div class="line line-dashed line-lg pull-in"></div>                                                                         
							</section>
						</form>
					</div>
					<div class="step-pane" id="step2">
						<p class="h5 m-l-sm m-t-lg font-bold">1. 请将热点名称   <label class="m-l-sm text-danger apname"></label>  填写到认证设备对应的配置页面并开启web认证，如图：</p>
						<div class="m-l-sm m-t-md">
							<img src="/res/images/rxilie.png">
						</div>
						<p class="h5 m-l-sm m-t-xl m-b-lg font-bold">2. 约3分钟即可配置生效</p>
						<div class="line line-dashed line-lg pull-in"></div>
					</div>
					<div class="actions m-t">
						<button type="button" class="btn btn-default btn-sm btn-next"  data-wizard="next" data-last="Finish">下一步</button>
					</div>
				</div>
			</div>
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php include ('footer.php') ?>
<link rel="stylesheet" type="text/css" href="/res/js/fuelux/fuelux.css">
<script src="/res/js/fuelux/fuelux.js" cache="false"></script>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
G.set('nav_name', 'aplist');


$(function (){
	var html1 = '1. 群组接入点，可以一个接入点绑定多个设备, 实现设备间无漫游<br/><br/>2. 群组接入点(物理设备)必须配置相同的SSID';
	$('.apgrouptip').attr('data-original-title', html1);
	var html2 = '自适配： 路由器和网关会根据接入点名称与物理设备匹配<br/><br/>绑定： 根据机器码匹配设备';
	$('.machinecode-tip').attr('data-original-title', html2);

	$('.protocol-type').change(function (){
		if($(this).val() == 'p' || $(this).val() == 'pr'){
			$('.portal2').removeClass('d-n');
			$('.machcode-label').addClass('d-n');
			$('input[name=chaporpap]').attr('data-parsley-required', "true");
			$('input[name=wanip]').attr('data-parsley-required', "true");
			$('input[name=key]').attr('data-parsley-required', "true");
		}else{
			$('.portal2').addClass('d-n');
			$('.machcode-label').removeClass('d-n');
			$('input[name=chaporpap]').attr('data-parsley-required', "true");
			$('input[name=wanip]').attr('data-parsley-required', "true");
			$('input[name=key]').attr('data-parsley-required', "true");
		}
	});

	$('input[name=machcode-radio]').on('click', function (){
		if($(this).val() == 'o'){
			$('.dev-mach').removeClass('d-n');
			$('input[name=machcode]').attr('data-parsley-required', "true");
		}else{
			$('.dev-mach').addClass('d-n');
			$('input[name=machcode]').removeAttr('data-parsley-required');
		}
	});

	// 添加热点
	$('.btn-next').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}	
		if($('#step2').hasClass('active')) {
			window.location.href = "/corp/aplist";
		}
		zy.send_sync_ajax('/corp/apoptions/addap', $('form').serialize(), function (data){
			if(data.state == "failed"){
				showTipMessageDialog(data.reson, data.state);
			}else{
				$('.apname').html($('input[name=apname]').val());
				$('.step1').removeClass('active');
				$('#step1').removeClass('active');
				$('.step2').addClass('active');
				$('#step2').addClass('active');	
				$(".btn-next").html('完成');
			}
		});
	});


	$('.machinecode-tip').on('click', function (){
		console.log($('.tooltip').html());
	});
});
</script>
</body>
</html>