<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
?>
<section id="content">
	<section class="vbox">
		<header class="header bg-white b-b b-light"><p>功能配置</p></header>
		<section class="scrollable wrapper " data-initialize="wizard">
			<div class="main text-center wrapper-xl">
				<div class="m-t b-a wrapper-lg line-dashed text-left l-h-2x row">
					<p>1. 无线WIFI认证平台,是指为您的连锁经营机构提供统一的WIFI认证和广告营销的管理平台。</p>
					<p>2. 以接入点为主体, 每个接入点代表一台设备或一组设备，采用树状结构设计。每个接入点仅需根据以下步骤即可完成配置。</p>
					<p>3. 实现的关联关系如下图，要进行配置请点击开始配置。</p>
				</div>
				<div class="m-t-xl"><img src="/res/images/bg1.jpg"/></div>
				<p><a href="/corp/apauthtype" class="btn btn-default btn-danger dker m-t-xl btn-config">开始配置</a></p>
			</div>
		</section>
	</section>
</section>
<?php  include ('footer.php'); ?>	
<script type="text/javascript">
G.set('nav_name', 'aplist');
</script>
</body>
</html>