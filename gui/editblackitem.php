<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clear">
			<p>黑白名单 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 黑名单<i class="fa fa-angle-right m-l-sm m-r-sm"></i> 修改黑名单 </p>
		</header>		
		<section class="scrollable padder">
			<section class="panel panel-default m-t">
				<div class="panel-body">
					<form class="form-horizontal">
						<div class="form-group" >
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>黑名单类型</label>
							<div class="col-sm-5">
								<input type="hidden" name="id" value="<?=$item['id']?>">
								<select class="form-control text-mt blacktype" name="blacktype">
									<option value="m" <?php if($item['type'] == "m") echo "selected='selected'"; ?> >MAC 黑名单</option>
									<option value="p" <?php if($item['type'] == "p") echo "selected='selected'"; ?> >手机号黑名单</option>
								</select>
							</div>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>黑名单内容</label>
							<div class="col-sm-5">
								<input type="text" name="content" class="form-control" data-parsley-required="true" data-parsley-length="[17,17]" value="<?=$item['content']?>">
							</div>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group" >
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>所属接入点</label>
							<div class="col-sm-5">
								<select name="ap" class="form-control text-mt">
									<option value="0">所有接入点</option>
									<?php
									foreach ($ap as $row) {
										$select = $row['apid'] == $item['apid'] ? "selected='selected'" : "";
										echo "<option value='".$row['apid']."' ".$select.">".$row['apname']."</option>";
									}
									?>
								</select>
							</div>
						</div> 
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>有效时间</label>
							<div class="col-sm-5">
								<input type="text" name="datetime" class="form-control" data-parsley-required="true" id="dt1" value="<?=date("d.m.Y", $item['validtime'])?>">
							</div>
						</div>							  
						<div class="line line-dashed line-lg pull-in"></div >               
						<div class="form-group">
							<label class="col-sm-2 control-label">备注</label>
							<div class="col-sm-5"><textarea class="form-control remark" rows="6"  placeholder="备注信息" name="remark"><?=$item['remark']?></textarea></div>
						</div> 
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="alert alert-danger m-lg text-mt m-t-lg">
							<p>温馨提示：</p>
							<p>1. 提示信息 ：平台检测到用户为黑名单后，给出的相应提示，不可为空</p>
							<p>2. MAC 黑名单：终端设备MAC地址符合黑名单规范则禁止访问网络，黑名单名称: 50:E5:49:66:D1:E1（字母不区分大小写）</p>
							<p>3. 手机黑名单：终端以短信验证码形式认证，识别到黑名单手机号码后禁止获取验证码</p>
						</div> 
						<div class="line line-dashed line-lg pull-in"></div>              
					</form>
					<div class="actions m-t">
						<button type="button" class="btn btn-default btn-sm btn-next"  data-wizard="next" data-last="Finish">确认修改</button>
					</div>					
				</div>
			</section>				
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>	
<link rel="stylesheet" type="text/css" href="/res/js/datetimepicker/jquery.datetimepicker.min.css">
<script src="/res/js/datetimepicker/jquery.datetimepicker.full.min.js" cache="false"></script>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">

$(function (){

	$('.blacktype').change(function (){
		if($(this).val() == 'p'){
			$('input[name=content]').attr('data-parsley-type', "number")
				.attr('data-parsley-length', "[11, 13]");
		}else{
			$('input[name=content]').removeAttr('data-parsley-type');
			$('input[name=content]').attr('data-parsley-length', "[17,17]");
		}
	});
	$('.btn-next').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}	
		zy.send_sync_ajax('/corp/wboptions/blackedit', $('form').serialize(), function (data){
			showTipMessageDialog(data.reson,data.state, "Message", "/corp/authblacklist");
		});
	});

	$('#dt1').datetimepicker({
		timepicker:false,
		format:'d.m.Y'
	});


	if($('.blacktype').val() == "p"){
		$('input[name=content]').attr('data-parsley-type', "number").attr('data-parsley-length', "[11, 13]");
	}

});
G.set('nav_name', 'authblacklist');
</script>
</body>
</html>