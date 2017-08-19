<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b clearfix">
			<div class="row m-t-sm"><div class="col-sm-8 m-b-xs l-h-2x">
				<a class="btn btn-sm btn-default m-r-sm refresh"><i class="fa fa-refresh"></i></a>
			</div></div>
		</header>        
		<section class="scrollable wrapper">
			<section class="panel panel-default">
				<header class="panel-heading font-bold">修改商户基本信息</header>
				<div class="panel-body">
					<form role="form">
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>商家昵称</label>
							<input type="hidden" name="cid" value="<?=$corp['cid']?>">
							<input type="text" class="form-control input-sm" data-parsley-required="true" name="nickname" value="<?=$corp['nickname']?>">
						</div>
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>登录帐号</label>
							<input type="text" class="form-control input-sm" data-parsley-required="true" name="cpun" value="<?=$corp['cpun']?>">
						</div>
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>登录密码</label>
							<input type="password" class="form-control input-sm" data-parsley-required="true" name="cppw" id="password">
						</div>		
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>确认密码</label>
							<input type="password" class="form-control input-sm" data-parsley-required="true" data-parsley-equalto="#password" >
						</div>																		
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>行业</label>
							<input type="text" class="form-control input-sm" data-parsley-required="true" name="industry"  value="<?=$corp['industry']?>">
						</div>
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>负责人姓名</label>
							<input type="text" class="form-control input-sm" data-parsley-required="true" name="name_manager"  value="<?=$corp['name_manager']?>">
						</div>
						<div class="form-group">
							<label><a class="text-danger m-r-sm">*</a>负责人电话</label>
							<input type="text" class="form-control input-sm" data-parsley-required="true" name ="phone"  value="<?=$corp['phone']?>">
						</div>	
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="form-control input-sm" data-parsley-type="email" name="email"  value="<?=$corp['email']?>">
						</div>
						<div class="form-group">
							<label>QQ</label>
							<input type="text" class="form-control input-sm" name="qq"  value="<?=$corp['qq']?>">
						</div>
					</form>
					<button type="submit" class="btn btn-sm btn-default btn-next m-t">Submit</button>
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
G.set('nav_name', 'allcooperater');


$('.btn-next').on('click', function (){
	if(!$('form').parsley().validate()){
		return false;
	}	
	zy.send_sync_ajax('/adc/corpmanage/editcorp', $('form').serialize(), function (data){
		
		if(data.state == "failed" ){
			showTipMessageDialog(data.reson, data.state);
		}else{
			window.location.href = "/adc/allcooperater";
		}
	});
});
</script>
</body>
</html>
