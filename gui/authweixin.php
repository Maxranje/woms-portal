<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<header class="header bg-white b-b b-light">
		<p>微信配置 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 微信接入信息
			<?php
			if($state == "failed"){
				echo "<span class='font-bold text-danger'>".$reson."</span>";
			}
			?>
		</p>
	</header>	
	<section class="hbox stretch">
		<section class="vbox">
			<section class="scrollable wrapper">
				<section class="panel panel-default m-b">
					<header class="panel-heading bg-light">
						<ul class="nav nav-tabs bg-info dark ">
							<li class="active"><a href="#develep" data-toggle="tab">开发者模式</a></li>
							<li class=""><a href="#addplat"  data-toggle="tab">添加公众号</a></li>
						</ul>
					</header>
					
					<div class="panel-body">
						<div class="tab-content ">
							<div class="tab-pane active" id="develep">
								<div class="alert alert-info m-t-lg text-mt" >
									<div class="row">
										<div class="col-lg-10">
											<h4 class="m-b-lg font-bold">微信对接参数:</h4>
											<div>微信对接 URL: <span class="font-bold m-l-sm"><?=$url?></span></div>
											<div class="m-t">微信对接Token:<span class="font-bold m-l-sm"><?=$wx['wxtoken']?></span></div>
											<div class="m-t">微信公众号: <span class="font-bold m-l-sm"><?=$wx['wxaccount']?></span></div>
										</div>
										<div class="col-lg-2">
											<?php
											if(isset($wx['wxrqcode']) && !empty($wx['wxrqcode'])){
												echo '<img src="/res/images/wx/'.$wx['wxrqcode'].'?vr='.mt_rand(1111,9999).'" class="img-full b-a" />'; 
											}
											?>
										</div>
									</div>
								</div>
								<div class="line line-dashed line-lg pull-in"></div>
								<div class="weixinoption b-a r padder padder-v b-light ">
									<h4 class="m-b font-bold" style="color:#3A87AD">微信对接流程:</h4>
									<video class="video-js vjs-default-skin vjs-big-play-centered"
										controls preload="none" style="width:100%" height="450"
										data-setup="{}">
										<source src="/res/images/wx.mp4" type='video/mp4' />
										不支持HTML VEDIO播放器， 请下载最新版浏览器
									</video>
								</div>
							</div>                  		
							<div class="tab-pane m-b-lg" id="addplat">
								<form class="form m-t row" method="post" enctype="multipart/form-data">
									<div class="col-lg-6">								
										<div class="form-group">
											<label><a class="text-danger m-r-sm">*</a>公众号名称</label>
											<input type="text" class="form-control" data-parsley-required="true" name="wxaccount" value="<?=$wx['wxaccount']?>">
										</div>

										<div class="line line-dashed line-lg pull-in"></div>
										<div class="form-group">
											<label>微信链接标题</label>
											<input type="text" class="form-control" name="wxlinktitle" value="<?=$wx['wxlinktitle']?>" placeholder="推送给粉丝的提示标题">
										</div>
										<div class="line line-dashed line-lg pull-in"></div>
										<div class="form-group">
											<label>微信链接内容</label>
											<input type="text" class="form-control" name="wxlinkcontent" value="<?=$wx['wxlinkcontent']?>" placeholder="推送给粉丝的提示的具体内容">
										</div>
										<div class="line line-dashed line-lg pull-in"></div>
										<div class="form-group">
											<label>微信链接地址</label>
											<input type="text" class="form-control" name="wxlinkcontent" placeholder="" value="<?=$wx['wxlinkurl']?>" >
										</div>
										<div class="line line-dashed line-lg pull-in"></div>
										<div class="form-group">
											<label>公众号二维码</label>
											<div class="form-group">
												<input type="file" id="wxrqcode" style="display: none;" name="wxrqcode">
												<div class="bootstrap-filestyle" style="display: inline;">
													<input type="text" class="form-control inline input-s-lg" id="wxrqcodeview" disabled="" value="<?=$wx['wxrqcode']?>"> 
													<label for="wxrqcode" class="btn btn-default text-mt"><span>选择图片</span></label>
												</div>
											</div> 
										</div>										
										<div class="line line-dashed line-lg pull-in"></div>
										<div class="form-group">
											<label>微信链接图片</label>
											<div class="form-group">
												<input type="file" id="wxreplylogo" style="display: none;" name="wxlinklogo">
												<div class="bootstrap-filestyle" style="display: inline;">
													<input type="text" class="form-control inline input-s-lg" id="wxlinklogoview" disabled="" value="<?=$wx['wxlinklogo']?>"> 
													<label for="wxreplylogo" class="btn btn-default text-mt"><span>选择图片</span></label>
												</div>
											</div> 
										</div>
									</div>										
								</form>	
								<div class="line line-dashed line-lg pull-in"></div>							
								<button class="btn btn-sm btn-default btn-submit">保存</button>
							</div>
						</div>
					</div>
				</section>
			</section>
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>	
<link rel="stylesheet" href="/res/js/video/video.min.css" type="text/css" />
<script src="/res/js/video/video.min.js"></script>
<script src="/res/js/ajaxfileupload/ajaxfileupload.js"></script>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>

<script type="text/javascript">
G.set("remove_url", "/corp/tmpoptions/smstmpremove")
	.set('nav_name', 'authweixin');

$(function (){

$(document).on('change', '#wxrqcode', function (){
	$('#wxrqcodeview').val($(this).val());
});

$(document).on('change', '#wxlinklogo', function (){
	$('#wxlinklogoview').val($(this).val());
});

$('.btn-submit').on('click', function (){
	if(!$('form').parsley().validate()){
		return false;
	}
    var data = new FormData($('form')[0]);  
    $.ajax({  
        url: '/corp/wxoptions/updatewxconf',  
        type: 'POST',  
        data: data,  
        dataType: 'JSON',  
        cache: false,  
        processData: false,  
        contentType: false  
    }).done(function(data){  
    	showTipMessageDialog(data.reson, data.state, "提示", "");
    }); 
});

});

</script>
</body>
</html>