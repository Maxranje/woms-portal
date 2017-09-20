<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clearfix">
			<div class="row m-t-sm">
				<div class="col-sm-1 m-b-xs l-h-2x">
					<a class="btn btn-sm btn-default m-r-sm refresh"><i class="fa fa-refresh"></i></a>
				</div>
				<div class="m-t-xs">
					<?php
					if($state == "falied"){
						echo '<span class="label label-danger"><i class="fa fa-exclamation-triangle m-r" aria-hidden="true"></i>'.$reson.'</span>';	
					}
					?>
				</div> 				
			</div>		
		</header> 
		<section class="scrollable wrapper">
			<section class="panel panel-default">
				<header class="panel-heading font-bold">更改个人资料</header>
				<div class="panel-body">
					<form method="post" enctype="multipart/form-data"> 
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>商家昵称</label>
							<input type="text" class="form-control" data-parsley-required="true" name="nickname" value="<?=$cpt['nickname']?>">
						</div>
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>行业</label>
							<input type="text" class="form-control" data-parsley-required="true" name="industry" value="<?=$cpt['industry']?>">
						</div>
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>负责人姓名</label>
							<input type="text" class="form-control" data-parsley-required="true" name="name_manager" value="<?=$cpt['name_manager']?>">
						</div>
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>负责人电话</label>
							<input type="text" class="form-control" data-parsley-required="true" name ="phone" value="<?=$cpt['phone']?>">
						</div>	
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="form-control" data-parsley-type="email" name="email" value="<?=$cpt['email']?>">
						</div>
						<div class="form-group">
							<label>QQ</label>
							<input type="text" class="form-control" name="qq" value="<?=$cpt['qq']?>">
						</div>	
						<div class="form-group">
							<label>头像</label>
							<input type="file" id="toppic" style="display: none;" name="toppic">
							<div class="bootstrap-filestyle" >
								<input type="text" class="form-control inline input-s input-sm fortoppic" disabled=""> 
								<label for="toppic" class="btn btn-default btn-sm"><span>选择头像</span></label>
							</div>
						</div>
						<div class="form-group">
							<img src="/res/images/corplogo/<?=$cpt['logo']?>" class="thumb b-a">
						</div>																								
						<button type="submit" class="btn btn-sm btn-default btn-next m-t">保存</button>
					</form>
				</div>
			</section>
		</section>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>	
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
$(function (){

	$('#toppic').change(function (){
		$('.fortoppic').val($(this).val());
	});


	$('.btn-next').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}
		$('form').submit();
	});
});

</script>
</body>
</html>
