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
								<a class="btn btn-sm btn-default m-r-sm refresh" ><i class="fa fa-refresh"></i></a>
							</div>
						</div>
					</div>
				</header>
				
				<!-- device table --> 
				<section class="scrollable padder m-t">
					<div class="panel panel-default">
						<header class="panel-heading text-danger">添加系统公告</header>
						<div class="panel-body">
							<form class="form">
								<section>	
									<div class="form-group">
										<label><a class="text-danger m-r-sm">*</a>标题</label>
										<input type="text" class="form-control input-sm" data-parsley-required="true" name="title">
									</div>
									<div class="line line-dashed line-lg pull-in"></div>	
									<div class="form-group">
										<label><a class="text-danger m-r-sm">*</a>内容</label>
										<textarea class="form-control input-sm" data-parsley-required="true" name="msg" rows="6"></textarea>
									</div>
									<div class="line line-dashed line-lg pull-in"></div>	
								</section>
							</form>
							<button type="button" class="btn btn-default btn-sm btn-submit" >添加公告</button>	
						</div>	
					</div>
					<section class="scrollable">
						<section class="panel panel-default">
							<div class="table-responsive">
							<table class="table table-striped m-b-none">
								<thead><tr><th width="70">编号</th><th>标题</th><th>创建时间</th><th></th><th></th></tr></thead>									
								<tbody>
									<?php
									foreach ($notice as $row) {
										echo '<tr>';
										echo '<td class="nid">'.$row['id'].'</td>';
										echo '<td>'.$row['title'].'</td>';
										echo '<td width="110" >'.$row['addtime'].'</td>';
										echo '<td width="80" align="center"><a href="javascript:;" onclick="edititem(event)"><i class="fa fa-pencil"></i></a></td>';
										echo '<td width="80" align="center"><a href="javascript:;" onclick="removeitem(event)"><i class="fa fa-remove"></i></a></td>';
										echo '</tr>';
									}
									?>
											
								</tbody>
							</table>
							</div>
						</section>
					</section>											
				</section>
			</section>
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php include ('foot.php'); ?>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/i18n/zh_cn.js" cache="false"></script>
<script type="text/javascript">
$(function (){
	$('.btn-submit').on('click', function (){
		if(!$('form').parsley().validate()){
			return false;
		}	
		zy.send_sync_ajax('/adc/sysmanage/addsystemnotice', $('form').serialize(), function (data){
			if(data.state == "failed"){
				showTipMessageDialog(data.reson, data.state);
			}else {
				$('input[name=title]').val("");
				$('input[name=msg]').val("");
				initTables();
			}
		});
	});
});


G.set('nav_name', 'systemnotice');
</script>
</body>
</html>