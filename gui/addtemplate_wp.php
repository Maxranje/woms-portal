<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="hbox stretch">
		<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix h6">
					<p>模版配置 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 认证模版配置<i class="fa fa-angle-right m-l-sm m-r-sm"></i> 创建图文认证模版 </p>
				</header>
				
				<!-- device table --> 
				<section class="scrollable">

					<section class="hbox stretch ">
						<aside class="bg-white text-center">
							
							<div class="wrapper-lg"> 
								<section class="panel bg-dark inline no-border device phone animated fadeIn">
									<header class="panel-heading text-center text-white">
										<i class="fa fa-minus fa-2x icon-muted m-b-n-xs block"></i>
									</header>
									<div class="m-l-xs m-r-xs bg-white"  style=" width: 375px; position: relative;">
										<div style="height:527px">
											<img src="/res/images/template/top.jpg" style="height: 100%; width: 100%;">
										</div>		
										<div class="tmp-top-model">
											<p>欢迎使用无线热点上网</p>
										</div>																		
										<div class="tmp-bt-model">
											<button class="btn btn-success btn-block m-t-md m-b-md btn-sm" type="submit">验证上网</button>											
										</div>
									</div>
									<footer class="bg-dark text-center panel-footer no-border">&nbsp;</footer>
								</section>
							</div>
						</aside>

						<section>
							<div class="wrapper-lg">
								<form class="form-horizontal" method="post" enctype="multipart/form-data">
									<p class="h5 m-b">模板名称</p>
									<div class="form-group col-sm-12">
										<input type="hidden" name="type" value="wp">
										<input type="text" class="form-control input-sm" placeholder="模版名称" name="name"  data-parsley-required="true" data-parsley-length="[4, 32]">
									</div>
									<div class="line line-dashed line-lg pull-in"></div>
									<p class="h5 m-b">页面标题</p>
									<div class="form-group col-sm-12">
										<input type="text" class="form-control input-sm" placeholder="标题名称, 默认: 欢迎使用无限热点" name="title" value="">
									</div>	
									<p class="h5 m-b">顶部文本</p>
									<div class="form-group col-sm-12">
										<input type="text" class="form-control input-sm" placeholder="默认: 欢迎使用无限热点" name="content" value="">
									</div>																
									<p class="h5 m-b m-t-lg">手机模版样式</p>
									<table class="tmp-table" >
										<tr>
											<td>手机背景图: </td>
											<td><input type="file" name="phonebg"></td>
										</tr>
										<tr>
											<td>手机广告图1: </td>
											<td><input type="file" name="phonead1"></td>
										</tr>
										<tr>
											<td>手机广告图2: </td>
											<td><input type="file" name="phonead2"></td>
										</tr>
										<tr>
											<td>手机广告图3: </td>
											<td><input type="file" name="phonead3"></td>
										</tr>																														
									</table>
						


									<p class="h5 m-b m-t-lg">电脑模版样式</p>
									<table class="tmp-table">
										<tr>
											<td>PC背景图: </td>
											<td><input type="file" name="pcbg"></td>
										</tr>
										<tr>
											<td>PC广告图1: </td>
											<td><input type="file" name="pcad1"></td>
										</tr>
										<tr>
											<td>PC广告图2: </td>
											<td><input type="file" name="pcad2"></td>
										</tr>
										<tr>
											<td>PC广告图3: </td>
											<td><input type="file" name="pcad3"></td>
										</tr>																														
									</table>								
									<div class="line line-dashed line-lg pull-in"></div>

									<div class="alert alert-danger h6 font-bold l-h-1x">
										<p>温馨提示:</p>
										<p>1. 模版名称唯一, 如提示冲突请更改名称</p>
										<p>2. 手机端图片格式推荐: 375px * 667px</p>
										<p>3. 电脑端图片格式推荐: 1280px * 800px</p>
										<p>4. 总上传图片大小不得超过50MB</p>
									</div>
									<div class="line line-dashed line-lg pull-in"></div>
								</form>
								<div class="actions m-t">
									<button type="button" class="btn btn-default btn-sm btn-next" >上传</button>
								</div>	
							</div>					
						</section>

					</section>


				</section>
			</section>
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>

<?php  include ('footer.php') ?>
<link rel="stylesheet" type="text/css" href="/res/css/template.css">
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
G.set('nav_name', 'authtemplate');
$(function (){

	$('input[name=content]').on("change", function (){
		$('.tmp-top-model p').html($(this).val());
	});
	$('.btn-next').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}	
        var data = new FormData($('form')[0]);  
        $.ajax({  
            url: '/corp/tmpoptions/addtemplate',  
            type: 'POST',  
            data: data,  
            dataType: 'JSON',  
            cache: false,  
            processData: false,  
            contentType: false  
        }).done(function(data){  
        	showTipMessageDialog(data.reson, data.state, "提示", "/corp/authtemplate");
        }); 
	});
});
</script>
</body>
</html>