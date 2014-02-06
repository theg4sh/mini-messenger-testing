function ajax_query(action, data, cb_function)
{
	var dataType = (typeof cb_function == 'string') ? 'jsonp' : 'json';
	var cb       = (typeof cb_function == 'string') ? function () { } : cb;

	if (typeof data == 'string')
	{
		data = 'q=' + action + 
			((typeof cb_function == 'string') ? ('&callback=' + cb_function) : '') +
			'&' + (('string' == typeof(data)) ? data : $(data).serialize());
	}
	else
	{
		data.q = action;
		data.callback = ((typeof cb_function == 'string') ? (cb_function) : null);
	}

	$.ajax({
		url: '/',
		data: data,
		dataType: dataType,
		success: function (response) {
			cb(response);
		},
		error: function () {

		}
	});
}

function flash_message(type, msg)
{
	if (typeof msg == 'string')
	{
		alert(msg);
	}
	else if (msg && msg.message)
	{
		alert(msg.message);
	}
	else
	{
		alert('Необработанная ошибка');
	}
}

var Modal = {
	current: null,
	timeout: 300,
}
Modal.open = function (id) {
	if (this.current)
	{
		Modal.close();
	}
	this.current = id;
	$(id).css({ opacity: 0 }).removeClass('hidden')
		.animate({ opacity: 1 }, this.timeout);
};
Modal.close = function () {
	if (this.current)
	{
		$(this.current).animate({ opacity: 0 }, this.timeout, function () { $(this).addClass('hidden'); });
		this.current = null;
	}
}

/*
 * User 
 */
var User = {};
User.login = function () {
	ajax_query('user/login', {
		username: $('#User_username').val(),
		password: $('#User_password').val(),
	}, 'User.login_cb');
}

User.login_cb = function (response) {
	if (response && response.success)
	{
		window.location.reload(true);
	}
	else
	{
		// Show flash
		flash_message('error', response);
	}
}

User.logout = function () {
	ajax_query('user/logout', {}, 'User.login_cb');
}

/**
 * WALL
 */
var Wall = {};
Wall.removeMessage = function (id) {
	ajax_query('message/remove', { id: id }, 'Wall.removeMessage_cb');
}
Wall.removeMessage_cb = function (response) {
	if (response && response.id)
	{
		$('.msg.msg-' + response.id).addClass('removing').animate({ height: 0, opacity: 0 }, 300, function () { $(this).remove() });
	}
	else
	{
		flash_message('error', response);
	}
}
Wall.pushMessage = function (msg) {
	$('.wall .wall-empty').addClass('hidden');
	$('.wall .inner').prepend(msg);
}
Wall.sendMessage_cb = function (response) {
	if (response && response.success)
	{
		$('#sendMessage .message').val('').focus();
		var msg = $(response.html);
		msg.find('.remove').click(function () { Wall.removeMessage(response.id); });
		Wall.pushMessage(msg);
	}
	else
	{
		flash_message('error', response);
	}
}
Wall.sendMessage = function () {
	var m = $('#sendMessage').serialize();
	ajax_query('message/send', m, 'Wall.sendMessage_cb');
}

/**
 * INIT
 */
function init()
{
	$('.modal').each(function (k, o) {
		$(o).find('.close').hover(
			function () { $(this).addClass('hover'); },
			function () { $(this).removeClass('hover'); }
		).click(function () { Modal.close(this); });
	});
	$('[modal]').each(function (k, o) {
		$(o).click(function () {
			Modal.open('#' + $(o).attr('modal'));
		});
	});
	$('#sendMessage .message').focus();
}
