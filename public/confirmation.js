/*
 Ticketconverter
 (C) Copyright 2015 fashion4home GmbH <www.fashionforhome.de>
 Authors Christoph Jaecks, Claudia Hüttenrauch, Tino Stöckel

 license GPL-3.0
 */
var TicketConverter = TicketConverter || {};

TicketConverter.Confirmation = function(project, ids, formId)
{
	var _project = project || '';
	var _ids = ids || new Array();
	var _form = document.getElementById(formId) || {};
	var _toSubmit = new Array();

	this.confirm = function()
	{
		var length = _ids.length;

		for (var i = 0; i < length; i++) {
			var answer = confirm('Das Ticket ' + _project + '-' + _ids[i] + ' wurde bereits gedruckt. Soll es wirklich erneut gedruckt werden?');
			if(answer == true) {
				_toSubmit.push(_ids[i]);
			}
		}

		if(_toSubmit.length > 0) {
			this.submit(_project, _toSubmit);
		}
	};

	this.submit = function(project, ids)
	{
		var select = document.getElementById('project');
		var options = select.getElementsByTagName('option');
		var length = options.length;
		for (var i = 0; i < length; i++) {
			if(options[i].getAttribute('value') == project) {
				options[i].setAttribute('selected', 'selected');
			}
		}

		var textarea = document.getElementById('ids');
		textarea.value = ids.join(' ');

		_form.submit();
	}
};