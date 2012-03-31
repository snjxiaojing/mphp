var reply_id = 0;
$(function(){
	if($.get_cookie('user'))
	{
		$('#top_nav_word').html('欢迎再次光临，' + $.get_cookie('user'));
	}

	if(1 == $.get_cookie('screen_type'))
	{
		$('#wrap').css('width','100%');
		$('#screem_n').css('color','#999');
	}
	else
	{
		$('#wrap').css('width','900px');
		$('#screem_w').css('color','#999');
	}

	$('#screem_w').click(function(){
		$('#wrap').css('width','100%');
		$('#screem_w').css('color','#000');
		$('#screem_n').css('color','#999');
		$.set_cookie('screen_type', 1);
	});
	$('#screem_n').click(function(){
		$('#wrap').css('width','900px');
		$('#screem_w').css('color','#999');
		$('#screem_n').css('color','#000');
		$.set_cookie('screen_type', 0);
	});

	var u = $('#username');
	if(u)
	{
		prettyPrint();
		var e = $('#email');
		var l = $('#url');

		u.val($.get_cookie('user')?$.get_cookie('user'):'');
		e.val($.get_cookie('email')?$.get_cookie('email'):'');
		l.val($.get_cookie('url')?$.get_cookie('url'):'');

		$.get('/ajax/pv', function(r){
			$('#pv').html(r);
		}, 'txt');

		var cmt_lock = false;
		var add_cmt = function()
		{
			if(cmt_lock)
			{
				return false;
			}
			cmt_lock = true;
			var n = $('#notice'), e_pat = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
				u_pat = /^(?:[\w-\.]{0,255})(?:(?:\/?[^\s]{0,255}){0,255})$/g;

			if('' == $.trim(u.val()))
			{
				n.val('请输入昵称').removeClass('fn-hide');
				u.focus();
				cmt_lock = false;
				return false;
			}
			var email = $.trim(e.val());
			if('' == $.email || !e_pat.test(email))
			{
				n.val('请输入正确格式的邮箱').removeClass('fn-hide');
				e.focus();
				cmt_lock = false;
				return false;
			}
			var url = $.trim(l.val());
			if('' != url && !u_pat.test(url))
			{
				n.val('请输入正确格式的url').removeClass('fn-hide');
				l.focus();
				cmt_lock = false;
				return false;
			}
			var t = $('#cmt_textarea');
			if('' == $.trim(t.html().replace(/&nbsp;/g,' ')))
			{
				n.val('请输入评论内容').removeClass('fn-hide');
				t.focus();
				cmt_lock = false;
				return false;
			}
			n.html('提交中...').removeClass('fn-hide');
			$.post('/ajax/add_cmt', {
				'pid'	: reply_id,
				'user'	: $.trim(u.val()),
				'email'	: email,
				'url'	: url,
				'cmt'	: $.trim(t.html())
			}, function(r){
				n.val('发表成功');
				ani(r, t);
				cmt_lock = false;
			}, 'json');
		}

		$('#submit').click(function(){
			add_cmt();
		});
		$('#cmt_form').keypress(function(e){
			e = e || window.event;
			if(10 == e.keyCode)
			{
				add_cmt();
			}
		});
	}
});

var emts = [];

var emt = function(o, n)
{
	if(3 == n)
	{
		$('#cmt_textarea').focus();
		var t = '', a=[], i=1;
		for(; i <=32; i++)
		{
			a.push(i);
		}
		var n = a[Math.floor(Math.random() * a.length + 1) - 1];
		if (1 == (n+'').length)
		{
			n = '0' + n;
		}
		n = [0,1,2][Math.floor(Math.random() * 3 + 1) - 1] + '' + n;
		$.sel().html('<img src="/static/img/system/emotion/' + n + '.gif">');
		$('#cmt_textarea').focus();
		return false;
	}
	if(!emts[n])
	{
		emts[n] = gen_emt(o, n);
	}
	d = emts[n];
	o = $(o);
	d.css({'top':o.offset().top - 100 + 'px', 'left':o.offset().left + 'px'}).removeClass('fn-hide').focus();
}

var gen_emt = function(o, n)
{
	var d = $('#emt').copy();
	var t = '';
	t += '<ul>';
	for (var i = 1; i <= 32; i++) {
		t += '<li><img src="/static/img/system/emotion/'+n;
		if(i < 10)
		{
			t += '0';
		}
		t += i+'.gif"></li>';
	}
	t += '</ul>';
	d.attr({'id':'','hidefocus':true,'tabIndex':-1}).html(t).appendTo(document.body).blur(function(){
		d.addClass('fn-hide');
	});
	var li = d.getElementsByTagName('li');
	for (var i = li.length - 1; i >= 0; i--) {
		$(li[i]).click(function(){
			$('#cmt_textarea').focus();
			if(window === this)
			{
				$.sel().html(this.event.srcElement.parentNode.innerHTML);
			}
			else
			{
				$.sel().html($(this).html());
			}
			d.addClass('fn-hide');
			$('#cmt_textarea').focus();
		});
	};
	return d;
}

var ani = function(r, d)
{
	if(r.p)
	{
		cancel();
	}
	var ul = $('#cmt_ul'), div = document.createElement('div'), str='', li=null, sh = 0, st = 0, dh = 0, dt = 0, th = 0, tt = 0, th2 = 0, o1 = 0, o2 = 0, t = null;
	str = '<li id="cmt_list_'+r.id+'" class="cmt_list" style="margin-left:0px;width:607px"><div id="cmt_anchor_'+r.p+'">'+(ul.children.length+1)+'.</div><div class="fn-right cmt_detail"><div class="cmt_detail_inner"><p><a id="cmt'+r.p+'" href="http://'+r.u+'" target="_blank" rel="nofollow">'+r.n+'</a><span class="ctime">['+r.t+']</span><span class="fn-right"><a href="#pre_cmt_div" name="cmt'+r.id+'" onclick="reply('+r.id+')">回复</a></span></p><div id="cmt_cnt_'+r.id+'">';
	if(r.pn)
	{
		str += '<a class="show_pcmt" href="#cmt_anchor_'+r.p+'" onmouseover="show_pcmt('+r.p+','+r.id+')" onmouseout="hide_pcmt()">@&nbsp;'+r.pn+'：</a>';
	}
	str += r.c+'</div></div></div><div class="fn-left avatar"><img src="'+r.a+'"></div></li>';
	div.innerHTML = str;
	li = $(div.children[0]);
	li2 = li.copy().appendTo(ul);
	dh = li2.client().height + 20;
	dt = li2.offset().top;
	li2.css({'height':'0px','padding':'0px','visibility':'hidden'});
	sh = d.client().height - 20;
	st = d.offset().top;
	li.css({'listStyle':'none','position':'absolute','left':d.offset().left + 'px','top':st + 'px','height':sh + 'px', 'width':d.client().width - 20 + 'px','opacity':0,'filter':'alpha(Opacity=0)'}).appendTo(document.body);
	t = setInterval(function(){
		if(!-[1,])
		{
			o2 = li.style.filter.match(/Opacity=([^)]*)/)[1] * 1;
			o2 += 100.0/50;
		}
		else
		{
			o1 = li.style.opacity * 1;
			o1 += 1.0/50;
		}
		li.css({'opacity':o1,'filter':'alpha(Opacity='+o2+')'});
		if(o1 >= 1)
		{
			clearInterval(t);
		}
	}, 3);
	setTimeout(function(){
		d.html('');
	}, 150);
	$.sleep(150);
	li2.css({'padding':10+'px'})
	t = setInterval(function(){
		tt = li.offset().top, th = li.client().height - 20, th2 = li2.client().height - 20;
		tt -= (st-dt)/20;
		th -= (sh-dh + 20)/20;
		th2 += dh/20;
		li.css({'top':tt+'px','height':th+'px'});
		li2.css({'height':th2+'px'});
	}, 20);
	setTimeout(function(){
		clearInterval(t);
		li.remove();
		li2.css({'height':dh-40+'px','visibility':''});
		$('#notice').addClass('fn-hide');
	}, 400);
}

var node = null;
function show_pcmt(pid, id)
{
	var pcmt = $('#cmt_list_'+pid);
	var cmt = $('#cmt_list_'+id);
	node = pcmt.copy();
	node.css({'boxShadow':'3px 3px 16px #000', 'borderColor':'red', 'position':'absolute', 'left':cmt.offset().left+150+'px', 'top':cmt.offset().top + 70 + 'px'});
	cmt.append(node);
}

function hide_pcmt()
{
	node.remove();
	node = null;
}

function reply(id)
{
	var cmt = $('#cmt_cnt_'+id);
	$('#pre_cmt_div').removeClass('fn-hide');
	$('#pre_cmt_cnt').html(cmt.html());
	reply_id = id;
	$('#cmt_textarea').focus();
}

function cancel()
{
	$('#pre_cmt_div').addClass('fn-hide');
	reply_id = 0;
}