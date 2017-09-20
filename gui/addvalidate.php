<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php')
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b b-light"><p>帐号管理 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 添加帐号 </p></header>
			<!-- <p class=" pull-right m-b-xs"><a href="javascript:showTipMessageDialog('未完善')" class="font-bold" style="color:#428bca; text-decoration:underline">批量添加</a></p>  -->
		<section class="scrollable padder">
			<div class="panel panel-default m-t-md">
				<header class="panel-heading">用户名密码认证</header>
				<div class="panel-body">
					<form class="form-horizontal">
						<section>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>验证名称</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true" data-parsley-rangelength="[4,6]" placeholder="4-6位数字或字母" name="uname">
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>							
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>验证密码</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true" data-parsley-maxlength="20" placeholder="20位以内的数字或字母" name="upass">
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>									
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>所属接入点</label>
								<div class="col-sm-6"><select class="form-control" name="ap">
									<option value="0">所有热点</option>
									<?php
									foreach ($ap as $row) {
										echo "<option value='".$row['apid']."'>".$row['apname']."</option>";
									}
									?>
								</select></div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>有效时间</label>
								<div class="col-sm-5">
									<input type="text" name="validatetime" class="form-control" data-parsley-required="true" id="dt1">
								</div>
							</div>								
							<div class="line line-dashed line-lg pull-in"></div>		
							<div class="form-group">
								<label class="col-sm-2 control-label">重复登录</label>
								<div class="col-sm-8">
									<div class="checkbox"><label class="checkbox-custom"><input type="checkbox" name="mutilogin"><i class="fa fa-fw fa-square-o"></i>  </label>
									<span class="text-danger m-l">(不勾选则同一帐号无限制登录, 勾选后根据使用人数限制登录, 后登陆无法认证)</span>
									</div>			
								</div>
							</div>
							<div class="form-group d-n mutilogin_label">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-8">
									<select class="day form-control" name ="mutilcount" style="width: auto;"><option value="">允许重复登录数</option>
									<?php foreach(range(1,10) as $n){echo '<option value = "'.$n.'">'.$n.'</option>';}?>
									</select>
								</div>
							</div>	
<!-- 							<div class="line line-dashed line-lg pull-in"></div>
							<div class="form-group">
								<label class="col-sm-2 control-label">绑定MAC</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true" data-parsley-maxlength="20" placeholder="20位以内的数字或字母" name="upass">
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
									</select>
								</div>
							</div> -->
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
					<button type="button" class="btn btn-default btn-sm btn-next" >添加</button>
				</div>
			</div>
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>

<?php  include ('footer.php') ?>	
<link rel="stylesheet" type="text/css" href="/res/js/fuelux/fuelux.css">
<script type="text/javascript" src="/res/js/fuelux/fuelux.js"></script>
<link rel="stylesheet" type="text/css" href="/res/js/datetimepicker/jquery.datetimepicker.min.css">
<script src="/res/js/datetimepicker/jquery.datetimepicker.full.min.js" cache="false"></script>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
G.set('nav_name', 'manageval');
$(function (){

	$('.btn-next').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}	
		zy.send_sync_ajax('/corp/authoptions/addaccount', $('form').serialize(), function (data){
			showTipMessageDialog(data.reson, data.state, "提示", "/corp/manageval");
		});	
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

	$('#dt1').datetimepicker({
		timepicker:false,
		format:'d.m.Y'
	});
});
</script>
</body>
</html>