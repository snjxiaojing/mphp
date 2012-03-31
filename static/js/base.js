var $ = function(x)
{
	var
	ie = !!(document.all),//top.execScript?1:0
	ie6 = ie && !window.XMLHttpRequest,
	ie8 = !!window.XDomainRequest,
	ie9 = ie && !-[1,],
	ie7 = ie && !ie6 && !ie8 && !ie9,
	xhr = ie6 ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
// if(ie7){alert7}
	/*FUNC*/
	$.in_array = function(val, arr)
	{
		for(v in arr)
		{
			if(val === arr[v])
			{
				return true;
			}
		}
		return false;
	};

	$.sleep = function(t)
	{
		var now = new Date();
		var exitTime = now.getTime() + t;
		while (true) {
			now = new Date();
			if (now.getTime() > exitTime)
				return;
		}
	}

	$.trim = function(s)
	{
		// return (s + '').replace(/(\s+)$/g , '').replace(/^\s+/g , '');
		return (s + '').replace(/(^\s*)|(\s*$)/g, "");
	};

	$.set_cookie = function(k,va)
	{
		var v = arguments;
		var n = arguments.length;
		var e = (n > 2) ? v[2] : (new Date(2089,7,31));
		var p = (n > 3) ? v[3] : '/';
		var d = (n > 4) ? v[4] : null;
		var s = (n > 5) ? v[5] : false;
		var c = k + '=' + escape(va)
				+ ((null == e) ? '' : ';expires=' + e.toUTCString())
				+ ((null == p) ? '' : ';path=' + p)
				+ ((null == d) ? '' : ';domain=' + d)
				+ ((true == s) ? ';secure' : '');
		document.cookie = c;
	};

	$.get_cookie = function(k)
	{
		var a = k + '=';
		var al = a.length;
		var c = document.cookie;
		var cl = document.cookie.length;
		var i = 0, j = 0;
		while(i < cl)
		{
			j = al + i;
			if(c.substring(i, j) == a)
			{
				var z = c.indexOf(';', i);
				z = (-1 == z) ? cl : z;
				return decodeURIComponent(c.substring(j, z));
			}
			i = c.indexOf(' ', i) + 1;
			if(0 == i)
			{
				break;
			}
		}
		return null;
	};

	$.del_cookie = function(k)
	{
		var exp = new Date();
		exp.setTime(exp.getTime() - 1);
		var v = $.get_cookie(k);
		if(null != v)
		{
			document.cookie = k + '=' + v + ';expires=' + exp.toGMTString();
		}
	};

	$.post = function(url, data, f, t)
	{
		if('undefined' == typeof(t))
		{
			t = 'json';
		}
		var info = '';
		for(i in data)
		{
			info += i + '=' + encodeURIComponent(data[i]) + '&';
		}
		if('' !== info)
		{
			info = info.slice(0, -1);
		}

		var x = xhr;
		x.open('POST', url);
		x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		x.onreadystatechange = function()
		{
			if(4 == x.readyState)
			{
				var r = x.responseText;
				if('json' == t)
				{
					f(eval('(' + r + ')'));
				}
				else
				{
					f(unescape(r));

				}
			}
		}
		x.send(info);

		return o;
	};

	$.get = function(url, f, t)
	{
		if('undefined' == typeof(t))
		{
			t = 'json';
		}
		var x = xhr;
		x.open('GET', url);
		x.onreadystatechange = function()
		{
			if(4 == x.readyState)
			{
				var r = x.responseText;
				if('json' == t)
				{
					f(eval('(' + r + ')'));
				}
				else
				{
					f(unescape(r));
				}
			}
		}
		x.send();
		return o;
	}

	$.sel = function ()
	{
		var s = {
			sel:ie?document.selection:window.getSelection()
		};
		s.rg = ie?s.sel.createRange():s.sel.getRangeAt(0);
		s.clear = function()
		{
			if(ie)
			{
				s.sel.clear();
			}
			else
			{
				s.rg.deleteContents();
			}
		};
		s.val = function(t)
		{
			if('undefined' == typeof(t))
			{
				return ie?s.rg.text:s.sel.toString;
			}
			else
			{
				s.clear();
				if(ie)
				{
					s.rg.text = t;
				}
				else
				{
					var fg = s.rg.createContextualFragment(t);
					var lastNode = fg.lastChild;
					s.rg.insertNode(fg);
					s.rg.setStartAfter(lastNode);
					s.rg.setEndAfter(lastNode);
					s.sel.removeAllRanges();
					s.sel.addRange(s.rg);
				}
			}
		};
		s.html = function(t)
		{
			if('undefined' == typeof(t))
			{
				return ie?s.rg.htmlText:s.sel.toString;
			}
			else
			{
				if(ie)
				{
					s.rg.pasteHTML(t);
				}
				else
				{
					s.val(t);
				}
			}
		};
		return s;
	}

	/*FUNC*/

	var o = null;

	switch(typeof(x))
	{
		case 'string':
			x = $.trim(x);
			switch(x.substr(0,1))
			{
				case '.':
					o = document.getElementsByClassName(x.substr(1))[0];
					break;
				case '#':
					o = document.getElementById(x.substr(1));
					break;
				default:
					o = document.getElementsByTagName(x)[0];
			}
			break;
		case 'object':
			o = x;
			break;
		case 'function':
			ie ?
			window.attachEvent('onload', x)
			: window.addEventListener('load',x,false);
			return false;
	};

	if(!o)
	{
		return false;
	}

	o.attr = function(a, v)
	{
		if('undefined' == typeof(v))
		{
			if('object' == typeof(a))
			{
				for(i in a)
				{
					o.setAttribute(i, a[i]);
				}
				return o;
			}
			else
			{
				return o.getAttribute(a);
			}
		}
		else
		{
			o.setAttribute(a, v);
			return o;
		}
	};

	o.val = function(v)
	{
		if('undefined' == typeof(v))
		{
			if('undefined' == typeof(o.value))
			{
				return o.innerText;
			}
			else
			{
				return o.value;
			}
		}
		else
		{
			if('undefined' !== typeof(o.value))
			{
				o.value = v;
				return o;
			}
			else
			{
				o.innerText = v;
				return o;
			}
		}
	};

	o.html = function(h)
	{
		if('undefined' == typeof(h))
		{
			return o.innerHTML;
		}
		else
		{
			o.innerHTML = h;
			return o;
		}
	};

	o.hasClass = function(s)
	{
		if('undefined' == typeof(o.className))
		{
			return false;
		}
		return o.className.match(new RegExp('(\\s+|^)' + s.replace(/\-/g, "\\-") + '(\\s+|$)'));
	};

	o.addClass = function(s)
	{
		if(!this.hasClass(s))
		{
			o.className += ' '+s;
		}
		return o;
	};

	o.removeClass = function(s)
	{
		var r = new RegExp('(\\s|^)' + s.replace(/\-/g, "\\-") + '(\\s|$)');
		o.className = o.className.replace(r, ' ');
		return o;
	};

	o.toggleClass = function(s)
	{
		if(o.hasClass(s))
		{
			o.removeClass(s);
		}
		else
		{
			o.addClass(s);
		}
		return o;
	}

	o.remove = function()
	{
		o.parentNode.removeChild(this);
		return null;
	};

	o.css = function(k,v)
	{
		if('string' == typeof(k))
		{
			o.style[k] = v;
		}
		else
		{
			for(i in k)
			{
				o.style[i] = k[i];
			}
		}
		return o;
	}

	o.bind = function(e, f)
	{
		ie ?
			o.attachEvent('on'+e, f)
			: o.addEventListener(e, f, false);
		return o;
	}

	o.keyup = function(f)
	{
		o.addEventListener('keyup', f, false);
		return o;
	}

	o.keypress = function(f)
	{
		// o.addEventListener('keypress', f, false);
		return o.bind('keypress', f);
	}

	o.click = function(f)
	{
		// o.addEventListener('click', f, false);
		return o.bind('click', f);
	}

	o.load = function(f)
	{
		// o.addEventListener('load', f, false);
		return o.bind('load', f);
	}

	o.blur = function(f)
	{
		// o.addEventListener('blur', f, false);
		return o.bind('blur', f);
	}

	o.copy = function()
	{
		return $(o.cloneNode(true));
	}

	o.append = function(d)
	{
		o.appendChild(d);
		return o;
	}

	o.appendTo = function(d)
	{
		d.appendChild(o);
		return o;
	}

	o.empty = function()
	{
		o.html('');
		return o;
	}

	o.remove = function()
	{
		o.parentNode.removeChild(o);
		return o;
	}

	o.offset = function()
	{
		var t = new Object;
		if(ie6 || ie7)
		{
			t.left = t.top = 0;
			var t_o = o;
			while(t_o!=null && t_o!=document.body)
			{
				t.left += t_o.offsetLeft;
				t.top += t_o.offsetTop;
				t_o = t_o.offsetParent;
			}
		}
		else
		{
			t.left = o.offsetLeft;
			t.top = o.offsetTop;
		}
		return t;
	}

	o.client = function()
	{
		var t = new Object;
		t.height = o.clientHeight;
		t.width = o.clientWidth;
		t.top=o.clientTop;
		return t;
	}

	o.scroll = function()
	{
		var t = new Object;
		t.height = o.scrollHeight;
		t.width = o.scrollWidth;
		return t;
	}

	return o;
};