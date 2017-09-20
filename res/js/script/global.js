var G = new Map ();

$(function (){

	$('.remove_submit').on('click', function (){
		var id = G.get('remove_id');	
		if(G.get('remove_url') == ""){
			showTipMessageDialog("no arguments with global");
			return ;
		}
		$.post(G.get('remove_url'), {id:id}, function (data){
			$('#remove').modal('hide');
			if(data.state == "success"){
				$('#table').datagrid('deleteRow',G.get('remove_index'));
			}
			showTipMessageDialog(data.reson, data.state);
		},"json");
	});

	$(document).on('click','.grid_item_edit', function (){
		let filed = $(this).parents('tr').find('.datagrid-cell-c1-id').html();
		let map = new Map().set('id',filed);
		zy.submit_form_action (G.get("edit_url"), map);
	});

	$(document).on('click','.grid_item_remove', function (){
		let filed = $(this).parents('tr').find('.datagrid-cell-c1-id').html();
		let index = $(this).parents('tr').attr('datagrid-row-index');
		G.set('remove_id', filed).set('remove_index', index);
		$('#remove').modal('show');
	});	

	$('.refresh').on('click',function (){ window.location.reload(true);});

	$(".btn-notice").on('click', function(){
		$('.btn-notice ~ .dropdown-menu').find('.list-group').html("");
		$.post('/corp/cptoptions/getsysnotice', function (data){
			if(data.state == "success"){
				for(let i = 0; i< data.size; i++){
					var $msg = '<a href="javascript:;" onclick="showNoticeMsg(this)" class="media list-group-item"> '+
						'<span class="pull-left thumb-sm text-center">'+
							'<i class="fa fa-envelope-o fa-2x text-success"></i>' + 
						'</span>' + 
						'<span class="media-body block m-b-none">' + 
							data.notice[i].title+'<br>' + 
							'<small class="text-muted">'+data.notice[i].addtime+'</small>' + 
						'</span>' + 
						'<span class="notice-content d-n">'+data.notice[i].content+'</span>' +
					'</a>';
					addMsg($msg);
				}
			}
		}, "json"); 
	});	
});


/**
 *  arguments :
 *  0: table dom
 *  1: url
 *  2: column
 *  3: fit
 *  4: queryparams
 */
function createTable (){
	if(arguments[0] == undefined){
		showTipMsgDialog ("系统列表异常");
	}
	arguments[0].datagrid({
		url:arguments[1],
		pagination:true,
		fitColumns:true,
		singleSelect:true,
		striped:true,
		idField:'id',
		pageList:[30,50,100],
		pageSize:50,
		fit: arguments[3], 
		queryParams:arguments[4],
		nowrap: true,
		scrollbarSize:0,
		columns:[arguments[2]],
		toolbar: '#tb'
	});
	$('.datagrid-pager.pagination').pagination({
		displayMsg:'数据从 {from} 到 {to}, 共 {total} 条数据'
	})	
}
function showTipMessageDialog(msg, state, title="提示信息", uri=""){
	if(state == "success"){
		msg = "<p class='text-primary text-left'>"+msg+"</p>";
	}else{
		msg = "<p class='text-danger text-left'>"+msg+"</p>";
	}
	if(uri != ""){
		$('.return-list').removeClass('d-n');
		$('.return-list').attr('href', uri);
	}
	$('#tip-msg .modal-title').html(title);
	$('#tip-msg .dialog-info').html(msg);
	$('#tip-msg').modal('show');
}

function showNoticeMsg(event){
	$('#notice .modal-body .msg').html($(event).children('.notice-content').html());
	$('#notice').modal('show');
}

function addMsg($msg) {
	var $el = $('.nav-user'), $n = $('.count:first', $el), $v = parseInt($n.text());
	$('.count', $el).fadeOut().fadeIn().text($v + 1);
	$($msg).hide().prependTo($el.find('.list-group')).slideDown().css('display', 'block');
}