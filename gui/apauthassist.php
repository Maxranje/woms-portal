<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b b-light"><p>功能配置 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 辅助配置 </p></header>					
		<section class="scrollable wrapper">
			<form action="/corp/apoptions/assitconfig">
				<section class="panel panel-default m-t">
					<header class="panel-heading">开放时间</header>
					<div class="panel-body">	
						<input type="hidden" name="apid" value="<?=$apid?>">		
						<div class="radio">
							<label class="radio-custom"><input type="radio" name="openable" value="1" <?php if($openable == '1') echo "checked='checked'"; ?>><i class="fa fa-circle-o"></i> 开启 </label>
							<label class="radio-custom"><input type="radio" name="openable" value="0" <?php if($openable == '0') echo "checked='checked'"; ?>><i class="fa fa-circle-o m-l-xl"></i> 关闭 </label>
							<a class="m-l opentimetip"  href="#" data-toggle="tooltip" data-placement="right" data-html="true" data-original-title=""> 
							<i class="fa fa-question-circle" aria-hidden="true" style="font-size:14px;"></i> 
						</a>
						</div>
						<div class="form-group row m-t-md">
							<div class="col-lg-7">
								<div class="form-group">
									<label>开始时间</label>
									<div class="form-group row">
										<div class="col-sm-5">
											<input type="text" name="starttime" class="form-control input-sm" data-parsley-required="true" id="dt1">
										</div>
									</div>																
								</div>	
							</div>
							<div class="col-lg-7">
								<div class="form-group">
									<label>结束时间</label>
									<div class="form-group row">
										<div class="col-sm-5">
											<input type="text" name="endtime" class="form-control input-sm" data-parsley-required="true" id="dt2">
										</div>
									</div>																
								</div>	
							</div>							
						</div>
						<?php
						if($openable == "1" && !empty($opentime)){
							$tt = explode("-", $opentime);
							$tt[0] = date("H:i", $tt[0]);
							$tt[1] = date("H:i", $tt[1]);
							echo '<div class="m-t-lg m-b-lg">';
							echo '<p>已开启</p>';
							echo '<span class="label label-success">'.$tt[0]."-".$tt[1].'</span>';
							echo '</div>';
						}
						?>							
						<div class="alert alert-danger col-lg-6">
							<p>时间为24时， 时间格式参考：<span class="label label-danger">08:00-14:59</span>， 访客在此期间可认证上网</p>
						</div>
					</div>
				</section>
			</form>
			<div class="actions m-t">
				<a class="btn btn-default btn-sm btn-next m-r" >保存</a>
				<a class="btn btn-success btn-sm btn-return" href="/corp/aplist">返回列表</a>
			</div>
		</section>
	</section>
</section>
<?php  include ('footer.php'); ?>
<link rel="stylesheet" type="text/css" href="/res/js/datetimepicker/jquery.datetimepicker.min.css">
<script src="/res/js/datetimepicker/jquery.datetimepicker.full.min.js" cache="false"></script>
<link rel="stylesheet" type="text/css" href="/res/js/fuelux/fuelux.css">
<script type="text/javascript" src="/res/js/fuelux/fuelux.js"></script>
<script type="text/javascript">
G.set('nav_name', 'aplist');
$(function (){
	var html1 = '关闭: 全天开启, 无使用限制</br>开启:根据配置时间确定使用时间';
	$('.opentimetip').attr('data-original-title', html1);	

	$('input[name=openable]').change(function (){
		if($(this).val() == 0){
			$('.input-sm').attr('disabled', "disabled");
		}else{
			$('.input-sm').removeAttr('disabled');
		}
	});

	$('.btn-next').on('click', function (){
		var starttime = $('#dt1').val();
		var endtime = $('#dt2').val();
		var a1 = parseInt(starttime.split(':')[0]);
		var a2 = parseInt( endtime.split(':')[0]);
		var a3 = parseInt(starttime.split(':')[1]);
		var a4 = parseInt(endtime.split(':')[1]);

		if($('input[name=openable]:checked').val() == 1){
			if(starttime == "" || endtime == ""){
				alert('开始时间和结束时间不可以为空');
				return;
			}			
			if((a1 > a2) || (a1== a2 && a3>=a4)){
				alert("时间段格式不正确");
				return;
			}
		}
		var openable = $('input[name=openable]:checked').val();
		var apid = $('input[name=apid]').val();
		zy.send_sync_ajax ('/corp/apoptions/assitconfig', {t1:a1,t2:a2,t3:a3,t4:a4, endtime:endtime,apid:apid,openable:openable}, function(data){
			if(data.state == "success"){
				window.location.reload(true);
			}else{
				showTipMessageDialog (data.reson, data.state);
			}

		});
	});

	$('#dt1').datetimepicker({
		datepicker:false,
		format:'H:i'		
	});

	$('#dt2').datetimepicker({
		datepicker:false,
		format:'H:i'		
	});

	if($('input[name=openable]:checked').val() == 0){
		$('.input-sm').attr('disabled', "disabled");
	}

});
</script>	
</script>
</body>
</html>