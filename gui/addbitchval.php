<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>

<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clear">
			<p class="m-t pull-left">批量添加新验证信息</p>
			<p class=" pull-right m-b-xs"><a href="/corp/addvalidate" class="font-bold" style="color:#428bca; text-decoration:underline">单个添加</a></p>
		</header>			
		<section class="scrollable padder">
			<div class="panel panel-default m-t-lg">
				<header class="panel-heading">用户名密码认证</header>
				<div class="panel-body">
					<form class="form-horizontal" data-validate="parsley">
						<section>	
							<div class="form-group">
								<label class="col-sm-2 control-label">生成数量</label>
								<div class="col-sm-6">
									<input type="text" class="form-control parsley-validated" data-required="true" data-maxlength="200" placeholder="小于200个" name="numbers">
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>									
							<div class="form-group">
								<label class="col-sm-2 control-label">前缀</label>
								<div class="col-sm-6">
									<input type="text" class="form-control parsley-validated" data-rangelength="[0,4]" placeholder="0-4个字母或数字" name="before">
								</div>
							</div>									
							<div class="line line-dashed line-lg pull-in"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label">后缀</label>
								<div class="col-sm-6">
									<input type="text" class="form-control parsley-validated" data-rangelength="[0,4]" placeholder="0-4个字母或数字" name="after">
								</div>
							</div>									
							<div class="line line-dashed line-lg pull-in"></div>									
							<div class="form-group">
								<label class="col-sm-2 control-label">密码</label>
								<div class="col-sm-6">
									<div class="checkbox"><label class="checkbox-custom"><input type="checkbox" name="samepass" value="on" checked><i class="fa fa-fw fa-square-o"></i> 与用户名一致 </label>
										<span class="m-l-sm text-danger">(批量创建的账户密码统一一个)</span>
									</div>
									<input type="text" class="form-control parsley-validated m-t-sm passwd d-n" name="password" placeholder="">
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label">所属接入点</label>
								<div class="col-sm-6"><select class="form-control" name="who">
									<option value="0">所有热点</option>
								<?php
									foreach ($ap as $row) {
										echo '<option value="'.$row['apid'].'">'.$row['apname'].'</option>';
									}
								?>
								</select></div>
							</div>							
							<div class="line line-dashed line-lg pull-in"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label">有效时间</label>
								<div class="col-sm-8 l-h-2x">
									<input class="input-sm input-s datepicker-input form-control pull-left" size="16" type="text" value="<?=$time?>" data-date-format="dd-mm-yyyy" name="validatetime">
									<span class="text-danger m-l">(该用户名密码仅在该时间内有效)</span>
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label">允许重复登录</label>
								<div class="col-sm-8">
									<div class="checkbox"><label class="checkbox-custom"><input type="checkbox" name="mutilogin"><i class="fa fa-fw fa-square-o"></i>  </label></div>			
								</div>
							</div>
							<div class="form-group d-n mutilogin_label">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-8">
									<select class="day form-control" name ="mutilcount" style="width: auto;"><option value="">允许重复登录数</option>
										<option value="1">1</option><option value="1">2</option>
										<option value="1">3</option><option value="1">4</option>
										<option value="1">5</option><option value="1">6</option>
										<option value="1">7</option><option value="1">8</option>
									</select>
								</div>
							</div>								
							<div class="line line-dashed line-lg pull-in"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label">绑定 MAC</label>
								<div class="col-sm-8">
									<div class="checkbox"><label class="checkbox-custom"><input type="checkbox" name="bindmac"><i class="fa fa-fw fa-square-o"></i>  </label></div>			
								</div>
							</div>							
							<div class="form-group d-n bindmac_label">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-8">
									<select class="day form-control" name="bindmacnum" style="width: auto;"><option value="">绑定MAC数目：</option>
									<?php
									foreach(range(1,10) as $n){
										echo '<option value = "'.$n.'">'.$n.'</option>';
									}
									?>
									</select>
								</div>
							</div>
																																					<div class="line line-dashed line-lg pull-in"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label">账户状态</label>
								<div class="col-sm-8">
									<div class="radio row m-t-md">
										<label class="radio-custom col-lg-2"><input type="radio" name="state" checked="checked" value="on" data-required="true"><i class="fa fa-circle-o checked"></i> 有效</label>
										<label class="radio-custom col-lg-2"><input type="radio" name="state" value="off"><i class="fa fa-circle-o"></i> 无效</label>
									</div>								
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>                                                                        
						</section>
					</form>
					<button type="button" class="btn btn-default btn-submit" data-target="#form-wizard" data-wizard="previous">添加</button>
				</div>
			</div>
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>
<link rel="stylesheet" type="text/css" href="/res/js/fuelux/fuelux.css">
<script type="text/javascript" src="/res/js/fuelux/fuelux.js"></script>
<script src="/res/js/datepicker/bootstrap-datepicker.js" cache="false"></script>
<script src="/res//js/libs/moment.min.js" cache="false"></script>
<script src="/res//js/combodate/combodate.js" cache="false"></script>
<script type="text/javascript">

$(function (){

$('.btn-submit').on('click', function (){
	if(!$('form').parsley('validate')){
		return ;
	}
	alert(123);
	sendAsyncAjax ('/corpoptions/addvalidate', $('form').serialize(), function (data){
		//showTipMessageDialog(data.reson);
	});
});
$('input[name=samepass]').change(function (){
	if($(this).is(":checked")){
		$('.passwd').addClass('d-n');
		$('.passwd').attr('data-required', "true");
	}else{
		$('.passwd').removeClass('d-n');
		$('.passwd').removeAttr('data-required');
	}
});

$('input[name=mutilogin]').change(function (){
	if($(this).is(":checked")){
		$('.mutilogin_label').removeClass('d-n');
	}else{
		$('.mutilogin_label').addClass('d-n');
	}
});

$('input[name=bindmac]').change(function (){
	if($(this).is(":checked")){
		$('.bindmac_label').removeClass('d-n');
	}else{
		$('.bindmac_label').addClass('d-n');
	}
});


})
function g(){
	return "manageval";
}

</script>
</body>
</html>