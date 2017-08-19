/**
 * this file is common javascript
 * used for all plat
 * made by maxranje 
 */

if(typeof(zy) != 'undefined'){
	alert('Predefined variables already exist, please check...');
}
zy = new Object();

zy.submit_form_action = function (uri, map){
	try{
		let form = $('<form action="'+uri+'" method="post"></form>');
		map.forEach(function (value, key, map){
			form.append('<input name="'+ key +'" value="'+value+'">');
		});
		form.appendTo(document.body).submit();
	}
	catch(err){
		showTipMsgDialog ('Error: system error, please try agine');
	}
}

zy.send_sync_ajax = function (uri, data, callback) {
	$.ajax( { url:uri, data:data, type:'post', async:false, cache:false, 
		dataType:'json', success:callback, error: function (e){
			alert("Error: system error, please try agine");
		}
	});
}

zy.ajax_upload_file = function (uri, id, data,callback){
	$.ajaxFileUpload({ url:uri, type:'post',  fileElementId:id,secureuri:false,data:data, 
		dataType:'json', success:callback, error: function (e){
			alert("Error: system error, please try agine");
		}
	});
}