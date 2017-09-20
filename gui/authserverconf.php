<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b b-light">
			<p>功能配置 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 认证模式 </p>
		</header>					
		<section class="scrollable wrapper ">
			<form class="form-horizontal">
				<section class="panel panel-default m-t">
					<header class="panel-heading">LDAP认证服务器配置</header>
					<div class="panel-body">                               
						<form class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>LDAP服务器地址</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true"  placeholder="xxx.xxx.xxx.xxx" name="ldaphost" value="<?=$asc['ldaphost']?>">
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>							
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>LDAP服务器端口</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true"  placeholder="默认389" name="ldapport" value="<?=$asc['ldapport']?>">
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>							
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>LDAP服务器账户</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true" placeholder="" name="ldapusername" value="<?=$asc['ldapusername']?>">
								</div>
							</div>
							<div class="line line-dashed line-lg pull-in"></div>							
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>LDAP服务器密码</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true" placeholder="" name="ldapuserpass" value="<?=$asc['ldapuserpass']?>">
								</div>
							</div>	
							<div class="line line-dashed line-lg pull-in"></div>							
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>区别名(DN)</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true" placeholder="ou=xxx,ou=xxx,dc=xxx,dc=xxx,dc=xxx" name="ldapdn" value="<?=$asc['dn']?>">
								</div>
							</div>						
							<div class="line line-dashed line-lg pull-in"></div>							
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>获取名标识</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true" placeholder="用户属性标识， 如有多个请以逗号分割" name="ldapattr" value="<?=$asc['attr']?>" >
								</div>
							</div>								
							<div class="line line-dashed line-lg pull-in"></div>							
							<div class="form-group">
								<label class="col-sm-2 control-label"><a class="text-danger m-r-sm">*</a>通用名标识</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" data-parsley-required="true" placeholder="过滤检索对象" name="ldapfilter" value="<?=$asc['filter']?>">
								</div>
							</div>						
						</form>
					</div>
				</section>
				<button class="btn btn-default btn-sm m-r-sm btn-save">完成</button>
			</form>
		</section>
	</section>
</section>
<?php  include ('footer.php'); ?>	
<link rel="stylesheet" type="text/css" href="/res/js/fuelux/fuelux.css">
<script type="text/javascript" src="/res/js/fuelux/fuelux.js"></script>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
G.set('nav_name', 'authserverconf');
$(function (){
	$('.btn-save').on('click', function (){
		zy.send_sync_ajax('/corp/authoptions/ldapauth', $('form').serialize(), function (data){
			showTipMessageDialog(data.reson, data.state);
		});
	});
});
</script>
</body>
</html>
