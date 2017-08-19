<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include ("top.php");
?>
<section id="content">
	<section class="hbox stretch">
		<aside>
			<section class="vbox">
				<header class="header bg-white b-b clearfix">
					<div class="row m-t-sm">
						<div class="col-sm-8 m-b-xs">
							<div class="btn-group">		
								<a class="btn btn-sm btn-default m-r-sm refresh" ><i class="fa fa-refresh fa-lg m-r-sm"></i>刷新</a>
							</div>
						</div>
					</div>
				</header>
				
				<!-- device table --> 
				<section class="scrollable wrapper w-f">
					<div class="panel panel-default">
						<header class="panel-heading">短信内容添加</header>
						<div class="panel-body">
							<form class="form-horizontal" data-validate="parsley">
								<section>	
									<div class="form-group">
										<label class="col-sm-2 control-label">短信内容</label>
										<div class="col-sm-6">
											<textarea class="form-control parsley-validated" rows="6" data-minwords="15" data-required="true" placeholder="Type your message"></textarea>
										</div>
									</div>									
									<div class="line line-dashed line-lg pull-in"></div>
									<div class="form-group">
										<div class="col-sm-7 col-lg-offset-1 alert alert-danger">
											<p class="font-bold">说明：</p>
											<p>验证码内容最大64字(英文字符也算一个字)</p>
											<p>生成的验证码用{verifycode}表示</p>
											<p>内容示例：欢迎使用XX无线网络，你的短信验证码是{verifycode}，谢谢。</p>

										</div>
									</div>																		
									<div class="line line-dashed line-lg pull-in"></div>                                                                         
								</section>
							</form>
							<button type="button" class="btn btn-dark" data-target="#form-wizard" data-wizard="previous">添加</button>
						</div>
					</div>
				</section>
			</section>
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php include ('foot.php'); ?>
</body>
</html>