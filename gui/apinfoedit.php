<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clearfix">
			<p>接入列表 <i class="fa fa-angle-right m-l-sm m-r-sm"></i>修改接入点<span class="text-danger font-bold m-l-sm">( 由于接入点名称为识别关键字之一， 所以禁止修改， 如若需要请重新创建接入点即可。请着重注意！)</span></p>
		</header>    	
		<section class="scrollable padder">
			<div class="panel panel-default m-t">
				<form class="form-horizontal">
					<section class="m-t-lg">
						<div class="form-group">
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>接入点名称</label>
							<input type="hidden" name="apid" value="<?=$apid?>">
							<div class="col-sm-9"><input type="text" class="form-control" readonly="readonly" name="apname" value="<?=$apname?>"></div>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>接入点类型</label>
							<div class="col-sm-9"><select class="form-control protocol-type" name="protocol">
								<option value="w" <?php if($protocol == 'w') echo "selected='selected'";?> > WIFIDOG 协议</option>
								<option value="p" <?php if($protocol == 'p') echo "selected='selected'";?>> PORTAL2 协议</option></select></div>
						</div>
						<div class="form-group portal2 <?php if($protocol == 'w') echo "d-n";?>">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-9">
								<div class="panel panel-default panel-body">
									<div class="form-group">
										<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>协议模式</label>
										<div class="col-sm-9">
											<select class="form-control" name="chaporpap">
												<option value="c" <?php if($modal == 'c') echo "selected='selected'";?>>CHAP 模式</option>
												<option value="p" <?php if($modal == 'p') echo "selected='selected'";?>>PAP 模式</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>协议密钥</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" placeholder="密钥必须与设备配置界面的KEY相同" name="key" value="<?=$key?>">
										</div>
									</div>	
									<div class="form-group">
										<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>公网IP</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="wanip" value="<?=$wanip?>">
										</div>
									</div>	
									<div class="form-group">
										<label class="col-sm-2 control-label">SSID</label>
										<div class="col-sm-9">
										<input type="text" class="form-control" placeholder="SSID " name="ssid" value="<?=$ssid?>">
										</div>
									</div>	
									<div class="form-group">
										<label class="col-sm-2 control-label">NASID</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" placeholder="NASID " name="nasid" value="<?=$nasid?>">
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
									<label class="radio-custom"><input type="radio" name="devtype" value="r" <?php if($devtype == 'r') echo "checked='checked'";?>><i class="fa fa-circle-o"></i> 路由器 </label>
									<label class="radio-custom"><input type="radio" name="devtype" value="w" <?php if($devtype == 'w') echo "checked='checked'";?>><i class="fa fa-circle-o m-l-lg"></i> 网关设备 </label>
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
						<div class="line line-dashed line-lg pull-in machcode-label <?php if($protocol != 'w') echo "d-n";?>"></div>
						<div class="form-group machcode-label <?php if($protocol != 'w') echo "d-n";?>">
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>设备机器码</label>
							<div class="col-sm-9">
								<div class="radio">
									<label class="radio-custom"><input type="radio" name="machcode-radio" value="n" <?php if($adapter == 'n') echo "checked='checked'";?>><i class="fa fa-circle-o"></i> 自适配 </label>
									<label class="radio-custom"><input type="radio" name="machcode-radio" value="o" <?php if($adapter == 'o') echo "checked='checked'";?>><i class="fa fa-circle-o m-l-lg"></i> 绑定 </label>
									<a class="m-l machinecode-tip"  href="#" data-toggle="tooltip" data-placement="right" data-html="true" data-original-title="可以一个接入点绑定多个设备, 实现设备间无漫游"> <i class="fa fa-question-circle" aria-hidden="true" style="font-size:14px;"></i> </a>
								</div>
							</div>
						</div>										
						<div class="form-group dev-mach <?php if($protocol != 'w' || $adapter != 'o') echo "d-n";?>">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="machcode" placeholder="设备的机器码" value="<?=$lic?>">
							</div>
						</div>   
						<div class="line line-dashed line-lg pull-in"></div>                  
						<div class="form-group">
							<label class="col-sm-2 control-label">接入点城市</label>
							<div class="col-sm-9"><input type="text" class="form-control" name="location" placeholder="市级别为单位, 默认为北京" value="<?=$location?>"></div>
						</div>								     
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label">接入点描述</label>
							<div class="col-sm-9">
								<textarea class="form-control remark" style="max-width:100%; height:100px;"  name="remark"><?=$remark?></textarea>
							</div>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>                                                                         
					</section>
				</form>
				<div class="wrapper m-t-n"><button type="button" class="btn btn-default btn-sm btn-next">确认修改</button></div>
			</div>
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php include ('footer.php') ?>
<link rel="stylesheet" type="text/css" href="/res/js/fuelux/fuelux.css">
<script type="text/javascript" src="/res/js/fuelux/fuelux.js"></script>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
$(function (){
	var html1 = '1. 群组接入点，可以一个接入点绑定多个设备, 实现设备间无漫游<br/><br/>2. 群组接入点(物理设备)必须配置相同的SSID';
	$('.apgrouptip').attr('data-original-title', html1);
	var html2 = '1. 路由器和网关会根据接入点名称与物理设备匹配<br/><br/>2. 根据机器码匹配设备';
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
		zy.send_sync_ajax ('/corp/apoptions/apedit', $('form').serialize(), function (data){
			showTipMessageDialog(data.reson, data.state, "Message", "/corp/aplist");
		});
	});

});

G.set('nav_name', 'aplist');
</script>

</body>
</html>