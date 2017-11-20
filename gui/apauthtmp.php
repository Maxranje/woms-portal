 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b b-light"><p>功能配置 <i class="fa fa-angle-right m-l-sm m-r-sm"></i> 选择模版 </p></header>
		<section class="scrollable padder">
			<section class="panel panel-default no-border">
				<div class="panel-body no-padder">
					<div class="row template_rows">
					<?php
					if (empty($tmp)){
						echo '<p class="h4 text-danger text-center">暂无模版</p>';
					}else{
						for ($i=0;$i<count($tmp);$i++) {
							$row = $tmp[$i];
							if(($i+1) % 4 == 0){ echo '<div class="row template_rows">'; }
							$select = $row['id'] == $tid ? "checked='checked'" : "";
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
								if($row['state'] == 0){
									echo '<label>'.$row['name'].'</label>';
									echo '<a class="text-warning" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 未审核</a>';
								}else if($row['state'] == 2){
									echo '<label>'.$row['name'].'</label>';
									echo '<a class="text-danger" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 审核未通过</a>';
								}else{
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'" '.$select.'>&nbsp;&nbsp;'.$row['name'].'</label>';
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
								if($row['state'] == 0){
									echo '<label>'.$row['name'].'</label>';
									echo '<a class="text-warning" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 未审核</a>';
								}else if($row['state'] == 2){
									echo '<label>'.$row['name'].'</label>';
									echo '<a class="text-danger" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 审核未通过</a>';
								}else{
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'" '.$select.'>&nbsp;&nbsp;'.$row['name'].'</label>';
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
								if($row['state'] == 0){
									echo '<label>'.$row['name'].'</label>';
									echo '<a class="text-warning" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 未审核</a>';
								}else if($row['state'] == 2){
									echo '<label>'.$row['name'].'</label>';
									echo '<a class="text-danger" title="'.$row['reson'].'"><i class="fa fa-info-circle m-l" aria-hidden="true"></i> 审核未通过</a>';
								}else{
									echo '<label><input type="radio" name="tmpradio" class="m-r-sm" data-id="'.$row['id'].'" '.$select.'>&nbsp;&nbsp;'.$row['name'].'</label>';
								}	
								echo '</div></div>'; 												
							}	
							if(($i+1) % 4 == 0){ echo '</div>'; }
						}
					}
					?>
					</div>						
				</div>
			</section>				
			<a class="btn btn-default btn-sm m-r-sm btn-next" href="/corp/apauthassist">下一项</a>								
		</section>
	</section>
</section>
<?php  include ('footer.php'); ?>
<link rel="stylesheet" type="text/css" href="/res/css/template.css">
<script type="text/javascript">
G.set('nav_name', 'aplist');
$(function (){
	$('input[name=tmpradio]').on('click', function (){
		var map = new Map ();
		map.set ("id", $(this).data('id'));
		zy.submit_form_action ('/corp/apauthtmp', map);
	});
});
</script>
</body>
</html>