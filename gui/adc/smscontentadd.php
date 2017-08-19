<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>

<section id="content">
	<section class="vbox">
	<header class="header bg-white b-b clearfix">
		<div class="row m-t-sm">
			<div class="col-sm-8 m-b-xs">
				<div class="btn-group">		
					<a class="btn btn-sm btn-default m-r-sm refresh" ><i class="fa fa-refresh"></i></a>
					<!-- <a class="btn btn-sm btn-default m-r-sm " href="/corp/addap"><i class="fa fa-plus m-r-sm"></i>添加</a> -->
				</div>
			</div>					
		</div>
	</header>		
		<section class="scrollable wrapper">
			<section class="panel panel-default">
				<div class="panel-body">
					<form class="m-t">
						<div class="form-group">
							<label class="m-b">短信模版内容</label>
							<textarea class="form-control text-mt " rows="6" data-minwords="6" data-parsley-required="true"  data-parsley-length="[12,64]" name="smscontent" ></textarea>
						</div>
						<div class="line line-dashed line-lg pull-in"></div>   
						<div class="alert alert-danger font-bold m-t-lg h6">
							<p>温馨提示：</p>
							<p>1. 验证码内容最大64字 (英文字母、标点符号也算一个字)</p>
							<p>2. 生成的验证码用 {verifycode} 表示</p>
							<p>3. 内容示例：欢迎使用XX无线网络，你的短信验证码是{verifycode}，谢谢。</p>
						</div>
					</form>
					<div class="line line-dashed line-lg pull-in"></div> 
				</div>
				<div class="actions wrapper m-t-n-lg">
					<button type="button" class="btn btn-default btn-sm btn-next"  data-wizard="next" data-last="Finish">提交</button>
				</div>
			</section>
		</section> 
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('foot.php'); ?>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
$(function (){
	$('.btn-next').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}	
		zy.send_sync_ajax('/adc/smsmanage/addsmstmp', $('form').serialize(), function (data){
			showTipMessageDialog(data.reson, data.state);
		});
	});
});
G.set('nav_name', 'smscontentadd');
</script>
</body>
</html>