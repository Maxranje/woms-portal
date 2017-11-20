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
						<div class="btn-group m-r-sm">
							<button class="btn btn-default  btn-sm dropdown-toggle" data-toggle="dropdown">添加 <span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li><a href="/corp/addsptemplate">添加单图片模板</a></li>
								<li><a href="/corp/addwptemplate"">添加图文模板</a></li>
								<li><a href="/corp/addtptemplate"">添加轮播图模板</a></li>
							</ul>
						</div>	
						<a class="btn btn-sm btn-default m-r-sm btn-remove m-r-sm"  ><i class="fa fa-trash m-r-sm"></i>删除</a>
						<a class="btn btn-sm btn-default m-r-sm btn-edit"  ><i class="fa fa-pencil m-r-sm"></i>编辑</a>
					</div>
				</div>
			</div>
		</header>		
		<section class="scrollable wrapper">
			<div class="btn-group"> 
				<a class="btn btn-default btn-sm btn-tmp <?php if($type=='all') echo "active"; ?>" href="/corp/authtemplate?type=all">全部模板</a>
				<a class="btn btn-default btn-sm m-l-sm btn-tmp <?php if($type=='glb') echo "active"; ?>" href="/corp/authtemplate?type=glb">公共模板</a>
				<a class="btn btn-default btn-sm m-l-sm btn-tmp <?php if($type=='cus') echo "active"; ?>" href="/corp/authtemplate?type=cus">自定义模板</a>
			</div>
			<section class="panel panel-default no-border">
				<div class="panel-body no-padder">
					<?php
					for ($i=0;$i<count($tmp);$i++) {
						$row = $tmp[$i];
						if(($i+1) % 4 == 0){ echo '<div class="row template_rows">'; }
						if($row['type'] == '1'){
							$tp = (!isset($row['phone_bgpic']) || empty($row['phone_bgpic'])) ? "top.jpg" : $row['phone_bgpic'];
							echo '<div class="col-lg-3 text-center m-t-md template ">
								<section class="panel bg-dark inline aside-lg no-border device phone animated fadeIn">
								<header class="panel-heading text-center text-white">
								<i class="fa fa-minus fa-2x icon-muted m-b-n-xs block"></i>
								</header>
								<div class="m-l-xs m-r-xs"><div class="carousel auto slide">
								<div class="carousel-inner" style="height:360px;">
								<div style="height:100%;">
								<img src="/res/images/template/'.$tp.'" style="height: 100%; width: 100%;">
								</div>
								<div class="tmp-bt-model">
								<button class="btn btn-success btn-block m-t-md m-b-md btn-sm" type="submit">验证上网</button>											
								</div>
								</div>
								</div>
								<footer class=" bg-dark text-center panel-footer no-border"></footer>
								</section>
								<div class="m-t-n-sm">';
							if($row['cid'] != '0'){
								if($row['state'] == 0){
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'">&nbsp;&nbsp;'.$row['name'].'</label>';
									echo '<a class="text-warning" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 未审核</a>';
								}else if($row['state'] == 2){
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'">&nbsp;&nbsp;'.$row['name'].'</label>';
									echo '<a class="text-danger" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 审核未通过</a>';
								}else{
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'">&nbsp;&nbsp;'.$row['name'].'</label>';	
								}										
							}else{
								echo '<label><input type="radio" style="visibility:hidden;">'.$row['name'].'</label>';
							}
							echo '</div></div>'; 									
						}
						if($row['type'] == '2'){
							$tp = (!isset($row['phone_bgpic']) || empty($row['phone_bgpic'])) ? "top.jpg" : $row['phone_bgpic'];
							echo '<div class="col-lg-3 text-center m-t-md template">
								<section class="panel bg-dark inline aside-lg no-border device phone animated fadeIn">
								<header class="panel-heading text-center text-white">
								<i class="fa fa-minus fa-2x icon-muted m-b-n-xs block"></i>
								</header>
								<div class="m-l-xs m-r-xs"><div class="carousel auto slide">
								<div class="carousel-inner" style="height:360px;">
								<div style="height:100%;">
								<img src="/res/images/template/'.$tp.'" style="height: 100%; width: 100%;">
								</div>
								<div class="tmp-top-model">
								<span>'.$row['content'].'</span>
								</div>													
								<div class="tmp-bt-model">
								<button class="btn btn-success btn-block m-t-md m-b-md btn-sm" type="submit">验证上网</button>											
								</div>
								</div>
								</div>
								<footer class=" bg-dark text-center panel-footer no-border"></footer>
								</section>
								<div class="m-t-n-sm">';
							if($row['cid'] != '0'){
								if($row['state'] == 0){
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'">&nbsp;&nbsp;'.$row['name'].'</label>';
									echo '<a class="text-warning" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 未审核</a>';
								}else if($row['state'] == 2){
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'">&nbsp;&nbsp;'.$row['name'].'</label>';
									echo '<a class="text-danger" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 审核未通过</a>';
								}else{
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'">&nbsp;&nbsp;'.$row['name'].'</label>';	
								}	
							}else{
								echo '<label><input type="radio" style="visibility:hidden;">'.$row['name'].'</label>';
							}
							echo '</div></div>'; 															
						}							
						if($row['type'] == '3'){
							$tp = json_decode($row['phone_bgpic'],true);
							$t = $tp[0] == ""?($tp[1]==""?($tp[2]==""?"top.jpg":$tp[2]):$tp[1]):$tp[0];
							echo '<div class="col-lg-3 text-center m-t-md template">
								<section class="panel bg-dark inline aside-lg no-border device phone animated fadeIn">
								<header class="panel-heading text-center text-white">
								<i class="fa fa-minus fa-2x icon-muted m-b-n-xs block"></i>
								</header>
								<div class="m-l-xs m-r-xs"><div class="carousel auto slide">
								<div class="carousel-inner" style="height:360px;">
								<div style="height:100%;">
								<img src="/res/images/template/'.$t.'" style="height: 100%; width: 100%;">
								</div>
								<div class="tmp-center-model">
								<button class="btn btn-success btn-block m-t-md m-b-md btn-sm" type="submit">验证上网</button>											
								</div>
								</div>
								</div>
								<footer class=" bg-dark text-center panel-footer no-border"></footer>
								</section>
								<div class="m-t-n-sm">';
							if($row['cid'] != '0'){
								if($row['state'] == 0){
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'">&nbsp;&nbsp;'.$row['name'].'</label>';
									echo '<a class="text-warning" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 未审核</a>';
								}else if($row['state'] == 2){
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'">&nbsp;&nbsp;'.$row['name'].'</label>';
									echo '<a class="text-danger" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 审核未通过</a>';
								}else{
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'">&nbsp;&nbsp;'.$row['name'].'</label>';	
								}	
							}else{
								echo '<label><input type="radio" style="visibility:hidden;">'.$row['name'].'</label>';
							}
							echo '</div></div>'; 														
						}
						if(($i+1) % 4 == 0){echo '</div>';}
					}
					?>
					</div>						
				</div>
			</section>				
		</section>
	</section>
	<!-- <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>  -->
</section>
<?php  include ('footer.php'); ?>	
<div class="modal fade" id="removetmp"><div class="modal-dialog"><div class="modal-content">
	<div class="modal-header"><h4 class="modal-title">删除模版</h4></div>
		<div class="modal-body"><p class="text-danger font-bold">是否确定删除?一旦删除无法恢复，且相关图片一同删除</p></div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default text-mt" data-dismiss="modal">关闭</button>
			<button type="button" class="btn btn-info text-mt remove_tmp_submit">确定</button>
		</div>
	</div>
</div></div>
<link rel="stylesheet" type="text/css" href="/res/css/template.css">
<script type="text/javascript">
G.set('nav_name', 'authtemplate');
$(function (){
	$('.btn-edit').on('click',function (){
		if($('input[name=tmpradio]:checked').length == 0){
			alert("至少选中一个模板");
			return;
		}
		var map = new Map();
		map.set("tid", $('input[name=tmpradio]:checked').data('id'));
		zy.submit_form_action ('/corp/edittemplate', map);
	});

	$('.btn-remove').on('click',function (){
		if($('input[name=tmpradio]:checked').length == 0){
			alert("至少选中一个模板");
			return;
		}
		$('#removetmp').modal('show');
	});	

	$('.remove_tmp_submit').on('click', function (){
		var id = $('input[name=tmpradio]:checked').data('id');
		var template = $('input[name=tmpradio]:checked').parents('.template');
		$('#removetmp').modal('hide');
		$.post('/corp/tmpoptions/removetmp', {id:id}, function (data){
			if(data.state == 'success'){
				window.location.reload(true);
			}else{
				showTipMessageDialog (data.reson, data.state);
			}		
		}, 'json');	
	});
});
</script>
</body>
</html>