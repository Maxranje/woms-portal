<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clear">
			<p>黑白名单 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 白名单<i class="fa fa-angle-right m-l-sm m-r-sm"></i> 添加白名单 </p>
		</header>		
		<section class="scrollable padder">
			<section class="panel panel-default m-t">
				<div class="panel-body">
					<form class="form-horizontal ">
						<div class="form-group" >
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>白名单类型</label>
							<div class="col-sm-5">
								<select class="form-control text-mt whitetype" name="whitetype"><option value="m">MAC 白名单</option></select>
							</div>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>白名单内容</label>
							<div class="col-sm-5"><input type="text" name="content" class="form-control" data-parsley-required="true" data-parsley-length="[17,17]"></div>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group" >
							<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>所属接入点</label>
							<div class="col-sm-5">
								<select name="ap" class="form-control text-mt">
									<option value="0">WIFIDOG协议接入点 </option>
									<?php
									foreach ($ap as $row) {
										echo "<option value='".$row['apid']."'>".$row['apname']."</option>";
									}
									?>									
								</select>
							</div>
						</div> 
						<div class="line line-dashed line-lg pull-in"></div>
						<div class="form-group" >
							<label class="col-sm-2 control-label">经过平台</label>
							<div class="col-sm-5">
								<div class="checkbox"><label class="checkbox-custom"><input type="checkbox" name="crossplat" value="o">
									<i class="fa fa-fw fa-square-o"></i><span class=" m-l h6 text-danger">( 开启后终端需看广告才可以上网 )</span> </label></div>			
							</div>
						</div>   
						<div class="line line-dashed line-lg pull-in"></div>									
						<div class="form-group">
							<label class="col-sm-2 control-label">备注</label>
							<div class="col-sm-5">
								<textarea class="form-control text-mt remark" rows="6" data-minwords="6" placeholder="备注信息" name="remark"></textarea>
							</div>
						</div> 
						<div class="line line-dashed line-lg pull-in"></div>	
						<div class="alert alert-info col-sm-12 m-t-lg">
							<p>温馨提示：</p>
							<p>1. 是否可经过平台:可经过平台的白名单可在平台统计登陆情况</p>
							<p>2. MAC白名单：不需要认证即可上网的用户设备，例如 50:E5:49:66:D1:E1（字母不区分大小写）</p>
						</div>               
						<div class="line line-dashed line-lg pull-in"></div>	
					</form>
					<div class="actions">
						<button type="button" class="btn btn-default btn-sm btn-next"  data-wizard="next" data-last="Finish">添加</button>
					</div>						
				</div>	
			</section>				
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>
<link rel="stylesheet" type="text/css" href="/res/js/fuelux/fuelux.css">
<script type="text/javascript" src="/res/js/fuelux/fuelux.js"></script>	
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">

$(function (){
$('.btn-next').on('click', function (){
	if(!$('form').parsley().validate()){
		return false;
	}	
	zy.send_sync_ajax('/corp/wboptions/addwhite', $('form').serialize(), function (data){
		showTipMessageDialog(data.reson,data.state, "提示信息", "/corp/authwhitelist");
	});
});

});
G.set('nav_name', 'authwhitelist');
</script>
</body>
</html>