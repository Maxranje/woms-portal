<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include ('top.php');
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
								<a class="btn btn-sm btn-default m-r-sm btn-alloffline" ><i class="fa fa-power-off m-r-sm"></i>全部下线</a>
							</div>
						</div>
						<div class="col-sm-4 m-b-xs">
							<div class="input-group">
								<input type="text" class="input-sm form-control searchbox" placeholder="Search">		
								<span class="input-group-btn"><button class="btn btn-sm btn-default btn-search" type="button"><i class="fa fa-search"></i></button></span> 	
							</div>
						</div>
					</div>
				</header>

				<section class="scrollable wrapper">
					<table class="table table-striped " id="table"></table>
				</section>
			</section>
			<div id="tb" style="height:auto; padding: 5px 10px">
				<form class="form-inline" role="form">
					<div class="row">
						<div class="form-group col-sm-2">
							<select name="" class="form-control input-sm userstate">
								<option class="text-muted" value="all">状态</option>
								<option value="on" <?php if(isset($apid)) echo "selected='selected'";?> >在线</option>
								<option value="off">离线</option>
							</select>
						</div>
						<div class="form-group col-sm-2">
							<select name="" class="form-control input-sm userprotocol">
								<option class="text-muted" value="all">认证协议</option>
								<option value="w">Wifidog</option>
								<option value="p">Portal</option>
							</select>
						</div>
						<div class="form-group col-sm-2">
							<select name="" class="form-control input-sm apname">
								<option class="text-muted" value="all">接入点</option>
								<?php
								foreach ($ap as $row) {
									$select = isset($apid) && ($row['apid'] == $apid) ? "selected='selected'" :"";
									echo '<option value="'.$row['apid'].'" '.$select.'>'.$row['apname'].'</option>';
								}
								?>
							</select>
						</div>						
						<div class="form-group col-sm-2">
							<select name="" class="form-control input-sm usertype">
								<option class="text-muted" value="all">用户类型</option>
								<option value="n">一键认证</option>
								<option value="c">帐号密码验证</option>
								<option value="w">微信验证</option>
								<option value="s">手机短信验证</option>
								<option value="m">Mac白名单验证</option>
							</select>
						</div>
						<button class="btn btn-default btn-sm btn-filter">过滤</button>
					</div>				
				</form>
			</div>			
		</aside>
	</section>
	<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a> 
</section>
<?php  include ('footer.php'); ?>
<div class="modal fade" id="info">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">个人信息</h4></div>	
    		<div class="modal-body">
				<div class="m-b"> 
			        <a class="list-group-item"><i class="fa fa-user icon-muted m-r-sm"></i> <span>登录用户名称:</span><span class="pi_name m-l font-bold"></span> </a>
					<a class="list-group-item"><i class="fa fa-sitemap icon-muted m-r-sm"></i><span>登录验证类型:</span><span class="pi_type m-l font-bold"></span> </a>
					<a class="list-group-item"><i class="fa fa-sort icon-muted m-r"></i><span>终端&nbsp;&nbsp;IP&nbsp;&nbsp;地址:</span><span class="pi_ip m-l font-bold"></span> </a>
					<a class="list-group-item"><i class="fa fa-sort icon-muted m-r"></i><span>终端MAC地址:</span><span class="pi_mac m-l font-bold"></span> </a>					
					<a class="list-group-item"><i class="fa fa-clock-o icon-muted m-r-sm"></i><span>开始使用时间:</span> <span class="pi_starttime m-l font-bold"></span></a>
					<a class="list-group-item"><i class="fa fa-clock-o icon-muted m-r-sm"></i><span>结束使用时间:</span><span class="pi_endtime m-l font-bold"></span> </a>
					<a class="list-group-item"><i class="fa fa-retweet icon-muted m-r-sm"></i><span>上行带宽总量:</span><span class="pi_upbyteall m-l font-bold"></span> </a>
					<a class="list-group-item"><i class="fa fa-retweet icon-muted m-r-sm"></i><span>下行带宽总量:</span><span class="pi_downbyteall m-l font-bold"></span> </a>
					<a class="list-group-item"><i class="fa fa-retweet icon-muted m-r-sm"></i><span>当次上行带宽:</span><span class="pi_upbyte m-l font-bold"></span> </a>
					<a class="list-group-item"><i class="fa fa-retweet icon-muted m-r-sm"></i><span>当次下行带宽:</span><span class="pi_downbyte m-l font-bold"></span> </a>
				</div>
			</div>
		</div>
	</div>
</div>	
<link rel="stylesheet" href="/res/js/esgrid/themes/bootstrap/easyui.css" type="text/css" />
<link rel="stylesheet" href="/res/js/esgrid/themes/icon.css" type="text/css" />
<script src="/res/js/esgrid/jquery.easyui.min.js"></script>
<script type="text/javascript">
G.set('nav_name', 'authstatistics');

$(function (){
	$('.btn-search').on('click', function (){
		$('#table').datagrid('load',{sc: $('.searchbox').val()});
	});
	$('.btn-filter').on('click', function (){
		let state = $('.userstate').val();
		let protocol = $('.userprotocol').val();
		let type = $('.usertype').val();
		let apid = $('.apname').val();
		$('#table').datagrid('load',{state: state, protocol:protocol, type:type, apid:apid});
		return false;
	});

	$('.btn-alloffline').on('click', function (){
		$.post('/corp/useroptions/alloffline', function (data){
			if(data.state == "failed"){
				showTipMessageDialog (data.reson, data.state);
				return ;
			}
			window.location.reload(true);		
		}, "json");
	});

	initTables();
}); 
function initTables (){
	var columns=[
		{field:'uid', title:'ID', align:'center', hidden:true},
		{field:'tobeoffline', title:'', align:'center', hidden:true},
		{field:'uname', title:'用户', width:20, align:'center'},
		{field:'apname', title:'接入点', width:20, align:'center'},
		{field:'state', title:'状态', width:10, align:'center',formatter:function (value, row, index) {
			if(value == "1"){
				return '<a href="#" title="在线"><i class="fa fa-circle text-success"></i></a>';	
			}else{
				return '<a href="#" title="离线,'+value+'"><i class="fa fa-circle text-danger"></i></a>';	
			}
		}},
		{field:'type', title:'登录类型', width:25,align:'center'},
		{field:'protocol', title:'认证类型', width:20,align:'center'},
		{field:'hearttime', title:'心跳时间',width:30, align:'center', formatter:function(value, row, index) {
			return '<span class="text-dark"><i class="fa fa-heartbeat m-r-sm" aria-hidden="true"></i> ' + value+"</span>";
		}},
		{field:'totaltime', title:'总使用时间', width:20,align:'center'},
		{field:'more', title:'', width:6,align:'center', formatter:function (value, row, index) {
			return '<a class="showinfo th-sortable" title="详细信息" onclick="showinfo('+row.id+')"><i class="fa fa-eye text-dark"></i></a>';
		}},
		{field:'more1', title:'', width:6,align:'center', formatter:function (value, row, index) {
			var a = "";
			if(row.tobeoffline == 1){
				a += '<a class="th-sortable" style="cursor:default" title="强制下线中, 请等待"><i class="fa fa-ban text-danger"></i></a>'
			} else {
				a += '<a class="offline th-sortable" title="强制下线" onclick="offlineuser('+row.id+', event)"><i class="fa fa-power-off text-dark"></i></a>'
			}
			return a;
		}}		
	];
	createTable($('#table'), '/corp/useroptions/getuserlist', columns, true);
}


function offlineuser (id, e) {
	$.post('/corp/useroptions/offline', {id:id}, function (data){
		if(data.state == "failed"){
			showTipMessageDialog (data.reson, data.state);
			return ;
		}
		$($(e.target)[0]).removeClass('fa-power-off text-dark').addClass('fa-ban text-danger');
		$($(e.target)[0]).parent('.offline').removeAttr('onclick').attr('title', "强制下线中, 请等待");
	}, "json");
}

function showinfo (id, e) {
	$.post('/corp/useroptions/showinfo', {id:id}, function (data){
		if(data.state == "failed"){
			showTipMessageDialog (data.reson, data.state);
			return ;
		}
		$('#info').modal('show');
		$('.pi_name').html (data.info['name']);
		$('.pi_type').html (data.info['type']);
		$('.pi_mac').html (data.info['mac']);
		$('.pi_ip').html (data.info['ip']);
		$('.pi_starttime').html (data.info['starttime']);
		$('.pi_endtime').html (data.info['endtime']);
		$('.pi_upbyteall').html (data.info['allup']);
		$('.pi_downbyteall').html (data.info['alldown']);
		$('.pi_upbyte').html (data.info['userup']);
		$('.pi_downbyte').html (data.info['userdown']);

	}, "json");
}


</script>
</body>
</html>
