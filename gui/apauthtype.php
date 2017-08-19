<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b b-light">
			<p>功能配置 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 认证模式 </p>
		</header>					
		<section class="scrollable wrapper ">
			<form class="form-horizontal">
				<div class="m-t alert alert-info">
					<span class="h5 m-l-sm m-t-lg">接入点名称: <label class="m-l-sm text-danger"><?=$ap['apname']?></label></span>
					<span class="h5 m-l-lg m-t-lg"> 接入点类型: <label class="m-l-sm text-danger">
						<?php echo $ap['protocol']=='w'?"WIFIDOG协议":($ap['protocol']=='p'?"PORTAL2.0协议" : "PORTAL2.0 增强"); ?>
					</label></span>
					<input type="hidden" name="apid" value="<?=$apid?>">
				</div>
				<section class="panel panel-default m-t">
					<header class="panel-heading">认证方式配置</header>
					<div class="panel-body">
						<p class="m-t-sm">基础认证 </p>
						<div class="radio row m-t-md">
							<label class="radio-custom col-lg-2"><input type="radio" name="authtype" value="0" <?php if($ap['authtype']== '0'){ echo "checked='checked'" ;} ?>><i class="fa fa-circle-o"></i> 一键免认证</label>
							<label class="radio-custom col-lg-2"><input type="radio" name="authtype" value="2" <?php if($ap['authtype']== '2'){ echo "checked='checked'" ;} ?>><i class="fa fa-circle-o"></i> 短信验证码认证</label>
							<label class="radio-custom col-lg-2"><input type="radio" name="authtype" value="1" <?php if($ap['authtype']== '1'){ echo "checked='checked'" ;} ?>><i class="fa fa-circle-o"></i> 用户名密码认证</label>
						</div>                                  
						<p class="m-t-lg">第三方认证 </p>
						<div class="row checkbox m-t-md">
							<label class="checkbox-custom col-lg-2"><input type="checkbox" name="wxlogin" <?php if($ap['wxloginable']== '1'){ echo "checked='checked'" ;} ?>><i class="fa fa-fw fa-square-o"></i> 微信认证 </label>
						</div>  
					</div>
				</section>
				<section class="panel panel-default m-t">
					<header class="panel-heading">访问信息配置</header>
					<div class="panel-body">
						<p class="m-t">认证跳转 </p>
						<div class="form-group m-l-n-md m-t-n-xs05 row">
							<div class="col-sm-6">
								<select class="form-control text-mt m-b showstatuspage" name="showstatuspage">
									<option value="s" <?php if($ap['showstatuspage']== 's'){ echo "selected='selected'" ;} ?>>跳转原访问地址</option>
									<option value="c" <?php if($ap['showstatuspage']== 'c'){ echo "selected='selected'" ;} ?>>自定义跳转地址</option>
								</select>
							</div>
							<div class="col-sm-5">
								<input type="text" class="form-control m-b d-n" name="customurl" data-trigger="change" data-type="url" placeholder="url" <?php if($ap['showstatuspage']== 'c'){ echo "value='".$ap['customurl']."'";} ?>>
							</div>
						</div>
						<p class="m-t">绑定MAC，二次免认证 </p>
						<div class="row m-t-n-xs">
							<div class="col-lg-6">
								<select class="form-control text-mt m-b bindmac" name="bindmac">
									<option value="0" <?php if($ap['bindmac']== '0'){ echo "selected='selected'" ;} ?>>关闭</option>
									<option value="1d" <?php if($ap['bindmac']== '1d'){ echo "selected='selected'" ;} ?>>1天内</option>
									<option value="1w" <?php if($ap['bindmac']== '1w'){ echo "selected='selected'" ;} ?>>1周内</option>
									<option value="1m" <?php if($ap['bindmac']== '1m'){ echo "selected='selected'" ;} ?>>1个月内</option>
									<option value="6m" <?php if($ap['bindmac']== '6m'){ echo "selected='selected'" ;} ?>>6个月内</option>
									<option value="a" <?php if($ap['bindmac']== 'a'){ echo "selected='selected'" ;} ?>>永久有效</option>
								</select>
							</div>
						</div>
						<p class="m-t">浏览广告 </p>
						<div class="row checkbox m-t-md">
							<label class="checkbox-custom col-lg-7"><input type="checkbox" name="showad" <?php if($ap['showad']== '1'){ echo "checked='checked'" ;} ?>><i class="fa fa-fw fa-square-o"></i> 
								<span class="font-bold text-info m-l-sm">（终端是否浏览广告, 该功能仅适用于自定义模板, 公共模板忽略该功能）</span> 
							</label>
						</div>  							 					
					</div>
				</section>
				<section class="panel panel-default m-t smspanel d-n">
					<header class="panel-heading">短信信息配置</header>
					<div class="panel-body">
						<div class="form-group row">
							<div class="col-lg-6 input-group">
								<input type="text" class="form-control" name="smskey"  placeholder="短信对接通道KEY" value="<?=$ap['smskey']?>">
							</div>	
							<div class="col-lg-3 input-group text-danger m-t-sm"> ( 必配项 ) </div>
						</div>

						<div class="form-group row">
							<div class="col-lg-6 input-group">
								<input type="text" class="form-control" name="smssecret" placeholder="短信对接通道SECRET" value="<?=$ap['smssecret']?>">
							</div>
							<div class="col-lg-3 input-group text-danger m-t-sm"> ( 必配项 ) </div>
						</div>	
						<div class="form-group row">
							<div class="col-lg-6 input-group">
								<input type="text" class="form-control" name="smstemplate" placeholder="通知类型模版ID" value="<?=$ap['smstemplate']?>">
							</div>
							<div class="col-lg-3 input-group text-danger m-t-sm"> ( 必配项 ) </div>
						</div>	

						<div class="form-group row">
							<div class="col-lg-6 m-l-none input-group" >
								<select class="form-control text-mt" name="smsid">
									<option value="0">短信验证码内容 <span class="m-l">(默认发送随机6位数字的验证信息)</span></option>
									<?php
									foreach ($sms as $row) {
										$select = ($row['smsid'] == $ap['smstid']) ? "selected='selected'" : "";
										echo '<option value="'.$row['smsid'].'" '.$select.'>'.$row['content'].'</option>';
									}
									?>
								</select>
							</div>                                          
						</div>
					</div>    
				</section>
			</form>
			<button class="btn btn-success btn-sm m-r-sm btn-save">完成</button>
			<a class="btn btn-default btn-sm m-r-sm btn-next" href="/corp/apauthtmp">高级配置</a>
		</section>
	</section>
</section>
<?php  include ('footer.php'); ?>	
<link rel="stylesheet" type="text/css" href="/res/js/fuelux/fuelux.css">
<script type="text/javascript" src="/res/js/fuelux/fuelux.js"></script>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
G.set('nav_name', 'aplist');
$(function (){
	if($('.showstatuspage').val() == 'c'){
		$('input[name=customurl]').removeClass('d-n');
	}else{
		$('input[name=customurl]').addClass('d-n');
	}

	if($('input[type=radio]:checked').val() == '2'){
		$('.smspanel').removeClass('d-n');
	}

	$('input[type=radio]').change(function (){
		if( $(this).val() == 2 ){
			$('.smspanel').removeClass('d-n');
		}else{
			$('.smspanel').addClass('d-n');
		}
	});

	$(".showstatuspage").change(function (){
		if($(this).val() == 'c'){
			$('input[name=customurl]').removeClass('d-n');
		}else{
			$('input[name=customurl]').addClass('d-n');
		}
	});
	$('.btn-save').on('click', function (){
		zy.send_sync_ajax('/corp/apoptions/apconfig', $('form').serialize(), function (data){
			showTipMessageDialog(data.reson, data.state);
		});
	});
});
</script>
</body>
</html>
