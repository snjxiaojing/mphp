$(function(){
	$('#menu').hover(function(){
		$(this).css('bottom',0);
	}, function(){
		$(this).css('bottom','-123px');
	});
});

var cmt_del = function(id)
{
	console.log(id)
},

get_data = function()
{
	return {
		'title': 	$('#title').val(),
		'excerpt': 	$('#excerpt').val(),
		'content': 	$('#editor').val(),
		'cat_id': 	$('#cat').val(),
		'tag': 		$('#tag').val(),
		'passwd': 	$('#passwd').val(),
		'top_pic': 	$('#top_pic').val()
	};
},

add_cat = function(type,obj){
	var new_cat = $.trim($(obj).siblings('input[type="text"]').val());
	if(!new_cat)
	{
		return false;
	}
	$.post('/admin/ajax/cat_add', {'name':new_cat, type:type}, function(r){
		$(obj).siblings('select').append($('<option selected value="'+r+'">'+new_cat+'</option>'));
	});
},

add_submit = function()
{
	console.log(get_data())
	$.post('/admin/ajax/post_add', get_data(), function(r){
		r = eval('(' + r + ')');
		if(r.errno)
		{
			alert(r.msg);
		}
		else
		{
			alert('Succ...');
		}
	});
},

edit_submit = function(id)
{
	$.post('/admin/ajax/post_edit/' + id, get_data(), function(r){
		r = eval('(' + r + ')');
		if(r.errno)
		{
			alert(r.msg);
		}
		else
		{
			alert('Succ...');
		}
	});
},

tblog_del = function(id)
{

},

tblog_add = function() {
	$.post('/admin/ajax/tblog_add', {cnt:$('#tblog_cnt').val(), cid:$('#tblog_cat').val()}, function(r){
		console.log(r)
	})
},

window_show = function()
{
	$('#cover').show();
	$('#window').show();
},

window_hide = function()
{
	$('#window').hide();
	$('#cover').hide();
},

toQzoneLogin = function(url)
{
	childWindow = window.open(url,"TencentLogin","width=450,height=320,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");
}
;