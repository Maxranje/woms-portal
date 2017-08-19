<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');

if($tmp['type'] != 3){
	$tp = !empty($tmp['phone_bgpic']) ? $tmp['phone_bgpic'] : 'top.jpg';
	$tmp['pc_adpic']    = json_decode($tmp['pc_adpic'], true);
	$tmp['phone_adpic'] = json_decode($tmp['phone_adpic'], true);
}else{
	$phone_bgpic = json_decode($tmp['phone_bgpic'], true);
	$pc_bgpic    = json_decode($tmp['pc_bgpic'], true);
	$tp = $phone_bgpic[0] == ""?($phone_bgpic[1]==""?($phone_bgpic[2]==""?"top.jpg":$phone_bgpic[2]):$phone_bgpic[1]):$phone_bgpic[0];
}
$type = $tmp['type'] == 1?"sp":($tmp['type'] ==2?"wp":"tp");
?>
<section id="content">
	<section class="hbox stretch">
		<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix h6">
					<p>模版配置 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 认证模版配置<i class="fa fa-angle-right m-l-sm m-r-sm"></i> 编辑认证模版 </p>
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
											<img src="/res/images/template/<?=$tp?>" style="height: 100%; width: 100%;">
										</div>
										<?php
										if($tmp['type'] == '2'){ echo '<div class="tmp-top-model"><p>'.$tmp['content'].'</p></div>'; }
										?>
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
								<form class="form-horizontal" method="post" action enctype="multipart/form-data">
									<p class="h5 m-b">模板名称</p>
									<div class="form-group col-sm-12">
										<input type="hidden" name="type" value="<?=$type?>">
										<input type="hidden" name="id" value="<?=$tmp['id']?>">
										<input type="text" class="form-control input-sm" readonly="readonly" name="name"  data-parsley-required="true" value="<?=$tmp['name']?>">
									</div>
									<div class="line line-dashed line-lg pull-in"></div>
									<p class="h5 m-b">页面标题</p>
									<div class="form-group col-sm-12">
										<input type="text" class="form-control input-sm" placeholder="标题名称, 默认: 欢迎使用无限热点" name="title" value="<?=$tmp['title']?>">
									</div>		
									<?php
									if($tmp['type'] == '2'){
										echo '<p class="h5 m-b">顶部文本</p>
											<div class="form-group col-sm-12">
												<input type="text" class="form-control input-sm" placeholder="默认: 欢迎使用无限热点" name="content" value="'.$tmp['content'].'">
											</div>';	
									}
									?>					
									
									<?php
									if($tmp['type'] != 3){
									?>
									<p class="h5 m-b m-t-lg">手机模版样式</p>
									<table class="tmp-table" >
										<tr>
											<td>手机背景图: </td>
											<?php
											if(empty($tmp['phone_bgpic'])){
												echo '<td class="show"><input type="file" name="phonebg"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$tmp['phone_bgpic'].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="phonebg">[删除]</a>
													</td>';
											}
											?>
											
										</tr>
										<tr>
											<td>手机广告图1: </td>
											<?php
											if(empty($tmp['phone_adpic'][0])){
												echo '<td class="show"><input type="file" name="phonead1"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$tmp['phone_adpic'][0].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="phonead1">[删除]</a>
													</td>';
											}
											?>										
										</tr>
										<tr>
											<td>手机广告图2: </td>
											<?php
											if(empty($tmp['phone_adpic'][1])){
												echo '<td class="show"><input type="file" name="phonead2"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$tmp['phone_adpic'][1].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="phonead2">[删除]</a>
													</td>';
											}
											?>	
										</tr>
										<tr>
											<td>手机广告图3: </td>
											<?php
											if(empty($tmp['phone_adpic'][2])){
												echo '<td class="show"><input type="file" name="phonead3"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$tmp['phone_adpic'][2].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="phonead3">[删除]</a>
													</td>';
											}
											?>	
										</tr>																														
									</table>
									<p class="h5 m-b m-t-lg">电脑模版样式</p>
									<table class="tmp-table">
										<tr>
											<td>PC背景图: </td>
											<?php
											if(empty($tmp['pc_bgpic'])){
												echo '<td class="show"><input type="file" name="pcbg"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$tmp['pc_bgpic'].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="pcbg">[删除]</a>
													</td>';
											}
											?>
										</tr>
										<tr>
											<td>PC广告图1: </td>
											<?php
											if(empty($tmp['pc_adpic'][0])){
												echo '<td class="show"><input type="file" name="pcad1"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$tmp['pc_adpic'][0].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="pcad1">[删除]</a>
													</td>';
											}
											?>
										</tr>
										<tr>
											<td>PC广告图2: </td>
											<?php
											if(empty($tmp['pc_adpic'][1])){
												echo '<td class="show"><input type="file" name="pcad2"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$tmp['pc_adpic'][1].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="pcad2">[删除]</a>
													</td>';
											}
											?>
										</tr>
										<tr>
											<td>PC广告图3: </td>
											<?php
											if(empty($tmp['pc_adpic'][2])){
												echo '<td class="show"><input type="file" name="pcad3"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$tmp['pc_adpic'][2].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="pcad3">[删除]</a>
													</td>';
											}
											?>
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
									<?php
									} else {
									?>
									<p class="h5 m-b m-t-lg">手机模版样式</p>
									<table class="tmp-table" >
										<tr>
											<td>手机背景图1: </td>
											<?php
											if(empty($phone_bgpic[0])){
												echo '<td class="show"><input type="file" name="phonebg1"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$phone_bgpic[0].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="phonebg1">[删除]</a>
													</td>';
											}
											?>
										</tr>
										<tr>
											<td>手机背景图2: </td>
											<?php
											if(empty($phone_bgpic[1])){
												echo '<td class="show"><input type="file" name="phonebg2"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$phone_bgpic[1].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="phonebg2">[删除]</a>
													</td>';
											}
											?>										</tr>
										<tr>
											<td>手机背景图3: </td>
											<?php
											if(empty($phone_bgpic[2])){
												echo '<td class="show"><input type="file" name="phonebg3"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$phone_bgpic[2].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="phonebg3">[删除]</a>
													</td>';
											}
											?>
										</tr>																													
									</table>
						


									<p class="h5 m-b m-t-lg">电脑模版样式</p>
									<table class="tmp-table">
										<tr>
											<td>PC背景图1: </td>
											<?php
											if(empty($pc_bgpic[0])){
												echo '<td class="show"><input type="file" name="pcbg1"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$pc_bgpic[1].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="pcbg1">[删除]</a>
													</td>';
											}
											?>
										</tr>
										<tr>
											<td>PC背景图2: </td>
											<?php
											if(empty($pc_bgpic[1])){
												echo '<td class="show"><input type="file" name="pcbg2"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$pc_bgpic[2].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="pcbg2">[删除]</a>
													</td>';
											}
											?>
										</tr>
										<tr>
											<td>PC背景图3: </td>
											<?php
											if(empty($pc_bgpic[2])){
												echo '<td class="show"><input type="file" name="pcbg3"></td>';
											}else{
												echo '<td class="show">
														<a href="/res/images/template/'.$pc_bgpic[2].'" target="_new" class="m-r-sm text-info">[查看]</a>
														<a href="javascript:;" class="m-r-sm text-info remove_img" data-name="pcbg3">[删除]</a>
													</td>';
											}
											?>
										</tr>																													
									</table>	
									<div class="line line-dashed line-lg pull-in"></div>
									<div class="alert alert-danger h6 font-bold l-h-1x">
										<p>温馨提示:</p>
										<p>1. 模版名称唯一, 如提示冲突请更改名称</p>
										<p>2. 手机端图片格式推荐: 375px * 667px</p>
										<p>3. 电脑端图片格式推荐: 1280px * 800px</p>
										<p>4. 总上传图片大小不得超过50MB</p>
										<p>5. 轮播图即为认证页面图片滚动播放</p>
									</div>																						
									<?php
									}
									?>
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

	$('.btn-next').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}
        var data = new FormData($('form')[0]);  
        $.ajax({  
            url: '/corp/tmpoptions/edittemplate',  
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

	$('.remove_img').on("click", function (){
		var flg = confirm("确定删除图片?");
		if(flg){
			var id 	 = $('input[name=id]').val();
			var type = $('input[name=type]').val();
			var img  = $(this).data('name');
			var marker = $(this);
			zy.send_sync_ajax ('/corp/tmpoptions/remtmpimg', {id:id, type:type, img:img}, function (data){
				if(data.state == "success"){
					marker.parents('td').html('<input type="file" name="'+img+'">');
				}else{
					showTipMessageDialog(data.reson, data.state);
				}
			});
		}
	});
});
var a ;
</script>
</body>
</html>