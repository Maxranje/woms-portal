<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
</section></section></section>
<div class="modal fade" id="tip-msg"><div class="modal-dialog"><div class="modal-content">
	<div class="modal-header "><h4 class="modal-title text-dark">提示信息</h4></div>	
    <div class="modal-body"><p class="dialog-info"></p></div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">关闭</button>
		<a class="btn btn-info btn-sm return-list d-n" href="">返回列表</a>
	</div>	
</div></div></div>

<div class="modal fade" id="remove"><div class="modal-dialog"><div class="modal-content">
	<div class="modal-header "><h4 class="modal-title text-dark">删除</h4></div>
	<div class="modal-body"><p class="text-danger">数据一旦删除则无法恢复，是否确定删除数据?  </p></div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default text-mt" data-dismiss="modal">关闭</button>
		<button type="button" class="btn btn-info text-mt remove_submit">确定</button>
	</div>
</div></div></div>
<script src="/res/js/jquery/jquery-1.11.2.min.js"></script>
<script src="/res/js/app.min.js"></script>
<script src="/res/js/fuelux/fuelux.js" cache="false"></script>
<script src="/res/js/script/common.js"></script>
<script src="/res/js/script/global.js"></script>
<script type="text/javascript">
$(function (){
	$('.'+G.get('nav_name')).parents('li').addClass('active');
});
</script>


